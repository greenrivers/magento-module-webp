<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Helper;

use Exception;
use Magento\Cron\Model\Config\Source\Frequency;
use Magento\Framework\App\Config\ValueFactory as ConfigValueFactory;
use Psr\Log\LoggerInterface;

class Cron
{
    const CRON_STRING_PATH = 'crontab/greenrivers/jobs/greenrivers_webp_convert/schedule/cron_expr';
    const CRON_MODEL_PATH = 'crontab/greenrivers/jobs/greenrivers_webp_convert/run/model';

    /** @var ConfigValueFactory */
    private $configValueFactory;

    /** @var LoggerInterface */
    private $logger;

    /** @var string */
    private $runModelPath = '';

    /**
     * Cron constructor.
     * @param ConfigValueFactory $configValueFactory
     * @param LoggerInterface $logger
     * @param string $runModelPath
     */
    public function __construct(
        ConfigValueFactory $configValueFactory,
        LoggerInterface $logger,
        string $runModelPath = ''
    ) {
        $this->configValueFactory = $configValueFactory;
        $this->logger = $logger;
        $this->runModelPath = $runModelPath;
    }

    /**
     * @param string $time
     * @param string $frequency
     */
    public function saveConfig(string $time, string $frequency): void
    {
        $timeArray = explode(',', $time);

        $cronExprArray = [
            intval($timeArray[1]), //Minute
            intval($timeArray[0]), //Hour
            $frequency == Frequency::CRON_MONTHLY ? '1' : '*', //Day of the Month
            '*', //Month of the Year
            $frequency == Frequency::CRON_WEEKLY ? '1' : '*', //Day of the Week
        ];

        $cronExprString = join(' ', $cronExprArray);

        try {
            $this->configValueFactory->create()->load(
                self::CRON_STRING_PATH,
                'path'
            )->setValue(
                $cronExprString
            )->setPath(
                self::CRON_STRING_PATH
            )->save();

            $this->configValueFactory->create()->load(
                self::CRON_MODEL_PATH,
                'path'
            )->setValue(
                $this->runModelPath
            )->setPath(
                self::CRON_MODEL_PATH
            )->save();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
