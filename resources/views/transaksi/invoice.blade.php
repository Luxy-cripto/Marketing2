<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style>

        body{
            font-family: Arial, sans-serif;
            font-size:14px;
            color:#333;
        }

        .header{
            text-align:center;
            margin-bottom:20px;
        }

        .invoice-box{
            width:100%;
        }

        .info{
            margin-bottom:20px;
        }

        .info p{
            margin:3px 0;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        table th{
            background:#f2f2f2;
            border:1px solid #ddd;
            padding:10px;
            text-align:left;
        }

        table td{
            border:1px solid #ddd;
            padding:10px;
        }

        .total{
            margin-top:20px;
            text-align:right;
            font-size:16px;
            font-weight:bold;
        }

        .footer{
            margin-top:40px;
            text-align:center;
            font-size:13px;
            color:#777;
        }

    </style>

</head>

<body>

    <div class="invoice-box">

        <div class="header">
            <h2>INVOICE</h2>
        </div>

        <hr>

        <div class="info">
            <p><b>No Transaksi :</b> {{ $transaksi->id }}</p>
            <p><b>Tanggal :</b> {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</p>
        </div>

        <hr>

        <h3>Konsumen</h3>

        <p>{{ $transaksi->konsumen->nama }}</p>
        <p>{{ $transaksi->konsumen->no_hp }}</p>

        <table>
            <tr>
                <th>Produk</th>
                <th width="80">Qty</th>
                <th width="150">Harga</th>
                <th width="150">Total</th>
            </tr>

            <tr>
                <td>{{ $transaksi->produk->nama }}</td>
                <td>{{ $transaksi->qty }}</td>
                <td>Rp {{ number_format($transaksi->harga_satuan,0,',','.') }}</td>
                <td>Rp {{ number_format($transaksi->total,0,',','.') }}</td>
            </tr>
        </table>

        <div class="total">
            Total Bayar :
            Rp {{ number_format($transaksi->total,0,',','.') }}
        </div>

        <div class="footer">
            Terima kasih atas pembelian Anda
        </div>

    </div>

</body>
</html>
