<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ProdukTerlarisExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{

    protected $totalTerjual = 0;

    public function collection()
    {

        $data = Transaksi::select(
                'produk_id',
                DB::raw('SUM(qty) as total_qty')
            )
            ->with('produk')
            ->groupBy('produk_id')
            ->orderByDesc('total_qty')
            ->get();

        $this->totalTerjual = $data->sum('total_qty');

        $no = 1;

        return $data->map(function($p) use (&$no){

            return [

                $no++,
                $p->produk->nama ?? '-',
                $p->total_qty

            ];

        });

    }


    public function headings(): array
    {
        return [

            'Ranking',
            'Nama Produk',
            'Total Terjual'

        ];
    }


    public function styles(Worksheet $sheet)
    {

        return [

            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ]
            ],

        ];

    }


    public function registerEvents(): array
    {

        return [

            AfterSheet::class => function(AfterSheet $event){

                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1,3);

                // Judul
                $sheet->setCellValue('A1','LAPORAN PRODUK TERLARIS');
                $sheet->mergeCells('A1:C1');

                $sheet->setCellValue('A2','Tanggal Export : '.Carbon::now()->format('d M Y'));
                $sheet->mergeCells('A2:C2');

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // Header style
                $sheet->getStyle('A4:C4')->getFont()->setBold(true);

                $sheet->getStyle('A4:C4')->getFill()
                    ->setFillType('solid')
                    ->getStartColor()
                    ->setARGB('D9E1F2');

                // Border tabel
                $sheet->getStyle('A4:C'.$sheet->getHighestRow())
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle('thin');

                // Total
                $lastRow = $sheet->getHighestRow() + 2;

                $sheet->setCellValue('B'.$lastRow,'TOTAL TERJUAL');

                $sheet->setCellValue('C'.$lastRow,$this->totalTerjual);

                $sheet->getStyle('B'.$lastRow.':C'.$lastRow)
                    ->getFont()
                    ->setBold(true);

            }

        ];

    }

}
