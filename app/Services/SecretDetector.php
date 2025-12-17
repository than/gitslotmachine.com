<?php

namespace App\Services;

class SecretDetector
{
    // Obfuscated: h=sha256, p=payout, n=name as char codes
    private const DATA = [
        // Legendary (42069)
        ['h' => '56379c14419d2363ce258293ed6adc3684ae4926aba335a49b75d7483b9f85ae', 'p' => 42069, 'n' => [66, 65, 68, 32, 70, 79, 79, 68]],
        ['h' => '475fbf8c77411b86a660c8d61f181e464a50572e8f1ff4ffb28e75289625d494', 'p' => 42069, 'n' => [67, 79, 70, 70, 69, 69, 83]],
        ['h' => 'a0d9961dc6d7e4059ec746b8ab9a20f330c1308266c5197da77e7bda839457b9', 'p' => 42069, 'n' => [68, 69, 65, 68, 32, 66, 69, 68]],
        ['h' => 'a3fe33e094647d5071cb6d3719bd7b55eb1ad1fb06f2c33923e66363fd805020', 'p' => 42069, 'n' => [68, 73, 83, 69, 65, 83, 69]],
        // Great (6969)
        ['h' => '011a1a79ee662806e273538d1a0036b8090f939efd05e312f402ed9fdcaf5f25', 'p' => 6969, 'n' => [73, 67, 69, 32, 67, 79, 76, 68]],
        ['h' => '432fe036b0239c1551947f5a1f93006f2f62e4c5512909453e539eba348b678f', 'p' => 6969, 'n' => [83, 65, 68, 32, 70, 65, 67, 69]],
        ['h' => 'dcf79a973cb6e6d4567c17e24865d39370db2bc6a167ebdcbeaedcda83906350', 'p' => 6969, 'n' => [66, 79, 65, 83, 84, 69, 68]],
        // Good (1337)
        ['h' => '70d8dec01053bbf890288c8927f605f209533b52512dafd9f145e891d6d3f0ae', 'p' => 1337, 'n' => [66, 65, 68, 32, 67, 65, 70, 69]],
        ['h' => '2c8d79f36c7758d1268998039ca8a565cb6687958516f370e10086b45c2a1ea7', 'p' => 1337, 'n' => [68, 69, 70, 65, 67, 69, 68]],
    ];

    public function check(string $hash): ?array
    {
        $h = hash('sha256', strtolower($hash));

        foreach (self::DATA as $s) {
            if ($s['h'] === $h) {
                return [
                    'name' => implode('', array_map('chr', $s['n'])),
                    'payout' => $s['p'],
                ];
            }
        }

        return null;
    }

    public function getHashForName(string $name): ?string
    {
        foreach (self::DATA as $s) {
            $decodedName = implode('', array_map('chr', $s['n']));
            if ($decodedName === $name) {
                return $s['h'];
            }
        }

        return null;
    }
}
