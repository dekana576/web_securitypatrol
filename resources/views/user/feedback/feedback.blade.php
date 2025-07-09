@extends('layout.user')
@section('title', 'Feedback Admin')

@section('content')
<main class="container py-4">
    <div class="d-flex justify-content-between">

        <h4 class="mb-4 fw-semibold">Riwayat Feedback</h4>
        {{-- Hapus baris ini jika tidak dibutuhkan --}}
        {{-- <p>Sales Office ID: {{ auth()->user()->sales_office_id }}</p> --}}
        <a href="{{ route('user.home') }}" class="btn btn-secondary mb-3">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
        
    @forelse($feedbacks as $item)
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $item->user->name }}</strong><br>
                    <small class="badge bg-secondary text-white">{{ $item->checkpoint->checkpoint_name }}</small>
                    @if($item->status === 'submitted')
                        <span class="badge bg-warning text-white">Submitted</span>
                    @elseif($item->status === 'approved')
                        <span class="badge bg-primary text-white">Approved</span>
                    @elseif($item->status === 'done')
                        <span class="badge bg-success text-white">Done</span>
                    @endif <br>
                    <small>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}</small>
                    

                </div>
                <a href="{{ route('security.feedback.show', $item->id) }}" class="btn btn-sm btn-outline-info">
                    Lihat Feedback
                </a>
            </div>
        </div>
    @empty
        <p class="text-muted">Belum ada feedback dari admin.</p>
    @endforelse
</main>
@endsection
