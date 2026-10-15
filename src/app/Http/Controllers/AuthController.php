<?php

namespace App\Http\Controllers;

use App\Models\ClassMember;
use App\Models\ClassModel;
use App\Models\User;
use App\Support\CurrentClass;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255', 'password' => 'required|string', 'remember' => 'sometimes|boolean']);
        $key = 'login:'.Str::lower($data['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['email' => 'Terlalu banyak percobaan. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.']);
        }

        if (! Auth::attempt(['email' => Str::lower(trim($data['email'])), 'password' => $data['password']], $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['email' => 'Email atau kata sandi belum cocok. Coba cek lagi, ya.']);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->intended(route(CurrentClass::membership() ? 'dashboard' : 'join'));
    }

    public function register(Request $request)
    {
        $request->merge(['email' => Str::lower(trim($request->string('email'))), 'join_code' => trim($request->string('join_code'))]);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
            'join_code' => 'required|string|max:100',
        ]);
        $user = DB::transaction(function () use ($data) {
            $class = $this->invitedClass($data['join_code']);
            $user = User::create(collect($data)->only('name', 'email', 'password')->all());
            ClassMember::create(['class_id' => $class->id, 'user_id' => $user->id, 'role' => 'member']);
            $user->profile()->create([]);

            return $user;
        });
        Auth::login($user);
        $request->session()->regenerate();
        // Account creation must remain usable if the mail transport is unavailable.
        try {
            event(new Registered($user));
        } catch (\Throwable $exception) {
            report($exception);
            session()->flash('mail_error', 'Email verifikasi belum terkirim. Kamu bisa mencoba lagi dari profil.');
        }

        return redirect()->route('profile')->with('status', 'Selamat datang! Yuk, isi sedikit cerita tentang dirimu.');
    }

    public function join(Request $request)
    {
        if (CurrentClass::membership($request->user())) {
            return redirect()->route('dashboard');
        }

        $data = $request->validate(['join_code' => 'required|string|max:100']);
        DB::transaction(function () use ($request, $data) {
            $class = $this->invitedClass(trim($data['join_code']));
            ClassMember::withoutGlobalScope('current_class')->firstOrCreate(
                ['class_id' => $class->id, 'user_id' => $request->user()->id],
                ['role' => 'member']
            );
        });

        return redirect()->route('dashboard');
    }

    private function invitedClass(string $code): ClassModel
    {
        $class = ClassModel::where('join_code', $code)->where('is_active', true)->lockForUpdate()->first();
        // Compare exactly on both SQLite and case-insensitive MySQL collations.
        if (! $class || ! hash_equals($class->join_code, $code)) {
            throw ValidationException::withMessages(['join_code' => 'Kode undangan tidak cocok atau kelas sudah tidak aktif. Minta kode terbaru ke pengurus kelas, ya.']);
        }

        return $class;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);
        try {
            Password::sendResetLink(['email' => Str::lower(trim($request->string('email')))]);
        } catch (\Throwable $exception) {
            report($exception);
        }

        return back()->with('status', 'Jika email terdaftar, tautan pemulihan akan dikirim. Cek kotak masuk dan folder spam.');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ]);
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
                event(new PasswordReset($user));
            });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => 'Tautan pemulihan tidak valid atau sudah kedaluwarsa. Minta tautan baru, ya.']);
        }

        return redirect()->route('login')->with('status', 'Kata sandi diperbarui. Silakan masuk kembali.');
    }
}
