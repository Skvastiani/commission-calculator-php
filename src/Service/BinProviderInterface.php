<?php

namespace App\Service;

interface BinProviderInterface
{
    public function getCountryCode(string $bin): string;
}
