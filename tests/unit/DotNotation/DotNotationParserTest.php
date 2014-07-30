<?php

namespace Aztech\Util\Tests\DotNotation;

use Aztech\Util\DotNotation\DotNotationParser;

class DotNotationParserTest extends \PHPUnit_Framework_TestCase
{

    public function getDotTestDataSet()
    {
        /* @f:off */
        return array(
            array('bla', false),
            array('bla.tbdldza', true),
            array('blfdshfjdskhfjkdsa', false),
            array('bla.blablabla.bla', true)
        );
        /* @f:on */
    }

    public function getComponentTestDataSet()
    {
        /* @f:off */
        return array(
            array('bla', null, array('bla')),
            array('bla.tbdldza', null, array('bla', 'tbdldza')),
            array('blfdshfjdskhfjkdsa', null, array('blfdshfjdskhfjkdsa')),
            array('bla.blablabla.bla', null, array('bla', 'blablabla', 'bla')),
            array('bla', 1, array('bla')),
            array('bla.tbdldza', 1, array('bla.tbdldza')),
            array('bla.blablabla.bla', 2, array('bla', 'blablabla.bla')),
            array('bla.blablabla.bla', 4, array('bla', 'blablabla', 'bla'))
        );
        /* @f:on */
    }

    /**
     * @dataProvider getDotTestDataSet
     */
    public function testDotsAreCorrectlyDetected($name, $expected)
    {
        $actual = DotNotationParser::hasDot($name);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider getComponentTestDataSet
     */
    public function testComponentsAreCorrectlyExtracted($name, $limit, $expected)
    {
        $actual = DotNotationParser::getComponents($name, $limit);

        $this->assertEquals($expected, $actual);
    }
}
