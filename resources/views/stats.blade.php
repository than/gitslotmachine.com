<x-terminal-layout title="Stats" activeTab="stats">
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║           STATISTICS v1.0.0          ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">📊 ANALYTICS</h1>
        <p class="text-sm sm:text-lg" style="color: var(--term-dim);">&gt; Real-world pattern distribution</p>
    </header>

    <livewire:stats.global-stats />
</x-terminal-layout>
