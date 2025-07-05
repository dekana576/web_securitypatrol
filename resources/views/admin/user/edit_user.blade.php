@extends('layout.app')

@section('title','Edit User')

@section('content')

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit User</h1>
            </div>
        </div>

        <div class="table-data">
            <div class="todo">
                <div class="head">
                    <h3>Form Edit User</h3>
                </div>

                <form action="{{ route('user.update', $user->id) }}" method="POST" class="form-input">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input type="number" id="nik" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="number" id="phone_number" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="">-- Pilih Gender --</option>
                            <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="security" {{ $user->role == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="region_id">Region</label>
                        <select name="region_id" id="region_id" class="form-control" required>
                            <option value="">-- Pilih Region --</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}" {{ $region->id == $user->region_id ? 'selected' : '' }}>
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
                                <option value="{{ $so->id }}" {{ $so->id == $user->sales_office_id ? 'selected' : '' }}>
                                    {{ $so->sales_office_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('user.index') }}" class="btn-cancel">Batal</a>
                </form>
            </div>
        </div>
    </main>
    <!-- MAIN -->
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
