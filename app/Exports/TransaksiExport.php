<?php

namespace App\Exports;

use App\Models\Transaksi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class TransaksiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    protected $search;
    protected $produkId;
    protected $start;
    protected $end;
    protected $totalOmzet = 0;

    public function __construct($search = null, $produkId = null, $start = null, $end = null)
    {
        $this->search = $search;
        $this->produkId = $produkId;
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        $query = Transaksi::with(['konsumen','produk']);

        // Filter search nama konsumen / produk
        if($this->search){
            $query->where(function($q){
                $q->whereHas('konsumen', function($k){
                    $k->where('nama','like','%'.$this->search.'%');
                })
                ->orWhereHas('produk', function($p){
                    $p->where('nama','like','%'.$this->search.'%');
                });
            });
        }

        // Filter produk
        if($this->produkId){
            $query->where('produk_id', $this->produkId);
        }

        // Filter tanggal range
        if($this->start && $this->end){
            $query->whereBetween('tanggal_transaksi', [$this->start, $this->end]);
        } elseif($this->start){
            $query->whereDate('tanggal_transaksi', '>=', $this->start);
        } elseif($this->end){
            $query->whereDate('tanggal_transaksi', '<=', $this->end);
        }

        $data = $query->get();

        $this->totalOmzet = $data->sum('total');

        return $data->map(function($t){
            return [
                $t->konsumen->nama ?? '-',
                $t->konsumen->no_hp ?? '-',
                $t->produk->nama ?? '-',
                $t->qty,
                'Rp '.number_format($t->harga_satuan,0,',','.'),
                'Rp '.number_format($t->total,0,',','.'),
                Carbon::parse($t->tanggal_transaksi)->format('d-m-Y')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Konsumen',
            'No HP',
            'Produk',
            'Qty',
            'Harga Satuan',
            'Total',
            'Tanggal Transaksi'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true,'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1,3);

                // Judul
                $sheet->setCellValue('A1','LAPORAN TRANSAKSI PENJUALAN');
                $sheet->mergeCells('A1:G1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                $sheet->setCellValue('A2','Tanggal Export : '.Carbon::now()->format('d M Y'));
                $sheet->mergeCells('A2:G2');

                // Header style
                $sheet->getStyle('A4:G4')->getFont()->setBold(true);
                $sheet->getStyle('A4:G4')->getFill()
                    ->setFillType('solid')
                    ->getStartColor()->setARGB('D9E1F2');

                // Border table
                $sheet->getStyle('A4:G'.$sheet->getHighestRow())
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle('thin');

                // Total omzet
                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->setCellValue('E'.$lastRow,'TOTAL OMZET');
                $sheet->setCellValue('F'.$lastRow,'Rp '.number_format($this->totalOmzet,0,',','.'));
                $sheet->getStyle('E'.$lastRow.':F'.$lastRow)->getFont()->setBold(true);
            }
        ];
    }
}
