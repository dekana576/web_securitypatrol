@extends('layout.user')
@section('title','Scan QR Checkpoint')

@section('content')
<main>
  <div class="mainContent">
    <div class="container mt-5">
      <form action="{{ route('user.scan.qr.result') }}" method="GET" class="card p-4 shadow">
        <label for="code"><strong>Masukkan / Scan QR Code:</strong></label>
        <input type="text" name="code" class="form-control mb-3" placeholder="Scan atau masukkan kode checkpoint" required>

        <button type="submit" class="btn btn-success w-100">
          <i class="fas fa-search me-2"></i> Lanjutkan
        </button>
      </form>
    </div>
  </div>
</main>
@endsection
