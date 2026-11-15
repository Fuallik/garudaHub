@extends('layouts.app')

@section('title', $project->judul)

@section('content')
<div class="card">
    <h1>{{ $project->judul }} @include('partials.status', ['status' => $project->status])</h1>
    <p class="muted">Kategori: {{ $project->kategori }} | Klien: {{ $project->client->nama }} | Budget: {{ $project->budgetRange() }} | Batas lelang: {{ $project->batas_lelang->format('Y-m-d') }}</p>
    <p>{!! nl2br(e($project->deskripsi)) !!}</p>
    @can('update', $project)
        <a class="btn sm" href="{{ route('projects.edit', $project) }}">Ubah</a>
    @endcan
    @can('delete', $project)
        <form class="inline" method="post" action="{{ route('projects.destroy', $project) }}" data-confirm="Hapus proyek beserta penawarannya?">
            @csrf
            @method('DELETE')
            <button class="sm danger" type="submit">Hapus</button>
        </form>
    @endcan
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
                    <td>@can('update', $project)
                            <form method="post" action="{{ route('projects.bids.pick', [$project, $bid]) }}" data-confirm="Pilih penawaran ini sebagai pemenang?">
                                @csrf
                                <button class="sm">Pilih pemenang</button>
                            </form>
                        @endcan</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>

@can('bid', $project)
    <div class="card">
        <h2>Ajukan Penawaran</h2>
        <form method="post" action="{{ route('projects.bids.store', $project) }}">
            @csrf
            <div class="row">
                <div><label>Harga (Rp)</label><input type="number" name="harga" min="1" required value="{{ old('harga') }}"></div>
                <div><label>Durasi (hari)</label><input type="number" name="durasi" min="1" required value="{{ old('durasi') }}"></div>
            </div>
            <label>Proposal</label>
            <textarea name="proposal" maxlength="5000" required>{{ old('proposal') }}</textarea>
            <p><button>Kirim penawaran</button></p>
        </form>
    </div>
@else
    @auth
        @if (auth()->user()->role === 'developer')
            <div class="card"><p class="muted">Lelang sudah ditutup; penawaran tidak dapat diajukan.</p></div>
        @endif
    @else
        @if ($project->isOpen())
            <div class="card"><p><a href="{{ route('login') }}">Masuk</a> sebagai developer untuk mengajukan penawaran.</p></div>
        @endif
    @endauth
@endcan
@endsection
