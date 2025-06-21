@extends('layout.app')

@section('title','Jadwal Security')

@section('content')
<main>
    <div class="head-title mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Jadwal Security</h1>
        </div>
    </div>

    <!-- Filter Sales Office -->
    <div class="card p-4 mb-4 shadow-sm">
        <form method="GET" action="{{ route('security_schedule.index') }}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="region_id" class="form-label">Region</label>
                    <select name="region_id" id="region_id" class="form-control" required onchange="this.form.submit()">
                        <option value="">-- Pilih Region --</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                                {{ $region->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="sales_office_id" class="form-label">Sales Office</label>
                    <select name="sales_office_id" id="sales_office_id" class="form-control" required>
                        <option value="">-- Pilih Sales Office --</option>
                        @foreach ($salesOffices as $office)
                            <option value="{{ $office->id }}" {{ request('sales_office_id') == $office->id ? 'selected' : '' }}>
                                {{ $office->sales_office_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(request('region_id'))
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Lihat Jadwal</button>
                </div>
            @endif
        </form>
    </div>

    @if ($selectedOffice)
        <div class="d-flex justify-content-end mb-3">
            @if ($selectedOffice->security_schedule->count() < 3)
                <a href="{{ route('security_schedule.create', ['sales_office_id' => $selectedOffice->id]) }}" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Jadwal
                </a>
            @else
                <a href="{{ route('security_schedule.edit', ['sales_office_id' => $selectedOffice->id]) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Update Jadwal
                </a>
            @endif
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Shift</th>
                                <th>Jam Mulai</th>
                                <th>Jam Berakhir</th>
                                <th>Senin</th>
                                <th>Selasa</th>
                                <th>Rabu</th>
                                <th>Kamis</th>
                                <th>Jumat</th>
                                <th>Sabtu</th>
                                <th>Minggu</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @foreach ($jadwals as $jadwal)
                                <tr>
                                    <td>{{ $jadwal->shift }}</td>
                                    <td>{{ $jadwal->jam_mulai }}</td>
                                    <td>{{ $jadwal->jam_berakhir }}</td>
                                    <td>{{ $jadwal->seninUser?->name ?? '-' }}</td>
                                    <td>{{ $jadwal->selasaUser?->name ?? '-' }}</td>
                                    <td>{{ $jadwal->rabuUser?->name ?? '-' }}</td>
                                    <td>{{ $jadwal->kamisUser?->name ?? '-' }}</td>
                                    <td>{{ $jadwal->jumatUser?->name ?? '-' }}</td>
                                    <td>{{ $jadwal->sabtuUser?->name ?? '-' }}</td>
                                    <td>{{ $jadwal->mingguUser?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</main>
@endsection
