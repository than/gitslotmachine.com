<?php

use App\Services\FormulaAnnotator;
use App\Services\Ruleset;

/**
 * Removes every \htmlData{tip=N}{BODY} wrapper, keeping BODY — brace-aware so
 * nested \binom{}{} bodies survive. Used to prove annotation is non-destructive.
 */
function stripHtmlData(string $latex): string
{
    while (($start = strpos($latex, '\htmlData{tip=')) !== false) {
        // Find the opening brace of the BODY (the second {...} group).
        $braceOpen = strpos($latex, '{', $start + strlen('\htmlData'));
        $bodyOpen = strpos($latex, '{', strpos($latex, '}', $braceOpen) + 1);

        $depth = 0;
        $bodyClose = null;
        for ($i = $bodyOpen; $i < strlen($latex); $i++) {
            if ($latex[$i] === '{') {
                $depth++;
            } elseif ($latex[$i] === '}') {
                $depth--;
                if ($depth === 0) {
                    $bodyClose = $i;
                    break;
                }
            }
        }

        $body = substr($latex, $bodyOpen + 1, $bodyClose - $bodyOpen - 1);
        $latex = substr($latex, 0, $start).$body.substr($latex, $bodyClose + 1);
    }

    return $latex;
}

/**
 * Removes each \htmlData{tip=N}{BODY} wrapper AND its body entirely — brace-aware,
 * leaving only the formula skeleton (operators, \dfrac, braces).
 */
function removeHtmlData(string $latex): string
{
    while (($start = strpos($latex, '\htmlData{tip=')) !== false) {
        $braceOpen = strpos($latex, '{', $start + strlen('\htmlData'));
        $bodyOpen = strpos($latex, '{', strpos($latex, '}', $braceOpen) + 1);

        $depth = 0;
        $bodyClose = null;
        for ($i = $bodyOpen; $i < strlen($latex); $i++) {
            if ($latex[$i] === '{') {
                $depth++;
            } elseif ($latex[$i] === '}') {
                $depth--;
                if ($depth === 0) {
                    $bodyClose = $i;
                    break;
                }
            }
        }

        $latex = substr($latex, 0, $start).substr($latex, $bodyClose + 1);
    }

    return $latex;
}

$visible = collect(Ruleset::patterns())
    ->reject(fn ($p) => $p['secret'] || $p['type'] === 'NO_WIN')
    ->values()
    ->all();

it('annotates without changing the underlying formula', function (array $pattern) {
    $annotated = FormulaAnnotator::annotate($pattern);

    expect(stripHtmlData($annotated['latex']))->toBe($pattern['formulaLatex']);
})->with(array_map(fn ($p) => [$p], $visible));

it('always explains the sample space (denominator) and the counting', function (array $pattern) {
    $annotated = FormulaAnnotator::annotate($pattern);

    // Every formula has a 16^7 denominator, so there is always at least one tip.
    expect($annotated['tips'])->not->toBeEmpty()
        ->and(collect($annotated['tips'])->contains(fn ($t) => str_contains($t, '268,435,456')))->toBeTrue();

    // Each tip index referenced in the LaTeX must exist in the tips array.
    preg_match_all('/\\\\htmlData\{tip=(\d+)\}/', $annotated['latex'], $m);
    foreach ($m[1] as $i) {
        expect($annotated['tips'][(int) $i] ?? null)->not->toBeNull();
    }
})->with(array_map(fn ($p) => [$p], $visible));

it('leaves no bare number untiled — every magic number has a tooltip', function (array $pattern) {
    $annotated = FormulaAnnotator::annotate($pattern);

    // Remove each \htmlData hook AND its body. Every numeric token and the denominator
    // are wrapped, so the skeleton that remains (operators, \dfrac, braces) has no digit.
    $skeleton = removeHtmlData($annotated['latex']);

    expect($skeleton)->not->toMatch('/\d/');
})->with(array_map(fn ($p) => [$p], $visible));
