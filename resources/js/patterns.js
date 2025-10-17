// Pattern detection and highlighting logic
// Ported from CLI: git-slot-machine/src/patterns.ts

export function detectPattern(hash) {
    // Validate input
    if (hash.length !== 7) {
        throw new Error('Hash must be 7 characters');
    }
    if (!/^[0-9a-f]+$/i.test(hash)) {
        throw new Error('Hash must contain only hex characters');
    }

    const lowerHash = hash.toLowerCase();
    const counts = countCharacters(lowerHash);
    const distribution = getCountDistribution(counts);

    // Detect pattern - check in order of rarity/value
    let type;

    // Check for the secret ultimate jackpot: 7777777
    if (lowerHash === '7777777') {
        type = 'LUCKY_SEVENS';
    }
    // Check for all same first (highest value)
    else if (distribution[0] === 7) {
        type = 'ALL_SAME';
    }
    // Check for 6 of a kind
    else if (distribution[0] === 6) {
        type = 'SIX_OF_KIND';
    }
    // Check for straight 7 (very rare)
    else if (hasSequentialRun(lowerHash, 7)) {
        type = 'STRAIGHT_7';
    }
    // Check for fullest house (4-3)
    else if (distribution[0] === 4 && distribution[1] === 3) {
        type = 'FULLEST_HOUSE';
    }
    // Check for 5 of a kind
    else if (distribution[0] === 5) {
        type = 'FIVE_OF_KIND';
    }
    // Check for straight 6
    else if (hasSequentialRun(lowerHash, 6)) {
        type = 'STRAIGHT_6';
    }
    // Check for 4 of a kind
    else if (distribution[0] === 4) {
        type = 'FOUR_OF_KIND';
    }
    // Check for all letters
    else if (isAllLetters(lowerHash)) {
        type = 'ALL_LETTERS';
    }
    // Check for straight 5
    else if (hasSequentialRun(lowerHash, 5)) {
        type = 'STRAIGHT_5';
    }
    // Check for double triple (3-3-1)
    else if (distribution[0] === 3 && distribution[1] === 3) {
        type = 'THREE_OF_KIND_PLUS_THREE';
    }
    // Check for full house (3-2-2 or 3-2-1-1)
    else if (distribution[0] === 3 && distribution[1] === 2) {
        type = 'FULL_HOUSE';
    }
    // Check for 3 of a kind
    else if (distribution[0] === 3) {
        type = 'THREE_OF_KIND';
    }
    // Check for three consecutive pairs
    else if (countConsecutivePairs(lowerHash) === 3) {
        type = 'THREE_PAIR';
    }
    // Check for two consecutive pairs
    else if (countConsecutivePairs(lowerHash) === 2) {
        type = 'TWO_PAIR';
    }
    // Check for all numbers
    else if (isAllNumbers(lowerHash)) {
        type = 'ALL_NUMBERS';
    }
    // Check for one consecutive pair (break-even)
    else if (countConsecutivePairs(lowerHash) === 1) {
        type = 'ONE_PAIR';
    }
    // Everything else is no win
    else {
        type = 'NO_WIN';
    }

    return {
        type,
        highlightIndices: getHighlightIndices(hash, type)
    };
}

function countCharacters(hash) {
    const counts = new Map();
    for (const char of hash) {
        counts.set(char, (counts.get(char) || 0) + 1);
    }
    return counts;
}

function getCountDistribution(counts) {
    return Array.from(counts.values()).sort((a, b) => b - a);
}

function hasSequentialRun(hash, length) {
    const hexValues = '0123456789abcdef';

    for (let i = 0; i <= hash.length - length; i++) {
        const substring = hash.substring(i, i + length);

        // Check ascending
        let isAscending = true;
        for (let j = 0; j < substring.length - 1; j++) {
            const currentIndex = hexValues.indexOf(substring[j]);
            const nextIndex = hexValues.indexOf(substring[j + 1]);
            if (nextIndex !== currentIndex + 1) {
                isAscending = false;
                break;
            }
        }
        if (isAscending) return true;

        // Check descending
        let isDescending = true;
        for (let j = 0; j < substring.length - 1; j++) {
            const currentIndex = hexValues.indexOf(substring[j]);
            const nextIndex = hexValues.indexOf(substring[j + 1]);
            if (nextIndex !== currentIndex - 1) {
                isDescending = false;
                break;
            }
        }
        if (isDescending) return true;
    }

    return false;
}

