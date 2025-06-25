<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #eee; }
        .meta { margin-top: 10px; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2>Laporan Pelanggaran Siswa</h2>

    <!-- Informasi Tanggal dan User -->
    <div class="meta">
        <strong>Tanggal:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y') }} <br>
        <strong>Dicetak oleh:</strong> {{ Auth::user()->nama }} <br>

        @if ($kelas)
            <strong>Nama Kelas:</strong> {{ $kelas->kode_kelas }} <br>
            <strong>Wali Kelas:</strong> {{ $kelas->wali_kelas }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                @if (!$kelas)
                    <th>Kelas</th>
                @endif
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Jenis Pelanggaran</th>
                <th>Sanksi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @if (!$kelas)
                        <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                    @endif
                    <td>{{ $item->nama_siswa }}</td>
                    <td>{{ $item->nis }}</td>
                    <td>
                        @php
                            $jenisPelanggaran = $item->pelanggaran->pluck('jenis.bentuk_pelanggaran')->unique()->implode(', ');
                        @endphp
                        {{ $jenisPelanggaran ?: '-' }}
                    </td>
                    <td>
                        @php
                            $sanksi = $item->pelanggaran->pluck('sanksi.keputusan_tindakan')->unique()->implode(', ');
                        @endphp
                        {{ $sanksi ?: '-' }}
                    </td>
                    <td>
                        @php
                            $statuses = $item->pelanggaran->pluck('status')->unique();
                        @endphp
                        {{ $statuses->implode(', ') ?: '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
