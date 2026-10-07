@props(['name', 'size' => 20])
<svg {{ $attributes->merge(['class' => 'shrink-0']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($name)
@case('home') <path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/> @break
@case('feed') <rect x="5" y="3" width="15" height="18" rx="3"/><path d="M9 7h7M9 11h7M9 15h4M3 7v11a3 3 0 0 0 3 3"/> @break
@case('calendar') <rect x="3" y="5" width="18" height="16" rx="3"/><path d="M7 3v4m10-4v4M3 11h18m-13 4h3m3 0h2"/> @break
@case('image') <rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8" cy="8" r="1.5"/><path d="m21 15-5-5L6 21m5-5-3-3-5 5"/> @break
@case('users') <circle cx="10" cy="7" r="3"/><path d="M4 21v-3a6 6 0 0 1 12 0v3zm13-17a3 3 0 0 1 0 6m2 4a5 5 0 0 1 3 5"/> @break
@case('search') <circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/> @break
@case('bell') <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/> @break
@case('arrow') <path d="M4 12h16m-6-6 6 6-6 6"/> @break
@case('chevron') <path d="m9 5 7 7-7 7"/> @break
@case('pen') <path d="m15 4 5 5M4 20l5-1L21 7a2 2 0 0 0-5-5L4 15z"/> @break
@case('megaphone') <path d="m4 9 14-5v16L4 15zm0 0H2v6h2m3 1 2 5h4l-2-4m10-8 2-1m-2 8 2 1"/> @break
@case('heart') <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/> @break
@case('comment') <path d="M21 11a9 9 0 0 1-9 9H4l-3 2 2-7a9 9 0 1 1 18-4Z"/> @break
@case('bookmark') <path d="M6 3h12v19l-6-4-6 4z"/> @break
@case('star') <path d="m12 2 2.7 6.8L22 9.5l-5.6 4.8 1.7 7.2L12 17.7l-6.1 3.8 1.7-7.2L2 9.5l7.3-.7Z"/> @break
@case('sparkle') <path d="m12 2 2.8 7.2L22 12l-7.2 2.8L12 22l-2.8-7.2L2 12l7.2-2.8Z"/> @break
@case('cake') <path d="M4 12h16v9H4zM4 16c3 3 5-3 8 0s5-3 8 0M8 12V8m8 4V8m-4 4V6M8 5V3m8 2V3m-4 0V1"/> @break
@case('chart') <path d="M4 21V10h4v11m2 0V4h4v17m2 0V7h4v14"/> @break
@case('check') <path d="m5 12 4 4L19 6"/> @break
@case('close') <path d="m6 6 12 12M6 18 18 6"/> @break
@case('settings') <path d="m9 3-1 3-3 1-2 3 2 2-1 3 3 2 2 3h5l2-3 3-1 2-4-2-2V7l-4-1-1-3z"/><circle cx="12" cy="12" r="3"/> @break
@case('clock') <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/> @break
@case('pin') <path d="M19 10c0 5-7 12-7 12S5 15 5 10a7 7 0 0 1 14 0Z"/><circle cx="12" cy="10" r="2"/> @break
@endswitch
</svg>
