<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Validator;

class ExamController extends Controller
{
    /**
     * Create a new exam by the admin.
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
}
