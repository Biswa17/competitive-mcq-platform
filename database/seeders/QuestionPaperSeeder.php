<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionPaper;
use App\Models\Exam;

class QuestionPaperSeeder extends Seeder
{
    public function run()
    {
        // Temporarily disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table safely
        QuestionPaper::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Find GATE CSE exam
        $gateCseExam = Exam::where('name', 'GATE CSE')->first();

        if ($gateCseExam) {
            // Adding question papers for the last 5 years for GATE CSE
            $years = [2021, 2022, 2023, 2024, 2025];

            foreach ($years as $year) {
                QuestionPaper::create([
                    'name' => 'GATE CSE ' . $year . ' Question Paper',
                    'year' => $year . '-01-01', // Format year as YYYY-MM-DD
                    'exam_id' => $gateCseExam->id,
                    'file_path' => null,  // Add file path if needed, leave as null for now
                ]);
            }
        } else {
            // Handle the case when the GATE CSE exam is not found
            \Log::error('GATE CSE exam not found!');
        }
    }
}
