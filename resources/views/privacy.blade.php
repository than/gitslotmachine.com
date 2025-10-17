<x-terminal-layout title="Privacy Policy" activeTab="privacy">
    <x-slot name="meta">
        <meta name="description" content="Transparent privacy policy for Git Slot Machine. We collect GitHub usernames and gameplay data. No tracking, no ads, no data selling. Your control.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/privacy') }}">
        <meta property="og:title" content="Privacy Policy - Git Slot Machine">
        <meta property="og:description" content="Transparent privacy policy for Git Slot Machine. We collect GitHub usernames and gameplay data. No tracking, no ads, no data selling. Your control.">
        <meta property="og:image" content="https://gitslotmachine.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url('/privacy') }}">
        <meta name="twitter:title" content="Privacy Policy - Git Slot Machine">
        <meta name="twitter:description" content="Transparent privacy policy for Git Slot Machine. We collect GitHub usernames and gameplay data. No tracking, no ads, no data selling. Your control.">
        <meta name="twitter:image" content="https://gitslotmachine.com/og-image.png">
    </x-slot>

    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║           PRIVACY POLICY             ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">YOUR DATA, YOUR CONTROL</h1>

        <p class="text-sm sm:text-lg font-mono mb-6 text-center" style="color: var(--term-dim);">
            &gt; We believe in transparency. Here's exactly what we collect and why.
        </p>
    </header>

    <!-- TL;DR - Moved to Top -->
    <div class="border bg-black/30 p-6 font-mono text-sm mb-8" style="border-color: var(--term-accent);">
        <h2 class="text-xl font-bold mb-4" style="color: var(--term-win);">→ TL;DR</h2>
        <div class="pl-4 space-y-2" style="color: var(--term-text);">
            <p>✓ We collect your GitHub username and gameplay data to power leaderboards</p>
            <p>✓ Private repo names are never stored (shown as *******/*******)</p>
            <p>✓ No tracking cookies, no analytics, no data selling</p>
            <p>✓ You can request deletion anytime</p>
            <p>✓ Your data is stored securely</p>
        </div>
    </div>

    <!-- Privacy Policy Content -->
    <div class="border bg-black/30 p-6 font-mono text-sm mb-8" style="border-color: var(--term-accent);">
        <div class="space-y-6" style="color: var(--term-text);">
            <p style="color: var(--term-dim);">Last updated: October 15, 2025</p>

            <!-- What We Collect -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ What We Collect</h2>
                <div class="pl-4 space-y-3" style="color: var(--term-dim);">
                    <div>
                        <span style="color: var(--term-text); font-weight: bold;">GitHub Usernames</span>
                        <p>Public GitHub usernames you provide when using the CLI. Used to authenticate and display your stats on leaderboards.</p>
                    </div>
                    <div>
                        <span style="color: var(--term-text); font-weight: bold;">Commit Data</span>
                        <p>For public repositories: commit hash (first 7 characters), repository name, and URL.</p>
                        <p>For private repositories: commit hash prefix only (7 characters). Repository name and URL are obfuscated as "private/private" and never stored on our servers.</p>
                    </div>
                    <div>
                        <span style="color: var(--term-text); font-weight: bold;">Gameplay Data</span>
                        <p>Pattern matches, payouts, timestamps, and balance history. Used to power leaderboards and statistics.</p>
                    </div>
                    <div>
                        <span style="color: var(--term-text); font-weight: bold;">Authentication Tokens</span>
                        <p>Laravel Sanctum tokens for API authentication. Stored securely and only used to verify your identity.</p>
                    </div>
                </div>
            </section>

            <!-- What We DON'T Collect -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ What We DON'T Collect</h2>
                <ul class="list-disc list-inside pl-4 space-y-2" style="color: var(--term-dim);">
                    <li>Email addresses</li>
                    <li>IP addresses (beyond standard server logs)</li>
                    <li>Tracking cookies or third-party analytics</li>
                    <li>Private repository names or full commit hashes</li>
                    <li>Any personally identifiable information beyond your GitHub username</li>
                    <li>Commit messages or code content</li>
                </ul>
            </section>

            <!-- How We Use Your Data -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ How We Use Your Data</h2>
                <ul class="list-disc list-inside pl-4 space-y-2" style="color: var(--term-dim);">
                    <li>Display your username and stats on public leaderboards</li>
                    <li>Calculate global statistics and pattern frequencies</li>
                    <li>Authenticate CLI requests to the API</li>
                    <li>Track win streaks and personal bests</li>
                    <li>Generate shareable winner cards</li>
                </ul>
            </section>

            <!-- Data Storage & Security -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ Data Storage & Security</h2>
                <div class="pl-4 space-y-2" style="color: var(--term-dim);">
                    <p>Your data is stored securely on Laravel Cloud infrastructure with industry-standard encryption. Authentication tokens are hashed and salted.</p>
                    <p>We retain gameplay data indefinitely to maintain historical leaderboards and statistics. You can request deletion at any time (see Your Rights below).</p>
                </div>
            </section>

            <!-- Cookies & Tracking -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ Cookies & Tracking</h2>
                <div class="pl-4 space-y-2" style="color: var(--term-dim);">
                    <p>We use localStorage (not cookies) to save your theme preference. This data never leaves your browser.</p>
                    <p>We do not use Google Analytics, Facebook Pixel, or any third-party tracking tools.</p>
                    <p>Standard Laravel session cookies are used for website functionality (XSRF protection, etc.).</p>
                </div>
            </section>

            <!-- Your Rights -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ Your Rights</h2>
                <div class="pl-4 space-y-2" style="color: var(--term-dim);">
                    <p><span style="color: var(--term-text); font-weight: bold;">Access:</span> View your data anytime on the leaderboards and stats pages.</p>
                    <p><span style="color: var(--term-text); font-weight: bold;">Deletion:</span> Request deletion of your account and all associated data by contacting us.</p>
                    <p><span style="color: var(--term-text); font-weight: bold;">Portability:</span> Request an export of your gameplay data in JSON format.</p>
                    <p><span style="color: var(--term-text); font-weight: bold;">Opt-out:</span> Disable API sync in the CLI with <code style="color: var(--term-accent);">git-slot-machine config set sync-enabled false</code></p>
                </div>
            </section>

            <!-- Data Sharing -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ Data Sharing</h2>
                <div class="pl-4 space-y-2" style="color: var(--term-dim);">
                    <p>We do not sell, rent, or share your data with third parties.</p>
                    <p>Your GitHub username and gameplay stats are publicly visible on leaderboards by design. This is the core feature of the application.</p>
                    <p>For private repositories, we only display obfuscated repository names (*******/*******) to protect your privacy.</p>
                </div>
            </section>

            <!-- Server Logs -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ Server Logs</h2>
                <div class="pl-4 space-y-2" style="color: var(--term-dim);">
                    <p>Standard web server logs may contain IP addresses, user agents, and request timestamps. These logs are used for debugging and security purposes only.</p>
                    <p>Logs are automatically rotated and deleted after 30 days.</p>
                </div>
            </section>

            <!-- Changes to This Policy -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ Changes to This Policy</h2>
                <div class="pl-4 space-y-2" style="color: var(--term-dim);">
                    <p>We may update this privacy policy from time to time. Check the "Last updated" date at the top.</p>
                    <p>Major changes will be announced on the changelog page.</p>
                </div>
            </section>

            <!-- Contact -->
            <section>
                <h2 class="text-xl font-bold mb-3" style="color: var(--term-accent);">→ Contact</h2>
                <div class="pl-4 space-y-2" style="color: var(--term-dim);">
                    <p>Questions about this privacy policy? Open an issue on GitHub:</p>
                    <p><a href="https://github.com/than/gitslotmachine.com/issues" target="_blank" rel="noopener" style="color: var(--term-accent); text-decoration: underline;">github.com/than/gitslotmachine.com/issues</a></p>
                </div>
            </section>

        </div>
    </div>
</x-terminal-layout>
