@extends('layouts.app')

@section('title', 'Dasbor')

@section('content')
<div class="card"><h1>Penawaran Saya</h1></div>

@forelse ($bids as $b)
    <div class="card">
        <h2>
            <a href="{{ route('projects.show', $b->project) }}">{{ $b->project->judul }}</a>
            @include('partials.status', ['status' => $b->project->status])
            @if ($b->is_winner)<span class="badge open">Pemenang</span>@endif
        </h2>
        <p class="muted">Klien: {{ $b->project->client->nama }} | Harga: Rp {{ number_format($b->harga, 0, ',', '.') }} | Durasi: {{ $b->durasi }} hari | Batas: {{ $b->project->batas_lelang->format('Y-m-d') }}</p>
    </div>
@empty
    <div class="card"><p>Anda belum mengajukan penawaran.</p></div>
@endforelse
@endsection
