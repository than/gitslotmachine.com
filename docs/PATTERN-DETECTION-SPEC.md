# Pattern Detection Specification

> **CRITICAL:** This specification defines the EXACT pattern detection logic for the git-slot-machine project. Both TypeScript (CLI) and PHP (API) implementations MUST follow this specification precisely. Any deviation will cause patterns to be detected differently between client and server, breaking the verification system.

## Version

**Spec Version:** 1.0.0
**Last Updated:** 2025-10-15

## Overview

The pattern detector analyzes 7-character hexadecimal git commit hashes to identify poker-style patterns and assign payouts. The detector MUST validate input, then check patterns in the exact order specified below.

## Input Validation

### MUST validate BEFORE pattern detection:

1. **Length Check:** Hash MUST be exactly 7 characters
   - Invalid: `abc` (too short)
   - Invalid: `abcd1234` (too long)
   - Valid: `abcd123`

2. **Hex Character Check:** Hash MUST contain only valid hexadecimal characters (0-9, a-f, A-F)
   - Invalid: `gggg123` (contains 'g')
   - Invalid: `xyz1234` (contains non-hex)
   - Valid: `AbCd123` (mixed case is OK)

3. **Case Normalization:** Convert hash to lowercase AFTER validation, BEFORE detection
   - Input: `AbCdEf1` → Normalized: `abcdef1`

### Error Handling:

- Length error: Throw/raise exception with message: `"Hash must be 7 characters"`
- Hex error: Throw/raise exception with message: `"Hash must contain only hex characters"`

## Pattern Detection Order

**CRITICAL:** Patterns MUST be checked in this EXACT order. First match wins.

### 1. ALL_SAME (JACKPOT)
**Check:** All 7 characters are identical
**Examples:**
- ✅ `aaaaaaa` → ALL_SAME
- ✅ `0000000` → ALL_SAME
- ✅ `fffffff` → ALL_SAME
- ❌ `aaaaaa1` → Not ALL_SAME (6 same, not 7)

**Implementation:** Count unique characters, must equal 1

---

### 2. MEGA_STRAIGHT (7 in a row)
**Check:** 7 consecutive hex values in sequence (ascending OR descending)
**Examples:**
- ✅ `0123456` → MEGA_STRAIGHT (ascending)
- ✅ `fedcba9` → MEGA_STRAIGHT (descending)
- ✅ `789abcd` → MEGA_STRAIGHT (ascending)
- ❌ `012345a` → Not MEGA_STRAIGHT (gap between 5→a)
- ❌ `0123457` → Not MEGA_STRAIGHT (gap between 6→7)

**Hex sequence:** `0→1→2→3→4→5→6→7→8→9→a→b→c→d→e→f`

**Implementation:**
- Convert each char to its hex value (0-15)
- Check if each value = previous ± 1
- Must be ALL ascending OR ALL descending (cannot mix)

---

### 3. SUPER_STRAIGHT (6 in a row)
**Check:** 6 consecutive hex values in sequence (ascending OR descending)
**Examples:**
- ✅ `012345a` → SUPER_STRAIGHT (6 in a row: 0-5)
- ✅ `a987654` → SUPER_STRAIGHT (6 in a row: 9-4 descending)
- ✅ `1abcdef` → SUPER_STRAIGHT (6 in a row: a-f)
- ❌ `0123467` → Not SUPER_STRAIGHT (gap in sequence)

**Implementation:** Find longest straight, must be >= 6

---

### 4. STRAIGHT (5 in a row)
**Check:** 5 consecutive hex values in sequence (ascending OR descending)
**Examples:**
- ✅ `01234ab` → STRAIGHT (5 in a row: 0-4)
- ✅ `ab98765` → STRAIGHT (5 in a row: 9-5 descending)
- ✅ `12bcdef` → STRAIGHT (5 in a row: b-f)
- ❌ `012a456` → Not STRAIGHT (no 5 consecutive)

**Implementation:** Find longest straight, must be >= 5

---

### 5. ALL_LETTERS
**Check:** All 7 characters are letters (a-f only, no digits 0-9)
**Examples:**
- ✅ `abcdefa` → ALL_LETTERS
- ✅ `fffffff` → ALL_LETTERS (also matches ALL_SAME first!)
- ✅ `bacedef` → ALL_LETTERS
- ❌ `abcdef1` → Not ALL_LETTERS (contains '1')
- ❌ `0abcdef` → Not ALL_LETTERS (contains '0')

**Implementation:** Check that NO character is in range 0-9

---

### 6. ALL_NUMBERS
**Check:** All 7 characters are digits (0-9 only, no letters a-f)
**Examples:**
- ✅ `1234567` → ALL_NUMBERS
- ✅ `0000000` → ALL_NUMBERS (also matches ALL_SAME first!)
- ✅ `9876543` → ALL_NUMBERS
- ❌ `123456a` → Not ALL_NUMBERS (contains 'a')

