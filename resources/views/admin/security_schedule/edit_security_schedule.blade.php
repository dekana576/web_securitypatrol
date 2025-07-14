@extends('layout.app')

@section('title', 'Edit Jadwal Bulanan')

@section('content')
<main class="container py-4">
    <h4 class="mb-3">Edit Jadwal Patroli Bulanan</h4>

    <form method="POST" action="{{ route('security_schedule.update', [$region->id, $salesOffice->id, $bulan, $tahun]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="bulan" class="form-label">Bulan</label>
            <input type="month" id="bulan" class="form-control" value="{{ $tahun }}-{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}" disabled>
        </div>

        <div id="jadwal-container"></div>

        <button type="submit" class="btn btn-success mt-3">Update Jadwal</button>
    </form>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById('jadwal-container');
    const shiftStyles = {
        'Pagi': 'background-color: #dceeff',
        'Siang': 'background-color: #fff9dc',
        'Malam': 'background-color: #c7c7c7',
        'Non-Shift': 'background-color: #ffd6e6',
    };

    const jadwalData = @json($jadwal);
    const users = @json($users);
    
    const tanggalMap = {};

    // Organize by tanggal + shift
    jadwalData.forEach(item => {
        const key = `${item.tanggal}-${item.shift.replace(/\s/g, '')}`;
        tanggalMap[key] = item;
    });

    const bulan = "{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}";
    const tahun = "{{ $tahun }}";
    const daysInMonth = new Date(tahun, bulan, 0).getDate();

    const shifts = [
        { name: 'Pagi', jam_mulai: '06:00', jam_selesai: '14:00' },
        { name: 'Siang', jam_mulai: '14:00', jam_selesai: '22:00' },
        { name: 'Malam', jam_mulai: '22:00', jam_selesai: '06:00' },
        { name: 'Non-Shift', jam_mulai: '08:00', jam_selesai: '16:15' },
    ];

    for (let d = 1; d <= daysInMonth; d++) {
        const date = new Date(`${tahun}-${bulan}-${String(d).padStart(2, '0')}`);
        const tanggalStr = date.toISOString().split('T')[0];
        const namaHari = date.toLocaleDateString('id-ID', { weekday: 'long' });

        container.innerHTML += `<h5 class="mt-4 mb-2">${namaHari}, ${tanggalStr}</h5>`;

        shifts.forEach(shift => {
            const key = `${tanggalStr}-${shift.name.replace(/\s/g, '')}`;
            const data = tanggalMap[key] || {};

            container.innerHTML += `
                <div class="border p-3 mb-2 rounded" style="${shiftStyles[shift.name]}">
                    <strong>Shift: ${shift.name}</strong>
                    <input type="hidden" name="data[${key}][tanggal]" value="${tanggalStr}">
                    <input type="hidden" name="data[${key}][shift]" value="${shift.name}">
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <label>Jam Mulai</label>
                            <input type="time" class="form-control" name="data[${key}][jam_mulai]" value="${data.jam_mulai || shift.jam_mulai}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Jam Selesai</label>
                            <input type="time" class="form-control" name="data[${key}][jam_selesai]" value="${data.jam_selesai || shift.jam_selesai}" required>
                        </div>
                        <div class="col-md-3">
                            <label>Security 1</label>
                            <select name="data[${key}][security1]" class="form-select">
                                <option value="">-</option>
                                ${users.map(user => `
                                    <option value="${user.id}" ${data.security_1_id == user.id ? 'selected' : ''}>${user.name}</option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Security 2</label>
                            <select name="data[${key}][security2]" class="form-select">
                                <option value="">-</option>
                                ${users.map(user => `
                                    <option value="${user.id}" ${data.security_2_id == user.id ? 'selected' : ''}>${user.name}</option>
                                `).join('')}
                            </select>
                        </div>
                    </div>
                </div>
            `;
        });
    }
});
</script>
@endpush
