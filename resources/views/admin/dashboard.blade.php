@extends('layout.app')

@section('title','Dashboard')

@section('content')

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Dashboard</h1>
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

        <ul class="box-info">
            <li>
                <i class="fa-solid fa-user"></i>
                <span class="text">
                    <p>Total User</p>
                    <h3>10</h3>
                </span>
            </li>
            <li>
                <i class="fa-solid fa-file"></i>
                <span class="text">
                    <p>Total Laporan</p>
                    <h3>1</h3>
                </span>
            </li>
            <li>
                <i class="fa-solid fa-file-circle-check"></i>
                <span class="text">
                    <p>Total Laporan Belum Approve</p>
                    <h3>0</h3>
                </span>
            </li>
        </ul>

        <!-- Filter data -->
        <div class="d-flex align-items-center gap-2 mt-5 filter-wrapper">
            <label for="date-filter" class="form-label mb-0">Filter data :</label>
            <div class="position-relative rounded-pill bg-light px-3 py-2 date-input-wrapper">
              <input type="date" id="date-filter" class="form-control border-0 bg-transparent p-0 ps-1"placeholder="dd/mm/yyyy" style="width: 110px; font-size: 14px;">
            </div>
          </div>



        <div class="table-data">
           
            {{-- <div class="todo">
                <div class="head">
                    <h3></h3>
                </div>
               
            </div> --}}
        </div>
    </main>
    <!-- MAIN -->   

@endsection
    