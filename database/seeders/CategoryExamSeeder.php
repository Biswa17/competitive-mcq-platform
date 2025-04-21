<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Exam;

class CategoryExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the pivot table safely
        // Assuming the pivot table name is 'category_exam_rel' based on common Laravel conventions
        // and the migration file 2025_01_21_061803_create_category_exam_rel_table.php
        DB::table('category_exam_rel')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Fetch Level 2 Categories (which now represent exams)
        $gateCat = Category::where('name', 'GATE')->where('level', 2)->first();
        $jeeMainCat = Category::where('name', 'JEE Main')->where('level', 2)->first();
        $jeeAdvancedCat = Category::where('name', 'JEE Advanced')->where('level', 2)->first();
        $neetUgCat = Category::where('name', 'NEET UG')->where('level', 2)->first();
        $catCat = Category::where('name', 'CAT')->where('level', 2)->first();
        $upscCseCat = Category::where('name', 'UPSC CSE')->where('level', 2)->first();

        // Fetch Exams
        $gateExam = Exam::where('name', 'GATE CSE')->first(); // Assuming GATE CSE exam maps to GATE category
        $jeeExam = Exam::where('name', 'JEE')->first();
        $neetExam = Exam::where('name', 'NEET')->first();
        $catExam = Exam::where('name', 'CAT')->first();
        $upscExam = Exam::where('name', 'UPSC')->first();

        // Prepare data for pivot table insertion
        $relations = [];

        if ($gateCat && $gateExam) {
            $relations[] = ['category_id' => $gateCat->id, 'exam_id' => $gateExam->id];
        }
        if ($jeeMainCat && $jeeExam) {
            $relations[] = ['category_id' => $jeeMainCat->id, 'exam_id' => $jeeExam->id];
        }
        if ($jeeAdvancedCat && $jeeExam) {
            // JEE Advanced also maps to the general 'JEE' exam in this seeder
            $relations[] = ['category_id' => $jeeAdvancedCat->id, 'exam_id' => $jeeExam->id];
        }
        if ($neetUgCat && $neetExam) {
            $relations[] = ['category_id' => $neetUgCat->id, 'exam_id' => $neetExam->id];
        }
        if ($catCat && $catExam) {
            $relations[] = ['category_id' => $catCat->id, 'exam_id' => $catExam->id];
        }
        if ($upscCseCat && $upscExam) {
            $relations[] = ['category_id' => $upscCseCat->id, 'exam_id' => $upscExam->id];
        }

        // Insert the relationships into the pivot table
        if (!empty($relations)) {
            DB::table('category_exam_rel')->insert($relations);
            $this->command->info('Category-Exam relationships seeded successfully.');
        } else {
            $this->command->warn('No Category-Exam relationships were seeded. Check if categories and exams exist.');
        }
    }
}