function isAllLetters(hash) {
    return /^[a-f]+$/i.test(hash);
}

function isAllNumbers(hash) {
    return /^[0-9]+$/.test(hash);
}

function countConsecutivePairs(hash) {
    let pairCount = 0;
    let i = 0;

    while (i < hash.length - 1) {
        if (hash[i] === hash[i + 1]) {
            pairCount++;
            i += 2;
        } else {
            i++;
        }
    }

    return pairCount;
}

function getHighlightIndices(hash, type) {
    const lowerHash = hash.toLowerCase();

    // For straights, highlight all characters in the sequential run
    if (type === 'STRAIGHT_7' || type === 'STRAIGHT_6' || type === 'STRAIGHT_5') {
        const length = type === 'STRAIGHT_7' ? 7 : type === 'STRAIGHT_6' ? 6 : 5;
        const hexValues = '0123456789abcdef';

        for (let i = 0; i <= lowerHash.length - length; i++) {
            const substring = lowerHash.substring(i, i + length);
            let isSequential = true;

            // Check ascending or descending
            for (let j = 0; j < substring.length - 1; j++) {
                const currentIndex = hexValues.indexOf(substring[j]);
                const nextIndex = hexValues.indexOf(substring[j + 1]);
                if (nextIndex !== currentIndex + 1 && nextIndex !== currentIndex - 1) {
                    isSequential = false;
                    break;
                }
            }

            if (isSequential) {
                return Array.from({ length }, (_, idx) => i + idx);
            }
        }
    }

    // For all letters or all numbers, highlight everything
    if (type === 'ALL_LETTERS' || type === 'ALL_NUMBERS' || type === 'ALL_SAME' || type === 'LUCKY_SEVENS') {
        return [0, 1, 2, 3, 4, 5, 6];
    }

    // For frequency-based patterns, highlight characters that appear in the pattern
    const counts = countCharacters(lowerHash);
    const indices = [];

    if (type === 'SIX_OF_KIND' || type === 'FIVE_OF_KIND' ||
        type === 'FOUR_OF_KIND' || type === 'THREE_OF_KIND') {
        // Find the character with the highest count and highlight all occurrences
        const targetCount = type === 'SIX_OF_KIND' ? 6 :
                            type === 'FIVE_OF_KIND' ? 5 :
                            type === 'FOUR_OF_KIND' ? 4 : 3;

        for (const [char, count] of counts.entries()) {
            if (count === targetCount) {
                for (let i = 0; i < lowerHash.length; i++) {
                    if (lowerHash[i] === char) {
                        indices.push(i);
                    }
                }
                break;
            }
        }
    }
    else if (type === 'FULLEST_HOUSE' || type === 'FULL_HOUSE' ||
             type === 'THREE_OF_KIND_PLUS_THREE') {
        // Highlight all paired/tripled characters
        for (const [char, count] of counts.entries()) {
            if (count >= 2) {
                for (let i = 0; i < lowerHash.length; i++) {
                    if (lowerHash[i] === char) {
                        indices.push(i);
                    }
                }
            }
        }
    }
    else if (type === 'THREE_PAIR' || type === 'TWO_PAIR' || type === 'ONE_PAIR') {
        // Highlight only consecutive pairs (adjacent identical characters)
        let i = 0;
        while (i < lowerHash.length - 1) {
            if (lowerHash[i] === lowerHash[i + 1]) {
                indices.push(i);
                indices.push(i + 1);
                i += 2;
            } else {
                i++;
            }
        }
    }

    return indices.sort((a, b) => a - b);
}

// Export for use in window
if (typeof window !== 'undefined') {
    window.detectPattern = detectPattern;
}
