{{-- resources/views/partials/notification-menu.blade.php --}}
{{-- File ini HANYA berisi menu dropdown, tanpa pemicu/ikon. --}}

<ul class="dropdown-menu dropdown-menu-end notification-dropdown-menu" aria-labelledby="notificationDropdown">
    {{-- Header Dropdown --}}
    <li class="notification-header">
        <h6 class="dropdown-header">Notifikasi</h6>
    </li>

    {{-- Kontainer untuk daftar notifikasi (diisi oleh JavaScript) --}}
    <li id="notification-list-container">
        {{-- Tampilkan spinner/loader saat pertama kali memuat --}}
        <div class="text-center p-3" id="notification-loader">
            <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </li>

    {{-- Footer Dropdown --}}
    <li class="notification-footer">
        <a href="#" class="dropdown-item text-center">Lihat Lebih Banyak</a>
    </li>
</ul>