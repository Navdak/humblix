(() => {
    const themeKey = 'humelix_admin_theme';
    const themeToggle = document.querySelector('[data-admin-theme-toggle]');
    const root = document.documentElement;

    const isDark = () => root.classList.contains('admin-theme-dark-root');
    const setTheme = (theme) => {
        const dark = theme === 'dark';
        root.classList.toggle('admin-theme-dark-root', dark);

        try {
            window.localStorage.setItem(themeKey, dark ? 'dark' : 'light');
        } catch (error) {}

        if (themeToggle) {
            themeToggle.setAttribute('aria-pressed', String(dark));
            themeToggle.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
            themeToggle.title = dark ? 'Switch to light mode' : 'Switch to dark mode';
        }
    };

    if (themeToggle) {
        setTheme(isDark() ? 'dark' : 'light');
        themeToggle.addEventListener('click', () => setTheme(isDark() ? 'light' : 'dark'));
    }

    const body = document.body;
    const sidebar = document.querySelector('[data-admin-sidebar]');
    const overlay = document.querySelector('[data-admin-overlay]');
    const toggles = document.querySelectorAll('[data-admin-menu-toggle]');

    const setSidebar = (open) => {
        sidebar?.classList.toggle('is-open', open);
        overlay?.toggleAttribute('hidden', !open);
        toggles.forEach((button) => button.setAttribute('aria-expanded', String(open)));
        body.classList.toggle('admin-menu-open', open);
    };

    toggles.forEach((button) => button.addEventListener('click', () => setSidebar(!sidebar?.classList.contains('is-open'))));
    overlay?.addEventListener('click', () => setSidebar(false));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') setSidebar(false); });
    sidebar?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => { if (innerWidth < 1024) setSidebar(false); }));

    const searchInput = document.querySelector('[data-admin-search]');
    const searchResults = document.querySelector('[data-admin-search-results]');
    if (searchInput && searchResults) {
        const items = [...document.querySelectorAll('.admin-nav a')]
            .map((link) => ({
                label: link.textContent.trim().replace(/\s+/g, ' '),
                href: link.href,
                group: link.closest('.admin-nav-group')?.querySelector('.admin-nav-label')?.textContent.trim() || 'Admin',
            }))
            .filter((item) => item.label && item.href);

        const renderSearch = () => {
            const query = searchInput.value.trim().toLowerCase();
            const matches = query
                ? items.filter((item) => `${item.group} ${item.label}`.toLowerCase().includes(query)).slice(0, 8)
                : [];

            searchResults.hidden = matches.length === 0;
            searchResults.innerHTML = matches.map((item) => `
                <a href="${item.href}">
                    <small>${item.group}</small>
                    <strong>${item.label}</strong>
                </a>
            `).join('');
        };

        searchInput.addEventListener('input', renderSearch);
        searchInput.addEventListener('focus', renderSearch);
        searchInput.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') return;
            const first = searchResults.querySelector('a');
            if (first) {
                event.preventDefault();
                first.click();
            }
        });
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.admin-search-wrap')) searchResults.hidden = true;
        });
    }

    document.querySelectorAll('[data-admin-greeting]').forEach((element) => {
        const name = element.dataset.adminGreetingName || 'Admin';
        const hour = new Date().getHours();
        let greeting = 'Good evening';
        let icon = '🌙';

        if (hour >= 5 && hour < 12) {
            greeting = 'Good morning';
            icon = '☀️';
        } else if (hour >= 12 && hour < 17) {
            greeting = 'Good afternoon';
            icon = '🌤️';
        }

        element.textContent = `${greeting} ${icon}, ${name}`;
    });
})();

