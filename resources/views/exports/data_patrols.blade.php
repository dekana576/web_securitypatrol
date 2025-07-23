<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Patrol</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
        }
        .filter-info {
            margin-bottom: 10px;
        }
        .filter-info p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <h1>Laporan Data Patrol</h1>

    <div class="filter-info">
        <p><strong>Region:</strong> {{ request('region_id') ? \App\Models\Region::find(request('region_id'))->name : 'Semua' }}</p>
        <p><strong>Sales Office:</strong> {{ request('sales_office_id') ? \App\Models\SalesOffice::find(request('sales_office_id'))->sales_office_name : 'Semua' }}</p>
        <p><strong>Tanggal:</strong>
            @php
                $tgl = [];
                if(request('day')) $tgl[] = str_pad(request('day'), 2, '0', STR_PAD_LEFT);
                if(request('month')) $tgl[] = str_pad(request('month'), 2, '0', STR_PAD_LEFT);
                if(request('year')) $tgl[] = request('year');
                echo count($tgl) ? implode('-', $tgl) : 'Semua';
            @endphp
        </p>
        <p><strong>Shift:</strong> {{ request('shift') ? ucfirst(request('shift')) : 'Semua' }}</p>
        <p><strong>Status:</strong> {{ request('status') ? ucfirst(request('status')) : 'Semua' }}</p>
        <p><strong>Kriteria:</strong>
            @switch(request('kriteria'))
                @case('aman') Aman @break
                @case('tidak_aman') Tidak Aman @break
                @default Semua
            @endswitch
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Region</th>
                <th>Sales Office</th>
                <th>Checkpoint</th>
                <th>Kriteria</th>
                <th>Security</th>
                <th>Status</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dataPatrols as $i => $dp)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($dp->tanggal)->format('d M Y') }}</td>
                    <td>{{ ucfirst($dp->shift) }}</td>
                    <td>{{ $dp->region->name ?? '-' }}</td>
                    <td>{{ $dp->salesOffice->sales_office_name ?? '-' }}</td>
                    <td>{{ $dp->checkpoint->checkpoint_name ?? '-' }}</td>
                    <td>
                        @php
                            $hasil = json_decode($dp->kriteria_result, true);
                            $isNegative = collect($hasil)->filter(fn($v) => stripos($v, 'tidak') !== false || stripos($v, 'negative') !== false)->isNotEmpty();
                            echo $isNegative ? 'Tidak Aman' : 'Aman';
                        @endphp
                    </td>
                    <td>{{ $dp->user->name ?? '-' }}</td>
                    <td>{{ ucfirst($dp->status) }}</td>
                    <td>{{ $dp->deskripsi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
