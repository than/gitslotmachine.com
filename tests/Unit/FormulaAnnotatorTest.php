<?php

use App\Services\FormulaAnnotator;
use App\Services\Ruleset;

/**
 * Unwraps every \htmlData{tip=N}{BODY} — brace-aware, so nested \binom{}{} bodies
 * survive. $keepBody true leaves BODY in place (proving annotation is
 * non-destructive); false drops it too, leaving only the formula skeleton
 * (operators, \dfrac, braces).
 */
function unwrapHtmlData(string $latex, bool $keepBody): string
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

        $body = $keepBody ? substr($latex, $bodyOpen + 1, $bodyClose - $bodyOpen - 1) : '';
        $latex = substr($latex, 0, $start).$body.substr($latex, $bodyClose + 1);
    }

    return $latex;
}

function stripHtmlData(string $latex): string
{
    return unwrapHtmlData($latex, keepBody: true);
}

function removeHtmlData(string $latex): string
{
    return unwrapHtmlData($latex, keepBody: false);
}

// Secret patterns are annotated too, even though /odds hides them — otherwise the
// guard below shares its blind spot with the bug it exists to catch, and a secret
// surfaced later would arrive with untooltipped numbers. NO_WIN is excluded: its
// formula is a single enumerated total with nothing to explain term by term.
dataset('annotatable patterns', fn () => collect(Ruleset::patterns())
    ->reject(fn ($p) => $p['type'] === 'NO_WIN')
    ->mapWithKeys(fn ($p) => [$p['type'] => [$p]])
    ->all());

it('annotates without changing the underlying formula', function (array $pattern) {
    $annotated = FormulaAnnotator::annotate($pattern);

    expect(stripHtmlData($annotated['latex']))->toBe($pattern['formulaLatex']);
})->with('annotatable patterns');

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
})->with('annotatable patterns');

it('leaves no bare number untiled — every magic number has a tooltip', function (array $pattern) {
    $annotated = FormulaAnnotator::annotate($pattern);

    // Remove each \htmlData hook AND its body. Every numeric token and the denominator
    // are wrapped, so the skeleton that remains (operators, \dfrac, braces) has no digit.
    $skeleton = removeHtmlData($annotated['latex']);

    expect($skeleton)->not->toMatch('/\d/');
})->with('annotatable patterns');
