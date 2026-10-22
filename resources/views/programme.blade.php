@extends('layouts.app')
@section('title', $programme->title.' - TT Metro Campus')
@section('description', \Illuminate\Support\Str::limit(strip_tags($programme->summary ?: $programme->title.' at TT Metro Campus, Mount Lavinia, Sri Lanka.'), 155))

@section('content')
<x-page-header :title="$programme->title" :subtitle="$programme->faculty?->name" crumb="Programme" />

<section class="section">
    <div class="wrap grid gap-12 lg:grid-cols-3">
        <div class="lg:col-span-2">
            @if ($programme->image)<img src="{{ media($programme->image) }}" alt="{{ $programme->title }}" class="mb-10 aspect-[16/9] w-full rounded-3xl object-cover shadow-card">@endif

            @if ($programme->summary)<div class="prose-site"><p class="text-lg">{{ $programme->summary }}</p></div>@endif
            @if (filled(strip_tags((string) $programme->content)) && ! str_contains((string) $programme->content, 'download-btn'))<div class="prose-site">{!! $programme->content !!}</div>@endif

            @if ($programme->requirements)
                <h2 class="mt-10 text-2xl font-extrabold">Entry requirements</h2>
                <ul class="mt-5 space-y-3">
                    @foreach ($programme->requirements as $r)
                        <li class="flex gap-3 text-muted"><span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-sky-soft text-brand-700"><x-ico name="check" class="h-3.5 w-3.5" /></span>{{ $r }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <aside class="space-y-6">
            <div class="card p-7">
                <dl class="space-y-4 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-muted">Level</dt><dd class="font-semibold">{{ $programme->level }}</dd></div>
                    @if ($programme->duration)<div class="flex justify-between gap-4"><dt class="text-muted">Duration</dt><dd class="font-semibold">{{ $programme->duration }}</dd></div>@endif
                    @if ($programme->faculty)<div class="flex justify-between gap-4"><dt class="text-muted">Faculty</dt><dd class="text-right font-semibold">{{ $programme->faculty->name }}</dd></div>@endif
                    @if ($programme->awarding_body)<div class="flex justify-between gap-4"><dt class="text-muted">Awarding body</dt><dd class="text-right font-semibold">{{ $programme->awarding_body }}</dd></div>@endif
                </dl>
                <a href="{{ route('apply', ['programme' => $programme->id]) }}" class="btn btn-primary mt-7 w-full">Apply for this programme</a>
                @if ($programme->brochure)
                    <a href="{{ media($programme->brochure) }}" target="_blank" class="btn btn-ghost mt-3 w-full"><x-ico name="download" class="h-4 w-4" /> Download brochure</a>
                @endif
            </div>
            <div class="rounded-2xl bg-navy-900 p-7 text-white">
                <h3 class="text-lg font-bold text-white">Need advice?</h3>
                <p class="mt-2 text-sm text-white/75">Speak to our admissions team about entry requirements, fees and intakes.</p>
                <a href="tel:{{ preg_replace('/[^\d+]/', '', setting('phone_1')) }}" class="mt-5 inline-flex items-center gap-2 font-semibold hover:text-sky-brand"><x-ico name="phone" class="h-4 w-4" /> {{ setting('phone_1') }}</a>
            </div>
        </aside>
    </div>
</section>

@if ($related->isNotEmpty())
<section class="section bg-surface">
    <div class="wrap">
        <h2 class="text-2xl font-extrabold sm:text-3xl">Related programmes</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach ($related as $r)<x-programme-card :programme="$r" />@endforeach</div>
    </div>
</section>
@endif
@endsection