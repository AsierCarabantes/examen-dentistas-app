<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sorteo;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SorteoController extends Controller {
    
    public function store(Request $request) {

        $user = auth('sanctum')->user();

        if(!$user || $user->rol !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'No autorizado'
            ], 403);
        }

        $validateData = Validator::make($request->all(), [
            'nombre' => 'required',
            'premio' => 'required',
            'fecha_sorteo' => 'required'
        ]);

        $sorteo = new Sorteo();

        $sorteo->nombre = $request->nombre;
        $sorteo->premio = $request->premio;
        $sorteo->fecha_sorteo = $request->fecha_sorteo;

        $sorteo->save();

        return response()->json($sorteo);
    }

    public function apuntarse($id_sorteo) {
        $user = Auth::user(); 

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'No autenticado'
            ], 403);
        }

        if ($user->rol == 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Solo participantes'
            ]);
        }

        $sorteo = Sorteo::find($id_sorteo);

        if (!$sorteo) {
            return response()->json([
                'status' => false,
                'message' => 'Sorteo no encontrado'
            ], 404);
        }

        Usuario::where('id', $user->id)->update(['sorteo_id' => $id_sorteo]);

        return response()->json([
            'status' => true,
            'message' => 'Participante apuntado con éxito',
            'sorteo' => $sorteo
        ], 200);
    }

    public function index() {
        $user = auth('sanctum')->user();
        if(!$user) {
            return response()->json([
                'status' => false,
                'message' => 'No autorizado'
            ], 403);
        }
    
        $sorteos = Sorteo::all();
        $resultados = []; 
    
        foreach ($sorteos as $sorteo) {
            $usuarios = $sorteo->usuarios()->get();
            $resultados[] = [
                "sorteo" => $sorteo->nombre,
                "usuarios" => $usuarios
            ];
        }

        return response()->json($resultados);

    }
    
}
