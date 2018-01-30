<?php

namespace Loevgaard\DandomainPeriodBundle\PeriodHelper;

use Carbon\Carbon;
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

        $now = Carbon::now();

        // first deduce the start and end dates
        if ($now->format('l') === $this->startDay) {
            $startDay = Carbon::now();
        } else {
            $startDay = new Carbon('last '.$this->startDay);
        }

        $startDay->setTime(0, 0, 0);
        $endDay = $startDay->copy()->add($this->interval)->setTime(23, 59, 59);

        // now we need to find this 'weeks' end day and it's as simple as adding a week to the start day
        $weekEndDay = $startDay->copy()->addWeek()->setTime(23, 59, 59);

        // to find the period number we need the number of weeks since the start year
        $startYearDay = new Carbon('first '.$this->startDay.' of january 2016');
        $number = ceil(intval($weekEndDay->diff($startYearDay)->format('%a')) / 7);

        $period = new Period(sprintf($this->format, $number), $number, $startDay, $endDay);

        return $period;
    }

    public function nextPeriod(): PeriodInterface
    {
        $currentPeriod = $this->currentPeriod();

        $number = $currentPeriod->getNumber() + 1;
        $start = $currentPeriod->getStart()->copy()->addWeek();
        $end = $currentPeriod->getEnd()->copy()->addWeek();

        return new Period(sprintf($this->format, $number), $number, $start, $end);
    }
}
