<?php

namespace App\Exports;

use App\Models\Konsumen;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

/* STYLE */
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KonsumenExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithCustomStartCell
{

    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    /* DATA */

    public function collection()
    {

        $query = Konsumen::select(
            'nama',
            'no_hp',
            'email',
            'alamat',
            'sumber_lead',
            'status'
        );

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        $user = Auth::user();

        if ($user && $user->role === 'marketing') {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    /* POSISI TABEL */

    public function startCell(): string
    {
        return 'A4';
    }

    /* HEADER TABEL */

    public function headings(): array
    {
        return [
            'Nama',
            'No HP',
            'Email',
            'Alamat',
            'Sumber Lead',
            'Status'
        ];
    }

    /* STYLE */

    public function styles(Worksheet $sheet)
    {

        // JUDUL
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN DATA KONSUMEN');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16
            ],
            'alignment' => [
                'horizontal' => 'center'
            ]
        ]);

        // SUB JUDUL
        $sheet->mergeCells('A2:F2');

        $statusText = $this->status ? 'Status : '.$this->status : 'Semua Data';

        $sheet->setCellValue('A2', $statusText);

        $sheet->getStyle('A2')->applyFromArray([
            'alignment' => [
                'horizontal' => 'center'
            ]
        ]);

        // HEADER TABEL
        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'alignment' => [
                'horizontal' => 'center'
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin'
                ]
            ]
        ]);

        // BORDER DATA
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A4:F'.$lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin'
                ]
            ]
        ]);

        return [];
    }

}
