@extends('layouts.app')

@section('content')
@php
    $hero = media(setting('hero_image'));
    $icons = ['science-technology' => 'cpu', 'engineering' => 'wrench', 'commerce-management' => 'chart', 'arts-culture' => 'palette', 'language-vocational-studies' => 'language'];
@endphp

{{-- Hero --}}
<section class="relative isolate overflow-hidden bg-navy-950 text-white">
    @if ($hero)<img src="{{ $hero }}" alt="" class="absolute inset-0 -z-10 h-full w-full object-cover opacity-40">@endif
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_20%_20%,rgba(17,96,180,0.55),transparent_60%),radial-gradient(ellipse_at_90%_90%,rgba(110,181,255,0.35),transparent_55%)]"></div>
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-navy-950/40 via-transparent to-navy-950/80"></div>
    <div class="wrap grid items-center gap-12 py-20 sm:py-28 lg:grid-cols-12 lg:py-36">
        <div class="lg:col-span-8">
            <span class="reveal inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest backdrop-blur">
                <span class="h-1.5 w-1.5 rounded-full bg-sky-brand"></span> Best higher education network in Sri Lanka
            </span>
            <h1 class="reveal mt-6 text-4xl font-extrabold leading-[1.08] text-white sm:text-6xl" style="animation-delay:.08s">{{ keep_name(setting('hero_title')) }}</h1>
            <p class="reveal mt-6 max-w-2xl text-lg leading-relaxed text-white/80" style="animation-delay:.16s">{{ setting('hero_subtitle') }}</p>
            <div class="reveal mt-9 flex flex-wrap gap-3" style="animation-delay:.24s">
                <a href="{{ route('apply') }}" class="btn btn-light px-7 py-3.5">Apply Now <x-ico name="arrow" class="h-4 w-4" /></a>
                <a href="{{ route('academics') }}" class="btn btn-outline px-7 py-3.5">Explore Programmes</a>
            </div>
        </div>
        <div class="hidden lg:col-span-4 lg:block">
            <div class="reveal rounded-3xl border border-white/15 bg-white/10 p-8 backdrop-blur-md" style="animation-delay:.3s">
                <p class="text-xs font-semibold uppercase tracking-widest text-sky-brand">Admissions open</p>
                <p class="mt-3 font-display text-2xl font-bold leading-snug">{{ setting('admissions_badge') }}</p>
                <p class="mt-2 text-sm text-white/75">{{ setting('admissions_text') }}</p>
                <a href="{{ route('apply') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-white hover:text-sky-brand">Start your application <x-ico name="arrow" class="h-4 w-4" /></a>
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
@if ($stats->isNotEmpty())
<section class="relative z-10 -mt-12" aria-label="Campus at a glance">
    <div class="wrap">
        <div class="card grid grid-cols-2 divide-line p-2 shadow-lift lg:grid-cols-4 lg:divide-x">
            @foreach ($stats as $s)
                <div class="p-6 text-center sm:p-8">
                    <div class="font-display text-4xl font-extrabold text-brand-700 sm:text-5xl"><span data-count="{{ (int) $s->value }}">{{ $s->value }}</span>{{ $s->suffix }}</div>
                    <div class="mt-2 text-sm font-medium text-muted">{{ $s->label }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Welcome --}}
<section class="section">
    <div class="wrap grid items-center gap-12 lg:grid-cols-2 lg:gap-20">
        <div class="reveal">
            <span class="eyebrow">About TT Metro Campus</span>
            <h2 class="h-section">{{ keep_name(setting('welcome_title')) }}</h2>
            <div class="prose-site mt-5">
                @foreach (preg_split('/\R{2,}/', (string) setting('welcome_text')) as $para)<p>{{ $para }}</p>@endforeach
            </div>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('about') }}" class="btn btn-primary">Learn more about us</a>
                <a href="{{ route('contact') }}" class="btn btn-ghost">Talk to us</a>
            </div>
        </div>
        <div class="reveal grid auto-rows-fr grid-cols-2 gap-4">
            @foreach ([['cap', 'Quality Education', 'Structured curricula that meet international academic standards.'], ['users', 'Expert Teachers', 'Qualified lecturers with real industry experience.'], ['briefcase', 'Career Guidance', 'Placement support and professional development after graduation.'], ['star', 'Global Pathways', 'Complete locally or continue your studies internationally.']] as $i => [$ic, $t, $d])
                <div class="card h-full p-6">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-sky-soft text-brand-700"><x-ico :name="$ic" class="h-6 w-6" /></span>
                    <h3 class="mt-4 text-base font-bold">{{ $t }}</h3>
                    <p class="mt-2 text-left text-sm leading-relaxed text-muted">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Faculties --}}
