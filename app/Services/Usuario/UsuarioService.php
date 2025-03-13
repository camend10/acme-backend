<?php

namespace App\Services\Usuario;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UsuarioService
{
    public function getUsersByFilter($buscar)
    {
        try {
            $user = auth('api')->user();

            return User::with(['role'])
                ->when(!empty($buscar), function ($query) use ($buscar) {
                    $query->where('name', 'like', '%' . $buscar . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate(20);
        } catch (\Exception $e) {
            Log::error('Error en getUsersByFilter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los usuarios.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function storeUser($request)
    {
        try {

            $data = is_array($request) ? $request : $request->toArray();

            unset($data['password_confirmation']);

            $estadosValidos = ['activo', 'inactivo'];
            if (!in_array($data['estado'], $estadosValidos)) {
                throw new \Exception("El estado debe ser 'activo' o 'inactivo'.");
            }

            $data['role_id'] = (int) $data['role_id'];

            // Log::info('Datos finales antes de insertar:', $data);

            $user = User::create($data);

            // Log::info('User:', $user->toArray());

            if ($user) {
                return $user;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error en storeUser: ' . $e->getMessage());
            return false;
        }
    }

    public function updateUser($request, $id)
    {
        try {

            $user = User::findOrFail($id);

            if (!$user) {
                return false;
            }

            $data = is_array($request) ? $request : $request->toArray();

            unset($data['password_confirmation']);
            if (isset($data['role_id'])) {
                $data['role_id'] = (int) $data['role_id'];
            }

            $user->update($data);

            // Log de usuario actualizado
            // Log::info('Usuario actualizado:', $user->toArray());

            return $user;
        } catch (\Exception $e) {
            // Registrar error en logs
            Log::error('Error en updateUser: ' . $e->getMessage());
            return false;
        }
    }

    public function estado($request, $id)
    {
        try {

            $user = User::findOrFail($id);
            if (!$user) {
                return false;
            }

            $estadosValidos = ['activo', 'inactivo'];
            if (!isset($request['estado']) || !in_array($request['estado'], $estadosValidos)) {
                throw new \Exception("Estado inválido. Debe ser 'activo' o 'inactivo'.");
            }
            
            // Actualizar solo si el estado es diferente al actual
            if ($user->estado !== $request['estado']) {
                $user->estado = $request['estado'];
                $user->save();
                // Log::info("Estado de usuario ID {$id} actualizado a '{$request['estado']}'");
            } else {
                // Log::info("No se realizó actualización. El usuario ID {$id} ya tiene el estado '{$request['estado']}'");
            }

            return $user;
        } catch (\Exception $e) {
            Log::error('Error en estado: ' . $e->getMessage());
            return false;
        }
    }
}
