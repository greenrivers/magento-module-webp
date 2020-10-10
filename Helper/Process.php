<?php
/**
 * @author GreenRivers Team
 * @copyright Copyright (c) 2020 GreenRivers
 * @package GreenRivers_Webp
 */

namespace GreenRivers\Webp\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Finder\Finder;

class Process
{
    const INCREMENT = 1000;

    const MEDIA_PATH = 'pub/media';
    const WEBP_PATH = 'greenrivers/webp';

    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /** @var File */
    private $file;

    /** @var Converter */
    private $converter;

    /** @var Image */
    private $image;

    /** @var LoggerInterface */
    private $logger;

    /** @var array */
    private $errorImages;

    /**
     * Process constructor.
     * @param Filesystem $filesystem
     * @param Finder $finder
     * @param File $file
     * @param Converter $converter
     * @param Image $image
     * @param LoggerInterface $logger
     * @param array $errorImages
     */
    public function __construct(
        Filesystem $filesystem,
        Finder $finder,
        File $file,
        Converter $converter,
        Image $image,
        LoggerInterface $logger,
        array $errorImages = []
    ) {
        $this->filesystem = $filesystem;
        $this->finder = $finder;
        $this->file = $file;
        $this->converter = $converter;
        $this->image = $image;
        $this->logger = $logger;
        $this->errorImages = $errorImages;
    }

    /**
     * @param array $extensions
     * @param array $folders
     * @return Finder
     */
    public function getImages(array $extensions = ['*.webp'], array $folders = []): Finder
    {
        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();

        if (empty($folders)) {
            $folders = $mediaPath . self::WEBP_PATH . DIRECTORY_SEPARATOR;
        } else if ($folders[0] === 'root') {
            $folders = $mediaPath;
        } else {
            $folders = array_filter($folders, function($folder) {
                return $folder;
            });
            array_walk($folders, function (&$folder) use ($mediaPath) {
                $folder = $mediaPath . $folder;
            });
        }

        return $this->finder
            ->ignoreDotFiles(false)
            ->files()
            ->in($folders)
            ->name($extensions);
    }

    /**
     * @param Finder $images
     * @return array
     */
    public function getImagesToConversion(Finder $images): array
    {
        $imagesToConversion = [];

        foreach ($images as $image) {
            $imagePath = $image->getPathname();
            $webpPath = $this->image->changePath($imagePath);

            if ($imagePath === $webpPath) {
                $imagesToConversion[] = $image;
            }
        }

        return $imagesToConversion;
    }

    /**
     * @param Finder $images
     * @param bool $command
     * @param ProgressBar|null $progressBar
     * @return int
     */
    public function clear(Finder $images, bool $command = false, ProgressBar $progressBar = null): int
    {
        $removedImages = 0;
        $step = $command ? $images->count() : self::INCREMENT;

        foreach ($images as $image) {
            if ($removedImages < $step) {
                $imagePath = $image->getPathname();

                try {
                    if ($this->file->isExists($imagePath)) {
                        $this->file->deleteFile($imagePath);
                    }
                } catch (FileSystemException $e) {
                    $this->logger->error($e->getMessage());
                }

                if ($command) {
                    $progressBar->advance();
                }

                $removedImages++;
            } else {
                break;
            }
        }

        return $removedImages;
    }

    /**
     * @param array $images
     * @param bool $command
     * @param ProgressBar|null $progressBar
     * @return array
     */
    public function convert(array $images, bool $command = false, ProgressBar $progressBar = null): array
    {
        $convertedImages = 0;
        $index = 0;
        $step = $command ? count($images) : self::INCREMENT;

        foreach ($images as $image) {
            if ($index <= $step) {
                $imagePath = $image->getPathname();

                $result = $this->doConvert($imagePath);

                if ($result) {
                    $convertedImages++;

                    if ($command && $progressBar) {
                        $progressBar->advance();
                    }
                } else {
                    $errorImages = $this->getErrorImages();

                    if (!array_key_exists($imagePath, $errorImages)) {
                        $errorImages[] = $imagePath;
                        $this->setErrorImages($errorImages);

                        $this->logger->error("Invalid image with path: ${imagePath}");
                    }
                }

                $index++;
            } else {
                break;
            }
        }

        return ['converted_images' => $convertedImages, 'error_images' => count($this->getErrorImages())];
    }


    /**
     * @param string $imagePath
     * @return bool
     */
    public function doConvert(string $imagePath): bool
    {
        $webpImage = substr_replace(
            $imagePath,
            'webp',
            strrpos($imagePath, '.') + 1
        );
        $webpPath = str_replace(
            self::MEDIA_PATH,
            self::MEDIA_PATH . DIRECTORY_SEPARATOR . self::WEBP_PATH,
            $webpImage
        );
        $webpDir = $this->file->getParentDirectory($webpPath);

        try {
            if (!$this->file->isExists($webpPath)) {
                $this->file->createDirectory($webpDir);
            }
        } catch (FileSystemException $e) {
            $this->logger->error($e->getMessage());
        }

        return $this->converter->convert($imagePath, $webpPath);
    }

    /**
     * @return array
     */
    public function getErrorImages(): array
    {
        return $this->errorImages;
    }

    /**
     * @param array $errorImages
     */
    public function setErrorImages(array $errorImages): void
    {
        $this->errorImages = $errorImages;
    }
}
