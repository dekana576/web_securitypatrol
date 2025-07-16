<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #000; padding: 6px; font-size: 12px; text-align: center; }
        svg { width: 100px; height: 100px; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Daftar Checkpoint & QR</h3>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Region</th>
            <th>Sales Office</th>
            <th>Checkpoint</th>
            <th>Kode</th>
            <th>QR</th>
        </tr>
    </thead>
    <tbody>
        @foreach($checkpoints as $cp)
        <tr>
            <td>{{ $cp['region'] }}</td>
            <td>{{ $cp['sales_office'] }}</td>
            <td>{{ $cp['checkpoint_name'] }}</td>
            <td>{{ $cp['checkpoint_code'] }}</td>
            <td>
                <img src="{{ $cp['qr_base64'] }}" width="100" height="100">
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
