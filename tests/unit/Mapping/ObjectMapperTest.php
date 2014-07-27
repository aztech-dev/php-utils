<?php

namespace Aztech\Util\Tests;

use Aztech\Util\Mapping\ObjectMapper;
use Aztech\Util\DotNotation\DotNotationResolver;

class ObjectMapperTest extends \PHPUnit_Framework_TestCase
{
    
    public function getObjectToObjectMapping()
    {
        $objSource = new \stdClass();
        $objSource->unmapped = 'mapped value';
        $objSource->unmapped_again = 'mapped value again';
        $objSource->complex = new \stdClass();
        $objSource->complex->unmapped = 'complex mapped value';
        
        $mappings = array('unmapped' => 'mapped', 'unmapped_again' => 'mapped_again', 'complex.unmapped' => 'complex_mapped'); 
        
        return array(
        	array($objSource, new \stdClass(), $mappings)
        );
    }
    
    /**
     * @dataProvider getObjectToObjectMapping
     */
    public function testObjectToObjectMapping($source, $target, $mappings)
    {
        $mapper = new ObjectMapper();
        
        foreach ($mappings as $sourceProperty => $targetProperty) {
            $mapper->addMapping($sourceProperty, $targetProperty, false);
        }
        
        $mapper->map($source, $target);
        
        foreach ($mappings as $sourceProperty => $targetProperty) {
            $this->assertEquals(
                DotNotationResolver::resolve($source, $sourceProperty), 
                DotNotationResolver::resolve($target, $targetProperty)
            );
        }
    }
    
}