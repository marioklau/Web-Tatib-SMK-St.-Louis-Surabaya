<table border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse; width:100%;">
    <thead>
        <tr>
            <th colspan="13" style="background-color:#cfe2f3; text-align:left; font-size:16px; border:1px solid #000;">
                DATA PELANGGARAN TATA TERTIB PESERTA DIDIK 
            </th>
        </tr>
        <tr>
            <th colspan="13" style="background-color:#cfe2f3; text-align:left; font-size:16px; border:1px solid #000;">
                @if($kelasName) {{ $kelasName }} @endif
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="border:1px solid #000; text-align:center; vertical-align:middle;">No</th>
            <th rowspan="2" style="border:1px solid #000; text-align:center; vertical-align:middle;">Nama Siswa</th>
            <th colspan="4" style="border:1px solid #000; text-align:center; vertical-align:middle;">Pelanggaran Ringan (R)</th>
            <th rowspan="2" style="border:1px solid #000; text-align:center; vertical-align:middle;">Terlambat (T)</th>
            <th rowspan="2" style="border:1px solid #000; text-align:center; vertical-align:middle;">Berat (B)</th>
            <th rowspan="2" style="border:1px solid #000; text-align:center; vertical-align:middle;">Sangat Berat (SB)</th>
            <th colspan="4" style="border:1px solid #000; text-align:center; vertical-align:middle;">Jumlah</th>
        </tr>
        <tr>
            <th style="border:1px solid #000;">Rambut</th>
            <th style="border:1px solid #000;">Seragam</th>
            <th style="border:1px solid #000;">Sepatu</th>
            <th style="border:1px solid #000;">Petisi</th>
            <th style="border:1px solid #000;">R</th>
            <th style="border:1px solid #000;">T</th>
            <th style="border:1px solid #000;">B</th>
            <th style="border:1px solid #000;">SB</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $r)
            <tr>
                <td style="border:1px solid #000;">{{ $r['no'] }}</td>
                <td style="border:1px solid #000; text-align:left;">{{ $r['nama'] }}</td>
                <td style="border:1px solid #000;">{{ $r['rambut'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['seragam'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['sepatu'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['petisi'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['terlambat'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['berat'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['sangat'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['totalR'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['totalT'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['totalB'] ?: '' }}</td>
                <td style="border:1px solid #000;">{{ $r['totalSB'] ?: '' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="background-color:#f3df29; border:1px solid #000;"><b>Jumlah Pelanggaran</b></td>
            <td style="border:1px solid #000;">{{ $rows->sum('rambut') ?: '' }}</td>
            <td style="border:1px solid #000;">{{ $rows->sum('seragam') ?: '' }}</td>
            <td style="border:1px solid #000;">{{ $rows->sum('sepatu') ?: '' }}</td>
            <td style="border:1px solid #000;">{{ $rows->sum('petisi') ?: '' }}</td>
            <td style="border:1px solid #000;">{{ $rows->sum('terlambat') ?: '' }}</td>
            <td style="border:1px solid #000;">{{ $rows->sum('berat') ?: '' }}</td>
            <td style="border:1px solid #000;">{{ $rows->sum('sangat') ?: '' }}</td>
            <td style="background-color:#f3df29; border:1px solid #000;">{{ $rows->sum('totalR') ?: '' }}</td>
            <td style="background-color:#f3df29; border:1px solid #000;">{{ $rows->sum('totalT') ?: '' }}</td>
            <td style="background-color:#f3df29; border:1px solid #000;">{{ $rows->sum('totalB') ?: '' }}</td>
            <td style="background-color:#f3df29; border:1px solid #000;">{{ $rows->sum('totalSB') ?: '' }}</td>
        </tr>
    </tfoot>
</table>