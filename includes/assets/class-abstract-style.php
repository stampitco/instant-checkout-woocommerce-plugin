<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

abstract class Stamp_IC_WC_Abstract_Style {

    abstract public function name();

    abstract public function url();

	abstract public function should_enqueue();

    public function version() {
        return STAMP_IC_WC_VERSION;
    }

    public function media() {
        return 'all';
    }

    public function deps() {
        return array();
    }
}
