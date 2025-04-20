<?php

namespace App\Http\Controllers\StoreFront;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\UserAnswerByTopic;
use Illuminate\Http\Request;
// Removed: use Illuminate\Support\Facades\Auth;
use Validator;

class ExamController extends Controller
{
    /**
     * Get all exams.
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
            // Find the exam by ID and include topics and question papers
            $exam = Exam::with(['topics', 'questionPapers'])->find($id);

            if ($exam) {
                // Prepare response for found exam with simplified topics (no pivot)
                $response = [
                    'exam_id' => $exam->id,
                    'name' => $exam->name,
                    'description' => $exam->description,
                    'is_active' => $exam->is_active,
                    'topics' => $exam->topics->map(function ($topic) {
                        return [
                                'id' => $topic->id,
                                'name' => $topic->name,
                                'solved_percentage' => 0
                            ];
            }),
            'question_papers' => $exam->questionPapers->map(function ($questionPapers) {
                return [
                            'id' => $questionPapers->id,
                            'name' => $questionPapers->name
                        ];
                    }),
                ];

                // Get user ID from the request (set by AllowGuestMiddleware)
                $userId = $request->token_id; // Will be user ID or -1 for guests

                if ($userId && $userId != -1) {
                    // User is authenticated, calculate solved percentage
                    $response['topics'] = $exam->topics->map(function ($topic) use ($userId, $exam) {
                        // Count total questions for this topic in this exam
                        // Assuming Question model has topic_id
                        // Need to refine this query based on actual DB structure
                        $totalQuestions = Question::where('topic_id', $topic->id)
                                                ->count();

                        // Count attempted questions by the user for this topic
                        $attemptedQuestions = UserAnswerByTopic::where('user_id', $userId)
                                                        ->where('topic_id', $topic->id)
                                                        ->count();

                        // Calculate percentage
                        $solvedPercentage = ($totalQuestions > 0) ? round(($attemptedQuestions / $totalQuestions) * 100) : 0;

                        return [
                            'id' => $topic->id,
                            'name' => $topic->name,
                            'solved_percentage' => $solvedPercentage
                        ];
                    });
                } else {
                     // User is a guest (token_id is -1 or missing), set percentage to 0
                     $response['topics'] = $exam->topics->map(function ($topic) {
                        return [
                            'id' => $topic->id,
                            'name' => $topic->name,
                            'solved_percentage' => 60
                        ];
                    });
                }


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



    public function getPopularExams(Request $request)
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
            // Fetch popular exams (where is_popular is true)
            $popularExams = Exam::where('is_popular', true)->get();

            if ($popularExams->isNotEmpty()) {
                $response = [
                    'exams' => $popularExams
                ];
                $msg = 'Popular exams retrieved successfully';
                $status = 200;
            } else {
                $response = [];
                $msg = 'No popular exams found';
                $status = 200;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }


    /**
     * Get exams by category ID.
     */
    public function getExamsByCategoryId(Request $request, $categoryId)
    {
        // Define validation rules (empty in this case, could add validation for categoryId existence if needed)
        $rules = [];

        // Validate the request data (though no specific data is expected in the request body here)
        $validator = Validator::make($request->all(), $rules);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

        // If validation fails (unlikely with empty rules, but good practice)
        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            // Fetch exams associated with the given category ID
            // Assuming a many-to-many relationship defined in Exam model named 'categories'
            // Or querying through the pivot table directly if the relationship isn't set up
            // Let's assume the relationship exists
            $exams = Exam::whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })->get();


            if ($exams->isNotEmpty()) {
                 $response = [
                    'exams' => $exams->map(function ($exam) {
                        // You might want to format the exam data similarly to other methods
                        return [
                            'id' => $exam->id,
                            'name' => $exam->name,
                            'description' => $exam->description,
                            'is_active' => $exam->is_active,
                            // Add other relevant fields if needed
                        ];
                    })
                ];
                $msg = 'Exams for the category retrieved successfully';
                $status = 200;
            } else {
                // Check if the category itself exists before saying no exams found
                // This requires the Category model
                // $categoryExists = \App\Models\Category::find($categoryId);
                // if (!$categoryExists) {
                //     $msg = 'Category not found';
                //     $status = 404;
                // } else {
                    $response = [];
                    $msg = 'No exams found for this category';
                    $status = 200; // Or 404 if you prefer for no results linked to a valid category
                // }
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }

}
