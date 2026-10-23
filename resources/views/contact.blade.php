@extends('layouts.app')
@section('title', 'Contact Us - TT Metro Campus')

@section('content')
<x-page-header title="Contact Us" subtitle="We would love to hear from you. Reach out with any questions about admissions or programmes." crumb="Contact" />

<section class="section">
    <div class="wrap grid gap-10 lg:grid-cols-5">
        <div class="space-y-4 lg:col-span-2">
            @foreach ([['pin', 'Visit us', setting('address')], ['phone', 'Call us', collect([setting('phone_1'), setting('phone_2')])->filter()->all()], ['mail', 'Email us', collect([setting('email_1'), setting('email_2')])->filter()->all()], ['clock', 'Opening hours', setting('opening_hours')]] as [$ic, $t, $v])
                @if ($v)
                    @php
                        $tint = ['pin' => 'bg-red-50 text-red-600', 'phone' => 'bg-emerald-50 text-emerald-600', 'mail' => 'bg-amber-50 text-amber-600', 'clock' => 'bg-violet-50 text-violet-600'][$ic];
                    @endphp
                    <div class="card flex gap-4 p-6">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl {{ $tint }}"><x-ico :name="$ic" class="h-6 w-6" /></span>
                        <div><h3 class="text-base font-bold">{{ $t }}</h3><div class="mt-1 space-y-1 break-words text-sm leading-relaxed text-muted">@foreach ((array) $v as $line)<p>@if ($t === 'Call us')<a href="tel:{{ preg_replace('/[^\d+]/', '', $line) }}" class="hover:text-brand-700">{{ $line }}</a>@elseif ($t === 'Email us')<a href="mailto:{{ $line }}" class="hover:text-brand-700">{{ $line }}</a>@else{{ $line }}@endif</p>@endforeach</div></div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="card p-7 sm:p-10 lg:col-span-3">
            <h2 class="text-2xl font-extrabold">Send us a message</h2>
            @if (session('success'))<div class="mt-5 rounded-xl bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800" role="status">{{ session('success') }}</div>@endif
            <form method="post" action="{{ route('contact.submit') }}" class="mt-6 grid gap-5 sm:grid-cols-2" novalidate>
                @csrf
                <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                <div><label class="label" for="name">Full name *</label><input id="name" name="name" value="{{ old('name') }}" required class="field">@error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="label" for="email">Email *</label><input id="email" type="email" name="email" value="{{ old('email') }}" required class="field">@error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><label class="label" for="phone">Phone</label><input id="phone" name="phone" value="{{ old('phone') }}" class="field"></div>
                <div><label class="label" for="subject">Subject</label><input id="subject" name="subject" value="{{ old('subject') }}" class="field"></div>
                <div class="sm:col-span-2"><label class="label" for="message">Message *</label><textarea id="message" name="message" rows="5" required class="field">{{ old('message') }}</textarea>@error('message')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div class="sm:col-span-2"><button class="btn btn-primary px-8">Send message</button></div>
            </form>
        </div>
    </div>
    <div class="wrap mt-12"><x-map /></div>
    <x-branches class="wrap mt-16" />
</section>
@endsection