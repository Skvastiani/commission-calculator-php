<?php

require __DIR__ . '/vendor/autoload.php';

use App\Service\BinListProvider;
use App\Service\ExchangeRateApiProvider;
use App\Service\CommissionCalculator;
use App\Model\Transaction;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$inputFile = $argv[1] ?? null;

if (!$inputFile || !file_exists($inputFile)) {
    echo "Input file is missing or not found." . PHP_EOL;
    exit(1);
}

$binProvider = new BinListProvider();
$rateProvider = new ExchangeRateApiProvider();
$calculator = new CommissionCalculator($binProvider, $rateProvider);

$rows = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($rows as $row) {
    try {
        $data = json_decode($row, true);
        if (!$data || !isset($data['bin'], $data['amount'], $data['currency'])) {
            throw new Exception('Invalid transaction data.');
        }

        $transaction = new Transaction($data['bin'], $data['amount'], $data['currency']);
        $commission = $calculator->calculate($transaction);
        echo number_format($commission, 2, '.', '') . PHP_EOL;
    } catch (Throwable $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
    }
}
