@extends('layout.app')

@section('title','Tambah user')

@section('content')

    <!-- MAIN -->
    <main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah Region</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="todo">
            <div class="head">
                <h3>Form Tambah Region</h3>
            </div>

            <form action="{{ route('region.store') }}" method="POST" class="form-input">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Region</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <button type="submit">Tambah</button>
                <a href="{{ route('region.index') }}" class="btn-cancel">Batal</a>
            </form>
        </div>
    </div>
</main>
    <!-- MAIN -->   

@endsection
    