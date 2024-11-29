@extends('layouts.admin.app')
@section('content')
    <div class="container mx-auto p-4">
        <div class="text-gray-800">
            @if (auth()->user()->hasRole('admin'))
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-2xl font-bold mb-4">Selamat Datang di Dashboard!</p>
                    <form action="{{ route('home') }}" method="GET">
                        <label for="tanggal" class="mr-2">Pilih Tanggal:</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $tanggal) }}"
                            class="border px-2 py-1 rounded">
                        <button type="submit"
                            class="bg-white border border-blue-500 text-blue-500 px-4 py-1 rounded">Filter</button>
                    </form>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="bg-white border border-blue-500 text-black p-6 rounded-lg shadow-md">
                            <p class="text-lg">Jumlah Karyawan</p>
                            <p class="text-2xl font-bold">{{ $jumlahKaryawan }}</p>
                        </div>

                        <div class="bg-white border border-blue-500 text-black p-6 rounded-lg shadow-md">
                            <p class="text-lg">Jumlah Hadir pada {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</p>
                            <p class="text-2xl font-bold">{{ $jumlahHadir }}</p>
                        </div>
                    </div>

                    <div class="bg-white border border-blue-500 p-6 rounded-lg shadow-md mt-6">
                        <p class="text-2xl font-bold text-gray-800">Data Absen Hari Ini</p>
                        @if ($absens->isEmpty())
                            <p class="mt-4 text-gray-600">Belum ada data absensi hari ini.</p>
                        @else
                            <div class="mt-4">
                                <table class="min-w-full bg-white border rounded-lg shadow-lg">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Masuk</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Keluar
                                            </th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($absens as $absen)
                                            <tr>
                                                <td class="px-4 py-2 border-b">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-2 border-b">{{ $absen->user->name }}</td>
                                                <td class="px-4 py-2 border-b">
                                                    {{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}
                                                </td>
                                                <td class="px-4 py-2 border-b">
                                                    {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') ?? '-' }}
                                                </td>
                                                <td class="px-4 py-2 border-b">
                                                    @if ($absen->jam_keluar)
                                                        {{ \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i:s') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border-b">{{ ucfirst($absen->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-2xl font-bold pl-2">Selamat Datang, {{ Auth::user()->name }}!</p>
                <div class="flex items-center space-x-4 mt-2 pl-10">
                    <div>
                        @if ($user->karyawan && $user->karyawan->cover)
                            <img src="{{ asset('images/cover/' . $user->karyawan->cover) }}" alt="Foto Profil"
                                class="w-40 h-40 object-cover rounded-full border-2 border-gray-300 shadow">
                        @else
                            <div
                                class="w-40 h-40 bg-gray-200 rounded-full flex items-center justify-center border-2 border-gray-300 shadow">
                                <span class="text-sm text-gray-500">No Photo</span>
                            </div>
                        @endif
                    </div>
                    <div class="border-l-2 border-gray-300 h-40"></div>
                    <div class="text-gray-800 grid grid-cols-1 md:grid-cols-2 gap-4 pl-10">
                        <div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Nama</p>
                                <p>{{ $user->name }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Email</p>
                                <p>{{ $user->email }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">NIP</p>
                                <p>{{ $user->karyawan ? $user->karyawan->nip : '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Telepon</p>
                                <p>{{ $user->karyawan ? $user->karyawan->telpon : '-' }}</p>
                            </div>
                        </div>

                        <div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Jenis Kelamin</p>
                                <p>{{ $user->karyawan ? $user->karyawan->jenis_kelamin : '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Tempat Tanggal Lahir</p>
                                <p>{{ $user->karyawan ? $user->karyawan->tempat_lahir : '-' }},
                                    {{ $user->karyawan ? \Carbon\Carbon::parse($user->karyawan->tgl_lahir)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Agama</p>
                                <p>{{ $user->karyawan ? $user->karyawan->agama : '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="text-lg font-semibold text-gray-700">Alamat</p>
                                <p>{{ $user->karyawan ? $user->karyawan->alamat : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <p class="text-2xl font-bold text-gray-800">Data Absen Hari Ini</p>
                @if ($absens->isEmpty())
                    <p class="mt-4 text-gray-600">Belum ada data absensi hari ini.</p>
                @else
                    <div class="mt-4">
                        <table class="min-w-full bg-white border rounded-lg shadow-lg">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Masuk</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Keluar</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absens as $absen)
                                    <tr>
                                        <td class="px-4 py-2 border-b">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border-b">
                                            {{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}
                                        </td>
                                        <td class="px-4 py-2 border-b">
                                            {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 border-b">
                                            @if ($absen->jam_keluar)
                                                {{ \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i:s') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border-b">{{ ucfirst($absen->status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            @endif
        </div>
    @endsection
