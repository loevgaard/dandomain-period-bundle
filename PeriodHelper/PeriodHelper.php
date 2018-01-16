<?php

namespace Loevgaard\DandomainPeriodBundle\PeriodHelper;

use Loevgaard\DandomainPeriodBundle\Period\Period;
use Loevgaard\DandomainPeriodBundle\Period\PeriodInterface;

class PeriodHelper implements PeriodHelperInterface
{
    /**
    /**
     * @var string
     */
    protected $format;

    /**
     * @var \DateInterval
     */
    protected $interval;

    /**
     * @var int
     */
    protected $startYear;

    /**
     * @var string
     */
    protected $startDay;

    public function __construct(string $format, string $interval, int $startYear, string $startDay)
    {
        $this->format = $format;
        $this->interval = new \DateInterval($interval);
        $this->startYear = $startYear;
        $this->startDay = ucfirst($startDay);
    }

    public function currentPeriod(): PeriodInterface
    {
        // @todo to test this it would be clever to install carbon where you can inject static dates to test against
        // in this bundle we presume that a period changes every week

        $now = new \DateTimeImmutable();

        // first deduce the start and end dates
        if ($now->format('l') === $this->startDay) {
            $startDay = new \DateTimeImmutable();
        } else {
            $startDay = new \DateTimeImmutable('last '.$this->startDay);
        }

        $startDay = $startDay->setTime(0, 0, 0);
        $endDay = $startDay->add($this->interval)->setTime(23, 59, 59);

        // now we need to find this 'weeks' end day and it's as simple as adding a week to the start day
        $weekEndDay = $startDay->add(new \DateInterval('P1W'))->setTime(23, 59, 59);

        // to find the period number we need the number of weeks since the start year
        $startYearDay = new \DateTime('first '.$this->startDay.' of january 2016');
        $number = ceil(intval($weekEndDay->diff($startYearDay)->format('%a')) / 7);

        $period = new Period(sprintf($this->format, $number), $number, $startDay, $endDay);

        return $period;
    }

    public function nextPeriod(): PeriodInterface
    {
        $currentPeriod = $this->currentPeriod();

        $week = new \DateInterval('P1W');
        $number = $currentPeriod->getNumber() + 1;
        $start = $currentPeriod->getStart()->add($week);
        $end = $currentPeriod->getEnd()->add($week);

        return new Period(sprintf($this->format, $number), $number, $start, $end);
    }
}
