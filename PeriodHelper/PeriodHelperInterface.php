<?php

declare(strict_types=1);

namespace Loevgaard\DandomainPeriodBundle\PeriodHelper;

use Loevgaard\DandomainPeriodBundle\Period\PeriodInterface;

interface PeriodHelperInterface
{
    public function currentPeriod(): PeriodInterface;

    public function nextPeriod(?PeriodInterface $period = null): PeriodInterface;
}
