
<!-- Footer -->
<footer class="bg-dark text-light pt-5 pb-3">
    <div class="container">
        <div class="row">

            <!-- Tentang Perusahaan -->
            <div class="col-md-3 mb-4">
                <img src="{{ asset('images/logo-small.png') }}" alt="Logo" class="mb-2" style="height:40px;">
                <p>PT. Sim_Marketing adalah perusahaan terkemuka di bidang solusi pemasaran dan manajemen konsumen, menyediakan layanan berkualitas tinggi untuk klien kami.</p>
            </div>

            <!-- Link Cepat -->
            <div class="col-md-3 mb-4">
                <h6 class="text-uppercase mb-3">Link Cepat</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('marketing.dashboard') }}" class="text-light text-decoration-none">Dashboard</a></li>
                    <li><a href="{{ route('konsumen.index') }}" class="text-light text-decoration-none">Konsumen</a></li>
                    <li><a href="{{ route('produk.index') }}" class="text-light text-decoration-none">Produk</a></li>
                    <li><a href="{{ route('transaksi.index') }}" class="text-light text-decoration-none">Transaksi</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="col-md-3 mb-4">
                <h6 class="text-uppercase mb-3">Kontak</h6>
                <p class="mb-1"><i class="bi bi-envelope me-2"></i><a href="mailto:admin@example.com" class="text-light text-decoration-none">admin@example.com</a></p>
                <p class="mb-1"><i class="bi bi-telephone me-2"></i>+62 812-3456-7890</p>
                <p><i class="bi bi-geo-alt me-2"></i>Jl. Katapang No. 123, Bandung, Indonesia</p>
            </div>

            <!-- Sosial Media -->
            <div class="col-md-3 mb-4">
                <h6 class="text-uppercase mb-3">Sosial Media</h6>
                <a href="#" class="text-light me-3 fs-5"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-light me-3 fs-5"><i class="bi bi-twitter"></i></a>
                <a href="#" class="text-light me-3 fs-5"><i class="bi bi-instagram"></i></a>
                <a href="#" class="text-light me-3 fs-5"><i class="bi bi-linkedin"></i></a>
                <a href="#" class="text-light me-3 fs-5"><i class="bi bi-youtube"></i></a>
                <a href="#" class="text-light me-3 fs-5"><i class="bi bi-whatsapp"></i></a>
                <a href="#" class="text-light me-3 fs-5"><i class="bi bi-tiktok"></i></a>
            </div>

        </div>

        <hr class="border-secondary">

        <!-- Copyright -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-0">&copy; {{ date('Y') }} PT. ContohMarketing. All rights reserved.</p>
            <p class="mb-0">Designed with <i class="bi bi-heart-fill text-danger"></i> by Your Team</p>
        </div>
    </div>
</footer>
