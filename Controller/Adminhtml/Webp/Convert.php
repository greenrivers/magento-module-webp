<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Controller\Adminhtml\Webp;

use Jcupitt\Vips\Image;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\Adapter\ImageMagick;
use Magento\Framework\View\Result\PageFactory;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Unexpected\Webp\Helper\Config;

class Convert extends Action
{
    /** @var PageFactory */
    private $resultPageFactory;

    /** @var JsonFactory */
    private $resultJsonFactory;

    /** @var Filesystem */
    private $filesystem;

    /** @var Finder */
    private $finder;

    /** @var Filesystem\Io\File */
    private $file;

    /** @var DirectoryList */
    private $directoryList;

    /** @var Config */
    private $config;

    /**
     * Convert constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param Filesystem $filesystem
     * @param Finder $finder
     * @param Filesystem\Io\File $file
     * @param DirectoryList $directoryList
     * @param Config $config
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Filesystem $filesystem,
        Finder $finder,
        Filesystem\Io\File $file,
        DirectoryList $directoryList,
        Config $config
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->filesystem = $filesystem;
        $this->finder = $finder;
        $this->file = $file;
        $this->directoryList = $directoryList;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $resultPage = $this->resultPageFactory->create();

        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();

        $images = $this->finder->ignoreDotFiles(false)->files()->in($mediaPath)->name(['*.jpg', '*.jpeg', '*.png']);
        $i = 0;

        foreach ($images as $image) {
            $extension = $image->getExtension();
            $webpImage = substr_replace($image->getFilename(), 'webp', strrpos($image->getFilename(), '.') + 1);
            $webpDir = $mediaPath . 'unexpected' . '/webp/' . $image->getRelativePath();
            $webpPath = $webpDir . '/' . $webpImage;
            if (!file_exists($webpDir)) {
                $this->file->mkdir($webpDir);
            }
            switch ($this->config->getTypeConfig()) {
                case 'webp':
                    $this->convertWebp($image, $webpPath);
                    break;
                case 'cwebp':
                    $this->convertCwebp($image, $webpPath);
                    break;
                case 'vips':
                    $this->convertVips($image, $webpPath);
                    break;
            }
            $i++;
            if ($i === 100) {
                break;
            }
        }

        $result->setData(['output' => $mediaPath]);

        return $result;
    }

    private function convertWebp(SplFileInfo $img, string $webpPath)
    {
//        $image = imagecreatefromstring($img->getPathname());
//        ob_start();
//        imagejpeg($image, NULL, 100);
//        $cont = ob_get_contents();
//        ob_end_clean();
//        imagedestroy($image);
//        $content = imagecreatefromstring($cont);
//        imagewebp($content, $webpPath);
//        imagedestroy($content);

//        imagepalettetotruecolor($img);
//        imagealphablending($img, true);
//        imagesavealpha($img, true);
//        imagewebp($img, $dir . $newName, 100);
//        imagedestroy($img);

        $image = $img->getPathname();
        $image = imagecreatefromstring(file_get_contents($image));
        ob_start();
        imagewebp($image, $webpPath, 100);
        $image = ob_get_clean();
    }

    private function convertCwebp(SplFileInfo $img, string $webpPath)
    {
        $result = false;

        $image = $img->getPathname();
        $image = imagecreatefromstring(file_get_contents($image));

        $cmd = sprintf('cwebp %s -m 0 -q 75 -o %s', $image, $webpPath);
        $descriptorSpec = [
            0 => ['pipe', 'r'], // stdin is a pipe that the child will read from
            1 => ['pipe', 'w'], // stdout is a pipe that the child will write to
            2 => ['pipe', 'w'], // stderr is a pipe that the child will write to
        ];
        $process = proc_open($cmd, $descriptorSpec, $pipes);

        if (is_resource($process)) {
            fwrite($pipes[0], $image);
            fclose($pipes[0]);

            stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $result = proc_close($process);
        }

        return $result;
    }

    private function convertVips(SplFileInfo $img, string $webpPath)
    {
        $image = $img->getPathname();
        $image = Image::newFromBuffer(file_get_contents($image));
//        $image = Image::thumbnail(file_get_contents($image), 1920);
        $image->writeToFile($webpPath);
    }
}