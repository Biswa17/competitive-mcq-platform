<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamTopicRel;
use App\Models\Exam;
use App\Models\Topic;
use Illuminate\Support\Facades\Http;

class ExamTopicRelSeeder extends Seeder
{
    public function run()
    {
        // Get all available exams
        $exams = Exam::where('id', '!=', 1)->get();

        foreach ($exams as $exam) {
            // Get topics from AI for this exam
            $topics = $this->get_topics_from_ai($exam->name);

            foreach ($topics as $topicName) {
                // Check if the topic exists, otherwise create it
                $topic = Topic::firstOrCreate(['name' => $topicName]);

                // Create the relationship
                ExamTopicRel::firstOrCreate([
                    'exam_id' => $exam->id,
                    'topic_id' => $topic->id,
                ]);
            }
        }
    }

    private function get_topics_from_ai($examName)
    {
        $apiKey = env('GEMINI_API_KEY', 'WRONG_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

        $prompt = "Generate a list of relevant topics for the '$examName' exam. 
                   Provide the response as a JSON array of topic names, like:
                   [ 'Mathematics', 'Physics', 'General Knowledge', ... ]";

        $postData = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $prompt
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::post($url, $postData)->json();

        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            $aiGeneratedText = trim($response['candidates'][0]['content']['parts'][0]['text']);

            // Clean AI response and convert it to an array
            $aiGeneratedText = preg_replace('/<pre>|<\/pre>|```json|```/', '', $aiGeneratedText);
            $topics = json_decode($aiGeneratedText, true);

            return json_last_error() === JSON_ERROR_NONE ? $topics : [];
        }

        return [];
    }
}
