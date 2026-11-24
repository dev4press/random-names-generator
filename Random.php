<?php

namespace Dev4Press\Generator\Name;

use Exception;
use RuntimeException;

class Random {
	protected string $output = 'array';
	protected array $allowed_formats = array(
		'array',
		'json',
		'associative_array'
	);

	private array $lists = array();

	public function __construct() {
	}

	public static function instance(): Random {
		static $_instance = false;

		if ( $_instance === false ) {
			$_instance = new Random();
		}

		return $_instance;
	}

	private function get_list( string $type ): array {
		if ( ! isset( $this->lists[ $type ] ) ) {
			$json = @file_get_contents( __DIR__ . '/names/' . $type . '.json' );
			if ( $json === false ) {
				throw new RuntimeException( 'Unable to read the ' . $type . ' name list.' );
			}

			$names = json_decode( $json );
			if ( ! is_array( $names ) ) {
				throw new RuntimeException( 'Invalid JSON name list for ' . $type . ' names.' );
			}

			$names = array_filter( $names, static function ( mixed $name ): bool {
				return is_string( $name ) && trim( $name ) !== '';
			} );

			if ( empty( $names ) ) {
				throw new RuntimeException( 'The ' . $type . ' name list has no usable entries.' );
			}

			$this->lists[ $type ] = $names;
		}

		return $this->lists[ $type ];
	}

	/**
	 * @throws \Exception
	 */
	public function output( string $output = 'array' ): Random {
		if ( ! in_array( $output, $this->allowed_formats ) ) {
			throw new Exception( 'Unrecognized format.' );
		}

		$this->output = $output;

		return $this;
	}

	public function generate_name(): string {
		$list = $this->generate_names( 1, 'array' );

		return $list[0];
	}

	public function generate_names( int $num = 1, string $output = '' ): array|string|false {
		if ( $num < 1 ) {
			return array();
		}

		$output = empty( $output ) ? $this->output : $output;

		$first_names = $this->get_list( 'first' );
		$last_names  = $this->get_list( 'last' );

		$results = array();

		for ( $i = 0; $i < $num; $i ++ ) {
			$random_first_name_index = array_rand( $first_names );
			$random_last_name_index  = array_rand( $last_names );

			$first_name = $first_names[ $random_first_name_index ];
			$last_name  = $last_names[ $random_last_name_index ];

			switch ( $output ) {
				case 'array':
					$results[] = $first_name . ' ' . $last_name;
					break;
				case 'json':
				case 'associative_array':
					$results[] = array( 'first_name' => $first_name, 'last_name' => $last_name );
					break;
			}
		}

		if ( $output == 'json' ) {
			$results = json_encode( $results );
		}

		return $results;
	}
}