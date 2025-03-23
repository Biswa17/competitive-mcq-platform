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
        
        // // Temporarily disable foreign key checks
        // \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // // Truncate the table safely
        // Question::truncate();

        // // Re-enable foreign key checks
        // \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all topics related to exam_id = 1
        $topics = Topic::whereHas('exams', function ($query) {
            $query->where('exams.id', 1);  // Specify table name to avoid ambiguity
        })->get();
        


        // Get question papers (for GATE CSE)
        // $questionPapers = QuestionPaper::where('exam_id', 1)->get();  // Assuming GATE CSE has exam_id = 1

        // Initialize Faker for generating random data
        $faker = Faker::create();

        // Define possible options
        $options = ['A', 'B', 'C', 'D'];

        // Loop through topics to create questions
        foreach ($topics as $topic) {
            $questions = $this->get_question($topic->name);
            foreach ($questions as $questionData) {
                // Check if the question already exists
                $exists = Question::where('question_text', $questionData['question_text'])
                ->where('topic_id', $topic->id)
                ->exists();

                if (!$exists) {
                    Question::create([
                        'question_text' => $questionData['question_text'],
                        'option_a' => $questionData['option_a'],
                        'option_b' => $questionData['option_b'],
                        'option_c' => $questionData['option_c'],
                        'option_d' => $questionData['option_d'],
                        'correct_option' => $questionData['correct_option'],
                        'topic_id' => $topic->id,
                        'question_paper_id' => null,
                    ]);
                }
            }
        }
        

        // // Loop through question papers to create questions
        // foreach ($questionPapers as $paper) {
        //     for ($i = 1; $i <= 20; $i++) {  // Generate 50 questions per question paper
        //         $questionData = [
        //             'question_text' => $faker->sentence(10),  // Random sentence for question text
        //             'option_a' => $faker->word,
        //             'option_b' => $faker->word,
        //             'option_c' => $faker->word,
        //             'option_d' => $faker->word,
        //             'correct_option' => $options[array_rand($options)],  // Randomly pick the correct option
        //             'topic_id' => null,  // No topic assigned, only question paper based questions
        //             'question_paper_id' => $paper->id,  // Linking question to the current question paper
        //         ];

        //         // Create the question
        //         Question::create($questionData);
        //     }
        // }
    }


    public function get_question($topic_name)
    {
        $apiKey = env('GEMINI_API_KEY', 'WRONG_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

        $promt = "Generate 10 multiple-choice questions (MCQs) on the topic '$topic_name'. 
                Provide the response in JSON format with the structure:
                [
                    {
                        'question_text': 'Question?',
                        'option_a': 'Option A',
                        'option_b': 'Option B',
                        'option_c': 'Option C',
                        'option_d': 'Option D',
                        'correct_option': 'A' (one of A, B, C, or D)
                    }
                ]";

        $postData = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $promt
                        ]
                    ]
                ]
            ]
        ];

        $response = call_curl($url, 'POST', $postData);

        if ($response['status'] === 200 && isset($response['response']['candidates'][0]['content']['parts'][0]['text'])) {
            $aiGeneratedText = trim($response['response']['candidates'][0]['content']['parts'][0]['text']);

            $aiGeneratedText = preg_replace('/<pre>|<\/pre>|```json|```/', '', $aiGeneratedText);
            // Convert AI response to JSON format
            $questions = json_decode($aiGeneratedText, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                return $questions;
            } else {
                print_r("JSON decoding failed. Retrying... Response: " . json_encode($response));
                sleep(10);
                return $this->get_question($topic_name);
            }
        } else {
            print_r("Unable to fetch data. Retrying... Response: " . json_encode($response));
            sleep(10);
            return $this->get_question($topic_name);
        }
    }




}
