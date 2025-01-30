<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Topic;
use App\Models\QuestionPaper;
use Faker\Factory as Faker;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        // Temporarily disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table safely
        Question::truncate();

        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all topics
        $topics = Topic::all();

        // Get question papers (for GATE CSE)
        $questionPapers = QuestionPaper::where('exam_id', 1)->get();  // Assuming GATE CSE has exam_id = 1

        // Initialize Faker for generating random data
        $faker = Faker::create();

        // Define possible options
        $options = ['A', 'B', 'C', 'D'];

        // Loop through topics to create questions
        foreach ($topics as $topic) {
            for ($i = 1; $i <= 50; $i++) {  // Generate 50 questions per topic
                $questionData = [
                    'question_text' => $faker->sentence(10),  // Random sentence for question text
                    'option_a' => $faker->word,
                    'option_b' => $faker->word,
                    'option_c' => $faker->word,
                    'option_d' => $faker->word,
                    'correct_option' => $options[array_rand($options)],  // Randomly pick the correct option
                    'topic_id' => $topic->id,  // Linking question to the current topic
                    'question_paper_id' => null,  // No question paper assigned, only topic based questions
                ];

                // Create the question
                Question::create($questionData);
            }
        }

        // Loop through question papers to create questions
        foreach ($questionPapers as $paper) {
            for ($i = 1; $i <= 50; $i++) {  // Generate 50 questions per question paper
                $questionData = [
                    'question_text' => $faker->sentence(10),  // Random sentence for question text
                    'option_a' => $faker->word,
                    'option_b' => $faker->word,
                    'option_c' => $faker->word,
                    'option_d' => $faker->word,
                    'correct_option' => $options[array_rand($options)],  // Randomly pick the correct option
                    'topic_id' => null,  // No topic assigned, only question paper based questions
                    'question_paper_id' => $paper->id,  // Linking question to the current question paper
                ];

                // Create the question
                Question::create($questionData);
            }
        }
    }
}
