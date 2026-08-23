<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
 <aside
    class="sticky top-16 hidden h-[calc(100vh-4rem)] w-64
           shrink-0 border-r border-slate-200 bg-white lg:block"
>

    <div class="flex h-full flex-col p-4">

        <nav class="space-y-1">

            <p class="px-3 pb-2 pt-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                Menu
            </p>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl bg-indigo-50
                       px-3 py-2.5 text-sm font-semibold text-indigo-600"
            >
                <span>⌂</span>
                Dashboard
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5
                       text-sm font-medium text-slate-600
                       hover:bg-slate-50 hover:text-slate-900"
            >
                <span>▣</span>
                Kelas Saya
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5
                       text-sm font-medium text-slate-600
                       hover:bg-slate-50 hover:text-slate-900"
            >
                <span>✓</span>
                Tugas
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5
                       text-sm font-medium text-slate-600
                       hover:bg-slate-50 hover:text-slate-900"
            >
                <span>◷</span>
                Jadwal
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5
                       text-sm font-medium text-slate-600
                       hover:bg-slate-50 hover:text-slate-900"
            >
                <span>★</span>
                Nilai
            </a>

        </nav>

        <div class="my-5 border-t border-slate-100"></div>

        <nav class="space-y-1">

            <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                Lainnya
            </p>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5
                       text-sm font-medium text-slate-600
                       hover:bg-slate-50 hover:text-slate-900"
            >
                ⚙
                Pengaturan
            </a>

            <a
                href="#"
                class="flex items-center gap-3 rounded-xl px-3 py-2.5
                       text-sm font-medium text-slate-600
                       hover:bg-slate-50 hover:text-slate-900"
            >
                ?
                Bantuan
            </a>

        </nav>

        <div class="mt-auto rounded-2xl bg-slate-50 p-4">

            <p class="text-sm font-semibold">
                Butuh bantuan?
            </p>

            <p class="mt-1 text-xs leading-5 text-slate-500">
                Hubungi administrator jika mengalami masalah.
            </p>

            <button
                class="mt-3 text-xs font-semibold text-indigo-600"
            >
                Pusat Bantuan →
            </button>

        </div>

    </div>

</aside>
</div>