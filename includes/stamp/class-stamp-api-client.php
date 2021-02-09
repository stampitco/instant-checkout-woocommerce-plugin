<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Api_Client {

    protected $api_url;

    protected $api_token;

	public function get_api_url( $path = null, array $query = array() ): string {

		if( ! is_null( $path ) ) {

			$url = sprintf(
				'%s/%s',
				$this->api_url,
				ltrim( $path, '/\\' )
			);

			if( ! empty( $query ) ) {
				$url .= '?' . http_build_query( $query );
			}

			return $url;
		}

		return $this->api_url;
	}

	/**
	 * @param mixed $api_url
	 *
	 * @return Stamp_IC_WC_Api_Client
	 */
	public function set_api_url( $api_url ): Stamp_IC_WC_Api_Client {
		$this->api_url = $api_url;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_api_token() {
		return $this->api_token;
	}

	/**
	 * @param mixed $api_token
	 *
	 * @return Stamp_IC_WC_Api_Client
	 */
	public function set_api_token( $api_token ): Stamp_IC_WC_Api_Client {
		$this->api_token = $api_token;
		return $this;
	}

	public function save_wc_credentials( array $params ) {

		$response = wp_remote_post(
			$this->get_api_url( 'api/instant-checkout/installations' ),
			array(
				'body' => wp_json_encode( $params ),
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-IC-AppKey' => $this->get_api_token()
				),
				'data_format' => 'body',
				'timeout' => 45
			)
		);

		$http_code = wp_remote_retrieve_response_code( $response );

		if( $http_code > 200 ) {

			$message = wp_remote_retrieve_response_message( $response );

			error_log( $message );

			return array(
				'error' => true,
				'code' => $http_code,
				'message' => $message,
			);
		}

		return wp_remote_retrieve_body( $response );
	}

	public function verify( array $params = array() ) {

        $response = wp_remote_get(
	        $this->get_api_url( 'api/instant-checkout/installations', $params ),
	        array(
	        	'headers' => array(
			        'Content-Type' => 'application/json',
			        'X-IC-AppKey' => $this->get_api_token()
		        )
	        )
        );

		$http_code = wp_remote_retrieve_response_code( $response );

		if( $http_code > 200 ) {

			$message = wp_remote_retrieve_response_message( $response );

			error_log( $message );

			return array(
				'error' => true,
				'code' => $http_code,
				'message' => $message,
			);
		}

		return wp_remote_retrieve_body( $response );
    }
}
