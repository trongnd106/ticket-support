<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        Category::create([
            'name' => $request->name,
        ]);
    
        return redirect(route('categories.index'))->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $label)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        Category::findorfail($label->id)->update(['name' => $request->name]);
    
        return redirect(route('categories.index'))->with('success', 'Category created successfully.');
    }
    public function destroy(Category $label)
    {
        $label->delete();

        return to_route('categories.index');
    }
}
