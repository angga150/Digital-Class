<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
<div class="mx-auto max-w-7xl space-y-8 p-4 sm:p-6 lg:p-8">

        {{-- Welcome --}}
        <section>
            <p class="text-sm font-medium text-indigo-600">
                Senin, 23 Agustus 2026
            </p>

            <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">
                Selamat datang kembali, Angga 👋
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Lanjutkan pembelajaranmu dan selesaikan tugas yang tertunda.
            </p>
        </section>

        {{-- Stats --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm text-slate-500">
                    Kelas Aktif
                </p>

                <p class="mt-2 text-3xl font-bold">
                    5
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm text-slate-500">
                    Tugas Pending
                </p>

                <p class="mt-2 text-3xl font-bold">
                    3
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm text-slate-500">
                    Materi Selesai
                </p>

                <p class="mt-2 text-3xl font-bold">
                    24
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                <p class="text-sm text-slate-500">
                    Rata-rata Nilai
                </p>

                <p class="mt-2 text-3xl font-bold">
                    87
                </p>
            </div>

        </section>

        {{-- Main Content --}}
        <div class="grid gap-6 xl:grid-cols-3">

            {{-- Classes --}}
            <section class="xl:col-span-2">

                <div class="mb-4 flex items-center justify-between">

                    <div>
                        <h2 class="font-semibold">
                            Kelas Saya
                        </h2>

                        <p class="text-sm text-slate-500">
                            Kelas yang sedang kamu ikuti
                        </p>
                    </div>

                    <a
                        href="#"
                        class="text-sm font-semibold text-indigo-600"
                    >
                        Lihat semua
                    </a>

                </div>

                <div class="space-y-4">

                    @foreach (range(1, 3) as $class)

                        <article
                            class="rounded-2xl border border-slate-200 bg-white p-5
                                   transition hover:border-indigo-200 hover:shadow-sm"
                        >

                            <div class="flex gap-4">

                                <div
                                    class="hidden h-16 w-16 shrink-0 items-center
                                           justify-center rounded-xl bg-indigo-50
                                           text-2xl sm:flex"
                                >
                                    💻
                                </div>

                                <div class="min-w-0 flex-1">

                                    <div class="flex items-start justify-between gap-4">

                                        <div>
                                            <h3 class="font-semibold">
                                                Pemrograman Web
                                            </h3>

                                            <p class="mt-1 text-sm text-slate-500">
                                                Dr. Ahmad • Semester 5
                                            </p>
                                        </div>

                                        <span
                                            class="rounded-full bg-emerald-50 px-2.5 py-1
                                                   text-xs font-medium text-emerald-600"
                                        >
                                            Aktif
                                        </span>

                                    </div>

                                    <div class="mt-4">

                                        <div class="mb-2 flex justify-between text-xs">

                                            <span class="text-slate-500">
                                                Progress
                                            </span>

                                            <span class="font-semibold">
                                                78%
                                            </span>

                                        </div>

                                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">

                                            <div
                                                class="h-full w-[78%] rounded-full bg-indigo-600"
                                            ></div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            </section>

            {{-- Upcoming --}}
            <section>

                <div class="mb-4">
                    <h2 class="font-semibold">
                        Tugas Mendatang
                    </h2>

                    <p class="text-sm text-slate-500">
                        Jangan sampai terlewat
                    </p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white">

                    @foreach (range(1, 3) as $task)

                        <div
                            class="border-b border-slate-100 p-4 last:border-0"
                        >

                            <div class="flex gap-3">

                                <div
                                    class="h-10 w-10 shrink-0 rounded-xl
                                           bg-amber-50 text-center leading-10"
                                >
                                    📝
                                </div>

                                <div class="min-w-0">

                                    <p class="truncate text-sm font-semibold">
                                        Tugas Pemrograman Web
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Deadline 25 Agustus
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </section>

        </div>

    </div>
</div>