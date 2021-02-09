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
	public function set_id( $id ): Stamp_IC_WC_Settings_Notification {
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
	public function set_type( $type ): Stamp_IC_WC_Settings_Notification {

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
	public function set_message( $message ): Stamp_IC_WC_Settings_Notification {
		$this->message = $message;
		return $this;
	}

	public function is_error(): bool {
		return $this->get_type() === static::ERROR;
	}

	public function is_success(): bool {
		return $this->get_type() === static::SUCCESS;
	}

	public function is_warning(): bool {
		return $this->get_type() === static::WARNING;
	}
}
