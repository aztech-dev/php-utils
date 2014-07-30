<?php

namespace Aztech\Util\Tests\Arrays;

use Aztech\Util\Arrays\ArrayResolver;

/**
 * @todo Use datasets instead of inline declarations for arrays
 * @author thibaud
 *
 */
class ArrayResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testResolveExistingNonNestedKeysReturnsNonDefaultValue()
    {
        $items = array('test' => 'blamabl', 'object' => new \stdClass());

        $resolver = new ArrayResolver($items);

        foreach ($items as $key => $value) {
            $this->assertSame($value, $resolver->resolve($key));
        }
    }

    public function testResolveExistingNestedKeysReturnsNonDefaultValue()
    {
        $nested = array('test' => 'blamabl', 'object' => new \stdClass());
        $items = array('nested' => $nested);

        $resolver = new ArrayResolver($items);

        foreach ($nested as $key => $value) {
            $this->assertSame($value, $resolver->resolve('nested.' . $key));
        }
    }

    public function testExtractReturnsOriginalArray()
    {
        $items = array('test' => 'blamabl', 'object' => new \stdClass());

        $resolver = new ArrayResolver($items);

        $this->assertSame($items, $resolver->extract());
    }

    public function testArrayAreWrapped()
    {
        $items = array('test' => array());

        $resolver = new ArrayResolver($items);

        $this->assertTrue($resolver->resolve('test') instanceof ArrayResolver);
    }

    public function testIterationReturnsOnlyFirstLevelProperties()
    {
        $nested = array('test' => 'blamabl', 'object' => new \stdClass());
        $items = array('nested' => $nested);

        $resolver = new ArrayResolver($items);
        $resolved = array();

        foreach ($resolver as $key => $value) {
            $resolved[$key] = $value;
        }

        $invalidItems = array_udiff($resolved, $items, function ($a, $b) {
            return $a === $b;
        });

        $this->assertEmpty($invalidItems);
    }

    public function testCountReturnsCorrectValue()
    {
        $items = array('test' => 'blamabl', 'object' => new \stdClass());

        $resolver = new ArrayResolver($items);

        $this->assertEquals(count($items), count($resolver));
    }

    public function testSettingByOffsetAcceptsNullOffsets()
    {
        $resolver=  new ArrayResolver();

        $resolver[] = "value";

        $this->assertCount(1, $resolver);
        $this->assertTrue(isset($resolver[0]));
        $this->assertEquals("value", $resolver[0]);
    }

    public function testOffsetAccessorReturnsSameValuesAsWhenCallingResolve()
    {
        $items = array('test' => 'blamabl', 'object' => new \stdClass());

        $resolver = new ArrayResolver($items);

        foreach ($items as $key => $value) {
            $this->assertSame($resolver->resolve($key), $resolver[$key]);
        }
    }

    public function testSettingByOffsetAcceptsAllKeysAndAssignsTheValueToTheCorrectKey()
    {
        $items = array('test' => 'blamabl', 'object' => new \stdClass());

        $resolver = new ArrayResolver();

        foreach ($items as $key => $value) {
            $resolver[$key] = $value;

            $this->assertTrue(isset($resolver[$key]));
            $this->assertSame($value, $resolver[$key]);
        }
    }

    public function testUnsetClearsItem()
    {
        $items = array('test' => 'blamabl', 'object' => new \stdClass());

        $resolver = new ArrayResolver($items);

        unset($resolver['test']);

        $this->assertFalse(isset($resolver['test']));
    }

}
