<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class CategoryExamSeeder extends Seeder
{
    public function run()
    {
        // Clear the existing mappings
        DB::table('category_exam_rel')->truncate();

        // Define exam-category mappings
        $mappings = [
            'GATE CSE' => 'Postgraduate Engineering',
            'JEE' => 'Undergraduate Engineering',
            'NEET' => 'Undergraduate Medical',
            'CAT' => 'MBA Entrance Exams',
            'UPSC CSE' => 'UPSC Exams',
        ];

        foreach ($mappings as $examName => $categoryName) {
            // Fetch the exam ID
            $exam = Exam::where('name', $examName)->first();

            // Fetch the category ID
            $category = Category::where('name', $categoryName)->first();

            // Ensure both exist before inserting
            if ($exam && $category) {
                DB::table('category_exam_rel')->insert([
                    'exam_id' => $exam->id,
                    'category_id' => $category->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
