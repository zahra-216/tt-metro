@extends('layouts.app')
@section('title', 'Student Life - TT Metro Campus')

@section('content')
<x-page-header title="Student Life" subtitle="Facilities, scholarships and services that make campus life rewarding." />
@php $facilities = $items['facility'] ?? collect(); $scholarships = $items['scholarship'] ?? collect(); @endphp

{{-- Facilities --}}
@if ($facilities->isNotEmpty())
<section id="facility" class="section">
    <div class="wrap">
        <div class="max-w-3xl"><span class="eyebrow">Campus facilities</span><h2 class="h-section">Learning beyond the classroom</h2>
            @if (setting('intro_facilities'))<div class="prose-site mt-4">{!! setting('intro_facilities') !!}</div>@endif
        </div>
        <div class="mt-14 space-y-16">
            @foreach ($facilities as $f)
                <article class="grid items-center gap-8 lg:grid-cols-2 lg:gap-16">
                    <div class="{{ $loop->even ? 'lg:order-2' : '' }}">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-sky-soft font-display text-lg font-extrabold text-brand-700">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="mt-4 text-2xl font-extrabold sm:text-3xl">{{ $f->title }}</h3>
                        <div class="prose-site mt-4">{!! $f->description !!}</div>
                    </div>
                    <div class="{{ $loop->even ? 'lg:order-1' : '' }}">
                        @if ($f->image)<img src="{{ media($f->image) }}" alt="{{ $f->title }}" loading="lazy" class="aspect-[4/3] w-full rounded-3xl object-cover shadow-lift">
                        @else<div class="grid aspect-[4/3] place-items-center rounded-3xl bg-gradient-to-br from-navy-900 to-brand-600 text-white/30"><x-ico name="cap" class="h-20 w-20" /></div>@endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Scholarships --}}
@if ($scholarships->isNotEmpty())
<section id="scholarship" class="section bg-surface" x-data="{ open: null }">
    <div class="wrap max-w-5xl">
        <div class="max-w-3xl"><span class="eyebrow">Scholarships</span><h2 class="h-section">Study abroad with funding support</h2>
            @if (setting('intro_scholarship'))<div class="prose-site mt-4">{!! setting('intro_scholarship') !!}</div>@endif
        </div>
        <div class="mt-10 space-y-3">
            @foreach ($scholarships as $s)
                <div class="card overflow-hidden">
                    <button type="button" class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left" @click="open = open === {{ $s->id }} ? null : {{ $s->id }}" :aria-expanded="open === {{ $s->id }}">
                        <span class="font-display text-lg font-bold text-navy-900">{{ $s->title }}</span>
                        <x-ico name="chevron" class="h-5 w-5 shrink-0 text-brand-600 transition" ::class="open === {{ $s->id }} && 'rotate-180'" />
                    </button>
                    <div x-cloak x-show="open === {{ $s->id }}" x-collapse><div class="prose-site border-t border-line px-6 py-6">{!! $s->description !!}</div></div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Services --}}
<section id="services" class="section">
    <div class="wrap">
        <div class="max-w-3xl"><span class="eyebrow">Student services</span><h2 class="h-section">Everything you need, online</h2></div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ([['book', 'Student Portal', 'Access your courses, materials and records.', setting('student_portal_url')], ['chart', 'Online Results', 'View your examination results online.', 'https://sp.ttmetrocampus.com/view_results.php'], ['shield', 'Verify Certificate', 'Confirm the authenticity of a TT Metro Campus certificate.', setting('verify_url')]] as [$ic, $t, $d, $u])
                @if ($u)
                    <a href="{{ $u }}" target="_blank" rel="noopener" class="card card-hover group p-8">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-brand-700 text-white transition group-hover:bg-navy-900"><x-ico :name="$ic" class="h-7 w-7" /></span>
                        <h3 class="mt-6 text-xl font-bold">{{ $t }}</h3><p class="mt-2 text-sm text-muted">{{ $d }}</p>
                        <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-700">Open <x-ico name="arrow" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
                    </a>
                @endif
            @endforeach
        </div>
        @foreach (collect($items->all())->except(['facility', 'scholarship']) as $cat => $group)
            <h3 class="mt-16 text-2xl font-extrabold capitalize">{{ $cat }}s</h3>
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach ($group as $i)<article class="card p-6"><h4 class="text-lg font-bold">{{ $i->title }}</h4><div class="prose-site mt-2 text-sm">{!! $i->description !!}</div></article>@endforeach</div>
        @endforeach
    </div>
</section>
@endsection