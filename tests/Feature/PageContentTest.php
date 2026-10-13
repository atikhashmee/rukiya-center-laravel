<?php

use App\Models\PageContent;
use App\Models\Role;
use App\Models\User;
use App\Support\ContentSchema;
use App\Models\Theme;
use App\Support\Region;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Region::forget();
    $this->seed(RoleSeeder::class);
    $this->admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);

    // Mirror production: each region renders its own theme, built from the converted templates.
    foreach ([Region::UK => 'uk', Region::BD => 'bn'] as $region => $folder) {
        Theme::forceCreate([
            'name' => strtoupper($folder),
            'slug' => "test-{$folder}",
            'is_active' => true,
            'region' => $region,
            'files' => ['index' => file_get_contents(resource_path("views/Themes/{$folder}/index.blade.php"))],
        ]);
    }
});

function saveContent($test, string $page, array $values)
{
    return $test->actingAs($test->admin, 'web')
        ->put("/admin/content/{$page}", ['values_b64' => base64_encode(json_encode($values))]);
}

it('discovers the homepage fields from the template', function () {
    $fields = ContentSchema::forPage('index', Region::UK);

    expect($fields)->not->toBeEmpty()
        ->and(collect($fields)->pluck('key'))->toContain('hero_title', 'hero_button_primary', 'cta_title');

    $title = collect($fields)->firstWhere('key', 'hero_title');
    expect($title['type'])->toBe('html');
});

it('shows edited wording on the public site and keeps the template default otherwise', function () {
    // Before editing: the template's own wording.
    $this->get('http://dkhealingcenter.com/')->assertOk()->assertSee('Book a Ruqyah Session');

    saveContent($this, 'index', ['hero_button_primary' => 'Reserve Your Session'])->assertRedirect();

    Region::forget();
    $this->get('http://dkhealingcenter.com/')
        ->assertOk()
        ->assertSee('Reserve Your Session')
        ->assertDontSee('Book a Ruqyah Session')
        ->assertSee('Explore Our Services');   // untouched field still uses the default
});

it('keeps each country content separate', function () {
    saveContent($this, 'index', ['hero_button_primary' => 'UK wording'])->assertRedirect();

    // Switch the admin to Bangladesh and save different wording.
    $this->actingAs($this->admin, 'web')->post('/admin/region', ['region' => Region::BD]);
    saveContent($this, 'index', ['hero_button_primary' => 'বাংলা শব্দ'])->assertRedirect();

    Region::forget();
    $this->get('http://dkhealingcenter.com/')->assertOk()->assertSee('UK wording');
    $this->get('http://bd.dkhealingcenter.com/')->assertOk()->assertSee('বাংলা শব্দ', false);
});

it('resets a field back to the template wording', function () {
    saveContent($this, 'index', ['hero_button_primary' => 'Temporary'])->assertRedirect();

    $this->actingAs($this->admin, 'web')
        ->delete('/admin/content/index/reset', ['key' => 'hero_button_primary'])
        ->assertRedirect();

    Region::forget();
    $this->get('http://dkhealingcenter.com/')->assertOk()->assertSee('Book a Ruqyah Session');
});

it('ignores field keys that the template does not define', function () {
    saveContent($this, 'index', ['not_a_real_field' => 'nope'])->assertRedirect();

    expect(PageContent::where('key', 'not_a_real_field')->exists())->toBeFalse();
});

it('uploads an image for an image field', function () {
    Storage::fake('public');

    // The homepage has no image field yet, so prove the endpoint guards unknown keys.
    $this->actingAs($this->admin, 'web')
        ->post('/admin/content/index/image/hero_button_primary', ['image' => UploadedFile::fake()->image('x.jpg')])
        ->assertNotFound();
});

it('requires the right permissions', function () {
    $viewer = User::factory()->create([
        'role_id' => Role::create(['name' => 'viewer', 'label' => 'Viewer', 'permissions' => ['content.view'], 'is_system' => false])->id,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($viewer, 'web')->get('/admin/content')->assertOk();
    $this->actingAs($viewer, 'web')
        ->put('/admin/content/index', ['values_b64' => base64_encode(json_encode(['hero_button_primary' => 'x']))])
        ->assertForbidden();
});
