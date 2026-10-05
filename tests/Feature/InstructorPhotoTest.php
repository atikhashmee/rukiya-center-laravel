<?php

use App\Models\Instructor;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Support\Region;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Region::forget();
    Storage::fake('public');
    $this->seed(RoleSeeder::class);

    $this->admin = User::factory()->create([
        'role_id' => Role::where('name', Role::SUPER_ADMIN)->value('id'),
        'email_verified_at' => now(),
    ]);

    $category = ServiceCategory::forceCreate(['name' => 'Care', 'slug' => 'care', 'region' => Region::UK]);
    $this->service = Service::factory()->create(['category_id' => $category->id, 'region' => Region::UK]);
});

function instructorPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Shaykh Test',
        'title' => 'Senior Imam',
        'service_ids' => [test()->service->id],
        'region' => Region::UK,
    ], $overrides);
}

it('uploads a photo when creating an instructor', function () {
    $this->actingAs($this->admin, 'web')
        ->post('/admin/instructors', instructorPayload([
            'photo' => UploadedFile::fake()->image('shaykh.jpg', 400, 400),
        ]))
        ->assertRedirect();

    $instructor = Instructor::withoutGlobalScopes()->firstOrFail();

    expect($instructor->photo)->toStartWith('/storage/instructors/');
    Storage::disk('public')->assertExists(str_replace('/storage/', '', $instructor->photo));
});

it('keeps the existing photo when the form sends no file', function () {
    $instructor = Instructor::forceCreate(['name' => 'Keep', 'photo' => '/storage/instructors/old.jpg', 'is_active' => true, 'region' => Region::UK]);
    Storage::disk('public')->put('instructors/old.jpg', 'x');

    $this->actingAs($this->admin, 'web')
        ->put("/admin/instructors/{$instructor->id}", instructorPayload(['name' => 'Renamed']))
        ->assertRedirect();

    expect($instructor->fresh()->photo)->toBe('/storage/instructors/old.jpg')
        ->and($instructor->fresh()->name)->toBe('Renamed');
    Storage::disk('public')->assertExists('instructors/old.jpg');
});

it('replaces the photo and deletes the old file', function () {
    $instructor = Instructor::forceCreate(['name' => 'Replace', 'photo' => '/storage/instructors/old.jpg', 'is_active' => true, 'region' => Region::UK]);
    Storage::disk('public')->put('instructors/old.jpg', 'x');

    $this->actingAs($this->admin, 'web')
        ->put("/admin/instructors/{$instructor->id}", instructorPayload([
            'name' => 'Replace',
            'photo' => UploadedFile::fake()->image('new.png'),
        ]))
        ->assertRedirect();

    $fresh = $instructor->fresh();

    expect($fresh->photo)->not->toBe('/storage/instructors/old.jpg');
    Storage::disk('public')->assertMissing('instructors/old.jpg');
    Storage::disk('public')->assertExists(str_replace('/storage/', '', $fresh->photo));
});

it('removes the photo when asked', function () {
    $instructor = Instructor::forceCreate(['name' => 'Remove', 'photo' => '/storage/instructors/old.jpg', 'is_active' => true, 'region' => Region::UK]);
    Storage::disk('public')->put('instructors/old.jpg', 'x');

    $this->actingAs($this->admin, 'web')
        ->put("/admin/instructors/{$instructor->id}", instructorPayload(['name' => 'Remove', 'remove_photo' => true]))
        ->assertRedirect();

    expect($instructor->fresh()->photo)->toBeNull();
    Storage::disk('public')->assertMissing('instructors/old.jpg');
});

it('rejects a file that is not an image', function () {
    $this->actingAs($this->admin, 'web')
        ->post('/admin/instructors', instructorPayload([
            'photo' => UploadedFile::fake()->create('notes.pdf', 100, 'application/pdf'),
        ]))
        ->assertSessionHasErrors('photo');
});

it('shows the photo on the public team page', function () {
    Instructor::forceCreate([
        'name' => 'Pictured', 'photo' => '/storage/instructors/face.jpg', 'is_active' => true, 'region' => Region::UK,
    ]);

    Region::forget();

    $this->get('http://dkhealingcenter.com/team')
        ->assertOk()
        ->assertSee('/storage/instructors/face.jpg', false);
});
