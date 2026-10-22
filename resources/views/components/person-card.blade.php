@props(['member'])
<div class="card overflow-hidden text-center">
    <div class="aspect-[4/4.2] bg-gradient-to-b from-sky-soft to-white">
        @if ($member->photo)
            <img src="{{ media($member->photo) }}" alt="{{ $member->name }}" loading="lazy" class="h-full w-full object-cover object-top">
        @else
            <div class="grid h-full place-items-center"><div class="grid h-24 w-24 place-items-center rounded-full bg-gradient-to-br from-navy-900 to-brand-600 font-display text-3xl font-extrabold text-white">{{ collect(explode(' ', preg_replace('/^(Mr|Ms|Mrs|Miss|Dr|Prof)\.?\s+/i', '', $member->name)))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}</div></div>
        @endif
    </div>
    <div class="p-5">
        <h3 class="text-base font-bold leading-snug">{{ $member->name }}</h3>
        @if ($member->position)<p class="mt-1.5 text-sm leading-snug text-muted">{{ $member->position }}</p>@endif
        @if ($member->bio)<p class="mt-1 text-xs leading-snug text-muted/80">{{ $member->bio }}</p>@endif
    </div>
</div>