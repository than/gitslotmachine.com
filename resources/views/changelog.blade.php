<x-terminal-layout title="Changelog" activeTab="changelog">
    <x-slot name="meta">
        <meta name="description" content="Track all updates to Git Slot Machine. View release history for both the web app and CLI. See what's new, what's fixed, and what's coming next.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/changelog') }}">
        <meta property="og:title" content="Changelog - Git Slot Machine">
        <meta property="og:description" content="Track all updates to Git Slot Machine. View release history for both the web app and CLI. See what's new, what's fixed, and what's coming next.">
        <meta property="og:image" content="https://gitslotmachine.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url('/changelog') }}">
        <meta name="twitter:title" content="Changelog - Git Slot Machine">
        <meta name="twitter:description" content="Track all updates to Git Slot Machine. View release history for both the web app and CLI. See what's new, what's fixed, and what's coming next.">
        <meta name="twitter:image" content="https://gitslotmachine.com/og-image.png">
    </x-slot>

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
