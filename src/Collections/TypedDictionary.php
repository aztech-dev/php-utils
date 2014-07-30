<?php

namespace Aztech\Util\Collections;

class TypedDictionary extends TypedIterator
{

    public function __construct($typeName, array $items = array())
    {
        parent::__construct($typeName, $items);

        reset($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function rewind()
    {
      return reset($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function valid()
    {
        return isset($this->items[$this->key]);
    }

    public function setKey($key, $item)
    {
        $this->validate($item);

        $this->items[$key] = $item;
    }

    public function hasKey($key)
    {
        return array_key_exists($key, $this->items);
    }

    public function removeByKey($key)
    {
        if ($this->hasKey($key)) {
            unset($this->items[$key]);
        }
    }

    public function getByKey($key)
    {
        if ($this->hasKey($key)) {
            return $this->items[$key];
        }

        throw new \OutOfBoundsException('Key does not exist');
    }
}
