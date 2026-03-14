<?php

namespace App\Exports;

use App\Models\Konsumen;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KonsumenExport implements FromCollection, WithHeadings
{

        protected $status;

        public function __construct($status = null)
        {
                $this->status = $status;
        }

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

                /* FILTER STATUS */

                if (!empty($this->status)) {

                        $query->where('status', $this->status);

                }

                /* ROLE MARKETING */

                $user = Auth::user();

                if ($user && $user->role === 'marketing') {

                        $query->where('user_id', $user->id);

                }

                return $query->get();

        }

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

}
