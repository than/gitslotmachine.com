# Git Slot Machine Server

The house-side context: owns the canonical ruleset, re-evaluates every
submitted play, and keeps the leaderboard.

## Language

### Ruleset

**Ruleset**:
The versioned, canonical definition of every pattern, payout, and probability,
shared verbatim with the CLI. Exactly one ruleset is live at a time.
_Avoid_: payout table, config

**Pattern**:
A named shape a hash can match (JACKPOT, ONE PAIR, …), with a payout and
exact enumerated odds over all 16⁷ hashes.
_Avoid_: combo, win type

**Detection order**:
The sequence patterns are checked in; the first match wins. Deliberately NOT
strict rarity order — a hash matching several patterns pays the first detected,
not the rarest.
_Avoid_: priority (implies rarity), rarity order

**Display order**:
How the odds page lists patterns: biggest payout first, rarity breaking ties.
Presentation only; never feeds detection.

**Secret pattern**:
A pattern absent from the public odds table until some player first hits it —
a discovery. Detected before all public patterns.

**Net winning hashes**:
The count of hashes whose *best* match is this pattern, after every
earlier-detected pattern has claimed its overlaps. The numerator of a
pattern's published odds.
_Avoid_: raw count (that's before overlap removal)

**RTP (return to player)**:
The fraction of all ante spent that the ruleset pays back over the full hash
space. A property of the ruleset, not of any player's luck.

### Leaderboard

**Play (server-side)**:
A submitted commit whose pattern and payout the server recomputes from the
hash — the client's claimed result is never trusted.

**Discovery**:
The first-ever play to hit a secret pattern, permanently credited to the
player who made it.

**Grinding**:
Manufacturing commits (amend/rehash loops) to farm favorable hashes. Flagged,
not forbidden.
_Avoid_: cheating (grinding is detectable play, not rule-breaking)
