<?php
/**
 * The Bread and butter of Framework.
 *
 * @since inception
 */
 

		
class PPT extends PPT_API {
 	
	/**
	 * Front controller that hooks into the template files and bootstraps all
	 * the functionality.
	 *
	 * @since 0.3.0
	 */
	function PPT( $args = array() ) {
	 
	global $pagenow; 
	
		// Enavle PremiumPress Navigation Menu support.	
		add_theme_support('nav_menus');
		register_nav_menu('PPT-CUSTOM-MENU-PAGES', 'Main Navigation Bar');		
		
		// Load custom options for login and register pages
		if ( $pagenow == "wp-login.php"  &&  $_GET['action'] != 'logout' && $_GET['action'] != "rp" && $_GET['action'] != "resetpass" &&  !isset($_GET['key']) ) {			
			add_action('init', array( $this, 'login_init' ) , 98); 
		}		
		//Load custom taxomony
		add_action( 'init', array( $this, 'create_post_type' ) );
		
		// Enable PremiumPress Custom Background support.
		add_custom_background(); 	

		// Magic hook for the admin.
		if ( is_admin() ){
			$this->callback( $this, 'admin' ); // loads the ppt-admin.php 
		}else{
			$this->setup();
 		}
	}  
	

	/**
	 * Hooks all of the core Framework functionality into WordPress
	 * and the template files.
	 *
	 * @since 0.3.0
	 */
	function setup() {
	
	global $PPT, $pagenow; 
	
 		// load the advanced search options
		require_once (PPT_FW_CLASS . 'class_search.php');	
		add_action('init',array('PPT_S','init'));
		add_shortcode( 'premiumpress_search', array(&$this,'process_shortcode') );
		
		// Load the schedule options
		add_action('ppt_hourly_event', 'do_this_event_hourly');
		add_action('ppt_twicedaily_event', 'do_this_event_twicedaily');
		add_action('ppt_daily_event', 'do_this_event_daily');  
 		
		// Load in the filter for search queries
		add_filter('posts_where',   array($PPT, 'query_where'));
		add_filter('posts_join',   array($PPT, 'query_join') );
		add_filter('posts_orderby', array($PPT, 'query_orderby') );
		add_filter('post_limits', array($PPT, 'query_limits') );		

		// Load user area top admin menu bar
		add_action( 'admin_bar_menu', array( $this, 'add_menu_admin_bar' ) ,  70);
		
		// Load all the javascript/styles into the theme wp_head
		add_action( 'wp_loaded', array( $this, '_enqueue_assets' ) ); 
		
		// Load any widgets for the theme
		add_action( 'widgets_init', array( $this, '_widgets_init' ) );
		add_action( 'wp_head', array( $this, '_wp_head' ) );		
		
		// remove actions for theme header
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_generator');
		
		// Load footer objects
		add_action( 'wp_footer', array( $this, '_wp_footer' ) );		

 
	} 

	/**
	 * Customizes login/register displays
	 *
	 * @since 0.3.0
	 */	 
	function login_init() {
	
		require( ABSPATH . '/wp-load.php' );
			
		if (isset($_REQUEST["action"])) {
			$action = $_REQUEST["action"];
		} else {
			$action = 'login';
		}
		
		switch($action) {
			case 'lostpassword' :
			case 'retrievepassword' :
				cp_password();
				break;
			case 'register':
				cp_show_register();
				break;
			case 'login':
			default:
				cp_show_login();
				break;
		}
		die();
	}
		
	
	/**
	 * Customizes the admin display bar 
	 *
	 * @since 0.3.0
	 */	
	function add_menu_admin_bar() {
	
		global $wp_admin_bar;
	 
		if ( !is_super_admin() || !is_admin_bar_showing() )
			exit;
		$wp_admin_bar->add_menu( array( 'id' => 'theme_options', 'title' =>__( constant('PREMIUMPRESS_SYSTEM') , strtolower(constant('PREMIUMPRESS_SYSTEM')) ), 'href' => admin_url('admin.php')."?page=setup" ) ); 		
		$wp_admin_bar->add_menu( array( 'id' => 'pp-updates', 'title' =>__( 'Check for Updates', 'ppt-updates' ), 'href' => "http://www.premiumpress.com/?piwik_campaign=ThemeLink-".strtolower(constant('PREMIUMPRESS_SYSTEM'))."&piwik_kwd=admin-menu-bar") );	
	   
	}
	
	
	/**
	 * This removed the dashbord link from members
	 */	
	function remove_the_dashboard () {
		if (!current_user_can('level_0') || !current_user_can('level_1') || !current_user_can('level_2')) {
			return;
		} else {
	 
		global $menu, $submenu, $user_ID;
				$the_user = new WP_User($user_ID);
				reset($menu); $page = key($menu);
				while ((__('Dashboard') != $menu[$page][0]) && next($menu))
						$page = key($menu);
				if (__('Dashboard') == $menu[$page][0]) unset($menu[$page]);
				reset($menu); $page = key($menu);
				while (!$the_user->has_cap($menu[$page][1]) && next($menu))
						$page = key($menu);
				if (preg_match('#wp-admin/?(index.php)?$#',$_SERVER['REQUEST_URI']) && ('index.php' != $menu[$page][2]))
						wp_redirect(get_option('siteurl') . '/wp-admin/post-new.php');
		}
	}			

