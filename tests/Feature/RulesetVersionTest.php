<?php

use App\Services\Ruleset;

it('exposes the ruleset version and hash for CLI drift detection', function () {
    $this->getJson('/api/ruleset-version')
        ->assertOk()
        ->assertJson(['success' => true])
        ->assertJsonPath('data.version', 3)
        ->assertJsonPath('data.version', Ruleset::version())
        ->assertJsonPath('data.hash', Ruleset::hash())
        ->assertJsonStructure(['data' => ['version', 'hash']]);
});
