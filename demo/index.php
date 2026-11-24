<?php

use Dev4Press\Generator\Name\Random;

require_once __DIR__ . '/../Random.php';

header( 'Content-Type: text/plain; charset=UTF-8' );

$generator = new Random();

echo 'Single name', PHP_EOL;
echo $generator->generate_name(), PHP_EOL, PHP_EOL;

echo 'Full names', PHP_EOL;
foreach ( $generator->generate_names( 5 ) as $name ) {
    echo $name, PHP_EOL;
}

echo PHP_EOL, 'First and last name fields', PHP_EOL;
print_r( $generator->generate_names( 3, 'associative_array' ) );

echo PHP_EOL, 'JSON names', PHP_EOL;
$generator->output( 'json' );
echo $generator->generate_names( 3 ), PHP_EOL;
