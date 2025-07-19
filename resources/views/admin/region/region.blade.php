@extends('layout.app')

@section('title','Region')

@section('content')

    <!-- MAIN -->

<main>
    <div class="head-title">
        <div class="left">
            <h1>Region</h1>
        </div>
    </div>

    <div class="control-button top mb-3">
        <a href="{{ route('region.create') }}" class="btn-tambah">
            <i class="fa-solid fa-plus"></i>
            <span class="text">Tambah Region</span>
        </a>
        
    </div>

    <!-- Modal Import Excel -->
    {{-- <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('region.import') }}" method="POST" enctype="multipart/form-data">
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
    </div> --}}

    <div class="table-data mt-4">
        <table id="region-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Region Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables akan mengisi isi tabel -->
            </tbody>
        </table>
    </div>
    
</main>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('scripts')
<!-- jQuery dan DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#region-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("region.data") }}',
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                { data: 'name', name: 'name' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            responsive: true,
            language: {
                search: "",
                searchPlaceholder: " Cari region...",
                lengthMenu: "Tampilkan _MENU_ entri",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
                infoEmpty: "Tidak ada data untuk ditampilkan",
                emptyTable: "Belum ada data region.",

                processing: "Sedang memuat data..."
            },
            lengthMenu: [5, 10, 25, 50],
            pageLength: 10,
        });
    

        // Handler tombol delete
        $('#region-table').on('click', '.delete', function () {
            console.log("Tombol delete diklik"); // Tambahkan log
            const id = $(this).data('id');

            Swal.fire({
            title: 'Yakin ingin menghapus region ini?',
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
                        url: `/region/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            toastr.success(response.message || 'Data berhasil dihapus');
                            $('#region-table').DataTable().ajax.reload();
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

    