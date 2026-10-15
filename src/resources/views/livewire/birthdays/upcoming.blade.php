<section wire:poll.60s class="relative overflow-hidden rounded-3xl border border-[#f4e6aa] bg-[#fff3c8] p-5" aria-labelledby="birthday-heading">
    <h2 id="birthday-heading" class="flex items-center gap-2 text-sm font-semibold"><x-icon name="cake" :size="18" class="text-[#b77d20]" />{{ $next && $next['days'] === 0 ? 'Hari ini tiup lilin!' : 'Sebentar lagi tiup lilin!' }}</h2>
    @forelse($birthdays as $birthday)
        <a wire:key="next-birthday-{{ $birthday['id'] }}" href="{{ route('members.show', $birthday['id']) }}" class="mt-5 flex items-center gap-3">
            @if($birthday['avatar'])<img src="{{ $birthday['avatar'] }}" alt="" loading="lazy" class="size-12 shrink-0 rounded-2xl object-cover">@else<x-avatar :initials="$birthday['initials']" tone="sage" class="size-12 shrink-0" />@endif
            <span class="min-w-0"><span class="block break-words font-bold">{{ $birthday['name'] }}</span><span class="mt-1 inline-flex rounded-full bg-white/70 px-2 py-1 text-xs font-semibold text-[#885c19]">{{ $birthday['countdown'] }}</span></span>
        </a>
    @empty
        <p class="mt-4 text-sm leading-6 text-[#7d6c53]">Belum ada ulang tahun yang bisa ditampilkan dalam 30 hari ke depan.</p>
    @endforelse
    <a href="{{ route('birthdays') }}" class="mt-5 inline-flex min-h-10 items-center gap-3 rounded-full bg-white/85 px-4 py-2 text-xs font-semibold text-orange-dark">Lihat kalender <x-icon name="arrow" :size="15" /></a>
</section>
