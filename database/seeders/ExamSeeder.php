<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;

class ExamSeeder extends Seeder
{
    public function run()
    {
        // Temporarily disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table safely
        Exam::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Exam::create([
            'name' => 'GATE CSE',
            'description' => 'Graduate Aptitude Test in Engineering for Computer Science and Engineering.',
            'is_active' => true,
            'is_popular' => true
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

        Exam::create([
            'name' => 'UPSC',
            'description' => 'Union Public Service Commission Civil Services Examination.',
            'is_active' => true,
            'is_popular'=>true
        ]);

        Exam::create([
            'name' => 'CAT',
            'description' => 'Common Admission Test for management courses.',
            'is_active' => true,
            'is_popular'=>true
        ]);
    }
}
