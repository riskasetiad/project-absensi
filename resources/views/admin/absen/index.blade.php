@extends('layouts.admin.app')

@section('content')
    <div class="container mx-auto p-4">
        @if (Auth::user()->hasRole('admin'))
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-2xl font-bold text-gray-800">Data Absen Karyawan</p>
                <div class="mt-4">
                    <table class="min-w-full bg-white mt-6 border rounded-lg shadow-lg">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Masuk</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Keluar</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Kerja</th>
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
                                        {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">
                                        @if ($absen->jam_keluar)
                                            {{ \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i:s') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $absen->jam_kerja ?? '-' }} Jam</td>
                                    <td class="px-4 py-2 border-b">{{ ucfirst($absen->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($absens->isEmpty())
                    <div class="mt-4 text-center text-gray-500">
                        <p>No absences found.</p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form method="POST" action="{{ route('absen.store') }}">
                    @csrf
                    <input type="hidden" name="status"
                        value="{{ $check_absen === 'checkout' ? '' : ($check_absen ? 'checkout' : 'checkin') }}">

                    @if ($check_absen && $check_absen->jam_keluar)
                        <button type="button"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-md cursor-not-allowed" disabled>
                            Sudah Checkout
                        </button>
                    @elseif ($check_absen)
                        <button type="submit"
                            class="bg-white border border-red-500 text-red-500 px-4 py-2 rounded-md shadow-md hover:bg-red-600 hover:text-white">
                            Checkout
                        </button>
                    @else
                        <button type="submit"
                            class="bg-white border border-green-500 text-green-500 px-4 py-2 rounded-md shadow-md hover:bg-green-600 hover:text-white">
                            Checkin
                        </button>
                    @endif
                </form>
                <p class="text-2xl mt-2 font-bold text-gray-800">Data Absen {{ Auth::user()->name }}</p>
                <div class="mt-4">
                    <table class="min-w-full bg-white mt-6 border rounded-lg shadow-lg">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nama</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Masuk</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Keluar</th>
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Jam Kerja</th>
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
                                        {{ $absen->jam_masuk ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $absen->jam_keluar ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{ $absen->jam_kerja ?? '-' }} Jam</td>
                                    <td class="px-4 py-2 border-b">{{ ucfirst($absen->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($absens->isEmpty())
                    <div class="mt-4 text-center text-gray-500">
                        <p>No absences found.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
