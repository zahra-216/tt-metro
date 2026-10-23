<!DOCTYPE html>
<html lang="en" class="scroll-pt-28">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trim($__env->yieldContent('title')) ?: 'TT Metro Campus - Higher Education Institute, Sri Lanka' }}</title>
    <meta name="description" content="@yield('description', 'TT Metro Campus is a leading higher education institute in Mount Lavinia, Sri Lanka, offering Diploma, HND, Degree and Postgraduate programmes.')">
    <meta name="theme-color" content="#0a2a52">
    <link rel="icon" href="{{ asset('images/logo.jpg') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ menu: false }" :class="menu && 'overflow-hidden'">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[100] focus:rounded focus:bg-white focus:px-4 focus:py-2">Skip to content</a>

    {{-- Announcement & portal bar --}}
    <div class="bg-bar text-white">
        <div class="flex w-full items-center justify-between gap-4 px-4 py-2.5 text-sm font-medium sm:px-6">
            <div class="flex min-w-0 items-center gap-3">
                @if (setting('admissions_badge'))
                    <span class="shrink-0 rounded-md bg-white px-3 py-1 text-xs font-bold tracking-wide text-brand-700">{{ setting('admissions_badge') }}</span>
                @endif
                <a href="{{ route('apply') }}" class="hidden truncate hover:underline md:block">{{ setting('admissions_text') }}</a>
            </div>
            <nav class="hidden shrink-0 items-center gap-6 lg:flex" aria-label="Portals">
                @if (setting('student_portal_url'))
                    <a href="{{ setting('student_portal_url') }}" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:underline"><x-ico name="book" class="h-4 w-4" /> Student Portal</a>
                @endif
                @if (setting('staff_portal_url'))
                    <a href="{{ setting('staff_portal_url') }}" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:underline"><x-ico name="briefcase" class="h-4 w-4" /> Staff Portal</a>
                @endif
                @if (setting('verify_url'))
                    <a href="{{ setting('verify_url') }}" target="_blank" rel="noopener" class="flex items-center gap-1.5 hover:underline"><x-ico name="shield" class="h-4 w-4" /> Verify Certificate</a>
                @endif
            </nav>
        </div>
    </div>

    {{-- Main navigation --}}
    <header class="sticky top-0 z-50 border-b border-line bg-white/90 backdrop-blur-lg" x-data="{ open: null }" @keydown.escape.window="open = null; menu = false">
        <div class="flex h-[5.25rem] w-full items-center justify-between gap-3 px-4 sm:gap-6 sm:px-6">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" alt="TT Metro Campus logo" class="h-12 w-12 rounded-full object-cover">
                <span class="leading-tight">
                    <span class="block whitespace-nowrap font-display text-[17px] font-extrabold tracking-tight text-brand-700 min-[400px]:text-xl sm:text-[26px] 2xl:text-[32px]">TT METRO CAMPUS</span>
                    <span class="mt-0.5 hidden whitespace-nowrap text-[13px] font-medium tracking-wide text-muted sm:block">Empowering Minds, Shaping Futures.</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 xl:flex" aria-label="Main">
                @php
                    $link = 'flex items-center gap-1 whitespace-nowrap rounded-xl px-3 py-2.5 text-[15px] font-semibold text-brand-700 transition hover:bg-sky-soft';
                    $active = 'bg-sky-soft';
                @endphp
                <a href="{{ route('home') }}" class="{{ $link }} {{ request()->routeIs('home') ? $active : '' }}">Home</a>

                <div class="relative" @mouseenter="open = 'about'" @mouseleave="open = null">
                    <a href="{{ route('about') }}" class="{{ $link }} {{ request()->routeIs('about') ? $active : '' }}">About Us <x-ico name="chevron" class="h-3.5 w-3.5" /></a>
                    <div x-cloak x-show="open === 'about'" x-transition.opacity class="absolute left-0 top-full w-60 pt-2">
                        <div class="rounded-2xl border border-line bg-white p-2 shadow-lift">
                            <a class="block rounded-lg px-3 py-2 text-sm font-medium text-navy-900 hover:bg-sky-soft" href="{{ route('about') }}">Our Story</a>
                            <a class="block rounded-lg px-3 py-2 text-sm font-medium text-navy-900 hover:bg-sky-soft" href="{{ route('about') }}#leadership">Leadership</a>
                            <a class="block rounded-lg px-3 py-2 text-sm font-medium text-navy-900 hover:bg-sky-soft" href="{{ route('about') }}#partners">Partners &amp; Affiliations</a>
                        </div>
                    </div>
                </div>

                <div class="relative" @mouseenter="open = 'academics'" @mouseleave="open = null">
                    <a href="{{ route('academics') }}" class="{{ $link }} {{ request()->routeIs('academics', 'programme') ? $active : '' }}">Academics <x-ico name="chevron" class="h-3.5 w-3.5" /></a>
                    <div x-cloak x-show="open === 'academics'" x-transition.opacity class="absolute left-0 top-full w-80 pt-2">
                        <div class="rounded-2xl border border-line bg-white p-2 shadow-lift">
                            <a class="block rounded-lg px-3 py-2 text-sm font-semibold text-brand-700 hover:bg-sky-soft" href="{{ route('academics') }}">All Programmes</a>
                            <div class="my-1 border-t border-line"></div>
                            @foreach ($navFaculties as $f)
                                <a class="block rounded-lg px-3 py-2 text-sm font-medium text-navy-900 hover:bg-sky-soft" href="{{ route('academics', ['faculty' => $f->slug]) }}">{{ $f->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="relative" @mouseenter="open = 'life'" @mouseleave="open = null">
                    <a href="{{ route('student-life') }}" class="{{ $link }} {{ request()->routeIs('student-life') ? $active : '' }}">Student Life <x-ico name="chevron" class="h-3.5 w-3.5" /></a>
                    <div x-cloak x-show="open === 'life'" x-transition.opacity class="absolute left-0 top-full w-56 pt-2">
                        <div class="rounded-2xl border border-line bg-white p-2 shadow-lift">
                            @foreach (['facility' => 'Facilities', 'scholarship' => 'Scholarships', 'services' => 'Student Services'] as $key => $label)
                                <a class="block rounded-lg px-3 py-2 text-sm font-medium text-navy-900 hover:bg-sky-soft" href="{{ route('student-life') }}#{{ $key }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('gallery') }}" class="{{ $link }} {{ request()->routeIs('gallery', 'album') ? $active : '' }}">Gallery</a>
                <a href="{{ route('events') }}" class="{{ $link }} {{ request()->routeIs('events', 'event', 'post') ? $active : '' }}">Events</a>
                <a href="{{ route('contact') }}" class="{{ $link }} {{ request()->routeIs('contact') ? $active : '' }}">Contact</a>
            </nav>

            <div class="flex items-center gap-2">
                <form action="{{ route('academics') }}" method="get" class="hidden items-center 2xl:flex" role="search">
                    <label class="sr-only" for="nav-search">Search programmes</label>
                    <input id="nav-search" name="q" type="search" placeholder="Search programmes…" class="h-11 w-40 rounded-full border border-transparent bg-surface px-5 text-sm outline-none transition focus:w-56 focus:border-brand-600 focus:bg-white">
                </form>
                <a href="{{ route('academics') }}" class="grid h-11 w-11 place-items-center rounded-full bg-surface text-navy-900 transition hover:bg-sky-soft 2xl:hidden" aria-label="Search programmes"><x-ico name="search" /></a>
                <a href="{{ route('apply') }}" class="btn btn-primary hidden whitespace-nowrap sm:inline-flex">Apply Now</a>
                <button type="button" class="grid h-11 w-11 place-items-center rounded-full bg-surface text-navy-900 xl:hidden" @click="menu = !menu" :aria-expanded="menu" aria-label="Toggle menu">
                    <x-ico name="menu" x-show="!menu" /><x-ico name="x" x-cloak x-show="menu" />
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-cloak x-show="menu" x-transition class="max-h-[calc(100dvh-8.5rem)] overflow-y-auto overscroll-contain border-t border-line bg-white xl:hidden">
            <nav class="w-full space-y-1 px-4 py-4 sm:px-6" aria-label="Mobile">
                @foreach ([['Home', route('home')], ['About Us', route('about')], ['Academics', route('academics')], ['Student Life', route('student-life')], ['Gallery', route('gallery')], ['Events', route('events')], ['Contact', route('contact')]] as [$label, $url])
                    <a href="{{ $url }}" class="block rounded-xl px-4 py-3 text-base font-semibold text-brand-700 hover:bg-sky-soft">{{ $label }}</a>
                @endforeach
                <a href="{{ route('apply') }}" class="btn btn-primary mt-3 w-full">Apply Now</a>
                <div class="mt-4 grid grid-cols-1 gap-2 border-t border-line pt-4 text-sm font-semibold text-navy-900">
                    @if (setting('student_portal_url'))<a class="flex items-center gap-2 rounded-xl bg-sky-soft px-4 py-3" href="{{ setting('student_portal_url') }}"><x-ico name="book" class="h-4 w-4" /> Student Portal</a>@endif
                    @if (setting('staff_portal_url'))<a class="flex items-center gap-2 rounded-xl bg-sky-soft px-4 py-3" href="{{ setting('staff_portal_url') }}"><x-ico name="briefcase" class="h-4 w-4" /> Staff Portal</a>@endif
                    @if (setting('verify_url'))<a class="flex items-center gap-2 rounded-xl bg-sky-soft px-4 py-3" href="{{ setting('verify_url') }}"><x-ico name="shield" class="h-4 w-4" /> Verify Certificate</a>@endif
                </div>
            </nav>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-navy-950 text-white/80">
        <div class="wrap grid gap-12 py-16 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="" class="h-12 w-12 rounded-full object-cover">
                    <span class="font-display text-lg font-extrabold text-white">TT METRO CAMPUS</span>
                </div>
                <p class="mt-5 text-left text-sm leading-relaxed">An innovative higher education institute in Sri Lanka, established in 2015, delivering nationally and internationally recognised programmes.</p>
                <div class="mt-6 flex gap-3">
                    @foreach (['facebook', 'instagram', 'youtube', 'linkedin', 'tiktok'] as $s)
                        @if (setting($s))
                            <a href="{{ setting($s) }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($s) }}" class="grid h-10 w-10 place-items-center rounded-full bg-white/10 transition hover:bg-sky-brand hover:text-navy-950"><x-ico :name="$s" class="h-5 w-5" /></a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-widest text-white">Quick Links</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    @foreach ([['Home', route('home')], ['About Us', route('about')], ['Academics', route('academics')], ['Student Life', route('student-life')], ['Gallery', route('gallery')], ['Events', route('events')], ['Contact Us', route('contact')], ['Apply Now', route('apply')]] as [$label, $url])
                        <li><a href="{{ $url }}" class="transition hover:text-sky-brand">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-widest text-white">Faculties</h3>
                <ul class="mt-5 space-y-3 text-sm">
                    @foreach ($navFaculties as $f)
                        <li><a href="{{ route('academics', ['faculty' => $f->slug]) }}" class="transition hover:text-sky-brand">{{ $f->name }}</a></li>
                    @endforeach
                </ul>
                <h3 class="mt-8 font-display text-sm font-bold uppercase tracking-widest text-white">Portals</h3>
                <ul class="mt-4 space-y-3 text-sm">
                    @if (setting('student_portal_url'))<li><a href="{{ setting('student_portal_url') }}" class="transition hover:text-sky-brand">Student Portal</a></li>@endif
                    @if (setting('staff_portal_url'))<li><a href="{{ setting('staff_portal_url') }}" class="transition hover:text-sky-brand">Staff Portal</a></li>@endif
                    @if (setting('verify_url'))<li><a href="{{ setting('verify_url') }}" class="transition hover:text-sky-brand">Verify Certificate</a></li>@endif
                </ul>
            </div>
            <div>
                <h3 class="font-display text-sm font-bold uppercase tracking-widest text-white">Contact</h3>
                <ul class="mt-5 space-y-4 text-sm">
                    @if (setting('address'))<li class="flex gap-3"><x-ico name="pin" class="mt-0.5 h-5 w-5 shrink-0 text-sky-brand" /><span>{{ setting('address') }}</span></li>@endif
                    @foreach (['phone_1', 'phone_2', 'phone_landline'] as $k)
                        @if (setting($k))<li class="flex gap-3"><x-ico name="phone" class="h-5 w-5 shrink-0 text-sky-brand" /><a href="tel:{{ preg_replace('/[^\d+]/', '', setting($k)) }}" class="hover:text-sky-brand">{{ setting($k) }}</a></li>@endif
                    @endforeach
                    @foreach (['email_1', 'email_2'] as $k)
                        @if (setting($k))<li class="flex gap-3"><x-ico name="mail" class="h-5 w-5 shrink-0 text-sky-brand" /><a href="mailto:{{ setting($k) }}" class="break-all hover:text-sky-brand">{{ setting($k) }}</a></li>@endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="wrap flex flex-col items-center justify-between gap-2 py-6 text-xs sm:flex-row">
                <p>© {{ date('Y') }} TT Metro Campus. All rights reserved.</p>
                <p>Higher Education Institute · Mount Lavinia, Sri Lanka</p>
            </div>
        </div>
    </footer>
</body>
</html>
