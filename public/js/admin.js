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
})();
