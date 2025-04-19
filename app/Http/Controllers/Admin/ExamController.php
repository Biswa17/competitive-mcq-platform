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
     * Display a listing of the exams, applying filters if provided.
     */
    public function index(Request $request)
    {
        // Start building the query for exams with relationships
        $query = Exam::with(['categories', 'topics']);

        // Apply category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        // Apply status filter
        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('is_active', $request->status);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where('name', 'like', $searchTerm);
            // Optionally, search in description or other fields
            // $query->orWhere('description', 'like', $searchTerm); 
        }

        // Paginate the results
        $exams = $query->paginate(10)->withQueryString(); // Append query string to pagination links
        
        // Get level 3 categories with their exam counts for the filter dropdown
        $categories = Category::where('level', 3)->withCount('exams')->get();
        
        // Get all topics for the modal dropdown
        $topics = Topic::all();
        
        // Pass the exams, categories, topics, and filter inputs data to the view
        return view('admin.exams.index', [
            'exams' => $exams,
            'categories' => $categories,
            'topics' => $topics,
            'filters' => $request->only(['category', 'status', 'search']) // Pass filters back to view
        ]);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        // Get all categories for the category dropdown
        $categories = Category::all();

        // Get all topics for the topic dropdown
        $topics = Topic::all();

        // Pass the exam, categories, and topics data to the view
        return view('admin.exams.edit', [
            'exam' => $exam,
            'categories' => $categories,
            'topics' => $topics
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
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
            // Update the exam record
            $exam->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->is_active,
            ]);

            // Sync categories if provided
            if ($request->has('categories')) {
                $exam->categories()->sync($request->categories);
            }

            // Sync topics if provided
            if ($request->has('topics')) {
                $exam->topics()->sync($request->topics);
            }

            // Redirect back with success message
            return redirect()->route('admin.exams')->with('success', 'Exam updated successfully');
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->route('admin.exams')->with('error', 'Failed to update exam: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        // Pass the exam data to the view
        return view('admin.exams.show', [
            'exam' => $exam
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Exam $exam)
    {
        try {
            // Delete the exam record
            $exam->delete();

            return redirect()->route('admin.exams')->with('success', 'Exam deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.exams')->with('error', 'Failed to delete exam: ' . $e->getMessage());
        }
    }

}
