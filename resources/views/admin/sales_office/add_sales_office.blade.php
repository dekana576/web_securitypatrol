@extends('layout.app')

@section('title','Tambah Sales Office')

@section('content')

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah Sales Office</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="todo">
            <div class="head">
                <h3>Form Tambah Sales Office</h3>
            </div>

            <form action="{{ route('sales_office.store') }}" method="POST" class="form-input">
                @csrf

                <div class="form-group">
                    <label for="region_id">Region</label>
                    <select id="region_id" name="region_id" class="form-control" required>
                        <option value="">-- Pilih Region --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="sales_office_name">Nama Sales Office</label>
                    <input type="text" id="sales_office_name" name="sales_office_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="sales_office_address">Alamat Sales Office</label>
                    <textarea id="sales_office_address" name="sales_office_address" class="form-control" rows="3" required></textarea>
                </div>

                <button type="submit">Tambah</button>
                <a href="{{ route('sales_office.index') }}" class="btn-cancel">Batal</a>
            </form>
        </div>
    </div>
</main>

@endsection
