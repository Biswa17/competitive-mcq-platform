<?php
namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CategoryController extends Controller
{
    // Display a listing of categories
    public function index()
    {
        $categories = Category::with('parent')->paginate(10);
        $allCategories = Category::all(); // Get all categories for dropdowns
        return view('admin.categories.index', compact('categories', 'allCategories'));
    }

    // Add a new category
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Create category
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id ? $request->parent_id : null,
            'is_popular' => $request->has('is_popular') ? 1 : 0,
        ]);

        // Redirect back with success message
        return redirect()->route('admin.categories')->with('success', 'Category created successfully');
    }

    // Update an existing category
    public function update(Category $category, Request $request)
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Prepare data for update
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id ? $request->parent_id : null,
            'is_popular' => $request->has('is_popular') ? 1 : 0,
        ];

        // Update category
        $category->update($data);

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully');
    }

    // Delete a category
    public function destroy(Category $category)
    {
        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories')->with('error', 'Cannot delete category with subcategories');
        }

        // Delete the category
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully');
    }
}
