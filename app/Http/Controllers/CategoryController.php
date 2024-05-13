<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

    /**
 * @OA\Get(
 *     path="/categories",
 *     summary="Liste des catégories",
 *     description="Renvoie la liste de toutes les catégories avec les produits qui leur sont associés.",
 *     tags={"Catégories"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des catégories récupérée avec succès.",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID de la catégorie."),
 *                 @OA\Property(property="title", type="string", description="Titre de la catégorie."),
 *                 @OA\Property(
 *                     property="products",
 *                     type="array",
 *                     @OA\Items(type="string"),
 *                     description="Liste des noms des produits associés à cette catégorie."
 *                 ),
 *             ),
 *         ),
 *     ),
 * )
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
                'description'=>$category->description,
                'products'=>$productsArray
            ];
        }
        return response()->json(
            $categoriesArray
        );
    }

    /**
 * @OA\Post(
 *     path="/categories",
 *     summary="Créer une nouvelle catégorie",
 *     description="Crée une nouvelle catégorie avec le titre fourni.",
 *     tags={"Catégories"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title"},
 *             @OA\Property(property="title", type="string", description="Titre de la nouvelle catégorie."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Catégorie créée avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/Category"),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation des données en échec.",
 *     ),
 * )
 */

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255'
          ]);
            $newCategory = Category::create($request->all());
            return $newCategory;
    }

    /**
 * @OA\Get(
 *     path="/categories/{id}",
 *     summary="Afficher les détails d'une catégorie",
 *     description="Récupère les détails d'une catégorie spécifiée par son ID.",
 *     tags={"Catégories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la catégorie à afficher.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails de la catégorie récupérés avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/Category"),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Catégorie non trouvée.",
 *     ),
 * )
 */

    public function show(string $id)
    {
        $category = Category::find($id);
        return $category;
    }


    /**
 * @OA\Put(
 *     path="/categories/{id}",
 *     summary="Mettre à jour une catégorie",
 *     description="Met à jour les informations d'une catégorie spécifiée par son ID.",
 *     tags={"Catégories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la catégorie à mettre à jour.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title"},
 *             @OA\Property(property="title", type="string", description="Nouveau titre de la catégorie."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Catégorie mise à jour avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/Category"),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Catégorie non trouvée.",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation des données en échec.",
 *     ),
 * )
 */

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255'
          ]);
          $category = Category::find($id);
          $category->update($request->all());
          return $category;
    }

    /**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     summary="Supprimer une catégorie",
 *     description="Supprime une catégorie spécifiée par son ID.",
 *     tags={"Catégories"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la catégorie à supprimer.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Catégorie supprimée avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="La catégorie a été supprimée avec succès.")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Catégorie non trouvée.",
 *     ),
 * )
 */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $category->delete();
        return ['Votre categorie a bien été supprimée.'];
    }
}
