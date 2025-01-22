<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller {

    public function index() {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(Request $request) {
        $event = new Event();

        $event->nombre = $request->nombre;
        $event->fecha = $request->fecha;
        $event->descripcion = $request->descripcion;

        $event->save();

        return response()->json($event);
        
    }

    public function asistentes($id) {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $asistentes = $event->user()->where('id_rol', 2)->get();
        return response()->json($asistentes);
    }   

}
