<?php

namespace Tests\Feature;

use App\Livewire\Membership\EditProfile;
use App\Livewire\Membership\MemberDirectory;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class MembershipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    private function classroom(string $suffix = 'A', bool $active = true): ClassModel
    {
        return ClassModel::create(['name' => 'Kelas '.$suffix, 'code' => 'TI-'.$suffix, 'join_code' => 'Join-'.$suffix, 'is_active' => $active]);
    }

    private function member(ClassModel $class, array $attributes = [], string $role = 'member'): User
    {
        $user = User::factory()->create($attributes);
        ClassMember::create(['class_id' => $class->id, 'user_id' => $user->id, 'role' => $role]);

        return $user;
    }

    public function test_private_pages_require_login_and_auth_pages_render(): void
    {
        foreach (['/', '/profile', '/members', '/feed', '/events', '/gallery'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
        foreach (['/login', '/register', '/forgot-password'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_registration_creates_account_and_member_atomically_without_accepting_role(): void
    {
        $class = $this->classroom();
        $this->post('/register', [
            'name' => 'Nara', 'email' => 'NARA@example.com', 'password' => 'Cerita12345',
            'password_confirmation' => 'Cerita12345', 'join_code' => $class->join_code, 'role' => 'admin',
        ])->assertRedirect('/profile');
        $user = User::where('email', 'nara@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('Cerita12345', $user->password));
        $this->assertDatabaseHas('class_members', ['user_id' => $user->id, 'class_id' => $class->id, 'role' => 'member']);
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id, 'profile_visibility' => 'class_only', 'birth_year_visible' => false]);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_invalid_or_inactive_invitation_cannot_create_account(): void
    {
        $this->classroom('A', false);
        foreach (['wrong', 'Join-A'] as $code) {
            $this->post('/register', [
                'name' => 'Nara', 'email' => 'nara@example.com', 'password' => 'Cerita12345',
                'password_confirmation' => 'Cerita12345', 'join_code' => $code,
            ])->assertSessionHasErrors('join_code');
        }
        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('class_members', 0);
        $this->assertGuest();
    }

    public function test_login_logout_and_rate_limiting(): void
    {
        $user = $this->member($this->classroom(), ['password' => 'Cerita12345']);
        $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/login', ['email' => $user->email, 'password' => 'Cerita12345'])->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong']);
        }
        $this->post('/login', ['email' => $user->email, 'password' => 'Cerita12345'])
            ->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_account_without_membership_can_join_and_duplicate_join_is_safe(): void
    {
        $user = User::factory()->create();
        $class = $this->classroom();
        $this->actingAs($user)->get('/')->assertRedirect('/join');
        $this->post('/join', ['join_code' => $class->join_code, 'role' => 'admin'])->assertRedirect('/');
        $this->post('/join', ['join_code' => $class->join_code])->assertRedirect('/');
        $this->assertDatabaseCount('class_members', 1);
        $this->assertDatabaseHas('class_members', ['user_id' => $user->id, 'role' => 'member']);
    }

    public function test_profile_persists_and_dashboard_uses_actual_identity(): void
    {
        $user = $this->member($this->classroom());
        Livewire::actingAs($user)->test(EditProfile::class)
            ->set('name', 'Nara Putri')->set('nickname', 'Nara')
            ->set('bio', 'Suka desain')->set('hobby', 'Membaca, Fotografi')
            ->set('birth_date', '2004-02-29')->set('birthday_visibility', 'private')
            ->call('save')->assertHasNoErrors()->assertRedirect('/profile');
        $this->assertDatabaseHas('profiles', ['user_id' => $user->id, 'nickname' => 'Nara', 'birthday_visibility' => 'private']);
        $this->assertSame(['Membaca', 'Fotografi'], $user->fresh()->profile->hobby);
        $this->get('/')->assertOk()->assertSee('Hai, Nara!');
        $this->get('/profile')->assertOk();
        $this->get('/members')->assertOk();
    }

    public function test_profile_policy_rejects_other_owners_and_save_cannot_target_another_user(): void
    {
        $class = $this->classroom();
        $alice = $this->member($class);
        $bob = $this->member($class);
        $bobProfile = $bob->profile()->create(['bio' => 'Tetap milik Bob']);
        $this->assertFalse(Gate::forUser($alice)->allows('update', $bobProfile));
        $this->actingAs($alice)->put('/members/'.$bob->classMemberships()->withoutGlobalScope('current_class')->first()->id, ['bio' => 'Hijacked'])->assertStatus(405);
        Livewire::actingAs($alice)->test(EditProfile::class)->set('bio', 'Cerita Alice')->call('save')->assertHasNoErrors();
        $this->assertSame('Tetap milik Bob', $bobProfile->fresh()->bio);
    }

    public function test_private_fields_are_not_rendered_or_serialized_and_cross_class_profiles_are_hidden(): void
    {
        $class = $this->classroom();
        $viewer = $this->member($class);
        $owner = $this->member($class, ['name' => 'Nama Umum']);
        $owner->profile()->create(['nickname' => 'NicknameRahasia', 'bio' => 'BioRahasia', 'birth_date' => '2004-01-02', 'profile_visibility' => 'private']);
        $other = $this->member($this->classroom('B'));
        $otherMember = ClassMember::withoutGlobalScope('current_class')->where('user_id', $other->id)->first();
        $ownerMember = ClassMember::withoutGlobalScope('current_class')->where('user_id', $owner->id)->first();
        $this->actingAs($viewer)->get('/members/'.$ownerMember->id)->assertOk()->assertDontSee('BioRahasia')->assertDontSee('NicknameRahasia')->assertDontSee('2004');
        Livewire::actingAs($viewer)->test(MemberDirectory::class)->assertDontSee('BioRahasia')->assertDontSee('NicknameRahasia');
        $this->get('/members/'.$otherMember->id)->assertNotFound();
        $this->get('/members/'.$otherMember->id.'/avatar')->assertNotFound();
    }

    public function test_admin_visibility_and_hidden_birthday_year_are_enforced(): void
    {
        $class = $this->classroom();
        $owner = $this->member($class);
        $viewer = $this->member($class);
        $admin = $this->member($class, [], 'admin');
        $owner->profile()->create(['bio' => 'KhususPengurus', 'profile_visibility' => 'admin_only', 'birth_date' => '2004-02-29']);
        $id = ClassMember::withoutGlobalScope('current_class')->where('user_id', $owner->id)->value('id');
        $this->actingAs($viewer)->get('/members/'.$id)->assertDontSee('KhususPengurus');
        $this->actingAs($admin)->get('/members/'.$id)->assertSee('KhususPengurus')->assertSee('29 Februari')->assertDontSee('2004');
        $owner->profile->update(['profile_visibility' => 'class_only', 'birthday_visibility' => 'private']);
        $this->actingAs($viewer)->get('/members/'.$id)->assertDontSee('29 Februari');
    }

    public function test_invalid_profile_input_is_rejected(): void
    {
        $user = $this->member($this->classroom());
        Livewire::actingAs($user)->test(EditProfile::class)
            ->set('nickname', str_repeat('x', 51))->set('birth_date', '2999-01-01')
            ->set('profile_visibility', 'public')->call('save')
            ->assertHasErrors(['nickname', 'birth_date', 'profile_visibility']);
        $this->assertDatabaseCount('profiles', 0);
    }

    public function test_avatar_upload_is_private_validated_and_requires_verified_email(): void
    {
        Storage::fake('local');
        $class = $this->classroom();
        $user = $this->member($class, ['email_verified_at' => null]);
        Livewire::actingAs($user)->test(EditProfile::class)
            ->set('avatar', UploadedFile::fake()->image('photo.png'))->call('save')->assertHasErrors('avatar');
        $user->markEmailAsVerified();
        Livewire::actingAs($user->fresh())->test(EditProfile::class)
            ->set('avatar', UploadedFile::fake()->create('script.svg', 1, 'image/svg+xml'))->call('save')->assertHasErrors('avatar');
        Livewire::actingAs($user->fresh())->test(EditProfile::class)
            ->set('avatar', UploadedFile::fake()->image('photo.png'))->call('save')->assertHasNoErrors();
        $path = $user->fresh()->profile->avatar_path;
        Storage::disk('local')->assertExists($path);
        $id = ClassMember::withoutGlobalScope('current_class')->where('user_id', $user->id)->value('id');
        $this->get('/members/'.$id.'/avatar')->assertOk()->assertHeader('cache-control', 'no-store, private');
        $user->fresh()->profile->update(['profile_visibility' => 'private']);
        $this->actingAs($this->member($class))->get('/members/'.$id.'/avatar')->assertNotFound();
    }

    public function test_email_verification_requires_valid_signature_and_matching_user(): void
    {
        $user = $this->member($this->classroom(), ['email_verified_at' => null]);
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), ['id' => $user->id, 'hash' => sha1($user->email)]);
        $this->actingAs($user)->get('/email/verify/'.$user->id.'/'.sha1($user->email))->assertForbidden();
        $this->get($url)->assertRedirect('/profile');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_password_reset_link_changes_password_only_with_valid_token(): void
    {
        $user = $this->member($this->classroom());
        $this->post('/forgot-password', ['email' => $user->email])->assertSessionHas('status');
        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;

            return true;
        });
        $this->post('/reset-password', ['email' => $user->email, 'token' => 'invalid', 'password' => 'NewSecret123', 'password_confirmation' => 'NewSecret123'])->assertSessionHasErrors('email');
        $this->post('/reset-password', ['email' => $user->email, 'token' => $token, 'password' => 'NewSecret123', 'password_confirmation' => 'NewSecret123'])->assertRedirect('/login');
        $this->assertTrue(Hash::check('NewSecret123', $user->fresh()->password));
    }

    public function test_inactive_class_prevents_profile_mutation(): void
    {
        $class = $this->classroom();
        $user = $this->member($class);
        $component = Livewire::actingAs($user)->test(EditProfile::class)->set('bio', 'Not allowed');
        $class->update(['is_active' => false]);
        $component->call('save')->assertForbidden();
        $this->assertDatabaseCount('profiles', 0);
    }
}
