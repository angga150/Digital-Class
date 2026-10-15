@component('layouts.auth', ['title' => 'Kata sandi baru — Digital Class'])
<h2 class="text-3xl font-bold">Mulai dengan kata sandi baru.</h2>
<p class="mt-4 text-sm leading-6 text-muted">Minimal 8 karakter, mengandung huruf dan angka.</p>
<form action="{{ route('password.update') }}" method="POST" class="mt-7 space-y-5">@csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <x-form.input name="email" label="Email akun" type="email" :value="old('email', $email)" autocomplete="email" required />
    <x-form.input name="password" label="Kata sandi baru" type="password" autocomplete="new-password" required minlength="8" />
    <x-form.input name="password_confirmation" label="Ulangi kata sandi" type="password" autocomplete="new-password" required />
    <button class="primary-button w-full" type="submit">Simpan kata sandi</button>
</form>
<a href="{{ route('password.request') }}" class="quiet-button mt-5 w-full">Minta tautan baru</a>
@endcomponent
