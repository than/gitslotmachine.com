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
            padding: 60px;
            width: 100%;
            box-shadow: 0 0 30px rgba(0, 255, 65, 0.3);
            text-align: center;
        }

        .emoji {
            font-size: 80px;
            margin-bottom: 20px;
            filter: drop-shadow(0 0 20px rgba(255, 255, 0, 0.5));
        }

        .title {
            font-size: 64px;
            font-weight: 700;
            color: #ffff00;
            text-shadow: 0 0 20px rgba(255, 255, 0, 0.5);
            margin-bottom: 30px;
            line-height: 1.2;
        }

        .tagline {
            font-size: 28px;
            color: #00ff41;
            margin-bottom: 40px;
            line-height: 1.4;
        }

        .footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #00ff41;
            color: #888;
            font-size: 22px;
        }

        .url {
            color: #00ff41;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="terminal">
        <div class="emoji">🎰</div>
        <div class="title">Git Slot Machine</div>
        <div class="tagline">Turn every commit into a slot machine spin</div>
        <div class="footer">
            <span class="url">gitslotmachine.com</span>
        </div>
    </div>
</body>
</html>
