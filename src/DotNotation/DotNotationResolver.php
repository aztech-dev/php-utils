<?php

namespace Aztech\Util\DotNotation;

/**
 * @deprecated Warning, all static methods are deprecated. Use instance methods instance. Deprecation and static methods
 * will be removed in next major release.
 * @todo Turn recursive into iterative
 * @author thibaud
 * @method mixed resolve(mixed $value, string $name, mixed $default = null)
 * @method bool propertyOrIndexExists(mixed $value, string $name)
 */
class DotNotationResolver
{

    private static $instance = null;

    private $parser = null;

    public function __construct(DotNotationParser $parser = null)
    {
        if ($parser === null) {
            $parser = new DotNotationParser();
        }

        $this->parser = $parser;
    }

    public static function __callStatic($method, $args)
    {
        if (self::$instance == null) {
            self::$instance = new self(new DotNotationParser());
        }

        return call_user_func_array(array(self::$instance, $method), $args);
    }

    public function __call($method, $args)
    {
        $prefix = 'public';

        if (method_exists($this, $prefix . $method)) {
            return call_user_func_array(array($this, $prefix . $method), $args);
        }
    }

    private function publicResolve($value, $name, $default = null)
    {
        if (! $this->parser->hasDot($name)) {
            return $this->getDirectProperty($value, $name, $default);
        }

        $elements = $this->parser->getComponents($name, 2);
        $firstLevelObject = $this->publicResolve($value, $elements[0], $default);

        return $this->publicResolve($firstLevelObject, $elements[1], $default);
    }

    private function publicPropertyOrIndexExists($value, $name)
    {
        if (! $this->parser->hasDot($name)) {
            return $this->checkDirectProperty($value, $name);
        }

        $elements = $this->parser->getComponents($name, 2);
        $firstLevelObject = $this->publicResolve($value, $elements[0], null);

        return $this->publicPropertyOrIndexExists($firstLevelObject, $elements[1]);
    }

    private function checkDirectProperty($value, $name)
    {
        if (is_object($value)) {
            return isset($value->{$name});
        } elseif ($this->hasOffsetAccessor($value)) {
            return isset($value[$name]);
        }

        return false;
    }

    private function hasOffsetAccessor($value)
    {
        return (is_array($value) || $value instanceof \ArrayAccess);
    }

    private function getDirectProperty($value, $name, $default)
    {
        if (is_object($value)) {
            return $this->getObjectProperty($value, $name, $default);
        } elseif (is_array($value)) {
            return $this->getArrayProperty($value, $name, $default);
        }

        return $default;
    }

    private function getObjectProperty($value, $name, $default)
    {
        if (isset($value->{$name})) {
            return $value->{$name};
        }

        return $default;
    }

    private function getArrayProperty($value, $name, $default)
    {
        if (array_key_exists($name, $value)) {
            return $value[$name];
        }

        return $default;
    }
}
