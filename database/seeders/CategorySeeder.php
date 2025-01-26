<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
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

        // Subcategories under Engineering
        Category::create([
            'name' => 'GATE',
            'description' => 'Graduate Aptitude Test in Engineering',
            'parent_id' => $engineering->id,
        ]);

        Category::create([
            'name' => 'JEE',
            'description' => 'Joint Entrance Examination',
            'parent_id' => $engineering->id,
        ]);

        // Subcategories under Medical
        Category::create([
            'name' => 'NEET',
            'description' => 'National Eligibility cum Entrance Test',
            'parent_id' => $medical->id,
        ]);
    }
}
