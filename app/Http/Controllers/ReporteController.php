<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReporteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::with(['ultimoEstudio.usuario'])
                            ->orderBy('fecha_creacion', 'desc')
                            ->get();
        
        return view('panel_inicio.reportes', compact('pacientes'));
    }

    public function exportPDF($id)
    {
        $paciente = Paciente::with(['estudios.usuario'])->findOrFail($id);
        
        $pdf = Pdf::loadView('reportes.pdf', compact('paciente'));
        
        return $pdf->download('reporte_' . $paciente->codigo . '.pdf');
    }

    public function exportExcel($id)
    {
        $paciente = Paciente::with(['estudios.usuario'])->findOrFail($id);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // TÍTULO PRINCIPAL
        $sheet->setCellValue('A1', 'REPORTE DE ANÁLISIS PATOLÓGICO');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // DATOS DEL PACIENTE
        $row = 3;
        $sheet->setCellValue('A' . $row, 'DATOS DEL PACIENTE');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Código:');
        $sheet->setCellValue('B' . $row, $paciente->codigo);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Nombre:');
        $sheet->setCellValue('B' . $row, $paciente->nombre_completo);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Cédula:');
        $sheet->setCellValue('B' . $row, $paciente->cedula);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Edad:');
        $sheet->setCellValue('B' . $row, $paciente->edad ?? 'N/A');
        
        $row++;
        $sheet->setCellValue('A' . $row, 'EPS:');
        $sheet->setCellValue('B' . $row, $paciente->eps ?? 'N/A');
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Sexo:');
        $sheet->setCellValue('B' . $row, $paciente->sexo == 'm' ? 'Masculino' : 'Femenino');
        
        // HISTORIAL DE ESTUDIOS
        $row += 2;
        $sheet->setCellValue('A' . $row, 'HISTORIAL DE ESTUDIOS');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        
        $row++;
        // Encabezados de la tabla
        $sheet->setCellValue('A' . $row, 'Código');
        $sheet->setCellValue('B' . $row, 'Fecha');
        $sheet->setCellValue('C' . $row, 'Descripción Macro');
        $sheet->setCellValue('D' . $row, 'Descripción Micro');
        $sheet->setCellValue('E' . $row, 'Diagnóstico');
        $sheet->setCellValue('F' . $row, 'Resultado');
        
        // Estilar encabezados
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0D47A1');
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->getColor()->setRGB('FFFFFF');
        
        // Datos de los estudios
        foreach ($paciente->estudios as $estudio) {
            $row++;
            $sheet->setCellValue('A' . $row, $estudio->codigo_estudio);
            $sheet->setCellValue('B' . $row, $estudio->fecha->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $estudio->descripcion_macro ?? 'N/A');
            $sheet->setCellValue('D' . $row, $estudio->descripcion_micro ?? 'N/A');
            $sheet->setCellValue('E' . $row, $estudio->diagnostico ?? 'N/A');
            $sheet->setCellValue('F' . $row, $estudio->resultado_texto);
            
            // Color según resultado
            if ($estudio->resultado) {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('DC3545'); // Rojo
            } else {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setRGB('28A745'); // Verde
            }
        }
        
        // Ajustar ancho de columnas automáticamente
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);
        
        $fileName = 'reporte_' . $paciente->codigo . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);
        
        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}