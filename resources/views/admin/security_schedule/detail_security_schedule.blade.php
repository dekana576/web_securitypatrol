@extends('layout.app')

@section('title', 'Detail Jadwal Patroli')

@section('content')
<main>
    <div class="head-title mb-4">
        <div class="left">
            <h1>Detail Jadwal Patroli</h1>
            <h6>
                Region: {{ $region->name }} /
                Sales Office: {{ \App\Models\SalesOffice::find($salesOfficeId)->sales_office_name }} <br>
                Bulan: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
            </h6>
        </div>

        <div class="right mt-3">
            <a href="{{ route('security_schedule.edit', [$region->id, $salesOfficeId, $bulan, $tahun]) }}" class="btn btn-primary">
                <i class="fa fa-edit me-1"></i> Edit Jadwal
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th rowspan="2">Nama Security</th>
                    @foreach($dates as $date)
                        @php $isSunday = $date->locale('id')->translatedFormat('D') === 'Min'; @endphp
                        <th class="{{ $isSunday ? 'bg-danger text-white' : '' }}">
                            {{ $date->format('d') }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($dates as $date)
                        @php $isSunday = $date->locale('id')->translatedFormat('D') === 'Min'; @endphp
                        <th class="{{ $isSunday ? 'bg-danger text-white' : '' }}">
                            {{ $date->locale('id')->translatedFormat('D') }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($dataPerSecurity as $securityName => $dataHarian)
                    <tr>
                        <td class="text-start">{{ $securityName }}</td>
                        @foreach($dates as $date)
                            <td>
                                @php
                                    $val = $dataHarian[$date->format('Y-m-d')] ?? '';
                                @endphp
                                @if($val == 'p')
                                    <span class="badge bg-success">P</span>
                                @elseif($val == 's')
                                    <span class="badge text-dark" style="background-color: yellow">S</span>
                                @elseif($val == 'm')
                                    <span class="badge bg-dark">M</span>
                                @elseif($val == 'p8')
                                    <span class="badge bg-warning text-dark">P8</span>
                                @else
                                    off
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('security_schedule.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</main>
@endsection
