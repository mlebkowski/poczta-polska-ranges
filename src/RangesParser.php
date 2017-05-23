<?php

namespace Nassau\PocztaPolskaRanges;

class RangesParser
{
    const RE = '/
        (?(DEFINE)
            (?P<NUMBER>\d+\s?[a-z]{0,2})
            (?P<COMPOUND>(?&NUMBER)(\/(?&NUMBER))?)
            (?P<DK>DK)
            (?P<PARITY>p|n)
        )
        (?P<from>  (?&COMPOUND)  )
        (?:
            -
            (?P<to>  (?&COMPOUND)|(?&DK)  )
            (?: \(  (?P<parity>(?&PARITY))  \)  )?
        )?
        (?:,\s+|$)
    /x';

    const BOUND_LOWER = '<';
    const BOUND_UPPER = '>';

    /**
     * DK for "Do końca" aka "until the end"
     */
    const UNTIL_END = 'DK';

    public function parse($range)
    {
        if (false === preg_match_all(self::RE, $range, $matches, PREG_SET_ORDER)) {
            throw new RangeParserException(sprintf('Range could not be parsed: "%s"', $range));
        }

        $expected = sizeof(explode(', ', $range));
        if (sizeof($matches) !== $expected) {
            throw new RangeParserException(sprintf('Only %d out of %d ranges could be parsed: "%s"', sizeof($matches), $expected, $range));
        }

        return array_map(function ($item) {
            return $this->parseItem($item);
        }, $matches);

    }

    private function parseItem(array $item)
    {
        $item = array_replace([
            'to' => null,
            'parity' => null,
        ], $item);

        $from = $this->parseNumber($item['from'], self::BOUND_LOWER);
        $to = $this->parseNumber($item['to'] ?: $item['from'], self::BOUND_UPPER);

        /*
         * Special case for "X/Y" ranges. This is one block with same parity, treat it as such
         */
        if (!$item['to'] && $from !== $to && !$item['parity']) {
            $item['parity'] = ($from % 2) ? RangeDefinition::PARITY_ODD : RangeDefinition::PARITY_EVEN;
        }

        return new RangeDefinition($from, $to, $item['parity']);
    }

    private function parseNumber($number, $bound)
    {
        $number = explode('/', $number);
        $number = self::BOUND_UPPER === $bound ? end($number) : reset($number);

        if (self::UNTIL_END === $number) {
            return null;
        }

        $value = (int)$number;

        if ((string)$value !== $number) {
            $value += (ord($number[strlen($value)])-ord('a')+1)/100;
        }

        return $value;
    }
}
