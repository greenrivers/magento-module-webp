<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Test\Unit\Helper;

use ArrayObject;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Driver\File;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Greenrivers\Webp\Helper\Converter;
use Greenrivers\Webp\Helper\Image;
use Greenrivers\Webp\Helper\Process;
use Greenrivers\Webp\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\Webp\Test\Unit\Traits\TraitReflectionClass;

class ProcessTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Process */
    private $process;

    /** @var Filesystem|MockObject */
    private $filesystemMock;

    /** @var Finder|MockObject */
    private $finderMock;

    /** @var File|MockObject */
    private $fileMock;

    /** @var Converter|MockObject */
    private $converterMock;

    /** @var Image|MockObject */
    private $imageMock;

    /** @var LoggerInterface|MockObject */
    private $loggerMock;

    protected function setUp(): void
    {
        $this->filesystemMock = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->finderMock = $this->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->fileMock = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->converterMock = $this->getMockBuilder(Converter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->imageMock = $this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $properties = [
            $this->filesystemMock,
            $this->finderMock,
            $this->fileMock,
            $this->converterMock,
            $this->imageMock,
            $this->loggerMock,
            []
        ];
        $this->process = $this->getObjectManager()->getObject(Process::class, $properties);
        $this->setAccessibleProperties($this->process, $properties);
    }

    /**
     * @covers Process::getImages
     */
    public function testGetImages()
    {
        $readMock = $this->getMockBuilder(ReadInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAbsolutePath'])
            ->getMockForAbstractClass();

        $this->filesystemMock->expects(self::once())
            ->method('getDirectoryRead')
            ->with('media')
            ->willReturn($readMock);
        $readMock->expects(self::once())
            ->method('getAbsolutePath')
            ->willReturn('/var/www/magento2/pub/media/');
        $this->finderMock->expects(self::once())
            ->method('ignoreDotFiles')
            ->with(false)
            ->willReturn($this->finderMock);
        $this->finderMock->expects(self::once())
            ->method('files')
            ->willReturn($this->finderMock);
        $this->finderMock->expects(self::once())
            ->method('in')
            ->with(['/var/www/magento2/pub/media/catalog/product', '/var/www/magento2/pub/media/wysiwyg'])
            ->willReturn($this->finderMock);
        $this->finderMock->expects(self::once())
            ->method('name')
            ->with(['*.jpg', '*.jpeg'])
            ->willReturn($this->finderMock);

        $this->assertInstanceOf(
            Finder::class,
            $this->process->getImages(['*.jpg', '*.jpeg'], ['catalog/product', 'wysiwyg'])
        );
    }

    /**
     * @covers Process::getImagesToConversion
     */
    public function testGetImagesToConversion()
    {
        $image1Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $image2Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $finderMock = $this->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $image1Mock->expects(self::exactly(2))
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/catalog/product1.jpg');
        $image2Mock->expects(self::exactly(2))
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/catalog/product2.jpg');
        $finderMock->expects(self::once())
            ->method('getIterator')
            ->willReturn(new ArrayObject([$image1Mock, $image2Mock]));
        $this->imageMock->expects(self::exactly(2))
            ->method('changePath')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/catalog/product1.jpg'],
                ['/var/www/magento2/pub/media/catalog/product2.jpg']
            )
            ->willReturnOnConsecutiveCalls(
                '/var/www/magento2/pub/media/catalog/product1.jpg',
                '/var/www/magento2/pub/media/catalog/product2.jpg'
            );

        $result = $this->process->getImagesToConversion($finderMock);

        $this->assertInstanceOf(SplFileInfo::class, $result[0]);
        $this->assertInstanceOf(SplFileInfo::class, $result[1]);
        $this->assertEquals('/var/www/magento2/pub/media/catalog/product1.jpg', $result[0]->getPathname());
        $this->assertEquals('/var/www/magento2/pub/media/catalog/product2.jpg', $result[1]->getPathname());
        $this->assertCount(2, $result);
    }

    /**
     * @covers Process::clear
     */
    public function testClear()
    {
        $image1Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $image2Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $image3Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $finderMock = $this->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $image1Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/greenrivers/webp/catalog/product1.webp');
        $image2Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/greenrivers/webp/catalog/product2.webp');
        $image3Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/greenrivers/webp/catalog/product3.webp');
        $finderMock->expects(self::once())
            ->method('getIterator')
            ->willReturn(new ArrayObject([$image1Mock, $image2Mock, $image3Mock]));
        $this->fileMock->expects(self::exactly(3))
            ->method('isExists')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product1.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product2.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product3.webp']
            )
            ->willReturn(true);
        $this->fileMock->expects(self::exactly(3))
            ->method('deleteFile')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product1.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product2.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product3.webp']
            )
            ->willReturn(true);

        $result = $this->process->clear($finderMock);

        $this->assertEquals(3, $result);
    }

    /**
     * @covers Process::convert
     */
    public function testConvert()
    {
        $image1Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $image2Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $image3Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();
        $image4Mock = $this->getMockBuilder(SplFileInfo::class)
            ->disableOriginalConstructor()
            ->setMethods(['getPathname'])
            ->getMock();

        $image1Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/captcha/captcha.jpg');
        $image2Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/catalog/product.jpeg');
        $image3Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/wysiwyg/text.png');
        $image4Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/theme/invalid.jpg');
        $this->fileMock->expects(self::exactly(4))
            ->method('getParentDirectory')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/greenrivers/webp/captcha/captcha.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/wysiwyg/text.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/theme/invalid.webp']
            )
            ->willReturnOnConsecutiveCalls(
                '/var/www/magento2/pub/media/greenrivers/webp/captcha',
                '/var/www/magento2/pub/media/greenrivers/webp/catalog',
                '/var/www/magento2/pub/media/greenrivers/webp/wysiwyg',
                '/var/www/magento2/pub/media/greenrivers/webp/theme'
            );
        $this->fileMock->expects(self::exactly(4))
            ->method('isExists')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/greenrivers/webp/captcha/captcha.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog/product.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/wysiwyg/text.webp'],
                ['/var/www/magento2/pub/media/greenrivers/webp/theme/invalid.webp']
            )
            ->willReturn(false);
        $this->fileMock->expects(self::exactly(4))
            ->method('createDirectory')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/greenrivers/webp/captcha'],
                ['/var/www/magento2/pub/media/greenrivers/webp/catalog'],
                ['/var/www/magento2/pub/media/greenrivers/webp/wysiwyg'],
                ['/var/www/magento2/pub/media/greenrivers/webp/theme']
            )
            ->willReturn(true);
        $this->converterMock->expects(self::exactly(4))
            ->method('convert')
            ->withConsecutive(
                [
                    '/var/www/magento2/pub/media/captcha/captcha.jpg',
                    '/var/www/magento2/pub/media/greenrivers/webp/captcha/captcha.webp'
                ],
                [
                    '/var/www/magento2/pub/media/catalog/product.jpeg',
                    '/var/www/magento2/pub/media/greenrivers/webp/catalog/product.webp'
                ],
                [
                    '/var/www/magento2/pub/media/wysiwyg/text.png',
                    '/var/www/magento2/pub/media/greenrivers/webp/wysiwyg/text.webp'
                ],
                [
                    '/var/www/magento2/pub/media/theme/invalid.jpg',
                    '/var/www/magento2/pub/media/greenrivers/webp/theme/invalid.webp'
                ]
            )
            ->willReturn(true, true, true, false);

        $result = $this->process->convert([$image1Mock, $image2Mock, $image3Mock, $image4Mock]);

        $this->assertArrayHasKey('converted_images', $result);
        $this->assertArrayHasKey('error_images', $result);
        $this->assertEquals(3, $result['converted_images']);
        $this->assertEquals(1, $result['error_images']);
    }
}
