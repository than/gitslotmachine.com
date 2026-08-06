<?php

namespace App\Services;

/**
 * Adds per-token hover tooltips to a pattern's probability formula for the /odds page.
 *
 * The canonical formula LaTeX lives in patterns.json (shared with the CLI and guarded
 * by tests). Rather than fork that string, this service tiles the *canonical* formula
 * into its numeric factors and wraps each one with a KaTeX \htmlData{tip=i}{...} hook
 * at render time — so every magic number (arrangement counts, overlap removals, the
 * sample space) explains itself on hover, and the rendered math stays byte-identical
 * to the source (FormulaAnnotatorTest unwraps and compares).
 */
class FormulaAnnotator
{
    private const DENOMINATOR = '16^{7}';

    private const DENOMINATOR_TIP = '16⁷ = 268,435,456 — every possible 7-character hex hash (16 options per digit).';

    /**
     * Per pattern type, the numeric tokens of the first-fraction numerator in the
     * order they appear, each paired with a plain-language explanation. Operators
     * (\cdot, -, parentheses) are left untouched between tokens.
     *
     * @var array<string, list<array{0: string, 1: string}>>
     */
    private const TOKENS = [
        'LUCKY_SEVENS' => [
            ['1', 'Exactly one hash in the whole space: 7777777.'],
        ],
        'ALL_SAME' => [
            ['16', '16 all-same hashes — one for each hex digit 0–f.'],
            ['1', 'Minus 1 all-same hash that a rarer secret pattern claims first.'],
        ],
        'SIX_OF_KIND' => [
            ['16', '16 choices for the six-of-a-kind digit.'],
            ['7', '7 positions for the single odd digit.'],
            ['15', '15 remaining choices for that odd digit.'],
        ],
        'STRAIGHT_7' => [
            ['2', '2 run directions — ascending or descending.'],
            ['10', '10 starting digits for a 7-long run.'],
        ],
        'FULLEST_HOUSE' => [
            ['16', '16 choices for the four-of-a-kind digit.'],
            ['15', '15 choices for the three-of-a-kind digit.'],
            ['\binom{7}{4}', 'C(7,4) = 35 ways to choose which 4 of the 7 positions hold the four-of-a-kind.'],
        ],
        'FIVE_OF_KIND' => [
            ['16', '16 choices for the five-of-a-kind digit.'],
            ['\binom{7}{5}', 'C(7,5) = 21 ways to place the five matching digits.'],
            ['15^{2}', '15² = 225 — the 2 leftover positions are any of the other 15 digits.'],
        ],
        'STRAIGHT_6' => [
            ['2', '2 run directions — ascending or descending.'],
            ['22', '22 = 11 possible 6-long runs × 2 places the run can sit in a 7-char hash.'],
            ['16', '16 choices for the one free digit.'],
            ['20', 'Minus 20 that are really a full 7-straight (rarer, wins first).'],
            ['20', 'Minus 20 more where the free digit extends the run (already counted).'],
        ],
        'FOUR_OF_KIND' => [
            ['16', '16 choices for the four-of-a-kind digit.'],
            ['\binom{7}{4}', 'C(7,4) = 35 ways to place the four.'],
            ['15^{3}', '15³ = 3,375 — the 3 leftover positions are any of the other 15 digits.'],
            ['8400', 'Minus 8,400 that are actually a FULLEST HOUSE (4+3, rarer).'],
        ],
        'ALL_LETTERS' => [
            ['6^{7}', '6⁷ = 279,936 hashes using only the letters a–f.'],
            ['29640', 'Minus 29,640 letters-only hashes that also match a rarer pattern.'],
        ],
        'STRAIGHT_5' => [
            ['17728', '17,728 ways to place a 5-long run plus two free digits.'],
            ['684', 'Minus 684 that are really 6- or 7-long straights (rarer).'],
            ['384', 'Minus 384 more removed for overlap double-counting.'],
        ],
        'THREE_OF_KIND_PLUS_THREE' => [
            ['\binom{16}{2}', 'C(16,2) = 120 ways to pick the two digits that each appear three times.'],
            ['14', '14 choices for the leftover 7th digit.'],
            ['140', '140 = 7!/(3!·3!·1!) arrangements of two triples and a single.'],
            ['\binom{6}{2}', 'C(6,2) = 15 — the same count but letters only…'],
            ['4', '…4 leftover letter choices…'],
            ['140', '…×140 arrangements, subtracted because a rarer letters-only pattern wins first.'],
        ],
        'FULLER_HOUSE' => [
            ['16', '16 choices for the digit that appears three times.'],
            ['\binom{15}{2}', 'C(15,2) = 105 ways to pick the two digits that each appear twice.'],
            ['210', '210 = 7!/(3!·2!·2!) arrangements of a triple and two pairs.'],
            ['6', 'Letters-only version: 6 choices for the triple…'],
            ['\binom{5}{2}', '…C(5,2) = 10 for the two pairs…'],
            ['210', '…×210 arrangements, subtracted as a rarer letters-only pattern wins first.'],
        ],
        'FULL_HOUSE' => [
            ['16', '16 choices for the triple digit.'],
            ['15', '15 choices for the pair digit.'],
            ['\binom{14}{2}', 'C(14,2) = 91 ways to pick the two distinct leftover digits.'],
            ['420', '420 = 7!/(3!·2!·1!·1!) arrangements of a triple, a pair and two singles.'],
            ['75600', 'Minus 75,600 that are actually FIVE OF A KIND (rarer).'],
        ],
        'THREE_OF_KIND' => [
            ['16', '16 choices for the triple digit.'],
            ['\binom{15}{4}', 'C(15,4) = 1,365 ways to pick the 4 distinct leftover digits.'],
            ['840', '840 = 7!/3! arrangements of a triple and four distinct singles.'],
            ['25200', 'Minus 25,200 that also match a rarer pattern…'],
            ['300', '…minus 300 more for the remaining overlaps.'],
        ],
        'THREE_PAIR' => [
            ['4', '4 position layouts for three adjacent pairs in 7 characters.'],
            ['16', '16 choices for the first pair’s digit.'],
            ['15', '15 for the second pair’s digit.'],
            ['14', '14 for the third pair’s digit.'],
            ['13', '13 for the leftover single digit.'],
            ['4', 'Letters-only version: 4 layouts…'],
            ['6', '…6 letters for the first pair…'],
            ['5', '…5 for the second…'],
            ['4', '…4 for the third…'],
            ['3', '…3 for the single — subtracted as a rarer letters-only pattern wins.'],
        ],
        'TWO_PAIR' => [
            ['6014140', 'Net count: hashes whose best match is exactly two adjacent pairs, after removing every rarer pattern.'],
        ],
        'ALL_NUMBERS' => [
            ['10^{7}', '10⁷ = 10,000,000 digit-only hashes (each position 0–9).'],
            ['2932088', 'Minus 2,932,088 digit-only hashes that also match a rarer pattern.'],
        ],
        'ONE_PAIR' => [
            ['55017508', 'Net count: a single adjacent pair — all that remains after every rarer win is removed.'],
        ],
    ];

