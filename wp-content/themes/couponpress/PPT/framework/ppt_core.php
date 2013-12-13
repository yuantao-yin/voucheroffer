<?php

/**
 * Default constants available throughout the Framework.
 *
 * @since 0.3.0
 *
 * @return void
 */
function ppt_initial_constants() {

	// sets the name of the PremiumPress theme
	define( 'HOME_URI', get_home_url() );	
	
	// sets the name of the PremiumPress theme
	define( 'PPT_THEME', strtolower(PREMIUMPRESS_SYSTEM) );	
 
	// Sets the File path for the theme installation
	define( 'PPT_THEME_DIR', TEMPLATEPATH );	 
 
	// Sets the File path for the theme installation
	define( 'PPT_THEMES_DIR', TEMPLATEPATH . '/themes/');
	
		// Sets the URI path to the theme installation
	define( 'PPT_THEME_URI', get_template_directory_uri() );
	
	// Sets the file path to PremiumPress Framework folder(s)
	define( 'PPT_FW', PPT_THEME_DIR . '/PPT/' );
 
		// Sets the file path to framework files ()
		define( 'PPT_FW_CLASS', PPT_THEME_DIR . '/PPT/class/' );

		// Sets the file path to framework files ()
		define( 'PPT_FW_AJAX', PPT_THEME_DIR . '/PPT/ajax/' );

			// Sets the file path to framework files ()
			define( 'PPT_FW_AJAX_URI', PPT_THEME_URI . '/PPT/ajax/' );
		
		// Sets the file path to framework files ()
		define( 'PPT_FW_JS', PPT_THEME_DIR . '/PPT/js/' );

			// Sets the file path to framework files ()
			define( 'PPT_FW_JS_URI', PPT_THEME_URI . '/PPT/js/' );
		
		// Sets the file path to framework files ()
		define( 'PPT_FW_WP', PPT_THEME_DIR . '/PPT/wordpress/' );		

		// Sets the file path to framework files ()
		define( 'PPT_FW_CSS', PPT_THEME_DIR . '/PPT/css/' );
		
			// Sets the file path to framework files ()
			define( 'PPT_FW_CSS_URI', PPT_THEME_URI . '/PPT/css/' );

		// Sets the file path to framework files ()
		define( 'PPT_FW_GATEWAYS', PPT_THEME_DIR . '/PPT/gateways/' );

		// Sets the file path to framework files ()
		define( 'PPT_FW_FUNCTION', PPT_THEME_DIR . '/PPT/func/' );
		
		// Sets the file path to framework files ()
		define( 'PPT_FW_IMG', PPT_THEME_DIR . '/PPT/img/' );
			
			// Sets the file path to framework files ()
			define( 'PPT_FW_IMG_URI', PPT_THEME_URI . '/PPT/img/' );				
			
		// Sets the file path to framework files ()
		define( 'PPT_THUMBS', PPT_THEME_DIR . '/thumbs/' );
			
			// Sets the file path to framework files ()
			define( 'PPT_THUMBS_URI', PPT_THEME_URI . '/thumbs/' );		
	
	// OLD THEME SETTING FOR OLDER CHILD THEME SUPPORT	
	define('PPT_PATH',PPT_THEME_URI.'/PPT/');
	define('THEME_PATH',PPT_THEME_DIR."/"); 
		
			 
}

/**
 * Templating constants that you can override before the Framework is loaded.
 *
 * @since 0.3.0
 *
 * @return void
 */
