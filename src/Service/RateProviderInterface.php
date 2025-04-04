<?php

namespace App\Service;

interface RateProviderInterface
{
    public function getRate(string $currency): float;
}
