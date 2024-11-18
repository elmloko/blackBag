<?php

namespace App\Exports;

use App\Models\Despacho;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpedicionExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        return Despacho::query()
            ->where('estado', 'EXPEDICION')
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);
    }

    public function headings(): array
    {
        return [
            'Despacho',
            'Oficina Destino',
            'Categoría',
            'Subclase',
            'Nro. Envases',
            'Peso (Kg)',
            'Estado',
            'Enviado',
        ];
    }

    public function map($despacho): array
    {
        $oficinas = [
            'BOLPZ' => 'BOLPZ - LA PAZ',
            'BOTJA' => 'BOTJA - TARIJA',
            'BOPOI' => 'BOPOI - POTOSI',
            'BOCIJ' => 'BOCIJ - PANDO',
            'BOORU' => 'BOORU - ORURO',
            'BOTDD' => 'BOTDD - BENI',
            'BOSRE' => 'BOSRE - SUCRE',
            'BOSRZ' => 'BOSRZ - SANTA CRUZ',
        ];

        $categorias = [
            'A' => 'A - Aéreo',
            'B' => 'B - S.A.L.',
            'C' => 'C - Superficie',
            'D' => 'D - Prioritario por superficie',
        ];

        $subclases = [
            'UA' => 'UA CARTAS - AO',
            'UB' => 'UB CARTAS - MASIVO',
            'UC' => 'UC CARTAS - CORREO DIRECTO ARMONIZADO',
            // Añade el resto de las subclases según sea necesario...
        ];

        return [
            $despacho->identificador,
            $oficinas[$despacho->ofdestino] ?? $despacho->ofdestino,
            $categorias[$despacho->categoria] ?? $despacho->categoria,
            $subclases[$despacho->subclase] ?? $despacho->subclase,
            $despacho->nroenvase,
            $despacho->peso,
            $despacho->estado,
            $despacho->updated_at ? $despacho->updated_at->format('Y-m-d H:i:s') : 'No enviado',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Estilo del encabezado (ajustado para incluir la nueva columna)
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '4CAF50'], // Fondo verde
            ],
        ]);

        // Bordes para todas las celdas con datos
        $sheet->getStyle('A1:H' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Centrar el contenido de todas las celdas
        $sheet->getStyle('A1:H' . $sheet->getHighestRow())->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:H' . $sheet->getHighestRow())->getAlignment()->setVertical('center');

        // Auto ajustar el ancho de las columnas
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
