<?php

namespace App\Console\Commands;

use App\Models\Question;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Add File facade
use Illuminate\Support\Facades\Log;  // Add Log facade

class ExtractImageContentCommand extends Command
{
    // Updated signature to accept directory and question paper ID
    protected $signature = 'extract:image-content {directory : Path to the directory containing images} {question_paper_id : The ID of the associated QuestionPaper}';
    protected $description = 'Extract text content from all images in a directory using Google Gemini API and associate with a QuestionPaper';

    public function handle()
    {
        $directoryPath = $this->argument('directory');
        $questionPaperId = $this->argument('question_paper_id');

        if (!File::isDirectory($directoryPath)) {
            $this->error("Directory not found or is not a directory: {$directoryPath}");
            Log::error("Directory not found or is not a directory: {$directoryPath}");
            return Command::FAILURE;
        }

        // Get all image files (jpg, jpeg, png) and sort them naturally
        $imageFiles = File::files($directoryPath);
        usort($imageFiles, function ($a, $b) {
            return strnatcmp($a->getFilename(), $b->getFilename());
        });

        if (empty($imageFiles)) {
            $this->info("No image files found in directory: {$directoryPath}");
            Log::info("No image files found in directory: {$directoryPath}");
            return Command::SUCCESS; // No images to process is not necessarily an error
        }

        $this->info("Found " . count($imageFiles) . " images to process in {$directoryPath}");
        Log::info("Found " . count($imageFiles) . " images to process in {$directoryPath} for Question Paper ID: {$questionPaperId}");

        $allQuestionsData = [];
        $errorOccurred = false;

        foreach ($imageFiles as $imageFile) {
            $filePath = $imageFile->getPathname();
            $this->line("Processing image: {$filePath}");
            try {
                $jsonContent = $this->extractImageContent($filePath);
                $this->info("Extracted content from: {$imageFile->getFilename()}");
                // $this->line("Raw JSON: " . substr($jsonContent, 0, 200) . "..."); // Debugging

                $questionsData = json_decode($jsonContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error("Failed to decode JSON from {$imageFile->getFilename()}: " . json_last_error_msg());
                    Log::error("Failed to decode JSON from {$imageFile->getFilename()} for QP ID {$questionPaperId}: " . json_last_error_msg() . " | Content: " . substr($jsonContent, 0, 500));
                    $errorOccurred = true;
                    continue; // Skip this image's content
                }

                if (is_array($questionsData)) {
                    $allQuestionsData = array_merge($allQuestionsData, $questionsData);
                } else {
                     $this->warn("Decoded JSON from {$imageFile->getFilename()} is not an array. Skipping merge.");
                     Log::warning("Decoded JSON from {$imageFile->getFilename()} for QP ID {$questionPaperId} is not an array. Type: " . gettype($questionsData));
                }

            } catch (\Exception $e) {
                $this->error("Error processing image {$filePath}: " . $e->getMessage());
                Log::error("Error processing image {$filePath} for QP ID {$questionPaperId}: " . $e->getMessage());
                $errorOccurred = true;
                // Decide if you want to stop processing or continue with other images
                // continue;
            }
        }

        if (!empty($allQuestionsData)) {
            $this->info("Attempting to store " . count($allQuestionsData) . " questions in total for Question Paper ID: {$questionPaperId}.");
            $this->storeQuestions($allQuestionsData, $questionPaperId);
        } else {
            $this->warn("No valid question data extracted from any image for Question Paper ID: {$questionPaperId}.");
            Log::warning("No valid question data extracted from any image for Question Paper ID: {$questionPaperId}.");
        }

        return $errorOccurred ? Command::FAILURE : Command::SUCCESS;
    }

    // No change needed here for now, still extracts from a single file path
    protected function extractImageContent(string $filePath): string
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro-preview-03-25:generateContent?key=$apiKey";

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

