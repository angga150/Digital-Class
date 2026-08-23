<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
<header class="sticky top-0 z-40 h-16 border-b border-slate-200 bg-white/90 backdrop-blur">

    <div class="flex h-full items-center justify-between px-4 sm:px-6">

        {{-- Left --}}
        <div class="flex items-center gap-4">

            <button
                type="button"
                class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 lg:hidden"
            >
                ☰
            </button>

            <a
                href="#"
                class="text-lg font-bold tracking-tight"
            >
                <span class="text-indigo-600">Digital</span>Class
            </a>

        </div>

        {{-- Search --}}
        <div class="hidden w-full max-w-md md:block">

            <div class="relative">

                <input
                    type="search"
                    placeholder="Cari kelas, materi, tugas..."
                    class="w-full rounded-xl border border-slate-200 bg-slate-50
                           py-2.5 pl-10 pr-4 text-sm outline-none
                           transition focus:border-indigo-500
                           focus:bg-white focus:ring-4 focus:ring-indigo-500/10"
                >

                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    ⌕
                </span>

            </div>

        </div>

        {{-- Right --}}
        <div class="flex items-center gap-2">

            <button
                class="relative rounded-xl p-2.5 text-slate-500
                       hover:bg-slate-100"
            >
                🔔

                <span
                    class="absolute right-2 top-2 h-2 w-2 rounded-full
                           bg-red-500 ring-2 ring-white"
                ></span>
            </button>

            <div class="ml-2 flex items-center gap-3">

                <div
                    class="hidden text-right sm:block"
                >
                    <p class="text-sm font-semibold">
                        Angga
                    </p>

                    <p class="text-xs text-slate-500">
                        Mahasiswa
                    </p>
                </div>

                <div
                    class="flex h-9 w-9 items-center justify-center
                           rounded-xl bg-indigo-600 text-sm font-semibold text-white"
                >
                    A
                </div>

            </div>

        </div>

    </div>

</header>
</div>