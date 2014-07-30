<?php

namespace Aztech\Util\Collections;

use Aztech\Util\Collections\TypedIterator;

/**
 * Base class providing type constrained collection methods that can be extended to provide type-hinted methods that rely on the methods declared here.
 * @author thibaud
 */
class TypedCollection extends TypedIterator
{

    /**
     *
     * @param mixed $item
     * @throws \InvalidArgumentException
     */
    public function addObject($item)
    {
        $this->validate($item);
        $this->items[] = $item;
    }
    
    /**
     *
     * @param mixed $item
     * @throws \InvalidArgumentException
     */
    public function addRange(array $items)
    {
        foreach ($items as $item) {
            $this->validate($item);
            $this->items[] = $item;
        }
    }

    /**
     *
     * @param mixed $item
     * @throws \InvalidArgumentException
     */
    public function removeObject($item)
    {
        $this->validate($item);

        $this->items = array_udiff($this->items, array(
            $item
        ), function ($a, $b) {
            return $a === $b;
        });
    }
}
