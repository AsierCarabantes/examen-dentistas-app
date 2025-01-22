<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {
    
    public function register(Request $request) {
        $validateUser = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'DNI' => 'required|unique:users,DNI',
            'password' => 'required',
            'fecha_nacimiento' => 'required',
            'id_rol' => 'required'
        ]);

        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = new User();
        $user->nombre = $request->input('nombre');
        $user->apellido = $request->input('apellido');
        $user->DNI = $request->input('DNI');
        $user->password = Hash::make($request->input('password'));
        $user->fecha_nacimiento = $request->input('fecha_nacimiento');
        $user->id_rol = $request->input('id_rol');
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
        ], 201);

    }

    public function login(Request $request) {
        $validateUser = Validator::make($request->all(), [
            'DNI' => 'required',
            'password' => 'required',
        ]);

        if($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $user = User::where('DNI', $request->DNI)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Credenciales incorrectas',
            ], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'token' => $token,
            'DNI' => $user->DNI,
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

    public function dentistas() {
        $user = auth('sanctum')->user(); 
        if (!$user || $user->id_rol !== 1) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $dentistas = User::where('id_rol', '2')->get();
        return response()->json($dentistas);
    }

}