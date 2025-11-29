<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudio extends Model
{
    protected $table = 'estudios';
    protected $primaryKey = 'id_estudio';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
        'id_usuario',
        'codigo_estudio',
        'fecha',
        'descripcion_macro',
        'descripcion_micro',
        'diagnostico',
        'resultado',
        'estado',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'fecha_registro' => 'datetime',
        'resultado' => 'boolean',
        'estado' => 'integer',
    ];

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    // Accessors
    public function getResultadoTextoAttribute()
    {
        return $this->resultado ? 'Positivo' : 'Negativo';
    }

    public function getEstadoTextoAttribute()
    {
        return $this->estado == 1 ? 'Validado' : 'Parcial';
    }
}