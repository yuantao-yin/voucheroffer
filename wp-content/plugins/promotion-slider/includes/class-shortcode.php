<?php

class Promotion_Slider_Shortcode {

	protected $atts, $content, $output = '';

	public static function shortcode( $atts, $content = false ) {
		$shortcode = new self( $atts, $content );
		return $shortcode->output;
	}

	private function __construct( $atts, $content ) {
		$this->atts = shortcode_atts( array(
			'id' => false,
			'width' => false,
			'height' => false,
			'post_type' => 'ps_promotion',
			'taxonomy' => 'promotion-categories',
			'term' => false,
			'slider' => false, // Alias for term
			'numberposts' => -1,
			'start_on' => promotion_slider( 'option', 'start_on', 'first' ),
			'auto_advance' => promotion_slider( 'option', 'auto_advance', true ),
			'time_delay' => promotion_slider( 'option', 'time_delay', 6 ),
			'display_nav' => promotion_slider( 'option', 'display_nav', true ),
			'display_title' => promotion_slider( 'option', 'display_title', true ),
			'display_excerpt' => promotion_slider( 'option', 'display_excerpt', true ),
			'pause_on_hover' => promotion_slider( 'option', 'pause_on_hover', false ),
		), $atts );
		$this->validate_atts();
		$this->content = $content;
		$this->init();
	}

	protected function validate_atts() {
		// Slider is an alias for term, but is only used when term is empty and we are on the built-in taxonomy
		if( $this->atts['slider'] && ! $this->atts['term'] && 'promotion-categories' == $this->atts['taxonomy'] ) {
			$this->atts['term'] = $this->atts['slider'];
		}
		// Slider isn't actually a valid option since it is just an alias
		unset( $this->atts['slider'] );
		// Validate each attribute / option to ensure we are working with clean data
		foreach( $this->atts as $key => $value ) {
			$method = "_validate__{$key}";
			if( method_exists( $this, $method ) && is_callable( array( $this, $method ) ) ) {
				$this->atts[$key] = $this->$method( $value );
			} else {
				$this->atts[$key] = Promotion_Slider_Options::validate_option( $key, $value );
			}
		}
	}

	protected static function _validate__numberposts( $value ) {
		return ( $value = intval( $value ) ) ? $value : -1;
	}

	protected static function _validate__post_type( $value ) {
		return post_type_exists( $value ) ? $value : 'ps_promotion';
	}

	protected static function _validate__taxonomy( $value ) {
		return taxonomy_exists( $value ) ? $value : 'promotion-categories';
	}

	protected static function _validate__term( $value ) {
		return term_exists( $value ) ? $value : false;
	}

	protected function init() {
		$query = $this->get_query();
		if( $query->have_posts() ) {
			$id = $this->get_html_att( 'id', $this->get_att('id') );
			$class = $this->get_html_att( 'class', 'promo_slider_wrapper' );
			$width = ( $w = $this->get_att('width') ) ? "width: {$w};" : '';
			$height = ( $h = $this->get_att( 'height' ) ) ? "height: {$h};" : '';
			$style = $this->get_html_att( 'style', "{$width} {$height}" );
			echo "<div{$id}{$class}>";
			echo "<div class=\"promo_slider\"{$style}>";
			while( $query->have_posts() ) {
				$query->the_post();
			}
			echo '</div>';
			echo '</div>';
			wp_reset_postdata();
		}
	}

	protected function get_query() {
		$query_args = array(
			'post_type' => $this->get_att( 'post_type' ),
			'post_status' => 'publish',
			'numberposts' => $this->get_att( 'numberposts' ),
		);
		$tax = $this->get_att('taxonomy');
		$term = $this->get_att('term');
		if( $tax && $term ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => $tax,
					'field' => 'slug',
					'terms' => $term,
				),
			);
		}
		return new WP_Query( apply_filters( 'promoslider_query_args', $query_args, $this->atts ) );
	}

	protected function get_att( $name ) {
		return array_key_exists( $name, $this->atts ) ? $this->atts[$name] : false;
	}

	protected function get_html_att( $name, $value ) {
	    return $value ? ' ' . $name . '="' . esc_attr( $value ) . '"' : '';
	}

}