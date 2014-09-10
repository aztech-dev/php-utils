<?php

namespace Aztech\Util\Collections;

/**
 * Base class providing type constrained collection methods that can be extended
 * to provide type-hinted methods that rely on the methods declared here.
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
     * @param mixed $items
     * @throws \InvalidArgumentException
     */
    public function addRange(array $items)
    {
        foreach ($items as $item) {
            $this->addObject($item);
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
        ), function ($first, $other) {
            return $first === $other;
        });
    }

    /**
     *
     * @param mixed $items
     * @throws \InvalidArgumentException
     */
    public function removeRange(array $items)
    {
        foreach ($items as $item) {
            $this->validate($item);
        }

        // Dirty hack for HHVM, see https://github.com/facebook/hhvm/issues/3653
        array_unshift($items, null);

        $this->items = array_udiff($this->items, $items, function ($first, $other) {
            return $first === $other;
        });
    }

    public function hasObject($item)
    {
        $this->validate($item);

        return in_array($item, $this->items, true);
    }
}
