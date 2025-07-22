document.addEventListener('DOMContentLoaded', function () {

    const dropdownElement = document.getElementById('notificationDropdown');
    if (!dropdownElement) { return; }

    const countBadge = document.getElementById('notification-count');
    const listContainer = document.getElementById('notification-list-container');
    const loader = document.getElementById('notification-loader');
    const seeMoreButton = document.querySelector('.notification-footer a');
    let displayedNotificationIds = [];

    function formatTime(dateString) {
        const date = new Date(dateString);
        const options = { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false };
        return new Intl.DateTimeFormat('id-ID', options).format(date);
    }

    function createNotificationItem(notification) {
        const isUnread = notification.read_at === null;
        return `
            <a href="#" class="notification-item">
                <div class="icon-container"><i class="${notification.data.icon || 'fa-regular fa-bell'}"></i></div>
                <div class="message-container">
                    <h6>${notification.data.title}</h6>
                    <p>${notification.data.message}</p>
                    <small>${formatTime(notification.created_at)}</small>
                </div>
                ${isUnread ? '<div class="unread-dot"></div>' : ''}
            </a>
        `;
    }

    async function fetchNotifications() {
        if (loader) loader.style.display = 'block';
        listContainer.innerHTML = '';
        seeMoreButton.style.display = 'none';

        try {
            // MENGGUNAKAN URL LANGSUNG, BUKAN BLADE
            const response = await fetch('/notifications'); 
            if (!response.ok) throw new Error('Network response was not ok.');

            const data = await response.json();
            if (loader) loader.style.display = 'none';

            if (data.unreadCount > 0) {
                countBadge.textContent = data.unreadCount > 9 ? '9+' : data.unreadCount;
                countBadge.style.display = 'block';
            } else {
                countBadge.style.display = 'none';
            }

            if (data.notifications.length > 0) {
                const notificationsToShow = data.notifications.slice(0, 2);
                notificationsToShow.forEach(n => { listContainer.innerHTML += createNotificationItem(n); });
                displayedNotificationIds = notificationsToShow.map(n => n.id);

                if (data.notifications.length > 2) {
                    seeMoreButton.style.display = 'block';
                    seeMoreButton.onclick = (e) => {
                        e.preventDefault();
                        listContainer.innerHTML = '';
                        data.notifications.forEach(n => { listContainer.innerHTML += createNotificationItem(n); });
                        displayedNotificationIds = data.notifications.map(n => n.id);
                        seeMoreButton.style.display = 'none';
                    };
                }
            } else {
                listContainer.innerHTML = '<p class="text-center text-muted p-3 mb-0">Tidak ada notifikasi.</p>';
            }
        } catch (error) {
            console.error('Gagal mengambil notifikasi:', error);
            if (loader) loader.style.display = 'none';
            listContainer.innerHTML = '<p class="text-center text-danger p-3 mb-0">Gagal memuat notifikasi.</p>';
        }
    }

    async function markAsRead() {
        if (displayedNotificationIds.length === 0) return;
        try {
            // MENGGUNAKAN URL LANGSUNG, BUKAN BLADE
            await fetch('/notifications/mark-as-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: displayedNotificationIds })
            });
            document.querySelectorAll('.unread-dot').forEach(dot => dot.remove());
        } catch (error) {
            console.error('Gagal menandai notifikasi sebagai dibaca:', error);
        }
    }

    dropdownElement.parentElement.addEventListener('show.bs.dropdown', fetchNotifications);
    dropdownElement.parentElement.addEventListener('hide.bs.dropdown', markAsRead);
});