<?php

namespace Aztech\Util\DotNotation;

/**
 *
 * @todo Turn recursive into iterative
 * @author thibaud
 */
class DotNotationResolver
{

    public static function resolve($value, $name, $default = null)
    {
        if (! DotNotationParser::hasDot($name)) {
            return self::getDirectProperty($value, $name, $default);
        }

        $elements = DotNotationParser::getComponents($name, 2);
        $firstLevelObject = self::resolve($value, $elements[0]);

        return self::resolve($firstLevelObject, $elements[1]);
    }

    public static function propertyOrIndexExists($value, $name)
    {
        if (! DotNotationParser::hasDot($name)) {
            return self::checkDirectProperty($value, $name);
        }

        $elements = DotNotationParser::getComponents($name, 2);
        $firstLevelObject = self::resolve($value, $elements[0]);

        return self::propertyOrIndexExists($firstLevelObject, $elements[1]);
    }

    private static function checkDirectProperty($value, $name)
    {
        if (is_object($value)) {
            return isset($value->{$name});
        } elseif (is_array($value) || $value instanceof \ArrayAccess) {
            return isset($value[$name]);
        }
    }

    private static function getDirectProperty($value, $name, $default)
    {
        if (is_object($value)) {
            return self::getObjectProperty($value, $name, $default);
        } elseif (is_array($value)) {
            return self::getArrayProperty($value, $name, $default);
        }

        return $default;
    }

    private static function getObjectProperty($value, $name, $default)
    {
        if (isset($value->{$name})) {
            return $value->{$name};
        }

        return $default;
    }

    private static function getArrayProperty($value, $name, $default)
    {
        if (array_key_exists($name, $value)) {
            return $value[$name];
        }

        return $default;
    }
}
