@extends('layout.user')
@section('title','Scan Checkpoint')

@section('content')
<main>
  <div class="mainContent">
    <div class="container mt-4">
      <!-- ✅ Ganti action jadi route yang benar -->
      <form action="{{ route('patrol.store') }}" method="POST" enctype="multipart/form-data" id="patrolForm">
        @csrf

        <input type="hidden" name="checkpoint_id" value="{{ $checkpoint->id }}">
        <input type="hidden" name="region_id" value="{{ $checkpoint->region_id }}">
        <input type="hidden" name="sales_office_id" value="{{ $checkpoint->sales_office_id }}">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div class="card p-3 mb-3">
          <p><strong>Checkpoint Name:</strong> {{ $checkpoint->checkpoint_name }}</p>
          <p><strong>Kode:</strong> {{ $checkpoint->checkpoint_code }}</p>
        </div>

        @foreach($criterias as $criteria)
        <div class="card p-3 mb-3">
          <h6><strong>{{ $criteria->nama_kriteria }}</strong></h6>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="criteria[{{ $criteria->id }}]" value="{{ $criteria->positive_answer }}" required>
            <label class="form-check-label">{{ $criteria->positive_answer }}</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="criteria[{{ $criteria->id }}]" value="{{ $criteria->negative_answer }}" required>
            <label class="form-check-label">{{ $criteria->negative_answer }}</label>
          </div>
        </div>
        @endforeach

        <div class="card p-3 mb-3">
          <label for="description"><strong>Deskripsi Tambahan</strong></label>
          <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="card p-3 mb-3">
          <label for="image"><strong>Upload Foto</strong></label>
          <input type="file" name="image" accept="image/*" capture="environment" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Simpan</button>
      </form>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script>
  // Ambil lokasi GPS
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      document.getElementById("latitude").value = position.coords.latitude;
      document.getElementById("longitude").value = position.coords.longitude;
    }, function(error) {
      alert("Gagal mengambil lokasi: " + error.message);
    });
  } else {
    alert("Browser tidak mendukung Geolocation.");
  }
</script>
@endpush
