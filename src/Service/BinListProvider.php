<?php

namespace App\Service;

class BinListProvider implements BinProviderInterface
{

    public function getCountryCode(string $bin): string
    {
        $response = file_get_contents("https://lookup.binlist.net/" . $bin);
        if ( ! $response) {
            throw new \RuntimeException('BIN service unavailable');
        }
        $data = json_decode($response, true);

        return $data['country']['alpha2'] ?? '';
    }
}
