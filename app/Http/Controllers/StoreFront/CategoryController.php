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

            if ($categories) {
                $response = $categories;
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
