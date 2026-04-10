@extends('layouts.admin2')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">🛒 Tambah Transaksi</h5>
        </div>

        <div class="card-body">

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf

                <!-- Konsumen -->
                <div class="mb-3">
                    <label class="fw-bold">Konsumen</label>
                    <select name="konsumen_id" class="form-control" required>
                        <option value="">-- Pilih Konsumen --</option>
                        @foreach($konsumens as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="mb-3">
                    <label class="fw-bold">Tanggal</label>
                    <input type="date" name="tanggal_transaksi" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <hr>

                <!-- Produk -->
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
                        <tr class="produk-row">
                            <td>
                                <select name="produk_id[]" class="form-control select-produk" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($produks as $p)
                                        <option value="{{ $p->id }}" data-harga="{{ $p->harga }}">
                                            {{ $p->nama }} (Stok: {{ $p->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" class="form-control harga-display" readonly>
                            </td>

                            <td>
                                <input type="number" name="qty[]" class="form-control qty" value="1" min="1">
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger remove">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="add" class="btn btn-primary mb-3">
                    + Tambah Produk
                </button>

                <!-- Total -->
                <div class="mb-3">
                    <label class="fw-bold">Total</label>
                    <input type="text" id="total_display" class="form-control" readonly>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label class="fw-bold">Status</label>
                    <select name="status" class="form-control">
                        <option value="Belum Bayar">Belum Bayar</option>
                        <option value="Lunas">Lunas</option>
                    </select>
                </div>

                <button class="btn btn-success w-100">💾 Simpan</button>

            </form>

        </div>
    </div>
</div>

<script>

// update harga
function updateRow(row) {
    let select = row.querySelector('.select-produk');
    let harga = select.options[select.selectedIndex]?.getAttribute('data-harga') || 0;

    row.querySelector('.harga-display').value =
        new Intl.NumberFormat('id-ID').format(harga);

    updateTotal();
}

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

// pilih produk
document.addEventListener('change', function(e){
    if(e.target.classList.contains('select-produk')){
        updateRow(e.target.closest('tr'));
    }
});

// qty berubah
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

</script>

@endsection
