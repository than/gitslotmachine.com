<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Git Slot Machine - Global Leaderboard</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎰</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation -->
        <nav class="flex justify-center gap-6 mb-8">
            <a href="{{ route('home') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold border-b-2 border-cyan-400">Leaderboard</a>
            <a href="{{ route('stats') }}" class="text-gray-400 hover:text-cyan-300">Statistics</a>
        </nav>

        <!-- Header -->
        <header class="text-center mb-12">
            <h1 class="text-6xl font-bold mb-4">🎰 Git Slot Machine 🎰</h1>
            <p class="text-xl text-gray-400">Every commit is a spin. Will you hit the jackpot?</p>
        </header>

        <!-- Livewire Leaderboard Component (auto-refreshes every 5 seconds) -->
        <livewire:leaderboard />

        <!-- FAQ Section -->
        <div class="mt-16 border-t border-gray-700 pt-16">
            <h2 class="text-3xl font-bold mb-8">How It Works</h2>

            <div class="space-y-6 text-gray-300">
                <div>
                    <h3 class="text-xl font-bold text-white mb-2">Installation</h3>
                    <pre class="bg-gray-800 p-4 rounded-lg">npm install -g git-slot-machine
cd your-repo
git-slot-machine init</pre>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-white mb-2">What happens?</h3>
                    <p>Every time you commit, the slot machine spins using your commit hash. Different patterns win different payouts!</p>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-white mb-2">Leaderboard</h3>
                    <p>Compete globally! Your plays are automatically tracked here. Daily leaderboard resets at midnight UTC.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
