<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'id_paciente';
    public $timestamps = false;

    protected $fillable = [
        'codigo','nombre','apellidos','cedula',
        'fecha_nacimiento','eps','sexo','fecha_creacion'
    ];

    public function estudios()
    {
        return $this->hasMany(Estudio::class, 'id_paciente', 'id_paciente');
    }
}
