<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use GuzzleHttp\Client;

class Stamp_IC_WC_Api_Client {

	/* @var Client $http_client */
	protected $http_client;

	/**
	 * Stamp_IC_WC_Api_Client constructor.
	 *
	 * @param Client $http_client
	 */
	public function __construct( Client $http_client ) {
		$this->http_client = $http_client;
	}

	public function save_wc_credentials( array $params ) {

		$result = null;

		try {

			$response = $this->http_client->post(
				'/api/instant-checkout/installations',
				array(
					'json' => $params
				)
			);

//			$body = json_decode( (string) $response->getBody(), true );

			$result = true;

		} catch ( Exception $exception ) {

			$result = array(
				'error' => true,
				'message' => $exception->getMessage(),
				'code' => $exception->getCode(),
			);

			error_log( $result[ 'message' ] );
		}

		return $result;
	}
}
