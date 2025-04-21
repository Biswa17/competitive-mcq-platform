<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Temporarily disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table safely
        Category::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // --- Level 1 Categories ---
        $engineering = Category::create([
            'name' => 'Engineering',
            'description' => 'All engineering-related exams',
            'parent_id' => null,
            'level' => 1, // Added level
        ]);

        $medical = Category::create([
            'name' => 'Medical',
            'description' => 'All medical-related exams',
            'parent_id' => null,
            'level' => 1, // Added level
        ]);

        $management = Category::create([
            'name' => 'Management',
            'description' => 'All management-related exams',
            'parent_id' => null,
            'level' => 1, // Added level
        ]);

        $civil_services = Category::create([
            'name' => 'Civil Services',
            'description' => 'Civil service and government exams',
            'parent_id' => null,
            'level' => 1, // Added level
        ]);

        // --- Level 2 Categories (Exams) ---

        // Engineering Exams (Level 2)
        $gate = Category::create([
            'name' => 'GATE',
            'description' => 'Graduate Aptitude Test in Engineering',
            'parent_id' => $engineering->id, // Parent is Level 1 Engineering
            'level' => 2, // Now Level 2
        ]);
        $jeeMain = Category::create([
            'name' => 'JEE Main',
            'description' => 'Joint Entrance Examination Main',
            'parent_id' => $engineering->id, // Parent is Level 1 Engineering
            'level' => 2, // Now Level 2
        ]);
        $jeeAdvanced = Category::create([
            'name' => 'JEE Advanced',
            'description' => 'Joint Entrance Examination Advanced',
            'parent_id' => $engineering->id, // Parent is Level 1 Engineering
            'level' => 2, // Now Level 2
        ]);
        // Add more L2 Engineering exams if needed

        // Medical Exams (Level 2)
        $neetUg = Category::create([
            'name' => 'NEET UG',
            'description' => 'National Eligibility cum Entrance Test for UG',
            'parent_id' => $medical->id, // Parent is Level 1 Medical
            'level' => 2, // Now Level 2
        ]);
        // Add more L2 Medical exams if needed

        // Management Exams (Level 2)
        $cat = Category::create([
            'name' => 'CAT',
            'description' => 'Common Admission Test for management courses',
            'parent_id' => $management->id, // Parent is Level 1 Management
            'level' => 2, // Now Level 2
        ]);
        // Add more L2 Management exams if needed

        // Civil Services Exams (Level 2)
        $upscCse = Category::create([
            'name' => 'UPSC CSE',
            'description' => 'Union Public Service Commission Civil Services Examination',
            'parent_id' => $civil_services->id, // Parent is Level 1 Civil Services
            'level' => 2, // Now Level 2
        ]);
        // Add more L2 Civil Services exams if needed

        $this->command->info('Categories seeded successfully with 2 levels.');
    }
}
