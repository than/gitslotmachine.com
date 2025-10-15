<div class="space-y-8">
    <!-- Changelog Tabs -->
    <div>
        <div class="border bg-black/30 p-4" style="border-color: var(--term-accent);">
            <nav class="flex space-x-8 justify-center">
                <button
                    wire:click="$set('tab', 'app')"
                    class="py-2 px-4 font-mono font-bold transition-all"
                    style="color: {{ $tab === 'app' ? 'var(--term-text)' : 'var(--term-dim)' }}; {{ $tab === 'app' ? 'background: rgba(var(--term-accent-rgb), 0.2); border: 1px solid var(--term-accent);' : '' }}">
                    [WEB APP]
                </button>
                <button
                    wire:click="$set('tab', 'cli')"
                    class="py-2 px-4 font-mono font-bold transition-all"
                    style="color: {{ $tab === 'cli' ? 'var(--term-text)' : 'var(--term-dim)' }}; {{ $tab === 'cli' ? 'background: rgba(var(--term-accent-rgb), 0.2); border: 1px solid var(--term-accent);' : '' }}">
                    [CLI]
                </button>
            </nav>
        </div>

        <!-- Web App Changelog -->
        @if($tab === 'app')
        <div class="mt-8 border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <div class="space-y-8">
                <!-- Version 1.0.2 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.0.2</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-15</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Improved text readability in Dracula (purple) theme</li>
                                <li>Updated --term-dim color for better contrast on dark backgrounds</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.0.1 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.0.1</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-15</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Random theme selection for first-time visitors</li>
                                <li>Theme persists in localStorage after initial random selection</li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: #66d9ef;">📄 Documentation:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Added comprehensive privacy policy page</li>
                                <li>Clear explanations of data collection and user rights</li>
                                <li>Added [PRIVACY] navigation link</li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🎨 UI/UX:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Swapped header text - "COMMIT & SPIN" in ASCII box, "GIT SLOT MACHINE" as H1</li>
                                <li>Removed version number from landing page ASCII box</li>
                                <li>Added footer with GitHub Sponsors button</li>
                                <li>Footer includes links to GitHub repos and privacy policy</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.0.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.0.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-15</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">🚀 LAUNCHED:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Privacy mode for private repositories - display as *******/*******</li>
                                <li>Shields.io-style badges for README files</li>
                                <li>Livewire reconnection handling during deployments</li>
                                <li>Win streak tracking with detailed history</li>
                                <li>Streaks leaderboard tab with accordion details</li>
                                <li>Pattern-based payouts (poker-style hands)</li>
                                <li>Global leaderboards (daily and all-time)</li>
                                <li>Winner sharing with Open Graph images</li>
                                <li>Real-time stats and analytics</li>
                                <li>Retro terminal aesthetic with theme picker</li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Fixed badge endpoint PHP syntax error in heredoc</li>
                                <li>Fixed TWO_PAIR and THREE_PAIR pattern detection</li>
                                <li>Updated odds page with accurate probabilities</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- CLI Changelog -->
        @if($tab === 'cli')
        <div class="mt-8 border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <div class="space-y-8">
                <!-- Version 1.1.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.1.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-15</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Privacy mode for private repositories</li>
                                <li>Prompt during init if private repo detected</li>
                                <li>Obfuscate repo data - send "private/private" to API</li>
                                <li>Clear privacy notice explaining data handling</li>
                                <li>Repository names never stored on server</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.0.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.0.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-14</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">🚀 LAUNCHED:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Laravel API integration with authentication</li>
                                <li>Auto-sync balance with server after each play</li>
                                <li>Auth commands: login, logout, status</li>
                                <li>Sync command to compare local vs API balances</li>
                                <li>Config commands for API URL and sync settings</li>
                                <li>Auto-extract GitHub username from git remote</li>
                                <li>Privacy notices explaining data sent to server</li>
                                <li>API fallback domains for resilient connectivity</li>
                                <li>Animated slot machine display</li>
                                <li>Single-line compact mode</li>
                                <li>Post-commit hook for automatic gameplay</li>
                                <li>Pattern detection for poker-style hands</li>
                                <li>Local balance tracking</li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Fixed TWO_PAIR and THREE_PAIR pattern detection</li>
                                <li>Improved pattern matching logic</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
