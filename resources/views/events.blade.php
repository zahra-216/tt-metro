@extends('layouts.app')
@section('title', 'Events - TT Metro Campus')

@section('content')
<x-page-header title="Events &amp; News" subtitle="Celebrations, workshops, partnerships and milestones at TT Metro Campus." crumb="Events" />

<section class="section">
    <div class="wrap">
        @if ($news->isNotEmpty())
            <div class="mb-16">
                <h2 class="text-2xl font-extrabold sm:text-3xl">Latest news</h2>
                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach ($news as $n)
                        <a href="{{ route('post', $n) }}" class="card card-hover group overflow-hidden">
                            @if ($n->image)<img src="{{ media($n->image) }}" alt="" loading="lazy" class="aspect-[16/9] w-full object-cover">@endif
                            <div class="p-6"><p class="text-xs font-semibold uppercase tracking-wider text-brand-600">{{ $n->published_at?->format('d M Y') }}</p><h3 class="mt-2 text-lg font-bold leading-snug">{{ $n->title }}</h3><p class="mt-2 line-clamp-3 text-sm text-muted">{{ $n->excerpt }}</p></div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <h2 class="text-2xl font-extrabold sm:text-3xl">All events</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($events as $e)<x-event-card :event="$e" />@empty<p class="text-muted">No events yet.</p>@endforelse
        </div>
        <div class="mt-12">{{ $events->links() }}</div>
    </div>
</section>
@endsection