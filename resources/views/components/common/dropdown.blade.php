@props([
    'align' => 'end', // start, end
    'width' => 'w-56',
])

<div x-data="dropdown" @keydown.escape.window="close()" @click.outside="close()" class="relative">
    <button type="button" @click="toggle()" :aria-expanded="open" class="block">
        {{ $trigger }}
    </button>

    <div x-show="open" x-cloak x-transition.origin.top
         class="card-surface absolute z-99999 mt-2 {{ $width }} {{ $align === 'end' ? 'end-0' : 'start-0' }} overflow-hidden shadow-theme-lg">
        {{ $slot }}
    </div>
</div>
