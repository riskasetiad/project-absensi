<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\absen;
use App\Models\karyawan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display the user index page.
     */
    public function home(Request $request)
    {
        $tanggal = $request->input('tanggal') ?: Carbon::today('Asia/Jakarta')->format('Y-m-d');

        $jumlahKaryawan = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->count();

        $jumlahHadir = absen::whereDate('tanggal', $tanggal)
            ->whereIn('status', ['Hadir', 'Terlambat'])
            ->distinct('user_id')
            ->count('user_id');

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $absens = absen::whereDate('tanggal', $tanggal)->with('user')->get();
        } else {
            $absens = $user->absens()->whereDate('tanggal', $tanggal)->get();
        }

        return view('home', compact('user', 'absens', 'jumlahKaryawan', 'jumlahHadir', 'tanggal'));
    }

    public function index()
    {
        $users = User::role('user')->with('karyawan')->get();
        return view('admin.user.index', compact('users'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',

            'nip' => 'required|string|max:20',
            'telpon' => 'required|string|max:15',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:50',
            'tgl_lahir' => 'required|date',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string',
            'cover' => 'required|mimes:jpg,jpeg,png|max:65535',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('user');

        $karyawan = new Karyawan([
            'nip' => $request->nip,
            'telpon' => $request->telpon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'user_id' => $user->id,
        ]);

        if ($request->hasFile('cover')) {
            $img = $request->file('cover');
            $name = rand(1000, 9999) . $img->getClientOriginalName();
            $img->move('images/cover', $name);
            $karyawan->cover = $name;
        }

        $karyawan->save();
        Alert::toast('Berhasil ditambahkan!', 'success');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('karyawan')->findOrFail($id);

        return view('admin.user.show', compact('user'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::with('karyawan')->findOrFail($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Email harus unik, kecuali untuk dirinya sendiri
            'password' => 'nullable|string|min:8',

            'nip' => 'required|string|max:20',
            'telpon' => 'required|string|max:15',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:50',
            'tgl_lahir' => 'required|date',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string',
            'cover' => 'nullable|mimes:jpg,jpeg,png|max:65535',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $karyawan = Karyawan::where('user_id', $id)->firstOrFail();
        $karyawan->nip = $request->nip;
        $karyawan->telpon = $request->telpon;
        $karyawan->jenis_kelamin = $request->jenis_kelamin;
        $karyawan->tempat_lahir = $request->tempat_lahir;
        $karyawan->tgl_lahir = $request->tgl_lahir;
        $karyawan->agama = $request->agama;
        $karyawan->alamat = $request->alamat;

        if ($request->hasFile('cover')) {
            $karyawan->delete();
            $img = $request->file('cover');
            $name = rand(1000, 9999) . $img->getClientOriginalName();
            $img->move('images/cover', $name);
            $karyawan->cover = $name;
        }

        $karyawan->save();
        Alert::toast('Berhasil diubah!', 'success');

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $karyawan = karyawan::where('user_id', $id)->first();

        if ($karyawan) {
            $karyawan->delete();
        }

        $user->delete();
        Alert::toast('Berhasil dihapus!', 'success');
        return redirect()->route('user.index');
    }
}