 	/**
	 * Magic hook: Define your own after_setup_theme method
	 *
	 * @since 0.3.0
	 */
	function _enqueue_assets($currentPage = "") {
	
	global $post, $pagenow, $page;	
	 
		//wp_deregister_script( 'jquery' );
		//wp_register_script( 'jquery', PPT_THEME_URI.'/PPT/js/jquery.js');
		wp_enqueue_script( 'jquery' );	 // <-- load WP jquery
		
		// Load swfobject for videos
		if(strtolower(PREMIUMPRESS_SYSTEM) == "moviepress"){
		
		 	wp_deregister_script( 'swfobject' );
			wp_register_script( 'swfobject', PPT_CHILD_JS.'swfobject.js');
			wp_enqueue_script( 'swfobject' );		
		}
		 
        
        wp_register_script( 'PPTajax',  PPT_FW_AJAX_URI.'actions.js');
		wp_enqueue_script( 'PPTajax' );	
		
		if($currentPage == "front_page"){	 
	
			// Load the home page full content slider
			if(get_option('PPT_slider') == "s1"){ 
			
				$SLIDERSTYLE = get_option('PPT_slider_style');
				
				if($SLIDERSTYLE == ""){ update_option('PPT_slider_style',"1"); $SLIDERSTYLE=1; } // fix for older versions
				
				echo '<script type="text/javascript" src="'.PPT_THEME_URI.'/PPT/js/sliders/slider'.$SLIDERSTYLE.'.js"></script>';
				echo "<link rel='stylesheet' type='text/css' href='".PPT_THEME_URI."/PPT/js/sliders/css.slider".$SLIDERSTYLE.".css' media='screen' />";
				echo "<link rel='stylesheet' type='text/css' href='".PPT_THEME_URI."/PPT/js/sliders/css.slider".$SLIDERSTYLE.".ie.css' media='screen' />";
				 
			}

		
		}elseif(isset($GLOBALS['tpl-add'])){ 
		
			// Load date options for couponpress
			if(strtolower(PREMIUMPRESS_SYSTEM) == "couponpress" || strtolower(PREMIUMPRESS_SYSTEM) == "jobpress"){
			
				echo "<!--[if IE]><script type='text/javascript' src='".PPT_THEME_URI."/PPT/js/jquery.bgiframe.js'></script><![endif]-->";
				echo "<script type='text/javascript' src='".PPT_THEME_URI."/PPT/js/jquery.date.js'></script>";
				echo "<script type='text/javascript' src='".PPT_THEME_URI."/PPT/js/jquery.date_pick.js'></script>";
				echo "<link rel='stylesheet' type='text/css' media='screen' href='".PPT_THEME_URI."/PPT/css/css.date.css' />";
			
			}
					
			echo "<link rel='stylesheet' type='text/css' href='".PPT_THEME_URI."/PPT/css/css.packages.css' media='screen' />";
			echo "<link rel='stylesheet' type='text/css' href='".PPT_THEME_URI."/PPT/js/jquery.wwiconified1.css' media='screen' />";
			echo "<script type='text/javascript' src='".PPT_THEME_URI."/PPT/js/jquery.wwiconified.js'></script>";	
			echo "<!--[if IE]> <script src='".PPT_THEME_URI."/PPT/js/html5.js'></script> <![endif]-->";	
			
		// Load lightbox into the single page or all pages for couponpress	
		}elseif(   $currentPage == "singular" && strtolower(PREMIUMPRESS_SYSTEM) != "couponpress"  ){ //
			 
			echo "<link rel='stylesheet' type='text/css' href='".PPT_THEME_URI."/PPT/js/lightbox/jquery.lightbox.css' media='screen' />";
			echo "<script type='text/javascript' src='".PPT_THEME_URI."/PPT/js/lightbox/jquery.lightbox.js'></script>";
 
        }  
		
		// Load lightbox into couponpress
		if( ( strtolower(PREMIUMPRESS_SYSTEM) == "couponpress" ||  ( strtolower(PREMIUMPRESS_SYSTEM) == "realtorpress" && isset($GLOBALS['GALLERYPAGE']) ) ) && !isset($_GET['action']) && !isset($_GET['redirect_to']) &&  $currentPage != "" ){
		   ;
			echo "<link rel='stylesheet' type='text/css' href='".PPT_THEME_URI."/PPT/js/lightbox/jquery.lightbox.css' media='screen' />";
			echo "<script type='text/javascript' src='".PPT_THEME_URI."/PPT/js/lightbox/jquery.lightbox.js'></script>";	
				
		}
		
		// LOAD LIGHTBOX INTO ALL PAGES
		/*wp_register_style( 'lightbox', PPT_THEME_URI.'/PPT/js/lightbox/jquery.lightbox.css');
		wp_enqueue_style( 'lightbox' );	
        wp_register_script( 'lightbox',  PPT_THEME_URI.'/PPT/js/lightbox/jquery.lightbox.js');
		wp_enqueue_script( 'lightbox' );*/			
		
		// Load the PremiumPress FrameWork CSS
		wp_register_style( 'PPT1', PPT_THEME_URI.'/PPT/css/css.premiumpress.css');
		wp_enqueue_style( 'PPT1' );	
		
		// Load the PremiumPress Core theme CSS
		wp_register_style( 'PPT2', PPT_CHILD_URL.'styles.css');
		wp_enqueue_style( 'PPT2' );	
		
		// Load the custom child theme CSS
		wp_register_style( 'PPT3', PPT_CUSTOM_CHILD_URL.'css/styles.css');
		wp_enqueue_style( 'PPT3' );			 
		
		if(get_option('display_themecolumns') =="3" && !isset($GLOBALS['nosidebar']) && !isset($GLOBALS['nosidebar-left']) && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"   ){ // 
		 
		// Load the custom child theme CSS
		wp_register_style( 'PPT3olumns', PPT_PATH.'css/css.3columns.css');
		wp_enqueue_style( 'PPT3olumns' );
 
 		} 
		
		
		// Load custom headers for the login page
		if($pagenow == "wp-login.php" && $_GET['action'] != "logout" && $_GET['action'] != "rp" && $_GET['action'] != "resetpass"){
		
			echo "<script type='text/javascript' src='".PPT_THEME_URI."/PPT/js/jquery.js'></script>";
			echo "<link rel='stylesheet' type='text/css' href='".PPT_THEME_URI."/PPT/css/css.premiumpress.css' media='screen' />";
			echo "<link rel='stylesheet' type='text/css' href='".PPT_CHILD_URL."styles.css' media='screen' />";
			echo "<link rel='stylesheet' type='text/css' href='".PPT_CUSTOM_CHILD_URL."css/styles.css' media='screen' />";
			
			if(get_option('display_themecolumns') =="3" && !isset($GLOBALS['nosidebar']) && !isset($GLOBALS['nosidebar-left']) && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"  ){ // 
			echo "<link rel='stylesheet' type='text/css' href='".PPT_PATH."css/css.3columns.css' media='screen' />";
			}
			
		}

	 
	}
 

