<?php

namespace Aztech\Util\Arrays;

use Aztech\Util\DotNotation\DotNotationParser;
use Aztech\Util\DotNotation\DotNotationResolver;

class ArrayResolver implements \Iterator, \Countable, \ArrayAccess
{

    private $source;

    public function __construct(array $source = array())
    {
        $this->source = $source;
    }

    public function extract()
    {
        return $this->source;
    }

    /**
     * Resolves a value stored in an array, optionally by using dot notation to access nested elements.
     *
     * @param string $key
     *            The key value to resolve.
     * @param mixed $default
     * @return mixed The resolved value or the provided default value.
     */
    public function resolve($key, $default = null)
    {
        $toReturn = $default;

        if (DotNotationParser::hasDot($key) && DotNotationResolver::propertyOrIndexExists($this->source, $key)) {
            $toReturn = DotNotationResolver::resolve($this->source, $key);        
        }
        elseif (DotNotationResolver::propertyOrIndexExists($this->source, $key)) {
            $toReturn = $this->source[$key];
        }

        return $this->wrapIfNecessary($toReturn);
    }

    private function wrapIfNecessary($value)
    {
        if (is_array($value)) {
            return new static($value);
        }

        return $value;
    }

    public function rewind()
    {
        reset($this->source);
    }

    public function current()
    {
        return $this->wrapIfNecessary(current($this->source));
    }

    public function key()
    {
        return key($this->source);
    }

    public function next()
    {
        return next($this->source);
    }

    public function valid()
    {
        $key = key($this->source);

        return ($key !== null && $key !== false);
    }

    public function count()
    {
        return count($this->source);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->source[] = $value;
        } else {
            $this->source[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->source[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->source[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->source[$offset]) ? $this->source[$offset] : null;
    }
}