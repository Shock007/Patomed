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
        .placeholder-text {
            color: #6c757d !important;
            font-style: italic;
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
        <a class="nav-link active px-3 py-2">Búsqueda y Listado</a>
        <a class="nav-link px-3 py-2" href="{{ route('panel_inicio.reportes') }}">Reportes</a>
    </div>
</div>

<!-- CONTENIDO PRINCIPAL -->
<div class="container my-4">

    <!-- BÚSQUEDA DE PACIENTES -->
    <div class="form-section">
        <h5 class="fw-bold mb-3">Búsqueda de Pacientes</h5>
        <p class="text-muted mb-3">Busque pacientes por código, cédula o nombre</p>
        
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tipo de búsqueda</label>
                <select class="form-select">
                    <option>Código</option>
                    <option>Cédula</option>
                    <option>Nombre</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">&nbsp;</label>
                <input type="text" class="form-control placeholder-text" placeholder="Ingrese término de búsqueda...">
            </div>
        </div>

        <div class="mt-3">
            <p class="text-muted">Se encontraron 0 resultados</p>
        </div>
    </div>

    <!-- LISTADO DE PACIENTES -->
    <div class="form-section mt-4">
        <h5 class="fw-bold mb-3">Listado de Pacientes</h5>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>EPS</th>
                        <th>Edad</th>
                        <th>Sexo</th>
                        <th>Resultado</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los datos se autocompletarán desde la base de datos -->
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            No se encontraron pacientes. Los datos se mostrarán aquí cuando se ingresen pacientes en el sistema.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

</body>
</html>