@extends('layouts.app')

@section('title', 'Dasbor')

@section('content')
<div class="card">
    <h1>Proyek Saya</h1>
    <p><a href="{{ route('projects.create') }}">Pasang proyek baru</a></p>
</div>

@forelse ($projects as $p)
    <div class="card">
        <h2><a href="{{ route('projects.show', $p) }}">{{ $p->judul }}</a> @include('partials.status', ['status' => $p->status])</h2>
        <p class="muted">Budget: {{ $p->budgetRange() }} | Batas: {{ $p->batas_lelang->format('Y-m-d') }} | {{ $p->bids_count }} penawaran</p>
    </div>
@empty
    <div class="card"><p>Anda belum memasang proyek.</p></div>
@endforelse
@endsection
