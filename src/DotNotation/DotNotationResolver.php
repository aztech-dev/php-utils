<?php

namespace Aztech\Util\DotNotation;

/**
 * @todo Turn recursive into iterative
 * @author thibaud
 *
 */
class DotNotationResolver
{
    
    public static function resolve($value, $name, $default = null)
    {
        if (! DotNotationParser::hasDot($name)) {
            if (is_object($value) && isset($value->{$name})) {
                return $value->{$name};
            }
            elseif (is_array($value) && array_key_exists($name, $value)) {
                return $value[$name];
            }
            
            return $default;
        }
        
        $elements = DotNotationParser::getComponents($name, 2);
        $firstLevelObject = self::resolve($value, $elements[0]);
        
        return self::resolve($firstLevelObject, $elements[1]);
    }
    
    public static function propertyOrIndexExists($value, $name) {
        if (! DotNotationParser::hasDot($name)) {
            if (is_object($value)) {
                return isset($value->{$name});
            }
            elseif (is_array($value) || $value instanceof \ArrayAccess) {
                return isset($value[$name]);
            }
        }
        
        $elements = DotNotationParser::getComponents($name, 2);
        $firstLevelObject = self::resolve($value, $elements[0]);
        
        return self::propertyOrIndexExists($firstLevelObject, $elements[1]);
    }
    
}