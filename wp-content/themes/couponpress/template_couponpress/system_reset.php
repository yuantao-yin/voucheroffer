<?php

if(!function_exists("selfURL1")){ function selfURL1() {
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = "http".$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
			: (":".$_SERVER["SERVER_PORT"]);
		return $protocol."://".$_SERVER['SERVER_NAME'].$port;
	}
}

if(!function_exists("FilterPath")){

	function FilterPath(){
		$path=dirname(realpath($_SERVER['SCRIPT_FILENAME']));
		$path_parts = pathinfo($path);
		return $path;
	}
}

if(function_exists("get_option")){ 

	// DELETE DEFAULT LINKS
	if(isset($_POST['RESETME']) && $_POST['RESETME'] =="yes"){

		mysql_query("DELETE FROM $wpdb->links");
		mysql_query("DELETE FROM $wpdb->posts");
		mysql_query("DELETE FROM $wpdb->postmeta");
		mysql_query("DELETE FROM $wpdb->terms");
		mysql_query("DELETE FROM $wpdb->term_taxonomy");
		mysql_query("DELETE FROM $wpdb->term_relationships");

	}

// CATEGORY SETUP
 

$pages_array = "";

// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'Articles';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($CATID);
$page1_id = wp_insert_post( $page1 );
$articles_page = $page1_id;
update_post_meta($page1_id , '_wp_page_template', 'tpl-articles.php');
// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'Contact';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($CATID);
$page1_id = wp_insert_post( $page1 );
update_post_meta($page1_id , '_wp_page_template', 'tpl-contact.php');
// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'Submit';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($CATID);
$page1_id = wp_insert_post( $page1 );
$pages_array .= $page1_id.",";
update_post_meta($page1_id , '_wp_page_template', 'tpl-add.php');

// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'My Account';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($CATID);
$page1_id = wp_insert_post( $page1 );
update_post_meta($page1_id , '_wp_page_template', 'tpl-myaccount.php');

// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'Store List';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($CATID);
$page1_id = wp_insert_post( $page1 );
update_post_meta($page1_id , '_wp_page_template', 'tpl-stores.php');

// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'Messages';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($CATID);
$page1_id = wp_insert_post( $page1 );
update_post_meta($page1_id , '_wp_page_template', 'tpl-messages.php');

// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'Manage';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($CATID);
$page1_id = wp_insert_post( $page1 );
update_post_meta($page1_id , '_wp_page_template', 'tpl-edit.php');

// CREATE PAGES
$page1 = array();
$page1['post_title'] = 'Callback';
$page1['post_content'] = '';
$page1['post_status'] = 'publish';
$page1['post_type'] = 'page';
$page1['post_author'] = 1;
$page1['post_category'] = array($ARTID);
$page1_id = wp_insert_post( $page1 );
$pages_array .= $page1_id.",";
update_post_meta($page1_id , '_wp_page_template', 'tpl-callback.php');


wp_delete_term( $ARTID+1, 'category' );

// PAGE VALES
update_option("submit_url", 	selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."submit/");
update_option("manage_url", 	selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."manage/");
update_option("dashboard_url",	selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."my-account/");
update_option("sitemap_url", 	selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."sitemap.xml");
 update_option("contact_url", 	selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."contact/");
 update_option("messages_url", 	selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."messages/");
 
// IMAGE STORAGE PATHS
update_option("imagestorage_link",selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."wp-content/themes/couponpress/thumbs/");
update_option("imagestorage_path",str_replace("wp-admin","wp-content/themes/couponpress/thumbs/",str_replace("\\","/",FilterPath())));

// NAVIGATION CATEGORIES
update_option("nav_cats","1,89,90,91,92");
update_option("article_cats","-97,-".$CATID);

// POST PRUN SETTINGS
update_option("post_prun","no");
update_option("prun_period","30");

// HOME PAGE SETUP
update_option("homepage_title","Featured Coupons");  
update_option("featured_items",""); 
update_option("display_previewimage","yes");


// ADVERTISING BOXES
update_option("advertising_right_checkbox","1");
update_option("advertising_top_checkbox", "1");
update_option("advertising_top_adsense", '<a href="http://www.'.''.'premiumpress.com/?source=couponpress"><img src="http://www.premiumpress.com/banner/468x60_1.gif" alt="premium wordpress themes" /></a>');
update_option("advertising_right_checkbox", "1");
update_option("advertising_right_adsense", '<div style="padding-left:0px;"><a href="http://www.premiumpress.com/?source=free-couponpress"><img src="http://www.premiumpress.com/banner/125x125.gif" alt="premium wordpress themes" /></a></div>');

update_option("advertising_footer_checkbox", "1");
update_option("advertising_footer_adsense", '<a href="http://www.'.''.'premiumpress.com/?source=auctionpress" target="_blank"><img src="http://www.premiumpress.com/banner/300x250.gif" /></a>');


/* ================ EXAMPLE CATEGORIES ===================== */

$my_cat = array('cat_name' => 'Wordpress Themes', 'category_description' => 'deals on Wordpress themes', 'category_nicename' => 'wordpress-themes', 'category_parent' => '');
$cat_id = wp_insert_category1($my_cat); 

$id[0]['ID'] = wp_create_category1('PremiumPress', $cat_id);
$id[1]['ID'] = wp_create_category1('Elegant Themes', $cat_id);
$id[2]['ID'] = wp_create_category1('PageLines', $cat_id);
$id[3]['ID'] = wp_create_category1('StudioPress', $cat_id);
$id[4]['ID'] = wp_create_category1('StyleWP', $cat_id);
$id[5]['ID'] = wp_create_category1('Viva Themes', $cat_id);
$id[6]['ID'] = wp_create_category1('WooThemes', $cat_id);
$id[7]['ID'] = wp_create_category1('OmniTheme', $cat_id);

$my_cat = array('cat_name' => 'Printable Coupons', 'category_description' => 'example of printable coupons', 'category_nicename' => 'printable-coupons', 'category_parent' => '');
$cat_id1 = wp_insert_category1($my_cat); 

$id1[0]['ID'] = wp_create_category1('Example Sub Category', $cat_id1);

$my_cat = array('cat_name' => 'Hosting Coupons', 'category_description' => 'great offers on hosting', 'category_nicename' => 'hosting-coupons', 'category_parent' => '');
$cat_id2 = wp_insert_category1($my_cat); 

$id2[0]['ID'] = wp_create_category1('HostMonster', $cat_id2);
$id2[1]['ID'] = wp_create_category1('Host Gator', $cat_id2);
$id2[2]['ID'] = wp_create_category1('Yahoo Business', $cat_id2);
$id2[3]['ID'] = wp_create_category1('BlueHost', $cat_id2);
$id2[4]['ID'] = wp_create_category1('Godaddy ', $cat_id2);
 

update_option("featured_stores",$id);

update_option("cat_extra_text_".$id[0]['ID'],"<p>PremiumPress design and develop quality, feature rich Premium Wordpress Themes for small, medium and large businesses.</p>
<p>All our premium Wordpress themes are constantly improving, developed by experts and include unlimited installations, lifetime free support and upgrades!</p>");

update_option("cat_extra_text_".$id[1]['ID'],"<p>I created this website to provide WordPress themes of quality and integrity. I craft my themes with a goal of simplicity and professionalism and strive to inject each design with a dose of modest elegance. I believe that your website is not just a tool, it is an integral part of your identity. </p>
<p>My job is to respect each customer by providing attractive and userfriendly templates that will help you achieve your online goals.</p>");
update_option("cat_extra_text_".$id[2]['ID'],"<p>Your website is important. That's why we create both premium & free CMS themes for WordPress that make it easy to have a great looking website with tons of capability.</p>");
update_option("cat_extra_text_".$id[3]['ID'],"<p>StudioPress themes are a perfect solution for small businesses or individuals looking to establish their online presence with a WordPress blog or website.</p>");
update_option("cat_extra_text_".$id[4]['ID'],"<p>Premium Wordpress themes are ready-made website templates, that can transform your dull & boring blog into an exciting and interactive website - no coding skills necessary!</p>");
update_option("cat_extra_text_".$id[5]['ID'],"<p>We create quality and professionally designed wordpress themes. We offer themes for your business, blog or personal sites. All of that comes with our excellent free support.</p>");

update_option("cat_extra_text_".$id[6]['ID'],"<p>After you become a member, you become part of an ever growing community of Woo Members, where we listen to what you have to say. We can then interact with you on a whole new level and shape our future theme releases towards your needs.</p>");
update_option("cat_extra_text_".$id[7]['ID'],"<p>Omni Theme WordPress theme that is great for MySpace or Facebook users that are looking for their own website or blog with a more professional and custom look.</p>");


update_option("cat_extra_image_".$id[0]['ID'],"example_store_0.png");
update_option("cat_extra_image_".$id[1]['ID'],"example_store_1.png");
update_option("cat_extra_image_".$id[2]['ID'],"example_store_2.png");
update_option("cat_extra_image_".$id[3]['ID'],"example_store_3.png");
update_option("cat_extra_image_".$id[4]['ID'],"example_store_4.png");
update_option("cat_extra_image_".$id[5]['ID'],"example_store_5.png");
update_option("cat_extra_image_".$id[6]['ID'],"example_store_6.png");
update_option("cat_extra_image_".$id[7]['ID'],"example_store_7.png");



// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "ShopperPress";
$my_post['post_content'] 	= "ShopperPress is a fully featured shopping cart plugin for wordpress, suitable for selling any types of products, services, and digital downloads online.";
$my_post['post_excerpt'] 	= "This is an example search description for this coupon. Edit this in the admin.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[0]['ID']);
$my_post['tags_input'] 		= "tag1,tag2,United Kingdom";
$POSTID 					= wp_insert_post( $my_post );

add_post_meta($POSTID, "style", "g_new");	
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.shopperpress.com");	
add_post_meta($POSTID, "link", "http://www.shopperpress.com/?afflink=1");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "DiscountShopper");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "1");
// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "DirectoryPress";
$my_post['post_content'] 	= "DirectoryPress is a fully featured directory theme for Wordpress, it allows you to turn your standard wordpress blog into a powerful online link directory.";
$my_post['post_excerpt'] 	= "This is an example search description for this coupon. Edit this in the admin.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[0]['ID']);
$my_post['tags_input'] 		= "tag1,tag2,United Kingdom";
$POSTID 					= wp_insert_post( $my_post );

add_post_meta($POSTID, "style", "b_featured");	 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.directorypress.net");	
add_post_meta($POSTID, "link", "http://www.directorypress.net/?afflink=1");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "DiscountDirectory");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "1");
// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "CouponPress";
$my_post['post_content'] 	= "CouponPress turns a normal Wordpress blog into a professional looking coupon code or voucher code website in minutes";
$my_post['post_excerpt'] 	= "This is an example search description for this coupon. Edit this in the admin.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[0]['ID']);
$my_post['tags_input'] 		= "tag1,tag2,United Kingdom";
$POSTID 					= wp_insert_post( $my_post );

