@extends('layout.user')
@section('title', 'Detail Feedback')

@section('content')
<main class="container py-4">
    <a href="{{ route('security.feedback') }}" class="btn btn-secondary mb-3">
        <i class="fa fa-arrow-left me-1"></i> Kembali
    </a>

    <div class="card shadow">
        <div class="card-body">
            <h5 class="fw-semibold">Feedback Admin</h5>
            <p class="text-muted">{{ $feedback->feedback_admin }}</p>
            <hr>
            <p><strong>Security:</strong> {{ $feedback->user->name }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($feedback->created_at)->format('d M Y H:i') }}</p>
            <p><strong>Region:</strong> {{ $feedback->region->name ?? '-' }}</p>
            <p><strong>Sales Office:</strong> {{ $feedback->salesOffice->sales_office_name ?? '-' }}</p>
            <p><strong>Checkpoint:</strong> {{ $feedback->checkpoint->checkpoint_name ?? '-' }}</p>
            <p><strong>Deskripsi:</strong> {{ $feedback->description }}</p>

            <h6 class="mt-3">Kriteria Checkpoint:</h6>
            @php
                $kriteriaData = json_decode($feedback->kriteria_result, true);
            @endphp
            <ul class="list-group">
                @foreach($kriteriaData as $id => $answer)
                    @php $label = \App\Models\CheckpointCriteria::find($id); @endphp
                    @if($label)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $label->nama_kriteria }}</span>
                            <span class="badge {{ str_contains(strtolower($answer), 'tidak') ? 'bg-danger' : 'bg-success' }} text-white">
                                {{ ucfirst($answer) }}
                            </span>
                        </li>
                    @endif
                @endforeach
            </ul>
            @if($feedback->status === 'submitted')
            <form id="patrolForm" action="{{ route('security.feedback.done', $feedback->id) }}" method="POST"
              enctype="multipart/form-data">
              
                    @csrf
                    @method('PUT')

                    <div class="card p-3 mt-2 mb-5">
                        <label><strong>Upload Foto Feedback (maksimal 10 gambar)</strong></label>

                        {{-- Tempat input file disisipkan dinamis --}}
                        <div id="image-input-wrapper" style="display: none;"></div>

                        {{-- Preview slider --}}
                        <div class="swiper-container mt-3" id="preview-swiper" style="display: none; overflow: hidden;">
                            <div class="swiper-wrapper" id="swiper-wrapper"></div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next swiper-button-custom"></div>
                            <div class="swiper-button-prev swiper-button-custom"></div>
                        </div>
                    </div>

                    <div class="position-fixed d-flex gap-3 align-items-center justify-content-around px-3"
                        style="bottom: 20px; right: 0; z-index: 9999; width: 100%;">

                        {{-- Tombol Kamera --}}
                        <button type="button" class="btn btn-danger rounded-circle shadow d-flex align-items-center justify-content-center" id="add-image-btn"
                            style="width: 80px; height: 80px;">
                            <i class="fa fa-camera fa-lg"></i>
                        </button>

                        {{-- Tombol Simpan --}}
                        <button type="submit"
                            class="btn btn-success rounded-circle shadow d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="fa fa-check fa-lg"></i>
                        </button>
                    </div>
                </form>
            @endif


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
      toastr.warning('Maksimal 10 gambar!');
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
    const totalInputs = imageInputWrapper.querySelectorAll('input[type="file"]').length;
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
