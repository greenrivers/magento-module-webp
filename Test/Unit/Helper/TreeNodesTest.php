<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Test\Unit\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Log\LoggerInterface;
use Greenrivers\Webp\Helper\TreeNodes;
use Greenrivers\Webp\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\Webp\Test\Unit\Traits\TraitReflectionClass;

class TreeNodesTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var TreeNodes */
    private $treeNodes;

    /** @var DirectoryList|PHPUnit_Framework_MockObject_MockObject */
    private $directoryListMock;

    /** @var File|PHPUnit_Framework_MockObject_MockObject */
    private $driverFileMock;

    /** @var LoggerInterface|PHPUnit_Framework_MockObject_MockObject */
    private $loggerMock;

    protected function setUp()
    {
        $this->directoryListMock = $this->getMockBuilder(DirectoryList::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->driverFileMock = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $properties = [
            $this->directoryListMock,
            $this->driverFileMock,
            $this->loggerMock
        ];
        $this->treeNodes = $this->getObjectManager()->getObject(TreeNodes::class, $properties);
        $this->setAccessibleProperties($this->treeNodes, $properties);
    }

    /**
     * @covers TreeNodes::getDirectories
     */
    public function testGetDirectories()
    {
        $this->directoryListMock->expects(self::once())
            ->method('getPath')
            ->with('media')
            ->willReturn('/var/www/magento2/pub/media');
        $this->driverFileMock->expects(self::once())
            ->method('readDirectory')
            ->with('/var/www/magento2/pub/media/captcha')
            ->willReturn(['/var/www/magento2/pub/media/captcha/admin', '/var/www/magento2/pub/media/captcha/base']);
        $this->driverFileMock->expects(self::exactly(2))
            ->method('isDirectory')
            ->withConsecutive(
                ['/var/www/magento2/pub/media/captcha/admin'],
                ['/var/www/magento2/pub/media/captcha/base']
            )
            ->willReturn(true);

        $this->assertEquals(
            [
                [
                    'text' => 'admin',
                    'id' => 'captcha/admin',
                    'path' => '/var/www/magento2/pub/media/captcha/admin',
                    'cls' => 'folder'
                ],
                [
                    'text' => 'base',
                    'id' => 'captcha/base',
                    'path' => '/var/www/magento2/pub/media/captcha/base',
                    'cls' => 'folder'
                ]
            ],
            $this->treeNodes->getDirectories('captcha')
        );
    }
}
