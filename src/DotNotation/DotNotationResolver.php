<?php

namespace Aztech\Util\DotNotation;

/**
 * @author thibaud
 * @method mixed resolve(mixed $value, string $name, mixed $default = null)
 * @method bool propertyOrIndexExists(mixed $value, string $name)
 */
class DotNotationResolver
{

    private $parser = null;

    public function __construct(DotNotationParser $parser = null)
    {
        if ($parser === null) {
            $parser = new DotNotationParser();
        }

        $this->parser = $parser;
    }

    public function resolve($value, $name, $default = null)
    {
        $elements = $this->parser->getComponents($name);
        $current = $value;
        $index = 0;

        do {
            $current = $this->getDirectProperty($current, $elements[$index++], $default);
        }
        while ($current && $index < count($elements));

        return $current;
    }

    public function propertyOrIndexExists($value, $name)
    {
        $elements = $this->parser->getComponents($name);
        $current = $value;
        $index = 0;

        for ($index = 0; $index < count($elements); $index++) {
            if (! $this->checkDirectProperty($current, $elements[$index]))  {
                return  false;
            }

            $current = $this->getDirectProperty($current, $elements[$index], false);
        }

        return  true;
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
