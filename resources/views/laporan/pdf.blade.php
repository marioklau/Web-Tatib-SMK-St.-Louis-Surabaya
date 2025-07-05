<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
        h2, h3 { text-align: center; }
        .meta { margin-bottom: 20px; }
        .meta-info { padding: 5px 0; }
    </style>
</head>
<body>
    <h2>LAPORAN PELANGGARAN SISWA</h2>
    @if ($kelas)
        <h3>Kelas: {{ $kelas->nama_kelas }}</h3>
    @endif

    <div class="meta">
        <div class="meta-info"><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y') }}</div>
        <div class="meta-info"><strong>Dicetak oleh:</strong> {{ Auth::user()->nama }}</div>
        @if ($kelas)
            <div class="meta-info"><strong>Wali Kelas:</strong> {{ $kelas->wali_kelas }}</div>
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
                <th>Tanggal</th>
                <th>Pelanggaran</th>
                <th>Sanksi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $nomor = 1; @endphp
            @foreach ($siswa as $item)
                @foreach ($item->pelanggaran as $pelanggaran)
                    <tr>
                        {{-- Data siswa hanya ditampilkan sekali per siswa menggunakan rowspan --}}
                        @if ($loop->first)
                            <td rowspan="{{ $item->pelanggaran->count() }}">{{ $nomor++ }}</td>
                            @if (!$kelas)
                                <td rowspan="{{ $item->pelanggaran->count() }}">{{ $item->kelas->nama_kelas }}</td>
                            @endif
                            <td rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nama_siswa }}</td>
                            <td rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nis }}</td>
                        @endif

                        {{-- Data spesifik per pelanggaran --}}
                        <td>{{ $pelanggaran->created_at->format('d/m/Y') }}</td>
                        <td>{{ $pelanggaran->jenis->bentuk_pelanggaran ?? '-' }}</td>
                        <td>{{ is_array($pelanggaran->sanksi->keputusan_tindakan) ? implode(', ', $pelanggaran->sanksi->keputusan_tindakan) : ($pelanggaran->sanksi->keputusan_tindakan ?? '-') }}</td>
                        <td>{{ $pelanggaran->status }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>