**Implementation:** Check that NO character is in range a-f

---

### 7. Character Frequency Patterns

For the remaining patterns, count character frequencies and sort descending.

**Example:** `aabbcc1`
- Count: `a:2, b:2, c:2, 1:1`
- Distribution: `[2, 2, 2, 1]`

#### Pattern Priority (check in this order):

**7a. SIX_OF_KIND** - One character appears 6 times
Distribution: `[6, 1]`
Examples:
- ✅ `aaaaaa1` → SIX_OF_KIND
- ✅ `555555a` → SIX_OF_KIND

**7b. FIVE_OF_KIND** - One character appears 5 times
Distribution: `[5, ...]`
Examples:
- ✅ `aaaaa12` → FIVE_OF_KIND
- ✅ `bbbbb3c` → FIVE_OF_KIND

**7c. FULLEST_HOUSE** - 4 of one + 3 of another
Distribution: `[4, 3]`
Examples:
- ✅ `aaaabbb` → FULLEST_HOUSE
- ✅ `1111222` → FULLEST_HOUSE

**7d. FOUR_OF_KIND** - One character appears 4 times
Distribution: `[4, ...]` (but NOT `[4, 3]` which is FULLEST_HOUSE)
Examples:
- ✅ `aaaa123` → FOUR_OF_KIND
- ✅ `bbbbcde` → FOUR_OF_KIND
- ❌ `aaaabbb` → FULLEST_HOUSE (not FOUR_OF_KIND)

**7e. DOUBLE_TRIPLE** - Two characters each appear 3 times
Distribution: `[3, 3, 1]`
Examples:
- ✅ `aaabbb1` → DOUBLE_TRIPLE
- ✅ `cccddde` → DOUBLE_TRIPLE

**7f. FULL_HOUSE** - 3 of one + 2 of another (+ optionally more)
Distribution: `[3, 2, ...]`
Examples:
- ✅ `aaaabb1` → FULL_HOUSE (3+2+1)
- ✅ `aaabbbc` → FULL_HOUSE (3+3+1, but first 3+2 found)
- ✅ `aaabbcc` → FULL_HOUSE (3+2+2)

**7g. THREE_PAIR** - Three characters each appear exactly 2 times
Distribution: `[2, 2, 2, 1]`
Examples:
- ✅ `aabbcc1` → THREE_PAIR
- ✅ `ddeeff0` → THREE_PAIR
- ❌ `aabbccc` → Not THREE_PAIR (one is 3, not 2)

**7h. THREE_OF_KIND** - One character appears 3 times
Distribution: `[3, ...]`
Examples:
- ✅ `aaa1234` → THREE_OF_KIND
- ✅ `bbbc123` → THREE_OF_KIND

**7i. TWO_PAIR** - Two characters each appear 2 times
Distribution: `[2, 2, ...]`
Examples:
- ✅ `aabb123` → TWO_PAIR
- ✅ `ccdd1ef` → TWO_PAIR

**7j. PAIR** - One character appears 2 times
Distribution: `[2, ...]`
Examples:
- ✅ `aa12345` → PAIR
- ✅ `1234bbc` → PAIR (b appears twice)

**7k. NO_WIN** - No pattern matched
Distribution: `[1, 1, 1, 1, 1, 1, 1]`
Examples:
- ✅ `abcd123` → NO_WIN (all unique)
- ✅ `1234567` → NO_WIN (but would match STRAIGHT first!)

---

## Pattern Payouts

**CRITICAL:** These payout values MUST be identical in both implementations.

| Pattern | Type String | Display Name | Payout | Description |
|---------|-------------|--------------|--------|-------------|
| ALL_SAME | `ALL_SAME` | `JACKPOT` | 1,379,000 | All 7 same character |
| MEGA_STRAIGHT | `MEGA_STRAIGHT` | `MEGA STRAIGHT` | 1,102,000 | 7 in a row |
| SIX_OF_KIND | `SIX_OF_KIND` | `LEGENDARY` | 129,000 | 6 of a kind |
| SUPER_STRAIGHT | `SUPER_STRAIGHT` | `SUPER STRAIGHT` | 73,500 | 6 in a row |
| FULLEST_HOUSE | `FULLEST_HOUSE` | `FULLEST HOUSE` | 25,900 | 4 + 3 of a kind |
| STRAIGHT | `STRAIGHT` | `STRAIGHT` | 3,098 | 5 in a row |
| FIVE_OF_KIND | `FIVE_OF_KIND` | `EPIC` | 3,098 | 5 of a kind |
| FOUR_OF_KIND | `FOUR_OF_KIND` | `RARE` | 144 | 4 of a kind |
| DOUBLE_TRIPLE | `DOUBLE_TRIPLE` | `DOUBLE TRIPLE` | 934 | Two 3s |
| ALL_LETTERS | `ALL_LETTERS` | `ALL LETTERS` | 786 | Only a-f |
| FULL_HOUSE | `FULL_HOUSE` | `FULL HOUSE` | 312 | 3 + 2 |
| THREE_PAIR | `THREE_PAIR` | `THREE PAIR` | 150 | Three 2s |
| THREE_OF_KIND | `THREE_OF_KIND` | `THREE OF A KIND` | 12 | 3 of a kind |
| TWO_PAIR | `TWO_PAIR` | `TWO PAIR` | 50 | Two 2s |
| ALL_NUMBERS | `ALL_NUMBERS` | `ALL NUMBERS` | 9 | Only 0-9 |
| PAIR | `PAIR` | `PAIR` | 2 | 2 of a kind |
| NO_WIN | `NO_WIN` | `No win` | 0 | No pattern |

