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
                        d="M4 7v极速赛车开奖结果记录|极速赛车开奖官网直播10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
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
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold text-gray-700">Top 10 Siswa Pelanggaran Terbanyak</h2>
            <div class="filter-buttons" data-chart="topSiswaChart">
                <button class="px-2 py-1 text-xs bg-blue-500 text-white rounded active" data-filter="all">Semua</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="daily">Harian</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="weekly">Mingguan</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="monthly">Bulanan</button>
            </div>
        </div>
        <canvas id="topSiswaChart"></canvas>
    </div>

    <!-- Top 10 Kelas & Jurusan Pelanggaran Terbanyak -->
    <div class="bg-white p-4 m-4 rounded-lg shadow flex-1 max-w-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold text-gray-700">Top 10 Kelas Pelanggaran Terbanyak</h2>
            <div class="filter-buttons" data-chart="kelasChart">
                <button class="px-2 py-1 text-xs bg-blue-500 text-white rounded active" data-filter="all">Semua</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="daily">Harian</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="weekly">Mingguan</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="monthly">Bulanan</button>
            </div>
        </div>
        <canvas id="kelasChart"></canvas>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-center gap-4 mx-4">
    <div class="bg-white p-4 m-4 rounded-lg shadow flex-1 max-w-xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold text-gray-700">Top 10 Jenis Pelanggaran Terbanyak</h2>
            <div class="filter-buttons" data-chart="jenisChart">
                <button class="px-2 py-1 text-xs bg-blue-500 text-white rounded active" data-filter="all">Semua</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="daily">Harian</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="weekly">Mingguan</button>
                <button class="px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded" data-filter="monthly">Bulanan</button>
            </div>
        </div>
        <canvas id="jenisChart"></canvas>
    </div>
    
    <div class="bg-white p-4 m-4 rounded-lg shadow flex-1 max-w-xl">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Grafik Pelanggaran per Bulan</h2>
        <canvas id="pelanggaranChart"></canvas>
    </div>
</div>

