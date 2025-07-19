<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <!-- My CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="login-image">
                <img src="{{ asset('img/Data_security_26.jpg') }}" alt="Login Illustration">
            </div>
            <div class="login-form">
                <h2>Welcome</h2>

                <form method="POST" action="{{ route('login.index') }}">
                    @csrf

                    <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required>

                    <div class="position-relative mt-3">
                        <input id="password" class="form-control @error('password') is-invalid @enderror" name="password" type="password" placeholder="Password" required>
                        <span id="togglePassword" class="position-absolute" style="top: 50%; right: 20px; transform: translateY(-50%); cursor: pointer;">
                            <i class="fa fa-eye text-secondary"></i>
                        </span>
                    </div>

                    <div class="custome-checkbox mt-3">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                    </div>

                    <button class="btn-login mt-3" type="submit">Login</button>
                    <hr>
                    <a href="#" class="forgot">Forgot Password?</a>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery (required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toastr Config -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if($errors->has('login_gagal'))
            toastr.error("{{ $errors->first('login_gagal') }}");
        @endif
    </script>

    <!-- Show/Hide Password -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.innerHTML = type === 'password'
                ? '<i class="fa fa-eye text-secondary"></i>'
                : '<i class="fa fa-eye-slash text-secondary"></i>';
        });
    </script>
</body>
</html>

