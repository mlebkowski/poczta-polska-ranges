<?php

use Nassau\PocztaPolskaRanges\RangeChecker;

class RangeCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $number
     * @param $range
     * @param $expected
     * @dataProvider checker
     */
    public function testChecker($number, $range, $expected)
    {
        $result = (new RangeChecker)->isInRanges($number, $range);

        $this->assertSame($expected, $result);
    }

    public function checker()
    {
        return [
            ['1', '1-DK', true],
            ['1', '1-DK(n)', true],
            ['2', '1-DK(n)', false],
            ['10', '1-DK', true],
            ['5b', '1-DK', true],
            ['5B', '1-DK', true],
            ['5', '2-DK(p)', false],

            ['50', '17-DK(n), 26-36(p)', false],
            ['20', '17-DK(n), 26-36(p)', false],
            ['26', '17-DK(n), 26-36(p)', true],

            ['7', '1-9/11(n), 4-14(p)', true],
            ['9', '1-9/11(n), 4-14(p)', true],
            ['11', '1-9/11(n), 4-14(p)', true],

            ['10', '10a-10d', false],
            ['10a', '10a-10d', true],
            ['10d', '10a-10d', true],
            ['10e', '10a-10d', false],

            ['60', '60/62', true],
            ['62', '60/62', true],
            ['60/62', '60/62', true],
            ['61', '60/62', false],

            ['60', '55-69/71(n), 60', true],
        ];
    }
}
