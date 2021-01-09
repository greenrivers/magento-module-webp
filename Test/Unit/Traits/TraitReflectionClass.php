<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_Webp
 */

namespace Greenrivers\Webp\Test\Unit\Traits;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

trait TraitReflectionClass
{
    /**
     * @param object $object
     * @param array $properties
     * @throws ReflectionException
     */
    private function setAccessibleProperties(object $object, array $properties): void
    {
        $refClass = new ReflectionClass(get_class($object));
        $refProperties = $refClass->getProperties(ReflectionProperty::IS_PRIVATE);
        foreach ($refProperties as $key => $refProperty) {
            $refProperty->setAccessible(true);
            $refProperty->setValue($object, $properties[$key]);
        }
    }
}
