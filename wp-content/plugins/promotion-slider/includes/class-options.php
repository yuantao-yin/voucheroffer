<?php

class Promotion_Slider_Options {

	protected static $options;
	protected static $option_name = 'promotion_slider_options';

	public static function get_options(){
		return get_option( self::$option_name, array() );
	}

	public static function get_option( $name, $default = false ) {
	    if( ! isset( self::$options ) ) {
		    self::$options = self::get_options();
	    }
		return self::get_var( self::$options, $name, $default );
	}

	public static function validate_option( $name, $value ) {
		$method = "_validate__{$name}";
		if( method_exists( __CLASS__, $method ) && is_callable( array( __CLASS__, $method ) ) ) {
			return self::$method( $value );
		}
		return false;
	}

	protected static function _validate__auto_advance( $value ) {
		return in_array( $value, array( 'auto_advance', 'no_auto_advance' ) ) ? $value : 'auto_advance';
	}

	protected static function _validate__display_excerpt( $value ) {
		return in_array( $value, array( 'none', 'excerpt' ) ) ? $value : 'none';
	}

	protected static function _validate__default_img_size( $value ) {
		return in_array( $value, array( 'thumbnail', 'medium', 'large', 'full' ) ) ? $value : 'full';
	}

	protected static function _validate__display_nav( $value ) {
		return in_array( $value, array( 'none', 'default', 'fancy', 'links', 'thumb', 'tabbed' ) ) ? $value : 'fancy';
	}

	protected static function _validate__display_title( $value ) {
		return in_array( $value, array( 'none', 'default', 'fancy' ) ) ? $value : 'fancy';
	}

	protected static function _validate__load_js_in( $value ) {
		return in_array( $value, array( 'head', 'footer' ) ) ? $value : 'head';
	}

	protected static function _validate__pause_on_hover( $value ) {
		return in_array( $value, array( 'pause', 'no_pause' ) ) ? $value : 'no_pause';
	}

	protected static function _validate__start_on( $value ) {
		return in_array( $value, array( 'first', 'random' ) ) ? $value : 'first';
	}

	protected static function _validate__time_delay( $value ) {
		return ( $value = absint( $value ) ) ? $value : 6;
	}

	protected static function _validate__version( $value ) {
		return PROMOSLIDER_VER;
	}

	protected static function get_var( $data, $key, $default = false ) {
	    return array_key_exists( $key, $data ) ? $data[$key] : $default;
	}

}