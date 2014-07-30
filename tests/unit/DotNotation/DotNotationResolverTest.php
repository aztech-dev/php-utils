<?php

namespace Aztech\Util\Tests\DotNotation;

use Aztech\Util\DotNotation\DotNotationResolver;

class DotNotationResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @return multitype:multitype:string boolean unknown multitype:string boolean multitype:string multitype:string boolean multitype:string boolean multitype:string multitype:string multitype:string boolean \stdClass
     */
    public function getItems()
    {
        /* @f:off */
        $array = array('nested' => array('key' => 'value'), 'key' => 'value');

        $obj = new \stdClass();
        $obj->key = 'value';
        $obj->nested = new \stdClass();
        $obj->nested->key = 'value';

        $primitive = "primitive";

        return array(
            array($array, 'nested', true),
            array($array, 'nested.key', true),
            array($array, 'nested.missing', false),
            array($array, 'key', true),
            array($array, 'missing', false),
            array($obj, 'nested', true),
            array($obj, 'nested.key', true),
            array($obj, 'nested.missing', false),
            array($obj, 'key', true),
            array($obj, 'missing', false),
            array($primitive, 'nested', false),
            array($primitive, 'nested.key', false),
            array($primitive, 'nested.missing', false),
            array($primitive, 'key', false),
            array($primitive, 'missing', false)
        );
        /* @f:on */
    }

    /**
     * @dataProvider getItems
     */
    public function testPropertyExistsReturnsCorrectValue($value, $name, $expected)
    {
        $this->assertEquals($expected, DotNotationResolver::propertyOrIndexExists($value, $name));
    }

    public function getResolveItems()
    {
        /* @f:off */
        $array = array('nested' => array('key' => 'value'), 'key' => 'value');

        $obj = new \stdClass();
        $obj->key = 'value';
        $obj->nested = new \stdClass();
        $obj->nested->key = 'value';

        $primitive = "primitive";

        return array(
            array($array, 'nested', 'default-val', $array['nested']),
            array($array, 'nested.key', 'default-val', $array['nested']['key']),
            array($array, 'nested.missing', 'default-val', 'default-val'),
            array($array, 'key', 'default-val', $array['key']),
            array($array, 'missing', 'default-val', 'default-val'),
            array($obj, 'nested', 'default-val', $obj->nested),
            array($obj, 'nested.key', 'default-val', $obj->nested->key),
            array($obj, 'nested.missing', 'default-val', 'default-val'),
            array($obj, 'key', 'default-val', $obj->key),
            array($obj, 'missing', 'default-val', 'default-val'),
            array($primitive, 'nested', 'default-val', 'default-val'),
            array($primitive, 'nested.key', 'default-val', 'default-val'),
            array($primitive, 'nested.missing', 'default-val', 'default-val'),
            array($primitive, 'key', 'default-val', 'default-val'),
            array($primitive, 'missing', 'default-val', 'default-val')
        );
        /* @f:on */
    }

    /**
     * @dataProvider getResolveItems
     */
    public function testResolveReturnsCorrectValue($value, $name, $default, $expected)
    {
        $this->assertEquals($expected, DotNotationResolver::resolve($value, $name, $default));
    }

}
