<?php

namespace Loevgaard\DandomainPeriodBundle\Period;

interface PeriodInterface {
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return int
     */
    public function getNumber(): int;

    /**
     * @return \DateTimeImmutable
     */
    public function getStart(): \DateTimeImmutable;

    /**
     * @return \DateTimeImmutable
     */
    public function getEnd(): \DateTimeImmutable;
}
