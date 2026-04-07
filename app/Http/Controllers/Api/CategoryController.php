<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('subCategories')->get();
        if (count($categories) > 0) {
            return response()->json(['data' => CategoryResource::collection($categories)], 200);
        }
        return response()->json(['message' => 'No categories found'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create([
            'name' => $data['name'],
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move('images', $filename);
            $category->image = $filename;
            $category->save();
        }
        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     $category = Category::find($id);
    //     if ($category) {
    //         return response()->json(new CategoryResource($category), 200);
    //     }
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $slug)
    {
        $data = $request->validated();
        $category = Category::where('slug',$slug)->first();
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        $category->update([
            'name' => $data['name'],
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move('images', $filename);
            $category->image = $filename;
            $category->save();
        }

        return response()->json(new CategoryResource($category), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $category = Category::where('slug',$slug)->first();
        if ($category) {
            $category->delete();
            return response()->json(['message' => 'Category deleted'], 200);
        }
    }
}
