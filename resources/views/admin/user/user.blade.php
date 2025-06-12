@extends('layout.app')

@section('title','User')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>User</h1>
        </div>
    </div>

    <div class="control-button top">
        <a href="{{ route('user.create') }}" class="btn-tambah">
            <i class="fa-solid fa-plus"></i>
            <span class="text">Tambah User</span>
        </a>
        <a href="#" class="btn-import" type="button" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fa-solid fa-file-import"></i>
            <span class="text">Import User</span>
        </a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data dari Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" name="file" required accept=".xlsx,.xls">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
            </form>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
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
        const table = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("user.data") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'nik', name: 'nik' },
                { data: 'phone_number', name: 'phone_number' },
                { data: 'gender', name: 'gender' },
                { data: 'sales_office.sales_office_name', name: 'sales_office.sales_office_name' },
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