<!-- Grafik Baru: Perbandingan Jenis Pelanggaran per Bulan -->
<div class="flex justify-center mx-4">
    <div class="bg-white p-4 m-4 rounded-lg shadow w-full max-w-4xl">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-semibold text-gray-700">Perbandingan Jenis Pelanggaran per Bulan</h2>
            <div class="flex items-center">
                <label for="jenisPelanggaranSelect" class="mr-2 text-sm text-gray-700">Pilih Jenis Pelanggaran:</label>
                <select id="jenisPelanggaranSelect" class="px-3 py-1 border rounded-md text-sm">
                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                    @foreach($allJenisPelanggaran as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->bentuk_pelanggaran }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <canvas id="perbandinganChart"></canvas>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pelanggaranPerBulan = @json($pelanggaranPerBulanChart);
    const topSiswa = @json($topSiswa);
    const topKelas = @json($topKelas);
    const topJenis = @json($topJenisPelanggaran);
    
    // Variabel global untuk menyimpan instance chart
    let charts = {};

    function generateColorArray(length, primaryColor, secondaryColor, primaryBorder, secondaryBorder) {
        return {
            background: Array.from({ length }, (_, i) =>
            i === 0 ? primaryColor : secondaryColor
            ),
            border: Array.from({ length }, (_, i) =>
            i === 0 ? primaryBorder : secondaryBorder
            )
        };
    }

    // Inisialisasi semua chart
    function initCharts() {
        // Chart 1: Pelanggaran per Bulan
        charts.pelanggaranChart = new Chart(document.getElementById('pelanggaranChart'), {
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
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                }
            }
        });

        // Chart 2: Top Siswa
        const siswaColors = generateColorArray(topSiswa.data.length, 'rgba(255,99,132,0.8)', 'rgba(54,162,235,0.6)', 'rgba(255,99,132,1)', 'rgba(54,162,235,1)');
        charts.topSiswaChart = new Chart(document.getElementById('topSiswaChart'), {
            type: 'bar',
            data: {
                labels: topSiswa.labels,
                datasets: [{ 
                    label: 'Jumlah Pelanggaran', 
                    data: topSiswa.data, 
                    backgroundColor: siswaColors.background, 
                    borderColor: siswaColors.border, 
                    borderWidth:1 
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: { 
                        beginAtZero: true, 
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // Chart 4: Top Jenis Pelanggaran
        const jenisColors = generateColorArray(topJenis.data.length, 'rgba(255,99,132,0.8)', 'rgba(100,149,237,0.6)', 'rgba(255,99,132,1)', 'rgba(100,149,237,1)');
        charts.jenisChart = new Chart(document.getElementById('jenisChart'), {
            type: 'bar',
            data: {
                labels: topJenis.labels,
                datasets: [{ 
                    label: 'Jumlah Pelanggaran', 
                    data: topJenis.data, 
                    backgroundColor: jenisColors.background, 
                    borderColor: jenisColors.border, 
                    borderWidth:1 
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });

        // Chart 3: Top Kelas
        const kelasColors = generateColorArray(topKelas.data.length, 'rgba(255,99,132,0.8)', 'rgba(100,149,237,0.6)', 'rgba(255,99,132,1)', 'rgba(100,149,237,1)');
        charts.kelasChart = new Chart(document.getElementById('kelasChart'), {
            type: 'bar',
            data: {
                labels: topKelas.labels,
                datasets: [{ 
                    label: 'Jumlah Pelanggaran', 
                    data: topKelas.data, 
                    backgroundColor: kelasColors.background, 
                    borderColor: kelasColors.border, 
                    borderWidth:1 
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: { 
                        beginAtZero: true, 
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        }
                    } 
                }
            }
        });

        // Chart 5: Perbandingan Jenis Pelanggaran (Kosong awal)
        charts.perbandinganChart = new Chart(document.getElementById('perbandinganChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Pelanggaran',
                    data: [],
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
                }
            }
        });
    }

    // Fungsi untuk memperbarui data chart
    function updateChartData(chartId, filter) {
        // Kirim permintaan AJAX ke server
        fetch(`/dashboard/chart-data?chart=${chartId}&filter=${filter}`)
            .then(response => response.json())
            .then(data => {
                // Perbarui data chart berdasarkan ID
                if (charts[chartId]) {
                    charts[chartId].data.labels = data.labels;
                    charts[chartId].data.datasets[0].data = data.data;
                    charts[chartId].update();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Fungsi untuk memuat data perbandingan jenis pelanggaran
    function loadPerbandinganData(jenisId) {
        if (!jenisId) {
            // Kosongkan chart jika tidak ada jenis yang dipilih
            charts.perbandinganChart.data.datasets[0].data = Array(12).fill(0);
            charts.perbandinganChart.update();
            return;
        }

        fetch(`/dashboard/perbandingan-data?jenis_id=${jenisId}`)
            .then(response => response.json())
            .then(data => {
                charts.perbandinganChart.data.datasets[0].data = data;
                charts.perbandinganChart.update();
            })
            .catch(error => console.error('Error:', error));
    }

    // Event listener untuk tombol filter
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        
        // Tambahkan event listener untuk semua tombol filter
        document.querySelectorAll('.filter-buttons').forEach(buttonGroup => {
            const chartId = buttonGroup.getAttribute('data-chart');
            
            buttonGroup.querySelectorAll('button').forEach(button => {
                button.addEventListener('click', function() {
                    // Hapus kelas aktif dari semua tombol dalam grup
                    buttonGroup.querySelectorAll('button').forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-500', 'text-white');
                        btn.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    
                    // Tambahkan kelas aktif ke tombol yang diklik
                    this.classList.add('active', 'bg-blue-500', 'text-white');
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    
                    // Ambil nilai filter
                    const filter = this.getAttribute('data-filter');
                    
                    // Perbarui data chart
                    updateChartData(chartId, filter);
                });
            });
        });

        // Event listener untuk dropdown jenis pelanggaran
        document.getElementById('jenisPelanggaranSelect').addEventListener('change', function() {
            loadPerbandinganData(this.value);
        });
    });
</script>
@endsection