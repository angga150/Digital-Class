<?php

namespace App\Livewire\Membership;

use App\Models\Profile;
use App\Support\CurrentClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.member')]
class EditProfile extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $nickname = '';

    public string $bio = '';

    public string $hobby = '';

    public string $favorite = '';

    public string $fun_fact = '';

    public string $birth_date = '';

    public bool $birth_year_visible = false;

    public string $birthday_visibility = 'class_only';

    public string $profile_visibility = 'class_only';

    public $avatar;

    public function mount(): void
    {
        $user = auth()->user();
        $profile = $user->profile;
        $this->name = $user->name;
        foreach (['nickname', 'bio', 'fun_fact'] as $field) {
            $this->$field = $profile?->$field ?? '';
        }
        $this->hobby = implode(', ', $profile?->hobby ?? []);
        $this->favorite = implode(', ', $profile?->favorite ?? []);
        $this->birth_date = $profile?->birth_date?->format('Y-m-d') ?? '';
        $this->birth_year_visible = $profile?->birth_year_visible ?? false;
        $this->birthday_visibility = $profile?->birthday_visibility ?? 'class_only';
        $this->profile_visibility = $profile?->profile_visibility ?? 'class_only';
    }

    public function save(): void
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        // Never accept a client-supplied profile/user id.
        $profile->user_id = $user->id;
        Gate::authorize('update', $profile);
        $data = $this->validate([
            'name' => 'required|string|max:100',
            'nickname' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:500',
            'hobby' => 'nullable|string|max:250',
            'favorite' => 'nullable|string|max:250',
            'fun_fact' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date_format:Y-m-d|before_or_equal:today|after_or_equal:1900-01-01',
            'birth_year_visible' => 'boolean',
            'birthday_visibility' => ['required', Rule::in(['class_only', 'admin_only', 'private'])],
            'profile_visibility' => ['required', Rule::in(['class_only', 'admin_only', 'private'])],
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'name.required' => 'Nama perlu diisi dulu, ya.',
            'avatar.image' => 'Pilih foto JPG, PNG, atau WebP.',
            'avatar.mimes' => 'Pilih foto JPG, PNG, atau WebP.',
            'avatar.max' => 'Ukuran foto maksimal 5 MB.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
        ]);
        if ($this->avatar && ! $user->hasVerifiedEmail()) {
            $this->addError('avatar', 'Verifikasi email dulu sebelum mengunggah foto, ya.');

            return;
        }
        $oldAvatar = $profile->avatar_path;
        $newAvatar = null;
        try {
            if ($this->avatar) {
                $newAvatar = $this->avatar->store('avatars', 'local');
                if (! $newAvatar) {
                    throw new \RuntimeException('Avatar could not be stored.');
                }
                $profile->avatar_path = $newAvatar;
            }
            DB::transaction(function () use ($user, $profile, $data) {
                $user->update(['name' => trim($data['name'])]);
                foreach (['nickname', 'bio', 'fun_fact', 'birth_date'] as $field) {
                    $profile->$field = trim($data[$field] ?? '') ?: null;
                }
                foreach (['hobby', 'favorite'] as $field) {
                    $profile->$field = array_values(array_filter(array_map('trim', explode(',', $data[$field] ?? ''))));
                }
                foreach (['birth_year_visible', 'birthday_visibility', 'profile_visibility'] as $field) {
                    $profile->$field = $data[$field];
                }
                $profile->save();
            });
        } catch (\Throwable $exception) {
            if ($newAvatar) {
                Storage::disk('local')->delete($newAvatar);
            }
            throw $exception;
        }
        if ($newAvatar && $oldAvatar) {
            Storage::disk('local')->delete($oldAvatar);
        }
        $this->reset('avatar');
        $user->unsetRelation('profile');
        session()->flash('status', 'Profilmu tersimpan. Senang bisa mengenalmu lebih dekat!');
        $this->redirectRoute('profile');
    }

    public function render()
    {
        $member = CurrentClass::membership();
        abort_unless($member, 403);

        return view('livewire.membership.edit-profile', [
            'member' => $member,
            'savedProfile' => auth()->user()->profile,
        ]);
    }
}
