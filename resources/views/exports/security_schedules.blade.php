<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Patroli - {{ $region->name }} / {{ $salesOffice->sales_office_name }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 0;
        }
        h3 {
            font-weight: normal;
            margin-top: 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            text-align: center;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px;
        }
        .bg-sunday {
            color: red;
        }
    </style>
</head>
<body>

        <h1 style="text-align: center; margin-bottom: 50px">Jadwal Patroli Security</h1>

    <h3>
        Region: {{ $region->name }} <br>
        Sales Office: {{ $salesOffice->sales_office_name }} <br>
        Bulan: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
    </h3>

    <table>
    <thead>
        <tr>
            <th rowspan="2">No</th> <!-- Tambahan -->
            <th rowspan="2">Nama Security</th>
            @foreach($dates as $date)
                @php $isSunday = $date->locale('id')->translatedFormat('D') === 'Min'; @endphp
                <th class="{{ $isSunday ? 'bg-sunday' : '' }}">
                    {{ $date->format('d') }}
                </th>
            @endforeach
        </tr>
        <tr>
            @foreach($dates as $date)
                @php $isSunday = $date->locale('id')->translatedFormat('D') === 'Min'; @endphp
                <th class="{{ $isSunday ? 'bg-sunday' : '' }}">
                    {{ $date->locale('id')->translatedFormat('D') }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp <!-- Tambahkan counter -->
        @foreach($dataPerSecurity as $securityName => $dataHarian)
            <tr>
                <td>{{ $no++ }}</td> <!-- Kolom nomor urut -->
                <td style="text-align: left">{{ $securityName }}</td>
                @foreach($dates as $date)
                    @php $val = $dataHarian[$date->format('Y-m-d')] ?? ''; @endphp
                    <td>
                        @switch($val)
                            @case('p')
                                <span style="display:inline-block; padding:2px 6px; font-size:9px; font-weight:bold; border-radius:4px;  color:black;">
                                    P
                                </span>
                                @break

                            @case('s')
                                <span style="display:inline-block; padding:2px 6px; font-size:9px; font-weight:bold; border-radius:4px;  color:black;">
                                    S
                                </span>
                                @break

                            @case('m')
                                <span style="display:inline-block; padding:2px 6px; font-size:9px; font-weight:bold; border-radius:4px;  color:black;">
                                    M
                                </span>
                                @break

                            @case('p8')
                                <span style="display:inline-block; padding:2px 6px; font-size:9px; font-weight:bold; border-radius:4px; background-color:#ffc107; color:black;">
                                    P8
                                </span>
                                @break

                            @default
                                <span style="color:#dc3545;">OFF</span>
                        @endswitch
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>