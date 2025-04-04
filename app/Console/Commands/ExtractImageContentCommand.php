<?php

namespace App\Console\Commands;

use App\Models\Question;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExtractImageContentCommand extends Command
{
    protected $signature = 'extract:image {file : Path to the input image file}';
    protected $description = 'Extract text content from an image using Google Gemini API';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        try {
            $content = $this->extractImageContent($filePath);

            $this->info("Extracted content received.");
            // $this->line($content); // Avoid printing potentially large JSON

            // Assuming $content is the JSON string from the API
            $this->storeQuestions($content);

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString()); // Add trace for debugging
            return 1;
        }
    }

    protected function extractImageContent(string $filePath): string
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

        $imageData = base64_encode(file_get_contents($filePath));
        

        $prompt = "Extract all questions with text and images if available. 
            Provide output in JSON format with LaTeX for math values.
            If a question has an image, include its base64-encoded data.
            Structure:
            [
                {
                    'question_text': 'Question?',
                    'option_a': 'Option A',
                    'option_b': 'Option B',
                    'option_c': 'Option C',
                    'option_d': 'Option D',
                    'correct_option': 'A',
                    'image': 'true/false' // If applicable
                }
            ]";

        $postData = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $prompt
                        ],
                        [
                            "inline_data" => [
                                "mime_type" => "image/jpeg",
                                "data" => $imageData
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $response = call_curl($url, 'POST', $postData);

        if ($response['status'] === 200 && isset($response['response']['candidates'][0]['content']['parts'][0]['text'])) {
            $aiGeneratedText = trim($response['response']['candidates'][0]['content']['parts'][0]['text']);

            // Remove potential Markdown code fences (```json ... ```) and surrounding whitespace
            $aiGeneratedText = preg_replace('/^\s*```json\s*|\s*```\s*$/m', '', $aiGeneratedText);

            // Convert AI response to JSON format
            return $aiGeneratedText;
            
        } else {
            p("here");
        }
    }

    protected function storeQuestions(string $jsonContent)
    {
        // Decode the JSON string into an associative array
        $questionsData = json_decode($jsonContent, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error("Failed to decode JSON: " . json_last_error_msg());
            $this->line("Received content: " . substr($jsonContent, 0, 500) . "..."); // Log part of the content for debugging
            return;
        }

        if (!is_array($questionsData)) {
            $this->error("Decoded JSON is not an array as expected.");
            // Log the decoded data type and value for debugging
            $this->line("Decoded data type: " . gettype($questionsData));
            $this->line("Decoded data: " . print_r($questionsData, true));
            return;
        }

        $this->info("Attempting to store " . count($questionsData) . " questions...");

        DB::beginTransaction();
        try {
            foreach ($questionsData as $index => $qData) {
                // Basic validation
                if (!isset($qData['question_text'], $qData['option_a'], $qData['option_b'], $qData['option_c'], $qData['option_d'])) {
                    $this->warn("Skipping question at index {$index} due to missing fields.");
                    continue;
                }

                $imagePath = null;
                if (!empty($qData['image'])) {
                    $imagePath = $this->saveImage($qData['image']);
                }

                Question::create([
                    'question_paper_id' => 1, // As requested
                    'topic_id' => null,      // As requested
                    'question_text' => $qData['question_text'],
                    'option_a' => $qData['option_a'],
                    'option_b' => $qData['option_b'],
                    'option_c' => $qData['option_c'],
                    'option_d' => $qData['option_d'],
                    'correct_option' => $qData['correct_option'] ?? 'A', // Handle potentially null correct_option
                ]);
            }
            DB::commit();
            $this->info("Successfully stored questions.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Database error while storing questions: " . $e->getMessage());
             $this->error("Trace: " . $e->getTraceAsString()); // Add trace for debugging
        }
    }

    protected function saveImage(string $base64Image): ?string
    {
        $imageData = base64_decode($base64Image);
        if (!$imageData) return null;

        $fileName = uniqid('question_', true) . '.jpg';
        $filePath = "public/questions/images/" . $fileName;
        file_put_contents(storage_path("app/" . $filePath), $imageData);
        return "questions/images/" . $fileName;
    }
}
