<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akun Anda Berhasil Dibuat</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        h2 {
            color: #2b2e4a;
            font-size: 24px;
            margin-top: 0;
        }

        p {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
        }

        ul {
            background-color: #f9f9fb;
            padding: 15px;
            border-radius: 6px;
            list-style: none;
            font-size: 14px;
            color: #333;
        }

        ul li {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        ul li:last-child {
            border-bottom: none;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #888;
            text-align: center;
        }

        .footer strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">

        <h2>Halo, {{ $user->name }}</h2>
        <p>Akun Anda telah berhasil dibuat. Berikut adalah informasi login Anda:</p>

        <ul>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Password:</strong> {{ $plainPassword }}</li>
        </ul>

        <p>Silakan login dan segera <strong>ganti password</strong> Anda demi keamanan akun.</p>

        <p class="footer">
            Terima kasih,<br>
            <strong>Tim Keamanan AM Patrol</strong>
        </p>
    </div>
</body>
</html>