function ppt_templating_constants() {

	global $wpdb;
	
	$CHILDTHEME = get_option('theme');
	if(strlen($CHILDTHEME) < 2){ $CHILDTHEME = strtolower(PREMIUMPRESS_SYSTEM)."_default"; } 
	
	 

	// Sets relative paths for the default directories/paths
	if ( !defined( 'PPT_CHILD_DIR' ) )
		define( 'PPT_CHILD_DIR', PPT_THEME_DIR.'/template_'.strtolower(PREMIUMPRESS_SYSTEM).'/' );

 
	// Sets relative paths for the default directories/paths
	if ( !defined( 'PPT_CHILD_URL' ) )
		define( 'PPT_CHILD_URL', PPT_THEME_URI.'/template_'.strtolower(PREMIUMPRESS_SYSTEM).'/' );

 
	// Sets relative paths for the default directories/paths
	if ( !defined( 'PPT_CHILD_IMG' ) )
		define( 'PPT_CHILD_IMG', PPT_THEME_URI.'/template_'.strtolower(PREMIUMPRESS_SYSTEM).'/images/' ); 
 
 	// Sets relative paths for the default directories/paths
	if ( !defined( 'PPT_CHILD_JS' ) )
		define( 'PPT_CHILD_JS', PPT_THEME_URI.'/template_'.strtolower(PREMIUMPRESS_SYSTEM).'/js/' ); 


 
 	// Sets relative paths for the child theme
	if ( !defined( 'PPT_CUSTOM_CHILD_URL' ) )
		define( 'PPT_CUSTOM_CHILD_URL', PPT_THEME_URI.'/themes/'.$CHILDTHEME.'/' ); 

 	// Sets relative paths for the child theme
	if ( !defined( 'PPT_CUSTOM_CHILD_DIR' ) )
		define( 'PPT_CUSTOM_CHILD_DIR', PPT_THEME_DIR.'/themes/'.$CHILDTHEME.'/' ); 
		
		

	// OLD THEME SETTING FOR OLDER CHILD THEME SUPPORT	
	define('IMAGE_PATH',PPT_CHILD_IMG);

}

 
// SETUP WORDPRESS SCHEDULES
function ppt_event_activation() {
		
			if ( !wp_next_scheduled( 'ppt_hourly_event' ) ) {
				wp_schedule_event(time(), 'hourly', 'ppt_hourly_event');
			}	
			if ( !wp_next_scheduled( 'ppt_twicedaily_event' ) ) {		
				wp_schedule_event(time(), 'twicedaily', 'ppt_twicedaily_event');
			}	
			if ( !wp_next_scheduled( 'ppt_daily_event' ) ) {	
				wp_schedule_event(time(), 'daily', 'ppt_daily_event');		
			}
}
		 
		//print date('l jS \of F Y h:i:s A',wp_next_scheduled( "ppt_hourly_event"))."<br><br>".date('l jS \of F Y h:i:s A',wp_next_scheduled( "ppt_twicedaily_event"))."<br><br>".date('l jS \of F Y h:i:s A',wp_next_scheduled( "ppt_daily_event")); 
		
		function do_this_event_hourly() { 		global $PPTImport; $PPTImport->IMPORTSWITCH('hourly');	}		
		function do_this_event_twicedaily() { 	global $PPTImport; $PPTImport->IMPORTSWITCH('twicedaily');	}
		function do_this_event_daily() { 		global $PPTImport; $PPTImport->IMPORTSWITCH('daily');	}	

function ppt_body_class() {
	
	global $post; $c = "";
	
	if(isset($GLOBALS['GALLERYPAGE'])){ $c = 'id="PPTGalleryPage"'; }elseif(is_home()){ $c = 'id="PPTHomePage"'; }elseif(is_single()){ $c = 'id="PPTSinglePage-'.$post->post_type.'"'; }elseif(is_page()){ $c = 'id="PPTPage"'; }
	
	echo $c;
}

/**
 * Returns an array of contextual data based on the requested page.
 * It does this by running through all the WordPress conditional tags
 * and for every condition that is true, it the function adds contextual data
 * specific to that condition into the array and finally returns it.
 *
 * @link http://codex.wordpress.org/Conditional_Tags/
 *
 * @since 0.3.0
 * @global $wp_query The current page's query object.
 * @global $ppt_theme The global Theme object.
 * @return Array Returns an array of contexts based on the query.
 */
