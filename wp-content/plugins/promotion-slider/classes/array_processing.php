<?php

if ( ! class_exists('promoslider_array_processing') ){

	class promoslider_array_processing{
	
		public static function array_cleanup( $array ){
			// Filter out empty elements in an array, return false if array is already empty
			return ( empty( $array ) ) ? false : array_filter( $array, array( __CLASS__, 'delete_empty_elements') );
		}
		
		public static function array_prefix( $array, $prefix, $sep = false ){
			// Prefix all classes
			array_walk( $array,  array( __CLASS__, 'prepend_to_element' ), array( 'prefix' => $prefix, 'sep' => $sep ) );
			return $array;
		}
		
		public static function array_to_class( $array ){
			// Clean up array
			$array = self::array_cleanup( $array );
			// End early if array is empty
			if ( empty( $array ) )
				return false;
			// Implode array into space separated list and package with class attribute
			return ' class="' . implode( ' ', $array ) . '" ';
		}
		
		public static function array_to_style( $array ){
			// Clean up array
			$array = self::array_cleanup( $array );
			// End early if array is empty
			if( empty( $array ) )
				return false;
			// Perform associative array to css transformation
			array_walk( $array, array( __CLASS__, 'array_css_transform' ) );
			// Implode array into space separated list and package with style attribute
			return ' style="' . implode( ' ', $array ) . '" ';
		}
		
		private static function delete_empty_elements( $value ){
			// Return false if element is empty, otherwise return $value
			return ( empty( $value ) ) ? false : $value;
		}
		
		private static function prepend_to_element( &$value, $key, $array ){
			extract( $array );
			// Add the prefix, only if the prefix doesn't already exist
			$value = ( preg_match( '/^' . $prefix . '/i', $value ) ) ? $value : $prefix . $sep . $value;
		}
		
		private static function array_css_transform( &$value, $key ){
			$value =  $key . ':' . $value . ';';
		}
	
	}

}