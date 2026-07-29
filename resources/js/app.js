import './bootstrap';
import { detectPattern } from './patterns.js';
import katex from 'katex';
import 'katex/dist/katex.min.css';

// Make detectPattern available globally
window.detectPattern = detectPattern;

// One shared tooltip element, lazily created, reused by every formula part.
function formulaTooltip() {
    let tip = document.getElementById('formula-tooltip');
    if (!tip) {
        tip = document.createElement('div');
        tip.id = 'formula-tooltip';
        tip.className = 'formula-tooltip';
        tip.setAttribute('role', 'tooltip');
        document.body.appendChild(tip);
    }
    return tip;
}

// The part the tooltip is currently anchored to, so scrolling can reposition it.
let activeFormulaPart = null;

function positionFormulaTip(part) {
    const tip = formulaTooltip();
    const r = part.getBoundingClientRect();

    // Measure at the origin, not at wherever the previous anchor left us. The tip is
    // absolutely positioned with a max-width and no width, so its shrink-to-fit width
    // is measured against the space remaining to the right of its current `left` —
    // measuring in place makes it wrap narrower and taller than it will actually be.
    tip.style.left = '0px';
    tip.style.top = '0px';
    const tr = tip.getBoundingClientRect();

    // Centre above the part, clamped to the viewport.
    let left = r.left + r.width / 2 - tr.width / 2 + window.scrollX;
    left = Math.max(8, Math.min(left, window.scrollX + document.documentElement.clientWidth - tr.width - 8));
    tip.style.left = `${left}px`;

    // Flip below when there isn't room above — the first table row sits near enough to
    // the top that the tip would otherwise render off-screen.
    const above = r.top - tr.height - 8;
    tip.style.top = `${(above < 8 ? r.bottom + 8 : above) + window.scrollY}px`;
}

function showFormulaTip(part, text) {
    const tip = formulaTooltip();
    tip.textContent = text;
    tip.classList.add('is-visible');
    activeFormulaPart = part;
    // describedby, not label: the KaTeX span's accessible name stays the maths,
    // and the explanation is announced as a description on top of it.
    part.setAttribute('aria-describedby', 'formula-tooltip');
    positionFormulaTip(part);
}

function hideFormulaTip() {
    const tip = document.getElementById('formula-tooltip');
    if (tip) tip.classList.remove('is-visible');
    if (activeFormulaPart) activeFormulaPart.removeAttribute('aria-describedby');
    activeFormulaPart = null;
}

// WCAG 1.4.13: hover/focus content must be dismissible without moving the pointer
// or focus.
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') hideFormulaTip();
});

// A tooltip anchored on focus would otherwise strand once the anchor moves. capture
// is required, not optional: scroll events fired on an element do not bubble, and the
// container that actually moves here is the odds table's own overflow-x-auto wrapper,
// not the document.
const repositionFormulaTip = () => {
    if (activeFormulaPart) positionFormulaTip(activeFormulaPart);
};

window.addEventListener('scroll', repositionFormulaTip, { passive: true, capture: true });
window.addEventListener('resize', repositionFormulaTip, { passive: true });

// Attach hover/focus tooltips to every \htmlData{tip=i} span KaTeX rendered.
function wireFormulaTips(el, tips) {
    el.querySelectorAll('[data-tip]').forEach((part) => {
        const text = tips[Number(part.dataset.tip)];
        if (!text) return;
        part.setAttribute('tabindex', '0');
        part.classList.add('formula-part');
        const show = () => showFormulaTip(part, text);
        part.addEventListener('mouseenter', show);
        part.addEventListener('focus', show);
        // Don't let the pointer leaving yank a tooltip the keyboard is still holding.
        part.addEventListener('mouseleave', () => {
            if (document.activeElement !== part) hideFormulaTip();
        });
        part.addEventListener('blur', hideFormulaTip);
    });
}

// Render LaTeX odds formulas (used on the /odds page). Idempotent.
window.renderFormulas = function () {
    document.querySelectorAll('.katex-formula').forEach((el) => {
        const latex = el.dataset.latex;
        if (!latex || el.dataset.rendered) return;
        try {
            // `trust` is scoped to \htmlData only — the sole command FormulaAnnotator
            // emits. This keeps \href/\includegraphics un-trusted if the formula
            // source ever stops being static.
            katex.render(latex, el, {
                throwOnError: false,
                displayMode: false,
                trust: (context) => context.command === '\\htmlData',
                // Silence only the htmlExtension warning \htmlData itself triggers;
                // every other diagnostic still surfaces for formulas added later.
                strict: (errorCode) => (errorCode === 'htmlExtension' ? 'ignore' : 'warn'),
            });
            let tips = [];
            try { tips = JSON.parse(el.dataset.tips || '[]'); } catch (e) { tips = []; }
            wireFormulaTips(el, tips);
            el.dataset.rendered = '1';
        } catch (e) {
            el.textContent = latex;
        }
    });
};

// Global hash highlighting function
window.highlightHashes = function() {
    document.querySelectorAll('.hash-display').forEach(el => {
        const hash = el.dataset.hash;
        if (!hash) return;

        try {
            const pattern = window.detectPattern(hash);
            const highlights = pattern.highlightIndices || [];

            let html = '';
            for (let i = 0; i < hash.length; i++) {
                if (highlights.includes(i)) {
                    html += `<span class="inline-block px-1" style="background: var(--term-text); color: var(--term-bg); font-weight: 900;">${hash[i]}</span>`;
                } else {
                    html += `<span class="inline-block px-1">${hash[i]}</span>`;
                }
            }
            el.innerHTML = html;
        } catch (e) {
            el.textContent = hash;
        }
    });
};

// Run on page load
document.addEventListener('DOMContentLoaded', window.highlightHashes);
document.addEventListener('DOMContentLoaded', window.renderFormulas);

// Also run after Livewire updates (if Livewire is present)
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', () => {
        window.highlightHashes();
    });
});
