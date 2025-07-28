document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.USER_ID !== "undefined") {
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
                            <div class="me-3 pt-1">
                                <i class="${icon} fs-5 text-secondary"></i>
                            </div>
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

        // --- KONFIGURASI ECHO (DENGAN PENYESUAIAN) ---
        try {
            window.Echo = new Echo({
                broadcaster: "pusher",
                key: window.PUSHER_APP_KEY,
                cluster: window.PUSHER_APP_CLUSTER,
                forceTLS: true,
                // LANGKAH 1: Pastikan blok ini ada untuk otorisasi channel privat
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            // --- LISTENER UNTUK NOTIFIKASI BARU (DENGAN PENYESUAIAN) ---
            // LANGKAH 2: Pastikan nama event menggunakan alamat lengkapnya
            window.Echo.private("user." + window.USER_ID).listen(
                "App\\Events\\UserNotificationEvent", // Nama event harus cocok persis
                (e) => {
                    const notificationBadge =
                        document.getElementById("notification-count");
                    const notificationList = document.querySelector(
                        "#notification-list-container"
                    );
                    const loader = document.getElementById(
                        "notification-loader"
                    );
                    const noNotifMessage = document.getElementById(
                        "no-notification-message"
                    );

                    if (noNotifMessage) noNotifMessage.remove();

                    let currentCount =
                        parseInt(notificationBadge.innerText) || 0;
                    notificationBadge.innerText = currentCount + 1;
                    notificationBadge.style.display = "block";

                    const newNotificationHtml = `
                        <li>
                            <a href="#" class="dropdown-item notification-item">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 pt-1">
                                        <i class="fa-solid fa-paper-plane fs-5 text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="notification-title fw-bold mb-0">${
                                            e.title
                                        }</h6>
                                        <small class="notification-time text-muted">${new Date().toLocaleTimeString(
                                            "id-ID",
                                            {
                                                hour: "2-digit",
                                                minute: "2-digit",
                                            }
                                        )} WIB</small>
                                        <p class="notification-message small mb-0 mt-2">${
                                            e.message
                                        }</p>
                                    </div>
                                    <div class="notification-unread-dot"></div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                    `;

                    if (loader) loader.style.display = "none";
                    notificationList.insertAdjacentHTML(
                        "afterbegin",
                        newNotificationHtml
                    );
                }
            );
        } catch (e) {
            console.warn("Gagal inisialisasi notifikasi real-time.", e);
        }

        // --- FUNGSI FETCH NOTIFIKASI LAMA ---
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

        // --- EVENT LISTENER SAAT IKON DI-KLIK ---
        const notificationDropdown = document.getElementById(
            "notificationDropdown"
        );
        if (notificationDropdown) {
            let hasBeenClicked = false;
            notificationDropdown.addEventListener("click", function () {
                const notificationBadge =
                    document.getElementById("notification-count");
                notificationBadge.innerText = "0";
                notificationBadge.style.display = "none";

                if (!hasBeenClicked) {
                    fetchNotifications();
                    hasBeenClicked = true;
                }
            });
            const dropdownEl = document.querySelector(".notification-dropdown");
            dropdownEl.addEventListener("hidden.bs.dropdown", function () {
                hasBeenClicked = false;
            });
        }
    }
});
