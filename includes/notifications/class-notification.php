<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Stamp_IC_WC_Settings_Notification {

	const ERROR = 'error';

	const SUCCESS = 'success';

	const WARNING = 'warning';

	protected $id;

	protected $type;

	protected $message;

	/**
	 * Stamp_IC_WC_Settings_Notification constructor.
	 *
	 * @param $id
	 * @param $type
	 * @param $message
	 */
	public function __construct( $id, $type, $message ) {
		$this->set_id( $id );
		$this->set_type( $type );
		$this->set_message( $message );
	}

	/**
	 * @return mixed
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 *
	 * @return Stamp_IC_WC_Settings_Notification
	 */
	public function set_id( $id ) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param mixed $type
	 *
	 * @return Stamp_IC_WC_Settings_Notification
	 */
	public function set_type( $type ) {

		$types = array(
			static::ERROR,
			static::SUCCESS,
			static::WARNING,
		);

		if( ! in_array( $type, $types ) ) {
			throw new InvalidArgumentException(
				sprintf(
					'Invalid type value: %s',
					$type
				)
			);
		}

		$this->type = $type;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * @param mixed $message
	 *
	 * @return Stamp_IC_WC_Settings_Notification
	 */
	public function set_message( $message ) {
		$this->message = $message;
		return $this;
	}

	public function is_error() {
		return $this->get_type() === static::ERROR;
	}

	public function is_success() {
		return $this->get_type() === static::SUCCESS;
	}

	public function is_warning() {
		return $this->get_type() === static::WARNING;
	}

	public function to_array() {
		return array(
			'id' => $this->get_id(),
			'type' => $this->get_type(),
			'message' => $this->get_message(),
		);
	}
}
