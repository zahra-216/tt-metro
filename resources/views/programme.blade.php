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
                        <li class="flex gap-3 text-muted"><span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-sky-soft text-brand-700"><x-ico name="check" class="h-3.5 w-3.5" /></span><span data-typeset class="min-w-0 flex-1">{{ $r }}</span></li>
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
            @php($semesters = $programme->modulesBySemester())
            @if ($semesters)
                <div class="card p-7" x-data="{ sem: {{ array_key_first($semesters) }} }">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-lg font-bold">Modules</h2>
                        <span class="text-xs text-muted">{{ collect($semesters)->flatten()->count() }} modules</span>
                    </div>
                    <div class="mt-4 flex items-center gap-3">
                        <span class="shrink-0 text-sm font-semibold text-muted">Sem</span>
                        <div class="flex min-w-0 flex-1 gap-1.5" role="tablist" aria-label="Semesters">
                            @foreach ($semesters as $n => $mods)
                                <button type="button" role="tab" aria-label="Semester {{ $n }}" @click="sem = {{ $n }}" :aria-selected="sem === {{ $n }}" :class="sem === {{ $n }} ? 'bg-brand-700 text-white shadow' : 'bg-surface text-navy-900 hover:bg-sky-soft'" class="grid aspect-square min-w-0 max-w-10 flex-1 place-items-center rounded-full text-sm font-semibold transition">{{ $n }}</button>
                            @endforeach
                        </div>
                    </div>
                    @foreach ($semesters as $n => $mods)
                        <div x-cloak x-show="sem === {{ $n }}" role="tabpanel" class="mt-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-muted">Semester {{ $n }} - {{ count($mods) }} {{ \Illuminate\Support\Str::plural('module', count($mods)) }}</p>
                            <ol class="mt-3 space-y-2.5 text-left text-sm">
                                @foreach ($mods as $i => $m)
                                    <li class="flex gap-3"><span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-full bg-sky-soft text-xs font-bold text-brand-700">{{ $i + 1 }}</span><span class="text-left text-navy-900">{{ $m }}</span></li>
                                @endforeach
                            </ol>
                        </div>
                    @endforeach
                </div>
            @endif
            <div id="advice" class="scroll-mt-28 rounded-2xl bg-navy-900 p-7 text-white">
                <h3 class="text-lg font-bold text-white">Need advice?</h3>
                <p class="mt-2 text-sm text-white/75">Speak to our admissions team about entry requirements, fees and intakes.</p>

                @if (session('advice_whatsapp'))
                    <div class="mt-5 rounded-xl bg-emerald-600 p-4 text-sm text-white" role="status">
                        <p class="font-semibold text-white">Thank you! Opening WhatsApp so you can send your message...</p>
                        <p class="mt-1 text-white/90">Please press <strong>Send</strong> in WhatsApp. If it did not open, use the button below.</p>
                        <a id="advice-wa" href="{{ session('advice_whatsapp') }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 font-semibold text-emerald-700"><x-ico name="whatsapp" class="h-4 w-4" /> Open WhatsApp</a>
                    </div>
                    <script>setTimeout(function () { var a = document.getElementById('advice-wa'); if (a) { window.location.href = a.href; } }, 1500);</script>
                @else
                    <form method="POST" action="{{ route('programme.advice', $programme) }}" class="mt-5 space-y-3 text-left" novalidate>
                        @csrf
                        <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                        <div>
                            <label for="adv-name" class="mb-1 block text-xs font-semibold text-white/80">Your name *</label>
                            <input id="adv-name" name="name" value="{{ old('name') }}" required maxlength="120" autocomplete="name" class="field">
                            @error('name', 'advice')<p class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="adv-phone" class="mb-1 block text-xs font-semibold text-white/80">WhatsApp number *</label>
                            <input id="adv-phone" name="phone" type="tel" inputmode="tel" value="{{ old('phone') }}" required maxlength="30" autocomplete="tel" placeholder="077 123 4567" class="field">
                            @error('phone', 'advice')<p class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="adv-message" class="mb-1 block text-xs font-semibold text-white/80">Message *</label>
                            <textarea id="adv-message" name="message" rows="3" required maxlength="1500" class="field">{{ old('message') }}</textarea>
                            @error('message', 'advice')<p class="mt-1 text-xs text-red-300">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn btn-light w-full justify-center"><x-ico name="whatsapp" class="h-4 w-4" /> Send on WhatsApp</button>
                    </form>
                @endif
                @if ($errors->advice->any())<script>document.getElementById('advice').scrollIntoView({ block: 'center' });</script>@endif

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