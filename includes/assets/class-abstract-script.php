<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

abstract class Stamp_IC_WC_Abstract_Script {

    abstract public function name(): string;

    abstract public function url(): string;

    abstract public function deps(): array;

    public function in_footer(): bool {
        return true;
    }

    public function version(): string {
        return STAMP_IC_WC_VERSION;
    }

    public function screens(): array {
	    return array();
    }

    public function data( array $params = array() ): array {
        return array();
    }
}
