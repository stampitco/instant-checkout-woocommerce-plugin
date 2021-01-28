<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

abstract class Stamp_IC_WC_Abstract_Style {

    abstract public function name(): string;

    abstract public function url(): string;

	abstract public function should_enqueue(): bool;

    public function version(): string {
        return STAMP_IC_WC_VERSION;
    }

    public function media(): string {
        return 'all';
    }

    public function deps(): array {
        return array();
    }
}
