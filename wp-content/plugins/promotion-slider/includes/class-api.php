<?php

class Promotion_Slider_API {

	protected $args = array();

	public function __call( $method, $args = array() ) {
		return ( method_exists( $this, $method ) && is_callable( array( $this, $method ) ) ) ? $this->$method( $args ) : false;
	}

	public function option(array $args ) {
		$name = array_shift( $args );
		$default = ( $value = array_shift( $args ) ) ? $value : false;
	    return Promotion_Slider_Options::get_option( $name, $default );
	}

    function get_var( $data, $key, $default = false ) {
	    return array_key_exists( $key, $data ) ? $data[$key] : $default;
	}

}