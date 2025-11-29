<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'password_hash',
        'creado_en',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'creado_en' => 'datetime',
    ];

    // Laravel usa este método para obtener la contraseña
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relaciones
    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'id_usuario', 'id_usuario');
    }
}