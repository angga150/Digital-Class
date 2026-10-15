@component('layouts.auth', ['title' => 'Temukan kelasmu — Digital Class'])
<span class="text-xs font-bold uppercase tracking-[.18em] text-orange-dark">Satu langkah lagi</span>
<h2 class="mt-3 text-3xl font-bold">Temukan ruang kelasmu.</h2>
<p class="mt-4 text-sm leading-6 text-muted">Akunmu belum terhubung ke kelas aktif. Minta kode undangan ke pengurus kelas, lalu masukkan di bawah ini.</p>
<form action="{{ route('join') }}" method="POST" class="mt-7 space-y-5">@csrf
    <x-form.input name="join_code" label="Kode undangan" :value="old('join_code')" required autocomplete="off" />
    <button type="submit" class="primary-button w-full">Gabung kelas</button>
</form>
<form action="{{ route('logout') }}" method="POST" class="mt-4 text-center">@csrf<button class="quiet-button">Keluar dari akun</button></form>
@endcomponent
