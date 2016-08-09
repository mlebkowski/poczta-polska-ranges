<?php

namespace Nassau\PocztaPolskaRanges;

class RangeChecker
{

    /**
     * @var RangesParser
     */
    private $parser;

    /**
     * @param RangesParser $parser
     */
    public function __construct(RangesParser $parser = null)
    {
        $this->parser = $parser ?: new RangesParser;
    }

    /**
     * @param string|RangeDefinition   $number
     * @param string|RangeDefinition[] $ranges
     * @return bool
     */
    public function isInRanges($number, $ranges)
    {
        if ("" === $number || "" === $ranges) {
            return true;
        }

        if (is_string($ranges)) {
            $ranges = $this->parser->parse($ranges);
        }

        if (is_string($number)) {
            list ($number) = $this->parser->parse(strtolower($number));
        }

        $value = $number->getFrom();
        $parity = ($value % 2) ? RangeDefinition::PARITY_ODD : RangeDefinition::PARITY_EVEN;

        foreach ($ranges as $item) {
            if ($value < $item->getFrom()) {
                continue;
            }

            if (null !== $item->getTo() && $value > $item->getTo()) {
                continue;
            }

            if (null !== $item->getParity() && $item->getParity() !== $parity) {
                continue;
            }

            return true;

        }

        return false;

    }
}
