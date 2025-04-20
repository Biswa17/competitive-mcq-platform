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

        // --- Level 2 Categories ---

        // Engineering Subcategories (Level 2)
        $engineering_pg = Category::create([
            'name' => 'Postgraduate Engineering',
            'description' => 'Exams for postgraduate engineering admissions',
            'parent_id' => $engineering->id,
            'level' => 2, // Added level
        ]);

        $engineering_ug = Category::create([
            'name' => 'Undergraduate Engineering',
            'description' => 'Exams for undergraduate engineering admissions',
            'parent_id' => $engineering->id,
            'level' => 2, // Added level
        ]);

        // Medical Subcategories (Level 2)
        $medical_ug = Category::create([
            'name' => 'Undergraduate Medical',
            'description' => 'Exams for undergraduate medical admissions',
            'parent_id' => $medical->id,
            'level' => 2, // Added level
        ]);
        // Add more L2 medical categories if needed, e.g., Postgraduate Medical

        // Management Subcategories (Level 2)
        $management_mba = Category::create([
            'name' => 'MBA Entrance Exams',
            'description' => 'Exams for MBA admissions',
            'parent_id' => $management->id,
            'level' => 2, // Added level
        ]);
        // Add more L2 management categories if needed

        // Civil Services Subcategories (Level 2)
        $civil_services_upsc = Category::create([
            'name' => 'UPSC Exams',
            'description' => 'UPSC-conducted civil service exams',
            'parent_id' => $civil_services->id,
            'level' => 2, // Added level
        ]);
        // Add more L2 civil services categories if needed

        // --- Level 3 Categories ---

        // Postgraduate Engineering Sub-subcategories (Level 3)
        $gate = Category::create([ // Store in variable
            'name' => 'GATE',
            'description' => 'Graduate Aptitude Test in Engineering',
            'parent_id' => $engineering_pg->id,
            'level' => 3, // Added level
        ]);
        // Add more L3 PG Engineering categories if needed (e.g., ESE)

        // Undergraduate Engineering Sub-subcategories (Level 3)
        $jeeMain = Category::create([ // Store in variable
            'name' => 'JEE Main',
            'description' => 'Joint Entrance Examination Main',
            'parent_id' => $engineering_ug->id,
            'level' => 3, // Added level
        ]);

        $jeeAdvanced = Category::create([ // Store in variable
            'name' => 'JEE Advanced',
            'description' => 'Joint Entrance Examination Advanced',
            'parent_id' => $engineering_ug->id,
            'level' => 3, // Added level
        ]);
        // Add more L3 UG Engineering categories if needed (e.g., BITSAT)

        // Undergraduate Medical Sub-subcategories (Level 3)
        $neetUg = Category::create([ // Store in variable
            'name' => 'NEET UG',
            'description' => 'National Eligibility cum Entrance Test for UG',
            'parent_id' => $medical_ug->id,
            'level' => 3, // Added level
        ]);
        // Add more L3 UG Medical categories if needed (e.g., AIIMS UG - though now part of NEET)

        // MBA Entrance Exams Sub-subcategories (Level 3)
        $cat = Category::create([ // Store in variable
            'name' => 'CAT',
            'description' => 'Common Admission Test for management courses',
            'parent_id' => $management_mba->id,
            'level' => 3, // Added level
        ]);
        // Add more L3 MBA categories if needed (e.g., XAT, SNAP)

        // UPSC Exams Sub-subcategories (Level 3)
        $upscCse = Category::create([ // Store in variable
            'name' => 'UPSC CSE',
            'description' => 'Union Public Service Commission Civil Services Examination',
            'parent_id' => $civil_services_upsc->id,
            'level' => 3, // Added level
        ]);
        // Add more L3 UPSC categories if needed (e.g., UPSC CDS, UPSC NDA)

        $this->command->info('Categories seeded successfully with 3 levels.');
    }
}
