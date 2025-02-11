<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
    
    public function register(Request $request) {
        $validateUser = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'dni' => 'required|unique:usuarios,dni',
            'password' => 'required',
            'rol' => 'required|in:admin,participante',
            'sorteo_id' => 'nullable'
        ]);

        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = new Usuario();
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->dni = $request->dni;
        $user->password = Hash::make($request->password);
        $user->rol = $request->rol;
        $user->sorteo_id = $request->sorteo_id;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User created successfully'
        ], 201);
    }

    public function login(Request $request) {
        $validateUser = Validator::make($request->all(), [
            'dni' => 'required',
            'password' => 'required'
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = Usuario::where('dni', $request->dni)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login exitoso',
            'token' => $token,
            'dni' => $user->DNI,
            'password' => $user->password
        ], 200);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sesión cerrada correctamente'
        ], 200);
    }
}
