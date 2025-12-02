<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patomed - Ingreso de Pacientes</title>
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
        <a class="nav-link active px-3 py-2" href="{{ route('pacientes.create') }}">Ingreso de Pacientes</a>
        <a class="nav-link px-3 py-2" href="{{ route('pacientes.index') }}">Búsqueda y Listado</a>
        <a class="nav-link px-3 py-2" href="{{ route('reportes.index') }}">Reportes</a>
    </div>
</div>

<!-- CONTENIDO -->
<div class="container my-4">

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pacientes.store') }}" method="POST">
        @csrf
        
        <div class="row g-4">
            <!-- INFORMACIÓN DEL PACIENTE -->
            <div class="col-md-6">
                <div class="form-section">
                    <h5 class="fw-bold mb-3">Información del Paciente</h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Código <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                                   name="codigo" value="{{ old('codigo') }}" required>
                            @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('apellidos') is-invalid @enderror" 
                                   name="apellidos" value="{{ old('apellidos') }}" required>
                            @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Cédula <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('cedula') is-invalid @enderror" 
                                   name="cedula" value="{{ old('cedula') }}" required>
                            @error('cedula')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">F. Nacimiento</label>
                            <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                                   name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}">
                            @error('fecha_nacimiento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">EPS</label>
                            <input type="text" class="form-control" name="eps" value="{{ old('eps') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select class="form-select @error('sexo') is-invalid @enderror" name="sexo" required>
                                <option value="">Seleccione...</option>
                                <option value="m" {{ old('sexo') == 'm' ? 'selected' : '' }}>Masculino</option>
                                <option value="f" {{ old('sexo') == 'f' ? 'selected' : '' }}>Femenino</option>
                            </select>
                            @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- ANÁLISIS PATOLÓGICO -->
            <div class="col-md-6">
                <div class="form-section">
                    <h5 class="fw-bold mb-3">Análisis Patológico</h5>

                    <label class="form-label">Descripción Macroscópica</label>
                    <textarea class="form-control mb-3" name="descripcion_macroscopica" 
                              rows="3">{{ old('descripcion_macroscopica') }}</textarea>

                    <label class="form-label">Descripción Microscópica</label>
                    <textarea class="form-control mb-3" name="descripcion_microscopica" 
                              rows="3">{{ old('descripcion_microscopica') }}</textarea>

                    <label class="form-label">Diagnóstico</label>
                    <textarea class="form-control mb-3" name="diagnostico" 
                              rows="2">{{ old('diagnostico') }}</textarea>

                    <label class="form-label">Resultado <span class="text-danger">*</span></label>
                    <select class="form-select @error('resultado') is-invalid @enderror" name="resultado" required>
                        <option value="">Seleccione...</option>
                        <option value="0" {{ old('resultado') === '0' ? 'selected' : '' }}>Negativo</option>
                        <option value="1" {{ old('resultado') === '1' ? 'selected' : '' }}>Positivo</option>
                    </select>
                    @error('resultado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="form-section mt-4 d-flex gap-3 justify-content-center">
            <button type="submit" class="btn btn-primary fw-bold px-4">Guardar Completo</button>
            <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
        </div>
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- SCRIPT DE MONITOREO DE SESIÓN -->
<script src="{{ asset('js/session-monitor.js') }}"></script>
</body>
</html>