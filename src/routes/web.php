<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Middleware\EnsureClassMember;
use App\Livewire\Agenda\Detail;
use App\Livewire\Agenda\Index;
use App\Livewire\Membership\EditProfile;
use App\Livewire\Membership\MemberDirectory;
use App\Livewire\Stories\Feed;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:20,1');
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1')->name('password.email');
    Route::get('/reset-password/{token}', fn (string $token, Request $request) => view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]))->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/join', 'auth.join')->name('join');
    Route::post('/join', [AuthController::class, 'join'])->middleware('throttle:5,1');
    Route::view('/email/verify', 'auth.verify-email')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('profile')->with('status', 'Email terverifikasi. Foto profil sekarang bisa diunggah!');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        if (! $request->user()->hasVerifiedEmail()) {
            try {
                $request->user()->sendEmailVerificationNotification();
            } catch (Throwable $exception) {
                report($exception);

                return back()->with('mail_error', 'Email belum berhasil dikirim. Coba lagi nanti, ya.');
            }
        }

        return back()->with('status', 'Tautan verifikasi sudah diminta. Cek kotak masuk dan folder spam.');
    })->middleware('throttle:3,1')->name('verification.send');

    Route::middleware(EnsureClassMember::class)->group(function () {
        Route::livewire('/', 'pages::dashboard')->name('dashboard');
        Route::get('/feed', Feed::class)->name('feed');
        Route::get('/events', Index::class)->name('events');
        Route::get('/events/{event}', Detail::class)->name('events.show');
        Route::get('/polls', App\Livewire\Polling\Index::class)->name('polls');
        Route::get('/birthdays', App\Livewire\Birthdays\Index::class)->name('birthdays');
        Route::get('/announcements', App\Livewire\Announcements\Index::class)->name('announcements');
        Route::get('/announcements/{post}', App\Livewire\Announcements\Detail::class)->name('announcements.show');
        Route::livewire('/gallery', 'pages::gallery')->name('gallery');
        Route::get('/profile', EditProfile::class)->name('profile');
        Route::get('/members', MemberDirectory::class)->name('members');
        Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/avatar', [MemberController::class, 'avatar'])->name('members.avatar');
    });
});

Route::get('/health', fn () => response()->json(['status' => 'ok', 'app' => 'Digital Class Universe', 'timestamp' => now()]));