<section class="section bg-surface">
    <div class="wrap">
        <div class="reveal max-w-2xl">
            <span class="eyebrow">Our Faculties</span>
            <h2 class="h-section">Find the right path for your career</h2>
            <p class="lead">From technology and engineering to business, arts and languages - explore programmes across {{ $faculties->count() }} faculties.</p>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($faculties as $f)
                <a href="{{ route('academics', ['faculty' => $f->slug]) }}" class="reveal card card-hover group p-8">
                    <span class="grid h-14 w-14 place-items-center rounded-2xl bg-brand-700 text-white transition group-hover:bg-navy-900"><x-ico :name="$icons[$f->slug] ?? 'cap'" class="h-7 w-7" /></span>
                    <h3 class="mt-6 text-xl font-bold">{{ $f->name }}</h3>
                    <p class="mt-2 text-sm text-muted">{{ $f->programmes_count }} programmes</p>
                    <span class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-brand-700">Explore <x-ico name="arrow" class="h-4 w-4 transition group-hover:translate-x-1" /></span>
                </a>
            @endforeach
            <a href="{{ route('academics') }}" class="reveal flex flex-col justify-center rounded-2xl bg-navy-900 p-8 text-white transition hover:bg-brand-700">
                <h3 class="text-xl font-bold text-white">Browse all programmes</h3>
                <p class="mt-2 text-sm text-white/75">Diploma · HND · Top-up · Degree · Postgraduate</p>
                <span class="mt-6 inline-flex items-center gap-1 text-sm font-semibold">View all <x-ico name="arrow" class="h-4 w-4" /></span>
            </a>
        </div>
    </div>
</section>

{{-- Popular programmes --}}
<section class="section">
    <div class="wrap">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="reveal max-w-2xl">
                <span class="eyebrow">Popular Programmes</span>
                <h2 class="h-section">Programmes students love</h2>
            </div>
            <a href="{{ route('academics') }}" class="btn btn-ghost">View all programmes</a>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($popular as $p)<div class="reveal"><x-programme-card :programme="$p" /></div>@endforeach
        </div>
    </div>
</section>

{{-- Events --}}
@if ($events->isNotEmpty())
<section class="section bg-surface">
    <div class="wrap">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="reveal max-w-2xl">
                <span class="eyebrow">Campus Updates</span>
                <h2 class="h-section">Events &amp; news</h2>
            </div>
            <a href="{{ route('events') }}" class="btn btn-ghost">View all events</a>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($events as $e)<div class="reveal"><x-event-card :event="$e" /></div>@endforeach
        </div>
    </div>
</section>
@endif

{{-- Board --}}
@if ($board->isNotEmpty())
<section class="section">
    <div class="wrap">
        <div class="reveal mx-auto max-w-2xl text-center">
            <span class="eyebrow">Leadership</span>
            <h2 class="h-section">Board of Directors</h2>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($board as $m)
                <div class="reveal"><x-person-card :member="$m" /></div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Testimonials --}}
@if ($testimonials->isNotEmpty())
<section class="section bg-navy-900 text-white">
    <div class="wrap">
        <div class="mx-auto max-w-2xl text-center">
            <span class="eyebrow !text-sky-brand before:bg-sky-brand">Testimonials</span>
            <h2 class="h-section !text-white">What our students say</h2>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach ($testimonials as $t)
                <figure class="rounded-2xl bg-white/10 p-8 backdrop-blur">
                    <blockquote class="leading-relaxed text-white/90">“{{ $t->quote }}”</blockquote>
                    <figcaption class="mt-6 text-sm"><span class="font-bold text-white">{{ $t->name }}</span><span class="block text-white/60">{{ $t->role }}</span></figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA + map --}}
<section class="section">
    <div class="wrap">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-navy-900 to-brand-600 p-10 text-white sm:p-14">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-sky-brand/30 blur-3xl"></div>
            <div class="relative flex flex-wrap items-center justify-between gap-8">
                <div class="max-w-xl">
                    <h2 class="text-3xl font-extrabold !text-white sm:text-4xl">Ready to start your journey?</h2>
                    <p class="mt-3 text-white/80">Apply online in a few minutes - our admissions team will guide you through every step.</p>
                </div>
                <a href="{{ route('apply') }}" class="btn btn-light px-8 py-4">Apply Now <x-ico name="arrow" class="h-4 w-4" /></a>
            </div>
        </div>

        <div class="mt-16 grid items-center gap-10 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <span class="eyebrow">Visit us</span>
                <h2 class="h-section">Find us in Mount Lavinia</h2>
                <ul class="mt-6 space-y-4 text-muted">
                    <li class="flex gap-3"><x-ico name="pin" class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />{{ setting('address') }}</li>
                    <li class="flex gap-3"><x-ico name="phone" class="h-5 w-5 shrink-0 text-brand-600" />{{ setting('phone_1') }}</li>
                    <li class="flex gap-3"><x-ico name="mail" class="h-5 w-5 shrink-0 text-brand-600" />{{ setting('email_1') }}</li>
                </ul>
                <a href="{{ route('contact') }}" class="btn btn-primary mt-8">Contact us</a>
            </div>
            <div class="lg:col-span-3"><x-map /></div>
        </div>
        <x-branches class="mt-20" />
    </div>
</section>
@endsection
