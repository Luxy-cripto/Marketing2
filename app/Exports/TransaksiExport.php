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
        $query = Transaksi::with(['konsumen', 'details.produk']);

        // 🔍 SEARCH
        if ($this->search) {
            $query->whereHas('konsumen', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('details.produk', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'));
        }

        // 📦 FILTER PRODUK
        if ($this->produkId) {
            $query->whereHas('details', fn($q) => $q->where('produk_id', $this->produkId));
        }

        // 📅 FILTER TANGGAL
        if ($this->start && $this->end) {
            $query->whereBetween('tanggal_transaksi', [
                $this->start . ' 00:00:00',
                $this->end . ' 23:59:59'
            ]);
        } elseif ($this->start) {
            $query->whereDate('tanggal_transaksi', $this->start);
        } elseif ($this->end) {
            $query->whereDate('tanggal_transaksi', $this->end);
        }

        $data = $query->get();

        // 💰 HITUNG TOTAL OMZET (hanya Lunas)
        $this->totalOmzet = $data->filter(fn($t) => strtolower($t->status) === 'lunas')
                                  ->sum(fn($t) => $t->details->sum('subtotal'));

        // 🔄 Mapping data
        return $data->map(function ($t) {
            $produkNama = $t->details->map(fn($d) => $d->produk->nama ?? '-')->join(', ');
            $totalQty = $t->details->sum('qty');
            $totalOmzet = $t->details->sum('subtotal');

            return [
                $t->konsumen->nama ?? '-',
                $t->konsumen->no_hp ?? '-',
                $produkNama,
                $totalQty,
                '-', // Harga satuan bisa diabaikan atau ditambahkan logika per produk
                'Rp ' . number_format($totalOmzet, 0, ',', '.'),
                $t->status ?? '-',
                $t->tanggal_transaksi
                    ? Carbon::parse($t->tanggal_transaksi)->format('d-m-Y')
                    : '-'
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
            'Status',
            'Tanggal Transaksi'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            4 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Tambah 3 baris atas untuk judul
                $sheet->insertNewRowBefore(1, 3);

                // JUDUL
                $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI PENJUALAN');
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // TANGGAL EXPORT
                $sheet->setCellValue('A2', 'Tanggal Export : ' . Carbon::now()->format('d M Y'));
                $sheet->mergeCells('A2:H2');

                // STYLE HEADER
                $sheet->getStyle('A4:H4')->getFont()->setBold(true);
                $sheet->getStyle('A4:H4')->getFill()
                      ->setFillType('solid')
                      ->getStartColor()->setARGB('D9E1F2');

                // BORDER
                $sheet->getStyle('A4:H' . $sheet->getHighestRow())
                      ->getBorders()
                      ->getAllBorders()
                      ->setBorderStyle('thin');

                // TOTAL OMZET
                $lastRow = $sheet->getHighestRow() + 2;
                $sheet->setCellValue('F' . $lastRow, 'TOTAL OMZET');
                $sheet->setCellValue('G' . $lastRow, 'Rp ' . number_format($this->totalOmzet, 0, ',', '.'));
                $sheet->getStyle('F' . $lastRow . ':G' . $lastRow)->getFont()->setBold(true);
            }
        ];
    }
}