	/**
	 * Magic hook: Define your own wp_head method
	 *
	 * @since 0.3.0
	 */
	function _wp_head() {
	
	global $PPT, $post;
 
	 // Wordpress doesnt load enqueue options after the int 
	 // so to keep everything togehter we call it after the int has run
	 $f = ppt_get_request();	
	 $this->_enqueue_assets($f[0]);
	  
	 
	
	// Load custom header content for our themes
	switch(strtolower(PREMIUMPRESS_SYSTEM)){
	
	
	case "auctionpress":	
	case "couponpress":	
	case "classifiedstheme":
	case "realtorpress": 
	case "directorypress": { 
		
	 	  
        // Load Google map include
        if(is_single()){ $GLOBALS['mapType'] = get_option("display_googlemaps");   if($GLOBALS['mapType'] == "yes2" && strlen(get_post_meta($post->ID, "map_location", true)) > 2){ ?>
       
            <link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>css/css.maps.css" media="screen" />  
            <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?php echo get_option("google_maps_api"); ?>" type="text/javascript"></script> 
            <script type='text/javascript' src='<?php echo PPT_PATH; ?>js/jquery.maps.js'></script> 
            
                <script type="text/javascript"> 
                    window.onload = function(){
                        var mymap = new MeOnTheMap({
                            container: "map_sidebar2",
                            html: "<?php echo str_replace('"',"",$post->post_title); ?>",
                            address: "<?php echo str_replace('"','',get_post_meta($post->ID, "map_location", true)); ?>",
                            zoomLevel: 13
                        });
         
                        mymap.adjustMapCenter({
                            x: 0,
                            y: -80
                        });
                        
                    } 
                </script>
            
        <?php  } } 
		
		
		
		
		
		if(strtolower(PREMIUMPRESS_SYSTEM) == "realtorpress" && is_home() && isset($GLOBALS['isHome']) ){ 
		
		
		
		$post = query_posts('meta_key=featured&meta_value=yes&posts_per_page=1'); 	 // GETS THE POST LIST 
		
		$mapThis = str_replace('"','',get_post_meta($post[0]->ID, "map_location", true));
		
		?>
		
		
			<link rel="stylesheet" type="text/css" href="<?php echo PPT_PATH; ?>css/css.maps.css" media="screen" />  
			<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=true&amp;key=<?php echo get_option("google_maps_api"); ?>" type="text/javascript"></script> 
			<script type='text/javascript' src='<?php echo PPT_PATH; ?>js/jquery.maps.js'></script> 
			
				<script type="text/javascript"> 
				
					
					<?php if(strlen($mapThis) > 1){ ?>
					window.onload = function(){
					
						var mymap = new MeOnTheMap({
							container: "map_homepage",
							html: "<?php echo str_replace('"',"",$post[0]->post_title); ?><img src='<?php echo $PPT->Image($post[0],"m"); ?>' alt='img' />",
							address: "<?php echo $mapThis; ?>",
							zoomLevel: 13
						});
		 
						mymap.adjustMapCenter({
							x: 0,
							y: -80
						});
						
						}	
					 <?php } ?>
						
									
					
					function ChangeAddress(name,addr,links){
					
					
						var mymap = new MeOnTheMap({
							container: "map_homepage",
							html: "<a href='"+links+"'>"+name+"<\/a>",
							address: addr,
							zoomLevel: 13
						});
		 
						mymap.adjustMapCenter({
							x: 0,
							y: -80
						}); 
					
					}
					
					
				</script>
                
			
		<?php }elseif(strtolower(PREMIUMPRESS_SYSTEM) == "couponpress"){  ?> 
		
		    <script src="<?php echo PPT_CHILD_JS; ?>ZeroClipboard.js"  type="text/javascript"></script>
			<script language="javascript" type="text/javascript">
            
            function show_tool_tip ( copon_id )
            {
                jQuery("#coupon_Tool_tip_action_"+copon_id).show() ;
            }
            
            function hide_tool_tip ( copon_id )
            {
                jQuery("#coupon_Tool_tip_action_"+copon_id).hide() ;
            }
            
            function copy_coupon_and_go_to_site ( coupon_link )
            {
                window.open ( coupon_link , "_blank" ) ;
            }
        
            function set_copy_command ( text_to_copy , control_id , coupon_id, coupon_link )
            {
        
                if ( navigator.userAgent.indexOf("MSIE") > -1 ) 
                {
                    jQuery("#"+control_id).click ( function ( )
                    {
                        window.clipboardData.setData("Text",text_to_copy);
                        copy_coupon_and_go_to_site ( coupon_link ) ;
                    }
                 ) 
                }
                else
                {
                    var clip = new ZeroClipboard.Client();		 
                    ZeroClipboard.setMoviePath ( "<?php echo PPT_CHILD_JS; ?>ZeroClipboard.swf" ) ;
                   
                    clip.setText( text_to_copy ); 
                    clip.setHandCursor( true );
                    clip.addEventListener( 'mouseOver', function(client) {
                                        show_tool_tip ( coupon_id ) ;
                                        clip.setHandCursor( true );
                                } );
                    clip.addEventListener( 'mouseOut', function(client) { 
                                      hide_tool_tip ( coupon_id ) ;
                                } );
                    clip.addEventListener( 'complete', function(client, text) {
                        jQuery("#"+control_id).css("background-color" , "#33CC66");
                        //jQuery("#coupon_Tool_tip_action_"+coupon_id).css("width","60px").text("Copied") ;
                        copy_coupon_and_go_to_site ( coupon_link ) ;
                    } );
                                                            
                    clip.glue( control_id );
                }
            }
        
        </script>
        
       <?php   if($f[0] =="singular" && get_option('display_themecolumns') =="3"){ ?> <style> #PPTSinglePage-post .rightSidebar {width: 315px;}</style><?php } ?>
        
        <?php
		
		}
		
		
	
	
	} break;
	
	} 
	
	
	
	
	


		$F = get_option('faviconLink');	
		
		if(strlen($F) > 5){  ?>
		
		<link rel="shortcut icon" href="<?php echo $F; ?>" type="image/x-icon" />
		 
		<?php }  
		
		// Load the Google Webmaster code 
		echo stripslashes(get_option("google_webmaster_code"));
 
		if(strtolower(PREMIUMPRESS_SYSTEM) == "couponpress"){
 		?> 	
 	 
		<!--[if IE 7]><link rel="stylesheet" href="<?php echo PPT_CHILD_URL; ?>stylesIE7.css" type="text/css" media="screen" /><![endif]-->
		<!--[if IE 8]><link rel="stylesheet" href="<?php echo PPT_CHILD_URL; ?>stylesIE8.css" type="text/css" media="screen" /><![endif]-->
		<!--[if IE 9]><link rel="stylesheet" href="<?php echo PPT_CHILD_URL; ?>stylesIE9.css" type="text/css" media="screen" /><![endif]-->
		<?php
		}
 


 
	 
	}

	/**
	 * Magic hook: Define your own wp_footer method
	 *
	 * @since 0.3.0
	 */
	function _wp_footer() {
	
	global $pagenow;
 
		// Load the content slider
		if(is_front_page()){  ?>        
		
        <?php if(get_option('PPT_slider') == "s1"){ 
		
			$SLIDERSTYLE = get_option('PPT_slider_style');
			 
			switch($SLIDERSTYLE){
			case "1": { ?><script type="text/javascript">jQuery(document).ready(function(){jQuery('#preFeature').siteFeature();	}); </script><?php } break;
			case "2":
			case "3":
			case "4":
			case "5": { ?><script type="text/javascript">jQuery(document).ready(function($) { $(".myslider").slideshow({ width      : 960,height     : 360,});});</script><?php } break;
			}
			
		  }elseif(isset($GLOBALS['s2'])){ ?>
			<script src="<?php echo PPT_PATH; ?>js/jquery.s3Slider.js" type="text/javascript"></script> 
            <script type="text/javascript">	jQuery(document).ready(function() {	jQuery('#featured-item').s3Slider({timeOut: 6000	});}); </script> 
         <?php } ?> 
         
		<?php }  
		
  
		// Load global theme footer scripts
		?>         
	
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=premiumpress"></script>    
        <script type="text/javascript">var addthis_config = {ui_click: true}</script>
		<script type="text/javascript" src="<?php echo PPT_FW_JS_URI; ?>custom.js"></script> 
		<script type="text/javascript" src="<?php echo PPT_CHILD_JS; ?>_defaults.js"></script>
    
    
    	<?php 
		
		// Load lightbox integration
		if(is_single() || ( ( strtolower(PREMIUMPRESS_SYSTEM) == "couponpress" || ( strtolower(PREMIUMPRESS_SYSTEM) == "realtorpress" && isset($GLOBALS['GALLERYPAGE']) ) ) && !isset($GLOBALS['tpl-add']) && !isset($_GET['action']) && !isset($_GET['redirect_to']) && $pagenow !="wp-login.php" ) ){ ?>
 		<script type="text/javascript">
				  jQuery(document).ready(function(){
					jQuery('.lightbox').lightbox();
				  });
		</script>
        <?php }  ?>
 
    <?php
    
    
	switch(strtolower(PREMIUMPRESS_SYSTEM)){
	
	case "shopperpress": { 
	
 	 if(is_single()){ ?> 
     
     <?php //if(strlen($GLOBALS['images']) > 5 ){ ?>
    
    <script type="text/javascript" src="<?php echo PPT_PATH; ?>/js/jquery.ad-gallery.js"></script> 
    <script type="text/javascript">
 
    jQuery(function() {
     
        var galleries = jQuery('.ad-gallery').adGallery();
        jQuery('#switch-effect').change(
          function() {
            galleries[0].settings.effect = jQuery(this).val();
            return false;
          }
        );
        jQuery('#toggle-slideshow').click(
          function() {
            galleries[0].slideshow.toggle();
            return false;
          }
        );
     
    });  
    </script>
    <?php // } ?>
     
    <script type="text/javascript">
 
    jQuery(document).ready(function() {
         
            //Default Action
            jQuery(".tab_content").hide(); //Hide all content
            jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
            jQuery(".tab_content:first").show(); //Show first tab content
            
            //On Click Event
            jQuery("ul.tabs li").click(function() {
                jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
                jQuery(this).addClass("active"); //Add "active" class to selected tab
                jQuery(".tab_content").hide(); //Hide all tab content
                var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
                jQuery(activeTab).fadeIn(); //Fade in the active content
                return false;
                
            });
         
        }); 
    </script>  
    <?php } ?>


	<?php /*------------------- GLOBAL IDS FOR CART -----------------------*/ ?>
    
    <span id="CustomField_1" class="rfield"></span>
    <span id="CustomField_2" class="rfield"></span>
    <span id="CustomField_3" class="rfield"></span>
    <span id="CustomField_4" class="rfield"></span>
    <span id="CustomField_5" class="rfield"></span>
    <span id="CustomField_6" class="rfield"></span>
    <span id="CustomField_7" class="rfield"></span>
    <span id="CustomField_1_required" class="rfield"><?php if(isset($GLOBALS['customlist1_has_value']) && $GLOBALS['customlist1_has_value'] !=""){ echo get_option('custom_field1_required'); } ?></span>
    <span id="CustomField_2_required" class="rfield"><?php if(isset($GLOBALS['customlist2_has_value']) &&  $GLOBALS['customlist2_has_value'] !=""){ echo get_option('custom_field2_required'); } ?></span>
    <span id="CustomField_3_required" class="rfield"><?php if(isset($GLOBALS['customlist3_has_value']) &&  $GLOBALS['customlist3_has_value'] !=""){ echo get_option('custom_field3_required'); } ?></span>
    <span id="CustomField_4_required" class="rfield"><?php if(isset($GLOBALS['customlist4_has_value']) &&  $GLOBALS['customlist4_has_value'] !=""){ echo get_option('custom_field4_required'); } ?></span>
    <span id="CustomField_5_required" class="rfield"><?php if(isset($GLOBALS['customlist5_has_value']) &&  $GLOBALS['customlist5_has_value'] !=""){ echo get_option('custom_field5_required'); } ?></span>
    <span id="CustomField_6_required" class="rfield"><?php if(isset($GLOBALS['customlist6_has_value']) &&  $GLOBALS['customlist6_has_value'] !=""){ echo get_option('custom_field6_required'); } ?></span>
    <span id="CustomField_7_required" class="rfield"><?php if(isset($GLOBALS['customlist7_has_value']) &&  $GLOBALS['customlist7_has_value'] !=""){ echo get_option('custom_field7_required'); } ?></span>
    <span id="CustomQty" class="rfield"></span>
    <span id="CustomShipping" class="rfield"><?php if(isset($GLOBALS['default_shipping'])){ echo $GLOBALS['default_shipping']; } ?></span>
    <span id="CustomSize" class="rfield"></span>
    <span id="CustomColor" class="rfield"></span>
    <span id="CustomExtra" class="rfield"></span>
    
    <script type="text/javascript">
    
           jQuery(document).ready(function() {
                jQuery(".dropdown img.flag").addClass("flagvisibility");
    
                jQuery(".dropdown dt a").click(function() {
                    jQuery(".dropdown dd ul").toggle();
                });
                            
                jQuery(".dropdown dd ul li a").click(function() {
                    var text = jQuery(this).html();
                    jQuery(".dropdown dt a span").html(text);
                    jQuery(".dropdown dd ul").hide();
                   // jQuery("#result").html("Selected value is: " + getSelectedValue("sample"));
                });
    
    
                jQuery(".dropdown1 img.flag").addClass("flagvisibility");
    
                jQuery(".dropdown1 dt a").click(function() {
                    jQuery(".dropdown1 dd ul").toggle();
                });
                            
                jQuery(".dropdown1 dd ul li a").click(function() {
                    var text = jQuery(this).html();
                    jQuery(".dropdown1 dt a span").html(text);
                    jQuery(".dropdown1 dd ul").hide();
                   // jQuery("#result").html("Selected value is: " + getSelectedValue("sample"));
                });
                            
                function getSelectedValue(id) {
                    return jQuery("#" + id).find("dt a span.value").html();
                }
    
                jQuery(document).bind('click', function(e) {
                    var $clicked = jQuery(e.target);
                    if (! $clicked.parents().hasClass("dropdown"))
                        jQuery(".dropdown dd ul").hide();
                    if (! $clicked.parents().hasClass("dropdown1"))
                        jQuery(".dropdown1 dd ul").hide();
                });
    
    
                jQuery("#flagSwitcher").click(function() {
                    jQuery(".dropdown img.flag").toggleClass("flagvisibility");
                });
            });
    </script>
	
	<?php 	 } break;
	
	case "auctionpress":
	case "couponpress":
	case "classifiedstheme":
	case "realtorpress":
	case "jobpress":
	case "directorypress": {
	
	
		if(is_single()){ ?>
 
		 
		<script type="text/javascript"> 
		 
		jQuery(document).ready(function() {
		 
			//Default Action
			jQuery(".tab_content").hide(); //Hide all content
			jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
			jQuery(".tab_content:first").show(); //Show first tab content
			
			//On Click Event
			jQuery("ul.tabs li").click(function() {
				jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
				jQuery(this).addClass("active"); //Add "active" class to selected tab
				jQuery(".tab_content").hide(); //Hide all tab content
				var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
				jQuery(activeTab).fadeIn(); //Fade in the active content
				return false;
				
			});
		 
		});
		</script>
		<?php } 
		
		
		
		
		 if(isset($GLOBALS['tpl-add']) && strtolower(PREMIUMPRESS_SYSTEM) == "auctionpress"){   if(isset($_POST['action']) || isset($_GET['eid']) ){ ?> 
         
			<script type="text/javascript">
            jQuery(document).ready(function() {
            jQuery('#step1box').hide();
            <?php if(isset($_GET['eid']) && !isset($_POST['action']) ){ ?>jQuery('#step2box').show();<?php }elseif(isset($_POST['step3'])){ ?>jQuery('#step4box').show();<?php }else{ ?>jQuery('#step3box').show();<?php } ?>
            });
            </script>
            <?php } ?>
            
            <?php if(!isset($_POST['action']) || $_POST['action'] == ""){ ?>
            <script>jQuery(document).ready(function() { jQuery('#steptable').hide(); });</script>
            <?php } 
		
		 }elseif(isset($GLOBALS['tpl-add'])){  if(isset($_POST['action']) || isset($_GET['eid']) ){ ?> 
         
			<script type="text/javascript">
			jQuery(document).ready(function() { 
			<?php if(isset($_GET['eid']) && !isset($_POST['step1']) && !isset($_POST['step3'])){ ?>jQuery('#step3box').hide();jQuery('#steptable').hide();
			<?php }elseif(isset($_POST['step1'])){ ?>jQuery('#step2box').hide();jQuery('#step3box').show();
			<?php }elseif(isset($_POST['step3'])){ ?>jQuery('#step2box').hide();jQuery('#step4box').show();
			<?php }else{ ?>jQuery('#step3box').show();<?php } ?>
			});
			</script>
            
		<?php }  }  
		
		
		
		
		// Load extra bits for couponpress
		if(strtolower(PREMIUMPRESS_SYSTEM) == "couponpress" || strtolower(PREMIUMPRESS_SYSTEM) == "jobpress"){
		
		?>
 
        
        
        <?php if(isset($GLOBALS['tpl-add'])){ ?> 

 
		<script type="text/javascript" charset="utf-8">
        Date.firstDayOfWeek = 0;
        Date.format = 'yyyy-mm-dd';
        jQuery(function()
        {
            jQuery('.date-pick').datePicker()
            jQuery('#start-date').bind(
                'dpClosed',
                function(e, selectedDates)
                {
                    var d = selectedDates[0];
                    if (d) {
                        d = new Date(d);
                        jQuery('#end-date').dpSetStartDate(d.addDays(1).asString());
                    }
                }
            );
            jQuery('#end-date').bind(
                'dpClosed',
                function(e, selectedDates)
                {
                    var d = selectedDates[0];
                    if (d) {
                        d = new Date(d);
                        jQuery('#start-date').dpSetEndDate(d.addDays(-1).asString());
                    }
                }
            );
        });
                    
        </script> 
        
        <?php } ?>
        <?php

		
		}
		
		
		
	
	} break; 
    
    }    
    
    
    
	// Load the Google Analytics code into the footer
    echo stripslashes(get_option("analytics_code")); 
	
	// Load the Google Adsense tracking code into the footer
	echo stripslashes(get_option("google_adsensetracking_code")); 
		 
		
	}	
 
	
	/**
	 * Magic hook: Define your own widgets_init method
	 *
	 * @since 0.3.0
	 */
	function _widgets_init() {
		self::callback( $this, 'widgets_init' );
	}
	
	
	
		 
	/**
	 * Magic hook: Define your own widgets_init method
	 *
	 * @since 0.3.0
	 */	
	function create_post_type() {	
	
 	if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "moviepress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){
	  register_post_type( 'ppt_message', 
		array(
		'hierarchical' => true,	
		  'labels' => array('name' => 'Messages'),
		  'public' => true,
		  'query_var' => true,
		  'show_ui' => true,
      	  'rewrite' => array('slug' => 'message'),
		     
 
		) );
	}
	
	if( strtolower(constant('PREMIUMPRESS_SYSTEM')) == "auctionpress" || strtolower(PREMIUMPRESS_SYSTEM) == "jobpress"){	
	  register_post_type( 'ppt_feedback', 
		array(
		'hierarchical' => true,	
		  'labels' => array('name' => 'Feedback'),
		  'public' => true,
		  'query_var' => true,
		  'show_ui' => true,
      	  'rewrite' => array('slug' => 'feedback')	  
 
		) );
	}
		
		
	if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){
		
		register_post_type( 'ppt_wishlist', 
				array(
				'hierarchical' => true,	
				  'labels' => array('name' => 'Wishlist'),
				  'public' => true,
				  'query_var' => true,
				  'show_ui' => true,
				  'rewrite' => array('slug' => 'wishlist')	  
		 
			) );
				
		}
		
