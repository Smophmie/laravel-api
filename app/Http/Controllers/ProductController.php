<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\UploadedFile;

class ProductController extends Controller
{
    /**
 * @OA\Get(
 *     path="/products",
 *     summary="Liste des produits",
 *     description="Renvoie la liste de tous les produits avec leurs détails et catégories.",
 *     tags={"Produits"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des produits récupérée avec succès.",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID du produit."),
 *                 @OA\Property(property="name", type="string", description="Nom du produit."),
 *                 @OA\Property(property="price", type="number", format="float", description="Prix du produit."),
 *                 @OA\Property(property="stock", type="integer", description="Stock disponible du produit."),
 *                 @OA\Property(property="image", type="string", description="URL de l'image du produit."),
 *                 @OA\Property(
 *                     property="categories",
 *                     type="array",
 *                     @OA\Items(type="string"),
 *                     description="Catégories auxquelles appartient le produit."
 *                 ),
 *             ),
 *         ),
 *     ),
 * )
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
                'image'=>$product->image,
                'categories'=>$categoriesArray
            ];
        }
        return response()->json(
            $productsArray
        );
        
    }

    /**
 * @OA\Post(
 *     path="/products",
 *     summary="Créer un nouveau produit",
 *     description="Crée un nouveau produit avec les détails fournis.",
 *     tags={"Produits"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "price", "stock"},
 *             @OA\Property(property="name", type="string", description="Nom du produit."),
 *             @OA\Property(property="price", type="number", format="float", description="Prix du produit."),
 *             @OA\Property(property="stock", type="integer", description="Stock disponible du produit."),
 *             @OA\Property(property="categories", type="array", @OA\Items(type="integer"), description="ID des catégories auxquelles appartient le produit."),
 *             @OA\Property(property="imageinput", type="string", format="binary", description="Image du produit (format: pdf, jpg, png, max: 2MB)."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produit créé avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/Product"),
 *     ),
 * )
 */

    public function store(Request $request)
    {
        // Conversion categories into array
        if ($request->has('categories') && !is_array($request->categories)){
            $categories = json_decode($request->categories, true);
            $request->merge(['categories' => $categories]);
        }

        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required',
            'stock' => 'required',
            'categories' => 'sometimes|array|exists:categories,id',
            'imageinput' => 'sometimes|mimes:pdf,jpg,png|max:2048',
          ]);

          $imagePath = $request->imageinput->store('uploads', 'public');
          $request['image']=$imagePath;

        $product = Product::create($request->all());
        $product->categories()->attach($request->categories);
        return $product;
    }

    /**
 * @OA\Get(
 *     path="/products/{id}",
 *     summary="Afficher les détails d'un produit",
 *     description="Récupère les détails d'un produit spécifié par son ID.",
 *     tags={"Produits"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du produit à afficher.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails du produit récupérés avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", description="ID du produit."),
 *             @OA\Property(property="name", type="string", description="Nom du produit."),
 *             @OA\Property(property="price", type="number", format="float", description="Prix du produit."),
 *             @OA\Property(property="stock", type="integer", description="Stock disponible du produit."),
 *             @OA\Property(property="image", type="string", description="URL de l'image du produit."),
 *             @OA\Property(
 *                 property="categories",
 *                 type="array",
 *                 @OA\Items(type="string"),
 *                 description="Catégories auxquelles appartient le produit."
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produit non trouvé.",
 *     ),
 * )
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
        'image'=>$product->image,
        'categories'=>$categoriesArray
    ]);
    }

    /**
 * @OA\Put(
 *     path="/products/{id}",
 *     summary="Mettre à jour un produit",
 *     description="Met à jour les détails d'un produit spécifié par son ID.",
 *     tags={"Produits"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du produit à mettre à jour.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", description="Nom du produit."),
 *             @OA\Property(property="price", type="number", format="float", description="Prix du produit."),
 *             @OA\Property(property="stock", type="integer", description="Stock disponible du produit."),
 *             @OA\Property(property="categories", type="array", @OA\Items(type="integer"), description="ID des catégories auxquelles appartient le produit."),
 *             @OA\Property(property="imageinput", type="string", format="binary", description="Image du produit (format: pdf, jpg, png, max: 2MB)."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produit mis à jour avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/Product"),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produit non trouvé.",
 *     ),
 * )
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
            'categories' => 'sometimes|array|exists:categories,id',
            'imageinput' =>'sometimes|mimes:pdf,jpg,png|max:2048'
          ]);

        $imagePath = $request->imageinput->store('blog', 'public');
        $request['image']=$imagePath;

        $product = Product::find($id);
        $product->update($request->all());

        if ($request->has('categories')) {
        $product->categories()->sync($request->categories);
        }

        return $product;
    }

    /**
 * @OA\Delete(
 *     path="/products/{id}",
 *     summary="Supprimer un produit",
 *     description="Supprime un produit spécifié par son ID.",
 *     tags={"Produits"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID du produit à supprimer.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Produit supprimé avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Votre produit a bien été supprimé.")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Produit non trouvé.",
 *     ),
 * )
 */

    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();
        $product->categories()->detach();
        return ['Votre produit a bien été supprimé.'];
    }
}
