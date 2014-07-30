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

}
