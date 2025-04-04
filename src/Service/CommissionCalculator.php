<?php

namespace App\Service;

use App\Model\Transaction;

class CommissionCalculator
{
    private const EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR',
        'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'
    ];

    public function __construct(
        private readonly BinProviderInterface $binProvider,
        private readonly RateProviderInterface $rateProvider
    ) {}

    public function calculate(Transaction $transaction): float
    {
        $countryCode = $this->binProvider->getCountryCode($transaction->bin);
        $isEu = in_array($countryCode, self::EU_COUNTRIES, true);

        $rate = $transaction->currency === 'EUR'
            ? 1
            : $this->rateProvider->getRate($transaction->currency);

        $amountEur = $rate > 0 ? $transaction->amount / $rate : $transaction->amount;
        $commissionRate = $isEu ? 0.01 : 0.02;
        $commission = $amountEur * $commissionRate;

        return ceil($commission * 100) / 100;
    }
}
