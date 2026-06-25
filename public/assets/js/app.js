document.addEventListener('DOMContentLoaded', () => {
    const shell = document.getElementById('app-shell');
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const backdrop = document.getElementById('sidebar-backdrop');

    if (!shell || !toggleBtn) {
        return;
    }

    const storageKey = 'invox-sidebar-collapsed';
    const mobileQuery = window.matchMedia('(max-width: 768px)');

    const isMobile = () => mobileQuery.matches;

    const setExpanded = (expanded) => {
        toggleBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        toggleBtn.setAttribute('aria-label', expanded ? 'Close navigation menu' : 'Open navigation menu');
        if (backdrop) {
            backdrop.setAttribute('aria-hidden', expanded && isMobile() ? 'false' : 'true');
        }
    };

    const isSidebarVisible = () => {
        if (isMobile()) {
            return shell.classList.contains('sidebar-open');
        }
        return !shell.classList.contains('sidebar-collapsed');
    };

    const openSidebar = () => {
        if (isMobile()) {
            shell.classList.add('sidebar-open');
        } else {
            shell.classList.remove('sidebar-collapsed');
            localStorage.setItem(storageKey, 'false');
        }
        setExpanded(true);
    };

    const closeSidebar = () => {
        if (isMobile()) {
            shell.classList.remove('sidebar-open');
        } else {
            shell.classList.add('sidebar-collapsed');
            localStorage.setItem(storageKey, 'true');
        }
        setExpanded(false);
    };

    const toggleSidebar = () => {
        if (isSidebarVisible()) {
            closeSidebar();
        } else {
            openSidebar();
        }
    };

    const applyDesktopPreference = () => {
        if (isMobile()) {
            shell.classList.remove('sidebar-collapsed');
            return;
        }

        shell.classList.remove('sidebar-open');
        const collapsed = localStorage.getItem(storageKey) === 'true';
        shell.classList.toggle('sidebar-collapsed', collapsed);
        setExpanded(!collapsed);
    };

    toggleBtn.addEventListener('click', toggleSidebar);
    backdrop?.addEventListener('click', closeSidebar);

    shell.querySelectorAll('.sidebar__link:not(.sidebar__link--disabled)').forEach((link) => {
        link.addEventListener('click', () => {
            if (isMobile()) {
                closeSidebar();
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isSidebarVisible()) {
            closeSidebar();
        }
    });

    mobileQuery.addEventListener('change', applyDesktopPreference);
    applyDesktopPreference();
});
