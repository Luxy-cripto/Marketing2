@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h4 class="mb-0">✏️ Edit Transaksi</h4>
        </div>

        <div class="card-body">

            <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Konsumen -->
                <div class="mb-3">
                    <label class="fw-bold">Konsumen</label>
                    <select name="konsumen_id" class="form-control" required>
                        @foreach($konsumens as $k)
                            <option value="{{ $k->id }}"
                                {{ $transaksi->konsumen_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="mb-3">
                    <label class="fw-bold">Tanggal</label>
                    <input type="date" name="tanggal_transaksi"
                        class="form-control"
                        value="{{ $transaksi->tanggal_transaksi }}">
                </div>

                <hr>

                <!-- PRODUK MULTI -->
                <h6 class="fw-bold">Produk</h6>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="produk-list">

                        @foreach($transaksi->details as $d)
                        <tr class="produk-row">
                            <td>
                                <select name="produk_id[]" class="form-control select-produk" required>
                                    @foreach($produks as $p)
                                        <option value="{{ $p->id }}"
                                            data-harga="{{ $p->harga }}"
                                            {{ $d->produk_id == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama }} (Stok: {{ $p->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" class="form-control harga-display"
                                    value="{{ number_format($d->harga,0,',','.') }}" readonly>
                            </td>

                            <td>
                                <input type="number" name="qty[]" class="form-control qty"
                                    value="{{ $d->qty }}" min="1">
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger remove">X</button>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

                <button type="button" id="add" class="btn btn-primary mb-3">
                    + Tambah Produk
                </button>

                <!-- TOTAL -->
                <div class="mb-3">
                    <label class="fw-bold">Total</label>
                    <input type="text" id="total_display" class="form-control"
                        value="Rp {{ number_format($transaksi->total,0,',','.') }}" readonly>
                </div>

                <!-- STATUS -->
                <div class="mb-3">
                    <label class="fw-bold">Status</label>
                    <select name="status" class="form-control">
                        <option value="Belum Bayar" {{ $transaksi->status == 'Belum Bayar' ? 'selected' : '' }}>
                            Belum Bayar
                        </option>
                        <option value="Lunas" {{ $transaksi->status == 'Lunas' ? 'selected' : '' }}>
                            Lunas
                        </option>
                    </select>
                </div>

                <button class="btn btn-success w-100">💾 Update</button>

            </form>

        </div>
    </div>

</div>

<script>

// hitung total
function updateTotal() {
    let total = 0;

    document.querySelectorAll('.produk-row').forEach(row => {
        let harga = parseInt(
            row.querySelector('.select-produk')
            .options[row.querySelector('.select-produk').selectedIndex]
            ?.getAttribute('data-harga')
        ) || 0;

        let qty = parseInt(row.querySelector('.qty').value) || 0;

        total += harga * qty;
    });

    document.getElementById('total_display').value =
        'Rp ' + new Intl.NumberFormat('id-ID').format(total);
}

// change produk
document.addEventListener('change', function(e){
    if(e.target.classList.contains('select-produk')){
        let row = e.target.closest('tr');
        let harga = e.target.options[e.target.selectedIndex].getAttribute('data-harga');

        row.querySelector('.harga-display').value =
            new Intl.NumberFormat('id-ID').format(harga);

        updateTotal();
    }
});

// qty change
document.addEventListener('input', function(e){
    if(e.target.classList.contains('qty')){
        updateTotal();
    }
});

// tambah row
document.getElementById('add').addEventListener('click', function(){
    let row = document.querySelector('.produk-row').cloneNode(true);

    row.querySelector('.select-produk').value = '';
    row.querySelector('.harga-display').value = '';
    row.querySelector('.qty').value = 1;

    document.getElementById('produk-list').appendChild(row);
});

// hapus row
document.addEventListener('click', function(e){
    if(e.target.classList.contains('remove')){
        if(document.querySelectorAll('.produk-row').length > 1){
            e.target.closest('tr').remove();
            updateTotal();
        }
    }
});

updateTotal();

</script>

@endsection
