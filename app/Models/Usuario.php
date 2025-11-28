<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    protected $fillable = [
        'usuario',
        'password_hash'
    ];
    
    protected $hidden = [
        'password_hash'
    ];
    
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;
    
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}