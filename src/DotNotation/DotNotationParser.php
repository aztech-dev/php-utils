<?php

namespace Aztech\Util\DotNotation;

class DotNotationParser
{

    public function __call($method, $args)
    {
        return call_user_func_array(array(__CLASS__, $method), $args);
    }

    public static function hasDot($name) {
        return strpos($name, '.', 1) !== false;
    }

    /**
     * @param integer $limit
     */
    public static function getComponents($name, $limit = null) {
        if (! self::hasDot($name)) {
            return array($name);
        }

        $components = ($limit == null) ? explode('.', $name) : explode('.', $name, $limit);

        return $components;
    }
}
