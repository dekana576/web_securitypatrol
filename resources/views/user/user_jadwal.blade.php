@extends('layout.user')

@section('title', 'Jadwal Saya')

@section('content')
<main class="px-3 px-md-5 py-4">
    <div class="d-flex justify-content-between align-items-center">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 fw-bold mb-0">Jadwal Patroli Saya</h1>
        </div>
        <a href="{{ route('user.home') }}" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle shadow-sm">
            <thead class="table-primary text-center">
                <tr>
                    <th style="width: 20%">Tanggal</th>
                    <th style="width: 20%">Hari</th>
                    <th style="width: 20%">Shift</th>
                    <th style="width: 20%">Jam Mulai</th>
                    <th style="width: 20%">Jam Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedule as $item)
                    <tr class="{{ $item['off'] ? 'table-danger text-center fw-semibold' : 'text-center' }}">
                        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d M Y') }}</td>
                        <td>{{ $item['hari'] }}</td>
                        <td>
                            {{ $item['off'] ? 'Day Off' : $item['shift'] }}
                        </td>
                        <td>{{ $item['jam_mulai'] }}</td>
                        <td>{{ $item['jam_selesai'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada jadwal.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection
