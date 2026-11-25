@php
    // Friendly page for client errors (404, 403, 419, 429, 405 ...) inside the normal site layout.
    $code = isset($exception) && method_exists($exception, 'getStatusCode') ? (int) $exception->getStatusCode() : 404;
    $raw = isset($exception) ? trim((string) $exception->getMessage()) : '';
    $retry = false;

    switch (true) {
        case $code === 403:
            $title = "You cannot open this page";
            $text = ($raw !== '' && strtolower($raw) !== 'forbidden') ? $raw : 'You do not have permission to open this page.';
            break;
        case $code === 404:
            $title = 'Page not found';
            $text = 'The page you are looking for does not exist, or it has moved. Try one of the links below.';
            break;
        case $code === 405:
            $title = "This address cannot be opened directly";
            $text = 'Please go back and use the buttons on the page instead of typing this address.';
            break;
        case $code === 419:
            $title = 'This page was open for too long';
            $text = 'Nothing was sent. Please go back, reload the page and send your message or application again.';
            break;
        case $code === 429:
            $title = 'Too many tries';
            $text = 'You sent several messages in a short time. Please wait a minute and try again.';
            $retry = true;
            break;
        default:
            $title = "This page cannot be shown";
            $text = 'Please go back and try again.';
    }
@endphp
@extends('layouts.app')
@section('title', $title.' - TT Metro Campus')

@section('content')
<section class="section">
    <div class="wrap">
        <div class="mx-auto max-w-2xl rounded-2xl border border-line bg-white p-10 text-center shadow-card">
            <span class="eyebrow">Error {{ $code }}</span>
            <h1 class="h-section">{{ $title }}</h1>
            <p class="lead">{{ $text }}</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                @if ($retry)<button type="button" class="btn btn-primary" onclick="location.reload()">Try again</button>@endif
                <a href="{{ route('home') }}" class="btn {{ $retry ? 'btn-ghost' : 'btn-primary' }}">Go to the home page</a>
                <a href="{{ route('academics') }}" class="btn btn-ghost">See our programmes</a>
                <a href="{{ route('contact') }}" class="btn btn-ghost">Contact us</a>
            </div>
            <p class="mt-8 text-sm text-muted"><a href="javascript:history.back()" class="underline">Go back</a></p>
        </div>
    </div>
</section>
@endsection
