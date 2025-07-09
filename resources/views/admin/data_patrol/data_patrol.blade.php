@extends('layout.app')

@section('title','Data Patrol')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Data Patrol</h1>
        </div>
    </div>

    <div class="table-data mt-4">
        <table id="data-patrol-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Region</th>
                    <th>Sales Office</th>
                    <th>Checkpoint</th>
                    <th>Kriteria</th>
                    <th>Security</th>
                    <th>Status</th>
                    <th>Aksi</th>
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
        const table = $('#data-patrol-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("data_patrol.data") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'region_name', name: 'region.name' },
                { data: 'sales_office_name', name: 'salesOffice.sales_office_name' },
                { data: 'checkpoint_name', name: 'checkpoint.checkpoint_name' },
                { data: 'kriteria_result', name: 'kriteria_result' },
                { data: 'security_name', name: 'user.name' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            responsive: true,
            dom: '<"row mb-3"<"col-sm-6"l><"col-sm-6 text-end"f>>' +
                 '<"table-responsive"tr>' +
                 '<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>',
            language: {
                search: "",
                searchPlaceholder: " Cari Data Patrol...",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                infoEmpty: "Tidak ada data untuk ditampilkan",
                emptyTable: "Belum ada data patrol.",
                paginate: {
                    previous: "<button class='btn btn-primary btn-sm me-2'>←</button>",
                    next: "<button class='btn btn-primary btn-sm'>→</button>"
                },
                processing: "Sedang memuat data..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
        });

        // Handler tombol approve
        $('#data-patrol-table').on('click', '.approve', function () {
            const id = $(this).data('id');

            if (confirm("Setujui data patroli ini?")) {
                $.ajax({
                    url: `/data_patrol/${id}/approve`,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert(response.message);
                        table.ajax.reload();
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat menyetujui data.');
                    }
                });
            }
        });


        // Handler tombol delete
        $('#data-patrol-table').on('click', '.delete', function () {
            const url = $(this).data('url');

            if (confirm("Yakin ingin menghapus data patrol ini?")) {
                $.ajax({
                    url: url,
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
