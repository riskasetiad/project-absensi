<?php

namespace App\Exports;

use App\Models\absen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class AbsenExport implements FromCollection, WithHeadings
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        // Filter absensi berdasarkan tanggal
        return absen::whereDate('tanggal', $this->tanggal)
            ->select('user_id', 'tanggal', 'jam_masuk', 'jam_keluar', 'status', 'jam_kerja')
            ->get();
    }

    public function headings(): array
    {
        return [
            'User ID',
            'Tanggal',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Jam Kerja',
        ];
    }
}
