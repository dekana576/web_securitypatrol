@extends('layout.app')

@section('title','Tambah user')

@section('content')

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>User</h1>
                <!-- <ul class="breadcrumb">
                    <li>
                        <a href="#">Dashboard</a>
                    </li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li>
                        <a class="active" href="#">Home</a>
                    </li>
                </ul> -->
            </div>
            {{-- <a href="#" class="btn-download">
                <i class='bx bxs-cloud-download' ></i>
                <span class="text">Download PDF</span>
            </a> --}}
        </div>

       


        <div class="table-data">
           
            <div class="todo">
                <div class="head">
                    <h3></h3>
                </div>
                <form action="/submit" method="POST" class="form-input">
                    <div class="form-group">
                      <label for="nama">Name</label>
                      <input type="text" id="nama" name="nama" required>
                    </div>
                  
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" id="email" name="email" required>
                    </div>
                  
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" id="password" name="password" required>
                    </div>
                  
                    <button type="submit">Tambah</button>
                    
                  </form>
            </div>
            <form action="#" method="POST">
              <div class="row">
                
                  <div class="card card-primary">
                    
                    <div class="card-body">
                      <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Masukkan Nama" name="name">
                      </div>
                    </div>
                  </div>
                
              </div>
      
              <div class="row">
                <div class="col-12">
                  <a href="{{ url()->previous() }}" class="btn btn-secondary" style="margin-right: 10px;">Cancel</a>
                  <input type="submit" value="Submit" class="btn btn-success">
                </div>
              </div>
      
            </form>
        </div>
    </main>
    <!-- MAIN -->   

@endsection
    