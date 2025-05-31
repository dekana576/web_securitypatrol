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
                      <label for="name">Name</label>
                      <input type="text" id="name" name="name" class="form-control"  required>
                    </div>
                    <div class="form-group">
                      <label for="nik">NIK</label>
                      <input type="number" id="nik" name="nik" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label for="phone_number">Phone Number</label>
                      <input type="number" id="phone_number" name="phone_number" class="form-control" required>
                    </div>  
                    <div class="form-group">
                      <label for="gender">Gender</label>
                      <input type="text" id="gender" name="gender" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                      <label for="sales_office_name">Sales Office Name</label>
                      <select id="sales_office_name" name="sales_office_name" class="form-control" required>
                        <option value="">-- Pilih Sales Office --</option>
                        <option value="Denpasar">Denpasar</option>
                        <option value="Badung">Badung</option>
                      </select>
                    </div>
                  
                    <button type="submit">Tambah</button>
                    <button class="btn-cancel">Batal</button>
                    
                  </form>
            </div>
         
        </div>
    </main>
    <!-- MAIN -->   

@endsection
    