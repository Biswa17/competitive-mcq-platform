<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\QuestionPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QuestionPaperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = QuestionPaper::query();

        if ($request->has('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $questionPapers = $query->with('exam')->paginate(15); // Paginate results

        return response()->json($questionPapers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'name' => 'required|string|max:255',
            'question_paper_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exam = Exam::find($request->exam_id);
        if (!$exam) {
             return response()->json(['message' => 'Exam not found'], 404);
        }

        $file = $request->file('question_paper_file');
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        // Sanitize exam name and original file name for path
        $examNameSlug = \Illuminate\Support\Str::slug($exam->name);
        $fileNameSlug = \Illuminate\Support\Str::slug($originalFileName);
        $fileName = $fileNameSlug . '.' . $extension;

        // Store the file in public/exam_name/question_paper/name_of_qpaper.extension
        $path = $file->storeAs("public/{$examNameSlug}/question_paper", $fileName);

        if (!$path) {
            return response()->json(['message' => 'Failed to upload question paper'], 500);
        }

        // Store file path relative to the storage/app/public directory
        $storagePath = str_replace('public/', '', $path);

        $questionPaper = QuestionPaper::create([
            'exam_id' => $request->exam_id,
            'name' => $request->name,
            'file_path' => $storagePath, // Store the relative path
            'file_type' => $file->getMimeType(),
        ]);

        return response()->json($questionPaper, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionPaper  $questionPaper
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionPaper $questionPaper)
    {
        // Eager load the related exam
        $questionPaper->load('exam');
        return response()->json($questionPaper);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionPaper  $questionPaper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionPaper $questionPaper)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'question_paper_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('name')) {
            $questionPaper->name = $request->name;
        }

        if ($request->hasFile('question_paper_file')) {
            // Delete old file if it exists
            if ($questionPaper->file_path) {
                Storage::delete('public/' . $questionPaper->file_path);
            }

            $file = $request->file('question_paper_file');
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileNameSlug = \Illuminate\Support\Str::slug($originalFileName);
            $fileName = $fileNameSlug . '.' . $extension;

            // Get exam name for path
            $exam = $questionPaper->exam;
            $examNameSlug = \Illuminate\Support\Str::slug($exam->name);

            // Store the new file
            $path = $file->storeAs("public/{$examNameSlug}/question_paper", $fileName);
            $storagePath = str_replace('public/', '', $path);

            $questionPaper->file_path = $storagePath;
            $questionPaper->file_type = $file->getMimeType();
        }

        $questionPaper->save();

        return response()->json($questionPaper);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionPaper  $questionPaper
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionPaper $questionPaper)
    {
        // Delete the associated file if it exists
        if ($questionPaper->file_path) {
            Storage::delete('public/' . $questionPaper->file_path);
        }

        $questionPaper->delete();

        return response()->json(['message' => 'Question paper deleted successfully']);
    }

    /**
     * Analyze uploaded file to determine type and PDF characteristics
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function analyzeFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        $result = ['type' => strpos($mimeType, 'image/') === 0 ? 'image' : 'pdf'];

        if ($result['type'] === 'pdf') {
            $content = file_get_contents($file->getRealPath());
            // Simple check for text content in PDF
            $result['pdf_type'] = preg_match('/\/Font|\/Text/', $content) ? 'text' : 'image';
        }

        return response()->json($result);
    }
}
