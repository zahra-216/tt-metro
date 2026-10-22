@extends('layouts.app')
@section('title', $album->title.' - Gallery')

@section('content')
<x-page-header :title="$album->title" :subtitle="$album->description" crumb="Album" />

<section class="section">
    <div class="wrap">
        <a href="{{ route('gallery') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-brand-700"><x-ico name="arrow" class="h-4 w-4 rotate-180" /> All albums</a>
        <x-media-grid :alt="$album->title" :items="$album->items->map(fn ($i) => ['type' => $i->type, 'src' => media($i->path), 'url' => $i->video_url, 'caption' => $i->caption])" />
    </div>
</section>
@endsection