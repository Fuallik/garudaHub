@extends('layouts.app')

@section('title', $project->judul)

@section('content')
<div class="card">
    <h1>{{ $project->judul }} @include('partials.status', ['status' => $project->status])</h1>
    <p class="muted">Kategori: {{ $project->kategori }} | Klien: {{ $project->client->nama }} | Budget: {{ $project->budgetRange() }} | Batas lelang: {{ $project->batas_lelang->format('Y-m-d') }}</p>
    <p>{!! nl2br(e($project->deskripsi)) !!}</p>
    {{-- OWNER_ACTIONS --}}
</div>

<div class="card">
    <h2>Penawaran ({{ $project->bids->count() }})</h2>
    @if ($project->bids->isEmpty())
        <p>Belum ada penawaran.</p>
    @else
        <table>
            <tr><th>Developer</th><th>Harga</th><th>Durasi</th><th>Proposal</th><th></th></tr>
            @foreach ($project->bids as $bid)
                <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                    <td>{{ $bid->developer->nama }}@if ($bid->is_winner) <span class="badge win">Pemenang</span>@endif</td>
                    <td>Rp {{ number_format($bid->harga, 0, ',', '.') }}</td>
                    <td>{{ $bid->durasi }} hari</td>
                    <td>{!! nl2br(e($bid->proposal)) !!}</td>
                    <td>{{-- PICK_FORM --}}</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>

{{-- BID_FORM --}}
@endsection
