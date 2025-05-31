@extends('layout.app')

@section('title','Kriteria Checkpoint')

@section('content')

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Kriteria Checkpoint</h1>
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
            <a href="{{url("")}}" class="btn-tambah-kc">
                <i class="fa-solid fa-plus"></i>
                <span class="text">Tambah Kriteria Checkpoint</span>
            </a>
            <a href="#" class="btn-import-kc"  data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa-solid fa-file-import"></i>
                <span class="text">Import Kriteria Checkpoint</span>
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
                        <th>ID Checkpoint</th>
                        <th>Kriteria Name</th>
                        <th>Positive Answer</th>
                        <th>Negative Answer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
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
    