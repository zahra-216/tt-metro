@props(['event'])
@php $img = media($event->image); @endphp
<a href="{{ route('event', $event) }}" class="card card-hover group flex h-full flex-col overflow-hidden">
    <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-navy-900 to-brand-600">
        @if ($img)<img src="{{ $img }}" alt="{{ $event->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@else<div class="grid h-full place-items-center"><img src="{{ asset('images/logo.jpg') }}" alt="" class="h-20 w-20 rounded-full object-cover opacity-90 ring-4 ring-white/30"></div>@endif
        @if ($event->starts_on)
            <div class="absolute left-4 top-4 rounded-xl bg-white px-3 py-2 text-center shadow-lg">
                <div class="font-display text-xl font-extrabold leading-none text-brand-700">{{ $event->starts_on->format('d') }}</div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-muted">{{ $event->starts_on->format('M Y') }}</div>
            </div>
        @endif
    </div>
    <div class="flex flex-1 flex-col p-6">
        <h3 class="text-lg font-bold leading-snug text-navy-900">{{ $event->title }}</h3>
        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-muted">{{ $event->excerpt }}</p>
        <span class="mt-auto flex items-center gap-1 pt-5 text-sm font-semibold text-brand-700">Read more <x-ico name="arrow" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
    </div>
</a>