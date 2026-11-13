<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationReceived;
use App\Mail\ContactReceived;
use App\Mail\CourseEnquiryReceived;
use App\Models\Application;
use App\Models\ContactMessage;
use App\Models\CourseEnquiry;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FormController extends Controller
{
    public function apply(Request $request)
    {
        // Honeypot: real visitors leave this hidden field empty.
        if ($request->filled('website')) {
            return redirect()->route('apply')->with('success', 'Thank you! Your application has been received.');
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'nic' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'highest_qualification' => ['nullable', 'string', 'max:150'],
            'programme_id' => ['nullable', 'exists:programmes,id'],
            'programme_other' => ['nullable', 'string', 'max:150'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $application = Application::create($data);

        $this->send(new ApplicationReceived($application->load('programme')), $application->email);

        return redirect()->route('apply')->with('success', 'Thank you! Your application has been received and our admissions team will contact you shortly.');
    }

    public function contact(Request $request)
    {
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', 'Thank you! Your message has been sent.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $message = ContactMessage::create($data);

        $this->send(new ContactReceived($message), $message->email);

        return redirect()->route('contact')->with('success', 'Thank you! Your message has been sent and we will get back to you soon.');
    }

    /**
     * "Need advice?" box on a programme page: name, WhatsApp number and message, all required.
     * Saved in the admin (Course enquiries, separate from Contact messages) and emailed, then the visitor is sent to WhatsApp with the text ready.
     */
    public function advice(Request $request, Programme $programme)
    {
        if ($request->filled('website')) {
            return redirect()->route('programme', $programme)->withFragment('advice');
        }

        $data = $request->validateWithBag('advice', [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[\d\s\-()]{9,20}$/'],
            'message' => ['required', 'string', 'max:1500'],
        ], [
            'name.required' => 'Please enter your name.',
            'phone.required' => 'Please enter your WhatsApp number.',
            'message.required' => 'Please write your message.',
            'phone.regex' => 'Enter a valid WhatsApp number, for example 077 123 4567.',
        ]);

        $text = "Hello TT Metro Campus, I need advice about *{$programme->title}* ({$programme->level}).\n\n"
            ."Name: {$data['name']}\nWhatsApp: {$data['phone']}\n\n{$data['message']}\n\n"
            .route('programme', $programme);

        $saved = CourseEnquiry::create([
            'programme_id' => $programme->id,
            'programme_title' => $programme->title,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'message' => $data['message'],
        ]);

        $this->send(new CourseEnquiryReceived($saved));

        return redirect()->route('programme', $programme)->withFragment('advice')
            ->with('advice_whatsapp', whatsapp_url($text));
    }

    /** The submission is already saved, so a mail failure must not lose it. */
    private function send($mailable, ?string $replyTo = null): void
    {
        try {
            Mail::to(setting('apply_email', 'info.ttmetro@gmail.com'))->send($replyTo ? $mailable->replyTo($replyTo) : $mailable);
        } catch (\Throwable $e) {
            Log::error('Form notification email failed: '.$e->getMessage());
        }
    }
}