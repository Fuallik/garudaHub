@extends('layouts.app')

@section('title', $project->exists ? 'Ubah Proyek' : 'Pasang Proyek')

@section('content')
<div class="card">
    <h1>{{ $project->exists ? 'Ubah Proyek' : 'Pasang Proyek' }}</h1>
    <form method="post" action="{{ $project->exists ? route('projects.update', $project) : route('projects.store') }}">
        @csrf
        @if ($project->exists)
            @method('PUT')
        @endif
        <label>Judul</label>
        <input name="judul" maxlength="200" required value="{{ old('judul', $project->judul) }}">
        <label>Deskripsi</label>
        <textarea name="deskripsi" maxlength="5000" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>
        <label>Kategori</label>
        <input name="kategori" maxlength="50" placeholder="Web, Mobile, Desain, Data, AI..." required value="{{ old('kategori', $project->kategori) }}">
        <div class="row">
            <div><label>Budget minimum (Rp)</label><input type="number" name="budget_min" min="0" required value="{{ old('budget_min', $project->budget_min) }}"></div>
            <div><label>Budget maksimum (Rp)</label><input type="number" name="budget_max" min="0" required value="{{ old('budget_max', $project->budget_max) }}"></div>
            <div><label>Batas lelang</label><input type="date" name="batas_lelang" required value="{{ old('batas_lelang', $project->batas_lelang?->format('Y-m-d')) }}"></div>
        </div>
        <p><button>{{ $project->exists ? 'Simpan perubahan' : 'Pasang proyek' }}</button></p>
    </form>
</div>
@endsection
