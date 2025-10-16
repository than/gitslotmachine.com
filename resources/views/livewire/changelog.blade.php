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
                <!-- Version 1.4.2 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.4.2</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">📄 Documentation:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Added winner sharing documentation to README and About page</li>
                                <li>Explained how CLI displays share URLs for wins</li>
                                <li>Documented OG image generation for social media</li>
                                <li>Feature was working but completely undocumented</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.4.1 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.4.1</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">📄 Documentation:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Added comprehensive repository badge documentation to README</li>
                                <li>Badge URL format, markdown/HTML examples, and usage instructions</li>
                                <li>Added Repository Badges section to About page with visual examples</li>
                                <li>Color-coded badge status indicators (green/red/gray)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.4.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.4.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Luckiest Repos section showing repositories with highest net profit</li>
                                <li>Min. 5 plays required to qualify for luckiest repos list</li>
                                <li>Displays net profit, win rate, avg payout, and current balance per repo</li>
                                <li>Click repo name to view on GitHub</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.3.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.3.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Enhanced Stats page with Theory vs Reality comparison table</li>
                                <li>Real vs theoretical pattern frequencies showing expected vs actual occurrences</li>
                                <li>Legendary Wins showcase featuring rarest patterns that actually hit (jackpots, hextets, etc)</li>
                                <li>Pattern variance tracking to see which patterns beat or miss expectations</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.2.1 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.2.1</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🎨 UI/UX:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Renamed ALL LETTERS pattern to ALPHABET SOUP for better flavor</li>
                                <li>Added sortable columns to daily leaderboard (COMMITS and WINNINGS)</li>
                                <li>Moved Changelog link from main nav to footer for cleaner layout</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.2.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.2.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Improved leaderboard display with better content moderation</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.1.2 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.1.2</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🎨 UI/UX:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Standardized border colors for visual consistency</li>
                                <li>Replaced semi-transparent accent borders with --term-dim variable</li>
                                <li>Updated theme picker card and leaderboard table borders</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.1.1 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.1.1</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-10-15</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🎨 UI/UX:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Added typing effect to homepage terminal demo</li>
                                <li>More relaxed timing between animation steps</li>
                                <li>Commands now type out character by character for classic terminal feel</li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Fixed badge text spacing - removed textLength attribute causing "G i t S l o t s"</li>
                                <li>Added slot machine emoji (🎰) back to badge left side</li>
                            </ul>
                        </div>
                    </div>
                </div>

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
                                <li>Usernames in leaderboards and recent plays now link to GitHub profiles</li>
                                <li>Underline on hover for clear visual feedback</li>
                                <li>Links open in new tab for better UX</li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: #a78bfa;">⚡ Performance:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Optimized daily leaderboard query with composite index</li>
                                <li>Replaced DATE() function with range comparison for index usage</li>
                                <li>Faster page loads as plays table grows</li>
                            </ul>
                        </div>
                    </div>
                </div>

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
                                <li>Improved text readability across all themes</li>
                                <li>Updated --term-dim colors for better contrast: Dracula, DOS Blue, IBM Green, and Monokai</li>
                                <li>Brighter secondary text makes large blocks easier to read</li>
                                <li>Fixed ALL LETTERS demo hash (abcdef1 → abcdefa)</li>
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
                <!-- Version 1.3.2 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.3.2</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Choose between personal or organization credit during <code>init</code></li>
                                <li>Per-repo configuration stored locally in <code>.git/slot-machine-config.json</code></li>
                                <li>Perfect for company repos (credit the org) or personal projects (credit yourself)</li>
                                <li>Prompt only shows when repo owner differs from your personal username</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.3.1 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.3.1</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🎨 UI/UX:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Hidden <code>config:get</code> and <code>config:set</code> from default help output</li>
                                <li>Advanced commands still work but don't clutter user-facing help</li>
                                <li>Updated <code>init</code> command to show new <code>sync:disable</code> syntax</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.3.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.3.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #ff6b6b;">⚠️ Breaking Changes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Adopted Laravel/Artisan-style <code>resource:action</code> command pattern</li>
                                <li><code>config set sync-enabled false</code> → <code>sync:disable</code></li>
                                <li><code>config set sync-enabled true</code> → <code>sync:enable</code></li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Added <code>username:set &lt;username&gt;</code> for easier username changes</li>
                                <li>Shorter, more memorable commands for common operations</li>
                                <li>Better command discoverability with resource-based organization</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.2.4 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.2.4</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Fixed <code>init</code> command to continue setup when post-commit hook already exists</li>
                                <li>Now completes username setup, privacy mode, and leaderboard opt-in when hook exists</li>
                                <li>Shows instructions for manually integrating with existing hooks instead of aborting</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.2.3 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.2.3</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Fixed postinstall script dependency issue - now uses ANSI codes instead of chalk</li>
                                <li>Postinstall message now displays correctly on global install</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.2.2 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.2.2</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Fixed postinstall script not showing on global install</li>
                                <li>Removed overly strict global install detection that prevented message from displaying</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.2.1 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.2.1</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Added <code>-v</code> shorthand for <code>--version</code> flag</li>
                                <li>Updated hardcoded version from 0.1.0 to 1.2.1</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.2.0 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.2.0</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #ff6b6b;">⚠️ Breaking Changes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Simplified command structure - auth commands moved to top-level</li>
                                <li><code>git-slot-machine auth login</code> → <code>git-slot-machine login &lt;username&gt;</code></li>
                                <li><code>git-slot-machine auth status</code> → <code>git-slot-machine status</code></li>
                                <li><code>git-slot-machine auth logout</code> → <code>git-slot-machine logout</code></li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: var(--term-win);">✨ New Features:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Automatic GitHub username detection (gh CLI → git config → email → prompt)</li>
                                <li>Opt-in leaderboard prompt during init with username confirmation</li>
                                <li>Postinstall script with setup instructions (global installs only)</li>
                                <li>GitHub username now stored globally as developer identity</li>
                            </ul>
                        </div>
                        <div class="mt-3" style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🎨 UI/UX:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Clearer onboarding experience with guided prompts</li>
                                <li>Better username detection reduces manual entry</li>
                                <li>More intuitive command structure without nested subcommands</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.1.2 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.1.2</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🎨 UI/UX:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Enhanced privacy mode messaging with visual indicators</li>
                                <li>Green checkmarks (✓) show data that IS sent to server</li>
                                <li>Red X marks (✗) show data that is NOT sent (privacy mode)</li>
                                <li>Added explanatory note for privacy mode repo handling</li>
                                <li>Clearer distinction between public and private repo modes</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Version 1.1.1 -->
                <div>
                    <div class="flex items-baseline gap-3 mb-3">
                        <h3 class="text-2xl font-bold" style="color: var(--term-text);">v1.1.1</h3>
                        <span class="text-sm" style="color: var(--term-dim);">2025-01-16</span>
                    </div>
                    <div class="pl-4 space-y-2 text-sm">
                        <div style="color: var(--term-text);">
                            <span style="color: #66d9ef;">🐛 Bug Fixes:</span>
                            <ul class="list-disc list-inside pl-4 mt-2 space-y-1" style="color: var(--term-dim);">
                                <li>Fixed ES Module compatibility with chalk v5+</li>
                                <li>Converted from CommonJS to ES Modules (ESM)</li>
                                <li>Added explicit .js file extensions to all imports</li>
                                <li>Updated TypeScript to compile to ES2022 modules</li>
                                <li>Resolves ERR_REQUIRE_ESM error on Node.js 22+</li>
                            </ul>
                        </div>
                    </div>
                </div>

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
