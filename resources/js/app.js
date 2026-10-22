import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.start();

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