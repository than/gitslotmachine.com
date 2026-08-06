<?php

use App\Services\PatternDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads the odds page successfully', function () {
    $this->get('/odds')->assertOk();
});

// The table must read biggest-prize-first. Guards the reorder: HEXTET (25k) used to
// render before LUCKY SEVEN (100k) because patterns were in detection order, not payout.
it('lists patterns in descending payout order', function () {
    $this->get('/odds')->assertSeeInOrder([
        'JACKPOT',       // 250,000
        'LUCKY SEVEN',   // 100,000
        'BIG STRAIGHT',  //  50,000
        'HEXTET',        //  25,000
        'ONE PAIR',      //      10
    ]);
});

// The annotation map() in routes/web.php is otherwise untested — delete it and every
// other assertion here still passes.
it('carries annotated formulas and their tooltips into the page', function () {
    $response = $this->get('/odds');

    $response->assertSee('data-latex', false)
        // Named in full: 'data-latex' alone also matches as a substring of this
        // attribute, so it can't pin the fallback on its own.
        ->assertSee('data-latex-plain', false)
        ->assertSee('htmlData{tip=', false)
        ->assertSee('data-tips', false)
        ->assertSee('268,435,456');
});

// KaTeX renders the visual formula inside aria-hidden="true", so the hover tooltips
// are invisible to assistive tech and can't be fixed in place — aria-hidden is
// inherited and a descendant can't override it. These <details> lists are the
// keyboard/screen-reader path, so they have to be real content on the page.
it('exposes every tooltip as readable content outside the aria-hidden render', function () {
    $response = $this->get('/odds');

    $response->assertSee('formula-explain', false)
        ->assertSee('Explain this formula')
        // Anchored to the <li> markup: the bare tip string also survives verbatim
        // inside the data-tips attribute (json_encode only escapes non-ASCII), so an
        // unanchored assertSee would stay green with the list deleted.
        ->assertSee('<li>16 choices for the six-of-a-kind digit.</li>', false);
});

// Pins the disclosure to the PATTERN cell. The PROBABILITY cell is hidden
// md:table-cell — display:none removes it from the accessibility tree, and below md
// there's no hover either — so moving the <details> back there would make the a11y
// path desktop-only again while every other assertion stayed green. hash-display is
// the EXAMPLE cell, the td right after PATTERN.
it('keeps the formula explanations in the always-visible pattern cell', function () {
    $html = $this->get('/odds')->getContent();

    // First-occurrence comparison, not assertSeeInOrder: both needles appear once per
    // row, so a sequential sweep is free to straddle rows and passes even after the
    // revert. The first disclosure preceding the first hash-display (the EXAMPLE cell,
    // the td right after PATTERN) is only true while the <details> is in PATTERN.
    expect(strpos($html, 'Explain this formula'))
        ->toBeLessThan(strpos($html, 'hash-display'));
});

// EXAMPLE VALIDATION: every example hash in the canonical ruleset must detect as its own type,
// guarding the example hashes the odds page displays.
it('detects each canonical example as its own pattern type', function () {
    $detector = new PatternDetector;

    $patterns = json_decode(
        (string) file_get_contents(resource_path('data/patterns.json')),
        true,
    )['patterns'];

    foreach ($patterns as $pattern) {
        expect($detector->detect($pattern['example'])['type'])->toBe($pattern['type']);
    }
});
