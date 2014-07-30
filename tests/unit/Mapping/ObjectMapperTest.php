<?php

namespace Aztech\Util\Tests;

use Aztech\Util\Mapping\ObjectMapper;
use Aztech\Util\DotNotation\DotNotationResolver;

class ObjectMapperTest extends \PHPUnit_Framework_TestCase
{

    public function getObjectToObjectMapping()
    {
        /* @ f:off */
        $objSource = new \stdClass();
        $objSource->unmapped = 'mapped value';
        $objSource->unmapped_again = 'mapped value again';
        $objSource->complex = new \stdClass();
        $objSource->complex->unmapped = 'complex mapped value';

        $arrSource = array();
        $arrSource['unmapped'] = 'mapped value';
        $arrSource['unmapped_again'] = 'mapped value again';
        $arrSource['complex'] = new \stdClass();
        $arrSource['complex.unmapped'] = 'complex mapped value';

        $mappings = array(
            'unmapped' => array('mapped', false),
            'unmapped_again' => array('mapped_again', false),
            'complex.unmapped' => array('complex_mapped', false)
        );

        $constants = array(
          'constant' => 'constant-value'
        );

        return array(
            array($objSource,new \stdClass(),$mappings, $constants),
            array($arrSource, array(), $mappings, $constants)

        );
        /* @f:on */
    }

    /**
     * @dataProvider getObjectToObjectMapping
     */
    public function testObjectToObjectMapping($source, $target, $mappings, $constants)
    {
        $mapper = new ObjectMapper();

        foreach ($mappings as $sourceProperty => $targetProperty) {
            $mapper->addMapping($sourceProperty, $targetProperty[0], false);
        }

        foreach ($constants as $targetProperty => $value) {
            $mapper->addConstantMapping($targetProperty, $value);
        }

        $mapper->map($source, $target);

        foreach ($mappings as $sourceProperty => $targetProperty) {
            $this->assertEquals(DotNotationResolver::resolve($source, $sourceProperty),
                DotNotationResolver::resolve($target, $targetProperty[0]));
        }

        foreach ($constants as $name => $value) {
            $this->assertEquals(DotNotationResolver::resolve($target, $name), $value, $name);
        }
    }

    /**
     * @dataProvider getObjectToObjectMapping
     * @expectedException \InvalidArgumentException
     */
    public function testObjectToObjectMappingThrowsExceptionOnMissingRequiredSourceProperties($source, $target, $mappings, $constants)
    {
        $mapper = new ObjectMapper();

        foreach ($mappings as $sourceProperty => $targetProperty) {
            $sourceProperty .= '_missing_for_sure_' . md5(rand());
            $mapper->addMapping($sourceProperty, $targetProperty[0], true);
        }

        $mapper->map($source, $target);
    }

    /**
     * @dataProvider getObjectToObjectMapping
     */
    public function testObjectToObjectMappingIgnoresMissingOptionalSourceProperties($source, $target, $mappings, $constants)
    {
        $mapper = new ObjectMapper();

        foreach ($mappings as $sourceProperty => $targetProperty) {
            $sourceProperty .= '_missing_for_sure_' . md5(rand());
            $mapper->addMapping($sourceProperty, $targetProperty[0], false);
        }

        $mapper->map($source, $target);

        foreach ($mappings as $sourceProperty => $targetProperty) {
            $this->assertFalse(DotNotationResolver::propertyOrIndexExists($target, $targetProperty[0]));
        }
    }
}
