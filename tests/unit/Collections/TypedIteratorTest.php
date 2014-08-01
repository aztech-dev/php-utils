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

    public function testCanInitializeWithClassOrInterface()
    {
        $iterator = new TypedIterator('\stdClass');

        $this->assertNotNull($iterator);

        $iterator = new TypedIterator('\Iterator');

        $this->assertNotNull($iterator);
    }

    public function testGetTypeNameReturnsCorrectName()
    {
        $typename = '\stdClass';

        $iterator = new TypedIterator($typename);

        $this->assertEquals($typename, $iterator->getTypeName());
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

    public function getInvalidCallbacks()
    {
        return array(
            array('string'),
            array(12),
            array(new \stdClass()),
            array(array()),
            array(true),
            array(null),
            array(false)
        );
    }

    /**
     * @dataProvider getInvalidCallbacks
     * @expectedException \InvalidArgumentException
     */
    public function testFilterThrowsInvalidArgumentWithNonCallableParam($callback)
    {
        $iterator = new TypedIterator('\stdClass');

        $iterator = $iterator->filter($callback);
    }

    public function testFilterSelectsCorrectItems()
    {
        $a = new \stdClass();
        $a->test = true;

        $b = new \stdClass();
        $b->test = false;

        $items = array($a, $a, $b, $b, $a);
        $iterator = new TypedIterator('\stdClass', $items);

        $iterator = $iterator->filter(function(\stdClass $item) {
            return $item->test;
        });

        foreach ($iterator as $item) {
            $this->assertTrue($item->test);
        }

    }
}
