<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
 * @OA\Get(
 *     path="/users",
 *     summary="Liste des utilisateurs",
 *     description="Renvoie la liste de tous les utilisateurs.",
 *     tags={"Utilisateurs"},
 *     @OA\Response(
 *         response=200,
 *         description="Liste des utilisateurs récupérée avec succès.",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         ),
 *     ),
 * )
 */
    public function index()
    {
        $users = User::all();
        return $users;
    }

    /**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Afficher les détails d'un utilisateur",
 *     description="Récupère les détails d'un utilisateur spécifié par son ID.",
 *     tags={"Utilisateurs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'utilisateur à afficher.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Détails de l'utilisateur récupérés avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/User"),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Utilisateur non trouvé.",
 *     ),
 * )
 */
   
    public function show(string $id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
 * @OA\Post(
 *     path="/users",
 *     summary="Créer un nouvel utilisateur",
 *     description="Crée un nouvel utilisateur avec les détails fournis.",
 *     tags={"Utilisateurs"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", description="Nom de l'utilisateur."),
 *             @OA\Property(property="email", type="string", format="email", description="Adresse e-mail de l'utilisateur."),
 *             @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur créé avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/User"),
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
            'name' => 'required|max:255',
            'email' => 'required',
            'password' => 'required'
          ]);
            User::create($request->all());
            return $request;
    }

    /**
 * @OA\Post(
 *     path="/register",
 *     summary="Inscription d'un nouvel utilisateur",
 *     description="Inscrit un nouvel utilisateur avec les détails fournis.",
 *     tags={"Authentification"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "password_confirmation"},
 *             @OA\Property(property="name", type="string", description="Nom de l'utilisateur."),
 *             @OA\Property(property="email", type="string", format="email", description="Adresse e-mail de l'utilisateur."),
 *             @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur."),
 *             @OA\Property(property="password_confirmation", type="string", format="password", description="Confirmation du mot de passe de l'utilisateur."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur inscrit avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", ref="#/components/schemas/User"),
 *             @OA\Property(property="token", type="string", description="Jetton d'accès JWT."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation des données en échec.",
 *     ),
 * )
 */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => [
                'required', 
                'email',
                'unique:users,email'
            ],
            'password' => [
                'required', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/',                
                'confirmed'
            ],
            'password_confirmation'=> [
                'required', 
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]+$/',  
                'same:password'
            ],
          ]);
           $user = User::create($request->all());
           $token = $user->createToken("API TOKEN")->plainTextToken;
            return response()->json([
                'user'=>$user,
                'token'=>$token,
            ]);
    }

    /**
 * @OA\Post(
 *     path="/login",
 *     summary="Authentification de l'utilisateur",
 *     description="Authentifie l'utilisateur avec les informations d'identification fournies.",
 *     tags={"Authentification"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", description="Adresse e-mail de l'utilisateur."),
 *             @OA\Property(property="password", type="string", format="password", description="Mot de passe de l'utilisateur."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur connecté avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true, description="Statut de la requête."),
 *             @OA\Property(property="message", type="string", example="Utilisateur connecté."),
 *             @OA\Property(property="token", type="string", description="Jetton d'accès JWT."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Erreur d'authentification.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false, description="Statut de la requête."),
 *             @OA\Property(property="message", type="string", example="L'e-mail ou le mot de passe est incorrect."),
 *             @OA\Property(property="errors", type="object", description="Erreurs de validation."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur.",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=false, description="Statut de la requête."),
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur."),
 *         ),
 *     ),
 * )
 */

    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => "L'e-mail ou le mot de passe est incorrect.",
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if(auth('sanctum')->check()){
                auth()->user()->tokens()->delete();
             }

            return response()->json([
                'status' => true,
                'message' => 'Utilisateur connecté.',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    /**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Mettre à jour un utilisateur",
 *     description="Met à jour les informations d'un utilisateur spécifié par son ID.",
 *     tags={"Utilisateurs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'utilisateur à mettre à jour.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", description="Nouveau nom de l'utilisateur."),
 *             @OA\Property(property="email", type="string", format="email", description="Nouvelle adresse e-mail de l'utilisateur."),
 *             @OA\Property(property="password", type="string", description="Nouveau mot de passe de l'utilisateur."),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur mis à jour avec succès.",
 *         @OA\JsonContent(ref="#/components/schemas/User"),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Utilisateur non trouvé.",
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
            'name' => 'required|max:255',
            'email' => 'required',
            'password' => 'required'
          ]);
          $user = User::find($id);
          $user->update($request->all());
          return $user;
    }

    /**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Supprimer un utilisateur",
 *     description="Supprime un utilisateur spécifié par son ID.",
 *     tags={"Utilisateurs"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'utilisateur à supprimer.",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur supprimé avec succès.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Utilisateur supprimé avec succès.")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Utilisateur non trouvé.",
 *     ),
 * )
 */

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return 'product deleted successfully';
    }
}
