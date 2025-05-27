@extends('layout.user')
@section('title','User Input')


@section('content')
<main>
  <div id="mainContent">
    <div class="container mt-5">
      <div class="card p-3 mb-3">
        <p><strong>Id Checkpoint</strong> : </p>
        <p><strong>Checkpoint Name</strong> : </p>
      </div>
      <div class="card p-3 mb-3">
        <h6><strong>Keadaan lokasi aman?</strong></h6>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="lokasiAman" id="amanYa" value="Ya">
          <label class="form-check-label" for="amanYa">Ya</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="lokasiAman" id="amanTidak" value="Tidak">
          <label class="form-check-label" for="amanTidak">Tidak</label>
        </div>
      </div>
      <button class="save-btn">Simpan</button>
    </div>
  </div>
</main>
    
@endsection