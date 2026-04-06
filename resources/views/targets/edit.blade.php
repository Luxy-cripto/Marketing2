@extends('layouts.admin2')

@section('content')
    <div class="container py-4">

        <div class="card">
            <div class="card-body">

                <form action="{{ route('targets.update', $target->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <select name="user_id" class="form-control mb-3">
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ $target->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="bulan" value="{{ $target->bulan }}" class="form-control mb-2">
                    <input type="number" name="tahun" value="{{ $target->tahun }}" class="form-control mb-3">

                    <h5>Target Produk</h5>

                    <table class="table">
                        @foreach(\App\Models\Produk::all() as $produk)
                            @php
                                $detail = $target->details->where('produk_id', $produk->id)->first();
                            @endphp

                            <tr>
                                <td>{{ $produk->nama }}</td>

                                <td>
                                    <input type="number" name="produk[{{ $produk->id }}][qty]" class="form-control"
                                        value="{{ $detail->target_qty ?? 0 }}">
                                </td>

                                <td>
                                    <input type="number" name="produk[{{ $produk->id }}][omset]" class="form-control"
                                        value="{{ $detail->target_omset_produk ?? 0 }}">
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <button class="btn btn-success">Update</button>

                </form>

            </div>
        </div>

    </div>
@endsection
