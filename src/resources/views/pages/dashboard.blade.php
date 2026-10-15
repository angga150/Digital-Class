<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.dashboard')] class extends Component
{
    // Accounts and stories are persisted. Other dashboard widgets remain labelled examples.
};
?>

<div id="beranda">
    @php
        $today = now('Asia/Jakarta')->locale('id');
        $eventDate = $today->copy()->addDays(3);
        $birthdayDate = $today->copy()->addDays(2);
    @endphp
    <section class="mb-6 flex flex-wrap items-center justify-between gap-4 sm:mb-7" aria-labelledby="greeting">
        <div>
            <p class="mb-2 text-xs font-medium text-muted">{{ $today->translatedFormat('l, j F Y') }}</p>
            <h1 id="greeting" class="flex items-center gap-3 text-[30px] font-bold tracking-[-.04em] sm:text-[38px]"><span class="min-w-0 break-words">Hai, {{ auth()->user()->display_name }}!</span> <span class="inline-block shrink-0 -rotate-12 text-orange"><x-icon name="sparkle" :size="27" /></span></h1>
            <p class="mt-1 text-sm text-muted sm:text-base">Ada cerita apa di kelas hari ini?</p>
        </div>
        <button @click="openModal('compose')" class="primary-button"><x-icon name="pen" :size="17" />Tulis cerita</button>
    </section>

    <button @click="openModal('announcement')" class="mb-5 flex w-full items-center gap-4 rounded-2xl border border-[#dce7ce] bg-sage px-4 py-4 text-left transition hover:bg-[#e1ebd6] sm:px-5">
        <span class="text-[#547240]"><x-icon name="megaphone" :size="25" /></span>
        <span class="flex-1"><span class="mb-0.5 block text-[10px] font-bold uppercase tracking-widest text-[#59734a]">Papan kabar</span><span class="block text-sm font-semibold">Ruang kuliah besok pindah ke B.204</span></span>
        <span class="hidden text-xs text-[#59734a] sm:block">Baca info</span><x-icon name="chevron" :size="17" class="text-[#59734a]" />
    </button>

    <section class="paper-grid relative mb-7 overflow-hidden rounded-3xl border border-[#f2e4ce] bg-[#fff0d7] px-6 py-7 sm:px-8 lg:py-8" aria-labelledby="memories-heading">
        <div class="relative z-10 max-w-[65%] sm:max-w-[55%]">
            <p class="mb-3 hidden text-[10px] font-bold uppercase tracking-[.2em] text-[#9d6f3d] sm:block">Sedikit cerita, banyak rasa</p>
            <h2 id="memories-heading" class="font-story text-[20px] font-bold leading-[1.35] tracking-tight sm:text-[34px] xl:text-[37px]">Momen kecil,<br>kenangan panjang<span class="text-orange">.</span></h2>
            <p class="mt-3 max-w-60 text-xs leading-5 text-[#7d6c53] sm:max-w-none sm:text-sm">Tempat cerita dan kenangan kelas kita.</p>
            <a href="#kenangan" @click="active = 'kenangan'" class="mt-5 inline-flex min-h-10 items-center gap-3 rounded-full bg-white/90 px-4 py-2 text-xs font-semibold text-orange-dark shadow-sm transition hover:bg-white">Lihat kenangan <x-icon name="arrow" :size="16" /></a>
        </div>
        <div class="absolute right-3 top-1/2 w-[30%] max-w-[270px] -translate-y-1/2 rotate-6 sm:right-7 sm:w-[36%] lg:right-9" aria-hidden="true">
            <div class="absolute inset-0 translate-x-3 translate-y-3 -rotate-12 rounded-sm bg-[#e1b383]/45"></div>
            <div class="relative bg-white p-2 pb-5 shadow-[0_6px_15px_#8e754926] sm:p-3 sm:pb-7">
                <span class="tape"></span>
                <img src="{{ asset('img/dashboard/campus-friends.png') }}" alt="" width="1774" height="887" class="aspect-[4/3] w-full object-cover">
                <span class="absolute bottom-1.5 left-0 right-0 text-center font-story text-[10px] text-muted sm:bottom-2 sm:text-xs">our kind of happy ♡</span>
            </div>
        </div>
        <x-icon name="star" class="absolute right-2 top-4 rotate-12 text-orange sm:right-8" :size="31" />
        <x-icon name="sparkle" class="absolute bottom-6 right-[38%] -rotate-12 text-orange" :size="30" />
    </section>

    <div class="grid items-start gap-6 xl:grid-cols-[minmax(0,1.55fr)_minmax(290px,1fr)]">
        <livewire:stories.feed />

        <aside class="order-first space-y-5 xl:order-none" aria-label="Kabar dan kegiatan kelas">
            <section class="relative overflow-hidden rounded-3xl border border-[#f4e6aa] bg-[#fff3c8] p-5" aria-labelledby="birthday-heading">
                <h2 id="birthday-heading" class="flex items-center gap-2 text-sm font-semibold"><x-icon name="sparkle" :size="16" class="text-[#b77d20]" />Sebentar lagi tiup lilin!</h2>
                <div class="mt-5 flex items-center gap-3">
                    <x-avatar initials="R" tone="sage" class="size-12" />
                    <div><p class="font-bold">Rani</p><p class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-[#ffe9a2] px-2 py-1 text-[10px] font-semibold text-[#885c19]"><x-icon name="cake" :size="12" />2 hari lagi</p></div>
                    <x-icon name="cake" :size="49" class="ml-auto mr-1 rotate-6 text-orange" />
                </div>
                <button @click="openModal('birthdays')" class="mt-4 inline-flex min-h-10 items-center gap-3 rounded-full bg-white/85 px-4 py-2 text-xs font-semibold text-orange-dark transition hover:bg-white">Lihat kalender <x-icon name="arrow" :size="15" /></button>
                <x-icon name="star" :size="13" class="absolute right-8 top-12 -rotate-12 text-orange" />
            </section>

            <section id="agenda" class="card scroll-mt-24 p-5" aria-labelledby="agenda-heading">
                <div class="mb-4 flex items-center justify-between"><h2 id="agenda-heading" class="flex items-center gap-2 text-sm font-semibold"><x-icon name="calendar" :size="17" />Agenda terdekat</h2><span class="size-1.5 rounded-full bg-[#93a87a]"></span></div>
                <button @click="openModal('event')" class="flex w-full items-center gap-3 rounded-xl text-left">
                    <span class="flex size-14 shrink-0 flex-col items-center justify-center rounded-xl bg-[#fcf4ec] text-orange-dark"><span class="text-xl font-semibold">{{ $eventDate->format('d') }}</span><span class="text-[10px] font-semibold uppercase">{{ $eventDate->translatedFormat('M') }}</span></span>
                    <span class="min-w-0 flex-1"><span class="block text-sm font-semibold">Presentasi kelompok</span><span class="mt-1 block text-xs text-muted">{{ $eventDate->translatedFormat('l') }} · 09.00 WIB</span></span>
                    <x-icon name="chevron" :size="15" class="text-muted" />
                </button>
                <div class="mt-4 flex items-center justify-between border-t border-line pt-3"><span class="flex items-center gap-1.5 text-[11px] text-muted"><x-icon name="pin" :size="13" />Ruang B.204</span><span class="rounded-md bg-sage px-2 py-1 text-[10px] font-semibold text-[#50683e]">3 hari lagi</span></div>
            </section>

            <section class="card p-5" aria-labelledby="poll-heading">
                <h2 id="poll-heading" class="mb-4 flex items-center gap-2 text-sm font-semibold"><x-icon name="chart" :size="17" />Suara kelas</h2>
                <form @submit.prevent="vote()">
                    <fieldset :disabled="voted">
                        <legend class="mb-3 text-sm font-semibold leading-6">Mau kumpul di mana Jumat ini?</legend>
                        <div class="space-y-2" x-show="!voted">
                            <label class="flex min-h-11 cursor-pointer items-center gap-3 rounded-xl border border-line px-3 text-sm transition hover:bg-paper" :class="pollChoice === 'taman' ? 'border-orange bg-orange/5' : ''"><input type="radio" name="poll" value="taman" x-model="pollChoice" required class="size-4 accent-orange-dark">Taman kampus</label>
                            <label class="flex min-h-11 cursor-pointer items-center gap-3 rounded-xl border border-line px-3 text-sm transition hover:bg-paper" :class="pollChoice === 'kantin' ? 'border-orange bg-orange/5' : ''"><input type="radio" name="poll" value="kantin" x-model="pollChoice" required class="size-4 accent-orange-dark">Kantin</label>
                        </div>
                    </fieldset>
                    <div x-cloak x-show="voted" class="space-y-3" role="status">
                        <p class="flex items-center gap-2 text-xs font-medium text-[#567044]"><x-icon name="check" :size="16" />Pilihan demo tercatat di halaman ini.</p>
                        <template x-for="option in pollResults" :key="option.label"><div><div class="mb-1 flex justify-between text-xs"><span x-text="option.label"></span><span x-text="option.percent + '%'"></span></div><div class="h-2 overflow-hidden rounded-full bg-paper"><div class="h-full rounded-full bg-orange" :style="{ width: option.percent + '%' }"></div></div></div></template>
                        <p class="text-[10px] leading-4 text-muted">Hasil simulasi · bukan suara anggota sungguhan.</p>
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-2"><button x-show="!voted" type="submit" class="primary-button min-h-10 px-4 py-2 text-xs">Kirim pilihan <x-icon name="arrow" :size="14" /></button><span class="text-[10px] text-muted">Berakhir besok</span></div>
                </form>
            </section>
        </aside>
    </div>

    <section id="kenangan" class="mt-4 scroll-mt-24 border-t border-line pt-7" aria-labelledby="gallery-heading">
        <div class="mb-4 flex items-center justify-between gap-3"><div><h2 id="gallery-heading" class="text-lg font-bold tracking-tight">Sudut kenangan</h2><p class="mt-1 text-xs text-muted">Karena yang seru pantas disimpan.</p></div><button @click="openModal('memory')" class="quiet-button text-xs">Buka album <x-icon name="arrow" :size="15" /></button></div>
        <button @click="openModal('memory')" class="card group flex w-full flex-col overflow-hidden text-left sm:flex-row">
            <img src="{{ asset('img/dashboard/campus-friends.png') }}" width="1774" height="887" loading="lazy" alt="Contoh album kebersamaan teman di kampus, dibuat dengan AI" class="aspect-[2/1] w-full object-cover sm:aspect-[16/9] sm:w-60">
            <span class="flex flex-col justify-center p-5"><span class="mb-2 text-[10px] font-bold uppercase tracking-widest text-orange-dark">Album contoh · Kehidupan kampus</span><span class="text-lg font-semibold">Selesai tugas, lanjut cerita.</span><span class="mt-2 text-sm leading-6 text-muted">Obrolan setelah kelas yang selalu kepanjangan.</span><span class="mt-3 text-xs text-muted">1 foto ilustrasi AI <span class="ml-2 text-orange-dark">Lihat momen →</span></span></span>
        </button>
    </section>

    <footer class="mt-8 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-5 text-[11px] text-muted">
        <span>Digital Class <span class="mx-1 text-orange">✳</span> Dibuat untuk cerita kita.</span>
        <button @click="openModal('settings')" class="min-h-10 underline decoration-line underline-offset-4">Cerita & profil tersimpan · agenda dan polling masih contoh</button>
    </footer>
</div>