## Return Value Format

Both implementations MUST return an object/array with these EXACT keys:

```typescript
{
  type: string,        // The pattern type (e.g., "THREE_PAIR")
  name: string,        // The display name (e.g., "THREE PAIR")
  payout: number,      // The payout amount (e.g., 150)
  description?: string // Optional: Human-readable description (CLI only)
}
```

## Test Cases for Verification

Both implementations MUST pass these tests:

```
Input: "aaaaaaa" → {type: "ALL_SAME", name: "JACKPOT", payout: 1379000}
Input: "0123456" → {type: "MEGA_STRAIGHT", name: "MEGA STRAIGHT", payout: 1102000}
Input: "aaaaaa1" → {type: "SIX_OF_KIND", name: "LEGENDARY", payout: 129000}
Input: "012345a" → {type: "SUPER_STRAIGHT", name: "SUPER STRAIGHT", payout: 73500}
Input: "aaaabbb" → {type: "FULLEST_HOUSE", name: "FULLEST HOUSE", payout: 25900}
Input: "01234ab" → {type: "STRAIGHT", name: "STRAIGHT", payout: 3098}
Input: "aaaaa12" → {type: "FIVE_OF_KIND", name: "EPIC", payout: 3098}
Input: "aaaa123" → {type: "FOUR_OF_KIND", name: "RARE", payout: 144}
Input: "aaabbb1" → {type: "DOUBLE_TRIPLE", name: "DOUBLE TRIPLE", payout: 934}
Input: "abcdefa" → {type: "ALL_LETTERS", name: "ALL LETTERS", payout: 786}
Input: "aaaabb1" → {type: "FULL_HOUSE", name: "FULL HOUSE", payout: 312}
Input: "aabbcc1" → {type: "THREE_PAIR", name: "THREE PAIR", payout: 150}
Input: "aaa1234" → {type: "THREE_OF_KIND", name: "THREE OF A KIND", payout: 12}
Input: "aabb123" → {type: "TWO_PAIR", name: "TWO PAIR", payout: 50}
Input: "1234567" → {type: "MEGA_STRAIGHT", name: "MEGA STRAIGHT", payout: 1102000} (NOT ALL_NUMBERS!)
Input: "1230984" → {type: "ALL_NUMBERS", name: "ALL NUMBERS", payout: 9}
Input: "aa12345" → {type: "PAIR", name: "PAIR", payout: 2}
Input: "abcd123" → {type: "NO_WIN", name: "No win", payout: 0}

Error Cases:
Input: "abc" → THROW: "Hash must be 7 characters"
Input: "abcd1234" → THROW: "Hash must be 7 characters"
Input: "gggg123" → THROW: "Hash must contain only hex characters"
Input: "xyz1234" → THROW: "Hash must contain only hex characters"
```

## Implementation Checklist

When implementing pattern detection, verify:

- [ ] Input validation (length and hex chars) happens FIRST
- [ ] Hash is normalized to lowercase BEFORE detection
- [ ] Patterns are checked in the EXACT order specified
- [ ] First match wins (don't continue checking)
- [ ] All payout values match exactly
- [ ] Return object has correct keys: `type`, `name`, `payout`
- [ ] All test cases pass
- [ ] Error messages match specification

## Sync Protocol

If this specification is updated:

1. Update **Spec Version** at top of file
2. Update **both** TypeScript and PHP implementations
3. Run full test suite on **both** implementations
4. Verify all tests pass before deploying
5. Do NOT deploy one implementation without the other

## Related Files

- **TypeScript Implementation:** `/git-slot-machine/src/patterns.ts`
- **PHP Implementation:** `/gitslotmachine.com/app/Services/PatternDetector.php`
- **TypeScript Tests:** `/git-slot-machine/tests/` (if exists)
- **PHP Tests:** `/gitslotmachine.com/tests/Unit/PatternDetectorTest.php`
