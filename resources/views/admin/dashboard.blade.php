@extends('layout.app')

@section('title','Dashboard')

@section('content')

<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
        </div>
    </div>

    <ul class="box-info">
        <li>
            <i class="fa-solid fa-user"></i>
            <span class="text">
                <p>Total User</p>
                <h3>{{ $totalUser }}</h3>
            </span>
        </li>
        <li>
            <i class="fa-solid fa-file"></i>
            <span class="text">
                <p>Total Laporan</p>
                <h3>{{ $totalLaporan }}</h3>
            </span>
        </li>
        <li>
            <i class="fa-solid fa-file-circle-check"></i>
            <span class="text">
                <p>Total Laporan Belum Approve</p>
                <h3>{{ $laporanBelumApprove }}</h3>
            </span>
        </li>
    </ul>

    <!-- Filter -->
    {{-- <div class="d-flex align-items-center gap-2 mt-5 filter-wrapper">
        <label for="date-filter" class="form-label mb-0">Filter data :</label>
        <div class="position-relative rounded-pill bg-light px-3 py-2 date-input-wrapper">
            <input type="date" id="date-filter" class="form-control border-0 bg-transparent p-0 ps-1"
                placeholder="dd/mm/yyyy" style="width: 110px; font-size: 14px;">
        </div>
    </div> --}}

    <div class="row mt-4 mb-3">
        <div class="col-md-4">
            <label for="filter-region" class="form-label">Region</label>
            <select id="filter-region" class="form-select">
                <option value="">Semua Region</option>
                @foreach($regions as $region)
                <option value="{{ $region->id }}" {{ $user->salesOffice->region_id == $region->id ? 'selected' : '' }}>
                    {{ $region->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="filter-sales-office" class="form-label">Sales Office</label>
            <select id="filter-sales-office" class="form-select" disabled>
                <option value="">Memuat...</option>
            </select>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Grafik Patroli per Bulan per Sales Office
        </div>
        <div class="card-body">
            <canvas id="monthlyPatrolChart" height="120"></canvas>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const regionSelect = document.getElementById('filter-region');
        const salesOfficeSelect = document.getElementById('filter-sales-office');
        const ctx = document.getElementById('monthlyPatrolChart')?.getContext('2d');

        // Inisialisasi Chart
        let patrolChart;
        const initLabels = @json($labels ?? []);
        const initDatasets = @json($datasets ?? []);

        if (ctx) {
            patrolChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: initLabels,
                    datasets: initDatasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' },
                        title: {
                            display: true,
                            text: 'Jumlah Patroli per Bulan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Ambil Sales Office Berdasarkan Region
        function loadSalesOffices(regionId, selectedSalesOfficeId = null) {
            salesOfficeSelect.disabled = true;
            salesOfficeSelect.innerHTML = '<option value="">Memuat...</option>';

            if (!regionId) {
                salesOfficeSelect.innerHTML = '<option value="">Pilih region terlebih dahulu</option>';
                return;
            }

            fetch(`/get-sales-offices/${regionId}`)
                .then(response => response.json())
                .then(data => {
                    salesOfficeSelect.disabled = false;
                    salesOfficeSelect.innerHTML = '<option value="">-- Pilih Sales Office --</option>';

                    data.forEach(office => {
                        const isSelected = selectedSalesOfficeId && office.id == selectedSalesOfficeId ? 'selected' : '';
                        salesOfficeSelect.innerHTML += `<option value="${office.id}" ${isSelected}>${office.sales_office_name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Gagal mengambil data Sales Office:', error);
                    salesOfficeSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        }

        // Saat Region diubah
        regionSelect.addEventListener('change', function () {
            const regionId = this.value;
            loadSalesOffices(regionId);
        });

        // Saat Sales Office diubah -> ambil data grafik
        salesOfficeSelect.addEventListener('change', function () {
            const salesOfficeId = this.value;
            if (!salesOfficeId || !patrolChart) return;

            fetch(`/get-patrol-chart/${salesOfficeId}`)
                .then(res => res.json())
                .then(chartData => {
                    patrolChart.data.labels = chartData.labels;
                    patrolChart.data.datasets = chartData.datasets;
                    patrolChart.update();
                })
                .catch(err => {
                    console.error('Gagal memuat data grafik:', err);
                });
        });

        // Inisialisasi data awal jika sudah ada region terpilih
        const initialRegion = regionSelect.value;
        if (initialRegion) {
            loadSalesOffices(initialRegion, "{{ old('sales_office_id', $user->sales_office_id ?? '') }}");
        }
    });
</script>
@endpush
