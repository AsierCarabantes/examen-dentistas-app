<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    public function events() {
        return $this->belongsToMany(Event::class);
    }
    
    public function rols() {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
