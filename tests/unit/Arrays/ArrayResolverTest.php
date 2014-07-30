<?php

namespace Aztech\Util\Tests\Arrays;

use Aztech\Util\Arrays\ArrayResolver;

/**
 * @author thibaud
 *
 */
class ArrayResolverTest extends \PHPUnit_Framework_TestCase
{

    public function getStandardItems()
    {
        /* @f:off */
        return array(array(
            array('test' => 'blamabl', 'object' => new \stdClass())
        ));
        /* @f:on */
    }

    /**
     * @dataProvider getStandardItems
     */
    public function testResolveExistingNonNestedKeysReturnsNonDefaultValue($items)
    {
        $resolver = new ArrayResolver($items);

        foreach ($items as $key => $value) {
            $this->assertSame($value, $resolver->resolve($key));
        }
    }

    /**
     * @dataProvider getStandardItems
     */
    public function testResolveExistingNestedKeysReturnsNonDefaultValue($items)
    {
        $parent = array('nested' => $items);

        $resolver = new ArrayResolver($parent);

        foreach ($items as $key => $value) {
            $this->assertSame($value, $resolver->resolve('nested.' . $key));
        }
    }

    /**
     * @dataProvider getStandardItems
     */
    public function testExtractReturnsOriginalArray($items)
    {
        $resolver = new ArrayResolver($items);

        $this->assertSame($items, $resolver->extract());
    }

    public function testArrayAreWrapped()
    {
        $items = array('test' => array());

        $resolver = new ArrayResolver($items);

        $this->assertTrue($resolver->resolve('test') instanceof ArrayResolver);
    }

    /**
     * @dataProvider getStandardItems
     */
    public function testIterationReturnsOnlyFirstLevelProperties($nested)
    {
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

    /**
     * @dataProvider getStandardItems
     */
    public function testCountReturnsCorrectValue($items)
    {
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

    /**
     * @dataProvider getStandardItems
     */
    public function testOffsetAccessorReturnsSameValuesAsWhenCallingResolve($items)
    {
        $resolver = new ArrayResolver($items);

        foreach ($items as $key => $value) {
            $this->assertSame($resolver->resolve($key), $resolver[$key]);
        }
    }

    /**
     * @dataProvider getStandardItems
     */
    public function testSettingByOffsetAcceptsAllKeysAndAssignsTheValueToTheCorrectKey($items)
    {
        $resolver = new ArrayResolver();

        foreach ($items as $key => $value) {
            $resolver[$key] = $value;

            $this->assertTrue(isset($resolver[$key]));
            $this->assertSame($value, $resolver[$key]);
        }
    }

    /**
     * @dataProvider getStandardItems
     */
    public function testUnsetClearsItem($items)
    {
        $resolver = new ArrayResolver($items);

        foreach ($resolver as $key => $item) {
            unset($resolver[$key]);
            $this->assertFalse(isset($resolver[$key]));
        }
    }

}
