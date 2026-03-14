@extends('layouts.admin2')

@section('content')

<div class="container">

```
<h4 class="mb-3">Edit User</h4>

<div class="card">
    <div class="card-body">

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name', $user->name) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $user->email) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="marketing" {{ $user->role == 'marketing' ? 'selected' : '' }}>
                        Marketing
                    </option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>
                        User
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-success">
                    Update
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
