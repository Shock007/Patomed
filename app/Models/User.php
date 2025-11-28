<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // si usas Sanctum, opcional
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false; // la tabla usa 'creado_en' en vez de created_at

    protected $fillable = [
        'usuario',
        'password_hash',
        'creado_en',
    ];

    protected $hidden = [
        'password_hash'
    ];

    // Laravel usa getAuthPassword() para obtener la contraseña
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Helper para verificar contraseña manual (por si lo necesitas)
    public function checkPassword($plain)
    {
        return Hash::check($plain, $this->password_hash);
    }

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'id_usuario', 'id_usuario');
    }
}
