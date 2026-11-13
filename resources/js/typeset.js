/*
 * Body-copy typesetting.
 *
 * Paragraphs are left-aligned by default. A paragraph (or list item) is justified only when:
 *   - it has 4 or more lines, and
 *   - the extra space added between its words stays small.
 * To get there the font size is nudged by up to half a pixel either way and the letter spacing by a fraction
 * of a pixel (too small to see, but enough to change where lines break). If no size gives tidy spacing, the paragraph
 * stays left-aligned, so a long gap between words never shows.
 *
 * It re-runs when the window is resized or turned, and after a click (tabs and accordions reveal hidden text).
 */
import { softHyphenate } from './hyphenation-en';

const SELECTOR = 'main p, main li, footer p, [data-typeset]';
const MIN_LINES = 4;
const NUDGES = [0, -0.25, 0.25, -0.5, 0.5]; // px around the normal font size (small, so neighbouring paragraphs look alike)
const LETTER_SPACING = [0, 0.1, -0.1, 0.2, -0.2, 0.3, -0.3]; // px: tried only when font sizes alone are not tidy enough
const GOOD = 0.4;   // extra width added to each gap, as a fraction of the font size: tidy
const LIMIT = 0.6;  // the worst gap accepted when nothing reaches GOOD (a normal gap is about 0.27)
const TIME_BUDGET = 1500; // ms for the whole page before the letter-spacing search is skipped (slow phones)

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

function hyphenate(el) {
    const walker = document.createTreeWalker(el, NodeFilter.SHOW_TEXT);
    for (let node = walker.nextNode(); node; node = walker.nextNode()) {
        if (node.nodeValue.includes('­')) continue; // already done
        const next = softHyphenate(node.nodeValue);
        if (next !== node.nodeValue) node.nodeValue = next;
    }
}

let deadline = Infinity; // on a slow phone, stop the extra letter-spacing search after this moment

/** Try font sizes (then, if needed, tiny letter-spacing changes too) and keep the one with the tidiest gaps. */
function search(el, base) {
    let best = null;
    for (const spacing of LETTER_SPACING) {
        if (spacing && performance.now() > deadline) break;
        el.style.letterSpacing = spacing ? spacing + 'px' : '';
        for (const d of NUDGES) {
            el.style.fontSize = (base + d) + 'px';
            const r = measure(el);
            if (!r || r.lines < MIN_LINES) continue;
            if (!best || r.worst < best.worst) best = { d, spacing, worst: r.worst };
            if (r.worst <= GOOD) return best;
        }
        if (best && best.worst <= GOOD) break;
    }
    return best;
}

function typeset(el) {
    el.style.fontSize = '';
    el.style.letterSpacing = '';
    delete el.dataset.fit;
    if (!eligible(el)) return;

    el.dataset.fit = 'm'; // left-aligned with hyphenation, exactly as it will wrap when justified
    const base = parseFloat(getComputedStyle(el).fontSize);
    const first = measure(el);
    if (!first) { delete el.dataset.fit; return; }
    doneAt.set(el, el.getBoundingClientRect().width);

    if (first.lines < MIN_LINES) { el.dataset.fit = 'l'; return; }

    // Long enough to justify: let long words split across lines (invisible soft hyphens at known syllable
    // breaks), so the spaces between words stay even. Left-aligned text ignores them.
    hyphenate(el);

    const best = search(el, base);

    if (best && best.worst <= LIMIT) {
        el.style.fontSize = best.d === 0 ? '' : (base + best.d) + 'px';
        el.style.letterSpacing = best.spacing ? best.spacing + 'px' : '';
        el.dataset.fit = 'j';
    } else {
        el.style.fontSize = '';
        el.style.letterSpacing = '';
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
    const startedAt = performance.now();
    deadline = startedAt + TIME_BUDGET;
    const todo = [...document.querySelectorAll(SELECTOR)].filter((el) => all
        || !doneAt.has(el)
        || Math.abs(doneAt.get(el) - el.getBoundingClientRect().width) > 0.5);

    while (todo.length) {
        todo.splice(0, 12).forEach(typeset);
        if (todo.length) await yieldNow();
    }
    running = false;
    document.documentElement.dataset.typesetMs = Math.round(performance.now() - startedAt);
    document.documentElement.dataset.typesetReady = 'done';
}

let timer;
const later = (ms, all) => { clearTimeout(timer); timer = setTimeout(() => { running = false; run(all); }, ms); };

const start = () => (document.fonts && document.fonts.ready ? document.fonts.ready.then(() => run(true)) : run(true));
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
window.addEventListener('resize', () => later(200, false));
window.addEventListener('orientationchange', () => later(300, true));
document.addEventListener('click', () => later(450, false)); // tabs / accordions reveal hidden text
