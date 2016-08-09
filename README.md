# Parse ranges provided by Poczta Polska in their PNA list

## Installation

```
composer require nassau/poczta-polska-ranges
```

## Usage

TL;DR;

```
(new Nassau\PocztaPolskaRanges\RangeChecker)->isInRanges("15a", "10-24(p)");
// false, 15a is on the other side of the street
```


Poczta polska provides zip codes (PNA) with building number ranges in format:

```
An-Bm, X-Z(p)
```

This tool parses those formats and checks if given number matches given range, for example:

```php
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
```
