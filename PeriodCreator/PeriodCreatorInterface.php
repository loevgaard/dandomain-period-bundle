<?php

declare(strict_types=1);

namespace Loevgaard\DandomainPeriodBundle\PeriodCreator;

use Psr\Log\LoggerAwareInterface;

interface PeriodCreatorInterface extends LoggerAwareInterface
{
    /**
     * This method will create the deduced (from settings) periods in Dandomain
     * If they are already created it will ignore that.
     *
     * @param bool $dryRun
     *
     * @return bool
     */
    public function createPeriods(bool $dryRun = false): bool;
}
