<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Topic;
use App\Models\QuestionPaper;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class QuestionPaperController extends Controller
{
    /**
     * Display a listing of the question papers.
     */
    public function index(Request $request)
    {
        // Build query with filters
        $query = QuestionPaper::query()->with(['exam', 'questions']);
        
        if ($request->has('exam_id') && $request->exam_id) {
            $query->where('exam_id', $request->exam_id);
        }
        
        if ($request->has('topic_id') && $request->topic_id) {
            $topic = Topic::find($request->topic_id);
            if ($topic) {
                $query->whereHas('questions', function($q) use ($topic) {
                    $q->where('topic_id', $topic->id);
                });
            }
        }
        
        // Get all question papers with their relationships and paginate them
        $questionPapers = $query->paginate(10);
        
        // Get all exams and topics for the filters
        $exams = Exam::all();
        $topics = Topic::all();
        
        // Pass the data to the view
        return view('admin.question-papers.index', [
            'questionPapers' => $questionPapers,
            'exams' => $exams,
            'topics' => $topics
        ]);
    }

    /**
     * Display the specified question paper.
     */
    public function show(QuestionPaper $questionPaper)
    {
        // Load the question paper with its relationships
        $questionPaper->load(['exam', 'questions.topic']);
        
        // Pass the question paper data to the view
        return view('admin.question-papers.show', [
            'questionPaper' => $questionPaper
        ]);
    }

    /**
     * Show the form for creating a new question paper.
     */
    public function create()
    {
        // Get all exams for the dropdown
        $exams = Exam::all();
        $topics = Topic::all();
        
        // Pass the data to the view
        return view('admin.question-papers.create', [
            'exams' => $exams,
            'topics' => $topics
        ]);
    }

    /**
     * Show the form for editing the specified question paper.
     */
    public function edit(QuestionPaper $questionPaper)
    {
        // Load the question paper with its relationships
        $questionPaper->load(['exam', 'questions.topic']);
        
        // Get all exams for the dropdown
        $exams = Exam::all();
        $topics = Topic::all();
        
        // Pass the data to the view
        return view('admin.question-papers.edit', [
            'questionPaper' => $questionPaper,
            'exams' => $exams,
            'topics' => $topics
        ]);
    }

    /**
     * Store a newly created question paper in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'exam_id' => 'required|exists:exams,id',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        try {
            // Create the question paper record
            $questionPaper = QuestionPaper::create([
                'title' => $request->title,
                'exam_id' => $request->exam_id,
                'description' => $request->description,
            ]);

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $exam = Exam::find($request->exam_id);
                $examNameSlug = \Illuminate\Support\Str::slug($exam->name);
                $fileName = 'question_paper_' . $questionPaper->id . '.' . $file->getClientOriginalExtension();
                
                // Store the file
                $path = $file->storeAs("public/{$examNameSlug}/question_papers", $fileName);
                
                if ($path) {
                    $storagePath = str_replace('public/', '', $path);
                    $questionPaper->file_path = $storagePath;
                    $questionPaper->file_type = $file->getMimeType();
                    $questionPaper->save();
                }
            }

            // Redirect back with success message
            return redirect()->route('admin.question-papers')->with('success', 'Question paper created successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.question-papers.create')->with('error', 'Failed to create question paper: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified question paper in storage.
     */
    public function update(Request $request, QuestionPaper $questionPaper)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'exam_id' => 'required|exists:exams,id',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        try {
            // Update the question paper record
            $questionPaper->update([
                'title' => $request->title,
                'exam_id' => $request->exam_id,
                'description' => $request->description,
            ]);

            // Handle file upload if provided
            if ($request->hasFile('file')) {
                // Delete old file if it exists
                if ($questionPaper->file_path) {
                    Storage::delete('public/' . $questionPaper->file_path);
                }

                $file = $request->file('file');
                $exam = Exam::find($request->exam_id);
                $examNameSlug = \Illuminate\Support\Str::slug($exam->name);
                $fileName = 'question_paper_' . $questionPaper->id . '.' . $file->getClientOriginalExtension();
                
                // Store the new file
                $path = $file->storeAs("public/{$examNameSlug}/question_papers", $fileName);
                
                if ($path) {
                    $storagePath = str_replace('public/', '', $path);
                    $questionPaper->file_path = $storagePath;
                    $questionPaper->file_type = $file->getMimeType();
                    $questionPaper->save();
                }
            }

            // Redirect back with success message
            return redirect()->route('admin.question-papers')->with('success', 'Question paper updated successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.question-papers.edit', $questionPaper)->with('error', 'Failed to update question paper: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified question paper from storage.
     */
    public function destroy(QuestionPaper $questionPaper)
    {
        try {
            // Check if question paper has questions
            if ($questionPaper->questions()->count() > 0) {
                return redirect()->route('admin.question-papers')->with('error', 'Cannot delete question paper with questions');
            }

            // Delete the associated file if it exists
            if ($questionPaper->file_path) {
                Storage::delete('public/' . $questionPaper->file_path);
            }

            // Delete the question paper record
            $questionPaper->delete();

            return redirect()->route('admin.question-papers')->with('success', 'Question paper deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.question-papers')->with('error', 'Failed to delete question paper: ' . $e->getMessage());
        }
    }

    /**
     * Add questions to the question paper.
     */
    public function addQuestions(Request $request, QuestionPaper $questionPaper)
    {
        // Validate the request data
        $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        try {
            // Get the questions
            $questions = Question::whereIn('id', $request->question_ids)->get();
            
            // Update each question to associate with this question paper
            foreach ($questions as $question) {
                $question->question_paper_id = $questionPaper->id;
                $question->save();
            }

            return redirect()->route('admin.question-papers.show', $questionPaper)->with('success', 'Questions added successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.question-papers.show', $questionPaper)->with('error', 'Failed to add questions: ' . $e->getMessage());
        }
    }

    /**
     * Remove a question from the question paper.
     */
    public function removeQuestion(Request $request, QuestionPaper $questionPaper, Question $question)
    {
        try {
            // Check if the question belongs to this question paper
            if ($question->question_paper_id != $questionPaper->id) {
                return redirect()->route('admin.question-papers.show', $questionPaper)->with('error', 'Question does not belong to this question paper');
            }

            // Remove the question from this question paper
            $question->question_paper_id = null;
            $question->save();

            return redirect()->route('admin.question-papers.show', $questionPaper)->with('success', 'Question removed successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.question-papers.show', $questionPaper)->with('error', 'Failed to remove question: ' . $e->getMessage());
        }
    }
}
