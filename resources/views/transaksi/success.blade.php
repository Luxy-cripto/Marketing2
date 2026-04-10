@extends('layouts.admin2')

@section('content')

<div class="container py-4">

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h4 class="card-title mb-0">Status Transaksi</h4>
        </div>

        <div class="card-body text-center py-5">

            <div class="success-checkmark mb-4">
                <div class="check-icon">
                    <span class="icon-line line-tip"></span>
                    <span class="icon-line line-long"></span>
                    <div class="icon-circle"></div>
                </div>
            </div>

            <h3 class="text-success fw-bold">
                Transaksi Berhasil!
            </h3>

            <p class="text-muted">
                Invoice sedang dipersiapkan...
            </p>

            <div class="mt-3">
                <span id="countdown">5</span> detik menuju detail transaksi
            </div>

        </div>

    </div>

</div>

<style>
.success-checkmark{
    width:80px;
    height:80px;
    margin:auto;
}

.check-icon{
    width:80px;
    height:80px;
    border-radius:50%;
    border:4px solid #28a745;
    position:relative;
}

.icon-line{
    height:5px;
    background:#28a745;
    position:absolute;
    border-radius:2px;
}

.line-tip{
    top:45px;
    left:14px;
    width:25px;
    transform:rotate(45deg);
}

.line-long{
    top:38px;
    right:8px;
    width:47px;
    transform:rotate(-45deg);
}

.icon-circle{
    position:absolute;
    top:-4px;
    left:-4px;
    width:80px;
    height:80px;
    border-radius:50%;
    border:4px solid rgba(40,167,69,.4);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
confetti({
    particleCount:150,
    spread:90,
    origin:{ y:0.6 }
});

let time = 5;
let countdown = setInterval(function(){
    time--;
    document.getElementById('countdown').innerText = time;

    if(time <= 0){
        clearInterval(countdown);
        window.location.href = "{{ route('transaksi.show', $transaksi->id) }}";
    }
}, 1000);
</script>

@endsection
