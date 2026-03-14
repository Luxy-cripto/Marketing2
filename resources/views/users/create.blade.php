@extends('layouts.admin2')

@section('content')

<div class="container">

```
<h4 class="mb-3">Tambah User</h4>

<div class="card">
    <div class="card-body">

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required
                >
            </div>

            <div class="mb-4">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="marketing">Marketing</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-success">
                    Simpan
                </button>

                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    Batal
                </a>

            </div>

        </form>

    </div>
</div>
```

</div>
@endsection
