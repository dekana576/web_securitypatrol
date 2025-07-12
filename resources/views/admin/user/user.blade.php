@extends('layout.app')

@section('title','User')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>User</h1>
        </div>
    </div>

    <div class="control-button top mb-3">
        <a href="{{ route('user.create') }}" class="btn-tambah">
            <i class="fa-solid fa-plus"></i>
            <span class="text">Tambah User</span>
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

    <div class="table-data">
        <table id="user-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>NIK</th>
                    <th>Phone Number</th>
                    <th>Gender</th>
                    <th>Sales Office</th>
                    <th>Region</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</main>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    const defaultRegionId = "{{ auth()->user()->salesOffice->region_id }}";
    const defaultSalesOfficeId = "{{ auth()->user()->sales_office_id }}";

    const table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("user.data") }}',
            data: function (d) {
                d.region_id = $('#filter-region').val();
                d.sales_office_id = $('#filter-sales-office').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'nik', name: 'nik' },
            { data: 'phone_number', name: 'phone_number' },
            { data: 'gender', name: 'gender' },
            { data: 'sales_office.sales_office_name', name: 'salesOffice.sales_office_name' },
            { data: 'region.name', name: 'region.name' },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        responsive: true,
        language: {
            search: "",
            searchPlaceholder: "Cari user...",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
            infoEmpty: "Tidak ada data untuk ditampilkan",
            emptyTable: "Belum ada data User.",
            paginate: {
                previous: "<button class='btn btn-primary btn-sm me-2'>←</button>",
                next: "<button class='btn btn-primary btn-sm'>→</button>"
            },
            processing: "Sedang memuat data..."
        },
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,
    });

    function loadSalesOffices(regionId, selectedId = '') {
        $('#filter-sales-office').html('<option>Memuat...</option>').prop('disabled', true);
        $.get(`/get-sales-offices/${regionId}`, function (data) {
            let options = '<option value="">Semua Sales Office</option>';
            data.forEach(function (so) {
                const selected = so.id == selectedId ? 'selected' : '';
                options += `<option value="${so.id}" ${selected}>${so.sales_office_name}</option>`;
            });
            $('#filter-sales-office').html(options).prop('disabled', false);
            table.ajax.reload();
        });
    }

    if (defaultRegionId) {
        loadSalesOffices(defaultRegionId, defaultSalesOfficeId);
    }

    $('#filter-region').on('change', function () {
        const regionId = $(this).val();
        if (regionId) {
            loadSalesOffices(regionId);
        } else {
            $('#filter-sales-office').html('<option value="">Pilih Region terlebih dahulu</option>').prop('disabled', true);
            table.ajax.reload();
        }
    });

    $('#filter-sales-office').on('change', function () {
        table.ajax.reload();
    });

    $('#user-table').on('click', '.delete', function () {
        const id = $(this).data('id');
        if (confirm("Yakin ingin menghapus user ini?")) {
            $.ajax({
                url: `/user/${id}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    alert(response.message);
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
