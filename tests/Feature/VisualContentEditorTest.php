<?php

use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use App\Support\ContentEditMode;
use App\Support\Region;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    Region::forget();
    ContentEditMode::forget();
    $this->seed(RoleSeeder::class);

    $this->admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);

    Theme::forceCreate([
        'name' => 'UK', 'slug' => 'test-uk', 'is_active' => true, 'region' => Region::UK,
        'files' => ['index' => file_get_contents(resource_path('views/Themes/uk/index.blade.php'))],
    ]);
});

it('leaves the public page untouched for ordinary visitors', function () {
    $response = $this->get('http://dkhealingcenter.com/');

    $response->assertOk()
        ->assertDontSee('cf-editable')
        ->assertDontSee('content-editor.js')
        ->assertSee('Book a Ruqyah Session');

    expect($response->getContent())->not->toContain("\u{2063}");   // no stray markers
});

it('marks the text editable when opened with a valid edit token', function () {
    $token = ContentEditMode::issueToken('index');

    $response = $this->get('http://dkhealingcenter.com/?'.ContentEditMode::PARAM.'='.$token);

    $response->assertOk()
        ->assertSee('class="cf-editable" data-cf-page="index" data-cf-key="hero_eyebrow"', false)
        ->assertSee('js/content-editor.js', false)
        ->assertSee('Book a Ruqyah Session');

    expect($response->getContent())->not->toContain("\u{2063}");   // markers all converted
});

it('ignores an unknown or expired token', function () {
    $this->get('http://dkhealingcenter.com/?'.ContentEditMode::PARAM.'=not-a-real-token')
        ->assertOk()
        ->assertDontSee('cf-editable');
});

it('opens the visual editor with a preview url for the right region', function () {
    $this->actingAs($this->admin, 'web')
        ->get('/admin/content/index/visual')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('content/visual')
            ->where('page.key', 'index')
            ->has('fields')
            ->where('previewUrl', fn ($url) => str_contains($url, ContentEditMode::PARAM.'=')));
});

it('tells the user when a page cannot be previewed on its own', function () {
    $this->actingAs($this->admin, 'web')
        ->get('/admin/content/shop-show/visual')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('content/visual')->where('previewUrl', null));
});

it('needs permission to open the visual editor', function () {
    $outsider = User::factory()->create([
        'role_id' => Role::where('name', 'instructor')->value('id'),
        'email_verified_at' => now(),
    ]);

    $this->actingAs($outsider, 'web')->get('/admin/content/index/visual')->assertForbidden();
});
