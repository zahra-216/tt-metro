@extends('emails.layout')
@section('title', 'New course enquiry')
@section('preheader', $enquiry->name.' asked about '.$enquiry->programme_title.': '.\Illuminate\Support\Str::limit($enquiry->message, 80))
@section('badge', 'Course enquiry')
@section('heading', 'New course enquiry')
@section('intro', $enquiry->name.' asked for advice about '.$enquiry->programme_title.' on '.$enquiry->created_at->format('d M Y, g:i A').'.')

@php $wa = 'https://wa.me/'.preg_replace('/^0/', '94', preg_replace('/\D/', '', $enquiry->phone)); @endphp

@section('body')
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
    @include('emails.partials.row', ['label' => 'Programme', 'value' => e($enquiry->programme_title ?: 'Not known')])
    @include('emails.partials.row', ['label' => 'Name', 'value' => e($enquiry->name)])
    @include('emails.partials.row', ['label' => 'WhatsApp', 'value' => '<a href="'.e($wa).'" style="color:#0b4a8f;font-weight:600;text-decoration:none;">'.e($enquiry->phone).'</a>'])
</table>
<div style="background:#f5f8fc;border-left:4px solid #6eb5ff;border-radius:10px;padding:18px 20px;">
    <div style="font-size:12px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#5b6b80;margin-bottom:8px;">Message</div>
    <div style="font-size:15px;line-height:1.7;color:#0f1f33;">{!! nl2br(e($enquiry->message)) !!}</div>
</div>
@endsection

@section('actions')
@include('emails.partials.button', ['href' => $wa, 'label' => 'WhatsApp '.explode(' ', $enquiry->name)[0]])
@include('emails.partials.button', ['href' => url('/admin/course-enquiries'), 'label' => 'Open in admin', 'primary' => false])
@endsection
