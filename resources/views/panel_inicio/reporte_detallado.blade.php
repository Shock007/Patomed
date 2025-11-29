<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Estudio - Patomed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .form-section { background: white; border-radius: 15px; padding: 25px; box-shadow: 0px 2px 8px rgba(0,0,0,0.1); }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<div class="container my-4">
    <div class="form-section">
        <div class="text-center mb-4">
            <h2>REPORTE DE ANÁLISIS PATOLÓGICO</h2>
            <p class="text-muted">Código: {{ $estudio->codigo_estudio }}</p>
        </div>

        <h5 class="fw-bold">Datos del Paciente</h5>
        <table class="table table-bordered">
            <tr><th>Nombre</th><td>{{ $estudio->paciente->nombre_completo }}</td></tr>
            <tr><th>Cédula</th><td>{{ $estudio->paciente->cedula }}</td></tr>
            <tr><th>EPS</th><td>{{ $estudio->paciente->eps ?? 'N/A' }}</td></tr>
            <tr><th>Edad</th><td>{{ $estudio->paciente->edad ?? 'N/A' }} años</td></tr>
            <tr><th>Sexo</th><td>{{ $estudio->paciente->sexo == 'm' ? 'Masculino' : 'Femenino' }}</td></tr>
        </table>

        <h5 class="fw-bold mt-4">Análisis</h5>
        <p><strong>Descripción Macroscópica:</strong><br>{{ $estudio->descripcion_macro ?? 'No registrada' }}</p>
        <p><strong>Descripción Microscópica:</strong><br>{{ $estudio->descripcion_micro ?? 'No registrada' }}</p>
        <p><strong>Diagnóstico:</strong><br>{{ $estudio->diagnostico ?? 'No registrado' }}</p>
        
        <h5 class="fw-bold mt-4">Resultado</h5>
        <p class="fs-4"><span class="badge bg-{{ $estudio->resultado ? 'danger' : 'success' }}">{{ $estudio->resultado_texto }}</span></p>

        <p class="mt-4"><strong>Médico responsable:</strong> {{ $estudio->usuario->usuario }}</p>
        <p><strong>Fecha de registro:</strong> {{ $estudio->fecha_registro->format('d/m/Y H:i') }}</p>

        <div class="no-print mt-4">
            <button onclick="window.print()" class="btn btn-primary">Imprimir</button>
            <a href="{{ route('pacientes.show', $estudio->id_paciente) }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</div>
</body>
</html>