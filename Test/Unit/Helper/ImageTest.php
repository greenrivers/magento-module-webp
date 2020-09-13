<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Test\Unit\Helper;

use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Driver\File;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;
use Unexpected\Webp\Helper\Config;
use Unexpected\Webp\Helper\Image;
use Unexpected\Webp\Test\Unit\Traits\TraitObjectManager;
use Unexpected\Webp\Test\Unit\Traits\TraitReflectionClass;

class ImageTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Image */
    private $image;

    /** @var Config|PHPUnit_Framework_MockObject_MockObject */
    private $configMock;

    /** @var File|PHPUnit_Framework_MockObject_MockObject */
    private $fileMock;

    /** @var Filesystem|PHPUnit_Framework_MockObject_MockObject */
    private $filesystemMock;

    /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $loggerMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->fileMock = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $properties = [
            $this->configMock,
            $this->fileMock,
            $this->filesystemMock,
            $this->loggerMock
        ];
        $this->image = $this->getObjectManager()->getObject(Image::class, $properties);
        $this->setAccessibleProperties($this->image, $properties);
    }

    /**
     * @covers Image::changePath
     */
    public function testChangePath()
    {
        $webpDirPath = '/var/www/magento2/pub/media/unexpected/webp/catalog/product/image.webp';

        $readMock = $this->getMockBuilder(ReadInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAbsolutePath'])
            ->getMockForAbstractClass();

        $this->configMock->expects(self::once())
            ->method('getEnabledConfig')
            ->willReturn(true);
        $this->filesystemMock->expects(self::once())
            ->method('getDirectoryRead')
            ->with('pub')
            ->willReturn($readMock);
        $readMock->expects(self::once())
            ->method('getAbsolutePath')
            ->willReturn('/var/www/magento2/pub/');
        $this->fileMock->expects(self::once())
            ->method('isExists')
            ->with($webpDirPath)
            ->willReturn(true);

        $this->assertEquals(
            $webpDirPath,
            $this->image->changePath('/var/www/magento2/pub/media/catalog/product/image.jpg')
        );
    }
}
