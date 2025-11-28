@extends('layouts.app')

@section('content')
<h2>Crear Estudio para {{ $paciente->nombre }} {{ $paciente->apellidos }}</h2>
<form method="POST" action="{{ route('estudios.store') }}">
  @csrf
  <input type="hidden" name="id_paciente" value="{{ $paciente->id_paciente }}" />
  <input name="codigo_estudio" placeholder="Código Estudio" required />
  <textarea name="descripcion_macro" placeholder="Descripción macroscópica"></textarea>
  <textarea name="descripcion_micro" placeholder="Descripción microscópica"></textarea>
  <textarea name="diagnostico" placeholder="Diagnóstico"></textarea>
  <label>Resultado</label>
  <select name="resultado" required>
    <option value="0">Negativo</option>
    <option value="1">Positivo</option>
  </select>
  <label>Estado</label>
  <select name="estado">
    <option value="0">Guardado parcial</option>
    <option value="1">Validado</option>
  </select>
  <button type="submit">Guardar Estudio</button>
</form>
@endsection
