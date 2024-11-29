@extends('layouts.admin.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="container mx-auto py-2">
                <p class="text-2xl font-bold text-gray-800">Rekap Absen</p>
                <form method="GET" action="{{ route('laporan.index') }}" class="mt-4 mb-4">
                    <label for="tanggal_mulai" class="mr-2">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                        class="border rounded p-2">
                    <label for="tanggal_selesai" class="mx-2">Sampai Tanggal</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                        value="{{ request('tanggal_selesai') }}" class="border rounded p-2">
                    @if (Auth::user()->hasRole('admin'))
                        <div class="mt-2">
                            <label for="user_id">Pilih Karyawan</label>
                            <select name="user_id" class="p-2 border rounded">
                                <option value="">Pilih Karyawan</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $user->id == request('user_id') ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                    @endif
                <button type="submit" class="bg-white border border-blue-500 text-blue-500 px-4 py-2 rounded-md shadow-md hover:bg-blue-600 hover:text-white">Filter</button>
            </div>
            </form>
            <div class="mb-4">
                <a href="{{ route('laporan.exportExcel', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai]) }}"
                    class="bg-white border border-green-500 text-green-500 px-3 py-2 rounded-md shadow-md hover:bg-green-600 hover:text-white mr-2">
                    Export Excel
                </a>
                <a href="{{ route('laporan.exportPdf', ['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'user_id' => $userId]) }}"
                    class="bg-white border border-red-500 text-red-500 px-3 py-2 rounded-md shadow-md hover:bg-red-600 hover:text-white">
                    Export PDF
                </a>
            </div>

            @if ($absens->isEmpty())
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mt-6" role="alert">
                    <p class="font-bold">Tidak Ada Data</p>
                    <p>
                        @if ($tanggalMulai && $tanggalSelesai)
                            Tidak ada data absen dari tanggal
                            <strong>{{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }}</strong>
                            sampai
                            <strong>{{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}</strong> .
                        @else
                            Belum ada data absen yang tersedia.
                        @endif
                    </p>
                </div>
            @else
                <table class="min-w-full bg-white mt-6 border rounded-lg shadow-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Masuk</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Keluar</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Kerja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absens as $absen)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ $absen->user->name }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    @if ($absen->jam_keluar)
                                        {{ \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i:s') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-600">{{ ucfirst($absen->status) }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    {{$absen->jam_kerja }} Jam
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    </div>
@endsection
