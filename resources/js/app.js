import './bootstrap';
import { detectPattern } from './patterns.js';

// Make detectPattern available globally
window.detectPattern = detectPattern;

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

// Also run after Livewire updates (if Livewire is present)
document.addEventListener('livewire:init', () => {
    Livewire.hook('morph.updated', () => {
        window.highlightHashes();
    });
});
