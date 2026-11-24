# Random Names Generator

`Dev4Press\Generator\Name\Random` combines first names and surnames from bundled lists. It can generate millions of combinations for demo content, seed data, and tests. Names are sampled independently, so a batch may contain repeats.

## Requirements

- PHP 8.0 or newer

Keep the bundled `names/` directory alongside `Random.php`. The generator loads both lists relative to its own file, skips blank and non-string entries, and throws a `RuntimeException` if a required list cannot be loaded or has no usable names.

## Run the demo

From the repository root, run `php demo/index.php`. The demo also works through a PHP-enabled web server. [Demo script](demo/index.php)

## Usage

Run these examples from a PHP file alongside `Random.php`.

### Generate one name or a batch

```php
<?php

use Dev4Press\Generator\Name\Random;

require_once __DIR__ . '/Random.php';

$generator = new Random();

echo $generator->generate_name(), PHP_EOL;

foreach ( $generator->generate_names( 5 ) as $name ) {
    echo $name, PHP_EOL;
}
```

### Get separate first and last names or JSON

Use the same `$generator` instance. Pass a format to `generate_names()` to select it for one call.

```php
$people = $generator->generate_names( 3, 'associative_array' );

foreach ( $people as $person ) {
    echo $person['first_name'], ' ', $person['last_name'], PHP_EOL;
}

$generator->output( 'json' );
$json = $generator->generate_names( 3 );
if ( $json !== false ) {
    echo $json, PHP_EOL;
}
```

## Output formats

| Format | Result |
| --- | --- |
| `array` (default) | An array of full-name strings. |
| `associative_array` | An array of records with `first_name` and `last_name` keys. |
| `json` | A JSON string containing those records, or `false` if encoding fails. |

`output()` sets the default format for the instance. `generate_name()` always returns a single full-name string. A nonpositive count passed to `generate_names()` returns an empty PHP array in every format.

## Acknowledgment

This class is based on the original project [PHP Random Name Generator](https://github.com/edwardr/php-random-name-generator) by [edwardr](https://github.com/edwardr), and it uses list of names with most of the PHP code rewritten and expanded.

## License

[MIT](license.txt)
