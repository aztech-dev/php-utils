<?php

namespace Aztech\Util\Collections;

class TypedIterator implements \Iterator
{
    protected $position = 0;

    protected $typeName = '\stdClass';

    protected $items = array();

    protected $keys = array();

    public function __construct($typeName, array $items = array())
    {
        if (! class_exists($typeName, true) && ! interface_exists($typeName, true)) {
            throw new \InvalidArgumentException('Class or interface \'' . $typeName . '\' does not exist.');
        }

        $this->typeName = $typeName;
        $this->items = array();

        foreach ($items as $item) {
            $this->validate($item);
        }

        $this->items = $items;
        $this->keys = array_keys($items);
    }

    public function getTypeName()
    {
        return $this->typeName;
    }

    protected function validate($item)
    {
        if (! $item instanceof $this->typeName) {
            throw new \InvalidArgumentException('Type mismatch.');
        }
    }

    public function filter($callback) {
        if (! is_callable($callback)) {
            throw new \InvalidArgumentException('$callback is not a callback.');
        }

        $newItems = array_filter($this->items, $callback);

        return new self($this->typeName, $newItems);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->items[$this->keys[$this->position]];
    }

    public function key()
    {
        return $this->keys[$this->position];
    }

    public function next()
    {
        ++ $this->position;
    }

    public function valid()
    {
        return isset($this->keys[$this->position]);
    }

    public function toArray($preserveKeys = false)
    {
        if ((bool)$preserveKeys) {
            return $this->items;
        }

        return array_values($this->items);
    }
}
