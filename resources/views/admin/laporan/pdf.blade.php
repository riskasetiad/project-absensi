<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }

        p {
            font-size: 14px;
            color: #555;
        }

        hr {
            border: 1px solid #ddd;
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f8f8f8;
            font-weight: bold;
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
            color: #666;
        }

        .total-jam {
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>

    <header>
        <h1>Laporan Absensi</h1>
        <p>Periode:
            @if ($tanggalMulai && $tanggalSelesai)
                {{ \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') }} s/d
                {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y') }}
            @else
                Seluruh Data Absensi
            @endif
        </p>
    </header>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
                <th>Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalJamKerja = 0;
            @endphp
            @foreach ($absens as $absen)
                <tr>
                    <td>{{ $absen->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') }}</td>
                    <td>
                        @if ($absen->jam_keluar)
                            {{ \Carbon\Carbon::parse($absen->jam_keluar)->format('H:i:s') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ ucfirst($absen->status) }}</td>
                    <td>
                        @php
                            // Menghitung jam kerja
                            $jamMasuk = \Carbon\Carbon::parse($absen->jam_masuk);
                            $jamKeluar = $absen->jam_keluar ? \Carbon\Carbon::parse($absen->jam_keluar) : null;
                            $jamKerja = 0; // Default jika tidak ada jam keluar

                            if ($jamKeluar) {
                                $durasiMenit = $jamKeluar->diffInMinutes($jamMasuk);
                                if ($durasiMenit >= 30) {
                                    $jamKerjaMenit = round($durasiMenit / 30) * 30;
                                    $jamKerja = round($jamKerjaMenit / 60, 2);
                                }
                            }

                            // Menambahkan jam kerja ke total jam kerja
                            $totalJamKerja += $jamKerja;
                        @endphp
                        {{ $jamKerja }} Jam
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dibuat pada: {{ $waktuDibuat }}</p>
    </div>

</body>

</html>
