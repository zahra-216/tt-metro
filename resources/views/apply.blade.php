@extends('layouts.app')
@section('title', 'Apply Now - TT Metro Campus')

@section('content')
<x-page-header title="Apply Now" subtitle="Tell us a little about yourself and our admissions team will get in touch." crumb="Apply" />

<section class="section">
    <div class="wrap max-w-3xl">
        <div class="card p-7 sm:p-10">
            @if (session('success'))
                <div class="rounded-2xl bg-emerald-50 p-8 text-center text-emerald-900" role="status">
                    <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-emerald-100"><x-ico name="check" class="h-7 w-7" /></span>
                    <h2 class="mt-4 text-2xl font-extrabold !text-emerald-900">Application received</h2>
                    <p class="mt-2 text-sm">{{ session('success') }}</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-6">Back to home</a>
                </div>
            @else
                <form method="post" action="{{ route('apply.submit') }}" class="grid gap-5 sm:grid-cols-2" novalidate>
                    @csrf
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                    <div class="sm:col-span-2"><label class="label" for="full_name">Full name *</label><input id="full_name" name="full_name" value="{{ old('full_name') }}" required class="field">@error('full_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="label" for="email">Email *</label><input id="email" type="email" name="email" value="{{ old('email') }}" required class="field">@error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="label" for="phone">Phone / WhatsApp *</label><input id="phone" name="phone" value="{{ old('phone') }}" required class="field">@error('phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="label" for="nic">NIC / Passport no.</label><input id="nic" name="nic" value="{{ old('nic') }}" class="field"></div>
                    <div><label class="label" for="date_of_birth">Date of birth</label><input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="field">@error('date_of_birth')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div class="sm:col-span-2"><label class="label" for="highest_qualification">Highest qualification</label><input id="highest_qualification" name="highest_qualification" value="{{ old('highest_qualification') }}" placeholder="e.g. G.C.E. A/L, Diploma" class="field"></div>
                    <div class="sm:col-span-2">
                        <label class="label" for="programme_id">Programme of interest</label>
                        <select id="programme_id" name="programme_id" class="field">
                            <option value="">Select a programme…</option>
                            @foreach ($programmes->groupBy('level') as $level => $group)
                                <optgroup label="{{ $level }}">@foreach ($group as $p)<option value="{{ $p->id }}" @selected((string) old('programme_id', $selected) === (string) $p->id)>{{ $p->title }}</option>@endforeach</optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2"><label class="label" for="message">Anything else we should know?</label><textarea id="message" name="message" rows="4" class="field">{{ old('message') }}</textarea></div>
                    <div class="sm:col-span-2"><button class="btn btn-primary px-8 py-3.5">Submit application</button></div>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection