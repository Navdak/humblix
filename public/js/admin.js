(() => {
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
