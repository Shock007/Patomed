<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patomed - Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="width: 380px; border-radius: 20px;">

        <!-- ICONO -->
        <div class="text-center mb-3">
            <div class="bg-primary text-white rounded-3 mx-auto d-flex justify-content-center align-items-center"
                 style="width: 60px; height: 60px; font-size: 32px; font-weight: bold;">
                P
            </div>
        </div>

        <!-- TITULO -->
        <h4 class="text-center fw-bold mb-1">Patomed</h4>
        <p class="text-center text-muted mb-4" style="font-size: 14px;">
            Sistema de Análisis Patológico
        </p>

        <!-- MENSAJES DE SESIÓN -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show">
                <i class="bi bi-info-circle-fill me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- FORMULARIO -->
        <form action="{{ route('login.auth') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">
                    <i class="bi bi-person-circle me-1"></i> Usuario
                </label>
                <input type="text" 
                       class="form-control" 
                       name="username" 
                       placeholder="Ingrese su usuario" 
                       required 
                       autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    <i class="bi bi-lock-fill me-1"></i> Contraseña
                </label>
                <input type="password" 
                       class="form-control" 
                       name="password" 
                       placeholder="Ingrese su contraseña" 
                       required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Iniciar Sesión
            </button>
        </form>

        <!-- NOTA DE SEGURIDAD -->
        <div class="alert alert-light mt-3 mb-0 text-center" style="font-size: 12px;">
            <i class="bi bi-shield-lock text-primary me-1"></i>
            Su sesión se cerrará automáticamente al cerrar el navegador
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>