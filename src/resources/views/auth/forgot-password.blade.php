@component('layouts.auth', ['title' => 'Pulihkan akun — Digital Class'])
<span class="text-xs font-bold uppercase tracking-[.18em] text-orange-dark">Tenang, bisa kembali</span>
<h2 class="mt-3 text-3xl font-bold">Lupa kata sandi?</h2>
<p class="mt-4 text-sm leading-6 text-muted">Masukkan email akunmu. Kami akan mengirim tautan untuk membuat kata sandi baru.</p>
<form action="{{ route('password.email') }}" method="POST" class="mt-7 space-y-5">@csrf
    <x-form.input name="email" label="Email akun" type="email" :value="old('email')" autocomplete="email" required />
    <button class="primary-button w-full" type="submit">Kirim tautan pemulihan</button>
</form>
<a href="{{ route('login') }}" class="quiet-button mt-5 w-full">Kembali ke halaman masuk</a>
@endcomponent
