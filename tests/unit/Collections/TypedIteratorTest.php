<?php

namespace Aztech\Util\Tests\Collections;

use Aztech\Util\Collections\TypedIterator;

class TypedIteratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotInitializeWithCollectionContainingPrimitives()
    {
        $items = array('string', new \stdClass());

        $iterator = new TypedIterator('\stdClass', $items);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotInitializeWithInvalidTypeName()
    {
        $iterator = new TypedIterator('\Aztech\Util\Tests\Collections\TypedIteratorTest\DoNotUseThisClassName');
    }

    public function testIteratingReturnsAllItems()
    {
        $items = array(new \stdClass(), new \stdClass(), new \stdClass(), new \stdClass());
        $iterator = new TypedIterator('\stdClass', $items);

        $iteratedItems = array();
        foreach ($iterator as $item) {
            $iteratedItems[] = $item;
        }

        $itemDiff = array_udiff($items, $iteratedItems, function ($item, $other) {
            return $item === $other;
        });

        $this->assertEmpty($itemDiff);
    }

    public function testKeyReturnsCorrectKey()
    {
        $items = array(new \stdClass(), new \stdClass(), new \stdClass(), new \stdClass());
        $iterator = new TypedIterator('\stdClass', $items);

        $iterator->rewind();

        for ($i = 0; $i < count($items); $i++) {
            $this->assertEquals($i, $iterator->key());
            $iterator->next();
        }
    }
}
