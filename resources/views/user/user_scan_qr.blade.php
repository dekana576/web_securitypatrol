@extends('layout.user')
@section('title','User Scan QR')


@section('content')
<main>
  <div  id="mainContent">
    <div class="container mt-5">
      <div class="card p-3 mb-3">
        <h6><strong>Description</strong></h6>
        <textarea class="form-control" placeholder="Add Description" rows="3"></textarea>
      </div>

      <div class="card p-3 mb-3">
        <h6><strong>Upload Image</strong></h6>
        <div class="text-center mb-2 text-muted">Add Image</div>
        <label for="cameraInput" class="camera-btn">
          <i class="fa-solid fa-camera"></i>
        </label>
        <input type="file" accept="image/*" capture="environment" id="cameraInput">
      </div>

      <button class="save-btn">Simpan</button>
    </div>
  </div>
</main>
@endsection