@extends('layouts.admin2')

@section('content')

<div class="container">
<h4 class="mb-3">Manajemen User</h4>

<a href="{{ route('users.create') }}" class="btn btn-success mb-3">
    + Tambah User
</a>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="display table table-striped table-hover align-middle">

                <thead class="table-secondary">
                    <tr>
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $i => $user)
                        <tr>

                            <td>{{ $i + 1 }}</td>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </td>

                            <td>
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    <a href="{{ route('users.show', $user->id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        Detail
                                    </a>

                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('users.destroy', $user->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin hapus user ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Tidak ada user
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>
</div>
</div>
@endsection
