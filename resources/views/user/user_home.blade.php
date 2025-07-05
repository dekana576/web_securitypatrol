@extends('layout.user')
@section('title','Home')

@section('content')
<main>
  <div class="mainContent">
    <div class="container mt-5">
      <div class="card p-3 mb-3 shadow">
        <p><strong>Security Name</strong> : {{ $user->name }}</p>
        <p><strong>Phone Number</strong> : {{ $user->phone }}</p>
        <p><strong>Gender</strong> : {{ ucfirst($user->gender) }}</p>
        <p><strong>Sales Office Name</strong> : {{ $user->salesOffice->sales_office_name ?? '-' }}</p>
      </div>

      <div class="d-grid gap-2 mb-3">
        <a href="#" class="btn btn-primary">Lihat Jadwal</a>
      </div>

      <div class="d-grid gap-2">
        <button id="startScan" class="btn btn-success">
          <i class="fas fa-camera me-2"></i> Scan QR Checkpoint
        </button>
      </div>

      <div id="scanner" class="mt-4 d-none">
        <div id="reader" style="width: 100%; max-width: 500px; margin: auto;"></div>
        <div class="text-center mt-2">
          <button id="stopScan" class="btn btn-danger btn-sm">Tutup Scanner</button>
        </div>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
  const scannerContainer = document.getElementById('scanner');
  const readerDiv = document.getElementById('reader');
  const startBtn = document.getElementById('startScan');
  const stopBtn = document.getElementById('stopScan');

  let html5QrCode;
  let isScanning = false;

  async function startCameraScan() {
    scannerContainer.classList.remove('d-none');
    html5QrCode = new Html5Qrcode("reader");

    const config = { fps: 10, qrbox: 250 };

    try {
      // Coba kamera belakang dulu
      await html5QrCode.start({ facingMode: "environment" }, config, handleScan, handleError);
      isScanning = true;
    } catch (err) {
      console.warn("Kamera belakang tidak tersedia, gunakan kamera depan:", err);

      try {
        // Fallback ke kamera depan
        await html5QrCode.start({ facingMode: "user" }, config, handleScan, handleError);
        isScanning = true;
      } catch (err2) {
        alert("Gagal membuka kamera depan dan belakang: " + err2.message);
        scannerContainer.classList.add('d-none');
      }
    }
  }

  function handleScan(decodedText, decodedResult) {
    html5QrCode.stop().then(() => {
      isScanning = false;
      scannerContainer.classList.add('d-none');
      html5QrCode.clear();

      // Arahkan ke halaman patrol
      window.location.href = `/patrol/${encodeURIComponent(decodedText)}/create`;
    });
  }

  function handleError(errorMessage) {
    // Konsol error bisa diabaikan
  }

  startBtn.addEventListener('click', () => {
    if (!isScanning) startCameraScan();
  });

  stopBtn.addEventListener('click', () => {
    if (html5QrCode && isScanning) {
      html5QrCode.stop().then(() => {
        isScanning = false;
        scannerContainer.classList.add('d-none');
        html5QrCode.clear();
      });
    }
  });
</script>
@endpush
