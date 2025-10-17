<x-terminal-layout title="Stats" activeTab="stats">
    <x-slot name="meta">
        <meta name="description" content="Live global analytics for Git Slot Machine. Real commits, real patterns, real probability. See which patterns hit most often and how players rank.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/stats') }}">
        <meta property="og:title" content="Live Global Stats - Git Slot Machine">
        <meta property="og:description" content="Live global analytics for Git Slot Machine. Real commits, real patterns, real probability. See which patterns hit most often and how players rank.">
        <meta property="og:image" content="https://gitslotmachine.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url('/stats') }}">
        <meta name="twitter:title" content="Live Global Stats - Git Slot Machine">
        <meta name="twitter:description" content="Live global analytics for Git Slot Machine. Real commits, real patterns, real probability. See which patterns hit most often and how players rank.">
        <meta name="twitter:image" content="https://gitslotmachine.com/og-image.png">
    </x-slot>

    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║      LIVE GLOBAL ANALYTICS v1.0      ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">📊 THE NUMBERS 📊</h1>
        <p class="text-sm sm:text-lg" style="color: var(--term-dim);">&gt; Real commits. Real patterns. Real probability.</p>
    </header>

    <livewire:stats.global-stats />
</x-terminal-layout>
