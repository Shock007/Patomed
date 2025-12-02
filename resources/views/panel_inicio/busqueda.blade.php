<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patomed - Búsqueda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        .btn-action { min-width: 80px; margin: 2px; }
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
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- FORMULARIO DE BÚSQUEDA -->
    <div class="form-section mb-4">
        <h5 class="fw-bold mb-3">Búsqueda de Pacientes</h5>
        <p class="text-muted small mb-3">Busque pacientes por código, cédula o nombre</p>
        
        <form action="{{ route('pacientes.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Buscar por</label>
                <select class="form-select" name="tipo_busqueda" id="tipoBusqueda">
                    <option value="codigo" {{ request('tipo_busqueda') == 'codigo' ? 'selected' : '' }}>
                        Por Código
                    </option>
                    <option value="cedula" {{ request('tipo_busqueda') == 'cedula' ? 'selected' : '' }}>
                        Por Cédula
                    </option>
                    <option value="nombre" {{ request('tipo_busqueda') == 'nombre' ? 'selected' : '' }}>
                        Por Nombre
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Término de búsqueda</label>
                <input type="text" class="form-control" name="busqueda" 
                       value="{{ request('busqueda') }}" 
                       placeholder="Ingrese código del paciente...">
            </div>

            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-search"></i> Buscar
                </button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            </div>
        </form>

        @if(request('busqueda'))
            <div class="alert alert-info mt-3 mb-0">
                <i class="bi bi-info-circle me-2"></i>
                Mostrando resultados para: <strong>{{ request('busqueda') }}</strong> 
                ({{ $pacientes->total() }} resultado(s) encontrado(s))
            </div>
        @endif
    </div>

    <!-- LISTADO DE PACIENTES -->
    <div class="form-section">
        <h5 class="fw-bold mb-3">Listado de Pacientes</h5>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
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
                        <th style="min-width: 280px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                        <tr>
                            <td class="fw-semibold">{{ $paciente->codigo }}</td>
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
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('pacientes.show', $paciente->id_paciente) }}" 
                                       class="btn btn-sm btn-primary btn-action"
                                       title="Ver detalles">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    
                                    <a href="{{ route('pacientes.edit', $paciente->id_paciente) }}" 
                                       class="btn btn-sm btn-warning btn-action"
                                       title="Editar información">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-action" 
                                            onclick="confirmarEliminacion({{ $paciente->id_paciente }}, '{{ $paciente->codigo }}')"
                                            title="Eliminar paciente">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                @if(request('busqueda'))
                                    <i class="bi bi-search fs-1 d-block mb-2"></i>
                                    No se encontraron pacientes con el criterio de búsqueda especificado
                                @else
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No hay pacientes registrados en el sistema
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($pacientes->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $pacientes->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">¿Está seguro que desea eliminar el paciente <strong id="codigoPaciente"></strong>?</p>
                <div class="alert alert-warning mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Advertencia:</strong> Esta acción eliminará permanentemente:
                    <ul class="mb-0 mt-2">
                        <li>Todos los datos del paciente</li>
                        <li>Todos los estudios asociados</li>
                        <li>Esta acción no se puede deshacer</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Cancelar
                </button>
                <form id="formEliminar" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Eliminar Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Actualizar placeholder según tipo de búsqueda
    document.getElementById('tipoBusqueda').addEventListener('change', function() {
        const input = document.querySelector('input[name="busqueda"]');
        const placeholders = {
            'codigo': 'Ingrese código del paciente...',
            'cedula': 'Ingrese número de cédula...',
            'nombre': 'Ingrese nombre o apellido...'
        };
        input.placeholder = placeholders[this.value] || 'Ingrese término de búsqueda...';
    });

    // Función para confirmar eliminación
    function confirmarEliminacion(idPaciente, codigoPaciente) {
        document.getElementById('codigoPaciente').textContent = codigoPaciente;
        document.getElementById('formEliminar').action = `/pacientes/${idPaciente}`;
        
        const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
        modal.show();
    }
</script>
<script src="{{ asset('js/session-monitor.js') }}"></script>
</body>
</html>