<?php

namespace Dev4Press\Generator\Name;

use Exception;

class Random {
	protected $output = 'array';
	protected $allowed_formats = array(
		'array',
		'json',
		'associative_array'
	);

	private $lists = array();

	public function __construct() {
	}

	public static function instance() : Random {
		static $_instance = false;

		if ( $_instance === false ) {
			$_instance = new Random();
		}

		return $_instance;
	}

	private function get_list( string $type ) {
		if ( ! isset( $this->lists[ $type ] ) ) {
			$json = file_get_contents( 'names/' . $type . '.json', FILE_USE_INCLUDE_PATH );

			$this->lists[ $type ] = json_decode( $json, true );
		}

		return $this->lists[ $type ];
	}

	/**
	 * @throws \Exception
	 */
	public function output( string $output = 'array' ) : Random {
		if ( ! in_array( $output, $this->allowed_formats ) ) {
			throw new Exception( 'Unrecognized format.' );
		}

		$this->output = $output;

		return $this;
	}

	public function generate_names( int $num = 1 ) {
		if ( $num < 1 ) {
			return array();
		}

		$first_names = $this->get_list( 'first' );
		$last_names  = $this->get_list( 'last' );

		$results = array();

		for ( $i = 0; $i < $num; $i ++ ) {
			$random_first_name_index = array_rand( $first_names );
			$random_last_name_index  = array_rand( $last_names );

			$first_name = $first_names[ $random_first_name_index ];
			$last_name  = $last_names[ $random_last_name_index ];

			switch ( $this->output ) {
				case 'array':
					$results[] = $first_name . ' ' . $last_name;
					break;
				case 'json':
				case 'associative_array':
					$results[] = array( 'first_name' => $first_name, 'last_name' => $last_name );
					break;
			}
		}

		if ( $this->output == 'json' ) {
			$results = json_encode( $results );
		}

		return $results;
	}
}