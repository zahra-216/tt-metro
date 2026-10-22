@extends('layouts.app')
@section('title', $post->title.' - TT Metro Campus')

@section('content')
<x-page-header :title="$post->title" crumb="News" />
<section class="section"><div class="wrap max-w-3xl">
    @if ($post->image)<img src="{{ media($post->image) }}" alt="" class="mb-10 w-full rounded-3xl object-cover shadow-card">@endif
    <p class="mb-6 text-sm font-semibold text-brand-600">{{ $post->published_at?->format('d F Y') }}</p>
    <div class="prose-site">{!! $post->content !!}</div>
</div></section>
@endsection