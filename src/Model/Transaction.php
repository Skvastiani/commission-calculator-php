<?php

namespace App\Model;

class Transaction
{
    public function __construct(
        public string $bin,
        public float $amount,
        public string $currency
    ) {
    }
}
