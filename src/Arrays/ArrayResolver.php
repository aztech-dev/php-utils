<?php

namespace Aztech\Util\Arrays;

use Aztech\Util\Collections\StandardIterator;
use Aztech\Util\DotNotation\DotNotationResolver;

/**
 *
 * @author thibaud
 */
class ArrayResolver extends StandardIterator implements \Countable, \ArrayAccess
{

    private $resolver = null;

    public function __construct(array $items = array(), DotNotationResolver $resolver = null)
    {
        parent::__construct($items);

        $this->resolver = $resolver;

        if ($this->resolver === null) {
            $this->resolver = new DotNotationResolver();
        }
    }

    public function extract()
    {
        return $this->items;
    }

    public function iterate($coerce = false, $coercionKey = 0)
    {
        $values = [];

        foreach ($this as $name => $value) {
            $values[$name] = $this->wrapIfNecessary($value, $coerce, $coercionKey);
        }

        return new self($values);
    }

    /**
     * Resolves a value stored in an array, optionally by using dot notation to access nested elements.
     *
     * @param string $key
     *            The key value to resolve.
     * @param mixed $default
     * @return mixed The resolved value or the provided default value.
     */
    public function resolve($key, $default = null, $coerceArray = false, $coercionKey = 0)
    {
        $value = $this->resolver->resolve($this->items, $key, $default, $coercionKey);

        return $this->wrapIfNecessary($value, $coerceArray);
    }

    private function wrapIfNecessary($value, $coerceArray = false, $coercionKey = 0)
    {
        if (! is_array($value) && ! ($value instanceof self) && $coerceArray == true) {
            $value = [ $coercionKey => $value ];
        }

        if (is_array($value)) {
            return new static($value);
        }

        return $value;
    }

    public function current()
    {
        return $this->wrapIfNecessary(current($this->items));
    }

    public function count()
    {
        return count($this->items);
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
            return;
        }

        $this->items[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }
}
