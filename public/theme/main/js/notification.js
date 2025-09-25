document.addEventListener('DOMContentLoaded', function () {
    const {
        pusherKey,
        pusherCluster,
        csrfToken,
        channelName,
        unreadUrl,
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
    
        // Remove "no notifications" message if present
        const noNotifElem = notificationList.querySelector('.text-muted');
        if (noNotifElem && noNotifElem.innerText.includes('No notifications')) {
            noNotifElem.remove();
        }
    
        // Create <li> element
        const li = document.createElement('li');
        li.setAttribute('data-notification-id', data.id);
        li.classList.add('notification-item', 'unread');
    
        // Create <a> element
        const a = document.createElement('a');
        a.href = data.data.url || '#';
        a.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'p-3', 'rounded', 'text-decoration-none');
        a.innerHTML = `
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-bell text-primary"></i>
                <span class="notification-text">${data.data.message ?? 'New message'}</span>
            </div>
            <small class="text-muted" data-timestamp="${new Date().toISOString()}">Just now</small>
        `;
    
        li.appendChild(a);
    
        // Insert before the first <li> (or append if list is empty)
        const firstItem = notificationList.querySelector('li');
        if (firstItem) {
            notificationList.insertBefore(li, firstItem);
        } else {
            notificationList.appendChild(li);
        }
    
        // Update count badge
        let count = parseInt(countBadge.textContent) || 0;
        count++;
        countBadge.textContent = count;
        countBadge.style.display = 'inline-block';
    });
    
    // channel.bind('notification.received', function (data) {
    //     console.log('✅ New notification received:', data);

    //     const noNotifElem = notificationList.querySelector('.text-muted');
    //     if (noNotifElem) noNotifElem.remove();

    //     const li = document.createElement('li');
    //     li.setAttribute('data-notification-id', data.id);
    //     li.classList.add('unread');

    //     const a = document.createElement('a');
    //     a.href = data.data.url || '#';
    //     a.classList.add('dropdown-item', 'd-flex', 'justify-content-between', 'align-items-start', 'flex-column');
    //     a.innerHTML = `
    //         <div>${data.data.message ?? 'New message'}</div>
    //         <small class="text-muted mt-1" data-timestamp="${new Date().toISOString()}">Just now</small>
    //     `;

    //     li.appendChild(a);

    //     const divider = notificationList.querySelector('hr.dropdown-divider');
    //     notificationList.insertBefore(li, divider);

    //     let count = parseInt(countBadge.textContent) || 0;
    //     count++;
    //     countBadge.textContent = count;
    //     countBadge.style.display = 'inline-block';
    // });
    function formatTimeAgo(dateString) {
        const now = new Date();
        const past = new Date(dateString);
        const seconds = Math.floor((now - past) / 1000);
    
        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return `${Math.floor(seconds / 60)} minute(s) ago`;
        if (seconds < 86400) return `${Math.floor(seconds / 3600)} hour(s) ago`;
        if (seconds < 604800) return `${Math.floor(seconds / 86400)} day(s) ago`;
    
        // fallback: show date in 'MMM DD, YYYY' format
        return past.toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' });
    }
    
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

    // Load existing unread notifications on page load
    fetch(unreadUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!Array.isArray(data) || data.length === 0) return;
    
        // Update count badge
        countBadge.textContent = data.length;
        countBadge.style.display = 'inline-block';
    
        // Clean up existing "no notifications" message
        const noNotifElem = notificationList.querySelector('.text-muted');
        if (noNotifElem && noNotifElem.innerText.includes('No notifications')) {
            noNotifElem.remove();
        }
    
        // Optional: Clear old notifications (if needed)
        // notificationList.innerHTML = '';
    
        data.forEach(notification => {
            const li = document.createElement('li');
            li.setAttribute('data-notification-id', notification.id);
            li.classList.add('notification-item', 'unread');
    
            const a = document.createElement('a');
            a.href = notification.data.url || '#';
            a.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'p-3', 'rounded', 'text-decoration-none');
            a.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-bell text-primary"></i>
                    <span class="notification-text">${notification.data.message ?? 'New message'}</span>
                </div>
                <small class="text-muted" data-timestamp="${notification.created_at}">${formatTimeAgo(notification.created_at)}</small>
            `;
    
            li.appendChild(a);
    
            const firstItem = notificationList.querySelector('li');
            if (firstItem) {
                notificationList.insertBefore(li, firstItem);
            } else {
                notificationList.appendChild(li);
            }
        });
    
        updateRelativeTimes(); // optional if you're using timeago updates
    })
    
    // .then(data => {
    //     if (!Array.isArray(data) || data.length === 0) return;

    //     countBadge.textContent = data.length;
    //     countBadge.style.display = 'inline-block';

    //     const divider = notificationList.querySelector('hr.dropdown-divider');
    //     const noNotifElem = notificationList.querySelector('.text-muted');
    //     if (noNotifElem) noNotifElem.remove();

    //     data.forEach(notification => {
    //         const li = document.createElement('li');
    //         li.setAttribute('data-notification-id', notification.id);
    //         li.classList.add('unread');

    //         const a = document.createElement('a');
    //         a.href = notification.data.url || '#';
    //         a.classList.add('dropdown-item', 'd-flex', 'justify-content-between', 'align-items-start', 'flex-column');
    //         a.innerHTML = `
    //             <div>${notification.data.message ?? 'New message'}</div>
    //             <small class="text-muted mt-1" data-timestamp="${notification.created_at}">Just now</small>
    //         `;

    //         li.appendChild(a);
    //         notificationList.insertBefore(li, divider);
    //     });

    //     updateRelativeTimes();
    // })
    .catch(err => {
        console.error('❌ Failed to load unread notifications:', err);
    });


});

