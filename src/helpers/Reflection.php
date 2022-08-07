<?php
/**
 * Closure for Craft CMS
 *
 * Allows you to use arrow function closures in Twig
 *
 * @link      https://nystudio107.com
 * @copyright Copyright (c) 2022 nystudio107
 */

namespace nystudio107\closure\helpers;

use ReflectionException;
use ReflectionObject;
use ReflectionProperty;

/**
 * @author    nystudio107
 * @package   Closure
 * @since     1.0.0
 */
class Reflection
{
    // Public static Methods
    // =========================================================================
    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    public static function getReflectionProperty(object $object, string $propertyName): ReflectionProperty
    {
        $reflectionObject = new ReflectionObject($object);

        $reflectionProperty = null;

        if ($reflectionObject->hasProperty($propertyName)) {
            $reflectionProperty = $reflectionObject->getProperty($propertyName);
        } else {

            // This is needed for private parent properties only.
            $parent = $reflectionObject->getParentClass();
            while ($reflectionProperty === null && $parent !== false) {
                if ($parent->hasProperty($propertyName)) {
                    $reflectionProperty = $parent->getProperty($propertyName);
                }

                $parent = $parent->getParentClass();
            }
        }

        if (!$reflectionProperty) {
            throw new ReflectionException("Property not found: " . $propertyName);
        }

        return $reflectionProperty;
    }
}
