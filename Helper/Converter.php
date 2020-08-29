<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Helper;

use Jcupitt\Vips\Image;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Unexpected\Webp\Model\Config\Source\Algorithm;

class Converter
{
    /** @var Config */
    private $config;

    /**
     * Converter constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     */
    public function convert(string $imagePath, string $webpPath): void
    {
        switch ($this->config->getAlgorithmConfig()) {
            case Algorithm::WEBP_ALGORITHM:
                $this->convertWebp($imagePath, $webpPath);
                break;
            case Algorithm::CWEBP_ALGORITHM:
                $this->convertCwebp($imagePath, $webpPath);
                break;
            case Algorithm::VIPS_ALGORITHM:
                $this->convertVips($imagePath, $webpPath);
                break;
        }
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     */
    private function convertWebp(string $imagePath, string $webpPath)
    {
        $image = imagecreatefromstring(file_get_contents($imagePath));
        ob_start();
        imagewebp($image, $webpPath, $this->config->getQualityConfig());
        $image = ob_get_clean();
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     * @return string
     */
    private function convertCwebp(string $imagePath, string $webpPath)
    {
        $quality = $this->config->getQualityConfig();
        $process = Process::fromShellCommandline("cwebp ${imagePath} -m 0 -q ${quality} -o ${webpPath}");
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        return $process->getOutput();
    }

    /**
     * @param string $imagePath
     * @param string $webpPath
     * @throws \Jcupitt\Vips\Exception
     */
    private function convertVips(string $imagePath, string $webpPath)
    {
        $image = Image::newFromBuffer(file_get_contents($imagePath));
        $image->writeToFile($webpPath, ['Q' => $this->config->getQualityConfig()]);
    }
}