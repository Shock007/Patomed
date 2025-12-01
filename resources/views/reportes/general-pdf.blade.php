<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte General de Pacientes</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 10px;
            margin: 15px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 3px solid #0d47a1; 
            padding-bottom: 10px; 
        }
        .header h1 {
            color: #0d47a1;
            margin: 5px 0;
        }
        .fecha-generacion {
            text-align: center;
            color: #666;
            margin-bottom: 15px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        table th, table td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left;
            font-size: 9px;
        }
        table th { 
            background: #0d47a1; 
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .badge { 
            padding: 2px 6px; 
            border-radius: 3px; 
            color: white; 
            font-weight: bold;
            display: inline-block;
        }
        .badge-positivo { background: #dc3545; }
        .badge-negativo { background: #28a745; }
        .sin-estudios {
            color: #999;
            font-style: italic;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE GENERAL DE PACIENTES</h1>
        <p>Sistema de Análisis Patológico - Patomed</p>
    </div>

    <div class="fecha-generacion">
        <strong>Fecha de generación:</strong> {{ date('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Código</th>
                <th style="width: 15%;">Nombre</th>
                <th style="width: 10%;">Cédula</th>
                <th style="width: 5%;">Edad</th>
                <th style="width: 5%;">Sexo</th>
                <th style="width: 10%;">EPS</th>
                <th style="width: 10%;">Cód. Estudio</th>
                <th style="width: 12%;">Desc. Macro</th>
                <th style="width: 12%;">Desc. Micro</th>
                <th style="width: 10%;">Diagnóstico</th>
                <th style="width: 8%;">Resultado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pacientes as $paciente)
                @if($paciente->estudios->count() > 0)
                    @foreach($paciente->estudios as $estudio)
                        <tr>
                            <td>{{ $paciente->codigo }}</td>
                            <td>{{ $paciente->nombre_completo }}</td>
                            <td>{{ $paciente->cedula }}</td>
                            <td>{{ $paciente->edad ?? 'N/A' }}</td>
                            <td>{{ $paciente->sexo == 'm' ? 'M' : 'F' }}</td>
                            <td>{{ $paciente->eps ?? 'N/A' }}</td>
                            <td>{{ $estudio->codigo_estudio }}</td>
                            <td>{{ Str::limit($estudio->descripcion_macro ?? 'N/A', 50) }}</td>
                            <td>{{ Str::limit($estudio->descripcion_micro ?? 'N/A', 50) }}</td>
                            <td>{{ Str::limit($estudio->diagnostico ?? 'N/A', 40) }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-{{ $estudio->resultado ? 'positivo' : 'negativo' }}">
                                    {{ $estudio->resultado_texto }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $paciente->codigo }}</td>
                        <td>{{ $paciente->nombre_completo }}</td>
                        <td>{{ $paciente->cedula }}</td>
                        <td>{{ $paciente->edad ?? 'N/A' }}</td>
                        <td>{{ $paciente->sexo == 'm' ? 'M' : 'F' }}</td>
                        <td>{{ $paciente->eps ?? 'N/A' }}</td>
                        <td colspan="5" class="sin-estudios" style="text-align: center;">Sin estudios registrados</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px;">
                        No hay pacientes registrados en el sistema
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Total de pacientes:</strong> {{ $pacientes->count() }}</p>
        <p>Documento generado automáticamente por el sistema Patomed</p>
    </div>
</body>
</html>