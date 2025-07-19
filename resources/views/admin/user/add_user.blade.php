@extends('layout.app')

@section('title','Tambah user')

@section('content')

    <!-- MAIN -->
    <main>
        <div class="head-title">
            <div class="left">
                <h1>User</h1>
                
            </div>
        </div>

        <div class="table-data">
           
            <div class="todo">
                <div class="head">
                    <h3>Form Tambah User</h3>
                </div>
                <form action="{{ route('user.store') }}" method="POST" class="form-input">
                  @csrf
  
                  <div class="form-group">
                      <label for="name">Name</label>
                      <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                      @error('name')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
  
                  <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                      @error('username')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
  
                  <div class="form-group">
                      <label for="nik">NIK</label>
                      <input type="number" id="nik" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" required>
                      @error('nik')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
  
                  <div class="form-group">
                      <label for="phone_number">Phone Number</label>
                      <input type="number" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" required>
                      @error('phone_number')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
  
                  <div class="form-group">
                      <label for="gender">Gender</label>
                      <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror" required>
                          <option value="">-- Pilih Gender --</option>
                          <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                          <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                      </select>
                      @error('gender')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
  
                  <div class="form-group">
                      <label for="role">Role</label>
                      <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                          <option value="security" {{ old('role') == 'security' ? 'selected' : '' }}>Security</option>
                          <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                      </select>
                      @error('role')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>

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
                      <label for="email">Email</label>
                      <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                      @error('email')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                  </div>
  
                  <div class="form-group position-relative">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        <span class="toggle-password" onclick="togglePassword('password', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group position-relative">
                    <label for="password_confirmation">Retype Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>
                
  
                  <button type="submit" class="btn btn-primary">Tambah</button>
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

    //Show hide password
    function togglePassword(fieldId, icon) {
        const input = document.getElementById(fieldId);
        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';
        icon.innerHTML = isPassword
            ? '<i class="fa fa-eye-slash"></i>'
            : '<i class="fa fa-eye"></i>';
    }
</script>

@endpush
    