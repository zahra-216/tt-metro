<?php

namespace App\Http\Controllers;

use App\Mail\ApplicationReceived;
use App\Mail\ContactReceived;
use App\Models\Application;
use App\Models\ContactMessage;
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

    /** The submission is already saved, so a mail failure must not lose it. */
    private function send($mailable, string $replyTo): void
    {
        try {
            Mail::to(setting('apply_email', 'info.ttmetro@gmail.com'))->send($mailable->replyTo($replyTo));
        } catch (\Throwable $e) {
            Log::error('Form notification email failed: '.$e->getMessage());
        }
    }
}