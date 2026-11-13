<?php

namespace App\Mail;

use App\Models\CourseEnquiry;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class CourseEnquiryReceived extends Mailable
{
    public function __construct(public CourseEnquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Course enquiry: '.($this->enquiry->programme_title ?: $this->enquiry->name));
    }

    public function content(): Content
    {
        return new Content(view: 'emails.course-enquiry');
    }
}
