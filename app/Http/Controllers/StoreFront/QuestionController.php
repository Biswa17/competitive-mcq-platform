<?php

namespace App\Http\Controllers\StoreFront;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function getQuestionsByTopic(Request $request, $id)
    {
        // Initialize validation rules (difficulty is optional)
        $rules = [
            'difficulty' => 'nullable|in:easy,medium,hard',  // Difficulty is optional, and should be one of these if provided
            'page_number' => 'nullable|integer|min:1',  // Pagination: page_number is optional, but if provided should be >= 1
            'questions_per_page' => 'nullable|integer|min:1',  // Pagination: questions_per_page is optional, but if provided should be >= 1
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
            // Retrieve the difficulty from request, default to null (no filtering by difficulty)
            $difficulty = $request->input('difficulty');

            // Default pagination values
            $pageNumber = $request->get('page_number', 1);  // Default to page 1 if not specified
            $questionsPerPage = $request->get('questions_per_page', 10);  // Default to 10 questions per page

            // Fetch questions for the given topic and optional difficulty
            $query = Question::where('topic_id', $id);

            if ($difficulty) {
                // If difficulty is provided, filter by difficulty
                $query->where('difficulty_level', $difficulty);
            }

            // Paginate the results
            $questions = $query->paginate($questionsPerPage, ['*'], 'page', $pageNumber);

            // If questions are found, hide the unwanted fields and format the response
            if ($questions->isNotEmpty()) {
                $response = [
                    'questions' => $questions->map(function ($question) {
                        // Build the choices structure with value and explanation
                        return [
                            'id' => $question->id,
                            'question_text' => $question->question_text,
                            'choices' => [
                                'A' => [
                                    'value' => $question->option_a,
                                    'explanation' => $question->option_a_explanation ?? null  // Return null if no explanation
                                ],
                                'B' => [
                                    'value' => $question->option_b,
                                    'explanation' => $question->option_b_explanation ?? null  // Return null if no explanation
                                ],
                                'C' => [
                                    'value' => $question->option_c,
                                    'explanation' => $question->option_c_explanation ?? null  // Return null if no explanation
                                ],
                                'D' => [
                                    'value' => $question->option_d,
                                    'explanation' => $question->option_d_explanation ?? null  // Return null if no explanation
                                ]
                                ],
                            'correct_option' => $question->correct_option
                        ];
                    }),
                    'total_count' => $questions->total(),
                    'page_number' => $pageNumber,
                    'questions_per_page' => $questionsPerPage,
                ];
                $msg = 'Questions retrieved successfully';
                $status = 200;
            } else {
                $response = [];
                $msg = 'No questions found for this topic and difficulty';
                $status = 404;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }


}
