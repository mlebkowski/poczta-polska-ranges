<?php

use Nassau\PocztaPolskaRanges\RangesParser;

class BulkRangesParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $sample
     * @dataProvider sample
     */
    public function testSample($sample)
    {
        $sample && $this->assertGreaterThan(0, sizeof((new RangesParser())->parse($sample)));
    }

    public function sample()
    {
        $fd = fopen(__DIR__ . '/../app/sample.txt', 'r');

        while (!feof($fd)) {
            yield [fgets($fd, 1024)];
        }

        fclose($fd);
    }
}
