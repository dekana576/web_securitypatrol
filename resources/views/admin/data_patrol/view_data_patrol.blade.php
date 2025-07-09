@extends('layout.app')

@section('title', 'Detail Data Patrol')

@section('content')
<main class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4 fw-semibold">Detail Data Patrol</h4>

 

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Nama Security:</strong> {{ $dataPatrol->user->name }}</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($dataPatrol->created_at)->format('d M Y H:i') }}</p>
                    <p>
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $dataPatrol->status === 'approved' ? 'success' : 'warning' }} text-white">
                            {{ ucfirst($dataPatrol->status) }}
                        </span>
                    </p>
                    <p><strong>Region:</strong> {{ $dataPatrol->region->name ?? '-' }}</p>
                    <p><strong>Sales Office:</strong> {{ $dataPatrol->salesOffice->sales_office_name ?? '-' }}</p>
                    <p><strong>Checkpoint:</strong> {{ $dataPatrol->checkpoint->checkpoint_name ?? '-' }}</p>
                </div>

                <div class="col-md-6">
                    <p><strong>Deskripsi:</strong></p>
                    <p class="text-muted">{{ $dataPatrol->description ?: '-' }}</p>

                    @if($dataPatrol->image)
                        <img src="{{ asset('storage/' . $dataPatrol->image) }}" alt="Foto Patroli"
                            class="img-fluid rounded shadow mt-2" style="max-height: 300px;">
                    @endif
                </div>
            </div>

            <hr>

            <div class="mb-4">
                <h5 class="fw-semibold">Kriteria Checkpoint</h5>
                @php
                    $kriteriaData = json_decode($dataPatrol->kriteria_result, true);
                    $hasNegative = collect($kriteriaData)->filter(fn($val) => str_contains(strtolower($val), 'tidak') || str_contains(strtolower($val), 'negative'))->isNotEmpty();
                @endphp
                @if($kriteriaData)
                    <div class="mb-3">
                        <span class="badge {{ $hasNegative ? 'bg-danger' : 'bg-success' }} text-white py-2 px-3">
                            {{ $hasNegative ? 'Tidak Aman' : 'Aman' }}
                        </span>
                    </div>
                    <ul class="list-group">
                        @foreach($kriteriaData as $kriteriaId => $answer)
                            @php
                                $kriteria = \App\Models\CheckpointCriteria::find($kriteriaId);
                            @endphp
                            @if($kriteria)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>{{ $kriteria->nama_kriteria }}</span>
                                    <span class="badge {{ str_contains(strtolower($answer), 'tidak') ? 'bg-danger' : 'bg-success' }} text-white">
                                        {{ ucfirst($answer) }}
                                    </span>
                                </li>
                            @else
                                <li class="list-group-item text-muted fst-italic">
                                    Kriteria tidak ditemukan (ID: {{ $kriteriaId }})
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Belum ada data kriteria. </p>
                @endif
            </div>

            <div class="mb-4">
                <h5 class="fw-semibold">Lokasi</h5>
                <div id="map" class="rounded shadow" style="height: 400px;"></div>
            </div>

            {{-- Feedback Admin --}}
            @if(Auth::user()->role === 'admin')
                <div class="card mb-4 mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Feedback Admin</h5>
                        <form action="{{ route('data_patrol.feedback', $dataPatrol->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <textarea name="feedback_admin" rows="4" class="form-control mb-3">{{ $dataPatrol->feedback_admin }}</textarea>
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-save me-1"></i> Simpan Feedback
                            </button>
                        </form>

                        @if($dataPatrol->status != 'approved')
                            <form action="{{ route('data_patrol.approve', $dataPatrol->id) }}" method="POST" onsubmit="return confirm('Setujui data patroli ini?')" class="mt-3">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-check me-1"></i> Approve
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @elseif($dataPatrol->feedback_admin)
                <div class="card mb-4 mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Feedback dari Admin</h5>
                        <p>{{ $dataPatrol->feedback_admin }}</p>
                    </div>
                </div>
            @endif

            {{-- Tombol Kembali di Bawah --}}
            <div class="mt-4 text-start">
                <a href="{{ route('data_patrol.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

{{-- Toastr CSS & JS --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const lokasi = "{{ $dataPatrol->lokasi }}".split(',');
        const lat = parseFloat(lokasi[0]);
        const lng = parseFloat(lokasi[1]);

        const map = L.map('map').setView([lat, lng], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © OpenStreetMap contributors'
        }).addTo(map);

        L.marker([lat, lng]).addTo(map).bindPopup("Lokasi patroli").openPopup();

        setTimeout(() => map.invalidateSize(), 500);
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

    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif
    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif
    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endpush
