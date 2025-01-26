<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;

class ExamSeeder extends Seeder
{
    public function run()
    {
        Exam::create([
            'name' => 'GATE',
            'description' => 'Graduate Aptitude Test in Engineering for multiple streams.',
            'is_active' => true,
        ]);

        Exam::create([
            'name' => 'JEE',
            'description' => 'Joint Entrance Examination for undergraduate engineering courses.',
            'is_active' => true,
        ]);

        Exam::create([
            'name' => 'NEET',
            'description' => 'National Eligibility cum Entrance Test for medical courses.',
            'is_active' => true,
        ]);
    }
}
