/* =========================================================
   App JavaScript — Sidebar, Theme, and Utilities
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    // ===== Sidebar Toggle (Mobile) =====
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => sidebar?.classList.add('show'));
    }
    if (sidebarClose) {
        sidebarClose.addEventListener('click', () => sidebar?.classList.remove('show'));
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => sidebar?.classList.remove('show'));
    }

    // ===== Theme Toggle =====
    const themeToggle = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('czm-theme') || 'dark';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-bs-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('czm-theme', next);
            updateThemeIcon(next);
        });
    }

    function updateThemeIcon(theme) {
        const icon = themeToggle?.querySelector('i');
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
        }
    }

    // ===== Locale Toggle =====
    const localeToggle = document.getElementById('localeToggle');
    if (localeToggle) {
        localeToggle.addEventListener('click', () => {
            const current = localeToggle.querySelector('.locale-badge')?.textContent?.trim();
            // In a full app, this would make an AJAX call to switch locale
            const newLocale = current === 'EN' ? 'বাং' : 'EN';
            const badge = localeToggle.querySelector('.locale-badge');
            if (badge) badge.textContent = newLocale;
        });
    }

    // ===== Auto-dismiss alerts =====
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert?.close();
        }, 5000);
    });

    // ===== Number Formatting Helper =====
    window.CZM = window.CZM || {};
    window.CZM.formatMoney = function (amount, currency = '৳') {
        return currency + ' ' + Number(amount).toLocaleString('en-BD', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        });
    };

    // ===== Chart.js Global Defaults =====
    if (typeof Chart !== 'undefined') {
        Chart.defaults.color = getComputedStyle(document.documentElement)
            .getPropertyValue('--czm-text-secondary').trim() || '#94a3b8';
        Chart.defaults.borderColor = getComputedStyle(document.documentElement)
            .getPropertyValue('--czm-border').trim() || 'rgba(255,255,255,0.08)';
        Chart.defaults.font.family = "'Inter', sans-serif";
    }

    // ===== Tooltips Init =====
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
});
