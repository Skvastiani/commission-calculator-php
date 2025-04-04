<?php

namespace App\Service;

use RuntimeException;

class BinListProvider implements BinProviderInterface
{

    const string BIN_LIST_URL = 'https://lookup.binlist.net/';

    public function getCountryCode(string $bin): string
    {
        $response = file_get_contents(self::BIN_LIST_URL . $bin);
        if (!$response) {
            throw new RuntimeException('BIN service unavailable');
        }
        $data = json_decode($response, true);

        return $data['country']['alpha2'] ?? '';
    }
}
