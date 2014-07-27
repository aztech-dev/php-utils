<?php

namespace Aztech\Satisious\Tests\Util;

use Aztech\Satisious\Util\DotNotationParser;
class DotNotationParserTest extends \PHPUnit_Framework_TestCase
{
    
    public function getDotTestDataSet()
    {
        return array(
        	array('bla', false),
        	array('bla.tbdldza', true),
        	array('blfdshfjdskhfjkdsa', false),
        	array('bla.blablabla.bla', true)
        );
    }
    
    /**
     * @dataProvider getDotTestDataSet
     */
    public function testDotsAreCorrectlyDetected($name, $expected)
    {
        $actual = DotNotationParser::hasDot($name);
        
        $this->assertEquals($expected, $actual);
    }
}