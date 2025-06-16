@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 sm:px-8 mt-4">
    <h1 class="text-3xl font-semibold text-gray-800 mb-4">Selamat Datang Bapa/Ibu...</h1>

    <!-- Konten Card -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <!-- Card Total Siswa -->
        <div class="flex items-center bg-white border rounded-lg shadow overflow-hidden">
            <div class="p-4 bg-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
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
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
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

    <!-- Chart 1: Pelanggaran per Bulan -->
    <div class="bg-white p-6 m-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Grafik Pelanggaran per Bulan</h2>
        <canvas id="pelanggaranChart" width="150" height="50"></canvas>
    </div>

    <!-- Chart 2: Top 10 Siswa -->
    <div class="bg-white p-6 m-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Top 10 Siswa Pelanggaran Terbanyak</h2>
        <canvas id="topSiswaChart" width="150" height="50"></canvas>
    </div>

    <!-- Chart 3: Top 10 Kelas + Jurusan -->
    <div class="bg-white p-6 m-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Top 10 Kelas & Jurusan Pelanggaran Terbanyak</h2>
        <canvas id="kelasChart" width="150" height="50"></canvas>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart 1: Pelanggaran per Bulan
    const ctx = document.getElementById('pelanggaranChart').getContext('2d');
    const pelanggaranChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei'],
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: [92, 127, 214, 110, 42],
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Chart 2: Top 10 Siswa Pelanggaran Terbanyak
    const ctx2 = document.getElementById('topSiswaChart').getContext('2d');
    const topSiswaChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: [
                'Siswa A', 'Siswa B', 'Siswa C', 'Siswa D', 'Siswa E',
                'Siswa F', 'Siswa G', 'Siswa H', 'Siswa I', 'Siswa J'
            ],
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: [12, 10, 9, 8, 8, 7, 6, 5, 4, 3],
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart 3: Top 10 Kelas & Jurusan Pelanggaran Terbanyak
    const ctx3 = document.getElementById('kelasChart').getContext('2d');
    const kelasChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: [
                'X RPL', 'XI RPL', 'XII RPL',
                'X TKJ', 'XI TKJ', 'XII TKJ',
                'X DKV', 'XI DKV', 'XII DKV', 'X MM'
            ],
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: [50, 46, 43, 40, 35, 32, 30, 28, 25, 19],
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
