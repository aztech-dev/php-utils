<?php

namespace Aztech\Util\DotNotation;

class DotNotationResolver
{
    
    public static function resolve($value, $name)
    {
        if (! DotNotationParser::hasDot($name)) {
            if (is_object($value)) {
                return $value->{$name};
            }
            elseif (is_array($value)) {
                return $value[$name];
            }
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