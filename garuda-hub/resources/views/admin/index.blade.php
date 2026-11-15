@extends('layouts.app')

@section('title', 'Admin')

@section('content')
<div class="card"><h1>Admin</h1></div>

<div class="card">
    <h2>Kelola User</h2>
    <table>
        <tr><th>ID</th><th>Nama</th><th>Email</th><th>Peran</th><th>Aksi</th></tr>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->nama }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role }}</td>
                <td>
                    @if ($u->is(auth()->user()))
                        <span class="muted">(akun Anda)</span>
                    @else
                        <form class="inline" method="post" action="{{ route('admin.users.role', $u) }}">
                            @csrf
                            @method('PATCH')
                            <select name="role">
                                @foreach (\App\Models\User::ROLES as $role)
                                    <option value="{{ $role }}" @selected($role === $u->role)>{{ $role }}</option>
                                @endforeach
                            </select>
                            <button class="sm" type="submit">Ubah</button>
                        </form>
                        <form class="inline" method="post" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Hapus user beserta proyek dan penawarannya?">
                            @csrf
                            @method('DELETE')
                            <button class="sm sec" type="submit">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div class="card">
    <h2>Kelola Proyek</h2>
    <table>
        <tr><th>ID</th><th>Judul</th><th>Klien</th><th>Status</th><th>Aksi</th></tr>
        @foreach ($projects as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td><a href="{{ route('projects.show', $p) }}">{{ $p->judul }}</a></td>
                <td>{{ $p->client->nama }}</td>
                <td>@include('partials.status', ['status' => $p->status])</td>
                <td>
                    <form class="inline" method="post" action="{{ route('admin.projects.destroy', $p) }}" data-confirm="Hapus proyek beserta penawarannya?">
                        @csrf
                        @method('DELETE')
                        <button class="sm sec" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
