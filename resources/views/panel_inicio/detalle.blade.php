<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Paciente - Patomed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .form-section { background: white; border-radius: 15px; padding: 25px; box-shadow: 0px 2px 8px rgba(0,0,0,0.1); }
        .navbar-brand-icon { width: 38px; height: 38px; background: #0d47a1; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 8px; margin-right: 10px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0d47a1;">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="#"><div class="navbar-brand-icon">P</div><span class="fw-bold">Patomed</span></a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">@csrf<button type="submit" class="btn btn-light fw-semibold px-3">Cerrar Sesión</button></form>
    </div>
</nav>

<div class="container my-4">
    <div class="form-section">
        <h4 class="fw-bold mb-4">Información del Paciente</h4>
        <div class="row">
            <div class="col-md-6"><p><strong>Código:</strong> {{ $paciente->codigo }}</p></div>
            <div class="col-md-6"><p><strong>Nombre:</strong> {{ $paciente->nombre_completo }}</p></div>
            <div class="col-md-6"><p><strong>Cédula:</strong> {{ $paciente->cedula }}</p></div>
            <div class="col-md-6"><p><strong>EPS:</strong> {{ $paciente->eps ?? 'N/A' }}</p></div>
            <div class="col-md-6"><p><strong>Edad:</strong> {{ $paciente->edad ?? 'N/A' }} años</p></div>
            <div class="col-md-6"><p><strong>Sexo:</strong> {{ $paciente->sexo == 'm' ? 'Masculino' : 'Femenino' }}</p></div>
        </div>
    </div>

    <div class="form-section mt-4">
        <h5 class="fw-bold mb-3">Historial de Estudios</h5>
        @forelse($paciente->estudios as $estudio)
            <div class="card mb-3">
                <div class="card-body">
                    <h6>{{ $estudio->codigo_estudio }}</h6>
                    <p><strong>Fecha:</strong> {{ $estudio->fecha->format('d/m/Y') }}</p>
                    <p><strong>Diagnóstico:</strong> {{ $estudio->diagnostico ?? 'N/A' }}</p>
                    <p><strong>Resultado:</strong> <span class="badge bg-{{ $estudio->resultado ? 'danger' : 'success' }}">{{ $estudio->resultado_texto }}</span></p>
                    <p><strong>Médico:</strong> {{ $estudio->usuario->usuario }}</p>
                    <a href="{{ route('estudios.show', $estudio->id_estudio) }}" class="btn btn-sm btn-primary">Ver Reporte Completo</a>
                </div>
            </div>
        @empty
            <p class="text-muted">Sin estudios registrados</p>
        @endforelse
    </div>

    <a href="{{ route('pacientes.index') }}" class="btn btn-secondary mt-3">Volver al Listado</a>
</div>
</body>
</html>