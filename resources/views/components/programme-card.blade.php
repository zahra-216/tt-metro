@props(['programme'])
@php $img = media($programme->image); @endphp
<a href="{{ route('programme', $programme) }}" class="card card-hover group flex h-full flex-col overflow-hidden">
    <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-navy-900 to-brand-600">
        @if ($img)
            <img src="{{ $img }}" alt="{{ $programme->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="grid h-full place-items-center text-white/30"><x-ico name="cap" class="h-16 w-16" /></div>
        @endif
        <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1 text-xs font-bold text-brand-700 shadow">{{ $programme->level }}</span>
    </div>
    <div class="flex flex-1 flex-col p-6">
        @if ($programme->faculty)<p class="text-xs font-semibold uppercase tracking-wider text-brand-600">{{ str_replace('Faculty of ', '', $programme->faculty->name) }}</p>@endif
        <h3 class="mt-2 text-lg font-bold leading-snug text-navy-900">{{ $programme->title }}</h3>
        <div class="mt-auto flex items-center justify-between pt-5 text-sm">
            <span class="flex items-center gap-1.5 text-muted">@if ($programme->duration)<x-ico name="clock" class="h-4 w-4" /> {{ $programme->duration }}@endif</span>
            <span class="flex items-center gap-1 font-semibold text-brand-700">Details <x-ico name="arrow" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
        </div>
    </div>
</a>