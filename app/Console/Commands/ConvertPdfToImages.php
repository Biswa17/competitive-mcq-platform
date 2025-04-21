<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConvertPdfToImages extends Command
{
    protected $signature = 'pdf:convert-to-images';
    protected $description = 'Convert a PDF to images and save each page as a separate image';

    public function handle()
    {
        $pdfPath = public_path('pdfs/question_paper_1.pdf');
        $outputFolder = public_path('images');
        $dpi = 300;

        if (!file_exists($pdfPath)) {
            $this->error("PDF not found at: $pdfPath");
            return 1;
        }

        if (!is_dir($outputFolder)) {
            mkdir($outputFolder, 0777, true);
        }

        $pythonScript = base_path('scripts/convert_pdf_to_images.py');
        $cmd = escapeshellcmd("python3 $pythonScript $pdfPath $outputFolder $dpi");
        $output = shell_exec($cmd);

        $this->info($output ?? "✅ Done");
        return 0;
    }

}
