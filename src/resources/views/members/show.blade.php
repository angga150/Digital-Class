@component('layouts.member', ['title' => $member['name'].' — Digital Class'])
<a href="{{ route('members') }}" class="quiet-button mb-5">← Kembali ke teman kelas</a>
<article class="card mx-auto max-w-3xl overflow-hidden">
    <div class="paper-grid h-32 bg-sage sm:h-40"></div>
    <div class="px-6 pb-8 sm:px-10">
        <div class="-mt-10 flex flex-wrap items-end justify-between gap-4">
            @if($member['avatar'])<img src="{{ $member['avatar'] }}" alt="Foto {{ $member['name'] }}" class="size-24 rounded-3xl border-4 border-white object-cover">@else<span class="flex size-24 items-center justify-center rounded-3xl border-4 border-white bg-[#f4dcc8] text-4xl font-bold text-[#834d2a]">{{ $member['initials'] }}</span>@endif
            <span class="rounded-full bg-[#fff3c8] px-4 py-2 text-xs font-semibold text-[#886321]">{{ $member['role'] }}</span>
        </div>
        <h1 class="mt-6 break-words text-3xl font-bold">{{ $member['name'] }}</h1>
        <p class="mt-2 text-xs text-muted">Bagian dari kelas sejak {{ $member['joined'] }}</p>
        @if($member['visible'])
            <p class="mt-6 whitespace-pre-wrap break-words text-base leading-8">{{ $member['bio'] ?: 'Cerita tentangku masih disiapkan. Sampai ketemu di kelas!' }}</p>
            <div class="mt-8 grid gap-5 sm:grid-cols-2">
                @foreach(['hobby' => 'Kalau lagi senggang', 'favorite' => 'Hal-hal favorit'] as $field => $label)
                <div class="rounded-2xl bg-paper p-5"><h2 class="font-story text-sm">{{ $label }}</h2><div class="mt-4 flex flex-wrap gap-2">@forelse($member[$field] as $item)<span class="max-w-full break-words rounded-lg bg-white px-3 py-2 text-sm">{{ $item }}</span>@empty<p class="text-sm text-muted">Belum diisi.</p>@endforelse</div></div>
                @endforeach
            </div>
            @if($member['fun_fact'])<section class="mt-5 rounded-2xl bg-[#fff7df] p-5"><h2 class="font-story text-sm">Fakta random tentangku ✨</h2><p class="mt-3 whitespace-pre-wrap break-words text-sm leading-7">{{ $member['fun_fact'] }}</p></section>@endif
            @if($member['birthday'])<p class="mt-6 flex items-center gap-3 text-sm"><x-icon name="cake" class="text-orange-dark" />{{ $member['birthday'] }}</p>@endif
        @else
            <p class="mt-8 rounded-2xl bg-paper p-5 text-sm text-muted">Detail profil ini hanya dibagikan kepada orang yang dipilih pemiliknya.</p>
        @endif
    </div>
</article>
@endcomponent
