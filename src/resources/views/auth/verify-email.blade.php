@component('layouts.auth', ['title' => 'Verifikasi email — Digital Class'])
<span class="inline-flex size-16 items-center justify-center rounded-2xl bg-[#fff3c8] text-3xl" aria-hidden="true">✉️</span>
<h2 class="mt-5 text-3xl font-bold">Cek kotak masukmu, ya.</h2>
<p class="mt-4 text-sm leading-6 text-muted">Buka tautan verifikasi yang dikirim ke <strong>{{ auth()->user()->email }}</strong>. Setelah itu, kamu bisa mengunggah foto profil.</p>
<form action="{{ route('verification.send') }}" method="POST" class="mt-7">@csrf<button class="primary-button w-full">Kirim ulang email verifikasi</button></form>
<a href="{{ route('profile') }}" class="quiet-button mt-4 w-full">Lanjut lengkapi profil</a>
<form action="{{ route('logout') }}" method="POST" class="mt-2 text-center">@csrf<button class="quiet-button text-muted">Keluar</button></form>
@endcomponent
