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
        // Fetch all exams from the database
        $exams = Exam::all();

        // Prepare response
        $response = [
            'exams' => $exams,
        ];
        $msg = 'Exams retrieved successfully';
        $status = 200;

        return $this->response($response, $status, $msg);
    }

    /**
     * Get a single exam by ID.
     */
    public function getExamById(Request $request, $id)
    {
        // Find the exam by ID
        $exam = Exam::find($id);

        // Initialize response variables
        $response = [];
        $msg = '';
        $status = 200;

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

        // Return final response
        return $this->response($response, $status, $msg);
    }

}
