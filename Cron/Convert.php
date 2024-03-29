<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Cron;

use Psr\Log\LoggerInterface;
use Greenrivers\Webp\Helper\Config;
use Greenrivers\Webp\Helper\Process;

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

    public function execute(): void
    {
        if ($this->config->getEnabledConfig() && $this->config->getCronEnabledConfig()) {
            $extensions = $this->config->getCronImageFormatsConfig();
            $folders = $this->config->getCronFoldersConfig();

            $images = $this->process->getImages($extensions, $folders);
            $imagesToConversion = $this->process->getImagesToConversion($images);
            $countImages = $images->count();
            $countImagesToConversion = count($imagesToConversion);

            $this->logger->info("Cron total images: ${countImages}");
            $this->logger->info("Cron images to conversion: ${countImagesToConversion}");

            $convertData = $this->process->convert($imagesToConversion, true);
            $convertedImages = $convertData['converted_images'];
            $errorImages = $convertData['error_images'];

            $this->logger->info("Cron converted images: ${convertedImages}, errors images: ${errorImages}");
        }
    }
}