// Mark as read on dropdown open
// document.getElementById('notificationDropdown').addEventListener('show.bs.dropdown', function () {
//     const {
//         csrfToken,
//         markReadUrl
//     } = window.NotificationConfig;

//     const unreadNotificationIds = Array.from(document.querySelectorAll('#notification-list li.unread[data-notification-id]'))
//         .map(li => li.getAttribute('data-notification-id'));

//     if (unreadNotificationIds.length === 0) return;

//     fetch(markReadUrl, {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': csrfToken,
//             'Content-Type': 'application/json',
//             'Accept': 'application/json',
//         },
//         body: JSON.stringify({ ids: unreadNotificationIds }),
//     })
//     .then(res => res.json())
//     .then(data => {
//         if (data.success) {
//             const countBadge = document.getElementById('notification-count');
//             countBadge.style.display = 'none';
//             countBadge.textContent = '0';

//             unreadNotificationIds.forEach(id => {
//                 const item = document.querySelector(`#notification-list li[data-notification-id="${id}"]`);
//                 if (item) item.classList.remove('unread');
//                 item?.classList.add('read');
//             });
//         }
//     })
//     .catch(console.error);
// });

document.addEventListener('DOMContentLoaded', function () {
    const {
        csrfToken,
        markReadUrl
    } = window.NotificationConfig;

    const notificationList = document.getElementById('notification-list');
    const countBadge = document.getElementById('notification-count');

    if (!notificationList) return;

    notificationList.addEventListener('click', function (e) {
        const li = e.target.closest('li[data-notification-id]');
        if (!li || !li.classList.contains('unread')) return;

        const notificationId = li.getAttribute('data-notification-id');
        const link = li.querySelector('a');
        const href = link?.getAttribute('href') || '#';

        // Prevent default navigation
        e.preventDefault();

        // Mark as read
        fetch(markReadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ id: notificationId }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Update UI
                li.classList.remove('unread');
                li.classList.add('read');

                let count = parseInt(countBadge.textContent) || 0;
                count = Math.max(0, count - 1);
                countBadge.textContent = count;
                if (count === 0) countBadge.style.display = 'none';

                // Redirect after marking as read
                if (href && href !== '#') {
                    window.location.href = href;
                }
            } else {
                // If failed, still navigate
                if (href && href !== '#') {
                    window.location.href = href;
                }
            }
        })
        .catch(err => {
            console.error('❌ Failed to mark as read:', err);
            if (href && href !== '#') {
                window.location.href = href;
            }
        });
    });
});
