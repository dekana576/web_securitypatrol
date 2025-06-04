@extends('layout.app')

@section('title','Edit Sales Office')

@section('content')

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Sales Office</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="todo">
            <div class="head">
                <h3>Form Edit Sales Office</h3>
            </div>

            <form action="{{ route('sales_office.update', $salesOffice->id) }}" method="POST" class="form-input">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="region_id">Region</label>
                    <select id="region_id" name="region_id" class="form-control" required>
                        <option value="">-- Pilih Region --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}" {{ $salesOffice->region_id == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="sales_office_name">Nama Sales Office</label>
                    <input type="text" id="sales_office_name" name="sales_office_name"
                        class="form-control" required value="{{ old('sales_office_name', $salesOffice->sales_office_name) }}">
                </div>

                <div class="form-group">
                    <label for="sales_office_address">Alamat Sales Office</label>
                    <textarea id="sales_office_address" name="sales_office_address"
                        class="form-control" rows="3" required>{{ old('sales_office_address', $salesOffice->sales_office_address) }}</textarea>
                </div>

                <button type="submit">Simpan Perubahan</button>
                <a href="{{ route('sales_office.index') }}" class="btn-cancel">Batal</a>
            </form>
        </div>
    </div>
</main>

@endsection
