<?php

use Illuminate\Support\Facades\Route;
// Livewire v4 - Single File Components

Route::livewire('/', 'pages::dashboard')->name('dashboard');
Route::livewire('/feed', 'pages::feed')->name('feed');
Route::livewire('/events', 'pages::events')->name('events');
Route::livewire('/gallery', 'pages::gallery')->name('gallery');
Route::livewire('/members', 'pages::members')->name('members');
Route::livewire('/profile', 'pages::profile')->name('profile');

// Health check
Route::get('/health', fn() => response()->json([
    'status' => 'ok',
    'app' => 'Digital Class Universe',
    'timestamp' => now()
]));
