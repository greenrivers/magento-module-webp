<?php
/**
 * @author Unexpected Team
 * @copyright Copyright (c) 2020 Unexpected
 * @package Unexpected_Webp
 */

namespace Unexpected\Webp\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Unexpected\Webp\Helper\Config;
use Unexpected\Webp\Test\Unit\Traits\TraitObjectManager;

class ConfigTest extends TestCase
{
    use TraitObjectManager;

    /** @var Config */
    private $config;

    /** @var ScopeConfigInterface|PHPUnit_Framework_MockObject_MockObject */
    private $scopeConfigMock;

    protected function setUp()
    {
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->config = $this->getObjectManager()->getObject(
            Config::class,
            ['scopeConfig' => $this->scopeConfigMock]
        );
    }

    /**
     * @covers Config::getEnabledConfig
     */
    public function testGetEnabledConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('webp/general/enabled', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getEnabledConfig());
    }

    /**
     * @covers Config::getAlgorithmConfig
     */
    public function testGetAlgorithmConfig()
    {
        $value = 'webp';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('webp/settings/algorithm', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getAlgorithmConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getAlgorithmConfig());
    }

    /**
     * @covers Config::getQualityConfig
     */
    public function testGetQualityConfig()
    {
        $value = 75;
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('webp/settings/quality', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getQualityConfig());
        $this->assertInternalType(IsType::TYPE_INT, $this->config->getQualityConfig());
    }

    /**
     * @covers Config::getConvertProductImagesConfig
     */
    public function testGetConvertProductImagesConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('webp/conversion/convert_product_images', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getConvertProductImagesConfig());
    }

    /**
     * @covers Config::getConversionFoldersConfig
     */
    public function testGetConversionFoldersConfig()
    {
        $value = 'catalog,product,customer';
        $this->scopeConfigMock->expects(self::exactly(3))
            ->method('getValue')
            ->with('webp/conversion/folders', 'store')
            ->willReturn($value);

        $this->assertEquals(['catalog', 'product', 'customer'], $this->config->getConversionFoldersConfig());
        $this->assertCount(3, $this->config->getConversionFoldersConfig());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->config->getConversionFoldersConfig());
    }

    /**
     * @covers Config::getConversionImageFormatsConfig
     */
    public function testGetConversionImageFormatsConfig()
    {
        $value = '*.jpg,*.jpeg,*.png';
        $this->scopeConfigMock->expects(self::exactly(3))
            ->method('getValue')
            ->with('webp/conversion/image_formats', 'store')
            ->willReturn($value);

        $this->assertEquals(['*.jpg', '*.jpeg', '*.png'], $this->config->getConversionImageFormatsConfig());
        $this->assertCount(3, $this->config->getConversionImageFormatsConfig());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->config->getConversionImageFormatsConfig());
    }

    /**
     * @covers Config::getCronFoldersConfig
     */
    public function testGetCronFoldersConfig()
    {
        $value = 'wysiwyg,thumbnail';
        $this->scopeConfigMock->expects(self::exactly(3))
            ->method('getValue')
            ->with('webp/cron/folders', 'store')
            ->willReturn($value);

        $this->assertEquals(['wysiwyg', 'thumbnail'], $this->config->getCronFoldersConfig());
        $this->assertCount(2, $this->config->getCronFoldersConfig());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->config->getCronFoldersConfig());
    }

    /**
     * @covers Config::getCronEnabledConfig
     */
    public function testGetCronEnabledConfig()
    {
        $this->scopeConfigMock->expects(self::once())
            ->method('getValue')
            ->with('webp/cron/enabled', 'store')
            ->willReturn(true);

        $this->assertTrue($this->config->getCronEnabledConfig());
    }

    /**
     * @covers Config::getCronFrequencyConfig
     */
    public function testGetCronFrequencyConfig()
    {
        $value = 'W';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('webp/cron/frequency', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getCronFrequencyConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronFrequencyConfig());
    }

    /**
     * @covers Config::getCronTimeConfig
     */
    public function testGetCronTimeConfig()
    {
        $value = '12,24,36';
        $this->scopeConfigMock->expects(self::exactly(2))
            ->method('getValue')
            ->with('webp/cron/time', 'store')
            ->willReturn($value);

        $this->assertEquals($value, $this->config->getCronTimeConfig());
        $this->assertInternalType(IsType::TYPE_STRING, $this->config->getCronTimeConfig());
    }

    /**
     * @covers Config::getCronImageFormatsConfig
     */
    public function testGetCronImageFormatsConfig()
    {
        $value = '*.jpg,*.png';
        $this->scopeConfigMock->expects(self::exactly(3))
            ->method('getValue')
            ->with('webp/cron/image_formats', 'store')
            ->willReturn($value);

        $this->assertEquals(['*.jpg', '*.png'], $this->config->getCronImageFormatsConfig());
        $this->assertCount(2, $this->config->getCronImageFormatsConfig());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->config->getCronImageFormatsConfig());
    }
}
