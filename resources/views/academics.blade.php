@extends('layouts.app')
@section('title', 'Academics - TT Metro Campus')

@section('content')
<x-page-header title="Academics" subtitle="Diploma, HND, Top-up, Degree and Postgraduate programmes across {{ $faculties->count() }} faculties." />

<section class="section">
    <div class="wrap">
        <form method="get" class="card grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-5" role="search">
            <div class="lg:col-span-2">
                <label class="label" for="q">Search</label>
                <input id="q" name="q" value="{{ request('q') }}" placeholder="e.g. Data Science" class="field">
            </div>
            <div>
                <label class="label" for="faculty">Faculty</label>
                <select id="faculty" name="faculty" class="field">
                    <option value="">All faculties</option>
                    @foreach ($faculties as $f)<option value="{{ $f->slug }}" @selected(request('faculty') === $f->slug)>{{ $f->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="label" for="level">Level</label>
                <select id="level" name="level" class="field">
                    <option value="">All levels</option>
                    @foreach ($levels as $l)<option @selected(request('level') === $l)>{{ $l }}</option>@endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="btn btn-primary flex-1">Filter</button>
                @if (request()->hasAny(['q', 'faculty', 'level']))<a href="{{ route('academics') }}" class="btn btn-ghost">Reset</a>@endif
            </div>
        </form>

        <p class="mt-8 text-sm text-muted">{{ $programmes->total() }} programme{{ $programmes->total() === 1 ? '' : 's' }} found</p>

        @if ($programmes->isEmpty())
            <div class="mt-6 rounded-2xl border border-dashed border-line p-12 text-center text-muted">No programmes match your search. Try a different keyword or reset the filters.</div>
        @else
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($programmes as $p)<x-programme-card :programme="$p" />@endforeach
            </div>
            <div class="mt-12">{{ $programmes->links() }}</div>
        @endif
    </div>
</section>
@endsection