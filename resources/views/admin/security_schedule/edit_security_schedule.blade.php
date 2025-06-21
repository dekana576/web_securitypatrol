@extends('layout.app')

@section('title','Update Jadwal Security')

@section('content')
<main>
    <div class="head-title mb-4">
        <div class="left">
            <h1>Update Jadwal Security</h1>
        </div>
    </div>

    <div class="card p-4">
        <h4 class="mb-4">Form Update Jadwal</h4>

        <form 
            action="{{ route('security_schedule.store') }}" 
            method="POST"
        >
            @csrf

            <input type="hidden" name="region_id" value="{{ $regionId }}">
            <input type="hidden" name="sales_office_id" value="{{ $salesOfficeId }}">

            @php
                $shiftNames = ['Pagi', 'Siang', 'Malam'];
            @endphp

            <div class="row">
                @foreach($jadwals as $index => $jadwal)
                <div class="col-md-4">
                    <div class="border p-3 mb-3 shadow-sm rounded bg-light">
                        <h5 class="text-center mb-3">Shift {{ $shiftNames[$index] ?? 'Shift ' . ($index + 1) }}</h5>

                        <input type="hidden" name="shift[]" value="{{ $jadwal->shift }}">

                        <div class="form-group mb-2">
                            <label>Jam Mulai</label>
                            <input type="time" name="jam_mulai[]" class="form-control" value="{{ $jadwal->jam_mulai }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Jam Berakhir</label>
                            <input type="time" name="jam_berakhir[]" class="form-control" value="{{ $jadwal->jam_berakhir }}" required>
                        </div>

                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $day)
                        <div class="form-group mb-2">
                            <label>{{ ucfirst($day) }}</label>
                            <select name="{{ $day }}[]" class="form-control">
                                <option value="">-- Pilih Security --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $jadwal->$day == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Update Jadwal</button>
                <a href="{{ route('security_schedule.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</main>
@endsection
