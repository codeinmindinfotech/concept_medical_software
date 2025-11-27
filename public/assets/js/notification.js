document.addEventListener('DOMContentLoaded', function () {
  const {
      pusherKey,
      pusherCluster,
      csrfToken,
      channelName,
      unreadUrl,
  } = window.NotificationConfig;

  const notificationList = document.getElementById('front_notification');
  const notificationIcon = document.getElementById('notification-icon');

  if (!notificationList || !channelName) {
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
      const id = data?.id ?? '';
      const sender = data.data.sender ?? '';
      const message = data.data.message ?? 'New message';
      const url = data.data.url || '#';
      const avatar = data?.data?.avatar_url || defaultAvatar;
      // Create <li> element
      const createdAt = data?.created_at || new Date().toISOString();

                    // Create <li> element
            const li = document.createElement('li');
            li.classList.add('notification-message', 'unread');
            li.setAttribute('data-notification-id', id);

            // Create <a> element
            const a = document.createElement('a');
            a.href = url;

            // Create notification block container
            const notifyBlock = document.createElement('div');
            notifyBlock.classList.add('notify-block', 'd-flex');

            // Avatar wrapper
            const avatarSpan = document.createElement('span');
            avatarSpan.classList.add('avatar');

            const img = document.createElement('img');
            img.classList.add('avatar-img');
            img.alt = sender ?? 'User';
            img.src = avatar ?? defaultAvatar;

            avatarSpan.appendChild(img);

            // Media body
            const mediaBody = document.createElement('div');
            mediaBody.classList.add('media-body');

            // <h6>Title + time</h6>
            const h6 = document.createElement('h6');
            h6.innerHTML = `
                ${sender}
                <span class="notification-time" data-timestamp="${createdAt}">
                    ${formatTimeAgo(createdAt)}
                </span>
            `;

            // <p class="noti-details">Message</p>
            const details = document.createElement('p');
            details.classList.add('noti-details');
            details.innerHTML = `
                ${message}
            `;

            // Build full structure
            mediaBody.appendChild(h6);
            mediaBody.appendChild(details);

            notifyBlock.appendChild(avatarSpan);
            notifyBlock.appendChild(mediaBody);
            a.appendChild(notifyBlock);
            li.appendChild(a);

            // Insert at top of list
            const firstItem = notificationList.querySelector('li');
            if (firstItem) {
                notificationList.insertBefore(li, firstItem);
            } else {
                notificationList.appendChild(li);
            }
  
  
      // Update count badge
      notificationIcon.classList.add('active-dot', 'active-dot-danger');

  });
  
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
    if (data.length > 0) {
        notificationIcon.classList.add('active-dot', 'active-dot-danger');
    } 

   
      // Clean up existing "no notifications" message
      const noNotifElem = notificationList.querySelector('.text-muted');
      if (noNotifElem && noNotifElem.innerText.includes('No notifications')) {
          noNotifElem.remove();
      }
  
      // Optional: Clear old notifications (if needed)
      // notificationList.innerHTML = '';
  
      data.forEach(notification => {
          // Create <li> element
          const id = notification?.id ?? '';
          const sender = notification.data.sender ?? '';
          const message = notification.data.message ?? 'New message';
          const url = notification.data.url || '#';
          const avatar = notification?.data?.avatar_url || defaultAvatar;


            const createdAt = notification?.created_at || new Date().toISOString();

                    // Create <li> element
            const li = document.createElement('li');
            li.classList.add('notification-message', 'unread');
            li.setAttribute('data-notification-id', id);

            // Create <a> element
            const a = document.createElement('a');
            a.href = url;

            // Create notification block container
            const notifyBlock = document.createElement('div');
            notifyBlock.classList.add('notify-block', 'd-flex');

            // Avatar wrapper
            const avatarSpan = document.createElement('span');
            avatarSpan.classList.add('avatar');

            const img = document.createElement('img');
            img.classList.add('avatar-img');
            img.alt = sender ?? 'User';
            img.src = avatar ?? defaultAvatar;

            avatarSpan.appendChild(img);

            // Media body
            const mediaBody = document.createElement('div');
            mediaBody.classList.add('media-body');

            // <h6>Title + time</h6>
            const h6 = document.createElement('h6');
            h6.innerHTML = `
                ${sender}
                <span class="notification-time" data-timestamp="${createdAt}">
                    ${formatTimeAgo(createdAt)}
                </span>
            `;

            // <p class="noti-details">Message</p>
            const details = document.createElement('p');
            details.classList.add('noti-details');
            details.innerHTML = `
                ${message}
            `;

            // Build full structure
            mediaBody.appendChild(h6);
            mediaBody.appendChild(details);

            notifyBlock.appendChild(avatarSpan);
            notifyBlock.appendChild(mediaBody);
            a.appendChild(notifyBlock);
            li.appendChild(a);

            // Insert at top of list
            const firstItem = notificationList.querySelector('li');
            if (firstItem) {
                notificationList.insertBefore(li, firstItem);
            } else {
                notificationList.appendChild(li);
            }
      });
  
      updateRelativeTimes(); // optional if you're using timeago updates
  })
  
  .catch(err => {
      console.error('❌ Failed to load unread notifications:', err);
  });


});


document.addEventListener('DOMContentLoaded', function () {
  const {
      csrfToken,
      markReadUrl
  } = window.NotificationConfig;

  const notificationList = document.getElementById('front_notification');
  const countBadge = document.getElementById('notification-dot');

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

              const unreadLeft = notificationList.querySelectorAll('.unread').length - 1;

            if (unreadLeft <= 0) {
                notificationDot.style.display = 'none';
            }
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