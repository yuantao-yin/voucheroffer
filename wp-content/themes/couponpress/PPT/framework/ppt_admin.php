<?php
/**
 * Base class for registering an administration page in the PremiumPress
 * admin framework
 *
 * @since 0.3.0
 */
class PPT_Admin extends PPT_API {

	/**
	 * Sets the slug for a registered page.
	 *
	 * @access public
	 * @since 0.3.0
	 * @see _setup_globals();
	 * @var string
	 */
	var $slug;
	
	/**
	 * Magic hook: Define your own admin_head
	 *
	 * @since 0.3.0
	 */

	function PPT_Admin( $args = array() ) {
 
		
		// Process the save data
		if(isset($_POST['submitted']) && $_POST['submitted'] == "yes"){
			
			//$this->process_form_data();
		} 
		
		// Load the admin sidebar change post option
		add_action( 'post_submitbox_misc_actions', 'ppt_metabox' );
		
		// Load admin area notices	 
		add_action('admin_notices', array( $this, '_admin_notices' ));		

		// Load PremiumPress admin theme options
		require_once (PPT_CHILD_DIR."/system_customfields.php");
		
		// Load all the javascript/styles into the theme wp_head
		add_action( 'wp_loaded', array( $this, '_enqueue_assets' ) ); 
	 
		// Load into WP Magic hooks
		add_action( 'admin_menu', array( $this, '_admin_menu' ) );
		add_action( 'admin_head', array( $this, '_admin_head' ) );

add_filter('default_hidden_meta_boxes', array( $this, 'ppt_hidden_meta_boxes' ), 10, 2);
	}
	
	
	/**
	 * Magic hook: Custom display the author and excerpt when you install the theme 
	 *
	 * @since 0.3.0
	 */	
	function ppt_hidden_meta_boxes($hidden, $screen) {
	 
		if ( 'post' == $screen->base || 'page' == $screen->base )
			$hidden = array('slugdiv', 'trackbacksdiv',  'commentstatusdiv', 'postcustom', 'commentsdiv',  'revisionsdiv');
			// removed , 'postexcerpt','authordiv',
		return $hidden;
	}	
	/**
	 * Magic hook: Define your own admin_head
	 *
	 * @since 0.3.0
	 */
	 
	function _admin_head() {	
 
		$this->_enqueue_assets(true);	
		$this->_admin_css();
 
	}

	/**
	 * Magic hook: Defines the admin notices
	 *
	 * @since 0.3.0
	 */		
	function _admin_notices() {
	
	echo "<br />";
		 
	/* ?> 	
	
	<div id="premiumpress_nav">
	 
	<a href="http://www.premiumpress.com/?adminlink=<?php if(defined('PREMIUMPRESS_SYSTEM')){ echo PREMIUMPRESS_SYSTEM; }elseif(defined('PREMIUMPRESS_PLUGIN')){ echo PREMIUMPRESS_PLUGIN; } ?>" target="_blank">                      
	 
	  <img src="<?php echo PPT_FW_IMG_URI; ?>/admin/top0.png"  alt="Premium Wordpress Themes" />
	</a>	
	 
	  <img src="<?php echo PPT_FW_IMG_URI; ?>/admin/top1.png" />
	</div> 
	<div class="clearfix"></div><br />
	
	<?php */ if($GLOBALS['error'] == 1){ ?><div class="msg msg-<?php echo $GLOBALS['error_type']; ?>"><p><?php echo $GLOBALS['error_msg']; ?></p></div> <?php  }
			 
		 
	}

 

	
	/**
	 * Magic hook: Define the header scripts
	 *
	 * @since 0.3.0
	 */
	 
