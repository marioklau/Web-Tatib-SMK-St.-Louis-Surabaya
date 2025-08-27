<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kelas</th>
            <th>Nama Siswa</th>
            <th>NIS</th>
            <th>Tanggal</th>
            <th>Pelanggaran</th>
            <th>Sanksi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($siswa as $item)
            @foreach ($item->pelanggaran as $pelanggaran)
                <tr>
                    @if ($loop->first)
                        <td rowspan="{{ $item->pelanggaran->count() }}">{{ $no++ }}</td>
                        <td rowspan="{{ $item->pelanggaran->count() }}">{{ $item->kelas->nama_kelas }}</td>
                        <td rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nama_siswa }}</td>
                        <td rowspan="{{ $item->pelanggaran->count() }}">{{ $item->nis }}</td>
                    @endif
                    <td>{{ $pelanggaran->created_at->format('d-m-Y') }}</td>
                    <td>{{ $pelanggaran->jenis->bentuk_pelanggaran ?? '-' }}</td>
                    <td>
                        {{ is_array($pelanggaran->sanksi->keputusan_tindakan ?? null) 
                            ? implode(', ', $pelanggaran->sanksi->keputusan_tindakan) 
                            : ($pelanggaran->sanksi->keputusan_tindakan ?? '-') }}
                    </td>
                    <td>{{ $pelanggaran->status }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>