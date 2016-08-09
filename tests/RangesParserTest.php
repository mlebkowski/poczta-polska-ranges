<?php

use Nassau\PocztaPolskaRanges\RangeDefinition;
use Nassau\PocztaPolskaRanges\RangesParser;

class RangesParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $input
     * @param $expected
     * @dataProvider ranges
     */
    public function testRanges($input, $expected)
    {
        $result = (new RangesParser)->parse($input);

        $this->assertSameSize($expected, $result);

        array_map(function (RangeDefinition $definition, $expected) {
            list ($from, $to, $parity) = array_pad($expected, 3, null);

            $this->assertEquals($from, $definition->getFrom());
            $this->assertEquals($to, $definition->getTo());
            $this->assertEquals($parity, $definition->getParity());
        }, $result, $expected);
    }

    public function ranges()
    {
        $odd = RangeDefinition::PARITY_ODD;
        $even = RangeDefinition::PARITY_EVEN;

        return [
            ['1-DK', [[1]]],
            ['17-DK(n), 26-36(p)', [[17, null, $odd], [26, 36, $even]]],
            ['1-9/11(n), 4-14(p)', [[1, 11, $odd], [4, 14, $even]]],
            ['1-10, 11-41(n), 12-34(p)', [[1, 10], [11, 41, $odd], [12, 34, $even]]],
            ['78-88/92, 89-91(n)', [[78, 92], [89, 91, $odd]]],
            ['51-55a(n), 56-62(p)', [[51, 55.01, $odd], [56, 62, $even]]],
            ['11, 12-14(p)', [[11, 11], [12, 14, $even]]],
            ['60/62', [[60, 62, $even]]], // TODO
            ['1-2, 5-15(n), 8-32b(p)', [[1, 2], [5, 15, $odd], [8, 32.02, $even]]],
            ['55-69/71(n), 60', [[55, 71, $odd], [60, 60]]],
            ['8-DK(p), 13/15-DK(n), 34a-41c', [[8, null, $even], [13, null, $odd], [34.01, 41.03]]],
            ['37-47/51(n)', [[37, 51, $odd]]],
        ];
    }
}
