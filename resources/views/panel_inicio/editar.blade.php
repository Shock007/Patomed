<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patomed - Editar Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f7fb; }
        .navbar-brand-icon {
            width: 38px; height: 38px; background: #0d47a1; color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; border-radius: 8px; margin-right: 10px;
        }
        .form-section {
            background: white; border-radius: 15px; padding: 25px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .info-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 14px;
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

<!-- CONTENIDO -->
<div class="container my-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('pacientes.index') }}">Búsqueda y Listado</a></li>
            <li class="breadcrumb-item active">Editar Paciente {{ $paciente->codigo }}</li>
        </ol>
    </nav>

    <!-- Mensajes -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Errores de validación</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- INFORMACIÓN GENERAL -->
    <div class="alert alert-info mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle fs-4 me-3"></i>
            <div>
                <strong>Editando paciente: {{ $paciente->codigo }}</strong>
                <p class="mb-0 small">
                    Los cambios se aplicarán a la información del paciente y al último estudio registrado
                </p>
            </div>
        </div>
    </div>

    <form action="{{ route('pacientes.update', $paciente->id_paciente) }}" method="POST" id="formEditar">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- INFORMACIÓN DEL PACIENTE -->
            <div class="col-md-6">
                <div class="form-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Información del Paciente</h5>
                        <span class="info-badge">
                            <i class="bi bi-lock-fill"></i> Código: {{ $paciente->codigo }}
                        </span>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="alert alert-secondary py-2">
                                <small><i class="bi bi-calendar-event me-2"></i>
                                Fecha de registro: {{ $paciente->fecha_creacion->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   name="nombre" value="{{ old('nombre', $paciente->nombre) }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellidos') is-invalid @enderror" 
                                   name="apellidos" value="{{ old('apellidos', $paciente->apellidos) }}" required>
                            @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('cedula') is-invalid @enderror" 
                                   name="cedula" value="{{ old('cedula', $paciente->cedula) }}" required>
                            @error('cedula')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">F. Nacimiento</label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                   name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->format('Y-m-d') : '') }}">
                            @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">EPS</label>
                            <input type="text" class="form-control" name="eps" 
                                   value="{{ old('eps', $paciente->eps) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select class="form-select @error('sexo') is-invalid @enderror" name="sexo" required>
                                <option value="">Seleccione...</option>
                                <option value="m" {{ old('sexo', $paciente->sexo) == 'm' ? 'selected' : '' }}>
                                    Masculino
                                </option>
                                <option value="f" {{ old('sexo', $paciente->sexo) == 'f' ? 'selected' : '' }}>
                                    Femenino
                                </option>
                            </select>
                            @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ÚLTIMO ANÁLISIS PATOLÓGICO -->
            <div class="col-md-6">
                <div class="form-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Último Análisis Patológico</h5>
                        @if($paciente->ultimoEstudio)
                            <span class="info-badge">
                                {{ $paciente->ultimoEstudio->codigo_estudio }}
                            </span>
                        @endif
                    </div>

                    @if($paciente->ultimoEstudio)
                        <div class="alert alert-secondary py-2 mb-3">
                            <small><i class="bi bi-calendar-event me-2"></i>
                            Fecha del estudio: {{ $paciente->ultimoEstudio->fecha->format('d/m/Y H:i') }}</small>
                        </div>

                        <label class="form-label">Descripción Macroscópica</label>
                        <textarea class="form-control mb-3" name="descripcion_macroscopica" 
                                  rows="3">{{ old('descripcion_macroscopica', $paciente->ultimoEstudio->descripcion_macro) }}</textarea>

                        <label class="form-label">Descripción Microscópica</label>
                        <textarea class="form-control mb-3" name="descripcion_microscopica" 
                                  rows="3">{{ old('descripcion_microscopica', $paciente->ultimoEstudio->descripcion_micro) }}</textarea>

                        <label class="form-label">Diagnóstico</label>
                        <textarea class="form-control mb-3" name="diagnostico" 
                                  rows="2">{{ old('diagnostico', $paciente->ultimoEstudio->diagnostico) }}</textarea>

                        <label class="form-label">Resultado <span class="text-danger">*</span></label>
                        <select class="form-select @error('resultado') is-invalid @enderror" name="resultado" required>
                            <option value="">Seleccione...</option>
                            <option value="0" {{ old('resultado', $paciente->ultimoEstudio->resultado) === 0 ? 'selected' : '' }}>
                                Negativo
                            </option>
                            <option value="1" {{ old('resultado', $paciente->ultimoEstudio->resultado) === 1 ? 'selected' : '' }}>
                                Positivo
                            </option>
                        </select>
                        @error('resultado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Este paciente no tiene estudios registrados. Solo se actualizarán los datos personales.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- BOTONES DE ACCIÓN -->
        <div class="form-section mt-4">
            <div class="d-flex gap-3 justify-content-center">
                <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
                    <i class="bi bi-check-circle me-2"></i>Aplicar Cambios
                </button>
                <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
            </div>
        </div>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Confirmación antes de enviar el formulario
    document.getElementById('formEditar').addEventListener('submit', function(e) {
        if (!confirm('¿Está seguro que desea aplicar estos cambios?')) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>