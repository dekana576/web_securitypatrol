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

        <div class="card p-3" style="margin-bottom: 150px">
          <label><strong>Upload Foto (maksimal 10 gambar)</strong></label>

          {{-- Container untuk input file --}}
          <div id="image-input-wrapper" style="display: none;"></div>

          {{-- Container Preview Slider --}}
          <div class="swiper-container mt-3" id="preview-swiper" style="display: none; overflow: hidden;">
            <div class="swiper-wrapper" id="swiper-wrapper"></div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next swiper-button-custom"></div>
            <div class="swiper-button-prev swiper-button-custom"></div>
          </div>
        </div>

        <!-- Container tombol mengambang -->
        <div class="d-flex justify-content-center">

          <div class="position-fixed d-flex gap-3 align-items-center" style="bottom: 20px; z-index: 9999;">
            
            <!-- Tombol Back -->
            <a href="{{ url()->previous() }}" class="btn btn-secondary rounded-circle shadow d-flex align-items-center justify-content-center me-5"
              style="width: 60px; height: 60px;">
              <i class="fa fa-arrow-left"></i>
            </a>
  
            <!-- Tombol Kamera -->
            <button type="button" class="btn btn-danger rounded-circle shadow d-flex align-items-center justify-content-center" id="add-image-btn"
              style="width: 80px; height: 80px;">
              <i class="fa fa-camera fa-lg"></i>
            </button>
  
            <!-- Tombol Simpan -->
            <button type="submit" class="btn btn-primary rounded-circle shadow d-flex align-items-center justify-content-center ms-5"
              style="width: 60px; height: 60px;">
              <i class="fa fa-save"></i>
            </button>
  
          </div>
        </div>



      </form>
    </div>
  </div>
</main>
@endsection

@push('scripts')

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script> 
<script>
  // Ambil lokasi GPS
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      document.getElementById("latitude").value = position.coords.latitude;
      document.getElementById("longitude").value = position.coords.longitude;
    }, function(error) {
      toastr.error("Gagal mengambil lokasi: " + error.message);
    });
  } else {
    toastr.error("Browser tidak mendukung Geolocation.");
  }

  let imageCount = 0;
  const maxImages = 10;
  const imageInputWrapper = document.getElementById('image-input-wrapper');
  const swiperWrapper = document.getElementById('swiper-wrapper');
  const previewSwiper = document.getElementById('preview-swiper');
  const addImageBtn = document.getElementById('add-image-btn');

  const swiper = new Swiper('.swiper-container', {
    loop: false,
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    slidesPerView: 1,
    spaceBetween: 10,
  });

  function updateSwiper() {
    swiper.update();
    previewSwiper.style.display = imageCount > 0 ? 'block' : 'none';
  }

  addImageBtn.addEventListener('click', () => {
    if (imageCount >= maxImages) {
      toastr.error('Maksimal 10 gambar!');
      return;
    }

    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'image[]';
    input.accept = 'image/*';
    input.capture = 'environment';
    input.style.display = 'none';

    input.addEventListener('change', function () {
      if (input.files.length > 0) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function (e) {
          const slide = document.createElement('div');
          slide.classList.add('swiper-slide', 'position-relative');

          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'w-100 h-auto rounded shadow';
          img.style.maxHeight = '350px';
          img.style.objectFit = 'contain';

          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.innerHTML = '&times;';
          removeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle';
          removeBtn.style.width = '30px';
          removeBtn.style.height = '30px';
          removeBtn.title = 'Hapus gambar';

          removeBtn.addEventListener('click', function () {
            slide.remove();
            input.remove();
            imageCount--;
            updateSwiper();
          });

          slide.appendChild(img);
          slide.appendChild(removeBtn);
          swiperWrapper.appendChild(slide);
          imageInputWrapper.appendChild(input);
          imageCount++;
          updateSwiper();
        };
        reader.readAsDataURL(file);
      }
    });

    input.click();
  });

  // Validasi saat submit
  document.getElementById('patrolForm').addEventListener('submit', function(e) {
    const totalInputs = imageWrapper.querySelectorAll('input[type="file"]').length;
    if (totalInputs === 0) {
      e.preventDefault();
      toastr.error('Minimal upload 1 gambar.');
      return;
    }

    if (totalInputs > maxImages) {
      e.preventDefault();
      toastr.error('Maksimal 10 gambar diperbolehkan.');
    }
  });
</script>
@endpush

