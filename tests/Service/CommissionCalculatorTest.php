<?php

namespace Tests\Service;

use App\Model\Transaction;
use App\Service\BinProviderInterface;
use App\Service\CommissionCalculator;
use App\Service\RateProviderInterface;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{

    protected function tearDown(): void
    {
        m::close();
    }


    public function testCalculateForEuCountry()
    {
        $binProvider = m::mock(BinProviderInterface::class);
        $rateProvider = m::mock(RateProviderInterface::class);

        $binProvider->shouldReceive('getCountryCode')->with('45717360')->andReturn('DE');
        $rateProvider->shouldReceive('getRate')->with('EUR')->andReturn(1);

        $calculator = new CommissionCalculator($binProvider, $rateProvider);
        $transaction = new Transaction('45717360', 100.00, 'EUR');
        $result = $calculator->calculate($transaction);

        $this->assertEquals(1.00, $result);
    }


    public function testCalculateForNonEuCountry()
    {
        $binProvider = m::mock(BinProviderInterface::class);
        $rateProvider = m::mock(RateProviderInterface::class);

        $binProvider->shouldReceive('getCountryCode')->with('41417360')->andReturn('US');
        $rateProvider->shouldReceive('getRate')->with('USD')->andReturn(1.109158);

        $calculator = new CommissionCalculator($binProvider, $rateProvider);
        $transaction = new Transaction('41417360', 130.00, 'USD');
        $result = $calculator->calculate($transaction);

        $this->assertEquals(2.35, $result);
    }
}
