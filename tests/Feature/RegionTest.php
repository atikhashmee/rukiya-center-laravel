<?php

use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Theme;
use App\Models\User;
use App\Support\Region;
use Database\Seeders\RoleSeeder;

function makeService(string $region, string $title): Service
{
    $category = ServiceCategory::withoutGlobalScopes()->where('region', $region)->first()
        ?? ServiceCategory::forceCreate(['name' => "Cat {$region}", 'slug' => "cat-{$region}", 'region' => $region]);

    $service = Service::factory()->make([
        'category_id' => $category->id,
        'title' => $title,
    ]);
    $service->region = $region;
    $service->save();

    return $service;
}

beforeEach(function () {
    Region::forget();   // let each request resolve its own region
});

afterEach(function () {
    Region::forget();
});

it('maps hostnames to regions', function () {
    expect(Region::fromHost('dkhealingcenter.com'))->toBe(Region::UK)
        ->and(Region::fromHost('www.dkhealingcenter.com'))->toBe(Region::UK)
        ->and(Region::fromHost('bd.dkhealingcenter.com'))->toBe(Region::BD)
        ->and(Region::fromHost('bd.localhost'))->toBe(Region::BD);
});

it('shows each region only its own catalog content', function () {
    makeService(Region::UK, 'UK Ruqyah');
    makeService(Region::BD, 'বাংলা রুকইয়াহ');

    Region::setCurrent(Region::UK);
    expect(Service::pluck('title')->all())->toBe(['UK Ruqyah']);

    Region::setCurrent(Region::BD);
    expect(Service::pluck('title')->all())->toBe(['বাংলা রুকইয়াহ']);

    // Admin screens clear the scope and see everything.
    Region::withoutScope(fn () => expect(Service::count())->toBe(2));
});

it('tags new records with the current region automatically', function () {
    Region::setCurrent(Region::BD);
    $service = makeService(Region::BD, 'Auto tagged');

    expect($service->fresh()->region)->toBe(Region::BD);
});

it('keeps settings separate per region', function () {
    Setting::set('whatsapp_number', '447000000000', Region::UK);
    Setting::set('whatsapp_number', '8801700000000', Region::BD);

    expect(Setting::get('whatsapp_number', null, Region::UK))->toBe('447000000000')
        ->and(Setting::get('whatsapp_number', null, Region::BD))->toBe('8801700000000');

    Region::setCurrent(Region::BD);
    expect(Setting::get('whatsapp_number'))->toBe('8801700000000');
});

it('activates themes per region without switching the other country off', function () {
    $uk = Theme::forceCreate(['name' => 'UK', 'slug' => 'uk-theme', 'files' => [], 'is_active' => true, 'region' => Region::UK]);
    $bd = Theme::forceCreate(['name' => 'BD', 'slug' => 'bd-theme', 'files' => [], 'is_active' => false, 'region' => Region::BD]);

    Region::withoutScope(fn () => $bd->activate());

    expect($uk->fresh()->is_active)->toBeTrue()
        ->and($bd->fresh()->is_active)->toBeTrue();
});

it('serves the matching region for the request host', function () {
    makeService(Region::UK, 'UK Service');
    makeService(Region::BD, 'BD Service');

    Region::forget();

    $this->get('http://dkhealingcenter.com/')->assertOk();
    expect(Region::current())->toBe(Region::UK);

    $this->get('http://bd.dkhealingcenter.com/')->assertOk();
    expect(Region::current())->toBe(Region::BD);
});

it('lets an admin switch the region filter', function () {
    $this->seed(RoleSeeder::class);
    $admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);

    $this->actingAs($admin, 'web')->post('/admin/region', ['region' => Region::BD])->assertRedirect();
    expect(session('admin_region'))->toBe(Region::BD);

    $this->actingAs($admin, 'web')->post('/admin/region', ['region' => 'nowhere'])->assertSessionHasErrors('region');
});

it('keeps a record in its region when the form sends no region', function () {
    $service = makeService(Region::BD, 'BD service');

    Region::withoutScope(function () use ($service) {
        $service->update(['title' => 'Renamed', 'region' => null]);
    });

    expect($service->fresh()->region)->toBe(Region::BD)
        ->and($service->fresh()->title)->toBe('Renamed');
});

it('moves a record to another region when asked', function () {
    $service = makeService(Region::UK, 'Moving service');

    Region::withoutScope(fn () => $service->update(['region' => Region::BD]));

    expect($service->fresh()->region)->toBe(Region::BD);
});

