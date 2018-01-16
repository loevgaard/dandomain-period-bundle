<?php

namespace Loevgaard\DandomainPeriodBundle\Period;

class Period implements PeriodInterface
{
    /**
     * This is the formatted id that could be imported into Dandomain
     *
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $number;

    /**
     * @var \DateTimeImmutable
     */
    protected $start;

    /**
     * @var \DateTimeImmutable
     */
    protected $end;

    public function __construct(string $id, int $number, \DateTimeImmutable $start, \DateTimeImmutable $end)
    {
        $this->id = $id;
        $this->number = $number;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @inheritdoc
     */
    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    /**
     * @inheritdoc
     */
    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
    }
}
