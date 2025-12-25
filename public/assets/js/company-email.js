document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('sendTestEmailBtn');
    const statusEl = document.getElementById('testEmailStatus');

    if (!btn || !statusEl) {
        return; // page doesn't have test email button
    }

    btn.addEventListener('click', function () {
        statusEl.innerHTML = '⏳ Sending test email...';

        fetch(btn.dataset.url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': btn.dataset.csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusEl.innerHTML = `<span class="text-success">✅ ${data.message}</span>`;
            } else {
                statusEl.innerHTML = `<span class="text-danger">❌ ${data.message}</span>`;
            }
        })
        .catch(() => {
            statusEl.innerHTML = `<span class="text-danger">❌ Something went wrong</span>`;
        });
    });

});
