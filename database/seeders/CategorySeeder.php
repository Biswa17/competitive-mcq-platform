<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Temporarily disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table safely
        Category::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Top-level categories
        $engineering = Category::create([
            'name' => 'Engineering',
            'description' => 'All engineering-related exams',
            'parent_id' => null,
        ]);

        $medical = Category::create([
            'name' => 'Medical',
            'description' => 'All medical-related exams',
            'parent_id' => null,
        ]);

        $management = Category::create([
            'name' => 'Management',
            'description' => 'All management-related exams',
            'parent_id' => null,
        ]);

        $civil_services = Category::create([
            'name' => 'Civil Services',
            'description' => 'Civil service and government exams',
            'parent_id' => null,
        ]);

        // Subcategories under Engineering
        $engineering_pg = Category::create([
            'name' => 'Postgraduate Engineering',
            'description' => 'Exams for postgraduate engineering admissions',
            'parent_id' => $engineering->id,
        ]);

        $engineering_ug = Category::create([
            'name' => 'Undergraduate Engineering',
            'description' => 'Exams for undergraduate engineering admissions',
            'parent_id' => $engineering->id,
        ]);

        // Sub-subcategories under Postgraduate Engineering
        Category::create([
            'name' => 'GATE',
            'description' => 'Graduate Aptitude Test in Engineering',
            'parent_id' => $engineering_pg->id,
        ]);

        // Sub-subcategories under Undergraduate Engineering
        Category::create([
            'name' => 'JEE Main',
            'description' => 'Joint Entrance Examination Main',
            'parent_id' => $engineering_ug->id,
        ]);

        Category::create([
            'name' => 'JEE Advanced',
            'description' => 'Joint Entrance Examination Advanced',
            'parent_id' => $engineering_ug->id,
        ]);

        // Subcategories under Medical
        $medical_ug = Category::create([
            'name' => 'Undergraduate Medical',
            'description' => 'Exams for undergraduate medical admissions',
            'parent_id' => $medical->id,
        ]);

        // Sub-subcategories under Undergraduate Medical
        Category::create([
            'name' => 'NEET UG',
            'description' => 'National Eligibility cum Entrance Test for UG',
            'parent_id' => $medical_ug->id,
        ]);

        // Subcategories under Management
        $management_mba = Category::create([
            'name' => 'MBA Entrance Exams',
            'description' => 'Exams for MBA admissions',
            'parent_id' => $management->id,
        ]);

        // Sub-subcategories under MBA Entrance Exams
        Category::create([
            'name' => 'CAT',
            'description' => 'Common Admission Test for management courses',
            'parent_id' => $management_mba->id,
        ]);

        // Subcategories under Civil Services
        $civil_services_upsc = Category::create([
            'name' => 'UPSC Exams',
            'description' => 'UPSC-conducted civil service exams',
            'parent_id' => $civil_services->id,
        ]);

        // Sub-subcategories under UPSC Exams
        Category::create([
            'name' => 'UPSC CSE',
            'description' => 'Union Public Service Commission Civil Services Examination',
            'parent_id' => $civil_services_upsc->id,
        ]);
    }
}
