@component('layouts.auth', ['title' => 'Masuk — Digital Class'])
<span class="text-xs font-bold uppercase tracking-[.18em] text-orange-dark">Senang kamu kembali</span>
<h2 class="mt-3 text-4xl font-bold tracking-tight">Hai, teman kelas! <span aria-hidden="true">👋</span></h2>
<p class="mt-3 text-sm leading-6 text-muted">Masuk dulu, yuk. Ada cerita yang menunggumu.</p>
<form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
    @csrf
    <x-form.input name="email" label="Email" type="email" :value="old('email')" autocomplete="email" required autofocus placeholder="nama@email.com" />
    <x-form.input name="password" label="Kata sandi" type="password" autocomplete="current-password" required />
    <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
        <label class="flex min-h-11 items-center gap-2"><input type="checkbox" name="remember" value="1" class="size-4 accent-orange-dark">Ingat saya</label>
        <a href="{{ route('password.request') }}" class="font-semibold text-orange-dark hover:underline">Lupa kata sandi?</a>
    </div>
    <button class="primary-button w-full" type="submit">Masuk ke ruang kelas <x-icon name="arrow" :size="17" /></button>
</form>
<p class="mt-7 text-center text-sm text-muted">Baru di sini? <a href="{{ route('register') }}" class="font-semibold text-orange-dark hover:underline">Gabung kelas</a></p>
@endcomponent
