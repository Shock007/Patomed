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
        .filtro-fecha-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }
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
        <p class="text-muted small mb-3">Busque pacientes por código, cédula, nombre o fecha de registro</p>
        
        <form action="{{ route('pacientes.index') }}" method="GET" class="row g-3">
            <!-- Búsqueda por texto -->
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

            <!-- NUEVO: Filtro por Fecha -->
            <div class="col-12">
                <div class="filtro-fecha-container">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-calendar-range me-2"></i>Filtrar por Fecha de Registro
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Filtro</label>
                            <select class="form-select" name="tipo_fecha" id="tipoFecha">
                                <option value="">Sin filtro de fecha</option>
                                <option value="fecha_especifica" {{ request('tipo_fecha') == 'fecha_especifica' ? 'selected' : '' }}>
                                    Fecha Específica
                                </option>
                                <option value="mes" {{ request('tipo_fecha') == 'mes' ? 'selected' : '' }}>
                                    Por Mes
                                </option>
                                <option value="anio" {{ request('tipo_fecha') == 'anio' ? 'selected' : '' }}>
                                    Por Año
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Valor de Fecha</label>
                            
                            <!-- Input para fecha específica -->
                            <input type="date" 
                                   class="form-control filtro-fecha" 
                                   id="inputFechaEspecifica"
                                   name="valor_fecha" 
                                   value="{{ request('tipo_fecha') == 'fecha_especifica' ? request('valor_fecha') : '' }}"
                                   style="display: none;">

                            <!-- Input para mes -->
                            <input type="month" 
                                   class="form-control filtro-fecha" 
                                   id="inputMes"
                                   name="valor_fecha" 
                                   value="{{ request('tipo_fecha') == 'mes' ? request('valor_fecha') : '' }}"
                                   style="display: none;">

                            <!-- Input para año -->
                            <input type="number" 
                                   class="form-control filtro-fecha" 
                                   id="inputAnio"
                                   name="valor_fecha" 
                                   value="{{ request('tipo_fecha') == 'anio' ? request('valor_fecha') : '' }}"
                                   placeholder="Ej: 2025"
                                   min="2000"
                                   max="2099"
                                   style="display: none;">

                            <div id="placeholderFecha" class="form-control bg-light text-muted">
                                Seleccione un tipo de filtro
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-funnel"></i> Aplicar Filtro
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if(request('busqueda') || request('tipo_fecha'))
            <div class="alert alert-info mt-3 mb-0">
                <i class="bi bi-info-circle me-2"></i>
                @if(request('busqueda'))
                    Búsqueda: <strong>{{ request('busqueda') }}</strong>
                @endif
                @if(request('tipo_fecha') && request('valor_fecha'))
                    | Filtro de fecha: 
                    @if(request('tipo_fecha') == 'fecha_especifica')
                        <strong>{{ \Carbon\Carbon::parse(request('valor_fecha'))->format('d/m/Y') }}</strong>
                    @elseif(request('tipo_fecha') == 'mes')
                        <strong>{{ \Carbon\Carbon::parse(request('valor_fecha'))->locale('es')->isoFormat('MMMM YYYY') }}</strong>
                    @elseif(request('tipo_fecha') == 'anio')
                        <strong>Año {{ request('valor_fecha') }}</strong>
                    @endif
                @endif
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
                        <th>F. Registro</th>
                        <th>Resultado</th>
                        <th>Estado</th>
                        <th>F. Validación</th>
                        <th style="min-width: 320px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                        <tr>
                            <td class="fw-semibold">{{ $paciente->codigo }}</td>
                            <td>{{ $paciente->nombre_completo }}</td>
                            <td>{{ $paciente->cedula }}</td>
                            <td>{{ $paciente->fecha_creacion->format('d/m/Y') }}</td>
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
                                @if($paciente->ultimoEstudio && $paciente->ultimoEstudio->fecha_validacion)
                                    <small class="text-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $paciente->ultimoEstudio->fecha_validacion->format('d/m/Y H:i') }}
                                    </small>
                                @else
                                    <small class="text-muted">No validado</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('pacientes.show', $paciente->id_paciente) }}" 
                                       class="btn btn-sm btn-primary btn-action"
                                       title="Ver detalles">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    
                                    @if($paciente->ultimoEstudio && $paciente->ultimoEstudio->estado == 0)
                                        <!-- Si NO está validado: mostrar botones de Editar y Validar -->
                                        <a href="{{ route('pacientes.edit', $paciente->id_paciente) }}" 
                                           class="btn btn-sm btn-warning btn-action"
                                           title="Editar información">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>

                                        <form action="{{ route('pacientes.validar', $paciente->id_paciente) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Confirma la validación del paciente {{ $paciente->codigo }}?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success btn-action"
                                                    title="Validar paciente">
                                                <i class="bi bi-check-circle"></i> Validar
                                            </button>
                                        </form>
                                    @else
                                        <!-- Si está validado: mostrar botón de Invalidar -->
                                        <form action="{{ route('pacientes.invalidar', $paciente->id_paciente) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Desea invalidar este paciente para poder editarlo?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-warning btn-action"
                                                    title="Invalidar para editar">
                                                <i class="bi bi-x-circle"></i> Invalidar
                                            </button>
                                        </form>
                                    @endif
                                    
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
                            <td colspan="8" class="text-center text-muted py-4">
                                @if(request('busqueda') || request('tipo_fecha'))
                                    <i class="bi bi-search fs-1 d-block mb-2"></i>
                                    No se encontraron pacientes con los criterios especificados
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
                    <strong>Advertencia:</strong> Esta acción eliminará permanentemente todos los datos y estudios asociados.
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

    // NUEVO: Sistema de filtro dinámico por fecha
    const tipoFecha = document.getElementById('tipoFecha');
    const inputFechaEspecifica = document.getElementById('inputFechaEspecifica');
    const inputMes = document.getElementById('inputMes');
    const inputAnio = document.getElementById('inputAnio');
    const placeholderFecha = document.getElementById('placeholderFecha');

    function actualizarCampoFecha() {
        // Ocultar todos los inputs
        document.querySelectorAll('.filtro-fecha').forEach(input => {
            input.style.display = 'none';
            input.removeAttribute('name');
        });
        placeholderFecha.style.display = 'none';

        // Mostrar el input correspondiente
        const tipo = tipoFecha.value;
        
        if (tipo === 'fecha_especifica') {
            inputFechaEspecifica.style.display = 'block';
            inputFechaEspecifica.setAttribute('name', 'valor_fecha');
        } else if (tipo === 'mes') {
            inputMes.style.display = 'block';
            inputMes.setAttribute('name', 'valor_fecha');
        } else if (tipo === 'anio') {
            inputAnio.style.display = 'block';
            inputAnio.setAttribute('name', 'valor_fecha');
        } else {
            placeholderFecha.style.display = 'block';
        }
    }

    // Ejecutar al cargar la página
    actualizarCampoFecha();

    // Ejecutar al cambiar el tipo de fecha
    tipoFecha.addEventListener('change', actualizarCampoFecha);

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