<?php
/*
 * WordPress Custom Post Types
 *
 * Contains the wordpress_custom_post_type class. Requires PHP version 5+ and WordPress version 2.9 or greater.
 *
 * @version 1.0.1
 * @author Micah Wood
 * @copyright Copyright (c) 2011 - Micah Wood
 * @license GPL 3 - http://www.gnu.org/licenses/gpl.txt
 */

if ( !class_exists('wordpress_custom_post_type') ) {
    
    /*
     * WordPress Custom Post Type Class
     *
     * A class that handles the registration of WordPress custom post types and takes care of all the
     * dirty work for you.
     *
     * @package WordPress Custom Post Types
     */
    class wordpress_custom_post_type {

        /*
         * Stores the post type name. Maximum of 20 characters and cannot contain capital letters or spaces.
         *
         * @since 1.0.0
         * @var string
         * @access private
         */
        private $post_type;

        /*
         * Stores the singular label for the post type.
         *
         * @since 1.0.0
         * @var string
         * @access private
         */
        private $singular;

        /*
         * Stores the plural label for the post type.
         *
         * @since 1.0.0
         * @var string
         * @access private
         */
        private $plural;

        /*
         * Stores the slug to be used for this post type.
         *
         * @since 1.0.0
         * @var string
         * @access private
         */
        private $slug;

        /*
         * Stores the textdomain to be used for post type labels, if set.
         *
         * @since 1.0.0
         * @var string
         * @access private
         */
        private $textdomain;

        /*
         * Stores the optional array of arguments to be passed to register_post_type().
         *
         * @since 1.0.0
         * @var array
         * @access private
         */
        private $args;

        /*
         * Constructor - Sets class properties and attaches methods to proper WordPress hooks.
         *
         * Optional $options contents:
         *
         * - singular - Singular label for this post type.
         * - plural - Plural label for this post type.
         * - slug - Slug prepended to post of this post type to customize the permastruct.
         * - textdomain - The textdomain used for localization, if any.
         * - args - An array containing values to be passed to the WordPress register_post_type() function.
         *
         * @since 1.0.0
         * @param string $post_type The the post_type being registered.
         * @param string $file Optional. Path to the main plugin file where this class is used.
         * @param array $options Optional. See above description.
         */
        function __construct( $post_type, $options = array() ){
            // WordPress doesn't accept a post type if it is over 20 characters in length
            if( strlen($post_type) > 20 ){
                wp_die('The post type "'.$post_type.'" is over 20 characters in length. Please shorten it!');
            }

            // Extract optional values
            $singular = $plural = $slug = $textdomain = $file = $args = FALSE;
            extract( $options, EXTR_IF_EXISTS );

            // Register activation hook, if file path is provided, to properly flush rewrite rules
            if( $file ){
                register_activation_hook( $file, array( $this, 'activation' ) );
                register_deactivation_hook( $file, array( $this, 'deactivation' ) );
            }

            // Set class properties
            $this->post_type = $post_type;
            $this->singular = ( $singular ) ? $singular : ucfirst($this->post_type) ;
            $this->plural = ( $plural ) ? $plural : $this->singular.'s';
            $this->slug = ( $slug ) ? $this->create_slug($slug) : false;
            $this->textdomain = $textdomain;
            $this->args = $args;

            // Add rewrite rules for versions of WordPress older than 3.1
            global $wp_version;
            if( version_compare( $wp_version, '3.1', '<') ){
                add_filter( 'generate_rewrite_rules', array( $this, 'add_rewrite_rules' ) );
                // Issue error message if WordPress version is too old to support custom post types
                if( version_compare( $wp_version, '2.9', '<') ){
                    die('WordPress custom post types are not supported prior to version 2.9');
                }
            }

            // Register post type
            add_action( 'init', array( $this, 'register_post_type' ) );

            // Change how templates are pulled for this post type
            add_filter( 'template_include', array( $this, 'template_include' ) );

            // Add canonical link to head
            add_action( 'wp_head', array( $this, 'add_canonical_link' ) );

            // Add body classes for this post type
            add_filter( 'body_class', array( $this, 'body_class' ) );
        }

        /*
         * Flushes rewrite rules on activation to prevent 404 errors for this post type.
         *
         * You should only flush rewrite rules on plugin activation, not on init or any other hook that gets run
         * frequently as this can severely impact the performance of the site.
         *
         * Failure to flush rewrite rules immediately after the post type is registered will result in 'page not found'
         * errors when visiting pages related to this post type.  Visiting the permalinks page in WordPress will flush
         * the rewrite rules and fix this issue, but is not a viable option for publicly released plugins.  If you use
         * this class outside of a plugin, or fail to set the plugin file path, it will be necessary to visit the
         * permalinks page to get things working properly.
         *
         * @since 1.0.0
         * @access private
         */
        function activation(){
            $this->register_post_type();
            flush_rewrite_rules();
        }

        /*
         * Flushes rewrite rules on deactivation to remove rules for this post type.
         *
         * @since 1.0.0
         * @access private
         */
        function deactivation(){
            flush_rewrite_rules();
        }

        /*
         * Creates some smart defaults from user input, merges these with passed arguments and registers
         * the post type in WordPress.
         *
         * @since 1.0.0
         * @access private
         */
        function register_post_type( ){
            // Default array of arguments for post type
            $defaults = array(
                'labels' => array(
                    'name' => $this->plural,
                    'singular_name' => $this->singular,
                    'add_new' => $this->__( 'Add New ' ) . $this->singular,
                    'add_new_item' => $this->__( 'Add New ' ) . $this->singular,
                    'edit_item' => $this->__( 'Edit ' ) . $this->singular,
                    'new_item' => $this->__( 'New ' ) . $this->singular,
                    'view_item' => $this->__( 'View ' ) . $this->singular,
                    'search_items' => $this->__( 'Search ' ) . $this->plural,
                    'not_found' => sprintf( $this->__( 'No %s found' ), $this->plural ),
                    'not_found_in_trash' => sprintf( $this->__( 'No %s found in Trash' ), $this->plural )
                ),
                'public' => true,
                'has_archive' => true,
            );

            // If a user specifies a slug, use it
            if( $this->slug !== false ){
                $defaults['rewrite'] = array( 'slug' => $this->slug );
            }

            // Merge default arguments with passed arguments
            $args = wp_parse_args( $this->args, $defaults );

            // Allow filtering of post type arguments
            $args = apply_filters( 'wp_'.$this->post_type.'_post_type_args', $args );

            // Register the post type
            register_post_type( $this->post_type, $args );
        }

        /*
         * Customizes how which templates are used for our custom post type and in what order.
         *
         * @since 1.0.0
         * @param string $template The name of the template passed to this filter from WordPress.
         * @access private
         */
        function template_include( $template ) {
            // If our post type is being called, customize how the templates are pulled
            if ( get_query_var( 'post_type' ) == $this->post_type ) {
                if( is_page() ){ // PAGE
                    $page = locate_template( array( $this->post_type . '/page.php', 'page-' . $this->post_type . '.php' ) );
                    if ( $page )
                        return $page;
                } elseif( is_single() ){ // SINGLE
                    $single = locate_template( array($this->post_type.'/single.php') );
                    if ( $single )
                        return $single;
                } else{ // LOOP
                    return locate_template( array($this->post_type.'/index.php', $this->post_type.'.php', 'index.php'));
                }
            }
            return $template;
        }

        /*
         * Adds a canonical link when on a posts page for our post type.
         *
         * @since 1.0.0
         * @access private
         */
        function add_canonical_link(){
            if( get_query_var('post_type') == $this->post_type && !is_single() ){
                echo '<link rel="canonical" href="'.get_bloginfo('url').'/'.$this->slug.'/"/>';
            }
        }

        /*
         * Adds post type related classes to the <body> element when on a page related to our post type.
         *
         * @since 1.0.0
         * @param array $classes An array of classes passed to this filter by WordPress.
         * @access private
         */
        function body_class( $classes ){
            // If our post type is being called, add our classes to the <body> element
            if ( get_query_var('post_type') === $this->post_type ) {
                $classes[] = $this->post_type;
                $classes[] = 'type-' . $this->post_type;
            }
            return $classes;
        }

        /*
         * Handles rewrite rules for our custom post type if we are not using WordPres 3.1 or greater
         *
         * @since 1.0.0
         * @param array $wp_rewrite An array containing rewrite rules passed to this filter by WordPress.
         * @access private
         */
        function add_rewrite_rules( $wp_rewrite ) {
            // Add rewrite rules that allow our post type URLs to work properly
            $new_rules = array();
            // This rewrite rule is not necessary in WP 3.1 because of the has_archive argument
            $new_rules[$this->slug . '/?$'] = 'index.php?post_type=' . $this->post_type;
            // This rewrite rule is not necessary in WP 3.1 because of the rewrite->feeds argument
            $new_rules[$this->slug . '/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?post_type=' . $this->post_type . '&feed=' . $wp_rewrite->preg_index(1);
            // This rewrite rule is not necessary in WP 3.1 due to the rewrite->pages argument
            $new_rules[$this->slug . '/page/?([0-9]{1,})/?$'] = 'index.php?post_type=' . $this->post_type . '&paged=' . $wp_rewrite->preg_index(1);
            $wp_rewrite->rules = array_merge($new_rules, $wp_rewrite->rules);
            return $wp_rewrite;
        }

        /*
         * Converts text strings into lowercase and replaces spaces with dashes.
         *
         * @since 1.0.1
         * @param string $slug The text string that will be converted to a slug.
         * @access private
         */
        function create_slug( $slug ){
            return preg_replace('/\s/', '-', strtolower($slug));
        }

        /*
         * A wrapper function for the WordPress function by the same name.  Optionally adds the textdomain, if provided,
         * to the translation function.
         *
         * @since 1.0.0
         * @param string $text The text string that you wish to pass to the WordPress translation function.
         * @access private
         */
        function __( $text ){
            if( $this->textdomain ){
                return __( $text, $this->textdomain );
            } else {
                return __( $text );
            }
        }

    }

}