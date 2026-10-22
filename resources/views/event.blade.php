@extends('layouts.app')
@section('title', $event->title.' - TT Metro Campus')
@section('description', \Illuminate\Support\Str::limit(strip_tags((string) $event->excerpt), 155))

@section('content')
@php
    $media = collect();
    if ($event->image) { $media->push(['type' => 'image', 'src' => media($event->image), 'url' => null, 'caption' => null]); }
    foreach ($event->images as $i) { $media->push(['type' => $i->type, 'src' => media($i->path), 'url' => $i->video_url, 'caption' => $i->caption]); }
    $body = trim((string) $event->content) !== '' ? $event->content : '<p>'.e($event->excerpt).'</p>';
@endphp
<x-page-header :title="$event->title" crumb="Event" />

<section class="section">
    <div class="wrap grid gap-12 lg:grid-cols-3">
        <article class="lg:col-span-2">
            <div class="prose-site">{!! $body !!}</div>
        </article>
        <aside class="space-y-6">
            <div class="card p-7 text-sm">
                @if ($event->starts_on)<p class="flex items-center gap-3"><x-ico name="calendar" class="h-5 w-5 text-brand-600" /><span class="font-semibold">{{ $event->starts_on->format('d F Y') }}@if ($event->ends_on && ! $event->ends_on->isSameDay($event->starts_on)) - {{ $event->ends_on->format('d F Y') }}@endif</span></p>@endif
                @if ($event->location)<p class="mt-4 flex items-center gap-3"><x-ico name="pin" class="h-5 w-5 text-brand-600" /><span class="font-semibold">{{ $event->location }}</span></p>@endif
                <a href="{{ route('events') }}" class="btn btn-ghost mt-6 w-full">All events</a>
            </div>
        </aside>
    </div>

    @if ($media->isNotEmpty())
        <div class="wrap mt-14">
            <h2 class="mb-6 text-2xl font-extrabold">Photos</h2>
            <x-media-grid :alt="$event->title" :items="$media" />
        </div>
    @endif
</section>

@if ($others->isNotEmpty())
<section class="section bg-surface"><div class="wrap"><h2 class="text-2xl font-extrabold sm:text-3xl">More events</h2><div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach ($others as $o)<x-event-card :event="$o" />@endforeach</div></div></section>
@endif
@endsection