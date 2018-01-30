<?php

namespace Loevgaard\DandomainPeriodBundle\PeriodCreator;

use Dandomain\Import\Period;
use Dandomain\ImportExportClient;
use Dandomain\Xml\Period as PeriodElement;
use Loevgaard\DandomainPeriodBundle\PeriodHelper\PeriodHelperInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PeriodCreator implements PeriodCreatorInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

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
        $this->logger = new NullLogger();

        $this->periodHelper = $periodHelper;
        $this->importDir = realpath($importDir);
        $this->importUrl = rtrim($importUrl, '/');
        $this->dandomainUrl = $dandomainUrl;
        $this->dandomainUsername = $dandomainUsername;
        $this->dandomainPassword = $dandomainPassword;
    }

    public function createPeriods(bool $dryRun = false): bool
    {
        ImportExportClient::setHost($this->dandomainUrl);
        ImportExportClient::setUsername($this->dandomainUsername);
        ImportExportClient::setPassword($this->dandomainPassword);

        $currentPeriod = $this->periodHelper->currentPeriod();
        $nextPeriod = $this->periodHelper->nextPeriod();
        $filename = uniqid('period-import-').'.xml';
        $path = $this->importDir.'/'.$filename;

        $this->logger->info('Creating periods:');
        $this->logger->info('- '.$currentPeriod->getId().' | '.$currentPeriod->getStart()->format('Y-m-d').' - '.$currentPeriod->getEnd()->format('Y-m-d'));
        $this->logger->info('- '.$nextPeriod->getId().' | '.$nextPeriod->getStart()->format('Y-m-d').' - '.$nextPeriod->getEnd()->format('Y-m-d'));

        $periodImport = new Period($path, $this->importUrl.'/'.$filename);
        $periodImport->addElement(new PeriodElement($currentPeriod->getId(), $currentPeriod->getId(), $currentPeriod->getStart(), $currentPeriod->getEnd()));
        $periodImport->addElement(new PeriodElement($nextPeriod->getId(), $nextPeriod->getId(), $nextPeriod->getStart(), $nextPeriod->getEnd()));

        if ($dryRun) {
            $path = $periodImport->createImportFile();
            $this->logger->info('Import file was saved to: '.$path);
            return true;
        }

        $res = $periodImport->import();

        return (bool) $res->getStatus();
    }

    public function setLogger(LoggerInterface $logger): PeriodCreatorInterface
    {
        $this->logger = $logger;

        return $this;
    }
}
