document.addEventListener('DOMContentLoaded', function () {
    const {
        pusherKey,
        pusherCluster,
        csrfToken,
        channelName,
    } = window.NotificationConfig;

    const notificationList = document.getElementById('notification-list');
    const countBadge = document.getElementById('notification-count');

    if (!notificationList || !countBadge || !channelName) {
        console.warn("❌ Notification elements or config not found.");
        return;
    }

    const pusher = new Pusher(pusherKey, {
        cluster: pusherCluster,
        authEndpoint: '/broadcasting/auth',
        encrypted: true,
        auth: {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        }
    });

    const channel = pusher.subscribe(channelName);

    channel.bind('notification.received', function (data) {
        console.log('✅ New notification received:', data);

        const noNotifElem = notificationList.querySelector('.text-muted');
        if (noNotifElem) noNotifElem.remove();

        const li = document.createElement('li');
        li.setAttribute('data-notification-id', data.id);
        li.classList.add('unread');

        const a = document.createElement('a');
        a.href = data.data.url || '#';
        a.classList.add('dropdown-item', 'd-flex', 'justify-content-between', 'align-items-start', 'flex-column');
        a.innerHTML = `
            <div>${data.data.message ?? 'New message'}</div>
            <small class="text-muted mt-1" data-timestamp="${new Date().toISOString()}">Just now</small>
        `;

        li.appendChild(a);

        const divider = notificationList.querySelector('hr.dropdown-divider');
        notificationList.insertBefore(li, divider);

        let count = parseInt(countBadge.textContent) || 0;
        count++;
        countBadge.textContent = count;
        countBadge.style.display = 'inline-block';
    });

    // Format relative time
    function updateRelativeTimes() {
        const elements = document.querySelectorAll('[data-timestamp]');
        const now = new Date();

        elements.forEach(el => {
            const timestamp = new Date(el.getAttribute('data-timestamp'));
            const diff = Math.floor((now - timestamp) / 1000);

            let unit = 'second';
            let value = -diff;

            if (diff >= 60 && diff < 3600) {
                unit = 'minute';
                value = -Math.floor(diff / 60);
            } else if (diff >= 3600 && diff < 86400) {
                unit = 'hour';
                value = -Math.floor(diff / 3600);
            } else if (diff >= 86400) {
                unit = 'day';
                value = -Math.floor(diff / 86400);
            }

            const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' });
            el.textContent = rtf.format(value, unit);
        });
    }

    // Update timestamps every 30 seconds
    setInterval(updateRelativeTimes, 30 * 1000);
    updateRelativeTimes();
});

// Mark as read on dropdown open
document.getElementById('notificationDropdown').addEventListener('show.bs.dropdown', function () {
    const {
        csrfToken,
        markReadUrl
    } = window.NotificationConfig;

    const unreadNotificationIds = Array.from(document.querySelectorAll('#notification-list li.unread[data-notification-id]'))
        .map(li => li.getAttribute('data-notification-id'));

    if (unreadNotificationIds.length === 0) return;

    fetch(markReadUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ ids: unreadNotificationIds }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const countBadge = document.getElementById('notification-count');
            countBadge.style.display = 'none';
            countBadge.textContent = '0';

            unreadNotificationIds.forEach(id => {
                const item = document.querySelector(`#notification-list li[data-notification-id="${id}"]`);
                if (item) item.classList.remove('unread');
                item?.classList.add('read');
            });
        }
    })
    .catch(console.error);
});
