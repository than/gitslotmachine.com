<x-terminal-layout title="Changelog" activeTab="changelog">
    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║          CHANGELOG v1.0.0            ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">RELEASE HISTORY</h1>

        <p class="text-sm sm:text-lg font-mono mb-6 text-center" style="color: var(--term-dim);">
            &gt; Track all updates to the web app and CLI
        </p>
    </header>

    <!-- Livewire Changelog Component -->
    <livewire:changelog />
</x-terminal-layout>
