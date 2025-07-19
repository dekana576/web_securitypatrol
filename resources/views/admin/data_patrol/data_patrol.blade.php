@extends('layout.app')

@section('title','Data Patrol')

@section('content')

<main>
    <div class="head-title">
        <div class="left">
            <h1>Data Patrol</h1>
        </div>
    </div>

    <div class="row mb-3">
        {{-- Filter Region --}}
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
        

        {{-- Filter Sales Office --}}
        <div class="col-md-4">
            <label for="filter-sales-office" class="form-label">Sales Office</label>
            <select id="filter-sales-office" class="form-select" disabled>
                <option value="">Memuat...</option>
            </select>
        </div>

        {{-- Filter Tanggal --}}
        <div class="col-md-4">
            <label class="form-label">Tanggal</label>
            <div class="d-flex gap-2">
                <select id="filter-year" class="form-select">
                    <option value="">Tahun</option>
                    @for ($y = now()->year; $y >= 2023; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
                <select id="filter-month" class="form-select">
                    <option value="">Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endfor
                </select>
                <select id="filter-day" class="form-select">
                    <option value="">Hari</option>
                    @for ($d = 1; $d <= 31; $d++)
                        <option value="{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}">{{ $d }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>


    <div class="table-data mt-4">
        <table id="data-patrol-table" class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>shift</th>
                    <th>Region</th>
                    <th>Sales Office</th>
                    <th>Checkpoint</th>
                    <th>Kriteria</th>
                    <th>Security</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</main>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.4/css/buttons.dataTables.css">
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.4/js/dataTables.buttons.js"></script>
<script>
$(document).ready(function () {
    const defaultRegionId = "{{ auth()->user()->salesOffice->region_id }}";
    const defaultSalesOfficeId = "{{ auth()->user()->sales_office_id }}";

    const table = $('#data-patrol-table').DataTable({
        layout: {
        bottomStart: {
            buttons: [{
                extend: 'print',
                text: '<i class="fa-solid fa-print"></i>',
                className: 'btn btn-secondary',
                titleAttr: 'Print Data Patrol',
                exportOptions: {
                    columns: ':not(.no-export)'
                }
            }]
            ,
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ entri',
            
        }
    },
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("data_patrol.data") }}',
            data: function (d) {
                d.region_id = $('#filter-region').val();
                d.sales_office_id = $('#filter-sales-office').val();
                d.year = $('#filter-year').val();
                d.month = $('#filter-month').val();
                d.day = $('#filter-day').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'tanggal', name: 'tanggal' },
            { data: 'shift', name: 'shift' },
            { data: 'region_name', name: 'region.name' },
            { data: 'sales_office_name', name: 'salesOffice.sales_office_name' },
            { data: 'checkpoint_name', name: 'checkpoint.checkpoint_name' },
            { data: 'kriteria_result', name: 'kriteria_result' },
            { data: 'security_name', name: 'user.name' },
            { data: 'status', name: 'status' },
            { data: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        responsive: true,

        language: {
            search: "", searchPlaceholder: " Cari Data Patrol...",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ entri",
            emptyTable: "Belum ada data patrol.",
            processing: "Sedang memuat data..."

        },
        lengthMenu: [5, 10, 25, 50],
        pageLength: 10,

        
    });

    function loadSalesOffices(regionId, selectedId = '') {
        $('#filter-sales-office').html('<option>Memuat...</option>').prop('disabled', true);
        if (!regionId) {
            $('#filter-sales-office').html('<option value="">Pilih Region terlebih dahulu</option>').prop('disabled', true);
            table.ajax.reload();
            return;
        }

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

    // Load default sales office saat pertama
    if (defaultRegionId) {
        loadSalesOffices(defaultRegionId, defaultSalesOfficeId);
    }

    $('#filter-region').on('change', function () {
        const regionId = $(this).val();
        loadSalesOffices(regionId);
    });

    $('#filter-sales-office').on('change', function () {
        table.ajax.reload();
    });
    $('#filter-year, #filter-month, #filter-day').on('change', function () {
        table.ajax.reload();
    });

    $('#data-patrol-table').on('click', '.approve', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Setujui Data?',
            text: 'Apakah Anda yakin ingin menyetujui data patroli ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/data_patrol/${id}/approve`,
                    type: 'PUT',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        toastr.success(response.message || 'Data patroli berhasil disetujui');
                        $('#data-patrol-table').DataTable().ajax.reload();
                    },
                    error: function () {
                        toastr.error('Terjadi kesalahan saat menyetujui data.');
                    }
                });
            }
        });
    });


    $('#data-patrol-table').on('click', '.delete', function () {
    const url = $(this).data('url');

    Swal.fire({
        title: 'Hapus Data ?',
        text: 'Data ini akan dihapus secara permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.success(response.message || 'Data berhasil dihapus');
                    $('#data-patrol-table').DataTable().ajax.reload();
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
