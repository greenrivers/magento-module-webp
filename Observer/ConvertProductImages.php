<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Observer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Filesystem;
use Symfony\Component\Finder\Finder;
use Greenrivers\Webp\Helper\Config;
use Greenrivers\Webp\Helper\Process;

class ConvertProductImages implements ObserverInterface
{
    const CATALOG_PRODUCT_CACHE_DIR = 'catalog/product/cache';

    /** @var Config */
    private $config;

    /** @var Process */
    private $process;

    /** @var Finder */
    private $finder;

    /** @var Filesystem */
    private $filesystem;

    /**
     * ConvertImages constructor.
     * @param Config $config
     * @param Process $process
     * @param Finder $finder
     * @param Filesystem $filesystem
     */
    public function __construct(Config $config, Process $process, Finder $finder, Filesystem $filesystem)
    {
        $this->config = $config;
        $this->process = $process;
        $this->finder = $finder;
        $this->filesystem = $filesystem;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        if($this->config->getEnabledConfig() && $this->config->getConvertProductImagesConfig()) {
            /** @var ProductInterface $product */
            $product = $observer->getProduct();
            $productImages = $product->getMediaGalleryImages();
            $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
            $mediaCachePath = $mediaPath . self::CATALOG_PRODUCT_CACHE_DIR;

            foreach ($productImages as $productImage) {
                $imagePath = $productImage->getPath();

                $this->process->doConvert($imagePath);

                $file = $productImage->getFile();
                $image = substr(strrchr($file, DIRECTORY_SEPARATOR), 1);
                $this->finder = Finder::create();
                $cachedImages = $this->finder
                    ->ignoreDotFiles(false)
                    ->files()
                    ->in($mediaCachePath)
                    ->name($image);

                foreach ($cachedImages as $cachedImage) {
                    $imagePath = $cachedImage->getPathname();

                    $this->process->doConvert($imagePath);
                }
            }
        }
    }
}
