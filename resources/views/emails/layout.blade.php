@php
    $logo = isset($message) ? $message->embed(public_path('images/logo.jpg')) : asset('images/logo.jpg');
    $navy = '#0a2a52'; $brand = '#0b4a8f'; $sky = '#6eb5ff'; $line = '#e3eaf3'; $muted = '#5b6b80';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
</head>
<body style="margin:0;padding:0;background:#eef3f9;font-family:Inter,Segoe UI,Helvetica,Arial,sans-serif;color:#0f1f33;">
    <div style="display:none;max-height:0;overflow:hidden;">@yield('preheader')</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3f9;padding:32px 12px;">
        <tr><td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:620px;">
                {{-- Header --}}
                <tr><td style="background:{{ $navy }};background-image:linear-gradient(135deg,{{ $navy }} 0%,{{ $brand }} 100%);border-radius:20px 20px 0 0;padding:28px 32px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr>
                        <td width="56" style="vertical-align:middle;"><img src="{{ $logo }}" width="52" height="52" alt="TT Metro Campus" style="display:block;border-radius:50%;border:2px solid rgba(255,255,255,.35);"></td>
                        <td style="vertical-align:middle;padding-left:14px;">
                            <div style="font-size:18px;font-weight:800;letter-spacing:.3px;color:#ffffff;">TT METRO CAMPUS</div>
                            <div style="font-size:11px;letter-spacing:1.6px;text-transform:uppercase;color:{{ $sky }};margin-top:2px;">Higher Education Institute · Sri Lanka</div>
                        </td>
                    </tr></table>
                </td></tr>
                <tr><td style="background:{{ $sky }};height:4px;line-height:4px;font-size:0;">&nbsp;</td></tr>

                {{-- Body --}}
                <tr><td style="background:#ffffff;padding:36px 32px 12px;border-left:1px solid {{ $line }};border-right:1px solid {{ $line }};">
                    <div style="display:inline-block;background:#e8f2fd;color:{{ $brand }};font-size:11px;font-weight:700;letter-spacing:1.4px;text-transform:uppercase;padding:6px 12px;border-radius:999px;">@yield('badge')</div>
                    <h1 style="margin:16px 0 6px;font-size:24px;line-height:1.25;color:{{ $navy }};">@yield('heading')</h1>
                    <p style="margin:0 0 24px;font-size:14px;line-height:1.6;color:{{ $muted }};">@yield('intro')</p>
                    @yield('body')
                </td></tr>

                {{-- Action --}}
                <tr><td style="background:#ffffff;padding:8px 32px 36px;border-left:1px solid {{ $line }};border-right:1px solid {{ $line }};">
                    @yield('actions')
                </td></tr>

                {{-- Footer --}}
                <tr><td style="background:#f5f8fc;border:1px solid {{ $line }};border-top:0;border-radius:0 0 20px 20px;padding:22px 32px;text-align:center;font-size:12px;line-height:1.7;color:{{ $muted }};">
                    11, Galle Road, Mount Lavinia, Sri Lanka<br>
                    <a href="{{ url('/') }}" style="color:{{ $brand }};text-decoration:none;font-weight:600;">ttmetrocampus.com</a>
                    <div style="margin-top:8px;color:#8a98ab;">This message was sent automatically from the TT Metro Campus website.</div>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>