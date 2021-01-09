<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Test\Unit\Block\Adminhtml\System\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use PHPUnit\Framework\Constraint\IsType;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Greenrivers\Webp\Block\Adminhtml\System\Config\ToggleSwitch;
use Greenrivers\Webp\Helper\Config;
use Greenrivers\Webp\Test\Unit\Traits\TraitObjectManager;
use Greenrivers\Webp\Test\Unit\Traits\TraitReflectionClass;

class ToggleSwitchTest extends TestCase
{
    use TraitObjectManager;
    use TraitReflectionClass;

    /** @var ToggleSwitch */
    private $toggleSwitch;

    /** @var Config|PHPUnit_Framework_MockObject_MockObject */
    private $configMock;

    /** @var AbstractElement|PHPUnit_Framework_MockObject_MockObject */
    private $elementMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->elementMock = $this->getMockBuilder(AbstractElement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class);

        $properties = [$this->configMock, $this->elementMock];
        $this->toggleSwitch = $this->getObjectManager()->getObject(ToggleSwitch::class, $properties);
        $this->setAccessibleProperties($this->toggleSwitch, $properties);
    }

    /**
     * @covers ToggleSwitch::getComponent()
     */
    public function testGetComponent()
    {
        $this->elementMock->expects(self::exactly(4))
            ->method('getHtmlId')
            ->willReturn('webp_conversion_convert_product_images');
        $this->elementMock->expects(self::exactly(4))
            ->method('getName')
            ->willReturn('groups[conversion][fields][convert_product_images][value]');
        $this->configMock->expects(self::exactly(4))
            ->method('getConvertProductImagesConfig')
            ->willReturn(true);

        $this->toggleSwitch->setElement($this->elementMock);

        $this->assertEquals(
            [
                'id' => 'webp-conversion-convert-product-images',
                'name' => 'groups[conversion][fields][convert_product_images][value]',
                'value' => true
            ],
            $this->toggleSwitch->getComponent()
        );
        $this->assertCount(3, $this->toggleSwitch->getComponent());
        $this->assertArrayHasKey('value', $this->toggleSwitch->getComponent());
        $this->assertInternalType(IsType::TYPE_ARRAY, $this->toggleSwitch->getComponent());
    }
}
