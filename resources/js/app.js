import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    /**
     * =================================================================
     * BAGIAN 1: FUNGSI-FUNGSI BANTUAN
     * =================================================================
     */

    // Fungsi untuk membuat HTML dari satu notifikasi
    function createNotificationHtml(notification) {
        const date = new Date(notification.created_at);
        const time = date.toLocaleDateString("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
        const isUnread = notification.read_at === null;
        const unreadDotHtml = isUnread
            ? '<div class="notification-unread-dot"></div>'
            : "";
        const icon = notification.data.icon || "fa-solid fa-bell";
        const title = notification.data.title || "Notifikasi Baru";
        const message =
            notification.data.message || "Anda memiliki notifikasi baru.";

        return `
            <li>
                <a href="#" class="dropdown-item notification-item">
                    <div class="d-flex align-items-start">
                        <div class="me-3 pt-1"><i class="${icon} fs-5 text-secondary"></i></div>
                        <div class="flex-grow-1">
                            <h6 class="notification-title fw-bold mb-0">${title}</h6>
                            <small class="notification-time text-muted">${time} WIB</small>
                            <p class="notification-message small mb-0 mt-2">${message}</p>
                        </div>
                        ${unreadDotHtml}
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
        `;
    }
    // <<< TAMBAHAN BARU >>>
    // Fungsi untuk memuat jumlah notifikasi yang belum dibaca dari server
    function loadUnreadNotificationCount() {
        const notificationBadge = document.getElementById("notification-count");
        if (notificationBadge) {
            fetch("/notifications/unread-count") // Memanggil endpoint baru
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok.");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.count > 0) {
                        notificationBadge.innerText = data.count;
                        notificationBadge.style.display = "block";
                    } else {
                        notificationBadge.innerText = "0";
                        notificationBadge.style.display = "none";
                    }
                })
                .catch((error) => {
                    console.error(
                        "Gagal memuat jumlah notifikasi belum dibaca:",
                        error
                    );
                    notificationBadge.innerText = "0"; // Pastikan tetap 0 jika gagal
                    notificationBadge.style.display = "none";
                });
        }
    }

    // <<< PANGGIL FUNGSI INI SAAT HALAMAN DIMUAT >>>
    loadUnreadNotificationCount();

    /**
     * =================================================================
     * BAGIAN 2: LISTENER UNTUK NOTIFIKASI REAL-TIME
     * =================================================================
     */
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (userIdMeta) {
        const userId = userIdMeta.getAttribute("content");
        console.log(
            "Real-time notification listener is active for User ID:",
            userId
        );

        window.Echo.private("App.Models.User." + userId)
            // PERBAIKAN FINAL: Tambahkan titik (.) di depan nama event
            // Ini memberitahu Echo untuk mendengarkan nama alias (dari broadcastAs)
            // tanpa menambahkan namespace aplikasi secara otomatis.
            .listen(".user-notification", (e) => {
                console.log("Real-time event received!", e);

                const notificationBadge =
                    document.getElementById("notification-count");
                const notificationList = document.querySelector(
                    "#notification-list-container"
                );

                if (notificationBadge) {
                    let currentCount =
                        parseInt(notificationBadge.innerText) || 0;
                    notificationBadge.innerText = currentCount + 1; // Notifikasi baru: INKREMEN jumlah yang sudah ada
                    notificationBadge.style.display = "block";
                }

                if (notificationList) {
                    const noNotifMessage = document.getElementById(
                        "no-notification-message"
                    );
                    if (noNotifMessage) noNotifMessage.remove();

                    const newNotificationData = {
                        data: {
                            title: e.title,
                            message: e.message,
                            icon: "fa-solid fa-paper-plane",
                        },
                        created_at: new Date().toISOString(),
                        read_at: null, // Asumsi notifikasi baru selalu belum dibaca
                    };
                    notificationList.insertAdjacentHTML(
                        "afterbegin",
                        createNotificationHtml(newNotificationData)
                    );
                }
            });
    }

    /**
     * =================================================================
     * BAGIAN 3: LOGIKA SAAT DROPDOWN NOTIFIKASI DIKLIK
     * =================================================================
     */
    const notificationDropdown = document.getElementById(
        "notificationDropdown"
    );
    if (notificationDropdown) {
        let hasBeenClicked = false;

        // Fungsi untuk mengambil notifikasi lama dari server
        function fetchNotifications() {
            const notificationList = document.querySelector(
                "#notification-list-container"
            );
            const loader = document.getElementById("notification-loader");

            if (loader) loader.style.display = "block";
            notificationList.innerHTML = "";

            fetch("/notifications")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            "Network response was not ok: " +
                                response.statusText
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    if (loader) loader.style.display = "none";
                    if (data.notifications && data.notifications.length > 0) {
                        const allNotifications = data.notifications;
                        const visibleCount = 2; // Jumlah notifikasi awal

                        // Tampilkan hanya beberapa notifikasi awal
                        allNotifications
                            .slice(0, visibleCount)
                            .forEach((notification) => {
                                notificationList.innerHTML +=
                                    createNotificationHtml(notification);
                            });

                        // Sembunyikan sisanya dulu (gunakan data-attributenya)
                        allNotifications
                            .slice(visibleCount)
                            .forEach((notification) => {
                                const hiddenHtml = createNotificationHtml(
                                    notification
                                ).replace(
                                    "<li>",
                                    '<li class="hidden-notification notification-hidden">'
                                );
                                notificationList.innerHTML += hiddenHtml;
                            });

                        // Tampilkan tombol jika notifikasi > visibleCount
                        const showMoreBtn = document.getElementById(
                            "show-more-notifications"
                        );
                        if (showMoreBtn) {
                            if (allNotifications.length > visibleCount) {
                                showMoreBtn.style.display = "block";
                            } else {
                                showMoreBtn.style.display = "none";
                            }

                            // Tambahkan listener
                            showMoreBtn.onclick = function (e) {
                                e.preventDefault();
                                e.stopPropagation(); // agar dropdown tidak tertutup

                                const hiddenEls = document.querySelectorAll(
                                    ".notification-hidden"
                                );
                                const isExpanded =
                                    showMoreBtn.getAttribute(
                                        "data-expanded"
                                    ) === "true";

                                if (!isExpanded) {
                                    // Expand notifikasi
                                    hiddenEls.forEach((el) => {
                                        el.classList.remove(
                                            "hidden-notification"
                                        );
                                    });
                                    showMoreBtn.textContent =
                                        "Lihat Lebih Sedikit";
                                    showMoreBtn.setAttribute(
                                        "data-expanded",
                                        "true"
                                    );
                                } else {
                                    // Collapse kembali
                                    hiddenEls.forEach((el) => {
                                        el.classList.add("hidden-notification");
                                    });
                                    showMoreBtn.textContent =
                                        "Lihat Lebih Banyak";
                                    showMoreBtn.setAttribute(
                                        "data-expanded",
                                        "false"
                                    );
                                }
                            };
                        }
                    } else {
                        notificationList.innerHTML =
                            '<p id="no-notification-message" class="text-center text-muted p-3 mb-0">Tidak ada notifikasi.</p>';
                    }
                })
                .catch((error) => {
                    console.error("Gagal mengambil notifikasi:", error);
                    if (loader) loader.style.display = "none";
                    notificationList.innerHTML =
                        '<p class="text-center text-danger p-3 mb-0">Gagal memuat notifikasi.</p>';
                });
        }

        // Event listener saat ikon lonceng diklik
        notificationDropdown.addEventListener("click", function () {
            // <<< MODIFIKASI: Tandai notifikasi sebagai sudah dibaca dan reset badge >>>
            const notificationBadge =
                document.getElementById("notification-count");

            // Panggil API untuk menandai notifikasi sebagai sudah dibaca
            // Pastikan `axios` sudah diimpor dan tersedia (seperti di bootstrap.js)
            if (window.axios) {
                window.axios
                    .post("/notifications/mark-as-read") // Memanggil endpoint baru
                    .then((response) => {
                        if (response.data.success) {
                            // Setelah berhasil ditandai sebagai dibaca di backend,
                            // reset badge di frontend
                            if (notificationBadge) {
                                notificationBadge.innerText = "0";
                                notificationBadge.style.display = "none";
                            }
                        }
                    })
                    .catch((error) => {
                        console.error(
                            "Gagal menandai notifikasi sebagai sudah dibaca:",
                            error
                        );
                        // Optional: Beri tahu pengguna jika ada masalah
                    });
            } else {
                console.warn(
                    "Axios tidak ditemukan. Tidak dapat menandai notifikasi sebagai sudah dibaca."
                );
            }
            // <<< AKHIR MODIFIKASI >>>

            if (!hasBeenClicked) {
                fetchNotifications();
                hasBeenClicked = true;
            }
        });

        // Reset status klik saat dropdown ditutup
        const dropdownEl = document.querySelector(".notification-dropdown");
        if (dropdownEl) {
            dropdownEl.addEventListener("hidden.bs.dropdown", function () {
                hasBeenClicked = false;
            });
        }
    }
});
