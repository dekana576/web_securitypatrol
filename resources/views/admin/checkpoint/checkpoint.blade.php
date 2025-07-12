@extends('layout.app')

@section('title','Checkpoint')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Checkpoint</h1>
        </div>
    </div>

    <div class="control-button top mb-3">
        <a href="{{ route('checkpoint.create') }}" class="btn-tambah">
            <i class="fa-solid fa-plus"></i>
            <span class="text">Tambah Checkpoint</span>
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
        <table id="checkpoint-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Region</th>
                    <th>Sales Office</th>
                    <th>Checkpoint Name</th>
                    <th>Kode</th>
                    <th>QR Code</th>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    const userSalesOfficeId = '{{ $user->sales_office_id }}';
    const defaultRegionId = $('#filter-region').val();

    // Inisialisasi DataTable
    const table = $('#checkpoint-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("checkpoint.data") }}',
            data: function (d) {
                d.region_id = $('#filter-region').val();
                d.sales_office_id = $('#filter-sales-office').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'region_name', name: 'region.name' },
            { data: 'sales_office_name', name: 'salesOffice.sales_office_name' },
            { data: 'checkpoint_name', name: 'checkpoint_name' },
            { data: 'checkpoint_code', name: 'checkpoint_code' },
            { data: 'qr_code', name: 'qr_code', orderable: false, searchable: false },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false, 
                className: 'text-center' 
            }
        ],
        responsive: true,
        dom: '<"row mb-3"<"col-sm-6"l><"col-sm-6 text-end"f>>' +
             '<"table-responsive"tr>' +
             '<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>',
        language: {
            search: "",
            searchPlaceholder: " Cari Checkpoint...",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
            infoEmpty: "Tidak ada data untuk ditampilkan",
            emptyTable: "Belum ada data Sales Office.",
            paginate: {
                previous: "<button class='btn btn-primary btn-sm me-2'>←</button>",
                next: "<button class='btn btn-primary btn-sm'>→</button>"
            },
            processing: "Sedang memuat data..."
        },
        lengthMenu: [5, 10, 25, 50],
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

    // Tombol delete
    $('#checkpoint-table').on('click', '.delete', function () {
        const id = $(this).data('id');
        if (confirm("Yakin ingin menghapus checkpoint ini?")) {
            $.ajax({
                url: `/checkpoint/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    alert(res.message);
                    table.ajax.reload();
                },
                error: function () {
                    alert('Terjadi kesalahan saat menghapus data.');
                }
            });
        }
    });
});
</script>
@endpush