add_post_meta($POSTID, "style", "o_updated");	 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.couponpress.com");	
add_post_meta($POSTID, "link", "http://www.couponpress.com/?afflink=1");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "DiscountCoupon");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0");
// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "ClassifiedsTheme";
$my_post['post_content'] 	= "Turn a standard Wordpress blog into a powerful, feature rich classifieds website easily with lots of great featured!";
$my_post['post_excerpt'] 	= "This is an example search description for this coupon. Edit this in the admin.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[0]['ID']);
$my_post['tags_input'] 		= "tag1,tag2,United Kingdom";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.classifiedstheme.com");	
add_post_meta($POSTID, "link", "http://www.classifiedstheme.com/?afflink=1");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "DiscountClassifieds");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0");


// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "AgentPress - Real Estate Theme";
$my_post['post_content'] 	= "The AgentPress theme is an ideal solution for real estate agents who are looking to market themselves and rise above competition. It is professionally designed and fully optimized!";
$my_post['post_excerpt'] 	= "10% Discount on this studiopress theme with free updates and future discounts on bulk purchases.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[3]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.studiopress.com");	
add_post_meta($POSTID, "link", "https://www.e-junkie.com/ecom/gb.php?cl=10214&c=ib&aff=80442");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "BH10");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0");


// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "BusinessCard Theme";
$my_post['post_content'] 	= "BusinessCard is a simple jQuery-powered, one-page theme that allows you to provide your visitors with clean and concise information. BusinessCard transforms your blog into an easily customizable website complete with enhanced javascript effects and a fully functional gallery. Despite its unconventional appearance, BusinessCard is quick and easy to set up and a breeze to manage. <img src='http://www.elegantthemes.com/v2/images/preview-businesscard-1.jpg' style='max-width:350px;'>";
$my_post['post_excerpt'] 	= "Get discounts on new wordpress theme purchases from Elegant Themes with this coupon code.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[1]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.elegantthemes.com/");	
add_post_meta($POSTID, "link", "http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=2207");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "10%off");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 

// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "EcoPro Wordpress Theme";
$my_post['post_content'] 	= "EcoPro is a full-width CMS WordPress theme with tons of options and templates. And when you buy, we donate 10% to help the environment!";
$my_post['post_excerpt'] 	= "Buy this theme and they will donate 10% to help the environment!";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[2]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.pagelines.com/");	
add_post_meta($POSTID, "link", "https://www.e-junkie.com/ecom/gb.php?cl=49427&c=ib&aff=80442");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "EcoPro");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 


// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "Save 25% on stylewp themes";
$my_post['post_content'] 	= "";
$my_post['post_excerpt'] 	= "Use this discount code to save upto 25% on selected Wordpress themes.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[4]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://stylewp.com/");	
add_post_meta($POSTID, "link", "https://www.e-junkie.com/ecom/gb.php?cl=15848&c=ib&aff=80442");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "stylewp");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 


// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "Save $$ on Nautilius theme";
$my_post['post_content'] 	= "Nautilius is a very clean, minimalist theme made with lots of purposes in mind. You can use the theme for business websites, portfolio, blog, personal and for many other topics.";
$my_post['post_excerpt'] 	= "Use this discount code to save $$$ on Viva Nautilius Wordpress themes.";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id[5]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "url", "http://www.vivathemes.com/");	
add_post_meta($POSTID, "link", "https://www.e-junkie.com/ecom/gb.php?cl=74949&c=ib&aff=80442");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "code", "10%off");
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 

// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "Example Printable Coupon";
$my_post['post_content'] 	= "This is an example printable coupon using CouponPress";
$my_post['post_excerpt'] 	= "This is an example printable coupon using CouponPress";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id1[0]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "expires", "");	
add_post_meta($POSTID, "image", "example_barcode.png");	
add_post_meta($POSTID, "link", "https://www.e-junkie.com/ecom/gb.php?cl=74949&c=ib&aff=80442");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 
add_post_meta($POSTID, "type", "print");



// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "HostMonster - 45% Saving";
$my_post['post_content'] 	= "$6.95 shared hosting, save upto 45% of most hosting packages";
$my_post['post_excerpt'] 	= "This is an example printable coupon using CouponPress";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id2[0]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "code", "hostmonster");	
add_post_meta($POSTID, "url", "http://www.hostmonster.com/");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 
add_post_meta($POSTID, "expires", "");	

// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "HostMonster - 45% Saving";
$my_post['post_content'] 	= "$75 unmanged dedicated servers, 33% savings ($25 immediate discount)";
$my_post['post_excerpt'] 	= "$75 unmanged dedicated servers, 33% savings ($25 immediate discount)";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id2[1]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "code", "cactus");	
add_post_meta($POSTID, "url", "http://www.hostgator.com/");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 
add_post_meta($POSTID, "expires", "");	

// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "Yahoo Hosting - Big Savings";
$my_post['post_content'] 	= "$7.46 web hosting, $25 savings (no setup fees)";
$my_post['post_excerpt'] 	= "$7.46 web hosting, $25 savings (no setup fees)";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id2[2]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "code", "yahoo");	
add_post_meta($POSTID, "url", "http://smallbusiness.yahoo.com/");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 
add_post_meta($POSTID, "expires", "");	


 // ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "BlueHost Coupon Code, 45% savings!";
$my_post['post_content'] 	= "$6.95 unlimited hosting, 45% savings (unlimited sites for $3.95)";
$my_post['post_excerpt'] 	= "$6.95 unlimited hosting, 45% savings (unlimited sites for $3.95)";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id2[3]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "code", "utah");	
add_post_meta($POSTID, "url", "http://www.BlueHost.com/");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 
add_post_meta($POSTID, "expires", "");	

 // ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "Godaddy Hosting - Coupon Code";
$my_post['post_content'] 	= "$4.49 economy hosting, $20 savings ($20 off on godaddy.com shared hosting)";
$my_post['post_excerpt'] 	= "$4.49 economy hosting, $20 savings ($20 off on godaddy.com shared hosting)";
$my_post['post_status'] 	= "publish";
$my_post['post_category'] 	= array($id2[4]['ID']);
$my_post['tags_input'] 		= "";
$POSTID 					= wp_insert_post( $my_post );
 
add_post_meta($POSTID, "code", "cjc20host");	
add_post_meta($POSTID, "url", "http://www.godaddy.com/");
add_post_meta($POSTID, "hits", "0");	
add_post_meta($POSTID, "packageID", "1");
add_post_meta($POSTID, "featured", "0"); 
add_post_meta($POSTID, "expires", "");	
 

/* ================ EXAMPLE ARTICLE ===================== */