    /**
     * @param  array<string, mixed>  $pattern
     * @return array{latex: string, tips: list<string>}
     */
    public static function annotate(array $pattern): array
    {
        $latex = (string) $pattern['formulaLatex'];
        $type = (string) $pattern['type'];

        $tips = [];
        $numerators = self::dfracNumerators($latex);
        $rawText = $numerators[0] ?? null;
        $netText = $numerators[1] ?? null;

        // Tile the raw numerator into its numeric tokens, each with its own tooltip.
        $tiledRaw = $rawText === null
            ? null
            : self::tile($rawText, self::TOKENS[$type] ?? [], $tips);

        // The simplified net numerator (present only when the formula reduces).
        $netWrapped = null;
        if ($netText !== null) {
            $netWrapped = self::hook(self::pushTip($tips,
                'Net winning hashes: '.number_format((int) $pattern['net']).' (≈ 1 in '.number_format((int) $pattern['oneIn']).').'
            ), $netText);
        }

        // The denominator — the sample space, identical wherever 16^7 appears.
        $denomWrapped = self::hook(self::pushTip($tips, self::DENOMINATOR_TIP), self::DENOMINATOR);

        // Rebuild via placeholders so no wrap re-matches another region's digits.
        $phRaw = "\x01";
        $phNet = "\x02";
        if ($rawText !== null) {
            $latex = self::replaceFirst($latex, '\dfrac{'.$rawText.'}', '\dfrac{'.$phRaw.'}');
        }
        if ($netText !== null) {
            $latex = self::replaceFirst($latex, '\dfrac{'.$netText.'}', '\dfrac{'.$phNet.'}');
        }
        $latex = str_replace(self::DENOMINATOR, $denomWrapped, $latex);
        if ($tiledRaw !== null) {
            $latex = str_replace($phRaw, $tiledRaw, $latex);
        }
        if ($netWrapped !== null) {
            $latex = str_replace($phNet, $netWrapped, $latex);
        }

        return ['latex' => $latex, 'tips' => $tips];
    }

    /**
     * Wrap each configured token (in order) with a tooltip hook, leaving the
     * operators between them untouched.
     *
     * @param  list<array{0: string, 1: string}>  $tokens
     * @param  list<string>  $tips
     */
    private static function tile(string $text, array $tokens, array &$tips): string
    {
        $cursor = 0;
        $out = '';

        foreach ($tokens as [$token, $tip]) {
            $pos = strpos($text, $token, $cursor);
            if ($pos === false) {
                continue;
            }
            $out .= substr($text, $cursor, $pos - $cursor);
            $out .= self::hook(self::pushTip($tips, $tip), $token);
            $cursor = $pos + strlen($token);
        }

        return $out.substr($text, $cursor);
    }

    /**
     * The numerators of each \dfrac in order: [raw] or [raw, net]. Brace-aware so
     * \binom{}{} bodies are captured whole.
     *
     * @return list<string>
     */
    private static function dfracNumerators(string $latex): array
    {
        $numerators = [];
        $offset = 0;

        while (($open = strpos($latex, '\dfrac{', $offset)) !== false) {
            $start = $open + strlen('\dfrac{');
            $depth = 1;
            $i = $start;
            for (; $i < strlen($latex); $i++) {
                if ($latex[$i] === '{') {
                    $depth++;
                } elseif ($latex[$i] === '}') {
                    $depth--;
                    if ($depth === 0) {
                        break;
                    }
                }
            }
            $numerators[] = substr($latex, $start, $i - $start);
            $offset = $i + 1;
        }

        return $numerators;
    }

    /**
     * @param  list<string>  $tips
     */
    private static function pushTip(array &$tips, string $tip): int
    {
        $tips[] = $tip;

        return count($tips) - 1;
    }

    private static function hook(int $index, string $body): string
    {
        return '\htmlData{tip='.$index.'}{'.$body.'}';
    }

    private static function replaceFirst(string $haystack, string $needle, string $replace): string
    {
        $pos = strpos($haystack, $needle);

        return $pos === false
            ? $haystack
            : substr_replace($haystack, $replace, $pos, strlen($needle));
    }
}
