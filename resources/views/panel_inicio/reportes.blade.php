<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patomed - Reportes</title>
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
            background: white; 
            border-radius: 15px; 
            padding: 25px; 
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1); 
        }
        .table th { 
            background-color: #0d47a1; 
            color: white; 
        }
        .reporte-general-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0px 4px 15px rgba(102, 126, 234, 0.4);
        }
        .btn-export {
            min-width: 140px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0d47a1;">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <div class="navbar-brand-icon">P</div>
            <span class="fw-bold">Patomed</span>
        </a>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-light fw-semibold px-3">Cerrar Sesión</button>
        </form>
    </div>
</nav>

<div class="bg-primary bg-opacity-25 py-2">
    <div class="container d-flex gap-3">
        <a class="nav-link px-3 py-2" href="{{ route('pacientes.create') }}">Ingreso de Pacientes</a>
        <a class="nav-link px-3 py-2" href="{{ route('pacientes.index') }}">Búsqueda y Listado</a>
        <a class="nav-link active px-3 py-2" href="{{ route('reportes.index') }}">Reportes</a>
    </div>
</div>

<div class="container my-4">

    <!-- SECCIÓN REPORTE GENERAL -->
    <div class="reporte-general-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="fw-bold mb-2">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                    Reporte General
                </h3>
                <p class="mb-3">
                    Genera un reporte completo con todos los pacientes y sus estudios registrados en el sistema.
                </p>
                <div class="d-flex gap-2 align-items-center">
                    <i class="bi bi-info-circle me-1"></i>
                    <small>El reporte incluye información completa de {{ $pacientes->count() }} paciente(s)</small>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('reportes.general-pdf') }}" 
                       class="btn btn-light btn-lg fw-bold btn-export" 
                       target="_blank">
                        <i class="bi bi-file-pdf-fill text-danger me-2"></i>
                        Exportar PDF
                    </a>
                    <a href="{{ route('reportes.general-excel') }}" 
                       class="btn btn-light btn-lg fw-bold btn-export">
                        <i class="bi bi-file-excel-fill text-success me-2"></i>
                        Exportar Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN REPORTES INDIVIDUALES -->
    <div class="form-section">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-person-lines-fill me-2"></i>
            Seleccione un Paciente para Generar Reporte
        </h5>
        <p class="text-muted small mb-3">
            Genere reportes individuales con el historial completo de estudios de cada paciente
        </p>
        
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre Completo</th>
                        <th>Cédula</th>
                        <th>Estudios</th>
                        <th style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pacientes as $paciente)
                        <tr>
                            <td class="fw-semibold">{{ $paciente->codigo }}</td>
                            <td>{{ $paciente->nombre_completo }}</td>
                            <td>{{ $paciente->cedula }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $paciente->estudios->count() }} estudio(s)
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('reportes.pdf', $paciente->id_paciente) }}" 
                                       class="btn btn-sm btn-danger" 
                                       target="_blank"
                                       title="Descargar PDF">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                    <a href="{{ route('reportes.excel', $paciente->id_paciente) }}" 
                                       class="btn btn-sm btn-success"
                                       title="Descargar Excel">
                                        <i class="bi bi-file-excel"></i> Excel
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay pacientes registrados en el sistema
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>