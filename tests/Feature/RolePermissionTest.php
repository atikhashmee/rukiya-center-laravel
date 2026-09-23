<?php

use App\Models\Booking;
use App\Models\Instructor;
use App\Models\Role;
use App\Models\User;
use App\Support\Permissions;
use Database\Seeders\RoleSeeder;

function userWithRole(string $role, array $attributes = []): User
{
    return User::factory()->create([
        'role_id' => Role::where('name', $role)->value('id'),
        'email_verified_at' => now(),
        ...$attributes,
    ]);
}

beforeEach(function () {
    $this->seed(RoleSeeder::class);
});

it('gives the super admin every permission, including ones added later', function () {
    $user = userWithRole(Role::SUPER_ADMIN);

    expect($user->allPermissions())->toHaveCount(count(Permissions::all()))
        ->and($user->hasPermission('themes.manage'))->toBeTrue()
        ->and($user->hasPermission('anything.invented'))->toBeTrue();

    $this->actingAs($user, 'web')->get('/admin/users')->assertOk();
});

it('blocks a section the role cannot view', function () {
    $manager = userWithRole('manager');

    $this->actingAs($manager, 'web')->get('/admin/bookings')->assertOk();
    $this->actingAs($manager, 'web')->get('/admin/users')->assertForbidden();
    $this->actingAs($manager, 'web')->get('/admin/roles')->assertForbidden();
});

it('allows viewing but not managing when only the view permission is held', function () {
    $role = Role::create(['name' => 'read-only', 'label' => 'Read only', 'permissions' => ['bookings.view'], 'is_system' => false]);
    $user = User::factory()->create(['role_id' => $role->id, 'email_verified_at' => now()]);

    $booking = Booking::factory()->create();

    $this->actingAs($user, 'web')->get('/admin/bookings')->assertOk();
    $this->actingAs($user, 'web')->get("/admin/bookings/{$booking->id}/edit")->assertForbidden();
    $this->actingAs($user, 'web')->patch("/admin/bookings/{$booking->id}/status", ['booking_status' => 'confirmed'])->assertForbidden();
});

it('limits an instructor account to its own bookings', function () {
    $mine = Instructor::create(['name' => 'My Instructor', 'is_active' => true]);
    $theirs = Instructor::create(['name' => 'Other Instructor', 'is_active' => true]);

    $myBooking = Booking::factory()->create(['instructor_id' => $mine->id]);
    $otherBooking = Booking::factory()->create(['instructor_id' => $theirs->id]);

    $user = userWithRole('instructor', ['instructor_id' => $mine->id]);

    $this->actingAs($user, 'web')->get("/admin/bookings/{$myBooking->id}")->assertOk();
    $this->actingAs($user, 'web')->get("/admin/bookings/{$otherBooking->id}")->assertForbidden();
    $this->actingAs($user, 'web')->get("/admin/bookings/{$otherBooking->id}/edit")->assertForbidden();
});

it('only sends dashboard figures for sections the user may view', function () {
    $instructorUser = userWithRole('instructor');

    $this->actingAs($instructorUser, 'web')
        ->get('/admin/dashboard')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('stats.bookings')
            ->missing('stats.users')
            ->missing('stats.customers'));
});

it('keeps the last super admin from losing the role', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $managerRoleId = Role::where('name', 'manager')->value('id');

    $this->actingAs($admin, 'web')
        ->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role_id' => $managerRoleId,
        ])
        ->assertSessionHas('error');

    expect($admin->fresh()->isSuperAdmin())->toBeTrue();
});

it('creates, updates and protects roles from the roles screen', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);

    $this->actingAs($admin, 'web')
        ->post('/admin/roles', ['label' => 'Front Desk', 'permissions' => ['bookings.view', 'customers.view']])
        ->assertRedirect(route('roles.index'));

    $role = Role::where('label', 'Front Desk')->firstOrFail();
    expect($role->name)->toBe('front-desk')
        ->and($role->permissions)->toBe(['bookings.view', 'customers.view']);

    $this->actingAs($admin, 'web')
        ->put("/admin/roles/{$role->id}", ['label' => 'Front Desk', 'permissions' => ['bookings.view']])
        ->assertRedirect(route('roles.index'));

    expect($role->fresh()->permissions)->toBe(['bookings.view']);

    // A role still in use cannot be deleted.
    User::factory()->create(['role_id' => $role->id]);
    $this->actingAs($admin, 'web')->delete("/admin/roles/{$role->id}")->assertSessionHas('error');
    expect(Role::find($role->id))->not->toBeNull();
});

it('refuses to delete a system role and rejects unknown permissions', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->firstOrFail();

    $this->actingAs($admin, 'web')->delete("/admin/roles/{$superAdminRole->id}")->assertSessionHas('error');
    expect(Role::find($superAdminRole->id))->not->toBeNull();

    $this->actingAs($admin, 'web')
        ->post('/admin/roles', ['label' => 'Bad', 'permissions' => ['bookings.destroy-everything']])
        ->assertSessionHasErrors('permissions.0');
});

it('always keeps every permission on the super admin role, even if the form sends none', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $superAdminRole = Role::where('name', Role::SUPER_ADMIN)->firstOrFail();

    $this->actingAs($admin, 'web')
        ->put("/admin/roles/{$superAdminRole->id}", ['label' => 'Super Admin', 'permissions' => []]);

    expect($superAdminRole->fresh()->permissions)->toBe(Permissions::all());
});

