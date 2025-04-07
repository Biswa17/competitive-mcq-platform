<?php
namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CategoryController extends Controller
{
    // Display a listing of categories
    public function index(Request $request)
    {
        $sortColumn = $request->input('sort', 'name'); // Default sort by name
        $sortOrder = $request->input('order', 'asc'); // Default sort order asc

        $categories = Category::with('parent')
            ->orderBy($sortColumn, $sortOrder)
            ->paginate(10)
            ->appends(['sort' => $sortColumn, 'order' => $sortOrder]);
        $allCategories = Category::all(); // Get all categories for dropdowns
        return view('admin.categories.index', compact('categories', 'allCategories', 'sortColumn', 'sortOrder'));
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

        // Check if parent category is level 3
        if ($request->parent_id) {
            $parentCategory = Category::find($request->parent_id);
            if ($parentCategory && $parentCategory->level == 3) {
                return redirect()->route('admin.categories')->with('error', 'Level 3 categories cannot be parent categories');
            }
        }

        // Create category
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id ? $request->parent_id : null,
        ]);

        // Assign category level
        $category->level = $category->parent ? $category->parent->level + 1 : 1;
        $category->save();

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
        ];

        // Update category
        $category->update($data);

        // Assign category level
        $category->level = $category->parent ? $category->parent->level + 1 : 1;
        $category->save();

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
