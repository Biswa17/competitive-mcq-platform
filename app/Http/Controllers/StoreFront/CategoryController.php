<?php
namespace App\Http\Controllers\StoreFront;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CategoryController extends Controller
{
    // Get all categories (top-level and subcategories)
    public function index(Request $request)
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
            // Get top-level categories
            $categories = Category::get();

            if ($categories) {
                $response = $categories;
                $msg = 'Categories retrieved successfully';
                $status = 200;
            } else {
                $response = [];
                $msg = 'No categories found';
                $status = 404;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }

    // Get category tree (hierarchical structure)
    public function getCategoryTree(Request $request)
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
            // Get categories with subcategories
            $categories = Category::with('children')->whereNull('parent_id')->get();

            if ($categories->isNotEmpty()) {
                // Recursive function to load exams for level 2 categories
                $loadExams = function ($categories) use (&$loadExams) {
                    foreach ($categories as $category) {
                        if ($category->level == 2) {
                            $category->load('exams'); // Load exams relationship
                        }
                        if ($category->children->isNotEmpty()) {
                            $loadExams($category->children); // Recurse for children
                        }
                    }
                };

                // Start the recursive loading
                $loadExams($categories);

                // Recursive function to transform the data and remove unwanted fields
                $transformData = function ($categories) use (&$transformData) {
                    return $categories->map(function ($category) use (&$transformData) {
                        $data = [
                            'id' => $category->id,
                            'name' => $category->name,
                            'description' => $category->description,
                            'parent_id' => $category->parent_id,
                            'level' => $category->level,
                            'children' => $category->children->isNotEmpty() ? $transformData($category->children) : [],
                        ];

                        if ($category->relationLoaded('exams')) {
                            $data['exams'] = $category->exams->map(function ($exam) {
                                // Select only desired exam fields, excluding pivot
                                return [
                                    'id' => $exam->id,
                                    'name' => $exam->name,
                                    'description' => $exam->description,
                                    // Add other exam fields if needed, but exclude pivot
                                ];
                            });
                        }
                        return $data;
                    });
                };

                // Transform the final data structure
                $response = $transformData($categories);
                $msg = 'Category tree retrieved successfully';
                $status = 200;
            } else {
                $response = [];
                $msg = 'No categories found';
                $status = 404;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }

    /**
     * Get category by ID with its subcategories.
     */
    public function show(Request $request, $id)
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
            // Find the category by ID
            $category = Category::with('children')->find($id);

            if ($category) {
                // Prepare response for successful case
                $response = $category;
                $msg = 'Category retrieved successfully';
                $status = 200;
            } else {
                // Handle case when category is not found
                $response = [];
                $msg = 'Category not found';
                $status = 404;
            }
        }

        // Return final response at the end
        return $this->response($response, $status, $msg);
    }
}
