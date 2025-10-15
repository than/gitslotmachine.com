<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            width: 1200px;
            height: 630px;
            background: #0a0e14;
            color: #00ff41;
            font-family: 'JetBrains Mono', monospace;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .terminal {
            background: #000;
            border: 3px solid #00ff41;
            padding: 40px;
            width: 100%;
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #00ff41;
        }

        .title {
            font-size: 48px;
            font-weight: 700;
            color: #ffff00;
            text-shadow: 0 0 20px rgba(255, 255, 0, 0.5);
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 24px;
            color: #00ff41;
        }

        .info {
            margin: 20px 0;
            font-size: 20px;
        }

        .label {
            color: #888;
        }

        .value {
            color: #fff;
            font-weight: 700;
            margin-left: 10px;
        }

        .pattern {
            font-size: 32px;
            color: #ffff00;
            text-shadow: 0 0 10px rgba(255, 255, 0, 0.5);
            font-weight: 700;
        }

        .payout {
            font-size: 48px;
            color: #ffff00;
            text-shadow: 0 0 15px rgba(255, 255, 0, 0.5);
            font-weight: 700;
        }

        .hash {
            font-size: 36px;
            letter-spacing: 4px;
            color: #00ff41;
            font-weight: 700;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #00ff41;
            color: #888;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="terminal">
        <div class="header">
            <div class="title">🎰 BIG WIN! 🎰</div>
            <div class="subtitle">{{ $play->user->github_username }}</div>
        </div>

        <div class="info">
            <span class="label">Pattern:</span>
            <span class="value pattern">{{ $play->pattern_name }}</span>
        </div>

        <div class="info">
            <span class="label">Hash:</span>
            <span class="value hash">{{ $play->commit_hash }}</span>
        </div>

        <div class="info">
            <span class="label">Payout:</span>
            <span class="value payout">+{{ $play->payout - 10 }}</span>
        </div>

        <div class="footer">
            {{ $play->repository->displayFullName() }} • gitslotmachine.com
        </div>
    </div>
</body>
</html>
