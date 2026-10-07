@props(['initials' => 'A', 'tone' => 'orange'])
<span {{ $attributes->class(['inline-flex size-10 shrink-0 items-center justify-center rounded-full text-sm font-bold ring-2 ring-white', 'bg-[#f4dcc8] text-[#834d2a]' => $tone === 'orange', 'bg-[#e6ead9] text-[#526443]' => $tone === 'sage', 'bg-[#ede4f3] text-[#76518d]' => $tone === 'lilac']) }} aria-hidden="true">{{ $initials }}</span>
