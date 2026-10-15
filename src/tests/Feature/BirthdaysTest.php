<?php

namespace Tests\Feature;

use App\Livewire\Birthdays\Index;
use App\Livewire\Birthdays\Upcoming;
use App\Livewire\Membership\EditProfile;
use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\User;
use App\Support\ClassBirthdays;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class BirthdaysTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(Carbon::parse('2026-10-16 08:00:00', 'UTC'));
    }

    private function member(string $name, ?int $classId = null, array $profile = [], string $role = 'member'): User
    {
        $classId ??= ClassModel::create(['name' => 'Kelas '.uniqid(), 'code' => uniqid(), 'join_code' => uniqid(), 'is_active' => true])->id;
        $user = User::factory()->create(['name' => $name]);
        ClassMember::create(['user_id' => $user->id, 'class_id' => $classId, 'role' => $role]);
        if ($profile) {
            $user->profile()->create($profile);
        }

        return $user;
    }

    private function classId(User $user): int
    {
        return $user->classMemberships()->withoutGlobalScopes()->first()->class_id;
    }

    public function test_calendar_and_dashboard_use_profiles_and_include_same_day_birthdays(): void
    {
        $viewer = $this->member('Viewer');
        $classId = $this->classId($viewer);
        $this->member('Alya', $classId, ['birth_date' => '2004-10-16']);
        $this->member('Bima', $classId, ['birth_date' => '2003-10-16']);
        $this->member('Citra', $classId, ['birth_date' => '2005-10-17']);
        $this->actingAs($viewer);
        $this->get('/birthdays')->assertOk()->assertSee('Alya')->assertSee('Bima')->assertSee('Citra')
            ->assertSee('2 merayakan hari ini')->assertSee('3 dalam 30 hari ke depan')->assertDontSee('2004');
        Livewire::test(Upcoming::class)->assertSee('Alya')->assertSee('Bima')->assertDontSee('Citra')->assertSee('Hari ini tiup lilin!');
        $this->get('/')->assertOk()->assertSee('Alya')->assertSee('Bima')->assertDontSee('Rani ulang tahun');
    }

    public function test_private_birthdays_and_profiles_are_hidden_before_counts_and_filtering(): void
    {
        $viewer = $this->member('Viewer');
        $classId = $this->classId($viewer);
        $this->member('Tanggal Rahasia', $classId, ['birth_date' => '2001-10-16', 'birthday_visibility' => 'private']);
        $this->member('Profil Rahasia', $classId, ['birth_date' => '2002-10-16', 'profile_visibility' => 'private', 'nickname' => 'Nickname Rahasia']);
        $this->member('Khusus Pengurus', $classId, ['birth_date' => '2003-10-16', 'birthday_visibility' => 'admin_only']);
        $this->actingAs($viewer);
        $this->assertCount(0, ClassBirthdays::upcoming($viewer));
        Livewire::test(Index::class)->assertSee('0 merayakan hari ini')->assertSee('0 dalam 30 hari ke depan')
            ->assertDontSee('Tanggal Rahasia')->assertDontSee('Profil Rahasia')->assertDontSee('Nickname Rahasia')->assertDontSee('Khusus Pengurus')
            ->set('month', '10')->assertSee('Belum ada ulang tahun yang bisa ditampilkan.');
        Livewire::test(Upcoming::class)->assertDontSee('Tanggal Rahasia')->assertSee('Belum ada ulang tahun');
    }

    public function test_admin_only_requires_both_profile_and_birthday_permission(): void
    {
        $admin = $this->member('Pengurus', role: 'admin');
        $classId = $this->classId($admin);
        $this->member('Terlihat Admin', $classId, ['birth_date' => '2004-10-16', 'profile_visibility' => 'admin_only', 'birthday_visibility' => 'admin_only']);
        $this->member('Tetap Privat', $classId, ['birth_date' => '2004-10-16', 'birthday_visibility' => 'private']);
        $this->member('Profil Privat', $classId, ['birth_date' => '2004-10-16', 'profile_visibility' => 'private', 'birthday_visibility' => 'admin_only']);
        Livewire::actingAs($admin)->test(Index::class)->assertSee('Terlihat Admin')->assertDontSee('Tetap Privat')->assertDontSee('Profil Privat');
        ClassMember::withoutGlobalScopes()->where('user_id', $admin->id)->update(['role' => 'member']);
        Livewire::test(Index::class)->assertDontSee('Terlihat Admin');
    }

    public function test_owner_sees_own_private_birthday_but_calendar_never_shows_birth_year_or_age(): void
    {
        $owner = $this->member('Pemilik', profile: ['birth_date' => '1987-10-16', 'profile_visibility' => 'private', 'birthday_visibility' => 'private', 'birth_year_visible' => true]);
        $this->actingAs($owner);
        $entry = ClassBirthdays::upcoming($owner)->first();
        $this->assertTrue($entry['own']);
        $this->assertArrayNotHasKey('birth_date', $entry);
        $this->assertArrayNotHasKey('age', $entry);
        $this->assertArrayNotHasKey('email', $entry);
        $this->assertStringNotContainsString('1987', json_encode($entry));
        Livewire::test(Index::class)->assertSee('Pemilik')->assertSee('Ini hari spesialmu')->assertDontSee('1987')->assertDontSee($owner->email);
    }

    public function test_wib_midnight_and_year_rollover_are_used_for_countdown(): void
    {
        $viewer = $this->member('Viewer');
        $this->member('Awal Tahun', $this->classId($viewer), ['birth_date' => '2004-01-01']);
        $this->actingAs($viewer);
        $this->travelTo(Carbon::parse('2026-12-31 16:59:59', 'UTC'));
        $this->assertSame(1, ClassBirthdays::upcoming($viewer)->first()['days']);
        $this->assertSame('2027-01-01', ClassBirthdays::upcoming($viewer)->first()['date']);
        $this->travelTo(Carbon::parse('2026-12-31 17:00:00', 'UTC'));
        $this->assertSame(0, ClassBirthdays::upcoming($viewer)->first()['days']);
        Livewire::test(Upcoming::class)->assertSee('Hari ini tiup lilin!');
    }

    public function test_february_29_celebrates_on_28_in_common_year_and_29_in_leap_year(): void
    {
        $viewer = $this->member('Viewer');
        $this->member('Teman Kabisat', $this->classId($viewer), ['birth_date' => '2004-02-29']);
        $this->actingAs($viewer);
        $this->travelTo(Carbon::parse('2027-02-28 08:00:00', 'UTC'));
        $entry = ClassBirthdays::upcoming($viewer)->first();
        $this->assertSame(0, $entry['days']);
        $this->assertTrue($entry['leap_adjusted']);
        Livewire::test(Index::class)->assertSee('dirayakan 28 Februari');
        $this->travelTo(Carbon::parse('2027-03-01 08:00:00', 'UTC'));
        $this->assertSame('2028-02-29', ClassBirthdays::upcoming($viewer)->first()['date']);
        $this->travelTo(Carbon::parse('2028-02-28 08:00:00', 'UTC'));
        $entry = ClassBirthdays::upcoming($viewer)->first();
        $this->assertSame(1, $entry['days']);
        $this->assertFalse($entry['leap_adjusted']);
    }

    public function test_widget_30_day_boundary_and_calendar_month_filter(): void
    {
        $viewer = $this->member('Viewer');
        $classId = $this->classId($viewer);
        $near = $this->member('Hari Ketigapuluh', $classId, ['birth_date' => '2004-11-15']);
        $this->member('Hari Ketigapuluhsatu', $classId, ['birth_date' => '2004-11-16']);
        $this->actingAs($viewer);
        Livewire::test(Upcoming::class)->assertSee('Hari Ketigapuluh')->assertDontSee('Hari Ketigapuluhsatu');
        Livewire::test(Index::class)->set('month', '11')->assertSee('Hari Ketigapuluhsatu')->set('month', '10')->assertSee('Belum ada ulang tahun');
        $near->profile->update(['birthday_visibility' => 'private']);
        Livewire::test(Upcoming::class)->assertSee('Belum ada ulang tahun');
    }

    public function test_class_isolation_null_profiles_and_legacy_birthday_are_respected(): void
    {
        $viewer = $this->member('Viewer');
        $classId = $this->classId($viewer);
        $legacy = $this->member('Tanggal Lama', $classId);
        ClassMember::withoutGlobalScopes()->where('user_id', $legacy->id)->update(['birthday' => '2004-10-16']);
        $this->member('Tanpa Tanggal', $classId, ['nickname' => 'Profil Kosong']);
        $this->member('Kelas Lain', profile: ['birth_date' => '2004-10-16']);
        $this->actingAs($viewer);
        $this->assertCount(0, ClassBirthdays::upcoming($viewer));
        $this->get('/birthdays')->assertOk()->assertDontSee('Kelas Lain')->assertDontSee('Tanggal Lama')->assertDontSee('Profil Kosong');
        ClassModel::whereKey($classId)->update(['is_active' => false]);
        Livewire::test(Index::class)->assertForbidden();
    }

    public function test_privacy_change_refreshes_calendar_and_owner_can_clear_birthday(): void
    {
        $viewer = $this->member('Viewer');
        $owner = $this->member('Teman Berubah', $this->classId($viewer), ['birth_date' => '2004-10-16']);
        $component = Livewire::actingAs($viewer)->test(Index::class)->assertSee('Teman Berubah');
        $owner->profile->update(['birthday_visibility' => 'private']);
        $component->call('$refresh')->assertDontSee('Teman Berubah')->assertSee('0 merayakan hari ini');
        Livewire::actingAs($owner)->test(EditProfile::class)->set('birth_date', '')->call('save')->assertHasNoErrors();
        $this->assertNull($owner->profile()->first()->birth_date);
        Livewire::test(Index::class)->assertSee('0 merayakan hari ini');
    }

    public function test_calendar_requires_login_and_escapes_profile_names(): void
    {
        $this->get('/birthdays')->assertRedirect('/login');
        $viewer = $this->member('Viewer');
        $this->member('Nama Asli', $this->classId($viewer), ['birth_date' => '2004-10-16', 'nickname' => '<script>alert(1)</script>']);
        $this->actingAs($viewer)->get('/birthdays')->assertOk()->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(1)</script>', false);
    }
}
