<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            ExamSeeder::class,
            CategoryExamSeeder::class,
            TopicSeeder::class,
            ExamTopicRelSeeder::class,
            QuestionPaperSeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
