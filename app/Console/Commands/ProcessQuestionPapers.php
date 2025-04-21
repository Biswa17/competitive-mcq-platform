<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuestionPaper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan; // Added for calling commands
use Spatie\PdfToImage\Pdf;
use Exception;

class ProcessQuestionPapers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:question-papers {question_paper_id? : The ID of the specific question paper to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes question papers (PDFs/Images), extracting pages/copying images to a temporary directory.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $questionPaperId = $this->argument('question_paper_id');
        $papersToProcess = collect(); // Use a collection

        if ($questionPaperId) {
            $paper = QuestionPaper::find($questionPaperId);
            if ($paper) {
                $papersToProcess->push($paper);
                $this->info("Processing specific question paper ID: {$questionPaperId}");
                Log::info("Processing specific question paper ID: {$questionPaperId}");
            } else {
                $this->error("Question paper with ID {$questionPaperId} not found.");
                Log::error("Question paper with ID {$questionPaperId} not found.");
                return Command::FAILURE;
            }
        } else {
            $papersToProcess = QuestionPaper::where('is_sync', false)->get();
            $count = $papersToProcess->count();
            if ($count === 0) {
                $this->info('No unsynced question papers found to process.');
                Log::info('No unsynced question papers found to process.');
                return Command::SUCCESS;
            }
            $this->info("Found {$count} unsynced question papers to process.");
            Log::info("Found {$count} unsynced question papers to process.");
        }

        $processedCount = 0;
        $processedCount = 0;
        $errorCount = 0;
        $pythonScript = base_path('scripts/convert_pdf_to_images.py');
        $dpi = 300; // Default DPI, can be made configurable if needed

        if (!File::exists($pythonScript)) {
            $this->error("Python conversion script not found at: $pythonScript");
            Log::error("Python conversion script not found at: $pythonScript");
            return Command::FAILURE;
        }

        foreach ($papersToProcess as $paper) {
            $this->info("Processing paper ID: {$paper->id} - {$paper->file_path}");
            Log::info("Processing paper ID: {$paper->id} - {$paper->file_path}");

            // Assuming 'file_path' stores the path relative to the storage disk
            // And assuming you are using the 'public' disk or similar configured in filesystems.php
            // Adjust 'public' if your storage disk is different
            $pdfPath = Storage::disk('public')->path($paper->file_path);

            if (!File::exists($pdfPath)) {
                $this->error("PDF not found for paper ID {$paper->id} at: $pdfPath");
                Log::error("PDF not found for paper ID {$paper->id} at: $pdfPath");
                $errorCount++;
                continue; // Skip to the next paper
            }

            // Define a unique temporary output directory for this paper's images
            // Using storage_path for temporary processing is often better than public_path
            $outputFolder = storage_path("app/temp/qp_{$paper->id}_images");

            // Ensure the output directory exists and is writable
            if (!File::isDirectory($outputFolder)) {
                File::makeDirectory($outputFolder, 0777, true, true);
            } else {
                // Optionally clear the directory if reprocessing
                File::cleanDirectory($outputFolder);
            }

            try {
                // Execute the Python script for PDF conversion

                
                $cmd = escapeshellcmd("python3 $pythonScript $pdfPath $outputFolder $dpi");
                $this->line("Executing: $cmd"); // Log the command being run
                $conversionOutput = shell_exec($cmd);

                if ($conversionOutput === null || str_contains(strtolower($conversionOutput), 'error')) {
                   $this->error("Error converting PDF for paper ID {$paper->id}. Output: " . ($conversionOutput ?? 'No output'));
                   Log::error("Error converting PDF for paper ID {$paper->id}. Command: $cmd Output: " . ($conversionOutput ?? 'No output'));
                   $errorCount++;
                   continue;
                }

                $this->info("Successfully converted PDF to images for paper ID {$paper->id} into: $outputFolder");
                Log::info("Successfully converted PDF to images for paper ID {$paper->id} into: $outputFolder. Output: $conversionOutput");

                // Call the command to extract content from the generated images
                $this->line("Calling extract:image-content for directory: $outputFolder");
                Log::info("Calling extract:image-content for directory: $outputFolder and QP ID: {$paper->id}");

                $exitCode = Artisan::call('extract:image-content', [
                    'directory' => $outputFolder,
                    'question_paper_id' => $paper->id,
                    '--no-interaction' => true, // Prevent interactive questions if any
                ]);

                if ($exitCode === Command::SUCCESS) {
                    $this->info("Successfully extracted and stored content for paper ID {$paper->id}.");
                    Log::info("Successfully extracted and stored content for paper ID {$paper->id}.");
                    // Mark the paper as synced ONLY if both conversion and extraction were successful
                    $paper->is_sync = true;
                    $paper->save();
                    $processedCount++;
                } else {
                    $this->error("Failed to extract content for paper ID {$paper->id}. Exit code: {$exitCode}");
                    Log::error("Failed to extract content for paper ID {$paper->id} from directory {$outputFolder}. Exit code: {$exitCode}");
                    $errorCount++;
                    // Optionally decide if you want to keep the temporary images for debugging
                    // continue; // Skip cleanup if extraction failed
                }

                // Clean up the temporary directory after successful processing (or always, depending on preference)
                // Uncomment the line below to delete the temp images after processing
                // File::deleteDirectory($outputFolder);
                // $this->info("Cleaned up temporary directory: $outputFolder");


            } catch (Exception $e) {
                $this->error("An exception occurred while processing paper ID {$paper->id}: {$e->getMessage()}");
                Log::error("An exception occurred while processing paper ID {$paper->id}: {$e->getMessage()}");
                $errorCount++;
            }
        }

        $this->info("Processing complete. Processed: {$processedCount}, Errors: {$errorCount}");
        Log::info("Processing complete. Processed: {$processedCount}, Errors: {$errorCount}");

        return $errorCount > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
