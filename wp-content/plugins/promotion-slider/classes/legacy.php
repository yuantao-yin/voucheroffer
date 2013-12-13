<?php

if ( !class_exists('ps_legacy') ){

	class ps_legacy {
	
		public static function change_legacy_post_type(){
            /**
             * @var wpdb $wpdb
             */
            global $wpdb; // Namespace the post type rather than keeping the generic name
			$wpdb->query("UPDATE ".$wpdb->posts." SET post_type = 'ps_promotion' WHERE post_type = 'promotion'");
			$wpdb->query("UPDATE ".$wpdb->posts." SET post_type = 'ps_promotion' WHERE post_type = ''");
		}
		
		public static function remove_legacy_options(){
			delete_option('time_delay');
			delete_option('auto_advance');
			delete_option('show_title');
			delete_option('show_excerpt');
			delete_option('nav_option');
			delete_option('disable_fancy_title');
			delete_option('disable_fancy_nav');
		}
	
	}

}