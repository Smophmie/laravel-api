<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $productsArray=[];
            foreach ($category->products as $product) {
                $productsArray[] = $product->name;
            }
            $categoriesArray[] = [
                'id'=>$category->id,
                'title'=>$category->title,
                'products'=>$productsArray
            ];
        }
        return response()->json(
            $categoriesArray
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255'
          ]);
            $newCategory = Category::create($request->all());
            return $newCategory;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        return $category;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255'
          ]);
          $category = Category::find($id);
          $category->update($request->all());
          return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();
        return ['Votre categorie a bien été supprimée.'];
    }
}