it('renders the roles screens for a super admin', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $role = Role::where('name', 'manager')->firstOrFail();

    $this->actingAs($admin, 'web')->get('/admin/roles')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('roles/index')->has('roles')->has('permissionGroups'));

    $this->actingAs($admin, 'web')->get('/admin/roles/create')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('roles/create')->has('permissionGroups'));

    $this->actingAs($admin, 'web')->get("/admin/roles/{$role->id}/edit")
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('roles/edit')->where('role.name', 'manager'));

    $this->actingAs($admin, 'web')->get('/admin/users/create')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('users/create')->has('roles')->has('instructors'));
});

it('resolves the create pages instead of matching them as a record id', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);

    // Regression: registering show before create made /admin/x/create look up a record named "create".
    foreach (['products', 'product-categories', 'services', 'service-categories', 'customers', 'instructors', 'users', 'roles', 'blog', 'themes'] as $section) {
        $response = $this->actingAs($admin, 'web')->get("/admin/{$section}/create");

        expect($response->status())->toBe(200, "/admin/{$section}/create returned {$response->status()}");
    }
});

it('leaves no admin account without a role after migrating', function () {
    // RefreshDatabase has run every migration, including the role seeding one.
    expect(Role::whereIn('name', [Role::SUPER_ADMIN, 'manager', 'instructor'])->count())->toBe(3)
        ->and(User::whereNull('role_id')->count())->toBe(0);

    // A role-less account (created straight in the database) is locked out - this is what
    // production hit when the roles data was missing.
    $orphan = User::factory()->create(['role_id' => null, 'email_verified_at' => now()]);
    $this->actingAs($orphan, 'web')->get('/admin/dashboard')->assertForbidden();
});

it('grants a permission directly to one user on top of their role', function () {
    $user = userWithRole('instructor'); // role has no users.* permissions

    $this->actingAs($user, 'web')->get('/admin/users')->assertForbidden();

    $user->update(['permissions' => ['users.view']]);

    $this->actingAs($user->fresh(), 'web')->get('/admin/users')->assertOk();
    // Still no manage right: the direct grant was view only.
    $this->actingAs($user->fresh(), 'web')->get('/admin/users/create')->assertForbidden();

    expect($user->fresh()->hasPermission('users.view'))->toBeTrue()
        ->and($user->fresh()->allPermissions())->toContain('users.view', 'bookings.view');
});

it('saves and clears direct permissions from the user form', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $target = userWithRole('instructor');

    $this->actingAs($admin, 'web')->put("/admin/users/{$target->id}", [
        'name' => $target->name,
        'email' => $target->email,
        'role_id' => $target->role_id,
        'permissions' => ['orders.view', 'orders.manage'],
    ])->assertRedirect();

    expect($target->fresh()->permissions)->toBe(['orders.view', 'orders.manage']);

    // Submitting with no boxes ticked clears them again.
    $this->actingAs($admin, 'web')->put("/admin/users/{$target->id}", [
        'name' => $target->name,
        'email' => $target->email,
        'role_id' => $target->role_id,
    ])->assertRedirect();

    expect($target->fresh()->permissions)->toBe([])
        ->and($target->fresh()->hasPermission('orders.view'))->toBeFalse();
});

it('rejects an invented direct permission', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $target = userWithRole('manager');

    $this->actingAs($admin, 'web')->put("/admin/users/{$target->id}", [
        'name' => $target->name,
        'email' => $target->email,
        'role_id' => $target->role_id,
        'permissions' => ['everything.always'],
    ])->assertSessionHasErrors('permissions.0');
});

it('shares role and direct permissions with the frontend', function () {
    $user = userWithRole('instructor', ['permissions' => ['orders.view']]);

    $this->actingAs($user, 'web')->get('/admin/dashboard')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->where('auth.role.name', 'instructor'));

    expect($user->fresh()->allPermissions())->toContain('orders.view', 'bookings.view');
});

it('lets an admin assign and clear the instructor on a booking', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $instructor = Instructor::create(['name' => 'Assigned One', 'is_active' => true]);
    $booking = Booking::factory()->create(['instructor_id' => null]);

    $this->actingAs($admin, 'web')
        ->patch("/admin/bookings/{$booking->id}/instructor", ['instructor_id' => $instructor->id])
        ->assertSessionHas('success');

    expect($booking->fresh()->instructor_id)->toBe($instructor->id);

    $this->actingAs($admin, 'web')
        ->patch("/admin/bookings/{$booking->id}/instructor", ['instructor_id' => null])
        ->assertSessionHas('success');

    expect($booking->fresh()->instructor_id)->toBeNull();
});

it('stops an instructor account from handing its booking to someone else', function () {
    $mine = Instructor::create(['name' => 'Mine', 'is_active' => true]);
    $other = Instructor::create(['name' => 'Other', 'is_active' => true]);
    $booking = Booking::factory()->create(['instructor_id' => $mine->id]);
    $user = userWithRole('instructor', ['instructor_id' => $mine->id]);

    $this->actingAs($user, 'web')
        ->patch("/admin/bookings/{$booking->id}/instructor", ['instructor_id' => $other->id])
        ->assertForbidden();

    expect($booking->fresh()->instructor_id)->toBe($mine->id);
});

it('filters the bookings list by instructor and by unassigned', function () {
    $admin = userWithRole(Role::SUPER_ADMIN);
    $instructor = Instructor::create(['name' => 'Filter Target', 'is_active' => true]);

    Booking::factory()->create(['instructor_id' => $instructor->id]);
    Booking::factory()->create(['instructor_id' => null]);
    Booking::factory()->create(['instructor_id' => null]);

    $this->actingAs($admin, 'web')->get('/admin/bookings?instructor=unassigned')
        ->assertInertia(fn ($page) => $page->has('bookings.data', 2));

    $this->actingAs($admin, 'web')->get("/admin/bookings?instructor={$instructor->id}")
        ->assertInertia(fn ($page) => $page->has('bookings.data', 1)->has('instructors'));
});
