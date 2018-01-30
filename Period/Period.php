<?php

namespace Loevgaard\DandomainPeriodBundle\Period;

use Carbon\Carbon;

class Period implements PeriodInterface
{
    /**
     * This is the formatted id that could be imported into Dandomain.
     *
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $number;

    /**
     * @var Carbon
     */
    protected $start;

    /**
     * @var Carbon
     */
    protected $end;

    public function __construct(string $id, int $number, Carbon $start, Carbon $end)
    {
        $this->id = $id;
        $this->number = $number;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * {@inheritdoc}
     */
    public function getStart(): Carbon
    {
        return $this->start;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd(): Carbon
    {
        return $this->end;
    }
}
