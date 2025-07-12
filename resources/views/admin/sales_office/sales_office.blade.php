@extends('layout.app')

@section('title','Sales Office')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Sales Office</h1>
        </div>
    </div>

    <div class="control-button top mb-3">
        <a href="{{ route('sales_office.create') }}" class="btn-tambah">
            <i class="fa-solid fa-plus"></i>
            <span class="text">Tambah Sales Office</span>
        </a>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="filter-region" class="form-label">Filter Region</label>
            <select id="filter-region" class="form-select">
                <option value="">Semua Region</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ $user->salesOffice->region_id == $region->id ? 'selected' : '' }}>
                        {{ $region->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="table-data mt-4">
        <table id="sales-office-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Region</th>
                    <th>Nama Sales Office</th>
                    <th>Alamat</th>
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
        const table = $('#sales-office-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("sales_office.data") }}',
                data: function (d) {
                    d.region_id = $('#filter-region').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'region.name', name: 'region.name' },
                { data: 'sales_office_name', name: 'sales_office_name' },
                { data: 'sales_office_address', name: 'sales_office_address' },
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
                searchPlaceholder: " Cari sales office...",
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

        $('#filter-region').on('change', function () {
            table.ajax.reload();
        });

        $('#sales-office-table').on('click', '.delete', function () {
            const id = $(this).data('id');

            if (confirm("Yakin ingin menghapus Sales Office ini?")) {
                $.ajax({
                    url: `/sales_office/${id}`,
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
