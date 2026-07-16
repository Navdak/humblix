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
