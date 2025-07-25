<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar QR Code Checkpoint</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .checkpoint-box {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            page-break-inside: avoid;
            text-align: center;
        }
        .checkpoint-header {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .checkpoint-header strong {
            display: block;
            font-size: 18px;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        .qr-code img {
            margin-top: 10px;
            max-width: 200px;
            height: auto;
        }
        .not-found {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Daftar QR Code Checkpoint</h2>

    @foreach ($checkpoints as $checkpoint)
        <div class="checkpoint-box">
            <div class="checkpoint-header">
                <strong>{{ $checkpoint->checkpoint_name }}</strong>
                <small>{{ $checkpoint->region->name ?? '-' }} - {{ $checkpoint->salesOffice->sales_office_name ?? '-' }}</small>
            </div>
            <div class="qr-code">
                @if ($checkpoint->qr_base64)
                    <img src="{{ $checkpoint->qr_base64 }}" alt="QR Code" style="max-width: 200px;">
                @else
                    <p class="not-found">QR code tidak ditemukan</p>
                @endif

            </div>
            <div>{{ $checkpoint->checkpoint_code }}</div>
        </div>
    @endforeach

</body>
</html>
