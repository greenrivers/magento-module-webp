<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Helper;

use Exception;
use Jcupitt\Vips\Exception as VipsException;
use Jcupitt\Vips\Image;
use Magento\Framework\Filesystem\Io\File;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;
use Unexpected\Webp\Model\Config\Source\Algorithm;

class Converter
{
    const PNG_EXTENSION = 'png';

    /** @var Config */
    private $config;

    /** @var File */
    private $file;
    
    /** @var LoggerInterface */
    private $logger;

    /**
     * Converter constructor.
     * @param Config $config
     * @param File $file
     * @param LoggerInterface $logger
     */
    public function __construct(Config $config, File $file, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->file = $file;
        $this->logger = $logger;
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     * @return bool
     */
    public function convert(string $imagePath, string $webpPath): bool
    {
        switch ($this->config->getAlgorithmConfig()) {
            case Algorithm::WEBP_ALGORITHM:
                return $this->convertWebp($imagePath, $webpPath);
            case Algorithm::CWEBP_ALGORITHM:
                return $this->convertCwebp($imagePath, $webpPath);
            case Algorithm::VIPS_ALGORITHM:
                return $this->convertVips($imagePath, $webpPath);
        }
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     * @return bool
     */
    private function convertWebp(string $imagePath, string $webpPath): bool
    {
        $imageData = $this->file->getPathInfo($imagePath);

        if ($imageData['extension'] === self::PNG_EXTENSION) {
            $image = imagecreatefrompng($imagePath);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        } else {
            $image = imagecreatefromstring(file_get_contents($imagePath));
        }

        ob_start();
        imagewebp($image, $webpPath, $this->config->getQualityConfig());

        return ob_get_clean();
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     * @return bool
     */
    private function convertCwebp(string $imagePath, string $webpPath): bool
    {
        $quality = $this->config->getQualityConfig();

        try {
            $process = Process::fromShellCommandline("cwebp ${imagePath} -m 0 -q ${quality} -o ${webpPath}");
            $process->run();

            return $process->isSuccessful();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     * @return bool
     */
    private function convertVips(string $imagePath, string $webpPath): bool
    {
        try {
            $image = Image::newFromBuffer(file_get_contents($imagePath));
            $image->writeToFile($webpPath, ['Q' => $this->config->getQualityConfig()]);
        } catch (VipsException $e) {
            $this->logger->error($e->getMessage());

            return false;
        }

        return true;
    }
}