	if(strtolower(PREMIUMPRESS_SYSTEM) == "jobpress"){
	
		register_post_type( 'ppt_watchlist', 
				array(
				'hierarchical' => true,	
				  'labels' => array('name' => 'Watch List'),
				  'public' => true,
				  'query_var' => true,
				  'show_ui' => true,
				  'rewrite' => array('slug' => 'watchlist')	  
		 
			) );
	 
		
		register_post_type( 'ppt_proposal', 
				array(
				'hierarchical' => true,	
				  'labels' => array('name' => 'Proposals'),
				  'public' => true,
				  'query_var' => true,
				  'show_ui' => true,
				  'rewrite' => array('slug' => 'proposal')	  
		 
			) );
				
		}			
	  
   	register_taxonomy( 'article', 'article_type', array( 	
	 
	'labels' => array(
		'name' => 'Article Categories' ,
		'singular_name' => _x( 'Article Category', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Article Categorys' ),
		'popular_items' => __( 'Popular Article Categorys' ),
		'all_items' => __( 'All Article Categorys' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Article Category' ), 
		'update_item' => __( 'Update Article Category' ),
		'add_new_item' => __( 'Add Article Category' ),
		'new_item_name' => __( 'New Article Category Name' ),
		'separate_items_with_commas' => __( 'Separate Article Categorys with commas' ),
		'add_or_remove_items' => __( 'Add or remove Article Categorys' ),
		'choose_from_most_used' => __( 'Choose from the most used Article Categorys' )
		) , 
	'hierarchical' => true,	
	'query_var' => true,
	'show_ui' => true,
	'rewrite' => array('slug' => 'article-category') ) );
	
	
	
	register_post_type( 'article_type',
		array(
		  'labels' => array('name' => 'Article Manager', 'singular_name' => 'Articles' ), 
      	  'rewrite' =>  array('slug' => 'article'),
		  'public' => true,
		  'supports' => array ( 'title', 'editor','author', 'revisions', 'post-formats', 'trackbacks', 'comments','excerpt' ),
		  'taxonomies' => array('post_tag')
		)
	  ); 
	  
	 // THIS IS USED TO CREATE THE DEFAULT ARTICLE CATEGORY FOR WEBSITE RESETS 
	if(isset($_POST['RESETME']) && $_POST['RESETME'] == "yes"){	

		wp_insert_term("Sample Category 1", 'article',array('cat_name' => "Sample Category 1", 'description' => "This is an example article category description"  ));
 		wp_insert_term("Sample Category 2", 'article',array('cat_name' => "Sample Category 2", 'description' => "This is an example article category description"  ));
		wp_insert_term("Sample Category 3", 'article',array('cat_name' => "Sample Category 3", 'description' => "This is an example article category description"  ));
		wp_insert_term("Sample Category 4", 'article',array('cat_name' => "Sample Category 4", 'description' => "This is an example article category description"  ));		
		wp_insert_term("Sample Category 5", 'article',array('cat_name' => "Sample Category 5", 'description' => "This is an example article category description"  ));
		wp_insert_term("Sample Category 6", 'article',array('cat_name' => "Sample Category 6", 'description' => "This is an example article category description"  ));		
					
	}
		  
   	register_taxonomy( 'faq', 'faq_type', array( 	
	 
	'labels' => array(
		'name' => 'FAQ Categories' ,
		'singular_name' => _x( 'FAQ', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search FAQ\'s' ),
		'popular_items' => __( 'Popular FAQ\'s' ),
		'all_items' => __( 'All FAQs' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit FAQ' ), 
		'update_item' => __( 'Update FAQ' ),
		'add_new_item' => __( 'Add New FAQ' ),
		'new_item_name' => __( 'New FAQ Name' ),
		'separate_items_with_commas' => __( 'Separate FAQ\'s with commas' ),
		'add_or_remove_items' => __( 'Add or remove FAQ\'s' ),
		'choose_from_most_used' => __( 'Choose from the most used FAQ\'s' )
		) , 
	'hierarchical' => true,	
	'query_var' => true,
	'show_ui' => true,
	'rewrite' => array('slug' => 'faq-category') ) );
	
	
	
	register_post_type( 'faq_type',
		array(
		  'labels' => array('name' => 'FAQ Manager', 'singular_name' => 'FAQ Manager' ), 
      	  'rewrite' =>  array('slug' => 'faq'),
		  'public' => true,
		  'supports' => array ( 'title', 'editor','author', 'revisions', 'post-formats', 'trackbacks', 'comments','excerpt' ) 
		)
	  );



	  
	  	  
	 // THIS IS USED TO CREATE THE DEFAULT ARTICLE CATEGORY FOR WEBSITE RESETS 
	if(isset($_POST['RESETME']) && $_POST['RESETME'] == "yes"){	

		wp_insert_term("FAQ Category 1", 'faq',array('cat_name' => "FAQ Category 1", 'description' => "This is an example faq category description"  ));
 		wp_insert_term("FAQ Category 2", 'faq',array('cat_name' => "FAQ Category 2", 'description' => "This is an example faq category description"  ));
		wp_insert_term("FAQ Category 3", 'faq',array('cat_name' => "FAQ Category 3", 'description' => "This is an example faq category description"  ));
		wp_insert_term("FAQ Category 4", 'faq',array('cat_name' => "FAQ Category 4", 'description' => "This is an example faq category description"  ));		
		wp_insert_term("FAQ Category 5", 'faq',array('cat_name' => "FAQ Category 5", 'description' => "This is an example faq category description"  ));
		wp_insert_term("FAQ Category 6", 'faq',array('cat_name' => "FAQ Category 6", 'description' => "This is an example faq category description"  ));		
					
	}	   
	  
 }
	
		
}
	
?>