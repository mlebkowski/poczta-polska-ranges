<?php

namespace Nassau\PocztaPolskaRanges;

class RangeDefinition
{
    const PARITY_ODD = 'n';
    const PARITY_EVEN = 'p';

    private $from;

    private $to = null;

    private $parity = null;

    /**
     * @param float $from
     * @param float|null $to
     * @param string $parity
     */
    public function __construct($from, $to = null, $parity = null)
    {
        if (null !== $parity && false === in_array($parity, [self::PARITY_ODD, self::PARITY_EVEN])) {
            throw new RangeParserException('Invalid paritiy: '. $parity);
        }

        $this->from = $from;
        $this->to = $to;
        $this->parity = $parity;
    }

    /**
     * @return float
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return float|null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return null|string
     */
    public function getParity()
    {
        return $this->parity;
    }

}
