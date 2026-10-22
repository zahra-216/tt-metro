@extends('emails.layout')
@section('title', 'New application received')
@section('preheader', $application->full_name.' applied'.($application->programme ? ' for '.$application->programme->title : '').'.')
@section('badge', 'Admissions')
@section('heading', 'New application received')
@section('intro', $application->full_name.' submitted an application on '.$application->created_at->format('d M Y, g:i A').'. Please contact the applicant as soon as possible.')

@section('body')
@php $na = '<span style="color:#8a98ab;">Not provided</span>'; @endphp
@if ($application->programme)
<div style="background:#e8f2fd;border-radius:12px;padding:16px 20px;margin-bottom:20px;">
    <div style="font-size:11px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:#0b4a8f;">Programme of interest</div>
    <div style="font-size:17px;font-weight:700;color:#0a2a52;margin-top:4px;">{{ $application->programme->title }}</div>
    <div style="font-size:13px;color:#5b6b80;margin-top:2px;">{{ $application->programme->level }}</div>
</div>
@elseif ($application->programme_other)
<div style="background:#e8f2fd;border-radius:12px;padding:16px 20px;margin-bottom:20px;font-size:15px;font-weight:700;color:#0a2a52;">{{ $application->programme_other }}</div>
@endif
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
    @include('emails.partials.row', ['label' => 'Full name', 'value' => e($application->full_name)])
    @include('emails.partials.row', ['label' => 'Email', 'value' => '<a href="mailto:'.e($application->email).'" style="color:#0b4a8f;font-weight:600;text-decoration:none;">'.e($application->email).'</a>'])
    @include('emails.partials.row', ['label' => 'Phone', 'value' => '<a href="tel:'.e(preg_replace('/[^\d+]/', '', $application->phone)).'" style="color:#0b4a8f;font-weight:600;text-decoration:none;">'.e($application->phone).'</a>'])
    @include('emails.partials.row', ['label' => 'NIC / Passport', 'value' => $application->nic ? e($application->nic) : $na])
    @include('emails.partials.row', ['label' => 'Date of birth', 'value' => $application->date_of_birth ? $application->date_of_birth->format('d M Y') : $na])
    @include('emails.partials.row', ['label' => 'Qualification', 'value' => $application->highest_qualification ? e($application->highest_qualification) : $na])
</table>
@if ($application->message)
<div style="background:#f5f8fc;border-left:4px solid #6eb5ff;border-radius:10px;padding:18px 20px;">
    <div style="font-size:12px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#5b6b80;margin-bottom:8px;">Additional notes</div>
    <div style="font-size:15px;line-height:1.7;color:#0f1f33;">{!! nl2br(e($application->message)) !!}</div>
</div>
@endif
@endsection

@section('actions')
@include('emails.partials.button', ['href' => 'mailto:'.$application->email.'?subject='.rawurlencode('Your application to TT Metro Campus'), 'label' => 'Reply to '.explode(' ', $application->full_name)[0]])
@include('emails.partials.button', ['href' => 'tel:'.preg_replace('/[^\d+]/', '', $application->phone), 'label' => 'Call applicant', 'primary' => false])
@include('emails.partials.button', ['href' => url('/admin/applications'), 'label' => 'Open in admin', 'primary' => false])
@endsection