<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Test\Unit\Helper;

use ArrayObject;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Driver\File;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Unexpected\Webp\Helper\Converter;
use Unexpected\Webp\Helper\Image;
use Unexpected\Webp\Helper\Process;
use Unexpected\Webp\Test\Unit\Traits\TraitObjectManager;
use Unexpected\Webp\Test\Unit\Traits\TraitReflectionClass;

class ProcessTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var Process */
    private $process;

    /** @var Filesystem|PHPUnit_Framework_MockObject_MockObject */
    private $filesystemMock;

    /** @var Finder|PHPUnit_Framework_MockObject_MockObject */
    private $finderMock;

    /** @var File|PHPUnit_Framework_MockObject_MockObject */
    private $fileMock;

    /** @var Converter|PHPUnit_Framework_MockObject_MockObject */
    private $converterMock;

    /** @var Image|PHPUnit_Framework_MockObject_MockObject */
    private $imageMock;

    /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $loggerMock;

    protected function setUp()
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
            $this->loggerMock
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
        $this->assertInternalType(IsType::TYPE_ARRAY, $result);
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
            ->willReturn('/var/www/magento2/pub/media/unexpected/webp/catalog/product1.webp');
        $image2Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/unexpected/webp/catalog/product2.webp');
        $image3Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/unexpected/webp/catalog/product3.webp');
        $finderMock->expects(self::once())
            ->method('getIterator')
            ->willReturn(new ArrayObject([$image1Mock, $image2Mock, $image3Mock]));
        $this->fileMock->expects(self::exactly(3))
            ->method('isExists')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product1.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product2.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product3.webp']
            )
            ->willReturn(true);
        $this->fileMock->expects(self::exactly(3))
            ->method('deleteFile')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product1.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product2.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product3.webp']
            )
            ->willReturn(true);

        $result = $this->process->clear($finderMock);

        $this->assertEquals(3, $result);
        $this->assertInternalType(IsType::TYPE_INT, $result);
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

        $image1Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/captcha/captcha.jpg');
        $image2Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/catalog/product.jpeg');
        $image3Mock->expects(self::once())
            ->method('getPathname')
            ->willReturn('/var/www/magento2/pub/media/wysiwyg/text.png');
        $this->fileMock->expects(self::exactly(3))
            ->method('getParentDirectory')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/unexpected/webp/captcha/captcha.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/wysiwyg/text.webp']
            )
            ->willReturnOnConsecutiveCalls(
                '/var/www/magento2/pub/media/unexpected/webp/captcha',
                '/var/www/magento2/pub/media/unexpected/webp/catalog',
                '/var/www/magento2/pub/media/unexpected/webp/wysiwyg'
            );
        $this->fileMock->expects(self::exactly(3))
            ->method('isExists')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/unexpected/webp/captcha/captcha.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/catalog/product.webp'],
                ['/var/www/magento2/pub/media/unexpected/webp/wysiwyg/text.webp']
            )
            ->willReturn(false);
        $this->fileMock->expects(self::exactly(3))
            ->method('createDirectory')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/unexpected/webp/captcha'],
                ['/var/www/magento2/pub/media/unexpected/webp/catalog'],
                ['/var/www/magento2/pub/media/unexpected/webp/wysiwyg']
            )
            ->willReturn(true);
        $this->converterMock->expects(self::exactly(3))
            ->method('convert')
            ->withConsecutive(
                [
                    '/var/www/magento2/pub/media/captcha/captcha.jpg',
                    '/var/www/magento2/pub/media/unexpected/webp/captcha/captcha.webp'
                ],
                [
                    '/var/www/magento2/pub/media/catalog/product.jpeg',
                    '/var/www/magento2/pub/media/unexpected/webp/catalog/product.webp'
                ],
                [
                    '/var/www/magento2/pub/media/wysiwyg/text.png',
                    '/var/www/magento2/pub/media/unexpected/webp/wysiwyg/text.webp'
                ]
            )
            ->willReturn(true);

        $result = $this->process->convert([$image1Mock, $image2Mock, $image3Mock]);

        $this->assertEquals(3, $result);
        $this->assertInternalType(IsType::TYPE_INT, $result);
    }
}
