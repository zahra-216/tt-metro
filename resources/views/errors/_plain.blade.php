@php
    // Stand-alone friendly page for server problems (500, 503 ...): no layout, no database, so it works when the site is down.
    $code = isset($exception) && method_exists($exception, 'getStatusCode') ? (int) $exception->getStatusCode() : 500;

    if ($code === 503) {
        $title = "We'll be back soon";
        $text = 'The website is being updated. Please try again in a few minutes.';
    } else {
        $title = 'Something went wrong on our side';
        $text = 'Please try again in a moment. If it keeps happening, call or email the campus office.';
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title }} - TT Metro Campus</title>
    <link rel="icon" href="{{ asset('images/logo.jpg') }}">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #f4f7fb; color: #0f172a; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif; display: grid; place-items: center; padding: 24px 16px; }
        .card { width: 100%; max-width: 560px; background: #fff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 34px 26px; text-align: center; box-shadow: 0 2px 12px rgba(10, 42, 82, .08); }
        img { display: block; margin: 0 auto; width: 72px; height: 72px; border-radius: 50%; object-fit: cover; }
        .code { display: inline-block; margin: 14px 0 6px; padding: 6px 16px; border-radius: 999px; background: #e6f0ff; color: #0b4a8f; font-weight: 800; font-size: 14px; letter-spacing: .06em; }
        h1 { margin: 6px 0 10px; font-size: 26px; line-height: 1.25; color: #0a2a52; }
        p { margin: 0 0 12px; color: #475569; font-size: 16px; line-height: 1.55; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 22px; }
        .btn { display: inline-block; border-radius: 12px; padding: 11px 20px; font-size: 15px; font-weight: 700; text-decoration: none; cursor: pointer; border: 1px solid #0a2a52; font-family: inherit; }
        .btn-primary { background: #0a2a52; color: #fff; }
        .btn-light { background: #fff; color: #0a2a52; }
        .contact { margin-top: 20px; padding: 12px 14px; background: #f1f5f9; border-radius: 10px; font-size: 14px; color: #334155; overflow-wrap: anywhere; }
        .contact a { color: #0b4a8f; font-weight: 600; }
        @media (max-width: 420px) { .btn { width: 100%; } h1 { font-size: 22px; } }
    </style>
</head>
<body>
    <div class="card" role="alert">
        <img src="{{ asset('images/logo.jpg') }}" alt="TT Metro Campus">
        <div class="code">ERROR {{ $code }}</div>
        <h1>{{ $title }}</h1>
        <p>{{ $text }}</p>
        <div class="actions">
            <button type="button" class="btn btn-primary" onclick="location.reload()">Try again</button>
            <a href="{{ url('/') }}" class="btn btn-light">Go to the home page</a>
        </div>
        <div class="contact">TT Metro Campus, Mount Lavinia. Email: <a href="mailto:info.ttmetro@gmail.com">info.ttmetro@gmail.com</a></div>
    </div>
</body>
</html>
