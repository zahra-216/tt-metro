import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import './typeset';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.start();

// The page names can be changed in the admin, so make sure the top menu still fits the screen:
// first tighten the spacing and, if that is not enough, show the menu button instead.
const fitMenu = () => {
    const root = document.documentElement;
    const row = document.querySelector('[data-header-row]');
    if (!row) return;
    root.classList.remove('nav-compact', 'nav-tight');
    const tooWide = () => row.scrollWidth > row.clientWidth + 1;
    if (!tooWide()) return;
    root.classList.add('nav-compact');
    if (tooWide()) root.classList.replace('nav-compact', 'nav-tight');
};
fitMenu();
document.fonts?.ready.then(fitMenu);
let fitFrame;
window.addEventListener('resize', () => {
    cancelAnimationFrame(fitFrame);
    fitFrame = requestAnimationFrame(fitMenu);
});

// Scroll-reveal
const reveal = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
        if (e.isIntersecting) {
            e.target.classList.add('in');
            reveal.unobserve(e.target);
        }
    });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach((el) => reveal.observe(el));

// Count-up numbers
const counter = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
        if (!e.isIntersecting) return;
        counter.unobserve(e.target);
        const el = e.target;
        const target = parseInt(el.dataset.count, 10);
        if (Number.isNaN(target) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        const start = performance.now();
        const tick = (now) => {
            const p = Math.min((now - start) / 1400, 1);
            el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString();
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    });
}, { threshold: 0.5 });
document.querySelectorAll('[data-count]').forEach((el) => counter.observe(el));