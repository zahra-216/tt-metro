/*
 * Body-copy typesetting.
 *
 * Paragraphs are left-aligned by default. A paragraph (or list item) is justified only when:
 *   - it has 4 or more lines, and
 *   - the extra space added between its words stays small.
 * To get there the font size is nudged by up to 1.5px either way. If no size gives tidy spacing, the paragraph
 * stays left-aligned, so a long gap between words never shows.
 *
 * It re-runs when the window is resized or turned, and after a click (tabs and accordions reveal hidden text).
 */
const SELECTOR = 'main p, main li, footer p, [data-typeset]';
const MIN_LINES = 4;
const NUDGES = [0, -0.25, 0.25, -0.5, 0.5, -0.75, 0.75, -1, 1, -1.25, 1.25, -1.5, 1.5]; // px around the normal font size
const GOOD = 0.32;  // extra width added to each gap, as a fraction of the font size: tidy
const LIMIT = 0.5;  // the worst gap accepted when no size reaches GOOD (a normal gap is about 0.27)

const doneAt = new WeakMap(); // element -> its width when it was last typeset

function eligible(el) {
    if (el.closest('[data-no-typeset]')) return false;
    const cs = getComputedStyle(el);
    if (!['left', 'start', 'justify'].includes(cs.textAlign)) return false; // centred / right-aligned blocks keep theirs
    if (/flex|grid/.test(cs.display)) return false; // their text sits in anonymous boxes; mark the text with data-typeset instead
    if (el.querySelector('p, ul, ol, li, div, table, blockquote, h1, h2, h3, h4, h5, h6')) return false;
    return el.textContent.trim().length > 0;
}

/** Lines and the worst extra gap (as a fraction of the font size) if this text were justified at its current size. */
function measure(el) {
    const cs = getComputedStyle(el);
    const fs = parseFloat(cs.fontSize);
    const width = el.getBoundingClientRect().width
        - parseFloat(cs.paddingLeft) - parseFloat(cs.paddingRight)
        - parseFloat(cs.borderLeftWidth) - parseFloat(cs.borderRightWidth);
    if (!(width > 0)) return null; // hidden right now

    const range = document.createRange();
    const words = [];
    const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
    for (let node = walker.nextNode(); node; node = walker.nextNode()) {
        const re = /\S+/g;
        let m;
        while ((m = re.exec(node.nodeValue))) {
            range.setStart(node, m.index);
            range.setEnd(node, m.index + m[0].length);
            for (const r of range.getClientRects()) {
                if (r.width > 0.5) words.push({ cy: (r.top + r.bottom) / 2, left: r.left, right: r.right });
            }
        }
    }
    if (!words.length) return null;

    words.sort((a, b) => a.cy - b.cy || a.left - b.left);
    const lines = [];
    for (const w of words) {
        const last = lines[lines.length - 1];
        if (last && Math.abs(w.cy - last.cy) < fs * 0.5) last.items.push(w);
        else lines.push({ cy: w.cy, items: [w] });
    }

    let worst = 0;
    lines.forEach((line, i) => {
        if (i === lines.length - 1) return; // the last line is never stretched
        const items = line.items.sort((a, b) => a.left - b.left);
        let gaps = 0;
        for (let k = 1; k < items.length; k++) {
            if (items[k].left - items[k - 1].right > fs * 0.08) gaps++;
        }
        const spare = width - (items[items.length - 1].right - items[0].left);
        if (spare <= 1) return;
        // a line with no gap to stretch (one long word) cannot be justified
        worst = Math.max(worst, gaps ? spare / gaps / fs : (spare > width * 0.2 ? 9 : 0));
    });

    return { lines: lines.length, worst };
}

function typeset(el) {
    el.style.fontSize = '';
    delete el.dataset.fit;
    if (!eligible(el)) return;

    el.dataset.fit = 'm'; // left-aligned with hyphenation, exactly as it will wrap when justified
    const base = parseFloat(getComputedStyle(el).fontSize);
    const first = measure(el);
    if (!first) { delete el.dataset.fit; return; }
    doneAt.set(el, el.getBoundingClientRect().width);

    if (first.lines < MIN_LINES) { el.dataset.fit = 'l'; return; }

    let best = null;
    for (const d of NUDGES) {
        el.style.fontSize = (base + d) + 'px';
        const r = measure(el);
        if (!r || r.lines < MIN_LINES) continue;
        if (r.worst <= GOOD) { best = { d, worst: r.worst }; break; }
        if (!best || r.worst < best.worst) best = { d, worst: r.worst };
    }

    if (best && best.worst <= LIMIT) {
        el.style.fontSize = best.d === 0 ? '' : (base + best.d) + 'px';
        el.dataset.fit = 'j';
    } else {
        el.style.fontSize = '';
        el.dataset.fit = 'l';
    }
}

// give the browser a chance to paint between batches (a message hop is not slowed down in background tabs)
const yieldNow = () => new Promise((resolve) => {
    const channel = new MessageChannel();
    channel.port1.onmessage = () => resolve();
    channel.port2.postMessage(0);
});

let running = false;
async function run(all = false) {
    if (running) return;
    running = true;
    const todo = [...document.querySelectorAll(SELECTOR)].filter((el) => all
        || !doneAt.has(el)
        || Math.abs(doneAt.get(el) - el.getBoundingClientRect().width) > 0.5);

    while (todo.length) {
        todo.splice(0, 12).forEach(typeset);
        if (todo.length) await yieldNow();
    }
    running = false;
    document.documentElement.dataset.typesetReady = 'done';
}

let timer;
const later = (ms, all) => { clearTimeout(timer); timer = setTimeout(() => { running = false; run(all); }, ms); };

const start = () => (document.fonts && document.fonts.ready ? document.fonts.ready.then(() => run(true)) : run(true));
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
window.addEventListener('resize', () => later(200, false));
window.addEventListener('orientationchange', () => later(300, true));
document.addEventListener('click', () => later(450, false)); // tabs / accordions reveal hidden text
