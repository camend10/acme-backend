<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Usuario\UsuarioRequest;
use App\Models\User;
use App\Services\Usuario\UsuarioService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UsuarioService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {

        $buscar = $request->get('buscar');
        $users = $this->userService->getUsersByFilter($buscar);

        return response()->json([
            'total' => $users->total(),
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'direccion' => is_null($user->direccion) ? '' : $user->direccion,
                    'telefono' => $user->telefono,
                    'estado' => $user->estado,
                    'role_id' => $user->role_id,
                    'role' => $user->role,
                    "created_format_at" => $user->created_at ? $user->created_at->format("Y-m-d h:i A") : ''
                ];
            })
        ]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UsuarioRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->password) {
                $validated['password'] = bcrypt($request->password);
            }

            $user = $this->userService->storeUser($validated);

            if ($user == false) {
                return response()->json([
                    'message' => 403,
                    'message_text' => 'No se pudo realizar la acción',
                    'user' => []
                ], 403);
            }
            return response()->json([
                'message' => 201,
                'message_text' => 'El usuario se registró de manera exitosa',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'direccion' => is_null($user->direccion) ? '' : $user->direccion,
                    'telefono' => $user->telefono,
                    'estado' => $user->estado,
                    'role_id' => $user->role_id,
                    'role' => $user->role,
                    "created_format_at" => $user->created_at ? $user->created_at->format("Y-m-d h:i A") : ''
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 500,
                'message_text' => 'Ocurrió un error inesperado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UsuarioRequest $request, string $id)
    {

        try {

            $validated = $request->validated();

            $user = $this->userService->getUserById($request->id);

            $user = $this->userService->updateUser($validated, $request->id);

            return response()->json([
                'message' => 200,
                'message_text' => 'El usuario se editó de manera exitosa',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'direccion' => is_null($user->direccion) ? '' : $user->direccion,
                    'telefono' => $user->telefono,
                    'estado' => $user->estado,
                    'role_id' => $user->role_id,
                    'role' => $user->role,
                    "created_format_at" => $user->created_at ? $user->created_at->format("Y-m-d h:i A") : ''
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 500,
                'message_text' => 'Ocurrió un error inesperado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function estado(Request $request, $id)
    {

        $user = $this->userService->estado($request, $id);

        if ($request->estado == "activo") {
            $texto = 'Usuario activado de manera exitosa';
        } else {
            $texto = 'Usuario eliminado de manera exitosa';
        }

        if ($user == false) {
            return response()->json([
                'message' => 403,
                'message_text' => 'Usuario no encontrado',
                'user' => []
            ], 403);
        }

        return response()->json([
            'message' => 200,
            'message_text' => $texto,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'direccion' => is_null($user->direccion) ? '' : $user->direccion,
                'telefono' => $user->telefono,
                'estado' => $user->estado,
                'role_id' => $user->role_id,
                'role' => $user->role,
                "created_format_at" => $user->created_at ? $user->created_at->format("Y-m-d h:i A") : ''
            ]
        ]);
    }
}
