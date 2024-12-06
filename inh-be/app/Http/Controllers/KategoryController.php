<?php

namespace App\Http\Controllers;

use App\Models\Kategory;
use Illuminate\Http\Request;

class KategoryController extends Controller
{
    public function index()
    {
        $categories = Kategory::all();
        return response()->json($categories);
    }

    public function create()
    {
        // Return view for creating a category (if using views)
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category = Kategory::create($validated);
        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    public function show($id)
    {
        $category = Kategory::findOrFail($id);
        return response()->json($category);
    }

    public function edit($id)
    {
        // Return view for editing a category (if using views)
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category = Kategory::findOrFail($id);
        $category->update($validated);

        return response()->json(['message' => 'Category updated successfully', 'data' => $category]);
    }

    public function destroy($id)
    {
        $category = Kategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
