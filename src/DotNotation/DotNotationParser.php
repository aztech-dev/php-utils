<?php

namespace Aztech\Util\DotNotation;

class DotNotationParser
{
    
    public static function hasDot($name) {
        return strpos($name, '.') !== false;  
    }
    
    /**
     * @param integer $limit
     */
    public static function getComponents($name, $limit = null) {
        if (self::hasDot($name)) {
            return explode('.', $name, $limit);
        }
        
        return array($name);
    }
}