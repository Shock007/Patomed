@extends('layouts.app')

@section('content')
<h2>Crear Paciente</h2>
<form method="POST" action="{{ route('pacientes.store') }}">
  @csrf
  <input name="codigo" placeholder="Código" required />
  <input name="nombre" placeholder="Nombre" required />
  <input name="apellidos" placeholder="Apellidos" required />
  <input name="cedula" placeholder="Cédula" required />
  <input name="fecha_nacimiento" type="date" />
  <input name="eps" placeholder="EPS" />
  <select name="sexo" required>
    <option value="m">M</option>
    <option value="f">F</option>
  </select>
  <button type="submit">Guardar</button>
</form>
@endsection
