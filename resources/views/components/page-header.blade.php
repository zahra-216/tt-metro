{{-- page="about" takes the words from Site > Pages; otherwise pass title, subtitle and crumb --}}
@props(['title' => null, 'subtitle' => null, 'crumb' => null, 'page' => null])
@php
    if ($page) {
        $title = \App\Support\PageTexts::get($page, 'heading');
        $subtitle = \App\Support\PageTexts::get($page, 'subtitle');
        $crumb = \App\Support\PageTexts::name($page, 'crumb');
    }
@endphp
<section class="relative overflow-hidden bg-navy-900 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(110,181,255,0.35),transparent_55%)]"></div>
    <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-brand-600/30 blur-3xl"></div>
    <div class="wrap relative py-16 sm:py-20">
        <nav class="flex items-center gap-2 text-sm text-white/70" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white">{{ \App\Support\PageTexts::name('home') }}</a><span>/</span><span class="text-white">{{ $crumb ?? $title }}</span>
        </nav>
        <h1 class="mt-4 text-4xl font-extrabold text-white sm:text-5xl">{{ $title }}</h1>
        @if ($subtitle)<p class="mt-4 max-w-2xl text-lg text-white/80">{{ $subtitle }}</p>@endif
    </div>
</section>