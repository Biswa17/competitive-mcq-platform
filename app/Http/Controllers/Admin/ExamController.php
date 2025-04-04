<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Exam;
use App\Models\Topic;
use Illuminate\Http\Request;
use Validator;

class ExamController extends Controller
{
    /**
     * Display a listing of the exams.
     */
    public function index()
    {
        // Get all exams with their relationships and paginate them
        $exams = Exam::with(['categories', 'topics'])->paginate(10);
        
        // Get all categories for the filter dropdown
        $categories = Category::all();
        
        // Get all topics for the modal dropdown
        $topics = Topic::all();
        
        // Pass the exams, categories, and topics data to the view
        return view('admin.exams.index', [
            'exams' => $exams,
            'categories' => $categories,
            'topics' => $topics
        ]);
    }
    
    /**
     * Get a single exam by ID.
     */
    public function getExamById(Request $request, $id)
    {
        // Define validation rules (empty in this case)
        $rules = [];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

        // If validation fails, return error response
        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            // Find the exam by ID
            $exam = Exam::with(['topics', 'questionPapers', 'categories'])->find($id);

            if ($exam) {
                $response = [
                    'exam_id' => $exam->id,
                    'name' => $exam->name,
                    'description' => $exam->description,
                    'is_active' => $exam->is_active,
                    'topics' => $exam->topics,
                    'question_papers' => $exam->questionPapers,
                    'categories' => $exam->categories
                ];
                $msg = 'Exam retrieved successfully';
                $status = 200;
            } else {
                // Handle case when exam is not found
                $response = [];
                $msg = 'Exam not found';
                $status = 404;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }

    /**
     * Update an existing exam by ID.
     */
    public function updateExam(Request $request, $id)
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

        // If validation fails, return error response
        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            try {
                // Find the exam by ID
                $exam = Exam::find($id);

                if (!$exam) {
                    $response = [];
                    $msg = 'Exam not found';
                    $status = 404;
                } else {
                    // Update the exam record
                    $exam->update([
                        'name' => $request->name,
                        'description' => $request->description,
                        'is_active' => $request->is_active,
                    ]);

                    // Prepare success response
                    $response = [
                        'exam_id' => $exam->id,
                        'name' => $exam->name,
                        'description' => $exam->description,
                        'is_active' => $exam->is_active,
                    ];
                    $msg = 'Exam updated successfully';
                    $status = 200;
                }
            } catch (\Exception $e) {
                // Handle exception and return error response
                $response = ['error' => $e->getMessage()];
                $msg = 'Failed to update exam';
                $status = 500;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }

    /**
     * Store a newly created exam in the database.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'topics' => 'nullable|array',
            'topics.*' => 'exists:topics,id',
        ]);

        try {
            // Create the exam record
            $exam = Exam::create([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            // Attach categories if provided
            if ($request->has('categories')) {
                $exam->categories()->attach($request->categories);
            }

            // Attach topics if provided
            if ($request->has('topics')) {
                $exam->topics()->attach($request->topics);
            }

            // Redirect back with success message
            return redirect()->route('admin.exams')->with('success', 'Exam created successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.exams')->with('error', 'Failed to create exam: ' . $e->getMessage());
        }
    }

    /**
     * Create a new exam by the admin (API endpoint).
     */
    public function createExam(Request $request)
    {
        // Define validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

        // If validation fails, return error response
        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            try {
                // Create the exam record
                $exam = Exam::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'is_active' => $request->is_active,
                ]);

                // Prepare success response
                $response = [
                    'exam_id' => $exam->id,
                    'name' => $exam->name,
                    'description' => $exam->description,
                    'is_active' => $exam->is_active,
                ];
                $msg = 'Exam created successfully';
                $status = 200;
            } catch (\Exception $e) {
                // Handle exception and return error response
                $response = ['error' => $e->getMessage()];
                $msg = 'Failed to create exam';
                $status = 500;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }

    /**
     * Get all exams (admin endpoint).
     */
    public function getExams(Request $request)
    {
        // Define validation rules (empty in this case)
        $rules = [];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

        // If validation fails, return error response
        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            // Fetch all exams from the database
            $exams = Exam::all();

            if ($exams) {
                $response = [
                    'exams' => $exams
                ];
                $msg = 'Exams retrieved successfully';
                $status = 200;
            } else {
                $response = [];
                $msg = 'No exams found';
                $status = 200;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }
}
