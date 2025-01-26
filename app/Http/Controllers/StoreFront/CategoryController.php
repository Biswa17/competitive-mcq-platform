<?php
namespace App\Http\Controllers\StoreFront;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    // Get all categories (top-level and subcategories)
    public function index()
    {
        $categories = Category::whereNull('parent_id')->get(); // Get top-level categories
        return response()->json($categories, 200);
    }

    // Get category tree (hierarchical structure)
    public function getCategoryTree()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get(); // Get categories with subcategories
        return response()->json($categories, 200);
    }

    // Get category by ID (with its subcategories)
    public function show($id)
    {
        $category = Category::with('children')->find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200);
    }
}
