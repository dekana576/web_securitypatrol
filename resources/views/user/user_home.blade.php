@extends('layout.user')
@section('title','Home')

@section('content')
<main>
  <div class="mainContent">
    <div class="container mt-3">

      {{-- Info User --}}
      <div class="card p-3 mb-3 shadow">
        <div class="text-center mb-4 mt-2">
            <img src="{{ url('img/logo_AM_Patrol_no_Text_PNG.png') }}" width="60" alt="logo">
        </div>
        <p class="d-flex justify-content-between border-bottom pb-1">Security Name  <strong>{{ $user->name }}</strong></p>
        <p class="d-flex justify-content-between border-bottom pb-1">Email <strong>{{ $user->email }}</strong></p>
        <p class="d-flex justify-content-between border-bottom pb-1">Phone Number <strong>{{ $user->phone_number }}</strong> </p>
        <p class="d-flex justify-content-between border-bottom pb-1">Region <strong>{{ $user->region->name ?? '-' }}</strong></p>
        <p class="d-flex justify-content-between border-bottom pb-1">Sales Office <strong>{{ $user->salesOffice->sales_office_name ?? '-' }}</strong></p>

        <div class="target-counter card" style="
            text-align: center;
            padding: 1rem;
            border-radius: 8px;">
            <h6 class="mb-1 text-muted text-white"><strong>{{ ucfirst($shift) }}</strong></h6>
            <span class="counter-number text-primary" style="font-size: 2rem;
            font-weight: 700;
            display: block;">{{ $remaining }}</span>
            <div class="counter-label" style="font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;">Targets Remaining</div>
        </div>



      </div>

      {{-- Tombol Lihat Jadwal --}}
      <div class="d-grid gap-2 mb-3 d-flex justify-content-between">
          <a href="{{ route('schedule.index') }}" class="btn btn-outline-primary w-100 me-2">
              <i class="fa fa-calendar-alt me-2"></i> Jadwal
          </a>
          <a href="{{ route('security.feedback') }}" class="btn btn-outline-warning w-100 position-relative">
              <i class="fa fa-comments me-2"></i> Feedback
              @if(isset($unreadFeedbackCount) && $unreadFeedbackCount > 0)
                  <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                      <span class="visually-hidden">New feedback</span>
                  </span>
              @endif
          </a>
      </div>




      {{-- Tombol Scan Bulat --}}
      <div class="text-center mb-4 mt-5">
        <button id="startScan" class="btn btn-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto"
          style="width: 100px; height: 100px;">
          <i class="fas fa-qrcode fa-2x"></i>
        </button>
        <p class="mt-2 fw-semibold text-muted">Scan QR Checkpoint</p>
      </div>
    </div>
  </div>

  {{-- Overlay Scanner Fullscreen --}}
  <div id="scannerOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-75 d-none" style="z-index: 1050;">
    <div class="d-flex flex-column justify-content-center align-items-center h-100">
      <div id="reader" style="width: 300px;"></div>
      <button id="stopScan" class="btn btn-danger mt-3">Tutup Kamera</button>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
  const overlay = document.getElementById('scannerOverlay');
  const startBtn = document.getElementById('startScan');
  const stopBtn = document.getElementById('stopScan');

  let html5QrCode;
  let isScanning = false;

  async function startCameraScan() {
  overlay.classList.remove('d-none');
  html5QrCode = new Html5Qrcode("reader");

  const config = { fps: 10, qrbox: 250 };

  try {
    await html5QrCode.start({ facingMode: "environment" }, config, handleScan, handleError);
    isScanning = true;
  } catch (err) {
    console.warn("Kamera belakang gagal, coba kamera depan:", err);
    try {
      await html5QrCode.start({ facingMode: "user" }, config, handleScan, handleError);
      isScanning = true;
    } catch (err2) {
      Swal.fire({
        icon: 'error',
        title: 'Akses Kamera Gagal',
        text: 'Tidak bisa mengakses kamera: ' + err2.message,
        confirmButtonText: 'Tutup'
      });
      overlay.classList.add('d-none');
    }
  }
}


  function handleScan(decodedText) {
    html5QrCode.stop().then(() => {
      html5QrCode.clear();
      isScanning = false;
      overlay.classList.add('d-none');
      window.location.href = `/patrol/${encodeURIComponent(decodedText)}/create`;
    });
  }

  function handleError(errorMessage) {
    // Tidak perlu aksi khusus
  }

  startBtn.addEventListener('click', () => {
    if (!isScanning) startCameraScan();
  });

  stopBtn.addEventListener('click', () => {
    if (html5QrCode && isScanning) {
      html5QrCode.stop().then(() => {
        html5QrCode.clear();
        isScanning = false;
        overlay.classList.add('d-none');
      });
    }
  });
</script>
@endpush
