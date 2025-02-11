<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sorteo extends Model {
    
    protected $table = 'sorteos';
    
    public function usuarios() {
        return $this->hasMany(Usuario::class);
    }
}