function ppt_get_request() {
	// The query isn't parsed until wp, so bail if the function is called before.
	if ( !did_action( 'wp' ) )
		return false;

	global $wp_query, $ppt_theme;

	if ( isset($ppt_theme->request) && !empty($ppt_theme->request) )
		return $ppt_theme->request;

	/* Front page of the site. */
	if ( is_front_page() )
		$request[] = 'front_page';

	/* Blog page. */
	if ( is_home() )
		$request[] = 'home';

	/* Singular views. */
	elseif ( is_singular() ) {
		$request[] = 'singular';

		if ( ppt_is_subpage() )
			$request[] = 'subpage';

		$request[] = 'post_type_' . $wp_query->post->post_type;		
		$request[] = 'post_type_' . $wp_query->post->post_type . '_' . str_replace( '-', '_', $wp_query->post->post_name );
	}

	/* Archive views. */
	elseif ( is_archive() ) {
		$request[] = 'archive';

		/* Taxonomy archives. */
		if ( is_tax() || is_category() || is_tag() ) {
			$term = $wp_query->get_queried_object();
			$request[] = 'taxonomy';
			$request[] = 'taxonomy_' . $term->taxonomy;
			$request[] = 'taxonomy_' . "{$term->taxonomy}_" . sanitize_html_class( $term->slug, $term->term_id );
		}

		/* User/author archives. */
		elseif ( is_author() ) {
			$request[] = 'user';
			$request[] = 'user_' . sanitize_html_class( get_the_author_meta( 'user_nicename', get_query_var( 'author' ) ), $wp_query->get_queried_object_id() );
		}

		/* Date archives. */
		else {
			if ( is_date() ) {
				$request[] = 'date';
				if ( is_year() )
					$request[] = 'year';
				if ( is_month() )
					$request[] = 'month';
				if ( get_query_var( 'w' ) )
					$request[] = 'week';
				if ( is_day() )
					$request[] = 'day';
			}
		}
	}

	/* Search results. */
	elseif ( is_search() )
		$request[] = 'search';
	
	elseif ( is_feed() )
		$request[] = 'feed';
	
	elseif ( is_multisite() )
		$request[] = 'multisite';

	/* Error 404 pages. */
	elseif ( is_404() )
		$request[] = '404';

	$ppt_theme->request = apply_filters( 'ppt_request', $request );

	return $ppt_theme->request;
}

/**
 * Returns true if a post is the subpage of a post.
 *
 * @since 0.3.0
 *
 * @param string $post Optional. Post id, or object.
 * @return bool true if the post is a subpage, false if not.
 */
function ppt_is_subpage( $post = null ) {
	$post = get_post( $post );

	if ( is_page() && $post->post_parent )
		return $post->post_parent;

	return false;
}

 

/**
 * Retrieves the theme framework class and initalises it.
 *
 * @since 0.3.0
 * @uses ppt_get_class()
 *
 * @return object $ppt_theme class
 */
function PPT() {

	global $ppt_classes;

	$theme_class = ppt_get_class( 'theme' );

	return $ppt_classes['theme'] = new $theme_class;
}

 

/**
 * Registers a WP Framework class.
 *
 * @since 0.3.0
 *
 * @param string $handle Name of the api.
 * @param string $class The class name.
 * @return string The name of the class registered to the handle.
 */
function ppt_register_class( $handle, $class, $autoload = false ) {

	global $ppt_classes;

	$type = $autoload ? 'autoload' : 'static';

	$ppt_classes[$type][$handle] = $class;

	return $ppt_classes[$type][$handle];
}

/**
 * Registers a contextual Framework class.
 * Contextual classes will get loaded after the 'wp' action is fired.
 *
 * @since 0.3.0
 * @see ppt_load_contextual_classes()
 * @see ppt_get_request()
 *
 * @param string $handle Name of the api.
 * @param string $class The contextual class name.
 * @return string The name of the class registered to the handle.
 */
function ppt_register_contextual_class( $handle, $class ) {
	global $ppt_classes;

	$ppt_classes['contextual'][$handle] = $class;

	return $ppt_classes['contextual'][$handle];
}

