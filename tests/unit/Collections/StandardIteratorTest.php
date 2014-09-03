<?php

namespace Aztech\Util\Tests\Collections;

use Aztech\Util\Collections\StandardIterator;

class StandardIteratorTest extends \PHPUnit_Framework_TestCase
{

    public function testIteratingReturnsAllItems()
    {
        $items = array(
            new \stdClass(),
            new \stdClass(),
            new \stdClass(),
            new \stdClass()
        );
        $iterator = new StandardIterator($items);

        $iteratedItems = array();
        foreach ($iterator as $item) {
            $iteratedItems[] = $item;
        }

        $itemDiff = array_udiff($items, $iteratedItems,
            function ($item, $other)
            {
                return $item === $other;
            });

        $this->assertEmpty($itemDiff);
    }
}
