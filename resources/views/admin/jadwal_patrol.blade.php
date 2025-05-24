@extends('layout.app')

@section('title','Jadwal Patrol')

@section('content')

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Jadwal Patrol</h1>
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
            <a href="{{url("")}}" class="btn-tambah">
                <i class="fa-solid fa-plus"></i>
                <span class="text">Tambah Jadwal</span>
            </a>
            <a href="#" class="btn-import" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i>
                <span class="text">Import Jadwal</span>
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
                        <th>Senin</th>
                        <th>Selasa</th>
                        <th>Rabu</th>
                        <th>Kamis</th>
                        <th>Jumat</th>
                        <th>Sabtu</th>
                        <th>Shift</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
    