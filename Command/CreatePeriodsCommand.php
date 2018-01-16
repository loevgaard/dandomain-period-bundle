<?php

namespace Loevgaard\DandomainPeriodBundle\Command;

use Loevgaard\DandomainPeriodBundle\PeriodCreator\PeriodCreatorInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePeriodsCommand extends ContainerAwareCommand
{
    use LockableTrait;

    /**
     * @var PeriodCreatorInterface
     */
    protected $periodCreator;

    public function __construct(PeriodCreatorInterface $periodCreator)
    {
        $this->periodCreator = $periodCreator;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('loevgaard:dandomain:period:create-periods')
            ->setDescription('Will create periods in Dandomain that match the settings of this bundle')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!($this->lock())) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        $res = $this->periodCreator->createPeriods();

        if(!$res) {
            throw new \Exception('The import failed.');
        }

        return 0;
    }
}
