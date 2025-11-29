<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePacienteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'codigo' => 'required|string|max:50|unique:pacientes,codigo',
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellidos' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'cedula' => 'required|string|max:50|regex:/^[0-9]+$/|unique:pacientes,cedula',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'eps' => 'nullable|string|max:100',
            'sexo' => 'required|in:m,f',
            
            // Campos del estudio
            'descripcion_macroscopica' => 'nullable|string|max:5000',
            'descripcion_microscopica' => 'nullable|string|max:5000',
            'diagnostico' => 'nullable|string|max:2000',
            'resultado' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'codigo.required' => 'El código del paciente es obligatorio',
            'codigo.unique' => 'Este código ya está registrado',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.regex' => 'El nombre solo puede contener letras',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'apellidos.regex' => 'Los apellidos solo pueden contener letras',
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.regex' => 'La cédula solo puede contener números',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'sexo.required' => 'El sexo es obligatorio',
            'sexo.in' => 'Seleccione un sexo válido',
            'resultado.required' => 'El resultado es obligatorio',
        ];
    }
}