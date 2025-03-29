<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

            $this->info("Extracted content:");
            $this->line($content);

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }

    protected function extractImageContent(string $filePath): string
    {
        $apiKey = env('GEMINI_API_KEY', 'AIzaSyCr18U9exWuSpLyh6h0cIbTcM_hsZIKQo8');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

        $imageData = base64_encode(file_get_contents($filePath));

        $postData = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => "Extract all text exactly as it appears in the image."
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
            return trim($response['response']['candidates'][0]['content']['parts'][0]['text']);
        } else {
            p($response);
            print_r("Unable to fetch data. Retrying... Response: " . json_encode($response));
            sleep(10);
            return $this->extractImageContent($filePath);
        }
    }
}
