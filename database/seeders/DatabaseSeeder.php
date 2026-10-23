<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\Stat;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@ttmetrocampus.com')],
            ['name' => 'Administrator', 'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe!2026'))]
        );

        $defaults = [
            'hero_title' => 'Shape your future at TT Metro Campus',
            'hero_subtitle' => 'A leading higher education institute in Sri Lanka offering nationally and internationally recognised Diploma, HND, Degree and Postgraduate programmes.',
            'welcome_title' => 'Welcome to TT Metro Campus',
            'welcome_text' => "TT Metro Campus is an innovative higher education institute in Sri Lanka, established in 2015 with the vision of becoming a leading Education Provider in competencies matched industries globally. We offer nationally and internationally recognised programmes to our students, who may continue the stated programmes either locally or internationally.\n\nThe secret of success behind this legendary institute has always been passionate people. The dedication from the bottom-up hierarchy has taken TT Metro Campus to greater heights. Our expertise and efficiency, and our positive relationships with staff and students, have earned us a reputation for Professionalism, Reliability and Quality, which lead us to proceed towards Excellence.",
            'admissions_badge' => 'ADMISSIONS 2025/2026',
            'admissions_text' => 'Enrollments open for Diploma, HND, Top-up & Undergraduate Degree Programmes',
            'apply_email' => 'info.ttmetro@gmail.com',
            'phone_1' => '+94 11 431 9996',
            'phone_2' => '+94 77 227 0348',
            'email_1' => 'co.techlink@gmail.com',
            'email_2' => 'info.ttmetro@gmail.com',
            'address' => '11, Galle Road, Mount Lavinia, Sri Lanka',
            'map_embed_url' => 'https://www.google.com/maps?q=TT+Metro+Campus,+11+Galle+Rd,+Dehiwala-Mount+Lavinia+10370&output=embed',
            'student_portal_url' => 'https://lms.ttmetrocampus.com/',
            'staff_portal_url' => 'https://worksphere.ttmetrocampus.com/',
            'verify_url' => 'https://lms.ttmetrocampus.com/verify',
        ];
        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        if (Stat::count() === 0) {
            foreach ([['10', '+', 'Years of Educational Excellence'], ['5', '+', 'Faculties'], ['100', '+', 'Programmes'], ['5000', '+', 'Students']] as $i => [$v, $s, $l]) {
                Stat::create(['value' => $v, 'suffix' => $s, 'label' => $l, 'sort_order' => $i]);
            }
        }

        if (TeamMember::where('group', 'board')->count() === 0) {
            foreach ([
                ['Mr. AR Mohamed Sarjoon', 'Founder Chairman'],
                ['Ms. M.N.F Rinza', 'CEO & Director of International Affairs'],
                ['Mr. AM. Jahan', 'Director of Academic Affairs'],
                ['Mr. MSM. Imthiyas', 'Director of Operation & Dean - Faculty of Commerce & Management'],
            ] as $i => [$name, $position]) {
                TeamMember::create(['name' => $name, 'position' => $position, 'group' => 'board', 'sort_order' => $i]);
            }
        }
    }
}