<?php

use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use App\Support\Region;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    Region::forget();
    $this->seed(RoleSeeder::class);
    $this->admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);
    $this->theme = Theme::forceCreate([
        'name' => 'T', 'slug' => 'save-test', 'files' => [], 'is_active' => false, 'region' => Region::UK,
    ]);
});

it('saves page code sent base64-encoded, including script and Bangla text', function () {
    $content = "<script>el.innerHTML = 'hi';</script>\n<p>আমাদের টিম</p>";

    $this->actingAs($this->admin, 'web')
        ->putJson("/admin/themes/{$this->theme->id}/file/shop", ['content_b64' => base64_encode($content)])
        ->assertOk();

    expect($this->theme->fresh()->getFileContent('shop'))->toBe($content);
});

it('still accepts plain content', function () {
    $this->actingAs($this->admin, 'web')
        ->putJson("/admin/themes/{$this->theme->id}/file/about", ['content' => '<p>plain</p>'])
        ->assertOk();

    expect($this->theme->fresh()->getFileContent('about'))->toBe('<p>plain</p>');
});

it('rejects content that is not valid base64 text', function () {
    $this->actingAs($this->admin, 'web')
        ->putJson("/admin/themes/{$this->theme->id}/file/about", ['content_b64' => base64_encode("\xB1\x31 invalid utf8")])
        ->assertStatus(422);
});

it('requires one of the two fields', function () {
    $this->actingAs($this->admin, 'web')
        ->putJson("/admin/themes/{$this->theme->id}/file/about", [])
        ->assertStatus(422);
});
