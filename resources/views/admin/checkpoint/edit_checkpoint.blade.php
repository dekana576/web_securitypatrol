@extends('layout.app')

@section('title','Edit Checkpoint')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Edit Checkpoint</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="todo">
            <div class="head">
                <h3>Form Edit Checkpoint</h3>
            </div>

            <form action="{{ route('checkpoint.update', $checkpoint->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="region_id">Region</label>
                    <select name="region_id" id="region_id" class="form-control" required>
                        <option value="">-- Pilih Region --</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ $region->id == $checkpoint->region_id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="sales_office_id">Sales Office</label>
                    <select name="sales_office_id" id="sales_office_id" class="form-control" required>
                        <option value="">-- Pilih Sales Office --</option>
                        @foreach ($salesOffices as $so)
                            <option value="{{ $so->id }}" {{ $so->id == $checkpoint->sales_office_id ? 'selected' : '' }}>
                                {{ $so->sales_office_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="checkpoint_name">Checkpoint Name</label>
                    <input type="text" name="checkpoint_name" id="checkpoint_name" value="{{ $checkpoint->checkpoint_name }}" class="form-control" required>
                </div>

                <button type="submit">Update</button>
                <a href="{{ route('checkpoint.index') }}" class="btn-cancel">Batal</a>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $('#region_id').on('change', function () {
        const regionId = $(this).val();
        $.get('/get-sales-offices/' + regionId, function (data) {
            $('#sales_office_id').empty().append('<option value="">-- Pilih Sales Office --</option>');
            data.forEach(so => {
                $('#sales_office_id').append(`<option value="${so.id}">${so.sales_office_name}</option>`);
            });
        });
    });
</script>
@endpush
