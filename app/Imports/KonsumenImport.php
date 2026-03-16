<?php

namespace App\Imports;

use App\Models\Konsumen;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KonsumenImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {

        $status = strtolower($row['status'] ?? 'prospek');

        // NORMALISASI STATUS
        if ($status == 'deal') {

            $status = 'Prospek'; // belum transaksi

        } elseif ($status == 'follow up') {

            $status = 'Prospek';

        } elseif ($status == 'tidak tertarik') {

            $status = 'Tidak Tertarik';

        } else {

            $status = 'Prospek';

        }

        return new Konsumen([

            'nama' => $row['nama'] ?? '',
            'no_hp' => $row['no_hp'] ?? '',
            'email' => $row['email'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'sumber_lead' => $row['sumber_lead'] ?? 'Import Excel',
            'status' => $status,
            'user_id' => Auth::id(),

        ]);

    }

}
