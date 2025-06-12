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

                    <input class="form-control @error('username') is-invalid @enderror" name="username" type="text" placeholder="Username" value="{{ old('username') }}" required>

                    <input name="password" type="password" placeholder="Password" required>

                    <div class="custome-checkbox">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                    </div>

                    <button type="submit">Login</button>
                    <hr>
                    <a href="#" class="forgot">Forgot Password?</a>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
