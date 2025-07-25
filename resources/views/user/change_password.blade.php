@extends('layout.user') {{-- Ganti jika kamu pakai layout berbeda --}}

@section('title', 'Ubah Password')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Ubah Password</h4>

    <form action="{{ route('security.update_password') }}" method="POST">
        @csrf
        <div class="mb-3 position-relative">
            <label for="current_password" class="form-label">Password Lama</label>
            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            <button type="button" class="btn btn-sm  toggle-password" data-target="current_password" style="position: absolute; right: 10px; top: 35px;">
                <i class="fa fa-eye"></i>
            </button>
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3 position-relative">
            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
            <button type="button" class="btn btn-sm  toggle-password" data-target="new_password" style="position: absolute; right: 10px; top: 35px;">
                <i class="fa fa-eye"></i>
            </button>
            @error('new_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3 position-relative">
            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
            <button type="button" class="btn btn-sm  toggle-password" data-target="new_password_confirmation" style="position: absolute; right: 10px; top: 35px;">
                <i class="fa fa-eye"></i>
            </button>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('user.home') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left me-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const icon = this.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });
</script>
@endpush