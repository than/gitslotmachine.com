<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats - Git Slot Machine</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation -->
        <nav class="flex justify-center gap-6 mb-8">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-cyan-300">Leaderboard</a>
            <a href="{{ route('stats') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold border-b-2 border-cyan-400">Statistics</a>
        </nav>

        <header class="text-center mb-12">
            <h1 class="text-5xl font-bold mb-4">📊 Statistics</h1>
            <p class="text-xl text-gray-400">Real-world pattern distribution</p>
        </header>

        <livewire:stats.global-stats />
    </div>
</body>
</html>
