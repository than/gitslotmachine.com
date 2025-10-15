<div wire:poll.5s class="space-y-8">
    <!-- Leaderboard Tabs -->
    <div>
        <div class="border-b border-gray-700">
            <nav class="flex space-x-8">
                <button
                    wire:click="$set('tab', 'daily')"
                    class="py-4 px-1 border-b-2 font-medium text-lg {{ $tab === 'daily' ? 'border-cyan-500 text-cyan-500' : 'border-transparent text-gray-400 hover:text-gray-300' }}">
                    Daily Leaderboard
                </button>
                <button
                    wire:click="$set('tab', 'alltime')"
                    class="py-4 px-1 border-b-2 font-medium text-lg {{ $tab === 'alltime' ? 'border-cyan-500 text-cyan-500' : 'border-transparent text-gray-400 hover:text-gray-300' }}">
                    All-Time Leaderboard
                </button>
            </nav>
        </div>

        <!-- Daily Leaderboard -->
        @if($tab === 'daily')
        <div class="mt-8">
            @if($dailyLeaderboard->isEmpty())
            <div class="text-center py-12 text-gray-400">
                No plays today yet. Be the first!
            </div>
            @else
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-700">
                        <th class="pb-4">Rank</th>
                        <th class="pb-4">Player</th>
                        <th class="pb-4">Commits Today</th>
                        <th class="pb-4">Net Winnings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyLeaderboard as $index => $user)
                    <tr class="border-b border-gray-800 hover:bg-gray-800">
                        <td class="py-4 text-2xl">{{ $index + 1 }}</td>
                        <td class="py-4 font-bold">{{ $user->github_username }}</td>
                        <td class="py-4">{{ $user->daily_commits }}</td>
                        <td class="py-4 {{ $user->daily_winnings >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $user->daily_winnings >= 0 ? '+' : '' }}{{ $user->daily_winnings }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @endif

        <!-- All-Time Leaderboard -->
        @if($tab === 'alltime')
        <div class="mt-8">
            @if($allTimeLeaderboard->isEmpty())
            <div class="text-center py-12 text-gray-400">
                No plays yet. Install git-slot-machine to get started!
            </div>
            @else
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-700">
                        <th class="pb-4">Rank</th>
                        <th class="pb-4">Player</th>
                        <th class="pb-4">Total Commits</th>
                        <th class="pb-4">Total Balance</th>
                        <th class="pb-4">Biggest Win</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allTimeLeaderboard as $index => $user)
                    <tr class="border-b border-gray-800 hover:bg-gray-800">
                        <td class="py-4 text-2xl">{{ $index + 1 }}</td>
                        <td class="py-4 font-bold">{{ $user->github_username }}</td>
                        <td class="py-4">{{ $user->total_commits }}</td>
                        <td class="py-4 {{ $user->total_balance >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ $user->total_balance }}
                        </td>
                        <td class="py-4 text-yellow-400">{{ $user->biggest_win }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @endif
    </div>

    <!-- Recent Plays Feed -->
    <div class="mt-16">
        <h2 class="text-3xl font-bold mb-6">Recent Plays</h2>
        @if($recentPlays->isEmpty())
        <div class="text-center py-12 text-gray-400">
            No plays yet. Install git-slot-machine and start playing!
        </div>
        @else
        <div class="space-y-4">
            @foreach($recentPlays as $play)
            <div class="bg-gray-800 rounded-lg p-4 flex items-center justify-between">
                <div>
                    <span class="font-bold">{{ $play->user->github_username }}</span>
                    <span class="text-gray-400">on</span>
                    <span class="text-cyan-400">{{ $play->repository->owner }}/{{ $play->repository->name }}</span>
                </div>
                <div class="text-right">
                    <div class="font-mono text-sm text-gray-400">{{ $play->commit_hash }}</div>
                    <div class="{{ $play->payout > 0 ? 'text-green-400' : 'text-red-400' }} font-bold">
                        {{ $play->pattern_name }} {{ $play->payout > 0 ? '+' : '' }}{{ $play->payout }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
