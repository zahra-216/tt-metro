@extends('layouts.app')
@section('title', \App\Support\PageTexts::title('privacy'))
@section('description', \App\Support\PageTexts::get('privacy', 'description'))

@section('content')
<x-page-header page="privacy" />
<section class="section">
    <div class="wrap max-w-4xl">
        <div class="prose-site">
            <p><strong>Last updated: 23 November 2026</strong></p>

            <p>TT Metro Campus ("we", "us") is a higher education institute in Mount Lavinia, Sri Lanka. This page explains what information we collect through this website, our enquiry and application forms, our online classes and our learning portal, why we collect it, and the choices you have.</p>

            <h2>Information we collect</h2>
            <ul>
                <li><strong>Contact form:</strong> your name, email address, phone number (optional), subject and message.</li>
                <li><strong>Application form:</strong> your full name, email address, phone number, date of birth, NIC number, highest qualification, the programme you are interested in, and any message you write.</li>
                <li><strong>"Need advice?" form on programme pages:</strong> your name, WhatsApp number, your message and the programme you asked about.</li>
                <li><strong>Students and lecturers:</strong> the details needed to run the learning portal, such as registration number, name, course, level, semester, attendance, assignments and marks.</li>
                <li><strong>Technical information:</strong> the website uses only the small files (cookies) it needs to work. We do not use advertising cookies.</li>
            </ul>

            <h2>How we use it</h2>
            <ul>
                <li>To answer your questions and contact you by email, phone or WhatsApp.</li>
                <li>To process applications and admissions.</li>
                <li>To teach, record attendance and results, and manage student records.</li>
                <li>To keep our website and systems secure and working properly.</li>
            </ul>
            <p>We do not sell your information, and we do not use it for advertising.</p>

            <h2>Who can see it</h2>
            <p>Messages and applications are sent to the campus inbox and seen by the campus staff who deal with them. We use trusted services to run the website and our classes, such as email delivery, Zoom for online classes, YouTube for class recordings, and WhatsApp when you choose to send us a message there. These services handle information under their own privacy terms. We share information with others only when the law requires it or when you ask us to.</p>

            <h2>Online classes and recordings</h2>
            <p>Online classes are held on Zoom. A class may be recorded, which can include video, audio and the names shown by participants. Recordings are shared with the enrolled students of that module through the learning portal, as unlisted YouTube videos that are not shown in public search. Attendance for a class is recorded from the list of people who joined the Zoom meeting.</p>

            <h2>WhatsApp</h2>
            <p>When you press "Send on WhatsApp", the website opens WhatsApp with your message ready to send to the campus number. The message is sent only when you press Send in WhatsApp, and it is then handled under WhatsApp's own terms.</p>

            <h2>How long we keep it</h2>
            <p>We keep information for as long as we need it for the purposes above, or for as long as the law requires.</p>

            <h2>Keeping it safe</h2>
            <p>We take reasonable steps to protect your information, including secure connections to the website and password-protected access to our systems. No system is completely secure, so please do not send us more personal information than is needed.</p>

            <h2>Your choices</h2>
            <p>You may ask us to show you the information we hold about you, to correct it, or to delete it. Write to us using the contact details below and we will reply as soon as we can.</p>

            <h2>Changes to this page</h2>
            <p>We may update this policy from time to time. The date at the top shows when it was last changed.</p>

            <h2>Contact us</h2>
            <p>
                TT Metro Campus<br>
                @if (setting('address')){{ setting('address') }}<br>@endif
                @if (setting('email_1'))Email: <a href="mailto:{{ setting('email_1') }}">{{ setting('email_1') }}</a><br>@endif
                @if (setting('phone_1'))Phone: {{ setting('phone_1') }}@endif
            </p>
        </div>
    </div>
</section>
@endsection
