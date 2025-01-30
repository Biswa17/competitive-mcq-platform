<?php

namespace App\Http\Controllers\StoreFront;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
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
                $status = 404;
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
            // Find the exam by ID
            $exam = Exam::find($id);

            if ($exam) {
                // Prepare response for found exam
                $response = [
                    'exam_id' => $exam->id,
                    'name' => $exam->name,
                    'description' => $exam->description,
                    'is_active' => $exam->is_active,
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
                $status = 404;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }

}
