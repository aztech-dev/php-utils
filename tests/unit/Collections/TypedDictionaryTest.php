<?php

namespace Aztech\Util\Tests\Collections;

use Aztech\Util\Collections\TypedDictionary;

class TypedDictionaryTest extends \PHPUnit_Framework_TestCase
{

    public function testSetThenGetByKeyReturnsCorrectValue()
    {
        $key = 'test';
        $item = new \stdClass();

        $dictionary = new TypedDictionary('\stdClass');
        $dictionary->setKey($key, $item);

        $actual = $dictionary->getByKey($key);

        $this->assertSame($item, $actual);
    }

    public function testHasKeyReturnsCorrectValues()
    {
        $collection = new TypedDictionary('\stdClass');
        $key = 'test';
        $item = new \stdClass();
        
        $this->assertFalse($collection->hasKey($key));
        
        $collection->setKey($key, $item);
        
        $this->assertTrue($collection->hasKey($key));
    }
    
    public function testRemoveUnsetsItem()
    {
        $collection = new TypedDictionary('\stdClass');
        $key = 'test';
        $item = new \stdClass();
        
        $collection->setKey($key, $item);
        $this->assertTrue($collection->hasKey($key));
        $this->assertTrue($collection->has($item));
        $collection->removeByKey($key);
        $this->assertFalse($collection->hasKey($key));
        $this->assertFalse($collection->has($item));
    }
    
    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetByKeyThrowsExceptionOnOutOfBoundsKey()
    {
        $key = 'bla';
        
        $dictionary = new TypedDictionary('\stdClass');
        
        $dictionary->getByKey($key);
    }

    public function testIteration()
    {
        $items = array('key' => new \stdClass(), 'next' => new \stdClass());
        
        $dictionary = new TypedDictionary('\stdClass', $items);
        $collected = array();
        
        foreach ($dictionary as $key => $value) {
            $collected[$key] = $value;    
        }
        
        $this->assertEquals($items, $collected);
    }
}
