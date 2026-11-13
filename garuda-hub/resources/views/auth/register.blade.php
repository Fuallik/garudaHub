@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="card">
    <h1>Daftar</h1>
    <form method="post" action="{{ route('register') }}">
        @csrf
        <label>Nama</label>
        <input name="nama" maxlength="100" required value="{{ old('nama') }}">
        <label>Email</label>
        <input name="email" type="email" maxlength="100" required value="{{ old('email') }}">
        <label>Password (min. 8 karakter)</label>
        <input name="password" type="password" minlength="8" maxlength="72" required autocomplete="new-password">
        <label>Peran</label>
        <select name="role">
            @foreach (\App\Models\User::SELF_ROLES as $role)
                <option value="{{ $role }}" @selected(old('role', 'klien') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        <p><button type="submit">Daftar</button></p>
    </form>
</div>
@endsection
