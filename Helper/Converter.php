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
use Unexpected\Webp\Model\Config\Source\Type;

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

    public function convert(string $imagePath, string $webpPath)
    {
        switch ($this->config->getTypeConfig()) {
            case Type::WEBP_TYPE:
                $this->convertWebp($imagePath, $webpPath);
                break;
            case Type::CWEBP_TYPE:
                $this->convertCwebp($imagePath, $webpPath);
                break;
            case Type::VIPS_TYPE:
                $this->convertVips($imagePath, $webpPath);
                break;
        }
    }

    private function convertWebp(string $imagePath, string $webpPath)
    {
        $image = imagecreatefromstring(file_get_contents($imagePath));
        ob_start();
        imagewebp($image, $webpPath, $this->config->getQualityConfig());
        $image = ob_get_clean();
    }

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

    private function convertVips(string $imagePath, string $webpPath)
    {
        $image = Image::newFromBuffer(file_get_contents($imagePath));
        $image->writeToFile($webpPath, ['Q' => $this->config->getQualityConfig()]);
    }
}