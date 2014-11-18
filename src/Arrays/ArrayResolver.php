<?php

namespace Aztech\Util\Arrays;

use Aztech\Util\Collections\StandardIterator;
use Aztech\Util\DotNotation\DotNotationResolver;

/**
 *
 * @author thibaud
 * @SuppressWarnings(PHPMD.TooManyMethods)
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

    public function merge(ArrayResolver $other)
    {
        return new self(array_merge($this->extract(), $other->extract()));
    }

    public function extract()
    {
        return $this->items;
    }

    /**
     *
     * @param boolean $coerce
     * @param mixed $coercionKey
     * @return self @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function iterate($coerce = false, $coercionKey = 0)
    {
        $values = [];

        foreach ($this as $name => $value) {
            $values[$name] = $this->wrapIfNecessary($value, $coerce, $coercionKey);
        }

        return new self($values);
    }

    /**
     *
     * @param callback $filter
     * @return ArrayResolver
     */
    public function filter(callable $filter)
    {
        $filtered = [];

        foreach ($this as $name => $value) {
            if ($filter($name, $value)) {
                $filtered[$name] = $value;
            }
        }

        return new self($filtered);
    }

    /**
     * Resolves a value stored in an array, optionally by using dot notation to access nested elements.
     *
     * @param string $key The key value to resolve.
     * @param mixed $default
     * @param boolean $coerceArray
     * @param mixed $coercionKey
     * @return mixed The resolved value or the provided default value.
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function resolve($key, $default = null, $coerceArray = false, $coercionKey = 0)
    {
        $value = $this->resolver->resolve($this->items, $key, $default, $coercionKey);

        return $this->wrapIfNecessary($value, $coerceArray);
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    private function wrapIfNecessary($value, $coerceArray = false, $coercionKey = 0)
    {
        if ($this->shouldWrapValueInArray($value, $coerceArray)) {
            $value = [
                $coercionKey => $value
            ];
        }

        return $this->getWrappedValue($value);
    }

    private function getWrappedValue($value)
    {
        if (is_array($value)) {
            $value = new static($value);
        }
        return $value;
    }

    private function shouldWrapValueInArray($value, $coerceArray)
    {
        return ! is_array($value) && ! ($value instanceof self) && $coerceArray == true;
    }

    /**
     * (non-PHPdoc)
     *
     * @see StandardIterator::current()
     */
    public function current()
    {
        return $this->wrapIfNecessary(current($this->items));
    }

    /**
     * (non-PHPdoc)
     *
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
            return;
        }

        $this->items[$offset] = $value;
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * (non-PHPdoc)
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }
}
