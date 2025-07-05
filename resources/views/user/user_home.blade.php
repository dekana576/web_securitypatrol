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
        <a href="" class="btn btn-primary">Lihat Jadwal</a>
      </div>

      <div class="d-grid gap-2">
        <button id="startScan" class="btn btn-success">
          <i class="fas fa-camera me-2"></i> Mulai Scan QR
        </button>
      </div>

      <!-- QR Scanner Container -->
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
<!-- Load HTML5 QR Code library -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
  const scannerContainer = document.getElementById('scanner');
  const readerDiv = document.getElementById('reader');
  const startBtn = document.getElementById('startScan');
  const stopBtn = document.getElementById('stopScan');

  let html5QrCode;
  let isScanning = false;

  startBtn.addEventListener('click', () => {
    if (isScanning) return;

    scannerContainer.classList.remove('d-none');
    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
      { facingMode: "environment" }, // Kamera belakang
      { fps: 10, qrbox: { width: 250, height: 250 } },
      (decodedText, decodedResult) => {
        html5QrCode.stop().then(() => {
          isScanning = false;
          scannerContainer.classList.add('d-none');
          html5QrCode.clear();
          // Redirect ke halaman patrol
          window.location.href = `/patrol/${encodeURIComponent(decodedText)}/create`;
        });
      },
      (errorMessage) => {
        // console.log("Scanning...", errorMessage);
      }
    ).then(() => {
      isScanning = true;
    }).catch(err => {
      alert("Gagal mengakses kamera: " + err);
    });
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
