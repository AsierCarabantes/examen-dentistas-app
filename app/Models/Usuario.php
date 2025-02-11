<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model {

    use HasApiTokens;
    
    protected $table = 'usuarios';

    public function sorteos() {
        return $this->belongsTo(Sorteo::class, 'sorteo_id');
    }
}
