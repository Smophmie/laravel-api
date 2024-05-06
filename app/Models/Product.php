<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


/**
 * @OA\Schema(
 *     schema="Product",
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID du produit"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nom du produit"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="Prix du produit"
 *     ),
 *     @OA\Property(
 *         property="stock",
 *         type="integer",
 *         description="Stock du produit"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="url",
 *         description="URL de l'image du produit"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de création du produit"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de mise à jour du produit"
 *     )
 * )
 */

class Product extends Model
{
    use HasFactory;

    protected $fillable =
        ["name",
        "price",
        "stock",
        "image"
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
