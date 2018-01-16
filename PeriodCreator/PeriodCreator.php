<?php

namespace Loevgaard\DandomainPeriodBundle\PeriodCreator;

use Dandomain\Import\Period;
use Dandomain\ImportExportClient;
use Dandomain\Xml\Period as PeriodElement;
use Loevgaard\DandomainPeriodBundle\PeriodHelper\PeriodHelperInterface;

class PeriodCreator implements PeriodCreatorInterface
{
    /**
     * @var PeriodHelperInterface
     */
    protected $periodHelper;

    /**
     * @var string
     */
    protected $importDir;

    /**
     * This is the public URL where imports can be accessed by Dandomain's servers.
     *
     * @var string
     */
    protected $importUrl;

    /**
     * @var string
     */
    protected $dandomainUrl;

    /**
     * @var string
     */
    protected $dandomainUsername;

    /**
     * @var string
     */
    protected $dandomainPassword;

    public function __construct(PeriodHelperInterface $periodHelper, string $importDir, string $importUrl, string $dandomainUrl, string $dandomainUsername, string $dandomainPassword)
    {
        $this->periodHelper = $periodHelper;
        $this->importDir = realpath($importDir);
        $this->importUrl = rtrim($importUrl, '/');
        $this->dandomainUrl = $dandomainUrl;
        $this->dandomainUsername = $dandomainUsername;
        $this->dandomainPassword = $dandomainPassword;
    }

    public function createPeriods(): bool
    {
        ImportExportClient::setHost($this->dandomainUrl);
        ImportExportClient::setUsername($this->dandomainUsername);
        ImportExportClient::setPassword($this->dandomainPassword);

        $currentPeriod = $this->periodHelper->currentPeriod();
        $nextPeriod = $this->periodHelper->nextPeriod();
        $filename = uniqid('period-import-').'.xml';
        $path = $this->importDir.'/'.$filename;

        $periodImport = new Period($path, $this->importUrl.'/'.$filename);
        $periodImport->addElement(new PeriodElement($currentPeriod->getId(), $currentPeriod->getId(), $currentPeriod->getStart(), $currentPeriod->getEnd()));
        $periodImport->addElement(new PeriodElement($nextPeriod->getId(), $nextPeriod->getId(), $nextPeriod->getStart(), $nextPeriod->getEnd()));
        $res = $periodImport->import();

        return (bool) $res->getStatus();
    }
}
