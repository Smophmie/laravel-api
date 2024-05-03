<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $categoriesArray=[];
            foreach ($product->categories as $category) {
                $categoriesArray[] = $category->title;
            }
            $productsArray[] = [
                'id'=>$product->id,
                'name'=>$product->name,
                'price'=>$product->price,
                'stock'=>$product->stock,
                'categories'=>$categoriesArray
            ];
        }
        return response()->json(
            $productsArray
        );
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('categories') && !is_array($request->categories)){
            $categories = json_decode($request->categories, true);
            $request->merge(['categories' => $categories]);
        }
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required',
            'stock' => 'required',
            'categories' => 'sometimes|array|exists:categories,id'
          ]);
        $product = Product::create($request->all());
        $product->categories()->attach($request->categories);
        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        $categoriesArray=[];
        foreach ($product->categories as $category) {
            $categoriesArray[] = $category->title;
        }
        
    return response()->json([
        'id'=>$product->id,
        'name'=>$product->name,
        'price'=>$product->price,
        'stock'=>$product->stock,
        'categories'=>$categoriesArray
    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->has('categories') && !is_array($request->categories)){
            $categories = json_decode($request->categories, true);
            $request->merge(['categories' => $categories]);
        }

        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required',
            'stock' => 'required',
            'categories' => 'sometimes|array|exists:categories,id'
          ]);

          $product = Product::find($id);
          $product->update($request->all());

          if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

          return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();
        $product->categories()->detach();
        return ['Votre produit a bien été supprimé.'];
    }
}
