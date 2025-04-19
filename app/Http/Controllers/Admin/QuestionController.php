<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionImage;
use App\Models\QuestionPaper;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class QuestionController extends Controller
{
    /**
     * Display a listing of the questions.
     */
    public function index()
    {
        // Get all questions with their relationships and paginate them
        $questions = Question::with(['exam', 'topic', 'questionPaper', 'images'])->paginate(10);
        
        // Get all exams, topics, and question papers for the filters
        $exams = Exam::all();
        $topics = Topic::all();
        $questionPapers = QuestionPaper::all();
        
        // Pass the data to the view
        return view('admin.questions.index', [
            'questions' => $questions,
            'exams' => $exams,
            'topics' => $topics,
            'questionPapers' => $questionPapers
        ]);
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        // Load the question with its relationships
        $question->load(['exam', 'topic', 'questionPaper', 'images']);
        
        // Pass the question data to the view
        return view('admin.questions.show', [
            'question' => $question
        ]);
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        // Get all exams, topics, and question papers for the dropdowns
        $exams = Exam::all();
        $topics = Topic::all();
        $questionPapers = QuestionPaper::all();
        
        // Pass the data to the view
        return view('admin.questions.create', [
            'exams' => $exams,
            'topics' => $topics,
            'questionPapers' => $questionPapers
        ]);
    }

    /**
     * Show the form for editing the specified question.
     */
    public function edit(Question $question)
    {
        // Load the question with its relationships
        $question->load(['exam', 'topic', 'questionPaper', 'images']);
        
        // Get all exams, topics, and question papers for the dropdowns
        $exams = Exam::all();
        $topics = Topic::all();
        $questionPapers = QuestionPaper::all();
        
        // Pass the data to the view
        return view('admin.questions.edit', [
            'question' => $question,
            'exams' => $exams,
            'topics' => $topics,
            'questionPapers' => $questionPapers
        ]);
    }

    /**
     * Store a newly created question in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'exam_id' => 'required|exists:exams,id',
            'topic_id' => 'nullable|exists:topics,id', // Made optional
            'question_paper_id' => 'nullable|exists:question_papers,id', // Made optional
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Create the question record
            $question = Question::create([
                'question_text' => $request->question_text,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'correct_option' => $request->correct_option,
                'exam_id' => $request->exam_id,
                'topic_id' => $request->topic_id,
                'question_paper_id' => $request->question_paper_id,
            ]);

            // Handle image uploads if any
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = 'question_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('questions/images'), $filename);
                    
                    // Create image record
                    QuestionImage::create([
                        'question_id' => $question->id,
                        'image_path' => 'questions/images/' . $filename,
                    ]);
                }
            }

            // Redirect back with success message
            return redirect()->route('admin.questions')->with('success', 'Question created successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.questions.create')->with('error', 'Failed to create question: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, Question $question)
    {
        // Validate the request data
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D',
            'exam_id' => 'required|exists:exams,id',
            'topic_id' => 'nullable|exists:topics,id', // Made optional
            'question_paper_id' => 'nullable|exists:question_papers,id', // Made optional
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:question_images,id',
        ]);

        try {
            // Update the question record
            $question->update([
                'question_text' => $request->question_text,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'correct_option' => $request->correct_option,
                'exam_id' => $request->exam_id,
                'topic_id' => $request->topic_id,
                'question_paper_id' => $request->question_paper_id,
            ]);

            // Delete images if requested
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = QuestionImage::find($imageId);
                    if ($image) {
                        // Delete the file if it exists
                        if (file_exists(public_path($image->image_path))) {
                            unlink(public_path($image->image_path));
                        }
                        $image->delete();
                    }
                }
            }

            // Handle new image uploads if any
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = 'question_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('questions/images'), $filename);
                    
                    // Create image record
                    QuestionImage::create([
                        'question_id' => $question->id,
                        'image_path' => 'questions/images/' . $filename,
                    ]);
                }
            }

            // Redirect back with success message
            return redirect()->route('admin.questions')->with('success', 'Question updated successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.questions.edit', $question)->with('error', 'Failed to update question: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy(Question $question)
    {
        try {
            // Delete associated images
            foreach ($question->images as $image) {
                // Delete the file if it exists
                if (file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
                }
                $image->delete();
            }

            // Delete the question record
            $question->delete();

            return redirect()->route('admin.questions')->with('success', 'Question deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.questions')->with('error', 'Failed to delete question: ' . $e->getMessage());
        }
    }
}
