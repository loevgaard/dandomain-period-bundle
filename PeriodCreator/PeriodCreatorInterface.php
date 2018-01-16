<?php

namespace Loevgaard\DandomainPeriodBundle\PeriodCreator;

interface PeriodCreatorInterface
{
    /**
     * This method will create the deduced (from settings) periods in Dandomain
     * If they are already created it will ignore that.
     *
     * @return bool
     */
    public function createPeriods(): bool;
}
