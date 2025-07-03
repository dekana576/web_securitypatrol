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
        const table = $('#checkpoint-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("checkpoint.data") }}',
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

    // Handler tombol delete
        $('#checkpoint-table').on('click', '.delete', function () {
            const id = $(this).data('id');

            if (confirm("Yakin ingin menghapus checkpoint ini?")) {
                $.ajax({
                    url: `/checkpoint/${id}`,
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
