@extends('layouts.app')
@section('title', 'About Us - TT Metro Campus')

@section('content')
@php
    $tabs = ['board' => 'Board of Directors', 'council' => 'Governing Council', 'senate' => 'Academic Senate', 'staff' => 'Management Staff', 'lecturers' => 'Lecturers'];
    $tabs = array_filter($tabs, fn ($label, $key) => ($groups[$key] ?? collect())->isNotEmpty(), ARRAY_FILTER_USE_BOTH);
    $first = array_key_first($tabs);
    $paras = fn ($t) => array_filter(preg_split('/\R{2,}/', (string) $t));
@endphp
<x-page-header title="About Us" subtitle="An innovative higher education institute established in 2015 in Sri Lanka." />

{{-- Story + stats --}}
<section class="section">
    <div class="wrap grid gap-12 lg:grid-cols-5 lg:gap-16">
        <div class="lg:col-span-3">
            <span class="eyebrow">About TTMC</span>
            <h2 class="h-section">{{ setting('welcome_title') }}</h2>
            <div class="prose-site mt-5">@foreach ($paras(setting('welcome_text')) as $p)<p>{{ $p }}</p>@endforeach</div>
        </div>
        <div class="grid auto-rows-fr grid-cols-2 gap-4 lg:col-span-2">
            @foreach ($stats as $s)
                <div class="card flex flex-col items-center justify-center p-6 text-center">
                    <div class="font-display text-4xl font-extrabold text-brand-700">{{ $s->value }}{{ $s->suffix }}</div>
                    <div class="mt-2 text-sm text-muted">{{ $s->label }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Chairman --}}
@if (setting('chairman_message'))
<section class="section bg-surface">
    <div class="wrap grid items-center gap-12 lg:grid-cols-5">
        @if (setting('chairman_image'))
            <div class="lg:col-span-2"><img src="{{ media(setting('chairman_image')) }}" alt="Founder Chairman" class="mx-auto w-full max-w-md rounded-3xl object-cover shadow-lift"></div>
        @endif
        <div class="{{ setting('chairman_image') ? 'lg:col-span-3' : 'lg:col-span-5' }}">
            <span class="eyebrow">Message from the Founder Chairman</span>
            <div class="prose-site mt-5">{!! setting('chairman_message') !!}</div>
            @if ($b = ($groups['board'] ?? collect())->first())<p class="mt-6 font-display text-lg font-bold text-navy-900">{{ $b->name }}<span class="block text-sm font-medium text-muted">{{ $b->position }}</span></p>@endif
        </div>
    </div>
</section>
@endif

{{-- Vision / mission / motto --}}
@if (setting('vision') || setting('mission') || setting('motto'))
<section class="section">
    <div class="wrap grid gap-6 md:grid-cols-3">
        @foreach (['vision' => 'Vision', 'mission' => 'Mission', 'motto' => 'Motto'] as $k => $label)
            @if (setting($k))
                <div class="card p-8">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-brand-700 text-white"><x-ico :name="['vision' => 'star', 'mission' => 'cap', 'motto' => 'check'][$k]" class="h-6 w-6" /></span>
                    <h3 class="mt-5 text-xl font-bold">{{ $label }}</h3>
                    <p class="mt-3 leading-relaxed text-muted">{{ setting($k) }}</p>
                </div>
            @endif
        @endforeach
    </div>
</section>
@endif

{{-- What we do etc. --}}
@php $blocks = ['what_we_do' => 'What we do', 'program_options' => 'Programme options', 'registrations' => 'Registrations & partnerships', 'satellite' => 'Satellite centre opportunity', 'values' => 'Our values']; @endphp
<section class="section bg-surface">
    <div class="wrap grid gap-6 md:grid-cols-2">
        @foreach ($blocks as $k => $label)
            @if (setting($k))
                <div class="card p-8 {{ $loop->first ? 'md:col-span-2' : '' }}">
                    <h3 class="text-xl font-bold">{{ $label }}</h3>
                    <div class="prose-site mt-3">{!! setting($k) !!}</div>
                </div>
            @endif
        @endforeach
    </div>
</section>

{{-- People --}}
@if ($tabs)
<section id="leadership" class="section" x-data="{ tab: '{{ request()->query('team', $first) }}' }">
    <div class="wrap">
        <div class="max-w-2xl"><span class="eyebrow">Our people</span><h2 class="h-section">The team behind TT Metro Campus</h2></div>
        <div class="mt-8 flex flex-wrap gap-2" role="tablist">
            @foreach ($tabs as $key => $label)
                <button type="button" role="tab" @click="tab = '{{ $key }}'" :aria-selected="tab === '{{ $key }}'" :class="tab === '{{ $key }}' ? 'bg-brand-700 text-white shadow' : 'bg-surface text-navy-900 hover:bg-sky-soft'" class="rounded-full px-5 py-2.5 text-sm font-semibold transition">{{ $label }} <span class="ml-1 opacity-60">{{ $groups[$key]->count() }}</span></button>
            @endforeach
        </div>
        @foreach ($tabs as $key => $label)
            <div x-cloak x-show="tab === '{{ $key }}'" role="tabpanel">
                @if (setting('intro_'.$key))<div class="prose-site mt-8 max-w-4xl">{!! setting('intro_'.$key) !!}</div>@endif
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 2xl:grid-cols-5">
                    @foreach ($groups[$key] as $m)<x-person-card :member="$m" />@endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- Partners --}}
@if ($partners->isNotEmpty())
<section id="partners" class="section bg-surface">
    <div class="wrap">
        <div class="mx-auto max-w-3xl text-center">
            <span class="eyebrow">Affiliations</span><h2 class="h-section">Our partners &amp; affiliations</h2>
            @if (setting('intro_partners'))<div class="prose-site mt-4">{!! setting('intro_partners') !!}</div>@endif
        </div>
        <div class="mt-12 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-5 2xl:grid-cols-6">
            @foreach ($partners as $p)
                <div class="card grid h-36 place-items-center p-5">
                    @if ($p->logo)<img src="{{ media($p->logo) }}" alt="{{ $p->name }}" loading="lazy" class="max-h-24 w-full object-contain">@else<span class="text-center text-sm font-semibold text-navy-900">{{ $p->name }}</span>@endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection