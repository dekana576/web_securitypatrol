@extends('layout.user')
@section('title','Home')


@section('content')
<main>
  <div class="mainContent">
    <div class="container mt-5">
      <div class="card p-3 mb-3 ">
        <p><strong>Security Name</strong> : </p>
        <p><strong>Phone Number</strong> : </p>
        <p><strong>Gender</strong> : </p>
        <p><strong>Sales Office Name</strong> : </p>
        
      </div>
        <a href="#" class="link-jadwal">Lihat Jadwal di sini</a>
    </div>
    <div class="camera-button">
      <i class="fas fa-camera"></i>
    </div>
  </div>
</main>
    
@endsection