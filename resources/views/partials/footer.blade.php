<footer class="footer">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-5 col-12 mb-4 mb-lg-0 text-center text-lg-start">
                <div class="d-inline-flex flex-column"> 
                    <div class="footer-brand d-flex flex-column flex-lg-row align-items-center mb-3">
                        <div class="img mb-2 mb-lg-0 me-lg-3" style="width: 70px;">
                            <img class="w-100" src="{{ asset('images/logo.png') }}" alt="Logo Puskod">
                        </div>
                        <div class="text-center text-lg-start">
                            <h5 class="footer-title mb-0">Pelayanan NCAGE</h5>
                            <p class="footer-subtitle mb-0">Pusat Kodifikasi</p>
                        </div>
                    </div>
                    <p class="footer-address">
                        Jl. Pd. Labu Raya, RT.6/RW.6 Pd. Labu, Cilandak<br>
                        Kota Jakarta Selatan Daerah Khusus Ibukota Jakarta
                    </p>
                </div>
            </div>

            <div class="col-lg-7 col-12">
                <div class="row">
                    <div class="col-6 col-md-4 mb-4 mb-md-0 text-center text-md-start">
                        <h5 class="footer-heading">Tautan</h5>
                        <ul class="footer-links list-unstyled p-0">
                            <li><a href="{{ route('home') }}">Beranda</a></li>
                            <li><a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}">Pendaftaran NCAGE</a></li>
                            <li><a href="{{ route('tracking.index') }}">Pantau Status</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-md-5 mb-4 mb-md-0 text-center text-md-start">
                        <h5 class="footer-heading">Kontak</h5>
                        <ul class="footer-contact list-unstyled p-0">
                            <li class="d-flex align-items-start justify-content-center justify-content-md-start">
                                <i class="fas fa-phone-alt mt-1 me-2"></i>
                                <div>
                                    <span>Call Center Puskod</span>
                                    <p class="mb-2">0812-8882-4545</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start justify-content-center justify-content-md-start">
                                <i class="fas fa-clock mt-1 me-2"></i>
                                <div>
                                    <span>Jam Pelayanan</span>
                                    <p class="mb-0">Senin - Jumat, 08:00 - 15:30 WIB</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="col-12 col-md-3 text-center mt-4 mt-md-0">
                        <h5 class="footer-heading">QR Code</h5>
                        <img class="w-50" src="{{ asset('images/qr_code_whatsapp.jpg') }}" alt="QR Code WhatsApp">
                    </div>
                </div>
            </div>
        </div>

        <hr class="footer-divider my-4">

        <div class="row">
            <div class="col text-center footer-copyright">
                © {{ date('Y') }} Pusat Kodifikasi, Baranahan, Kementerian Pertahanan Republik Indonesia.
            </div>
        </div>
    </div>
</footer>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">