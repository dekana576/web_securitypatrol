@extends('layout.app')

@section('title','Tambah Checkpoint')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah Checkpoint</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="todo">
            <div class="head">
                <h3>Form Tambah Checkpoint</h3>
            </div>

            <form method="POST" action="{{ route('checkpoint.store') }}">
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
                    <label for="sales_office_id">Sales Office</label>
                    <select id="sales_office_id" name="sales_office_id" class="form-control" required>
                        <option value="">-- Pilih Sales Office --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="checkpoint_name">Checkpoint Name</label>
                    <input type="text" name="checkpoint_name" class="form-control" required>
                </div>

                <button type="submit">Tambah</button>
                <a href="{{ route('checkpoint.index') }}" class="btn-cancel">Batal</a>
            </form>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $('#region_id').on('change', function () {
        var regionId = $(this).val();
        $.get('/get-sales-offices/' + regionId, function (data) {
            $('#sales_office_id').empty().append('<option value="">-- Pilih Sales Office --</option>');
            $.each(data, function (index, so) {
                $('#sales_office_id').append('<option value="' + so.id + '">' + so.sales_office_name + '</option>');
            });
        });
    });
</script>
@endpush