(() => {
    const root = document.querySelector('[data-admin-notifications]');
    if (!root) return;

    const endpoint = root.dataset.endpoint;
    const readEndpointTemplate = root.dataset.readEndpointTemplate || '';
    const readModuleEndpointTemplate = root.dataset.readModuleEndpointTemplate || '';
    const readAllEndpoint = root.dataset.readAllEndpoint;
    const currentModule = root.dataset.currentModule || '';
    const currentRoute = root.dataset.currentRoute || '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const toggle = root.querySelector('[data-notification-toggle]');
    const panel = root.querySelector('[data-notification-panel]');
    const list = root.querySelector('[data-notification-list]');
    const countBadge = root.querySelector('[data-notification-count]');
    const dot = root.querySelector('[data-notification-dot]');
    const summary = root.querySelector('[data-notification-summary]');
    const refreshButton = root.querySelector('[data-notification-refresh]');
    const readAllButton = root.querySelector('[data-notification-read-all]');
    const toastStack = document.querySelector('[data-admin-toast-stack]');
    const liveBanner = document.querySelector('[data-live-list-banner]');
    const liveMessage = document.querySelector('[data-live-list-message]');
    const liveRefresh = document.querySelector('[data-live-list-refresh]');
    let lastSeenId = Number(window.localStorage.getItem('humelix:lastNotificationId') || 0);
    let bootstrapped = false;
    let pollingTimer;
    let isFetching = false;

    const escapeHtml = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const intervalMs = () => {
        if (document.hidden) return 60000;
        if (!panel?.hidden || currentRoute === 'admin.dashboard') return 10000;
        return 15000;
    };

    const schedule = () => {
        window.clearTimeout(pollingTimer);
        pollingTimer = window.setTimeout(() => fetchNotifications(), intervalMs());
    };

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrfToken ? {'X-CSRF-TOKEN': csrfToken} : {}),
                ...(options.headers || {}),
            },
            ...options,
        });

        if (!response.ok) throw new Error(`Request failed with ${response.status}`);
        return response.json();
    };

    const renderCount = (count) => {
        const unreadCount = Number(count || 0);
        if (countBadge) {
            countBadge.textContent = unreadCount > 9 ? '9+' : String(unreadCount);
            countBadge.hidden = unreadCount <= 0;
        }
        if (dot) dot.hidden = unreadCount <= 0;
        if (summary) summary.textContent = unreadCount ? `${unreadCount} unread update${unreadCount === 1 ? '' : 's'}` : 'All caught up';
    };

    const notificationUrl = (notification) => notification.action_url || '#';

    const renderList = (notifications) => {
        if (!list) return;
        if (!notifications.length) {
            list.innerHTML = '<div class="admin-notification-empty">No notifications yet.</div>';
            return;
        }

        list.innerHTML = notifications.map((notification) => `
            <article class="admin-notification-item ${notification.is_unread ? 'is-unread' : ''}" data-notification-id="${notification.id}">
                <span class="admin-notification-mark"></span>
                <div>
                    <strong>${escapeHtml(notification.title)}</strong>
                    <p>${escapeHtml(notification.message || 'New admin update available.')}</p>
                    <small>${escapeHtml(notification.human_time || '')}</small>
                </div>
                <a href="${escapeHtml(notificationUrl(notification))}" data-notification-open="${notification.id}">Open</a>
            </article>
        `).join('');
    };

    const showToast = (notification) => {
        if (!toastStack || !notification?.is_unread) return;

        const toast = document.createElement('article');
        toast.className = 'admin-toast';
        toast.innerHTML = `
            <span></span>
            <div>
                <strong>${escapeHtml(notification.title)}</strong>
                <p>${escapeHtml(notification.message || 'New admin update available.')}</p>
            </div>
            <button type="button" aria-label="Dismiss notification">×</button>
        `;
        toast.querySelector('button')?.addEventListener('click', () => toast.remove());
        toast.addEventListener('click', (event) => {
            if (event.target.closest('button')) return;
            if (notification.action_url) window.location.href = notification.action_url;
        });
        toastStack.appendChild(toast);
        window.setTimeout(() => toast.remove(), 8000);
    };

    const renderLiveBanner = (data) => {
        if (!liveBanner || !currentModule) return;
        const count = Number(data?.list_updates?.[currentModule] || 0);
        liveBanner.hidden = count <= 0;
        if (liveMessage && count > 0) {
            const messages = {
                enquiries: `${count} new enquiry update${count === 1 ? '' : 's'} available.`,
                client_jobs: `${count} new client job message${count === 1 ? '' : 's'} available.`,
            };
            liveMessage.textContent = messages[currentModule] || `${count} fresh update${count === 1 ? '' : 's'} available.`;
        }
    };

    const fetchNotifications = async () => {
        if (isFetching || !endpoint) return;
        isFetching = true;
        try {
            const data = await request(endpoint);
            const notifications = Array.isArray(data.notifications) ? data.notifications : [];
            renderCount(data.unread_count);
            renderList(notifications);
            renderLiveBanner(data);

            const latestId = Number(data.latest_id || 0);
            if (!bootstrapped) {
                lastSeenId = Math.max(lastSeenId, latestId);
                bootstrapped = true;
            } else if (latestId > lastSeenId) {
                notifications
                    .filter((notification) => Number(notification.id) > lastSeenId)
                    .reverse()
                    .forEach(showToast);
                lastSeenId = latestId;
            }

            window.localStorage.setItem('humelix:lastNotificationId', String(lastSeenId));
        } catch (error) {
            if (summary) summary.textContent = 'Unable to check updates';
        } finally {
            isFetching = false;
            schedule();
        }
    };

    const markRead = async (id) => {
        if (!id || !readEndpointTemplate) return;
        await request(readEndpointTemplate.replace('__ID__', id), {method: 'PATCH'});
    };

    const markModuleRead = async (module) => {
        if (!module || !readModuleEndpointTemplate) return;
        await request(readModuleEndpointTemplate.replace('__MODULE__', encodeURIComponent(module)), {method: 'PATCH'});
    };

    toggle?.addEventListener('click', () => {
        const isOpen = panel?.hidden;
        if (panel) panel.hidden = !isOpen;
        toggle.setAttribute('aria-expanded', String(Boolean(isOpen)));
        if (isOpen) fetchNotifications();
    });

    document.addEventListener('click', (event) => {
        if (!root.contains(event.target)) {
            if (panel) panel.hidden = true;
            toggle?.setAttribute('aria-expanded', 'false');
        }
    });

    refreshButton?.addEventListener('click', () => fetchNotifications());

    readAllButton?.addEventListener('click', async () => {
        if (!readAllEndpoint) return;
        await request(readAllEndpoint, {method: 'PATCH'});
        fetchNotifications();
    });

    list?.addEventListener('click', async (event) => {
        const link = event.target.closest('[data-notification-open]');
        if (!link) return;
        event.preventDefault();
        const id = link.dataset.notificationOpen;
        await markRead(id);
        window.location.href = link.href;
    });

    liveRefresh?.addEventListener('click', async () => {
        liveRefresh.disabled = true;
        try {
            await markModuleRead(currentModule);
        } catch (error) {
            // Reload anyway so the admin still sees the newest records.
        } finally {
            window.location.reload();
        }
    });

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) fetchNotifications();
        else schedule();
    });

    fetchNotifications();
})();
