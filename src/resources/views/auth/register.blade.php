@component('layouts.auth', ['title' => 'Gabung kelas — Digital Class'])
<span class="text-xs font-bold uppercase tracking-[.18em] text-orange-dark">Kursi untukmu sudah siap</span>
<h2 class="mt-3 text-4xl font-bold tracking-tight">Jadi bagian cerita.</h2>
<p class="mt-3 text-sm leading-6 text-muted">Pakai kode undangan dari pengurus untuk bergabung dengan kelasmu.</p>
<form action="{{ route('register') }}" method="POST" class="mt-7 space-y-4">
    @csrf
    <x-form.input name="join_code" label="Kode undangan kelas" :value="old('join_code')" autocomplete="off" required maxlength="100" placeholder="Kode dari pengurus kelas" />
    <x-form.input name="name" label="Nama lengkap" :value="old('name')" autocomplete="name" required maxlength="100" />
    <x-form.input name="email" label="Email" type="email" :value="old('email')" autocomplete="email" required />
    <x-form.input name="password" label="Kata sandi" type="password" autocomplete="new-password" required minlength="8" aria-describedby="password-help" />
    <p id="password-help" class="text-xs text-muted">Minimal 8 karakter, mengandung huruf dan angka.</p>
    <x-form.input name="password_confirmation" label="Ulangi kata sandi" type="password" autocomplete="new-password" required />
    <button class="primary-button w-full" type="submit">Gabung & mulai kenalan <x-icon name="arrow" :size="17" /></button>
</form>
<p class="mt-6 text-center text-sm text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-orange-dark hover:underline">Masuk di sini</a></p>
@endcomponent
