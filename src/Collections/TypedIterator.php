<?php

namespace Aztech\Util\Collections;

class TypedIterator implements \Iterator
{
    protected $position = 0;

    protected $typeName = '\stdClass';

    protected $items = array();

    public function __construct($typeName, array $items = array())
    {
        if (! class_exists($typeName, true)) {
            throw new \InvalidArgumentException('Class \'' . $typeName . '\' does not exist.');
        }

        $this->typeName = $typeName;
        $this->items = array();

        foreach ($items as $item) {
            $this->validate($item);
        }

        $this->items = $items;
    }

    protected function validate($item)
    {
        if (! $item instanceof $this->typeName) {
            throw new \InvalidArgumentException('Type mismatch.');
        }
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++ $this->position;
    }

    public function valid()
    {
        return isset($this->items[$this->position]);
    }
}