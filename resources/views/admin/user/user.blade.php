@extends('layout.app')

@section('title','User')

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
           
        </div>

        <div class="control-button top">
            <a href="{{url("adduser")}}" class="btn-tambah">
                <i class="fa-solid fa-plus"></i>
                <span class="text">Tambah User</span>
            </a>
            <a href="#" class="btn-import" type="button" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i>
                <span class="text">Import User</span>
            </a>
        </div>
              

            <!-- Modal -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" name="file" required accept=".xlsx,.xls">
                    </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Import</button>
                    </div>
                </div>
                </form>
            </div>
            </div>

          
        <div class="table-data">
            <table id="example" class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>NIK</th>
                        <th>Phone Number</th>
                        <th>Gender</th>
                        <th>Sales Office</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tiger Nixon</td>
                        <td>123456789</td>
                        <td>0823456789</td>
                        <td>Male</td>
                        <td>Denpasar</td>
                        <td>
                            <a href="#" class="action-icon edit-icon">
                                <i class="fa-solid fa-file-pen" title="Edit"></i>
                              </a>
                              <a href="#" class="action-icon delete-icon"  onclick="hapus()">
                                <i class="fa-solid fa-trash" title="Delete"></i>
                              </a>                              
                        </td>
                    </tr>
                    
                </tbody>
            </table>
           
        </div>
    </main>
    <!-- MAIN -->   

@endsection
    