<nav aria-label="Navigasi HP" class="fixed inset-x-0 bottom-0 z-30 grid grid-cols-4 border-t border-line bg-white/95 px-2 pb-[max(.5rem,env(safe-area-inset-bottom))] pt-2 backdrop-blur lg:hidden">
    @foreach (['beranda' => ['home', 'Beranda'], 'cerita' => ['feed', 'Cerita'], 'agenda' => ['calendar', 'Agenda'], 'kenangan' => ['image', 'Kenangan']] as $id => [$icon, $label])
    <a href="#{{ $id }}" @click="active = '{{ $id }}'" class="flex min-h-12 flex-col items-center justify-center gap-1 rounded-xl text-[10px] font-semibold" :class="active === '{{ $id }}' ? 'text-orange-dark bg-orange/5' : 'text-muted'" :aria-current="active === '{{ $id }}' ? 'location' : null"><x-icon :name="$icon" :size="21" />{{ $label }}</a>
    @endforeach
</nav>
