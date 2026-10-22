@props(['items', 'alt' => ''])
<div x-data="{ open: null, items: @js(collect($items)->values()) }" @keydown.escape.window="open = null" @keydown.arrow-right.window="open !== null && (open = (open + 1) % items.length)" @keydown.arrow-left.window="open !== null && (open = (open - 1 + items.length) % items.length)">
    <div class="columns-2 gap-4 sm:columns-3 lg:columns-4 [&>*]:mb-4">
        @foreach ($items as $i => $item)
            <button type="button" @click="open = {{ $i }}" class="group relative block w-full overflow-hidden rounded-2xl bg-navy-900 shadow-card">
                @if ($item['type'] === 'image')
                    <img src="{{ $item['src'] }}" alt="{{ $item['caption'] ?: $alt }}" loading="lazy" class="w-full transition duration-500 group-hover:scale-105">
                @else
                    <div class="relative grid aspect-video place-items-center bg-gradient-to-br from-navy-900 to-brand-600 text-white">
                        @if ($item['type'] === 'video')<video src="{{ $item['src'] }}#t=0.5" preload="metadata" muted class="absolute inset-0 h-full w-full object-cover"></video>@endif
                        <span class="relative grid h-14 w-14 place-items-center rounded-full bg-white/90 text-brand-700 shadow-lg"><x-ico name="play" class="h-6 w-6" /></span>
                    </div>
                @endif
            </button>
        @endforeach
    </div>

    {{-- Lightbox --}}
    <div x-cloak x-show="open !== null" x-transition.opacity class="fixed inset-0 z-[90] grid place-items-center bg-navy-950/95 p-4" @click.self="open = null" role="dialog" aria-modal="true">
        <button class="absolute right-5 top-5 grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20" @click="open = null" aria-label="Close"><x-ico name="x" /></button>
        <template x-if="items.length > 1">
            <div>
                <button class="absolute left-3 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20" @click="open = (open - 1 + items.length) % items.length" aria-label="Previous"><x-ico name="arrow" class="h-5 w-5 rotate-180" /></button>
                <button class="absolute right-3 top-1/2 grid h-11 w-11 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20" @click="open = (open + 1) % items.length" aria-label="Next"><x-ico name="arrow" class="h-5 w-5" /></button>
            </div>
        </template>
        <template x-if="open !== null">
            <figure class="max-h-full max-w-5xl">
                <template x-if="items[open].type === 'image'"><img :src="items[open].src" :alt="items[open].caption || ''" class="max-h-[85vh] rounded-xl object-contain"></template>
                <template x-if="items[open].type === 'video'"><video :src="items[open].src" controls autoplay class="max-h-[85vh] rounded-xl"></video></template>
                <template x-if="items[open].type === 'youtube'"><iframe :src="items[open].url.replace('watch?v=', 'embed/')" class="aspect-video w-[90vw] max-w-4xl rounded-xl" allowfullscreen></iframe></template>
                <figcaption x-show="items[open].caption" x-text="items[open].caption" class="mt-3 text-center text-sm text-white/80"></figcaption>
            </figure>
        </template>
    </div>
</div>