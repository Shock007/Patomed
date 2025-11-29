<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patomed - Búsqueda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f7fb; }
        .navbar-brand-icon {
            width: 38px; height: 38px; background: #0d47a1; color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; border-radius: 8px; margin-right: 10px;
        }
        .nav-link.active {
            background-color: #0d47a1 !important;
            color: white !important;
            border-radius: 6px;
        }
        .form-section {
            background: white; border-radius: 15px; padding: 25px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .table th { background-color: #0d47a1; color: white; }
        .badge-positivo { background-color: #dc3545; }
        .badge-negativo { background-color: #28a745; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0d47a1;">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="navbar-brand-icon">P</div>
            <span class="fw-bold">Patomed</span>
        </a>
        <div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-light fw-semibold px-3">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</nav>

<!-- MENÚ -->
<div class="bg-primary bg-opacity-25 py-2">
    <div class="container d-flex gap-3">
        <a class="nav-link px-3 py-2" href="{{ route('pacientes.create') }}">Ingreso de Pacientes</a>
        <a class="nav-link active px-3 py-2" href="{{ route('pacientes.index') }}">Búsqueda y Listado</a>
        <a class="nav-link px-3 py-2" href="{{ route('reportes.index') }}">Reportes</a>
    </div>
</div>

<!-- CONTENIDO -->
<div class="container my-4">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- LISTADO DE PACIENTES -->
    <div class="form-section">
        <h5 class="fw-bold mb-3">Listado de Pacientes</h5>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>EPS</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Último Resultado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                        <tr>
                            <td>{{ $paciente->codigo }}</td>
                            <td>{{ $paciente->nombre_completo }}</td>
                            <td>{{ $paciente->cedula }}</td>
                            <td>{{ $paciente->eps ?? 'N/A' }}</td>
                            <td>{{ $paciente->edad ?? 'N/A' }}</td>
                            <td>{{ $paciente->sexo == 'm' ? 'M' : 'F' }}</td>
                            <td>
                                @if($paciente->ultimoEstudio)
                                    <span class="badge {{ $paciente->ultimoEstudio->resultado ? 'badge-positivo' : 'badge-negativo' }}">
                                        {{ $paciente->ultimoEstudio->resultado_texto }}
                                    </span>
                                @else
                                    <span class="text-muted">Sin estudios</span>
                                @endif
                            </td>
                            <td>
                                @if($paciente->ultimoEstudio)
                                    <span class="badge bg-{{ $paciente->ultimoEstudio->estado == 1 ? 'success' : 'warning' }}">
                                        {{ $paciente->ultimoEstudio->estado_texto }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('pacientes.show', $paciente->id_paciente) }}" 
                                   class="btn btn-sm btn-primary">Ver Detalles</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No hay pacientes registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-3">
            {{ $pacientes->links() }}
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>