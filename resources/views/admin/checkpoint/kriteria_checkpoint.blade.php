@extends('layout.app')

@section('title', 'Kriteria Checkpoint')

@section('content')
<main>
    <div class="head-title mb-4">
        <div class="left">
            <h1>Kriteria untuk: {{ $checkpoint->checkpoint_name }}</h1>
            <p><strong>Region:</strong> {{ $checkpoint->region->name }} |
               <strong>Sales Office:</strong> {{ $checkpoint->salesOffice->sales_office_name }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Tambah Kriteria</h5>
        </div>

        
        <div class="card-body">
            <form action="{{ route('checkpoint_criteria.store', $checkpoint->id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Positive Answer</label>
                        <input type="text" name="positive_answer" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Negative Answer</label>
                        <input type="text" name="negative_answer" class="form-control" required>
                    </div>
                    <div class="col-12 text-end">
                        <button class="btn btn-primary mt-3">Tambah Kriteria</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    

    @if ($criterias->count())
    <div class="card">
        <div class="card-header">
            <h5>Daftar Kriteria</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kriteria</th>
                        <th>Positive Answer</th>
                        <th>Negative Answer</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criterias as $index => $kriteria)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $kriteria->nama_kriteria }}</td>
                        <td>{{ $kriteria->positive_answer }}</td>
                        <td>{{ $kriteria->negative_answer }}</td>
                        <td class="text-center">
                            <button 
                                class="btn btn-sm btn-danger delete-kriteria" 
                                data-id="{{ $kriteria->id }}" 
                                data-name="{{ $kriteria->nama_kriteria }}">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>
    
    @else
    <div class="alert alert-info">Belum ada kriteria untuk checkpoint ini.</div>
    @endif

    <div class="right mt-4">
        <a href="{{ route('checkpoint.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Kembali ke Checkpoint
        </a>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-kriteria').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;
    
                Swal.fire({
                    title: 'Yakin ingin menghapus kriteris ini?',
                    text: `Kriteria "${name}" akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/checkpoint-criteria/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success(data.message);
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                toastr.error(data.message || 'Gagal menghapus data.');
                            }
                        })
                        .catch(() => {
                            toastr.error('Terjadi kesalahan saat menghapus.');
                        });
                    }
                });
            });
        });
    });
</script>
    
@endpush
