@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 sm:px-8">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Selamat Datang Bapa/Ibu...</h1>

    <!-- Konten Card -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card Total Siswa -->
        <div class="flex items-center bg-white border rounded-lg shadow overflow-hidden">
            <div class="p-4 bg-green-600">
                <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="px-4 py-2 text-gray-800">
                <h3 class="text-sm font-medium">Total Siswa</h3>
                <p class="text-3xl font-bold">{{ $totalSiswa }}</p>
            </div>
        </div>

        <!-- Card Total Kelas -->
        <div class="flex items-center bg-white border rounded-lg shadow overflow-hidden">
            <div class="p-4 bg-yellow-600">
                <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                </svg>
            </div>
            <div class="px-4 py-2 text-gray-800">
                <h3 class="text-sm font-medium">Total Kelas</h3>
                <p class="text-3xl font-bold">{{ $totalKelas }}</p>
            </div>
        </div>

        <!-- Card Total Pelanggaran -->
        <div class="flex items-center bg-white border rounded-lg shadow overflow-hidden">
            <div class="p-4 bg-red-600">
                <svg class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
            </div>
            <div class="px-4 py-2 text-gray-800">
                <h3 class="text-sm font-medium">Total Pelanggaran</h3>
                <p class="text-3xl font-bold">{{ $totalPelanggaran }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
 <!-- Container untuk Top 10 Siswa & Top 10 Kelas (berdampingan) -->
<div class="flex flex-col md:flex-row justify-center gap-4 mx-4">
    <!-- Top 10 Siswa Pelanggaran Terbanyak -->
    <div class="bg-white p-4 m-4 rounded-lg shadow flex-1 max-w-xl">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Top 10 Siswa Pelanggaran Terbanyak</h2>
        <canvas id="topSiswaChart"></canvas>
    </div>

    <!-- Top 10 Kelas & Jurusan Pelanggaran Terbanyak -->
    <div class="bg-white p-4 m-4 rounded-lg shadow flex-1 max-w-xl">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Top 10 Kelas & Jurusan Pelanggaran Terbanyak</h2>
        <canvas id="kelasChart"></canvas>
    </div>
</div>
<div class="bg-white p-4 m-4 rounded-lg shadow max-w-4xl mx-auto">
    <h2 class="text-base font-semibold text-gray-700 mb-4">Grafik Pelanggaran per Bulan</h2>
    <canvas id="pelanggaranChart"></canvas>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pelanggaranPerBulan = @json($pelanggaranPerBulanChart);
    const topSiswa = @json($topSiswa);
    const topKelas = @json($topKelas);

    // Chart 1: Pelanggaran per Bulan
    new Chart(document.getElementById('pelanggaranChart'), {
        type: 'bar',
        data: {
            labels: pelanggaranPerBulan.labels,
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: pelanggaranPerBulan.data,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // Chart 2: Top Siswa
    new Chart(document.getElementById('topSiswaChart'), {
        type: 'bar',
        data: {
            labels: topSiswa.labels,
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: topSiswa.data,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    // Chart 3: Top Kelas
    new Chart(document.getElementById('kelasChart'), {
        type: 'bar',
        data: {
            labels: topKelas.labels,
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: topKelas.data,
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
</script>

@endsection
