import "./bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    /**
     * =================================================================
     * BAGIAN AWAL: FUNGSI UNTUK UPDATE BADGE SAAT HALAMAN DIMUAT
     * =================================================================
     */
    function updateInitialUnreadCount() {
        const notificationBadge = document.getElementById("notification-count");
        if (!notificationBadge) return;

        fetch("/notifications")
            .then((response) => response.json())
            .then((data) => {
                if (data.unread_count > 0) {
                    notificationBadge.innerText = data.unread_count;
                    notificationBadge.style.display = "block";
                } else {
                    notificationBadge.style.display = "none";
                }
            })
            .catch((error) =>
                console.error("Gagal mengambil jumlah notifikasi awal:", error)
            );
    }

    // Panggil fungsi ini sekali saat halaman selesai dimuat
    updateInitialUnreadCount();

    /**
     * =================================================================
     * BAGIAN 1: FUNGSI-FUNGSI BANTUAN
     * =================================================================
     */
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

    /**
     * =================================================================
     * BAGIAN 2: LISTENER UNTUK NOTIFIKASI REAL-TIME
     * =================================================================
     */
    const userIdMeta = document.querySelector('meta[name="user-id"]');
    if (userIdMeta) {
        const userId = userIdMeta.getAttribute("content");
        window.Echo.private("App.Models.User." + userId).listen(
            ".user-notification",
            (e) => {
                console.log("Real-time event received!", e);
                // Refresh badge dengan jumlah terbaru dari server
                updateInitialUnreadCount();

                // Tambahkan notifikasi baru ke daftar jika dropdown sedang terbuka
                const notificationList = document.querySelector(
                    "#notification-list-container"
                );
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
                        read_at: null,
                    };
                    notificationList.insertAdjacentHTML(
                        "afterbegin",
                        createNotificationHtml(newNotificationData)
                    );
                }
            }
        );
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

        function fetchNotifications() {
            const notificationList = document.querySelector(
                "#notification-list-container"
            );
            const loader = document.getElementById("notification-loader");

            if (loader) loader.style.display = "block";
            notificationList.innerHTML = "";

            fetch("/notifications")
                .then((response) => response.json())
                .then((data) => {
                    if (loader) loader.style.display = "none";
                    if (data.notifications && data.notifications.length > 0) {
                        data.notifications.forEach((notification) => {
                            notificationList.innerHTML +=
                                createNotificationHtml(notification);
                        });
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

        notificationDropdown.addEventListener("click", function () {
            const notificationBadge =
                document.getElementById("notification-count");
            if (notificationBadge) {
                notificationBadge.innerText = "0";
                notificationBadge.style.display = "none";
            }

            // Tandai notifikasi sebagai sudah dibaca di server
            fetch("/notifications/mark-as-read", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            if (!hasBeenClicked) {
                fetchNotifications();
                hasBeenClicked = true;
            }
        });

        const dropdownEl = document.querySelector(".notification-dropdown");
        if (dropdownEl) {
            dropdownEl.addEventListener("hidden.bs.dropdown", function () {
                hasBeenClicked = false;
            });
        }
    }
});
