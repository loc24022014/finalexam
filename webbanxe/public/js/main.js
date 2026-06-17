// Loc_Car_1273 - main.js

document.addEventListener('DOMContentLoaded', () => {

    // ── Navbar scroll effect ──
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });
    }

    // ── Navbar search submit ──
    const searchForm = document.getElementById('navbar-search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const q = document.getElementById('navbar-search-input').value.trim();
            const projectBase = typeof BASE_URL !== 'undefined' ? BASE_URL : '';
            window.location.href = projectBase + '/Search/index' + (q ? '?keyword=' + encodeURIComponent(q) : '');
        });
    }

    // ── Image upload preview ──
    const imageInput = document.getElementById('image-input');
    const newPreview = document.getElementById('new-preview');
    if (imageInput && newPreview) {
        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => {
                    newPreview.src = ev.target.result;
                    newPreview.style.display = 'block';
                    const placeholder = document.getElementById('new-placeholder');
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ── Toast auto-dismiss ──
    document.querySelectorAll('.toast').forEach(toast => {
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease forwards';
            toast.addEventListener('animationend', () => toast.remove());
        }, 4000);
    });

    // ── Toast keyframes ──
    const style = document.createElement('style');
    style.textContent = `@keyframes slideOutRight { to { transform: translateX(120%); opacity: 0; } }`;
    document.head.appendChild(style);

    // ── Brand chip filter (search page) ──
    document.querySelectorAll('.brand-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            const brandInput = document.getElementById('brand-input');
            if (!brandInput) return;
            if (chip.classList.contains('active')) {
                chip.classList.remove('active');
                brandInput.value = '';
            } else {
                document.querySelectorAll('.brand-chip').forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                brandInput.value = chip.dataset.brand;
            }
        });
    });

    // ── Animate count numbers ──
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let start = 0;
                    const step = target / (1500 / 16);
                    const timer = setInterval(() => {
                        start += step;
                        if (start >= target) { el.textContent = target; clearInterval(timer); return; }
                        el.textContent = Math.floor(start);
                    }, 16);
                    obs.disconnect();
                }
            });
        }, { threshold: 0.5 });
        obs.observe(el);
    });

    // ── Navbar Dropdown — Hover on desktop, click on mobile ──
    const navItems = document.querySelectorAll('.nav-item');
    const userMenu = document.querySelector('.user-menu');
    let isMobile = window.innerWidth < 768;

    navItems.forEach(item => {
        const link = item.querySelector('.nav-link');
        const menu = item.querySelector('.dropdown-menu');

        if (link && menu) {
            // Desktop: hover to open/close
            item.addEventListener('mouseenter', () => {
                if (!isMobile) item.classList.add('open');
            });
            item.addEventListener('mouseleave', () => {
                if (!isMobile) item.classList.remove('open');
            });

            // Mobile: click to toggle (only block nav if href is '#')
            link.addEventListener('click', (e) => {
                if (!isMobile) return; // desktop: let native href work
                const href = link.getAttribute('href');
                if (href && href !== '#') return; // real URL: let it navigate
                e.preventDefault();
                const isOpen = item.classList.contains('open');
                navItems.forEach(other => other.classList.remove('open'));
                if (!isOpen) item.classList.add('open');
            });
        }
    });

    // ── User menu toggle ──
    if (userMenu) {
        const userBtn = userMenu.querySelector('.user-btn');
        if (userBtn) {
            userBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const isOpen = userMenu.classList.contains('open');
                navItems.forEach(item => item.classList.remove('open'));
                userMenu.classList.toggle('open', !isOpen);
            });
        }
    }

    // ── Close all menus on outside click ──
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.nav-item') && !e.target.closest('.user-menu')) {
            navItems.forEach(item => item.classList.remove('open'));
            if (userMenu) userMenu.classList.remove('open');
        }
    });

    // ── Resize handler ──
    window.addEventListener('resize', () => {
        isMobile = window.innerWidth < 768;
        if (!isMobile) navItems.forEach(item => item.classList.remove('open'));
    });
});