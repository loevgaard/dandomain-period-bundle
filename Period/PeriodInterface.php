<?php

namespace Loevgaard\DandomainPeriodBundle\Period;

use Carbon\Carbon;

interface PeriodInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return int
     */
    public function getNumber(): int;

    /**
     * @return Carbon
     */
    public function getStart(): Carbon;

    /**
     * @return Carbon
     */
    public function getEnd(): Carbon;
}
