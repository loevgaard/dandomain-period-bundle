<?php

namespace Loevgaard\DandomainPeriodBundle\Tests\Period;

use Loevgaard\DandomainPeriodBundle\Period\Period;
use PHPUnit\Framework\TestCase;

class PeriodTest extends TestCase
{
    public function testGettersSetters()
    {
        $start = \DateTimeImmutable::createFromFormat('Y-m-d', '2018-01-01');
        $end = \DateTimeImmutable::createFromFormat('Y-m-d', '2018-02-01');

        $period = new Period('id', 1, $start, $end);

        $this->assertSame('id', $period->getId());
        $this->assertSame(1, $period->getNumber());
        $this->assertSame($start, $period->getStart());
        $this->assertSame($end, $period->getEnd());
    }
}
