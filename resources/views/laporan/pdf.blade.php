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
    </style>
</head>
<body>
    <h2>Laporan Siswa Kelas {{ $kelas->nama_kelas }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Jenis Kelamin</th>
                <th>Jumlah Pelanggaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_siswa }}</td>
                    <td>{{ $item->jenis_kelamin }}</td>
                    <td>-</td> <!-- Nanti diganti dengan data pelanggaran -->
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
