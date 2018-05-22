<?php

declare(strict_types=1);

namespace Loevgaard\DandomainPeriodBundle\PeriodCreator;

use Dandomain\Import\Period;
use Dandomain\ImportExportClient;
use Dandomain\Xml\Period as PeriodElement;
use Loevgaard\DandomainPeriodBundle\PeriodHelper\PeriodHelperInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PeriodCreator implements PeriodCreatorInterface
{
    use LoggerAwareTrait;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var PeriodHelperInterface
     */
    protected $periodHelper;

    /**
     * Number of periods to create ahead.
     *
     * @var int
     */
    protected $ahead;

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

    public function __construct(PeriodHelperInterface $periodHelper, int $ahead, string $importDir, string $importUrl, string $dandomainUrl, string $dandomainUsername, string $dandomainPassword)
    {
        $this->logger = new NullLogger();

        $this->periodHelper = $periodHelper;
        $this->ahead = $ahead;
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

        $filename = uniqid('period-import-').'.xml';
        $path = $this->importDir.'/'.$filename;
        $periodImport = new Period($path, $this->importUrl.'/'.$filename);

        $i = 0;
        $period = null;

        do {
            $period = $this->periodHelper->nextPeriod($period);
            $this->logger->info('Creating period: '.$period->getId().' | '.$period->getStart()->format('Y-m-d').' - '.$period->getEnd()->format('Y-m-d'));

            $periodImport->addElement(new PeriodElement($period->getId(), $period->getId(), $period->getStart(), $period->getEnd()));

            $i++;
        } while ($i < $this->ahead);

        if ($dryRun) {
            $path = $periodImport->createImportFile();
            $this->logger->info('Import file was saved to: '.$path);

            return true;
        }

        $res = $periodImport->import();

        if (!$res) {
            $this->logger->emergency($res->getXml());
        }

        return (bool) $res->getStatus();
    }
}
