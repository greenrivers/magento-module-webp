<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Finder\Finder;

class Process
{
    const INCREMENT = 100;

    const MEDIA_PATH = 'pub/media';
    const WEBP_PATH = 'unexpected/webp';

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

    /**
     * Process constructor.
     * @param Filesystem $filesystem
     * @param Finder $finder
     * @param File $file
     * @param Converter $converter
     * @param Image $image
     * @param LoggerInterface $logger
     */
    public function __construct(
        Filesystem $filesystem,
        Finder $finder,
        File $file,
        Converter $converter,
        Image $image,
        LoggerInterface $logger
    ) {
        $this->filesystem = $filesystem;
        $this->finder = $finder;
        $this->file = $file;
        $this->converter = $converter;
        $this->image = $image;
        $this->logger = $logger;
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
     * @return int
     */
    public function convert(array $images, bool $command = false, ProgressBar $progressBar = null): int
    {
        $convertedImages = 0;
        $index = 0;
        $step = $command ? count($images) : self::INCREMENT;

        foreach ($images as $image) {
            if ($index <= $step) {
                $imagePath = $image->getPathname();

                $this->doConvert($imagePath);

                if ($command && $progressBar) {
                    $progressBar->advance();
                }

                $index++;
            } else {
                $convertedImages = $index;
                break;
            }
        }

        if ($index === count($images)) {
            $convertedImages = $index;
        }

        return $convertedImages;
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
}