// ADD NEW PRODUCTS
$my_post = array();
$my_post['post_title'] 		= "Example Website Article 1";
$my_post['post_content'] 	= "<h1>This is an example h1 title</h1> <h2>This is an example h2 title</h2> <h3>This is an example h3 title</h3> <br> <p>This is an example paragraph of text added via the admin area.</p> <p>This is an example paragraph of text added via the admin area.</p> <p>This is an example paragraph of text added via the admin area.</p> <ul><li>example list item 1</li><li>example list item 2</li><li>example list item 3</li><li>example list item 4</li><li>example list item 5</li></ul> <p>This is an example paragraph with a link in it, click here to the <a href='http://www.premiumpress.com' title='premium wordpress themes'>premium wordpress themes website.</a></p>";
$my_post['post_excerpt'] 	= "This is an example article that you can create for your website just like any normal Wordpress blog post. You can use the 'image' custom field to attach a prewview picture also!";
$my_post['post_status'] 	= "publish";
$my_post['post_type'] 		= "article_type";
$my_post['post_category'] 	= array($ARTID);
$my_post['tags_input'] 		= "article,blog";
$POSTID 					= wp_insert_post( $my_post );
add_post_meta($POSTID, "type", "article");	
add_post_meta($POSTID, "image", "article.jpg");	

$my_post = array();
$my_post['post_title'] 		= "Example Website Article 2";
$my_post['post_content'] 	= "<h1>This is an example h1 title</h1> <h2>This is an example h2 title</h2> <h3>This is an example h3 title</h3> <br> <p>This is an example paragraph of text added via the admin area.</p> <p>This is an example paragraph of text added via the admin area.</p> <p>This is an example paragraph of text added via the admin area.</p> <ul><li>example list item 1</li><li>example list item 2</li><li>example list item 3</li><li>example list item 4</li><li>example list item 5</li></ul> <p>This is an example paragraph with a link in it, click here to the <a href='http://www.premiumpress.com' title='premium wordpress themes'>premium wordpress themes website.</a></p>";
$my_post['post_excerpt'] 	= "This is an example article that you can create for your website just like any normal Wordpress blog post. You can use the 'image' custom field to attach a prewview picture also!";
$my_post['post_status'] 	= "publish";
$my_post['post_type'] 		= "article_type";
$my_post['post_category'] 	= array($ARTID);
$my_post['tags_input'] 		= "example tag,blog tag";
$POSTID 					= wp_insert_post( $my_post );
add_post_meta($POSTID, "type", "article");	
add_post_meta($POSTID, "image", "article.jpg");	



$pack = array (
	'1' => array (
		'enable' => '1',
		'name' => '<b>Free Listing</b> <br> Package',
		'price' => '0',
	),
	'2' => array (
		'enable' => '1',
		'name' => '<b>Basic Listing</b> <br> Package',
		'price' => '10',
		'expire' => '30',
		'rec' => '0',
		'a1' => '1',
		'a2' => '0',
		'a3' => '0',
	),
	'3' => array (
		'enable' => '1',
		'name' => '<b>Silver Listing</b> <br> Package',
		'price' => '25',
		'expire' => '30',
		'rec' => '0',
		'a1' => '1',
		'a2' => '1',
		'a3' => '0',
	),
	'4' => array (
		'enable' => '1',
		'name' => '<b>Gold Listing</b> <br> Package',
		'price' => '50',
		'expire' => '30',
		'rec' => '0',
		'a1' => '1',
		'a2' => '1',
		'a3' => '1',
		'a4' => '1',
	),
);
update_option("packages",$pack);	
update_option("pak_text","<h3>Introduce your package with a 'punchy' headline here!</h3><p>You can edit this description via the admin area and add <b>HTML code</> also to make it look better.");	


update_option("system","normal");	


$navbars[0]['ID'] 		= $cat_id;
$navbars[0]['ORDER'] 	= 1;
$navbars[1]['ID'] 		= $cat_id2;
$navbars[1]['ORDER'] 	= 2;
$navbars[2]['ID'] 		= $cat_id1;
$navbars[2]['ORDER'] 	= 3;

update_option("nav_cats",$navbars);



// DEFAULT SETTINGS
update_option("theme", "couponpress-default"); // do not remove
update_option("language", "language_english"); // do not remove
update_option("copyright", "Your Copyright Text Here");
update_option("logo_url","");
 update_option("display_previewimage_type","directory");


update_option("listbox_custom_title","Order Results By");	
update_option("footer_text","<h3>Welcome to our website!</h3><p><strong>Your introduction goes here!</strong><br />You can customize and edit this text via the admin area to create your own introduction text for your website.</p><p>You can customize and edit this text via the admin area to create your own introduction text for your website.</p>  ");	


// ENABLE PAYPAL TEST
$cb = selfURL1().str_replace("wp-admin/","",str_replace("admin.php?page=setup","",str_replace("themes.php?activated=true","",$_SERVER['REQUEST_URI'])))."callback/";

update_option("gateway_paypal","yes");
update_option("paypal_email","example@paypal.com");
update_option("paypal_return",$cb);
update_option("paypal_cancel",$cb);
update_option("paypal_notify",$cb);
update_option("paypal_currency","USD");

}

?>