<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte - {{ $paciente->codigo }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .section { margin-bottom: 15px; }
        .section h3 { background: #0d47a1; color: white; padding: 5px 10px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background: #f0f0f0; }
        .badge { padding: 3px 8px; border-radius: 3px; color: white; font-weight: bold; }
        .badge-positivo { background: #dc3545; }
        .badge-negativo { background: #28a745; }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPORTE DE ANÁLISIS PATOLÓGICO</h1>
        <p><strong>Código del Paciente:</strong> {{ $paciente->codigo }}</p>
    </div>

    <div class="section">
        <h3>DATOS DEL PACIENTE</h3>
        <table>
            <tr><th>Nombre Completo</th><td>{{ $paciente->nombre_completo }}</td></tr>
            <tr><th>Cédula</th><td>{{ $paciente->cedula }}</td></tr>
            <tr><th>Edad</th><td>{{ $paciente->edad ?? 'N/A' }} años</td></tr>
            <tr><th>Sexo</th><td>{{ $paciente->sexo == 'm' ? 'Masculino' : 'Femenino' }}</td></tr>
            <tr><th>EPS</th><td>{{ $paciente->eps ?? 'N/A' }}</td></tr>
            <tr><th>Fecha de Registro</th><td>{{ $paciente->fecha_creacion->format('d/m/Y') }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h3>HISTORIAL DE ESTUDIOS</h3>
        @forelse($paciente->estudios as $estudio)
            <div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                <p><strong>Código:</strong> {{ $estudio->codigo_estudio }}</p>
                <p><strong>Fecha:</strong> {{ $estudio->fecha->format('d/m/Y H:i') }}</p>
                <p><strong>Descripción Macroscópica:</strong> {{ $estudio->descripcion_macro ?? 'No registrada' }}</p>
                <p><strong>Descripción Microscópica:</strong> {{ $estudio->descripcion_micro ?? 'No registrada' }}</p>
                <p><strong>Diagnóstico:</strong> {{ $estudio->diagnostico ?? 'No registrado' }}</p>
                <p><strong>Resultado:</strong> 
                    <span class="badge badge-{{ $estudio->resultado ? 'positivo' : 'negativo' }}">
                        {{ $estudio->resultado_texto }}
                    </span>
                </p>
                <p><strong>Médico:</strong> {{ $estudio->usuario->usuario }}</p>
            </div>
        @empty
            <p>Sin estudios registrados</p>
        @endforelse
    </div>
</body>
</html>