@extends('layout.app')

@section('title', 'Buat Jadwal Bulanan')

@section('content')
<main class="container py-4">
    <h4 class="mb-3">Buat Jadwal Patroli Bulanan</h4>

    <form method="POST" action="{{ route('security_schedule.store') }}">
        @csrf
        <div class="mb-3">
            <label for="bulan" class="form-label">Pilih Bulan</label>
            <input type="month" name="bulan" id="bulan" class="form-control" required>
        </div>

        <div id="jadwal-container"></div>

        <button type="submit" class="btn btn-success mt-3">Simpan Jadwal</button>
    </form>
</main>
@endsection

@push('scripts')
<script>
    const existingMonths = @json($existingMonths);

    function getDayName(dateStr) {
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dayIndex = new Date(dateStr).getDay();
        return days[dayIndex];
    }

    document.getElementById('bulan').addEventListener('change', function () {
        const bulan = this.value;
        const container = document.getElementById('jadwal-container');
        container.innerHTML = '';

        if (!bulan) return;

        if (existingMonths.includes(bulan)) {
            alert('Jadwal untuk bulan ini sudah ada. Silakan pilih bulan lain.');
            this.value = '';
            return;
        }

        const [year, month] = bulan.split('-');
        const daysInMonth = new Date(year, month, 0).getDate();
        const shifts = [
            { name: 'Pagi', jam_mulai: '06:00', jam_selesai: '14:00', bg: '#e3f2fd' },      // biru muda
            { name: 'Siang', jam_mulai: '14:00', jam_selesai: '22:00', bg: '#fff9c4' },     // kuning muda
            { name: 'Malam', jam_mulai: '22:00', jam_selesai: '06:00', bg: '#c7c7c7' },     // abu gelap
            { name: 'Non-Shift', jam_mulai: '08:00', jam_selesai: '16:15', bg: '#f8bbd0' }  // merah muda
        ];

        for (let d = 1; d <= daysInMonth; d++) {
            const tgl = `${year}-${month.padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const hari = getDayName(tgl);

            container.innerHTML += `<h5 class="mt-4 mb-2">Tanggal ${tgl} (${hari})</h5>`;

            shifts.forEach((shift) => {
                const key = `${tgl}-${shift.name.replace(/\s/g, '')}`;
                container.innerHTML += `
                    <div class="border p-3 mb-2 rounded" style="background-color: ${shift.bg}">
                        <strong>Shift: ${shift.name}</strong>
                        <input type="hidden" name="data[${key}][tanggal]" value="${tgl}">
                        <input type="hidden" name="data[${key}][shift]" value="${shift.name}">
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <label>Jam Mulai</label>
                                <input type="time" class="form-control" name="data[${key}][jam_mulai]" value="${shift.jam_mulai}" required>
                            </div>
                            <div class="col-md-3">
                                <label>Jam Selesai</label>
                                <input type="time" class="form-control" name="data[${key}][jam_selesai]" value="${shift.jam_selesai}" required>
                            </div>
                            <div class="col-md-3">
                                <label>Security 1</label>
                                <select name="data[${key}][security1]" class="form-select">
                                    <option value="">-</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Security 2</label>
                                <select name="data[${key}][security2]" class="form-select">
                                    <option value="">-</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
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
