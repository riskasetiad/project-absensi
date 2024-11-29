<?php

namespace App\Http\Controllers;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\absen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $absens = absen::with('user')->get();
        } else {
            $absens = absen::where('user_id', auth()->user()->id)->with('user')->get();
        }
        $check_absen = absen::whereDate('tanggal', Carbon::now('Asia/Jakarta')->format('Y-m-d'))->where('user_id', auth()->user()->id)->first();

        return view('admin.absen.index', compact('absens', 'check_absen'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $check_absen = absen::whereDate('tanggal', Carbon::now('Asia/Jakarta')->format('Y-m-d'))
            ->where('user_id', auth()->user()->id)
            ->first();

        return view('absen.create', compact('check_absen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->status == 'checkin') {
            $currentTime = Carbon::now('Asia/Jakarta');
            $isLate = $currentTime->gt(Carbon::createFromTime(17, 0, 0, 'Asia/Jakarta'));

            $absen = new absen;
            $absen->user_id = auth()->user()->id;
            $absen->status = $isLate ? 'Terlambat' : 'Hadir';
            $absen->tanggal = $currentTime->format('Y-m-d');
            $absen->jam_masuk = $currentTime->format('H:i:s');
            $absen->save();

            Alert::toast('Check-in berhasil.', 'success');

            return redirect()->back();

        } else {
            $absen = absen::whereDate('tanggal', Carbon::now('Asia/Jakarta')->format('Y-m-d'))
                ->where('user_id', auth()->user()->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($absen) {
                $jamKeluar = Carbon::now('Asia/Jakarta');

                if ($absen->jam_masuk) {
                    $jamMasuk = Carbon::parse($absen->jam_masuk, 'Asia/Jakarta');

                    $durasiKerja = $jamMasuk->diffInHours($jamKeluar);
                    $absen->jam_keluar = $jamKeluar->format('H:i:s');
                    $absen->jam_kerja = round($durasiKerja);
                    $absen->save();

                    Alert::toast('Check-out berhasil.', 'success');

                    return redirect()->back();
                } else {
                    // Jika belum check-in
                    // Alert::toast('Anda belum melakukan check-in hari ini.', 'error');
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(absen $absen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(absen $absen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, absen $absen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(absen $absen)
    {
        //
    }
}
