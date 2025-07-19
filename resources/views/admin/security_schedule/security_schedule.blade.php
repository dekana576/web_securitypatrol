@extends('layout.app')

@section('title','Jadwal Patroli Bulanan')

@section('content')
<main>
    <div class="head-title d-flex justify-content-between align-items-center">
        <div class="left">
            <h1>Jadwal Patroli Bulanan</h1>
        </div>
    </div>
    <div class="control-button top mb-3">
        <a href="{{ route('security_schedule.create') }}" class="btn-tambah">
            <i class="fa-solid fa-plus"></i>
            <span class="text">Tambah Schedule</span>
        </a>
    </div>
        <div class="row mb-3">
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

    <div class="table-data mt-4">
        <table id="patrol-schedule-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Region</th>
                    <th>Sales Office</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
<script>
$(document).ready(function () {
    const userSalesOfficeId = '{{ $user->sales_office_id }}';
    const defaultRegionId = $('#filter-region').val();
    const table = $('#patrol-schedule-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("security_schedule.data") }}',
            data: function (d) {
                d.region_id = $('#filter-region').val();
                d.sales_office_id = $('#filter-sales-office').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'region_name', name: 'region.name' },
            { data: 'sales_office_name', name: 'salesOffice.sales_office_name' },
            { data: 'bulan', name: 'bulan' },
            { data: 'tahun', name: 'tahun' },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false, 
                className: 'text-center' 
            }
        ],
        language: {
            search: "",
            searchPlaceholder: "Cari...",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
            processing: "Memuat data..."
        },
        lengthMenu: [5, 10, 25],
        pageLength: 10,
    });

    // Load Sales Office saat halaman pertama kali dimuat
    if (defaultRegionId) {
        loadSalesOffices(defaultRegionId, userSalesOfficeId);
    }

    function loadSalesOffices(regionId, selectedId = null) {
        $('#filter-sales-office').prop('disabled', true).html('<option value="">Memuat...</option>');

        $.get(`/get-sales-offices/${regionId}`, function (data) {
            let options = '<option value="">Semua Sales Office</option>';
            data.forEach(function (so) {
                const selected = selectedId == so.id ? 'selected' : '';
                options += `<option value="${so.id}" ${selected}>${so.sales_office_name}</option>`;
            });
            $('#filter-sales-office').html(options).prop('disabled', false);
            table.ajax.reload();
        });
    }

    // Event: Filter Region → Load Sales Office
    $('#filter-region').on('change', function () {
        const regionId = $(this).val();
        if (regionId) {
            loadSalesOffices(regionId);
        } else {
            $('#filter-sales-office').html('<option value="">Pilih Region terlebih dahulu</option>').prop('disabled', true);
            table.ajax.reload();
        }
    });

    // Event: Filter Sales Office
    $('#filter-sales-office').on('change', function () {
        table.ajax.reload();
    });

    $('#patrol-schedule-table').on('click', '.delete', function () {
        console.log("Tombol delete diklik"); // Tambahkan log
        const regionId = $(this).data('region');
        const salesOfficeId = $(this).data('id');
        const bulan = $(this).data('bulan');
        const tahun = $(this).data('tahun');
        

        Swal.fire({
            title: 'Yakin ingin menghapus jadwal ini?',
            text: "Tindakan ini tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/security_schedule/${regionId}/${salesOfficeId}/${bulan}/${tahun}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            toastr.success(response.message || 'Data berhasil dihapus');
                            $('#patrol-schedule-table').DataTable().ajax.reload();
                        },
                        error: function () {
                            toastr.error('Terjadi kesalahan saat menghapus data.');
                        }
                    });
                }
            });
    });
});
</script>
@endpush