/**
 * Registers an admin class in WP Framework.
 * An admin class allows you to create administrative pages in WordPress.
 *
 * @since 0.3.0
 * @see class ppt_Admin
 * @see class ppt_Admin_Metabox
 * @uses ppt_load_admin_pages()
 *
 * @param string $handle Identifier for the admin class.
 * @param string $class The admin class name.
 * @return string The name of the class registered to the handle.
 */
function ppt_register_admin_class( $menu_slug, $class ) {
	global $ppt_classes;

	$ppt_classes['admin'][$menu_slug] = $class;

	return $ppt_classes['admin'][$menu_slug];
}

/**
 * Retrieves a registered WP Framework class.
 *
 * @since 0.3.0
 *
 * @param string $class The class handler
 * @return string The name of the class registered to the handler.
 */
function ppt_get_class( $class ) {
	global $ppt_classes;
	
	if ( isset($ppt_classes[$class]) )
		return $ppt_classes[$class];
	
	if ( isset($ppt_classes['admin'][$class]) )
		return $ppt_classes['admin'][$class];
	
	if ( isset($ppt_classes['static'][$class]) )
		return $ppt_classes['static'][$class];
	
	if ( isset($ppt_classes['autoload'][$class]) )
		return $ppt_classes['autoload'][$class];
	
	if ( isset($ppt_classes['contextual'][$class]) )
		return $ppt_classes['contextual'][$class];
	
	return false;
}

/**
 * Loops through all the registered autoloaded classes and instantiates them.
 *
 * @since 0.3.0
 * 
 * @return void
 */
function ppt_autoload_classes() {
	global $ppt_classes;

	if ( isset( $ppt_classes['autoload'] ) ) {
		foreach ( (array) $ppt_classes['autoload'] as $handle => $class ) {
			if ( !isset($ppt_classes[$handle]) ) {
				$ppt_classes[$handle] = new $class;
			}
		}
	}
}

/**
 * Loops through all the registered contextual classes and attempts to call 
 * classs methods based on ppt_get_request().
 *
 * @since 0.3.0
 * 
 * @return void
 */
function ppt_load_contextual_classes() {

	global $ppt_classes, $ppt_theme;

	if ( isset($ppt_classes['contextual']) && !empty( $ppt_classes['contextual'] ) ) {
		$methods = array();

		// Get the context, but not in the admin.
		if ( !is_admin() ) {
			$context = array_reverse( (array) ppt_get_request() );

			if ( !empty($context) ) {
				foreach ( $context as $method ) {
					$methods[] = str_replace( '-', '_', $method );
				}
			}
		}

		foreach ( (array) $ppt_classes['contextual'] as $handle => $class ) {
			if ( isset($ppt_classes[$handle]) )
				continue;

			// Call the admin method if we're in the admin area.
			if ( is_admin() ) {
				$ppt_theme->callback( $ppt_classes['contextual'][$handle], 'admin' );

			} else {

				// Call the constructor method if we're not in the admin,
				// pass all the methods that are valid for this page request.
				$ppt_classes[$handle] = new $class( $methods );
			}

			// Call all the contextual methods.
			if ( !empty( $methods ) ) {
				foreach( $methods as $method ) {
					$ppt_theme->callback( $ppt_classes[$handle], $method );
				}
			}
		}
	}
}

/**
 * Loops through all the registered admin pages and attempts to call 
 * classs methods based on ppt_get_request().
 *
 * @since 0.3.0
 * 
 * @return void
 */
function ppt_load_admin_pages() {
	if ( !is_admin() )
		return;

	global $ppt_classes;

	if ( isset($ppt_classes['admin']) && !empty($ppt_classes['admin']) ) {
		foreach ( $ppt_classes['admin'] as $handle => $class ) {
			if ( !isset($ppt_classes[$handle]) ) {
				$ppt_classes[$handle] = new $class;
			}
		}
	}
} 
 

?>