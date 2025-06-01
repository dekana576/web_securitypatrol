@extends('layout.app')

@section('title','Edit Region')

@section('content')

<!-- MAIN -->
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Region</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="todo">
            <div class="head">
                <h3>Form Edit Region</h3>
            </div>

            <form action="{{ route('region.update', $region->id) }}" method="POST" class="form-input">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Region</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $region->name }}" required>
                </div>

                <button type="submit">Update</button>
                <a href="{{ route('region.index') }}" class="btn-cancel">Batal</a>
            </form>
        </div>
    </div>
</main>

@endsection
