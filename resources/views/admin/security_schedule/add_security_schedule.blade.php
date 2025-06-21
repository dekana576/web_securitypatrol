@extends('layout.app')

@section('title','Form Jadwal Security')

@section('content')
<main>
    <div class="head-title mb-4">
        <div class="left">
            <h1>{{ $isEdit ? 'Update' : 'Tambah' }} Jadwal Security</h1>
        </div>
    </div>

    <div class="card p-4">
        <h4 class="mb-4">Form {{ $isEdit ? 'Update' : 'Tambah' }} Jadwal</h4>

        <form 
            action="{{ $isEdit ? route('security_schedule.update', ['sales_office_id' => $salesOfficeId]) : route('security_schedule.store') }}" 
            method="POST"
        >
            @csrf

            <input type="hidden" name="sales_office_id" value="{{ $salesOfficeId }}">
            <input type="hidden" name="region_id" value="{{ $regionId }}">

            @php
                $shiftNames = ['Pagi', 'Siang', 'Malam'];
            @endphp

            <div class="row">
                @for ($i = 0; $i < 3; $i++)
                    <div class="col-md-4">
                        <div class="border p-3 mb-3 shadow-sm rounded bg-light">
                            <h5 class="text-center mb-3">Shift {{ $shiftNames[$i] }}</h5>

                            <input type="hidden" name="shift[]" value="{{ $shiftNames[$i] }}">

                            <div class="form-group mb-2">
                                <label>Jam Mulai</label>
                                <input type="time" name="jam_mulai[]" class="form-control" 
                                    value="{{ old("jam_mulai.$i", $jadwals[$i]->jam_mulai ?? '') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Jam Berakhir</label>
                                <input type="time" name="jam_berakhir[]" class="form-control" 
                                    value="{{ old("jam_berakhir.$i", $jadwals[$i]->jam_berakhir ?? '') }}" required>
                            </div>

                            @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $day)
                                <div class="form-group mb-2">
                                    <label>{{ ucfirst($day) }}</label>
                                    <select name="{{ $day }}[]" class="form-control">
                                        <option value="">-- Pilih Security --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ (old("$day.$i", $jadwals[$i]->$day ?? '') == $user->id) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">
                    {{ $isEdit ? 'Update' : 'Tambah' }} Jadwal
                </button>
                <a href="{{ route('security_schedule.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</main>
@endsection
