<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sorteo;
use App\Models\Usuario;

class UsuarioController extends Controller {

    public function index() {
        $user = auth('sanctum')->user();
        if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        $usuarios = Usuario::where('rol', 'participante')->get(['nombre', 'apellido', 'dni', 'sorteo_id']);

        return response()->json([$usuarios]);
    }
}
