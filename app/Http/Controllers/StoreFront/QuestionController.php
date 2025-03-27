<?php

namespace App\Http\Controllers\StoreFront;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\UserAnswer;

class QuestionController extends Controller
{
    public function getQuestionsByTopic(Request $request, $id)
    {
        // Initialize validation rules
        $rules = [
            'difficulty' => 'nullable|in:easy,medium,hard',
            'page_number' => 'nullable|integer|min:1',
            'questions_per_page' => 'nullable|integer|min:1',
            'status' => 'nullable|in:solved,unsolved',  // New filter for solved/unsolved
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

        // If validation fails, return errors
        if ($validator->fails()) {
            return $this->response($validator->errors(), 422, 'Validation Errors');
        }

        // Extract filters
        $difficulty = $request->input('difficulty');
        $statusFilter = $request->input('status'); // solved, unsolved, or null
        $userId = $request->token_id; // Extract user ID from token

        // Default pagination values
        $pageNumber = $request->get('page_number', 1);
        $questionsPerPage = $request->get('questions_per_page', 10);

        // Fetch user's answered questions along with selected options
        $answeredQuestions = UserAnswer::where('user_id', $userId)
            ->where('topic_id', $id)
            ->pluck('selected_option', 'question_id')  // Get question_id => selected_option
            ->toArray();

        // Start question query
        $query = Question::where('topic_id', $id);

        // Apply difficulty filter if provided
        if ($difficulty) {
            $query->where('difficulty_level', $difficulty);
        }

        // Apply solved/unsolved filter
        if ($statusFilter === 'solved') {
            $query->whereIn('id', array_keys($answeredQuestions)); // Only include solved
        } elseif ($statusFilter === 'unsolved') {
            $query->whereNotIn('id', array_keys($answeredQuestions)); // Only include unsolved
        }

        // Paginate the results
        $questions = $query->paginate($questionsPerPage, ['*'], 'page', $pageNumber);

        if ($questions->isNotEmpty()) {
            $response = [
                'questions' => $questions->map(function ($question) use ($answeredQuestions) {
                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'choices' => [
                            'A' => [
                                'value' => $question->option_a,
                                'explanation' => $question->option_a_explanation ?? null
                            ],
                            'B' => [
                                'value' => $question->option_b,
                                'explanation' => $question->option_b_explanation ?? null
                            ],
                            'C' => [
                                'value' => $question->option_c,
                                'explanation' => $question->option_c_explanation ?? null
                            ],
                            'D' => [
                                'value' => $question->option_d,
                                'explanation' => $question->option_d_explanation ?? null
                            ]
                        ],
                        'correct_option' => $question->correct_option,
                        'selected_option' => $answeredQuestions[$question->id] ?? null // Get selected answer if solved
                    ];
                }),
                'total_count' => $questions->total(),
                'page_number' => $pageNumber,
                'questions_per_page' => $questionsPerPage,
            ];
            $msg = 'Questions retrieved successfully';
        } else {
            $msg = 'No questions found for this topic and filters';
            $status = 200;
        }

        return $this->response($response, $status, $msg);
    }

    
    
    public function storeUserAnswer(Request $request)
    {
        $rules = [
            'topic_id' => 'required|exists:topics,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id,topic_id,' . $request->topic_id,
            'answers.*.selected_option' => 'required|in:A,B,C,D',
        ];

        $validator = Validator::make($request->all(), $rules);

        $response = [];
        $status = 200;
        $msg = "done";

        if ($validator->fails()) {
            $response = $validator->errors();
            $msg = 'Validation Errors';
            $status = 422;
        } else {
            try {
                $user_id = $request->token_id;
                $responses = [];

                foreach ($request->answers as $answer) {
                    $responses[] = UserAnswer::updateOrCreate(
                        [
                            'user_id' => $user_id,
                            'question_id' => $answer['question_id'],
                            'topic_id' => $request->topic_id,
                        ],
                        [
                            'selected_option' => $answer['selected_option'],
                        ]
                    );
                }

                $response = $responses;
            } catch (\Exception $e) {
                $response = ['error' => 'An error occurred while saving answers'];
                $msg = 'Database Error';
                $status = 500;
            }
        }

        return $this->response($response, $status, $msg);
    }

}
