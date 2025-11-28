<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patomed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }
        .navbar-brand-icon {
            width: 38px;
            height: 38px;
            background: #0d47a1;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 8px;
            margin-right: 10px;
        }
        .nav-link.active {
            background-color: #0d47a1 !important;
            color: white !important;
            border-radius: 6px;
        }
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .table th {
            background-color: #0d47a1;
            color: white;
        }
        .stats-card {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

<!-- NAVBAR SUPERIOR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0d47a1;">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="navbar-brand-icon">P</div>
            <span class="fw-bold">Patomed</span>
        </a>

        <div>
            <a href="{{ route('login.index') }}" class="btn btn-light fw-semibold px-3">
                Cerrar Sesión
            </a>
        </div>
    </div>
</nav>

<!-- MENÚ -->
<div class="bg-primary bg-opacity-25 py-2">
    <div class="container d-flex gap-3">
        <a class="nav-link px-3 py-2" href="{{ route('panel_inicio.ingreso') }}">Ingreso de Pacientes</a>
        <a class="nav-link px-3 py-2" href="{{ route('panel_inicio.busqueda') }}">Búsqueda y Listado</a>
        <a class="nav-link active px-3 py-2">Reportes</a>
    </div>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="container my-4">

    <!-- BOTONES DE EXPORTACIÓN -->
    <div class="form-section">
        <h5 class="fw-bold mb-3">Reportes</h5>
        <p class="text-muted mb-3">Genere y exporte reportes de pacientes</p>
        
        <div class="row justify-content-center">
            <div class="col-md-4 text-center">
                <button class="btn btn-success w-75 mb-2">
                    Exportar a Excel
                </button>
            </div>
            <div class="col-md-4 text-center">
                <button class="btn btn-danger w-75 mb-2">
                    Exportar a PDF
                </button>
            </div>
            <div class="col-md-4 text-center">
                <button class="btn btn-primary w-75 mb-2">
                    Imprimir
                </button>
            </div>
        </div>
    </div>

    <!-- REPORTE DE ANÁLISIS PATOLÓGICO -->
    <div class="form-section mt-4">
        <h5 class="fw-bold mb-3">Reporte de Análisis Patológico</h5>
        <p class="text-muted mb-3">Información del paciente y resultados</p>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cédula</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>EPS</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Estado</th>
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se autocompletarán desde la base de datos -->
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            No hay datos para mostrar. Los reportes se generarán automáticamente cuando existan pacientes en el sistema.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ESTADÍSTICAS -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="stats-card">
                <h4>Total Pacientes</h4>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h4>Resultados Positivos</h4>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <h4>Resultados Negativos</h4>
                <h2>0</h2>
            </div>
        </div>
    </div>

</div>

</body>
</html>