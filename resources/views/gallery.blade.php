@extends('layouts.app')
@section('title', 'Gallery - TT Metro Campus')

@section('content')
<x-page-header title="Gallery" subtitle="Moments from our campus - ceremonies, workshops, excursions and more." />

<section class="section">
    <div class="wrap">
        @if ($albums->isEmpty())
            <div class="rounded-2xl border border-dashed border-line p-14 text-center text-muted">Gallery coming soon.</div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($albums as $a)
                    <a href="{{ route('album', $a) }}" class="group relative aspect-[4/3] overflow-hidden rounded-3xl bg-gradient-to-br from-navy-900 to-brand-600 shadow-card">
                        @if ($a->cover_image)<img src="{{ media($a->cover_image) }}" alt="{{ $a->title }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">@endif
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950/90 via-navy-950/20 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                            <h3 class="text-xl font-bold !text-white">{{ $a->title }}</h3>
                            <p class="mt-1 text-sm text-white/75">{{ $a->items_count }} {{ \Illuminate\Support\Str::plural('item', $a->items_count) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection