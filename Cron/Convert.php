<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Cron;

use Psr\Log\LoggerInterface;
use Unexpected\Webp\Helper\Config;
use Unexpected\Webp\Helper\Process;

class Convert
{
    /** @var Config */
    private $config;

    /** @var Process */
    private $process;

    /** @var LoggerInterface */
    private $logger;

    /**
     * Convert constructor.
     * @param Config $config
     * @param Process $process
     * @param LoggerInterface $logger
     */
    public function __construct(Config $config, Process $process, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->process = $process;
        $this->logger = $logger;
    }

    public function execute()
    {
        if ($this->config->getEnabledConfig() && $this->config->getCronEnabledConfig()) {
            $extensions = $this->config->getCronImageFormatsConfig();
            $folders = $this->config->getCronFoldersConfig();

            $images = $this->process->getImages($extensions, $folders);
            $count = $images->count();

            $this->logger->info("Cron total images: ${count}");

            $convertedImages = $this->process->convert($images, true);

            $this->logger->info("Cron converted images: ${convertedImages}");
        }
    }
}