    // Updated to accept decoded array and question paper ID
    protected function storeQuestions(array $questionsData, int $questionPaperId)
    {
        if (empty($questionsData)) {
             $this->info("No questions data provided to store for Question Paper ID: {$questionPaperId}.");
             return;
        }

        $this->info("Attempting to store " . count($questionsData) . " questions for Question Paper ID: {$questionPaperId}...");
        Log::info("Attempting to store " . count($questionsData) . " questions for Question Paper ID: {$questionPaperId}...");

        // Optional: Delete existing questions for this paper ID before inserting new ones
        // Question::where('question_paper_id', $questionPaperId)->delete();
        // $this->info("Deleted existing questions for Question Paper ID: {$questionPaperId}.");
        // Log::info("Deleted existing questions for Question Paper ID: {$questionPaperId}.");


        DB::beginTransaction();
        try {
            foreach ($questionsData as $index => $qData) {
                // Basic validation - ensure it's an array and has required keys
                if (!is_array($qData) || !isset($qData['question_text'], $qData['option_a'], $qData['option_b'], $qData['option_c'], $qData['option_d'])) {
                    $this->warn("Skipping question at index {$index} due to missing fields.");
                    continue;
                }

                $imagePath = null;
                if (!empty($qData['image'])) {
                    $imagePath = $this->saveImage($qData['image']);
                }

                $imagePath = null;
                // Assuming the Gemini prompt asks for 'image_base64' if an image exists for the question
                if (!empty($qData['image_base64'])) {
                    $imagePath = $this->saveImage($qData['image_base64'], $questionPaperId, $index);
                    if (!$imagePath) {
                         $this->warn("Failed to save image for question at index {$index} for QP ID {$questionPaperId}.");
                         Log::warning("Failed to save image for question at index {$index} for QP ID {$questionPaperId}.");
                    }
                }

                Question::create([
                    'question_paper_id' => $questionPaperId, // Use the passed ID
                    'topic_id' => null, // Keep as null for now, might be linked later
                    'question_text' => $qData['question_text'],
                    'option_a' => $qData['option_a'],
                    'option_b' => $qData['option_b'],
                    'option_c' => $qData['option_c'],
                    'option_d' => $qData['option_d'],
                    'correct_option' => $qData['correct_option'] ?? null, // Default to null if not provided
                    'explanation' => $qData['explanation'] ?? null, // Add explanation if provided by API
                    'marks' => $qData['marks'] ?? 1, // Default marks if not provided
                    'negative_marks' => $qData['negative_marks'] ?? 0, // Default negative marks
                    'image_path' => $imagePath, // Store the saved image path
                    // Add any other fields extracted from the API
                ]);
            }
            DB::commit();
            $this->info("Successfully stored questions for Question Paper ID: {$questionPaperId}.");
            Log::info("Successfully stored questions for Question Paper ID: {$questionPaperId}.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Database error while storing questions for QP ID {$questionPaperId}: " . $e->getMessage());
            Log::error("Database error while storing questions for QP ID {$questionPaperId}: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
        }
    }

    // Updated to include question paper ID and index for unique naming
    protected function saveImage(string $base64Image, int $questionPaperId, int $index): ?string
    {
        // Basic check if it looks like base64
        if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $base64Image)) {
             $this->error("Invalid base64 string provided for image at index {$index}, QP ID {$questionPaperId}.");
             Log::error("Invalid base64 string provided for image at index {$index}, QP ID {$questionPaperId}.");
             return null;
        }

        // Remove potential data URI scheme (e.g., "data:image/jpeg;base64,")
        if (strpos($base64Image, ',') !== false) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
        }

        $imageData = base64_decode($base64Image);
        if ($imageData === false) {
            $this->error("Failed to decode base64 image data at index {$index}, QP ID {$questionPaperId}.");
            Log::error("Failed to decode base64 image data at index {$index}, QP ID {$questionPaperId}.");
            return null;
        }

        // Create a more specific directory structure if it doesn't exist
        $directory = "public/questions/images/qp_{$questionPaperId}";
        Storage::makeDirectory($directory); // Uses Laravel's Storage facade

        // Generate a unique filename including index
        $fileName = "q_{$index}_" . uniqid() . '.jpg'; // Assuming jpeg, might need to detect mime type
        $storagePath = $directory . "/" . $fileName;

        try {
            // Use Storage facade for consistency and flexibility
            Storage::put($storagePath, $imageData);
            $this->info("Saved image to: {$storagePath}");
            // Return the path relative to the storage/app/public directory for web access
            // (assuming 'public' disk links storage/app/public to public/storage)
            return "questions/images/qp_{$questionPaperId}/" . $fileName;
        } catch (\Exception $e) {
             $this->error("Failed to save image {$fileName} for QP ID {$questionPaperId}: " . $e->getMessage());
             Log::error("Failed to save image {$fileName} for QP ID {$questionPaperId}: " . $e->getMessage());
             return null;
        }
    }
}
