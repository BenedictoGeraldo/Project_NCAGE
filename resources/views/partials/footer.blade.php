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
                            <li>
                                @if ($hasPendingNcage)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#sudahDaftarModal">Pendaftaran NCAGE</a>
                                @elseif($hasActiveNcage)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#activeNcageModal">Pendaftaran NCAGE</a>
                                @else
                                    <a href="{{ route('pendaftaran-ncage.show', ['step' => 1]) }}">Pendaftaran NCAGE</a>
                                @endif
                            </li>
                            <li><a href="{{ route('tracking.index') }}">Pantau Status</a></li>
                            <li>
                                <a href="{{ route('entity-check.index') }}">Cek Entitas</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-6 col-md-5 mb-4 mb-md-0 text-center text-md-start">
                        <h5 class="footer-heading">Kontak</h5>
                        <ul class="footer-contact list-unstyled p-0">
                            <li class="d-flex align-items-start justify-content-center justify-content-md-start">
                                <i class="fas fa-phone-alt mt-1 me-2"></i>
                                <a href="https://wa.me/6281288824545?text=Halo%20Admin%20Puskod%2C%0A%0Asaya%20ingin%20bertanya%20mengenai%20layanan%20pendaftaran%20NCAGE.%0A%0ANama%3A%20%0APerusahaan%3A%20"
                                    target="_blank" class="footer-contact-link">
                                    <div>
                                        <span>Call Center Puskod</span>
                                        <p class="mb-2">0812-8882-4545</p>
                                    </div>
                                </a>
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
                <a href="{{ route('team.index') }}" class="text-decoration-none text-reset">
                    © {{ date('Y') }} Pusat Kodifikasi, Baranahan, Kementerian Pertahanan Republik Indonesia.
                </a>
            </div>
        </div>
    </div>
</footer>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">