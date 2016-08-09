<?php

include "vendor/autoload.php";

use Nassau\PocztaPolskaRanges\RangeChecker;

$data = [
    'Piękna' => [
        '00-549' => '11-29(n), 18-26(p)',
        '00-477' => '1-1a, 10',
        '00-539' => '1b-9(n), 12-16b(p)',
        '00-547' => '28-42(p)',
        '00-482' => '2-8(p)',
        '00-677' => '31-39(n)',
        '00-672' => '41-DK(n), 44-68a(p)',
    ]
];

$input = 'Piękna 8';

list ($street, $number) = explode(" ", $input);

$checker = new RangeChecker;

foreach ($data[$street] as $code => $ranges) {
    if ($checker->isInRanges($number, $ranges)) {
        echo "„${input}” matches $code zip code\n";
    }
}
