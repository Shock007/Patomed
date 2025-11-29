<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'id_paciente';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'apellidos',
        'cedula',
        'fecha_nacimiento',
        'eps',
        'sexo',
        'fecha_creacion'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_creacion' => 'datetime',
    ];

    // Relaciones
    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'id_paciente', 'id_paciente');
    }

    // Accessor para obtener el último estudio
    public function ultimoEstudio()
    {
        return $this->hasOne(Estudio::class, 'id_paciente', 'id_paciente')
                    ->latest('fecha_registro');
    }

    // Accessor para edad
    public function getEdadAttribute()
    {
        if (!$this->fecha_nacimiento) return null;
        return $this->fecha_nacimiento->age;
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}