it('filters admin bookings and dashboard figures by the chosen region', function () {
    $this->seed(RoleSeeder::class);
    $admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);

    \App\Models\Booking::factory()->create(['region' => Region::UK]);
    \App\Models\Booking::factory()->create(['region' => Region::BD]);
    \App\Models\Booking::factory()->create(['region' => Region::BD]);

    // All regions
    $this->actingAs($admin, 'web')->get('/admin/bookings')
        ->assertInertia(fn ($page) => $page->has('bookings.data', 3));

    // Narrowed to Bangladesh
    $this->actingAs($admin, 'web')->post('/admin/region', ['region' => Region::BD]);
    $this->actingAs($admin, 'web')->get('/admin/bookings')
        ->assertInertia(fn ($page) => $page->has('bookings.data', 2));

    $this->actingAs($admin, 'web')->get('/admin/dashboard')
        ->assertInertia(fn ($page) => $page->where('stats.bookings.total', 2));
});

it('offers the other country in the public navigation', function () {
    $this->get('http://dkhealingcenter.com/')
        ->assertOk()
        ->assertSee('Bangladesh')
        ->assertDontSee('United Kingdom');

    $this->get('http://bd.dkhealingcenter.com/')
        ->assertOk()
        ->assertSee('United Kingdom')
        ->assertDontSee('Bangladesh');
});

it('never shows one country the other country services on public pages', function () {
    $ukCategory = ServiceCategory::withoutGlobalScopes()->where('region', Region::UK)->first()
        ?? ServiceCategory::forceCreate(['name' => 'UK Care', 'slug' => 'uk-care', 'region' => Region::UK]);
    $bdCategory = ServiceCategory::forceCreate(['name' => 'বাংলা সেবা', 'slug' => 'bangla-seba', 'region' => Region::BD]);

    Service::factory()->create(['category_id' => $ukCategory->id, 'title' => 'UK Only Service', 'region' => Region::UK]);
    Service::factory()->create(['category_id' => $bdCategory->id, 'title' => 'BD Only Service', 'region' => Region::BD]);

    Region::forget();

    $this->get('http://dkhealingcenter.com/book')
        ->assertOk()
        ->assertSee('UK Care')
        ->assertDontSee('বাংলা সেবা', false);

    $this->get('http://bd.dkhealingcenter.com/book')
        ->assertOk()
        ->assertSee('বাংলা সেবা', false)
        ->assertDontSee('UK Care');
});

it('saves the WhatsApp number against the region the admin is viewing', function () {
    $this->seed(RoleSeeder::class);
    $admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);

    $this->actingAs($admin, 'web')->post('/admin/region', ['region' => Region::BD]);
    $this->actingAs($admin, 'web')->put('/admin/settings/whatsapp', ['whatsapp_number' => '8801711111111']);

    $this->actingAs($admin, 'web')->post('/admin/region', ['region' => Region::UK]);
    $this->actingAs($admin, 'web')->put('/admin/settings/whatsapp', ['whatsapp_number' => '+44 7000 000000']);

    expect(Setting::get('whatsapp_number', null, Region::BD))->toBe('8801711111111')
        ->and(Setting::get('whatsapp_number', null, Region::UK))->toBe('447000000000');

    // The public site shows its own country's number.
    Region::forget();
    $this->get('http://bd.dkhealingcenter.com/')->assertSee('wa.me/8801711111111', false);
    $this->get('http://dkhealingcenter.com/')->assertSee('wa.me/447000000000', false);
});

it('creates and moves content between regions from the admin forms', function () {
    $this->seed(RoleSeeder::class);
    $admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);

    // Create a Bangladeshi service category through the admin form.
    $this->actingAs($admin, 'web')
        ->post('/admin/service-categories', [
            'name' => 'বাংলা কাউন্সেলিং',
            'region' => Region::BD,
        ])
        ->assertRedirect();

    $category = ServiceCategory::withoutGlobalScopes()->where('name', 'বাংলা কাউন্সেলিং')->firstOrFail();
    expect($category->region)->toBe(Region::BD)
        ->and($category->slug)->toBe('bangla-kaunseling'); // Bangla transliterates to a usable URL

    // It shows on the Bangladeshi site and not on the UK one.
    Region::setCurrent(Region::BD);
    expect(ServiceCategory::where('name', 'বাংলা কাউন্সেলিং')->exists())->toBeTrue();
    Region::setCurrent(Region::UK);
    expect(ServiceCategory::where('name', 'বাংলা কাউন্সেলিং')->exists())->toBeFalse();
    Region::forget();

    // Moving it to the UK from the edit form.
    $this->actingAs($admin, 'web')
        ->put("/admin/service-categories/{$category->id}", [
            'name' => $category->name,
            'slug' => $category->slug,
            'region' => Region::UK,
        ])
        ->assertRedirect();

    expect($category->fresh()->region)->toBe(Region::UK);
});