	function _enqueue_assets($ishead = false){
	
	global $pagenow; 

		// Load scripts into system
		//wp_deregister_script( 'jquery' );
		//wp_register_script( 'jquery', PPT_THEME_URI.'/PPT/js/jquery.js');
		wp_enqueue_script( 'jquery' ); //<-- load WP jquery instead
	 
		wp_register_script( 'ppt_ajax_actions', PPT_THEME_URI.'/PPT/ajax/actions.js');
		wp_enqueue_script( 'ppt_ajax_actions' );	 
		
		if(isset($_GET['page']) && ($_GET['page'] == "images" || $_GET['page'] == "add" )){ 	  // not great i know but it works
	 
			wp_register_script( 'fancybox', PPT_THEME_URI.'/PPT/fancybox/jquery.fancybox-1.3.1.js');
			wp_enqueue_script( 'fancybox' );
			
			wp_register_style( 'fancyboxCSS', PPT_THEME_URI.'/PPT/fancybox/jquery.fancybox-1.3.1.css');
			wp_enqueue_style( 'fancyboxCSS' );
		
		}
		
		wp_register_script( 'msgbox', PPT_THEME_URI.'/images/premiumpress/msgbox/jquery.msgbox.js');
		wp_enqueue_script( 'msgbox' );
		
		wp_register_style( 'msgboxCSS', PPT_THEME_URI.'/images/premiumpress/msgbox/jquery.msgbox.css');
		wp_enqueue_style( 'msgboxCSS' ); 

		// Load the pop-up for admin image uploads	
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		
		if($ishead){ // Load the content within the head because we cannot enqueue after the init
		?>
        
		<!-- Adding support for transparent PNGs in IE6: -->
		<!--[if lte IE 6]>
		<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/images/premiumpress/ddpng.js"></script>
		<script type="text/javascript">
		DD_belatedPNG.fix('.ico img');
		DD_belatedPNG.fix('.msg p');
		DD_belatedPNG.fix('table.calendar thead th.month a img');
		DD_belatedPNG.fix('table.calendar tbody img');
		</script>
		<![endif]-->
		<script type="text/javascript">
		
	
		function PPHelpMe(keyword){
			 
			 tb_show("PremiumPress Video Tutorials","http://www.premiumpress.com/videotutorials/?l=<?php if($_SERVER['HTTP_HOST'] == "localhost"){ echo "localhost"; }else{ echo get_option('license_key');} ?>&t=<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>&p=<?php echo $_GET['page']; ?>&k="+keyword+"TB_iframe=true&height=600&width=900&modal=false", null);
			 return false;
			 
			}
		
		function PPMsgBox(text){
		jQuery.msgbox(text, {  type: "info",   buttons: [    {type: "submit", value: "OK"}  ]}, function(result) {  
		if (!result) {      window.open('http://www.premiumpress.com/videotutorials/?k=<?php if($_SERVER['HTTP_HOST'] == "localhost"){ echo "localhost"; }else{ echo get_option('license_key');} ?>&p=<?php echo $_GET['page']; ?>','mywindow','width=900,height=600')		  }});
		
		}
		
		jQuery(document).ready(function() {	
		
		 
			;
		
			jQuery('#post-type-select').siblings('a.edit-post-type').click(function() {
						if (jQuery('#post-type-select').is(":hidden")) {
							jQuery('#post-type-select').slideDown("normal");
							jQuery(this).hide();
						}
						return false;
			});
		
			jQuery('.save-post-type', '#post-type-select').click(function() {
						jQuery('#post-type-select').slideUp("normal");
						jQuery('#post-type-select').siblings('a.edit-post-type').show();
						pts_updateText();
						return false;
			});
		
			jQuery('.cancel-post-type', '#post-type-select').click(function() {
						jQuery('#post-type-select').slideUp("normal");
						jQuery('#pts_post_type').val(jQuery('#hidden_post_type').val());
						jQuery('#post-type-select').siblings('a.edit-post-type').show();
						pts_updateText();
						return false;
			});
		
			function pts_updateText() {
						jQuery('#post-type-display').html( jQuery('#pts_post_type :selected').text() );
						jQuery('#hidden_post_type').val(jQuery('#pts_post_type').val());
						jQuery('#post_type').val(jQuery('#pts_post_type').val());
						return true;
			}
		
							
		<?php if($_GET['page'] == "images" || $_GET['page'] == "add"){ ?>
		jQuery("a[rel=image_group]").fancybox({
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'titlePosition' 	: 'over',
			'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
		}
		});
		<?php } ?>
		
 
							jQuery('tbody tr:even').addClass('even');
							jQuery('table.grid tbody tr:last-child').addClass('last');
							jQuery('tr th:first-child, tr td:first-child').addClass('first');
							jQuery('tr th:last-child, tr td:last-child').addClass('last');
							jQuery('form.fields fieldset:last-child').addClass('last');					
							jQuery('ul.simple li:even').addClass('even');					
							jQuery('.grid .line:even').addClass('even');
							jQuery('.grid .line:first-child').addClass('firstline');
							jQuery('.grid .line:last-child').addClass('lastline');
							<?php if( isset($_POST['ad_zone']) ){ ?>
							jQuery('#premiumpress_box1 .content#premiumpress_tab1').hide(); 
							<?php } ?>
							<?php if(!isset($_POST['showtax']) ){ ?>
							jQuery('#premiumpress_box1 .content#premiumpress_tab2').hide(); 
							<?php } ?>					
							jQuery('#premiumpress_box1 .content#premiumpress_tab3').hide(); 
							jQuery('#premiumpress_box1 .content#premiumpress_tab4').hide(); 
							<?php if(!isset($_POST['ad_zone'])){ ?>
							jQuery('#premiumpress_box1 .content#premiumpress_tab5').hide(); 
							<?php } ?>
							<?php if(!isset($_POST['selectcountry'])){ ?>
							jQuery('#premiumpress_box1 .content#premiumpress_tab6').hide(); 
							 <?php } ?>					
							jQuery('#premiumpress_box1 .header ul a').click(function(){
								jQuery('#premiumpress_box1 .header ul a').removeClass('active');
								jQuery(this).addClass('active');
								jQuery('#premiumpress_box1 .content').hide(); 
								jQuery('#premiumpress_box1').find('#' + jQuery(this).attr('rel')).show(); 
								return false;
							});
					});		 
					 
					 
					function toggleLayer( whichLayer )
					{
					  var elem, vis;
					  if( document.getElementById ) 
						elem = document.getElementById( whichLayer );
					  else if( document.all ) 
						  elem = document.all[whichLayer];
					  else if( document.layers ) 
						elem = document.layers[whichLayer];
					  vis = elem.style;
					
					  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
					} 
					
		</script>  	
		<?php

		}
		
}	
	
	
	/**
	 * Magic hook: Define the menu lables
	 *
	 * @since 0.3.0
	 */	 
	
	
	function change_post_object_label() {
	 
	 
		switch(strtolower(constant('PREMIUMPRESS_SYSTEM'))){
		
			case "auctionpress": {
			
					global $wp_post_types;
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Auctions';
					$labels->singular_name = 'Auctions';
					$labels->add_new = 'Add Auction';
					$labels->add_new_item = 'Add Auction';
					$labels->edit_item = 'Edit Auction';
					$labels->new_item = 'Auctions';
					$labels->view_item = 'View Auctions';
					$labels->search_items = 'Search Auctions';
					$labels->not_found = 'No Auctions found';
					$labels->not_found_in_trash = 'No Auctions found in Trash';
			
			} break;
		
			case "directorypress": {
			
					global $wp_post_types;
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Listings1';
					$labels->singular_name = 'Listings1';
					$labels->add_new = 'Add Listing';
					$labels->add_new_item = 'Add Listing';
					$labels->edit_item = 'Edit Listing';
					$labels->new_item = 'Listings';
					$labels->view_item = 'View Listings';
					$labels->search_items = 'Search Listings';
					$labels->not_found = 'No Listings found';
					$labels->not_found_in_trash = 'No Listings found in Trash';
			
			} break;
			
			case "couponpress": {
			
					global $wp_post_types;
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Listings';
					$labels->singular_name = 'Coupons';
					$labels->add_new = 'Add Coupon';
					$labels->add_new_item = 'Add Coupon';
					$labels->edit_item = 'Edit Coupon';
					$labels->new_item = 'Coupons';
					$labels->view_item = 'View Coupons';
					$labels->search_items = 'Search Coupons';
					$labels->not_found = 'No Coupons found';
					$labels->not_found_in_trash = 'No Coupons found in Trash';
			
			} break;
			
			case "classifiedstheme": {
			
					global $wp_post_types;
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Listings';
					$labels->singular_name = 'Classifieds';
					$labels->add_new = 'Add Classified';
					$labels->add_new_item = 'Add Classified';
					$labels->edit_item = 'Edit Classified';
					$labels->new_item = 'Classifieds';
					$labels->view_item = 'View Classifieds';
					$labels->search_items = 'Search Classifieds';
					$labels->not_found = 'No Classifieds found';
					$labels->not_found_in_trash = 'No Classifieds found in Trash';
			
			} break;	
			
			
			case "realtorpress": {
			
					global $wp_post_types;
		
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Listings';
					$labels->singular_name = 'Real Estate';
					$labels->add_new = 'Add Real Estate';
					$labels->add_new_item = 'Add Real Estate';
					$labels->edit_item = 'Edit Real Estate';
					$labels->new_item = 'Real Estate';
					$labels->view_item = 'View Real Estate';
					$labels->search_items = 'Search Real Estate';
					$labels->not_found = 'No Real Estate found';
					$labels->not_found_in_trash = 'No Real Estate found in Trash';
			
			} break;	
			
			
			case "shopperpress": {
			
					global $wp_post_types;
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Products';
					$labels->singular_name = 'Product';
					$labels->add_new = 'Add Product';
					$labels->add_new_item = 'Add Product';
					$labels->edit_item = 'Edit Product';
					$labels->new_item = 'Product';
					$labels->view_item = 'View Product';
					$labels->search_items = 'Search Product';
					$labels->not_found = 'No Product found';
					$labels->not_found_in_trash = 'No Product found in Trash';
			
			} break;
			
			
			case "moviepress": {
			
					global $wp_post_types;
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Videos';
					$labels->singular_name = 'Video';
					$labels->add_new = 'Add Video';
					$labels->add_new_item = 'Add Video';
					$labels->edit_item = 'Edit Video';
					$labels->new_item = 'Video';
					$labels->view_item = 'View Video';
					$labels->search_items = 'Search Video';
					$labels->not_found = 'No Video found';
					$labels->not_found_in_trash = 'No Video found in Trash';
			
			} break;
			
			case "jobpress": {
			
					global $wp_post_types;
					$labels = &$wp_post_types['post']->labels;
					$labels->name = 'Jobs';
					$labels->singular_name = 'Job';
					$labels->add_new = 'Add Job';
					$labels->add_new_item = 'Add Job';
					$labels->edit_item = 'Edit Job';
					$labels->new_item = 'Job';
					$labels->view_item = 'View Job';
					$labels->search_items = 'Search Job';
					$labels->not_found = 'No Job found';
					$labels->not_found_in_trash = 'No Job found in Trash';
			
			} break;
		 
				
		}
	
	} 
	

	/**
	 * Magic hook: Define the admin_menu items
	 * @since 0.3.0
	 */
	 
	function _admin_menu() {
 
	global $wpdb;
	
	// Load the custom labels for the admin pages 
	$this->change_post_menu_label();
	$this->change_post_object_label();
 
	 
		add_menu_page(basename(__FILE__), __(str_replace("Theme","",str_replace("EmployeePress","EmployeeP",constant('PREMIUMPRESS_SYSTEM'))),'cp'), "edit_pages", basename(__FILE__), '', ''.PPT_PATH.'/img/admin/'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'.png',3); 
		
		if(get_option("license_key") == ""){	 // && $_SERVER['HTTP_HOST'] != "localhost"
		
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('License Key','cp'), "edit_pages", basename(__FILE__), array( $this, 'premiumpress_admin_licensekey_form' ) );	
		
		}else{
		
		
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/overview.png" align="middle"> Overview','cp'), "edit_pages", basename(__FILE__), array( $this, 'premiumpress_overview' ) );	
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/ssetup.png" align="middle"> General Setup','cp'), "edit_pages", 'setup', array( $this, 'premiumpress_admin_setup' ) );
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/smembers.png" align="middle"> Members','cp'), "edit_pages", 'members', array( $this, 'premiumpress_admin_members' ) );			
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/sdisplay.png" align="middle"> Display Settings','cp'), "edit_pages", 'display', array( $this, 'premiumpress_admin_display' ) );
		
		if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress"){  
		
		}elseif(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "moviepress"){  
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/spackages.png" align="middle"> Memberships','cp'), "edit_pages", 'submit',array( $this, 'premiumpress_admin_submit'));
		}else{	
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/simport.png" align="middle"> Submission','cp'), "edit_pages", 'submit', array( $this,'premiumpress_admin_submit'));
		}	
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/sadvert.png" align="middle"> Advertising','cp'), "edit_pages", 'advertising', array( $this,'premiumpress_admin_advertising'));	
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/sgoogle.png" align="middle"> Analytics','cp'), "edit_pages", 'analytics', array( $this,'premiumpress_admin_analytics'));
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/stools.png" align="middle"> Tools','cp'), "edit_pages", 'tools', array( $this,'premiumpress_admin_tools'));
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/spayment.png" align="middle"> Payments','cp'), "edit_pages", 'payments', array( $this,'premiumpress_admin_payments'));
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/sorders.png" align="middle"> Order Manager','cp'), "edit_pages", 'orders', array( $this,'premiumpress_admin_orders'));	
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/simages.png" align="middle"> File Manager','cp'), "edit_pages", 'images', array( $this,'premiumpress_admin_images'));
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/semail.png" align="middle"> Email Manager','cp'), "edit_pages", 'emails', array( $this,'premiumpress_admin_emails'));	
		
		switch(strtolower(constant('PREMIUMPRESS_SYSTEM'))){
		
			case "couponpress": {	
			
			add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/simport_coupons.png" align="middle"> Import Coupons','cp'), "edit_pages", 'import',  array( $this,'premiumpress_admin_import_coupons'));
				
			} break;
		
			case "shopperpress": {	
			
			add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/scheckout.png" align="middle"> Checkout','cp'), "edit_pages", 'checkout',  array( $this,'premiumpress_admin_checkout'));
			add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/sshipping.png" align="middle"> Shipping','cp'), "edit_pages", 'shipping',  array( $this,'premiumpress_admin_shipping'));
			add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/import.png" align="middle"> Import Products','cp'), "edit_pages", 'import_products',  array( $this,'premiumpress_admin_import_products'));
				
			} break;	
			
			case "moviepress": {	
			
			add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/video.png" align="middle"> Import Videos','cp'), "edit_pages", 'import_movies',  array( $this,'premiumpress_admin_import_movies'));
				
			} break;
			
			case "classifiedstheme": {	
			
			add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/import.png" align="middle"> Import Products','cp'), "edit_pages", 'import_products',  array( $this,'premiumpress_admin_import_products'));
				
			} break;		
					
			
			
		}
		
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/s123.png" align="middle"> Quick Help','cp'), "edit_pages", "quickhelp", array( $this, 'premiumpress_steps_setup' ) );
		add_submenu_page(basename(__FILE__), __(constant('PREMIUMPRESS_SYSTEM'),'cp'), __('<img src="'.PPT_PATH.'/img/admin/supdate.png" align="middle"> Theme Updates','cp'), "edit_pages", 'updates',  array( $this,'premiumpress_admin_updates'));
	
	
		
		} 

		$this->contextual_callback( 'admin_menu' );
	}

 


	/**
	 * Magic hook: Define the admin menu calls
	 * @since 0.3.0
	 */
	 
	function premiumpress_overview() 		{  			include(TEMPLATEPATH . '/admin/_ad_overview.php');  }
	function premiumpress_steps_setup() 	{  			include(TEMPLATEPATH . '/admin/_ad_stepbystep.php');  }
	function premiumpress_admin_setup() 	{  			include(TEMPLATEPATH . '/admin/_ad_setup.php');  }
	function premiumpress_admin_display() 	{ 			include(TEMPLATEPATH . '/admin/_ad_'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'_1.php');  }
	function premiumpress_admin_submit() 	{ 			include(TEMPLATEPATH . '/admin/_ad_'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'_2.php');  }
	function premiumpress_admin_members() 	{ 			include(TEMPLATEPATH . '/admin/_ad_members.php');  }
	function premiumpress_admin_orders() 	{    		global $wpdb;   include(TEMPLATEPATH . '/admin/_ad_orders.php');	}
	function premiumpress_admin_payments() 	{ 			include(TEMPLATEPATH . '/admin/_ad_payments.php'); }
	function premiumpress_admin_advertising() { 		include(TEMPLATEPATH . '/admin/_ad_advertising.php'); }
	function premiumpress_admin_tools() 	{ 			include(TEMPLATEPATH . '/admin/_ad_tools.php'); }
	function premiumpress_admin_analytics() { 			include(TEMPLATEPATH . '/admin/_ad_analytics.php'); }
	function premiumpress_admin_images() 	{ 			include(TEMPLATEPATH . '/admin/_ad_images.php'); } 
	function premiumpress_admin_emails() 	{ 			include(TEMPLATEPATH . '/admin/_ad_emails.php'); } 
	function premiumpress_admin_import_coupons() 	{ 	include(TEMPLATEPATH . '/admin/_ad_import_coupons.php'); } 
	function premiumpress_admin_checkout() 	{ 			include(TEMPLATEPATH . '/admin/_ad_checkout.php'); } 
	function premiumpress_admin_shipping() 	{ 			include(TEMPLATEPATH . '/admin/_ad_shipping.php'); } 
	function premiumpress_admin_import_products() 	{ 	include(TEMPLATEPATH . '/admin/_ad_import_products.php'); } 
	function premiumpress_admin_import_movies() 	{ 	include(TEMPLATEPATH . '/admin/_ad_import_movies.php'); } 
	function premiumpress_admin_updates(){  $this->premiumpress_updates(); } 

 
 
	/**
	 * Magic hook: Define the admin menu labels
	 * @since 0.3.0
	 */ 
  
	function change_post_menu_label() {
	
		global $menu,$submenu;
		
		switch(strtolower(constant('PREMIUMPRESS_SYSTEM'))){
		
		case "auctionpress": {
		
				$menu[5][0] = 'Auctions';
				$submenu['edit.php'][5][0] = 'Auctions';
				$submenu['edit.php'][10][0] = 'Add Auction';
				$submenu['edit.php'][16][0] = 'Auction Tags';
				echo '';
		} break;
		
		case "directorypress": {
		
				$menu[5][0] = 'Directory Listings';
				$submenu['edit.php'][5][0] = 'Listings';
				$submenu['edit.php'][10][0] = 'Add Listing';
				$submenu['edit.php'][16][0] = 'Listing Tags';
				echo '';
		} break;
		
		case "couponpress": {
		
				$menu[5][0] = 'Coupons';
				$submenu['edit.php'][5][0] = 'Coupons';
				$submenu['edit.php'][10][0] = 'Add Coupon';
				$submenu['edit.php'][16][0] = 'Coupon Tags';
				echo '';
		} break;
		
		case "classifiedstheme": {
		
				$menu[5][0] = 'Classifieds';
				$submenu['edit.php'][5][0] = 'Classifieds';
				$submenu['edit.php'][10][0] = 'Add Classified';
				$submenu['edit.php'][16][0] = 'Classified Tags';
				echo '';
		} break;
		
		
		case "realtorpress": {
		
				$menu[5][0] = 'Real Estate';
				$submenu['edit.php'][5][0] = 'Manage Real Estate';
				$submenu['edit.php'][10][0] = 'Add Real Estate';
				$submenu['edit.php'][16][0] = 'Real Estate Tags';
				echo '';
		} break;
		
		case "shopperpress": {
		
				$menu[5][0] = 'Products';
				$submenu['edit.php'][5][0] = 'Manage Products';
				$submenu['edit.php'][10][0] = 'Add Product';
				$submenu['edit.php'][16][0] = 'Product Tags';
				echo '';
		} break;
		
		
		case "moviepress": {
		
				$menu[5][0] = 'Videos';
				$submenu['edit.php'][5][0] = 'Manage Videos';
				$submenu['edit.php'][10][0] = 'Add Video';
				$submenu['edit.php'][16][0] = 'Video Tags';
				echo '';
		} break;
		
		case "jobpress": {
		
				$menu[5][0] = 'Jobs';
				$submenu['edit.php'][5][0] = 'Manage Jobs';
				$submenu['edit.php'][10][0] = 'Add Job';
				$submenu['edit.php'][16][0] = 'Job Tags';
				echo '';
		} break;
	
		}
	
	
	}


 
 
	/**
	 * Magic hook: Define the admin area CSS
	 * @since 0.3.0
	 */ 
 	
	function _admin_css(){
	?>
	<style type="text/css">
	
		#post-type-select {
				line-height: 2.5em;
				margin-top: 3px;
			}
			#post-type-display {
				font-weight: bold;
			}
			div.post-type-switcher {
				border-top: 1px solid #eee;
			}
	
	.ppnote { background:#dbffde; padding:10px; color:#078b10; width:280px; font-size:10px; border:1px silid #ddd;  }
	.ppnote1 { background:#dbefff; padding:10px; color:#4787b8; width:280px; font-size:12px; border:1px silid #9cc3e0;  }
	
	
	table {border-collapse: separate; border-spacing: 0;}
	caption, th, td {text-align: left; font-weight: normal;}
	blockquote:before, blockquote:after,
	q:before, q:after {content: "";}
	blockquote, q {quotes: "" "";}
	li {list-style-type: none;}
	hr {display: none;}
	strong, b {font-weight: bold;}
	em, i {font-style: italic;}
	a { text-decoration: none; }
	a img {border: none;}
	.cw {width: 100%; overflow: hidden;}
	.cw2 {overflow: hidden; height: 1%;}
	.fl {float: left;}
	.fr {float: right;}
	.cleaner {clear: both; visibility: hidden; height: 0; overflow: hidden; line-height: 0; font-size: 0;}
	.clearfix:after {content: "."; display: block; height: 0; clear: both; visibility: hidden;}
	.ir {position: absolute; top: 0; left: 0; display: block; width: 100%; height: 100%;}
	.tl {text-align: left !important;}
	.tr {text-align: right !important;}
	.tc {text-align: center !important;}
	.ttop {vertical-align: top !important;}
	.hand {cursor: hand; cursor: pointer;}
	.a-hidden {position: absolute; top: -10000em;}
	.first {border-left: 0 !important;}
	.last {border-right: 0 !important;}
	#premiumpress_nav {border-top: 1px solid #fff; border-bottom: 1px solid #999; background: #fff; height:60px;}
 
 
	
 
	.premiumpress_button {border: 1px solid #7a0000; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; background: #8e0f0f url("<?php bloginfo('template_url'); ?>/images/premiumpress/red/button.gif") repeat-x; padding: 5px 9px 5px; text-shadow: #5d0101 1px 1px 0; color: #fff; cursor: pointer;}
	.premiumpress_button:hover,
	.premiumpress_button:focus,
	.premiumpress_button:active {border-color: #272727; background: #2a2a2a url("<?php bloginfo('template_url'); ?>/images/premiumpress/altbutton.gif") repeat-x; text-shadow: #000 1px 1px 0; color: #fff;}
	/* alternative colors */
	.altbox .premiumpress_button {border: 1px solid #272727; background: #2a2a2a url("<?php bloginfo('template_url'); ?>/images/premiumpress/altbutton.gif") repeat-x; text-shadow: #000 1px 1px 0;}
	.altbox .premiumpress_button:hover,
	.altbox .premiumpress_button:focus,
	.altbox .premiumpress_button:active {border-color: #7a0000; background: #8e0f0f url("<?php bloginfo('template_url'); ?>/images/premiumpress/red/button.gif") repeat-x; text-shadow: #5d0101 1px 1px 0; color: #fff;}
	.altbutton {border: 1px solid #272727; background: #2a2a2a url("<?php bloginfo('template_url'); ?>/images/premiumpress/altbutton.gif") repeat-x; text-shadow: #000 1px 1px 0;}
	.altbutton:hover,
	.altbutton:focus,
	.altbutton:active {border-color: #7a0000; background: #8e0f0f url("<?php bloginfo('template_url'); ?>/images/premiumpress/red/button.gif") repeat-x; text-shadow: #5d0101 1px 1px 0; color: #fff;}
	.altbox .altbutton {border: 1px solid #7a0000; background: #8e0f0f url("<?php bloginfo('template_url'); ?>/images/premiumpress/red/button.gif") repeat-x; text-shadow: #5d0101 1px 1px 0;}
	.altbox .altbutton:hover,
	.altbox .altbutton:focus,
	.altbox .altbutton:active {border-color: #272727; background: #2a2a2a url("<?php bloginfo('template_url'); ?>/images/premiumpress/altbutton.gif") repeat-x; text-shadow: #000 1px 1px 0; color: #fff;}
	/* icons for content links etc. */
	.ico { border: 0 !important; }
	.ico-a { border: 0 !important; padding-left: 20px !important; }
	.msg {
	border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px;
	border: 1px solid; margin: 0 0 15px 0; padding: 8px 10px 0 10px;
	max-width:840px;
	}
	.msg p {margin: 0 0 8px 0; padding-left: 25px;}
	.msg-ok {border-color: #a6d877; background: #d2ecba url("<?php bloginfo('template_url'); ?>/images/premiumpress/msg-ok.png") repeat-x; color: #336801;}
	.msg-error {border-color: #f3abab; background: #f9c9c9 url("<?php bloginfo('template_url'); ?>/images/premiumpress/msg-error.png") repeat-x; color: #8d0d0d;}
	.msg-warn {border-color: #d7e059; background: #f3f7aa url("<?php bloginfo('template_url'); ?>/images/premiumpress/msg-warn.png") repeat-x; color: #6c6600;}
	.msg-info {border-color: #9fd1f5; background: #c3e6ff url("<?php bloginfo('template_url'); ?>/images/premiumpress/msg-info.png") repeat-x; color: #005898;}
	.msg-ok p {background: url("<?php bloginfo('template_url'); ?>/images/premiumpress/led-ico/accept.png") 0 50% no-repeat;}
	.msg-error p {background: url("<?php bloginfo('template_url'); ?>/images/premiumpress/led-ico/cross_octagon.png") 0 50% no-repeat;}
	.msg-warn p {background: url("<?php bloginfo('template_url'); ?>/images/premiumpress/led-ico/exclamation_octagon_fram.png") 0 50% no-repeat;}
	.msg-info p {background: url("<?php bloginfo('template_url'); ?>/images/premiumpress/led-ico/exclamation.png") 0 50% no-repeat;}
	.error {color: #b70b0b;}
	form.plain {padding: 0;}
	form.plain fieldset {border: 0 !important; padding: 0 !important;}
	form.basic dl {width: 100%; overflow: hidden;}
	form.basic dl dt,
	form.basic dl dd {float: left;}
	form.basic dl dt {padding: 3px 5px 3px 0; width: 20%;}
	form.basic dl dd {padding: 3px 0 3px 5px; width: 76%;}
	label.check,
	label.radio {margin-right: 5px;}
	form small {color: #999;}
	input.txt,
	textarea {
	border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px;
	border: 1px solid #999; background: #fff url("<?php bloginfo('template_url'); ?>/images/premiumpress/txt.gif") repeat-x; padding: 5px 2px;
	}
	form.basic input.txt,
	form.basic textarea {width: 100%;}
	input.error,
	textarea.error {border-color: #d35757; background-image: url("<?php bloginfo('template_url'); ?>/images/premiumpress/txt-error.gif");}
	span.loading {background: url("<?php bloginfo('template_url'); ?>/images/premiumpress/upload.gif") 0 50% no-repeat; padding: 3px 0 3px 20px;}
	form.fields {}
	form.fields fieldset {
	border-radius: 5px; -webkit-border-radius: 5px; -moz-border-radius: 5px;
	border: 1px solid #ccc; margin-bottom: 15px; padding: 10px 15px 15px 15px;
	}
	form.fields fieldset.last {margin-bottom: 0; border-right: 1px solid #ccc !important;}
	form.fields fieldset legend {padding: 0 10px; font-size: 110%;}
	form.fields label,
	form.fields div.sep {display: block; margin-top: 6px;}
	form.fields label.check,
	form.fields label.radio {display: inline; margin-top: 0;}
	form.fields span.loading {margin-left: 10px;}
	ul.actions {margin: 0;}
	ul.actions li {display: inline; margin-right: 5px;}
	.premiumpress_box {float: left; width: 860px; margin: 0 20px 20px 0;}
	.premiumpress_box-25 {width: 225px;} 
	.premiumpress_box-50 {width: 470px;}
	.premiumpress_box-75 {width: 715px;}
	.premiumpress_box-100 {min-width: 860px;}
	.premiumpress_boxin {box-shadow: #aaa 0 0 10px; -webkit-box-shadow: #aaa 0 0 10px; -moz-box-shadow: #aaa 0 0 10px; border: 1px solid #999; border-radius: 6px; -webkit-border-radius: 6px; -moz-border-radius: 6px; background: #fff;}
	.premiumpress_box .header {
	background: #3d3d3d url("<?php bloginfo('template_url'); ?>/images/premiumpress/headerbox.png") repeat-x;border-top: 1px solid #444;border-radius: 5px 5px 0 0;   -moz-border-radius-topleft: 5px;   -moz-border-radius-topright: 5px;   -webkit-border-top-left-radius: 5px;   -webkit-border-top-right-radius: 5px;position: relative; margin: -1px -1px 0 -1px; padding: 7px 0 9px 20px;
	}
	.altbox .header {border-top-color: #be0000; background: #8e0f0f url("<?php bloginfo('template_url'); ?>/images/premiumpress/red/altheaderbox.png") repeat-x;}
	.premiumpress_box .header h3 {position: relative; top: 2px; display: inline; font-size: 150%; color: #fff; text-shadow: #151515 0 1px 0;}
	.altbox .header h3 {text-shadow: #6c0000 0 1px 0;}
	.premiumpress_box .header .premiumpress_button {margin-left: 15px;}
	.premiumpress_box .header ul {position: absolute; right: 9px; bottom: 0;}
	.premiumpress_box .header ul li {display: inline;}
	.premiumpress_box .header ul a {border-radius: 5px 5px 0 0;  -moz-border-radius-topleft: 5px;   -moz-border-radius-topright: 5px;   -webkit-border-top-left-radius: 5px;   -webkit-border-top-right-radius: 5px;background: #777; border: 0; float: left; margin: 0 0 0 5px; padding: 8px 13px 6px; color: #fff;
	}
	.premiumpress_box .header ul a.active,
	.premiumpress_box .header ul a:hover,
	.premiumpress_box .header ul a:focus,
	.premiumpress_box .header ul a:active {background: #fff url("<?php bloginfo('template_url'); ?>/images/premiumpress/headertab.png") repeat-x; color: #444;}
	.altbox .header ul a {background-color: #d44848;}
	.altbox .header ul a.active,
	.altbox .header ul a:hover,
	.altbox .header ul a:focus,
	.altbox .header ul a:active {background: #fff url("<?php bloginfo('template_url'); ?>/images/premiumpress/red/altheadertab.png") repeat-x; color: #8e0f0f;}
	.premiumpress_box .content {margin-bottom: 5px;}
	.premiumpress_box .content table {width: 100%;}
	.premiumpress_box .content table th,
	.premiumpress_box .content table td {padding: 10px 10px 8px 10px;}
	.premiumpress_box .content table th {text-align: left; font-weight: normal;}
	.premiumpress_box .content table tr.even th,
	.premiumpress_box .content table tr.even td {background: #f5f5f5;}
	.altbox .content table tr.even th,
	.altbox .content table tr.even td {background: #fff0f0;}
	.premiumpress_box .content table th.first,
	.premiumpress_box .content table td.first {padding-left: 20px;}
	.premiumpress_box .content table thead th,
	.premiumpress_box .content table thead td {border-left: 1px solid #f2f2f2; border-right: 1px solid #d5d5d5; background: #ddd url("<?php bloginfo('template_url'); ?>/images/premiumpress/thead.gif") repeat-x; text-shadow: #fff 0 1px 0;}
	.premiumpress_box .content table tbody tr.first th,
	.premiumpress_box .content table tbody tr.first td {border-top: 1px solid #bbb;}
	.altbox .content table tbody {color: #732222;}
	.premiumpress_box .content table a.ico-comms {border: 0; background: url("<?php bloginfo('template_url'); ?>/images/premiumpress/red/ico-tablecomms.gif") 50% 60% no-repeat; padding: 10px; color: #fff;}
	.premiumpress_box .content table tfoot th,
	.premiumpress_box .content table tfoot td {border-top: 1px solid #ccc; background: #fff url("<?php bloginfo('template_url'); ?>/images/premiumpress/tfoot.gif") repeat-x;}
	.premiumpress_box .content ul.simple li {clear: both; padding: 10px 20px 8px 20px; overflow: hidden;}
	.premiumpress_box .content table tr.even th,
	.premiumpress_box .content ul.simple li.even {background: #f5f5f5;}
	.altbox .content table tr.even th,
	.altbox .content ul.simple li.even {background: #fff0f0;}
	.premiumpress_box .content ul.simple strong {float: left; font-weight: normal;}
	.premiumpress_box .content ul.simple span {float: right;}
	.premiumpress_box .content .grid {}
	.premiumpress_box .content .grid .line {border-bottom: 1px solid #ddd; width: 100%; overflow: hidden;}
	.altbox .content .grid .line {border-bottom-color: #f4d3d3;}
	.premiumpress_box .content .grid .even {background: #f5f5f5;}
	.altbox .content .grid .even {background: #fff0f0;}
	.premiumpress_box .content .grid .item {float: left; width: 50%;}
	.premiumpress_box .content .grid .item .inner {padding: 17px 15px 12px 20px;}
	.premiumpress_box .content .grid .firstline {border-top: 0 !important;}
	.premiumpress_box .content .grid .lastline {border-bottom: 0 !important;}
	.premiumpress_box .content .grid .item a.thumb {border: 0; float: left;}
	.premiumpress_box .content .grid .item .data {margin-left: 165px;}
	.premiumpress_box .content .grid .item h4 {margin: 0 0 10px 0; font-size: 110%; font-weight: bold;}
	.premiumpress_box .content .grid .item h4 span {margin-right: 5px; font-weight: normal; font-size: 90%;}
	.premiumpress_box .content .grid .item p {margin: 0 0 5px 0; color: #666;}
	.premiumpress_box .content .grid ul.actions {margin-top: 8px;}
	.pagination {border-top: 1px solid #999; background: #fff url("<?php bloginfo('template_url'); ?>/images/premiumpress/pagination.gif") repeat-x; text-align: center; color: #333 !important;}
	.pagination ul {position: relative; top: -1px; padding: 12px 10px 6px;}
	.pagination ul li {display: inline;}
	.pagination a {border: 0; background: #ebebeb url("<?php bloginfo('template_url'); ?>/images/premiumpress/pagination-item.gif") repeat-x; margin: 0 5px; padding: 6px 10px; color: #333 !important;
	border-radius: 3px;   -moz-border-radius: 3px;   -webkit-border-radius: 5px;}
	.pagination a:hover,
	.pagination a:active,
	.pagination a:focus {color: #b10d0d !important;}
	.pagination strong {background: url("<?php bloginfo('template_url'); ?>/images/premiumpress/pagination-arrow.gif") 50% 0 no-repeat; padding: 15px 10px 8px;}
	table.calendar {width: 100%;}
	table.calendar thead {zoom: 1;}
	table.calendar thead tr {zoom: 1;}
	table.calendar thead th.month {border-bottom: 1px solid #bbb; font-weight: bold; font-size: 120%; zoom: 1;}
	table.calendar thead th.month a {border: none; position: relative; top: 5px; margin: 0 10px; zoom: 1;}
	table.calendar th,
	table.calendar td {width: 14.3%; text-align: center;}
	table.calendar tbody td,
	table.calendar tbody th {border: 1px solid #ddd; border-top-color: #fff; border-left-color: #fff;}
	table.calendar strong {font-size: 140%;}
	table.calendar .inactive {color: #aaa;}
	table.calendar div.items a {border: 0; margin: 0 1px;} 
	.tag {padding: 4px 7px; color: #fff !important;border-radius: 3px;   -moz-border-radius: 3px;   -webkit-border-radius: 3px;}
	.tag-gray { border-bottom: 1px solid #666;background: #999;background: gradient(linear, left top, left bottom, from(#bbb), to(#999));background: -webkit-gradient(linear, left top, left bottom, from(#bbb), to(#999));
	}
	
	h2 { margin-bottom: 20px; }.title { margin: 0px !important; background: #DFDFDF repeat-x scroll left top; padding: 10px; font-family: Georgia, serif; font-weight: normal !important; letter-spacing: 1px; font-size: 18px; }.container { background: #EAF3FA; padding: 10px; }.maintable { font-family:"Lucida Grande","Lucida Sans Unicode",Arial,Verdana,sans-serif; background: ##F9F9F9; margin-bottom: 20px; padding: 10px 0px; border:1px solid #E6E6E6; width: 100%;}.mainrow { padding-bottom: 10px !important; border-bottom: 1px solid #E6E6E6 !important; float: left; margin: 0px 10px 10px 10px !important; }.titledesc { font-size: 12px; font-weight:bold; width: 220px !important; margin-right: 20px !important; }.forminp { width: 700px !important; valign: middle !important; }.forminp input, .forminp select, .forminp textarea { margin-bottom: 9px !important; background: #fff; border-top: 1px solid #ccc; border-left: 1px solid #ccc; padding: 4px; font-family:"Lucida Grande","Lucida Sans Unicode",Arial,Verdana,sans-serif; font-size: 12px; }.info { background: #FFFFCC; border: 1px dotted #D8D2A9; padding: 10px; color: #333; }.forminp .checkbox { width:20px }.info a { color: #333; text-decoration: none; border-bottom: 1px dotted #333 }.info a:hover { color: #666; border-bottom: 1px dotted #666; }.warning { background: #FFEBE8; border: 1px dotted #CC0000; padding: 10px; color: #333; font-weight: bold; }/* front grid */.frontleft { width: 500px; }.frontright { width: 500px; } .gdpttitle span {	font-size: 12px;	vertical-align: 14px;	color: red;	font-weight: bold;	margin-left: 5px;}.gdrgrid .disabled { color: gray; }.gdrgrid .table {	background: #F9F9F9 none repeat scroll 0 0;	border-bottom: 1px solid #ECECEC;	border-top: 1px solid #ECECEC;	margin: 0 -9px 10px;	padding: 0 10px;	table-layout: fixed;}.gdrgrid div.inside { margin: 10px; }.gdrgrid p.sub {	color: #777777;	font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;	font-size: 13px;	font-style: italic;	padding: 5px 10px 15px;	margin: -12px;}.gdrgrid table { width: 100%; }.gdrgrid table tr.first th { color: #990000; font-weight: bold; }.gdrgrid table tr.first th, .gdrgrid table tr.first td { border-top: medium none; }.gdrgrid td {	border-top: 1px solid #ECECEC;	padding: 2px 0;	white-space: nowrap;	font-size: 18px;}.gdrgrid td.b, .gdrgrid th.first {	font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;	font-size: 16px;	padding-right: 6px;	white-space: nowrap;	text-align: right;}.gdrgrid td.first, .gdrgrid td.last { width: 1px; }.gdrgrid td.options {	text-align: right;	white-space: normal;	padding-right: 0 !important;}.gdrgrid td.t { white-space: normal; padding-bottom: 3px; }.gdrgrid td.t, .gdrgrid th {	color: #777777;	font-size: 12px;	padding-right: 12px;	padding-top: 6px;}.gdrgrid th {	text-align: left;	background-color: #ECECEC;	padding: 3px 5px;}.panel { padding: 4px; }.paneltext { font-size: 11px; vertical-align: baseline; }.postbox .hndle { cursor: default !important; }.regular-text { border: 1px solid #8CBDD5; }.rssSummary { font-size: 11px; }.rssTitle { background-color: #DFDFDF; padding: 1px 6px; } .ssclear { clear: both; }
	
	
	
	<?php if(isset($_GET['page']) && $_GET['page'] == "quickhelp"  ){ ?>
	
	.panel{	background-color: #ffffff;	border: 1px solid #dbe6ee;	padding: 10px;	-moz-border-radius: 3px;	-webkit-border-radius: 3px;	border-radius: 3px;	width:780px;}.step{	float: left;	position: relative;	margin-left: -20px;	width: 351px;	height: 43px;	font-size: 16px;	padding-left: 20px;	padding-top: 18px;	background-image: url("<?php echo PPT_FW_IMG_URI; ?>sprite_with_icons.png");	background-repeat: no-repeat;	outline: none;}.step1, .step2, .step3{width: 237px; }.stepLabel{	position: absolute;	top: 19px;	right: 30px;}.stepLabelLast{	position: absolute;	top: 19px;	right: 25px;}.content{	clear: both;	padding: 0px;	line-height: 150%;}.nextPrevButtons { border-top:1px solid #ddd; padding-top:20px;}.nextPrevButtons .button:first-child{	margin-right: 10px;}.nextPrevButtons .button{float: left;	display: block;	width: 150px;	height: 34px;	padding-top: 12px;	text-align: center;	background:none; background-image: url("<?php echo PPT_FW_IMG_URI; ?>sprite_buttons.png");	background-repeat: no-repeat;	cursor: pointer; border:0px; font-size:14px !important}.nextPrevButtons .inactiveButton{	background-position: -10px -310px;	color: #ffffff;}.boxStart{	float: left;	width: 6px;	height: 60px;	background: url("<?php echo PPT_FW_IMG_URI; ?>sprite_with_icons.png") no-repeat;} .step-green-blue{	color: #ffffff;	background-position: -10px -170px;}.step1-green-blue, .boxStart-green-blue{	background-position: -10px -90px;}.nextPrevButtons .activeButton-green-blue{	color: #ffffff;	background-position: -10px -130px;}.nextPrevButtons .activeButton-green-blue:hover{	color: #ffffff;	background-position: -10px -70px;}
	.bar{	width:770px;	height: 52px;	border-right:0px;background: url("<?php echo PPT_FW_IMG_URI; ?>aspp_bar.png") no-repeat;	color: #5596bc;	margin-bottom: 15px;	margin-top: 0px;	padding-left: 30px;	padding-top: 18px;}
	
	.title{	margin-top: 15px;	font-size: 26px;	color: #666;}.button_green{	background-image: url("<?php echo PPT_FW_IMG_URI; ?>live_preview_img/aspp_button_green.png");}.button_blue{	background-image: url("<?php echo PPT_FW_IMG_URI; ?>live_preview_img/aspp_button_blue.png");}.p2Step1, .p2Step2, .p2Step3, .p2Step4{	width: 173px;}.p3Step1, .p3Step2, .p3Step3, .p3Step4{	width: 173px;}.p4Step1{	width: 202px;}.p4Step2{	width: 160px;}.p4Step3{	width: 135px;}.p4Step4{	width: 100px;}.p4Step5{	width: 85px;}.p5Step1, .p5Step2, .p5Step3{	width: 240px;}.p6Step1, .p6Step2, .p6Step3, .p6Step4{	width: 175px;}.p7Step1, .p7Step2{	width: 371px;}.p8Step1, .p8Step2, .p8Step3{	width: 240px;}
	
	<?php } ?>
	
	
	</style>
	<?php
	
	}


 

	/**
	 * Magic hook: Define the update tool for PremiumPress
	 * @since 0.3.0
	 */ 

 
	function premiumpress_updates(){  


	$msg = $this->PremiumPress_ValidateMe(strip_tags(get_option("license_key"))); 
	$whatNow = explode("**",$msg);
	$showBox = $whatNow[0];
	$showMessage = $whatNow[1];
	if($showBox ==0){
		update_option("license_key","");
	} 
	
	include(TEMPLATEPATH."/PPT/class/class_update.php");
	$updateME = new PremiumPress_Update; 
 
	PremiumPress_Header(); 

	?>
	
	
	<div class="premiumpress_box premiumpress_box-50 altbox"> 
	<div class="premiumpress_boxin"><div class="header"><h3><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/h-ico/rss.png" align="middle"> Latest News </h3></div>
	<form class="fields" style="padding:10px;">
	<fieldset class="last">
	<legend><strong>Latest PremiumPress News</strong></legend>
	<?php $this->premiumpress_rssfeed("http://www.premiumpress.com/feed/?post_type=blog_type"); ?>
	</ul>
    </fieldset>
	</form>	
	</div></div> 


    <div class="premiumpress_box premiumpress_box-50 altbox"> 
    <div class="premiumpress_boxin"><div class="header"><h3><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/h-ico/search.png" align="middle"> Helpful Information </h3>
    <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('update')">Help Me</a> 
    
    </div>
    <form class="fields" style="padding:10px;">
    
    <?php 
    
        if(isset($_GET['updateme'])){
        
            echo $updateME->STARTUPDATE();
        
        }else{
        
            echo $updateME->Check();
        } ?>
    
    <fieldset class="last">
    <legend><strong>Theme Information </strong></legend>
    
    <div>
    
    <table class="">
    
        
    
        <tr class="mainrow">
            <td class="titledesc">Name</td>
            <td class="forminp"><?php if(defined('PREMIUMPRESS_SYSTEM')){ echo PREMIUMPRESS_SYSTEM; }elseif(defined('PREMIUMPRESS_PLUGIN')){ echo PREMIUMPRESS_PLUGIN; } ?></td>
        </tr>
        <tr class="mainrow">
            <td class="titledesc">Version Number</td>
            <td class="forminp"><?php if(defined('PREMIUMPRESS_VERSION')){ echo PREMIUMPRESS_VERSION;}elseif(defined('PREMIUMPRESS_PLUGIN_VERSION')){ echo PREMIUMPRESS_PLUGIN_VERSION; } ?></td>
        </tr>
        <tr class="mainrow">
            <td class="titledesc">Last Updated</td>
            <td class="forminp"><?php if(defined('PREMIUMPRESS_VERSION_DATE')){  echo PREMIUMPRESS_VERSION_DATE; }elseif(defined('PREMIUMPRESS_PLUGIN_VERSION_DATE')){ echo PREMIUMPRESS_PLUGIN_VERSION_DATE; } ?></td>
        </tr>
        
        <tr class="mainrow">
            <td class="titledesc">License Key</td>
            <td class="forminp"><?php echo get_option("license_key");?></td>
        </tr>
        
        
    
    
    </table>
    
    </fieldset><br />
    
    <fieldset class="last">
    <legend><strong>Support Information </strong></legend>
    
    <table class="">
    
        <tr class="mainrow">
            <td class="titledesc">Manuals</td>
            <td class="forminp"><a href="http://www.premiumpress.com/documentation/?adminlink=<?php if(defined('PREMIUMPRESS_SYSTEM')){ echo PREMIUMPRESS_SYSTEM; }elseif(defined('PREMIUMPRESS_PLUGIN')){ echo PREMIUMPRESS_PLUGIN; } ?>" target="_blank">Visit Website</a></td>
        </tr>
        <tr class="mainrow">
            <td class="titledesc">Forums</td>
            <td class="forminp"><a href="http://www.premiumpress.com/forum/?adminlink=<?php if(defined('PREMIUMPRESS_SYSTEM')){ echo PREMIUMPRESS_SYSTEM; }elseif(defined('PREMIUMPRESS_PLUGIN')){ echo PREMIUMPRESS_PLUGIN; } ?>" target="_blank">Visit Website</a></td>
        </tr>
        <tr class="mainrow">
            <td class="titledesc">Video Tutorials</td>
            <td class="forminp"><a href="http://www.premiumpress.com/videos/?adminlink=<?php if(defined('PREMIUMPRESS_SYSTEM')){ echo PREMIUMPRESS_SYSTEM; }elseif(defined('PREMIUMPRESS_PLUGIN')){ echo PREMIUMPRESS_PLUGIN; } ?>" target="_blank">Visit Website</a></td>
        </tr>
    
    </table>
    </fieldset><br />
    
    
    
    <fieldset class="last">
    <legend><strong>Cron Information </strong></legend>
    
    <table class="">
    
        <tr class="mainrow">
            <td class="titledesc">Hourly</td>
            <td class="forminp"><?php echo date('l jS \of F Y h:i:s A',wp_next_scheduled( "ppt_hourly_event"));?></td>
        </tr>
        
        <tr class="mainrow">
            <td class="titledesc">Twice Daily</td>
            <td class="forminp"><?php echo date('l jS \of F Y h:i:s A',wp_next_scheduled( "ppt_twicedaily_event"));?></td>
        </tr>    
     
         <tr class="mainrow">
            <td class="titledesc">Daily</td>
            <td class="forminp"><?php echo date('l jS \of F Y h:i:s A',wp_next_scheduled( "ppt_daily_event"));?></td>
        </tr>   
        <?php
        
        if(isset($_GET['rcron'])){
        wp_clear_scheduled_hook('ppt_hourly_event');
        wp_clear_scheduled_hook('ppt_twicedaily_event');
        wp_clear_scheduled_hook('ppt_daily_event');	
        }	    
    
        ppt_event_activation(); // REGISTER CRON SCHEDULES
        ?>
    
    </table>
    <p>Click here to <a href="admin.php?page=updates&rcron=1">reset the cron times.</a></p>
    </fieldset><br />
     
    </form>
    </div></div> 
     
      
    
    <?php } 



	/**
	 * Magic hook: Define the update tool for PremiumPress
	 * @since 0.3.0
	 */  

	function premiumpress_admin_licensekey_form(){
	
	if(isset($_POST['action_key'])){
	
		$msg = $this->PremiumPress_ValidateMe(strip_tags($_POST['premiumpress_key']));
		$whatNow = explode("**",$msg);
	
		$showBox = $whatNow[0];
		$showMessage = $whatNow[1];
	
		if($showBox ==1){
			update_option("license_key",strip_tags($_POST['premiumpress_key']));
		}
	}
	
	if(!isset($showBox) ){
		$showBox = 3;
		$showMessage = $this->hexToStr("506c6561736520656e74657220796f7572205072656d69756d507265737320564950206c6963656e7365206b657920696e746f2074686520626f782062656c6f772e496620796f752061726520756e737572652077686174207468697320697320706c65617365206c6f67696e20746f20796f757220564950206163636f756e74206174207777772e7072656d69756d70726573732e636f6d");
	}
	
	?>
	<br />
	<div class="premiumpress_box premiumpress_box-50 altbox"> 
	<div class="premiumpress_boxin"><div class="header"><h3><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/h-ico/lock.png" align="middle"> License Key</h3></div>
	<form class="fields" style="padding:10px;" method="post" target="_self">
	<input type="hidden" name="action_key" value="1">
	<fieldset class="last">
	<legend><strong>VIP License Key</strong></legend>
	<div class="msg <?php if($showBox == 0){ ?>msg-error<?php }elseif($showBox == 1){ ?>msg-ok<?php }else{ ?>msg-info<?php } ?>"><p><?php echo $showMessage; ?></p></div> 
	<?php if($showBox != 1){ ?><label>Enter License Key</label>
	<input name="premiumpress_key" type="text" class="txt" value="<?php echo get_option("license_key"); ?>">             
	<input type="submit" class="premiumpress_button" value="save" style="color:white;">
	<?php } ?>
	</fieldset>
	
	<?php if($showBox == 1){ ?><a href="admin.php?page=ppt_admin.php">Click here to get started</a> <?php } ?>
	</form>
	</div></div> 
	
	<?php 
	
	}  









function PremiumPress_ValidateMe($key){

	if(!is_numeric($key)){
		return "0**".$this->hexToStr("496e76616c6964204c6963656e7365204b6579");
	}
	
	$msg = $this->PremiumPress_HelpFiles($key);
	
	return $msg;

}
function PremiumPress_HelpFiles($helpme){
	 
		$helpme = $helpme;
		$installed_host = $this->hexToStr("636c69656e74732e7072656d69756d70726573732e636f6d");
		$installed_directory=""; 
		$query_string	 = $this->hexToStr("6c6963656e73653d").$helpme;		
		$query_string	.= $this->hexToStr("266163636573735f69703d").$_SERVER['SERVER_ADDR'];
		$query_string	.= $this->hexToStr("266163636573735f7468656d653d").PREMIUMPRESS_SYSTEM;
		$query_string	.= $this->hexToStr("266163636573735f686f73743d").$_SERVER['HTTP_HOST'];			
		
		$data=$this->PremiumPress_exec_socket($installed_host, $installed_directory, "/validate.php", $query_string);
		$parser=@xml_parser_create('');
		@xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		@xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		@xml_parse_into_struct($parser, $data, $values, $tags);
		@xml_parser_free($parser);

		$returned=$values[0]['attributes'];
 

		if ($returned['status']=="invalid")
			{
				$error="0**".$this->hexToStr("496e76616c6964204c6963656e7365204b6579");
			}
		
		elseif ($returned['status']=="suspended")
			{
				$error="0**".$this->hexToStr("4c6963656e7365204b65792053757370656e646564");
			}

		else{
			
			$error="1**".$returned['message'];
		}
		
		return $error;
}

function PremiumPress_exec_socket($http_host, $http_dir, $http_file, $querystring)
	{
			 
	$fp=@fsockopen($http_host, 80, $errno, $errstr, 5); 
	if (!$fp) { return false; }
	else
		{
		$header="POST ".($http_dir.$http_file)." HTTP/1.0\r\n";
		$header.="Host: ".$http_host."\r\n";
		$header.="Content-type: application/x-www-form-urlencoded\r\n";
		$header.="User-Agent: PremiumPress (www.premiumpress.com)\r\n";
		$header.="Content-length: ".@strlen($querystring)."\r\n";
		$header.="Connection: close\r\n\r\n";
		$header.=$querystring;

		$data=false;		
		@fputs($fp, $header);
		
		$status=@socket_get_status($fp);
		while (!@feof($fp)&&$status) 
			{ 
			$data.=@fgets($fp, 1024);
			
			$status=@socket_get_status($fp);
			}
		@fclose ($fp);
 
		if (!$data) { return false; }		
		$data=explode("\r\n\r\n", $data, 2);
		return $data[1];
		}
}
function hexToStr($hex)
{
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2)
    {
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}





function premiumpress_rssfeed($link) {	wp_widget_rss_output($link, array('items' => 10, 'show_author' => 0, 'show_date' => 1, 'show_summary' => 1)); }




 	/**
	 * Processes the form data and updates the database with the new values.
	 */
	function process_form_data() {
	
	// do something soon
	
	//die("ads");
	
		
	} 
	
}

function PremiumPress_Header(){  

// no longer used 

}	

?>