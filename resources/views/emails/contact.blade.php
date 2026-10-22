@extends('emails.layout')
@section('title', 'New website enquiry')
@section('preheader', $contact->name.' sent a message through the website: '.\Illuminate\Support\Str::limit($contact->message, 80))
@section('badge', 'Contact form')
@section('heading', 'New website enquiry')
@section('intro', $contact->name.' contacted TT Metro Campus through the website on '.$contact->created_at->format('d M Y, g:i A').'.')

@section('body')
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
    @include('emails.partials.row', ['label' => 'Name', 'value' => e($contact->name)])
    @include('emails.partials.row', ['label' => 'Email', 'value' => '<a href="mailto:'.e($contact->email).'" style="color:#0b4a8f;font-weight:600;text-decoration:none;">'.e($contact->email).'</a>'])
    @include('emails.partials.row', ['label' => 'Phone', 'value' => $contact->phone ? '<a href="tel:'.e(preg_replace('/[^\d+]/', '', $contact->phone)).'" style="color:#0b4a8f;font-weight:600;text-decoration:none;">'.e($contact->phone).'</a>' : '<span style="color:#8a98ab;">Not provided</span>'])
    @include('emails.partials.row', ['label' => 'Subject', 'value' => $contact->subject ? e($contact->subject) : '<span style="color:#8a98ab;">Not provided</span>'])
</table>
<div style="background:#f5f8fc;border-left:4px solid #6eb5ff;border-radius:10px;padding:18px 20px;">
    <div style="font-size:12px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#5b6b80;margin-bottom:8px;">Message</div>
    <div style="font-size:15px;line-height:1.7;color:#0f1f33;">{!! nl2br(e($contact->message)) !!}</div>
</div>
@endsection

@section('actions')
@include('emails.partials.button', ['href' => 'mailto:'.$contact->email.'?subject='.rawurlencode('Re: '.($contact->subject ?: 'Your enquiry to TT Metro Campus')), 'label' => 'Reply to '.explode(' ', $contact->name)[0]])
@include('emails.partials.button', ['href' => url('/admin/contact-messages'), 'label' => 'Open in admin', 'primary' => false])
@endsection