<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

abstract class Stamp_IC_WC_Abstract_Script {

    abstract public function name();

    abstract public function url();

    abstract public function deps();

	abstract public function should_enqueue();

    public function in_footer() {
        return true;
    }

    public function version() {
        return STAMP_IC_WC_VERSION;
    }

    public function data( array $params = array() ) {
        return array();
    }
}
