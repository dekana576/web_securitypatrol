@extends('layout.user')
@section('title', 'Detail Feedback')

@section('content')
<main class="container py-4">
    <a href="{{ route('security.feedback') }}" class="btn btn-secondary mb-3">
        <i class="fa fa-arrow-left me-1"></i> Kembali
    </a>

    <div class="card shadow">
        <div class="card-body">
            <h5 class="fw-semibold">Feedback Admin</h5>
            <p class="text-muted">{{ $feedback->feedback_admin }}</p>
            <hr>
            <p><strong>Security:</strong> {{ $feedback->user->name }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($feedback->created_at)->format('d M Y H:i') }}</p>
            <p><strong>Region:</strong> {{ $feedback->region->name ?? '-' }}</p>
            <p><strong>Sales Office:</strong> {{ $feedback->salesOffice->sales_office_name ?? '-' }}</p>
            <p><strong>Checkpoint:</strong> {{ $feedback->checkpoint->checkpoint_name ?? '-' }}</p>
            <p><strong>Deskripsi:</strong> {{ $feedback->description }}</p>

            <h6 class="mt-3">Kriteria Checkpoint:</h6>
            @php
                $kriteriaData = json_decode($feedback->kriteria_result, true);
            @endphp
            <ul class="list-group">
                @foreach($kriteriaData as $id => $answer)
                    @php $label = \App\Models\CheckpointCriteria::find($id); @endphp
                    @if($label)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $label->nama_kriteria }}</span>
                            <span class="badge {{ str_contains(strtolower($answer), 'tidak') ? 'bg-danger' : 'bg-success' }} text-white">
                                {{ ucfirst($answer) }}
                            </span>
                        </li>
                    @endif
                @endforeach
            </ul>
            @if($feedback->status === 'submitted')
                <form action="{{ route('security.feedback.done', $feedback->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Tandai feedback ini sebagai selesai?')">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success">
                        <i class="fa fa-check me-1"></i> Tandai Selesai
                    </button>
                </form>
            @endif

        </div>
    </div>
</main>
@endsection
