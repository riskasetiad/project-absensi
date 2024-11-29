<?php

namespace App\Http\Controllers;

use App\Exports\AbsenExport;
use App\Http\Controllers\Controller;
use App\Models\absen;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{

    public function index(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        $userId = $request->get('user_id');

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->get();

        if (auth()->user()->hasRole('admin')) {
            $absens = Absen::when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                return $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
                ->get();
        } else {
            $absens = Absen::where('user_id', auth()->user()->id)
                ->when($tanggalMulai && $tanggalSelesai, function ($query) use ($tanggalMulai, $tanggalSelesai) {
                    return $query->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
                })
                ->get();
        }

        // Hitung total jam kerja
        $totalJamKerja = $absens->reduce(function ($carry, $absen) {
            $jamMasuk = Carbon::parse($absen->jam_masuk);
            $jamKeluar = Carbon::parse($absen->jam_keluar);

            $durasiMenit = $jamKeluar->diffInMinutes($jamMasuk);

            // Tentukan jam kerja berdasarkan durasi
            if ($durasiMenit < 30) {
                $jamKerja = 0;
            } else {
                $jamKerjaMenit = round($durasiMenit / 30) * 30;
                $jamKerja = round($jamKerjaMenit / 60, 2);
            }

            return $carry + $jamKerja;
        }, 0);

        return view('admin.laporan.index', compact('absens', 'tanggalMulai', 'tanggalSelesai', 'totalJamKerja', 'users', 'userId'));
    }

    public function exportExcel(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');

        if (auth()->user()->hasRole('admin')) {
            $absens = absen::whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->get();
        } else {
            $absens = absen::where('user_id', auth()->user()->id)
                ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->get();
        }

        return Excel::download(new AbsenExport($absens), 'absen_' . $tanggalMulai . '_to_' . $tanggalSelesai . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $tanggalMulai = $request->get('tanggal_mulai');
        $tanggalSelesai = $request->get('tanggal_selesai');
        $userId = $request->get('user_id');

        $absensQuery = Absen::query();

        if ($tanggalMulai && $tanggalSelesai) {
            $absensQuery = $absensQuery->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
        }

        if (auth()->user()->hasRole('admin') && $userId) {
            $absensQuery = $absensQuery->where('user_id', $userId);
        } elseif (!auth()->user()->hasRole('admin')) {
            $absensQuery = $absensQuery->where('user_id', auth()->user()->id);
        }

        $absens = $absensQuery->get();

        // Hitung total jam kerja
        $totalJamKerja = $absens->reduce(function ($carry, $absen) {
            $jamMasuk = Carbon::parse($absen->jam_masuk);
            $jamKeluar = Carbon::parse($absen->jam_keluar);

            $durasiMenit = $jamKeluar->diffInMinutes($jamMasuk);

            // Tentukan jam kerja berdasarkan durasi
            if ($durasiMenit < 30) {
                $jamKerja = 0;
            } else {
                $jamKerjaMenit = round($durasiMenit / 30) * 30;
                $jamKerja = round($jamKerjaMenit / 60, 2);
            }

            return $carry + $jamKerja;
        }, 0);

        $waktuDibuat = now('Asia/Jakarta')->format('d-m-Y H:i:s');

        // Generate PDF
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('absens', 'tanggalMulai', 'tanggalSelesai', 'waktuDibuat', 'totalJamKerja'))
            ->setOptions(['isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

        // Download PDF
        return $pdf->download('laporan_absen_' . now()->format('YmdHis') . '.pdf');
    }

}
