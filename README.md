# 🎰 Git Slot Machine

[![Git Slot Machine](https://gitslotmachine.com/badge/than/gitslotmachine.com.svg)](https://gitslotmachine.com)

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-38BDF8?logo=tailwindcss)](https://tailwindcss.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-4169E1?logo=postgresql)](https://www.postgresql.org)
[![GitHub stars](https://img.shields.io/github/stars/than/gitslotmachine.com.svg)](https://github.com/than/gitslotmachine.com/stargazers)

**Turn your git commits into a slot machine game!**

Git Slot Machine is a fun CLI tool that analyzes your commit hashes and rewards you based on patterns found in the first 7 characters. Every commit you make is a pull of the lever — will you hit the jackpot?

🎰 **Live Site:** [gitslotmachine.com](https://gitslotmachine.com)
📦 **CLI Package:** [npm](https://www.npmjs.com/package/git-slot-machine) | [GitHub](https://github.com/than/git-slot-machine)

---

## Features

- **Automated gameplay** via post-commit hook
- **Global leaderboards** with daily and all-time rankings
- **Win streak tracking** with detailed history
- **Pattern-based payouts** (poker-style hands in hex)
- **Real-time stats** and analytics
- **Repository badges** showing last play results
- **Winner sharing** with Open Graph images
- **Token-based authentication** (GitHub username)
- **Retro terminal aesthetic** with theme picker

---

## Tech Stack

- **Laravel 12** - Modern PHP framework
- **Livewire 3** - Reactive components
- **PostgreSQL** - Database
- **Tailwind CSS 4** - Styling
- **Laravel Sanctum** - API authentication
- **Spatie Browsershot** - OG image generation
- **Pest 4** - Testing framework

---

## Installation

### Prerequisites

- PHP 8.4+
- Composer
- Node.js 18+
- PostgreSQL
- Chrome/Chromium (for OG image generation)

### Local Setup

```bash
# Clone the repository
git clone https://github.com/than/gitslotmachine.com.git
cd gitslotmachine.com

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gitslotmachine
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Build assets
npm run build

# Start development server
php artisan serve
```

### Development

```bash
# Run both PHP server and Vite dev server
composer run dev

# Or run separately:
php artisan serve
npm run dev
```

---

## API Endpoints

### Authentication

**Create Token**
```http
POST /api/auth/token
Content-Type: application/json

{
  "github_username": "your-username"
}
```

**Get User Info**
```http
GET /api/auth/user
Authorization: Bearer {token}
```

**Delete Token**
```http
DELETE /api/auth/token
Authorization: Bearer {token}
```

### Gameplay

**Submit Play**
```http
POST /api/play
Authorization: Bearer {token}
Content-Type: application/json

{
  "commit_hash": "a1b2c3d",
  "commit_full_hash": "a1b2c3d4e5f6...",
  "pattern_type": "TWO_PAIR",
  "pattern_name": "TWO PAIR",
  "payout": 50,
  "wager": 10,
  "balance_before": 100,
  "balance_after": 140,
  "repo_url": "https://github.com/user/repo",
  "github_username": "your-username",
  "repo_owner": "user",
  "repo_name": "repo"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "balance": 140,
    "payout": 50,
    "pattern_name": "TWO PAIR",
    "share_url": "https://gitslotmachine.com/winner/{uuid}"
  }
}
```

**Get Balance**
```http
GET /api/balance
Authorization: Bearer {token}
```

---

## Database Schema

### Users Table
```sql
- id
- github_username (unique)
- balance
- total_commits
- total_winnings
- biggest_win
- biggest_win_pattern
- biggest_win_hash
- current_streak
- longest_streak
- longest_streak_ended_at
- timestamps
```

### Plays Table
```sql
- id
- uuid (unique, for sharing)
- user_id
- commit_hash
- commit_full_hash
- pattern_type
- pattern_name
- payout
- wager
- balance_before
- balance_after
- repo_url
- repo_owner
- repo_name
- timestamps
```

---

## Repository Badges

Show off your latest commit results with dynamic repository badges!

### Badge URL Format

```
https://gitslotmachine.com/badge/{owner}/{repo}.svg
```

### Examples

**Markdown:**
```markdown
[![Git Slot Machine](https://gitslotmachine.com/badge/your-username/your-repo.svg)](https://gitslotmachine.com)
```

**HTML:**
```html
<a href="https://gitslotmachine.com">
  <img src="https://gitslotmachine.com/badge/your-username/your-repo.svg" alt="Git Slot Machine">
</a>
```

### How Badges Work

- **Green badge** - Your last commit was a winner! Shows pattern name, payout, and commit hash
- **Red badge** - Last commit didn't win. Shows payout and commit hash
- **Gray badge** - Repository hasn't played yet. Time to install the CLI!
- **Auto-updates** - Badge refreshes every 5 minutes with latest play results

Perfect for README files, profile pages, or anywhere you want to show your Git Slot Machine stats!

---

## Winner Sharing

Every winning commit gets a shareable URL with Open Graph images for social media!

### How It Works

When you win (any payout > 0), the CLI displays a share URL:

```
Share your win:
https://gitslotmachine.com/winner/{uuid}
```

### Features

- **Beautiful OG Images** - Auto-generated 1200x630 terminal-themed images
- **Social Media Ready** - Works on Twitter, Facebook, LinkedIn, Discord, Slack
- **Permanent Links** - Winner pages never expire
- **Stats Display** - Shows pattern, hash, payout, repository, and username

### Example Winner Page

Visit any winner URL to see:
- Terminal-themed winner card with full stats
- Theme picker (hover over terminal header)
- Install instructions for new users
- Auto-generated Open Graph image for social sharing

Try sharing your next big win! 🎰

---

## Pattern Detection

Patterns are detected in the first 7 characters of a git commit hash using poker-style logic:

| Pattern | Example | Payout | Probability |
|---------|---------|--------|-------------|
| JACKPOT | `aaaaaaa` | 10,000 | 1 in 16.7M |
| HEXTET | `aaaaaa1` | 5,000 | 1 in 160K |
| LUCKY SEVEN | `1234567` | 2,500 | 1 in 2.5M |
| FULLEST HOUSE | `aaaabbb` | 2,000 | 1 in 32K |
| FIVE OF A KIND | `aaaaa12` | 1,000 | 1 in 8K |
| BIG STRAIGHT | `012345a` | 500 | 1 in 280K |
| FOUR OF A KIND | `aaaa123` | 400 | 1 in 800 |
| ALL LETTERS | `abcdefa` | 300 | 1 in 960 |
| STRAIGHT | `01234ab` | 200 | 1 in 9K |
| DOUBLE TRIPLE | `aaabbb1` | 150 | 1 in 2K |
| FULL HOUSE | `aaaabb1` | 100 | 1 in 1K |
| THREE PAIR | `aabbcc1` | 150 | 1 in 1.6K |
| THREE OF A KIND | `aaa1234` | 50 | 1 in 15 |
| TWO PAIR | `aabb1cd` | 50 | 1 in 45 |
| ALL NUMBERS | `1230984` | 10 | 1 in 27 |
| NO WIN | `abcd123` | 0 | ~35% |

**Note:** "Pairs" require consecutive identical characters (e.g., `aa`, `bb`).

See full pattern logic in `app/Services/PatternDetector.php`.

---

## Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ApiPlayTest.php

# Run with coverage
php artisan test --coverage
```

---

## Commands

```bash
# Backfill streak data for existing plays
php artisan streaks:backfill
```

---

## Deployment

This project is designed to deploy on [Laravel Cloud](https://cloud.laravel.com).

### Environment Variables

Required production environment variables:

```env
APP_NAME="Git Slot Machine"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://gitslotmachine.com

DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# For OG image generation
BROWSERSHOT_NODE_BIN=/path/to/node
BROWSERSHOT_NPM_BIN=/path/to/npm
```

### Auto-Deployment

Pushes to `main` branch trigger automatic deployment on Laravel Cloud.

---

## Contributing

Contributions welcome! Please open an issue or PR.

---

## Support

Enjoying Git Slot Machine? Consider sponsoring development:

[![Sponsor](https://img.shields.io/badge/Sponsor-GitHub-pink?logo=github)](https://github.com/sponsors/than)

---

## License

MIT License - see [LICENSE](LICENSE) file for details.

---

## Credits

Built by [Than Tibbetts](https://github.com/than)

Powered by [Laravel](https://laravel.com) and [Livewire](https://livewire.laravel.com)
