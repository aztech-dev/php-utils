<?php

namespace Aztech\Util\Collections;

class TypedCollectionTest extends \PHPUnit_Framework_TestCase
{

    public function getNonStdClassItems()
    {
        /* @f:off */
        return array(array('string'), array(12345), array(new \SplObjectStorage()));
        /* @f:on */
    }

    /**
     * @dataProvider getNonStdClassItems @expectedException \InvalidArgumentException
     */
    public function testAddObjectRejectsInvalidItems($item)
    {
        $collection = new TypedCollection('\stdClass');

        $collection->addObject($item);
    }

    public function testAddObjectAcceptsValidItems()
    {
        $items = array(
            new \stdClass(),
            new \stdClass()
        );

        $collection = new TypedCollection('\stdClass');

        foreach ($items as $item) {
            $collection->addObject($item);
        }

        $iterated = array();

        foreach ($collection as $item) {
            $iterated[] = $item;
        }

        $diff = array_udiff($items, $iterated, function ($a, $b) {
            return $a === $b;
        });

        $this->assertEmpty($diff);
    }

    public function testRemoveObjectRemovesValidItems()
    {
        $items = array(
            new \stdClass(),
            new \stdClass()
        );

        $collection = new TypedCollection('\stdClass', $items);

        foreach ($items as $item) {
            $collection->removeObject($item);
        }

        $iterated = array();

        foreach ($collection as $item) {
            $iterated[] = $item;
        }

        $this->assertEmpty($iterated);
    }
}
