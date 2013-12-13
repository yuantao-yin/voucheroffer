<?php
/* 
 * Plugin Name:   MaxBlogPress Ping Optimizer
 * Version:       2.2.5
 * Plugin URI:    http://www.maxblogpress.com/plugins/mpo/
 * Description:   Saves your wordpress blog from getting tagged as ping spammer by installing this plugin. Adjust your settings <a href="options-general.php?page=maxblogpress-ping-optimizer/maxblogpress-ping-optimizer.php">here</a>.
 * Author:        MaxBlogPress
 * Author URI:    http://www.maxblogpress.com
 *
 * License: Copyright (c) 2010 Pawan Agrawal. All rights reserved.
 * 
 * This plugin uses a commercial script library.
 * 
 * Please refer to "license.txt" file located at "mpo-lib/"
 * for copyright notice and end user license agreement.
 * 
 * 
 */

$mbp_po_path       	   = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', __FILE__);
$mbp_po_path       	   = str_replace('\\','/',$mbp_po_path);
$mbp_po_dir      	   = substr($mbp_po_path,0,strrpos($mbp_po_path,'/'));
$mbp_po_siteurl  	   = get_bloginfo('wpurl');
$mbp_po_siteurl  	   = (strpos($mbp_po_siteurl,'http://') === false) ? get_bloginfo('siteurl') : $mbp_po_siteurl;
$mbp_po_fullpath 	   = $mbp_po_siteurl.'/wp-content/plugins/maxblogpress-ping-optimizer/include/';
$mbp_po_incpath 	   = $mbp_po_siteurl.'/wp-content/plugins/maxblogpress-ping-optimizer/mpo-lib/include/';
$mbp_po_relpath  	   = str_replace('\\','/',dirname(__FILE__));
define('MBPO_NAME', 'MaxBlogPress Ping Optimizer');	
define('MBPO_VERSION', '2.2.5');					
define("MBPO_LOG", true);							
define("MBP_PO_PATH", $mbp_po_path);
define("MBP_PO_RELPATH", $mbp_po_relpath);
define("MBP_PO_SITEURL", $mbp_po_siteurl);
define("MBP_PO_INCPATH", $mbp_po_incpath);
require_once($mbp_po_relpath.'/mpo-lib/include/mbp-ping-optimizer.cls.php');

/**
 * PingOptimizer - MaxBlogPress Ping Optimizer Class
 * Holds all the necessary functions and variables
 */
class PingOptimizerPlugin extends PingOptimizer  
{
	var $mbpo_ping_option = 0;			// MaxBlogPress Ping Optimizer option
	var $mbpo_ping_sites = '';			// MaxBlogPress Ping Optimizer pinging URLs
	var $mbpo_future_pings = array();	// List of future posts to be pinged
	var $mbpo_future_ping_time = '';	// Last updated time for future ping
	var $mbpo_current_date = '';		// Holds the current date and time
	var $mbpo_post_title = "";			// Title of the post
	var $mbpo_edited = "";				// Set if post is edited
	var $mbpo_pvt_to_pub = "";			// Set if post status changes from private to published
	var $mbpo_max_log = 1000;   		// Maximum lines of log data to be stored
	var $mbpo_rows_to_show = 35;        // Number of log rows to be displayed in options page
	var $mpo_pinglog_tbl = 'mbp_ping_optimizer'; // Ping log table
    // Pinging action/type
	var $mpo_type =  array(1 => 'none', 2 => 'new', 3 => 'future', 4 => 'forced', 5 => 'edited', 6 => 'disabled', 7 => 'noservices', 8 => 'excessive');
	
	/**
	 * Constructor. Add MaxBlogPress Ping Optimizer plugin actions/filters and gets the user defined options.
	 * Also removes the default WordPress pinging services.
	 */
	function PingOptimizerPlugin() {
		global $wp_version, $table_prefix;
		$this->mpo_pinglog_tbl = $table_prefix.$this->mpo_pinglog_tbl;

		$this->wp_posts_table   = $table_prefix . $this->wp_posts_table;
		$this->wp_postmeta 		= $table_prefix . $this->wp_postmeta;
				
		$this->mbpo_abspath    = str_replace("\\","/",ABSPATH); 
		$this->mbpo_wp_version = $wp_version.

	    add_action('activate_'.MBP_PO_PATH, array(&$this, 'mbpoActivate'));
		add_action('deactivate_'.MBP_PO_PATH, array(&$this, 'mbpoDeactivate'));
		add_action('admin_menu', array(&$this, 'mbpoAddMenu'));
		
		$this->mpo_activate = get_option('mpo_activate');
		if ( $this->mpo_activate == 2 ) {
			add_action('wp_head', array(&$this, 'mbpoFuturePing'));
			add_filter('title_save_pre', array(&$this, 'mbpoGetPostTitle'));
			add_action('edit_post', array(&$this, 'mbpoEditPost'));		
			add_action('private_to_published', array(&$this, 'mbpoPrivateToPublished'));	
			add_action('save_post', array(&$this, 'mbpoPing'));
			add_action("delete_post", array(&$this, 'mbpoFuturePingDelete'));
			do_action('mbpo_ping', $post_title, $post_type);
			add_action('mbpo_ping', array(&$this, 'mbpoPingServices'), 5, 2);
			remove_action('do_pings', 'do_all_pings');
			remove_action("publish_post", "generic_ping");
		}
		$this->__mpoPingSet();
	}
	
	/**
	 * Called when plugin is activated. Adds option_value to the options table.
	 */
	function mbpoActivate() {
		$default_options = array('mpo_version' => MBPO_VERSION, 'limit_ping' => 0, 'limit_number' => 1, 'limit_time' => 15);
		add_option('mpo_options', $default_options);
		add_option('mpo_activate', 0);
		add_option('mbpo_ping_optimizer', 1, 'MaxBlogPress Ping Optimizer plugin options', 'no');
		if ( $this->mbpo_wp_version < 2.1 ) {
			add_option('mbpo_future_ping_time', date('Y-m-d-H-i-s'), 'MaxBlogPress Ping Optimizer plugin options', 'no');
		}
		$this->mbpoCreateLogTable();
		return true;
	}
	
	/**
	 * Called when plugin is deactivated. Deletes MaxBlogPress Ping Optimizer option from the options table.
	 */
	function mbpoDeactivate() {
		delete_option('mbpo_future_ping_time');
		delete_option('mbpo_future_pings');
		return true;
	}
	
	/**
	 * Creates ping log table
	 * @access public 
	 */
	function mbpoCreateLogTable() {
		$this->__mpoCreatePingTable();
	}

	/**
	 * Adds "MBPPingOptimizer" link to admin Options menu
	 */
	function mbpoAddMenu()	{
		add_options_page(MBPO_NAME, 'MBP Ping Optimizer', 'manage_options', MBP_PO_PATH, array(&$this, 'mbpoOptionsPg'));
	}
	
	/**
	 * Page Header
	 */
	function mbpoHeader() {
		if ( !isset($_GET['dnl']) ) {	
			$mbpo_version_chk = $this->mbpoRecheckData();
			if ( ($mbpo_version_chk == '') || strtotime(date('Y-m-d H:i:s')) > (strtotime($mbpo_version_chk['last_checked_on']) + $mbpo_version_chk['recheck_interval']*60*60) ) {
				$update_arr = $this->mbpoExtractUpdateData();
				if ( count($update_arr) > 0 ) {
					$latest_version   = $update_arr[0];
					$recheck_interval = $update_arr[1];
					$download_url     = $update_arr[2];
					$msg_in_plugin    = $update_arr[3];
					$msg_in_plugin    = $update_arr[4];
					$upgrade_url      = $update_arr[5];
					if( MBPO_VERSION < $latest_version ) {
						$mbpo_version_check = array('recheck_interval' => $recheck_interval, 'last_checked_on' => date('Y-m-d H:i:s'));
						$this->mbpoRecheckData($mbpo_version_check);
						$msg_in_plugin = str_replace("%latest-version%", $latest_version, $msg_in_plugin);
						$msg_in_plugin = str_replace("%plugin-name%", MBPO_NAME, $msg_in_plugin);
						$msg_in_plugin = str_replace("%upgrade-url%", $upgrade_url, $msg_in_plugin);
						$msg_in_plugin = '<div style="border-bottom:1px solid #CCCCCC;background-color:#FFFEEB;padding:6px;font-size:11px;text-align:center">'.$msg_in_plugin.'</div>';
					} else {
						$msg_in_plugin = '';
					}
				}
			}
		}
		echo '<div style="font-family: arial; font-size: 14px; font-weight: bold; padding: 0px 0px 0px 4px; width: 98%; border: 1px solid rgb(201, 220, 236); background-color: rgb(195, 217, 255); margin: 6px 0pt 0pt;"><h2>'.  MBPO_NAME.' '.MBPO_VERSION .'</h2>';		
		echo '</div>';
		if ( trim($msg_in_plugin) != '' && !isset($_GET['dnl']) ) echo $msg_in_plugin;

	}
	
	/**
	 * Page Footer
	 */
	function mbpoFooter() {
		echo '<p style="text-align:center;margin-top:3em;"><strong>'.MBPO_NAME.' '.MBPO_VERSION.' by <a href="http://www.maxblogpress.com/" target="_blank" >MaxBlogPress</a></strong></p>';
	}
	
	/**
	 * Displays the options page
	 * Carries out all the operations in options page
	 */
	function mbpoOptionsPg() {
		global $wpdb;
		$this->mbpo_request = $_REQUEST['mbpo'];
		$msg ='';
		
		$form_1 = 'mpo_reg_form_1';
		$form_2 = 'mpo_reg_form_2';
		// Activate the plugin if email already on list
		if ( trim($_GET['mbp_onlist']) == 1 ) { 
			$this->mpo_activate = 2;
			update_option('mpo_activate', $this->mpo_activate);
			$msg = 'Thank you for registering the plugin. It has been activated'; 
		} 
		// If registration form is successfully submitted
		if ( ((trim($_GET['submit']) != '' && trim($_GET['Contact0Email']) != '') || trim($_GET['submit_again']) != '') && $this->mpo_activate != 2 ) { 
			update_option('mpo_name', $_GET['Contact0FirstName']);
			update_option('mpo_email', $_GET['Contact0Email']);
			$this->mpo_activate = 1;
			update_option('mpo_activate', $this->mpo_activate);
		}
		if ( intval($this->mpo_activate) == 0 ) { // First step of plugin registration
			$this->__mpoRegister_1($form_1);
		} else if ( intval($this->mpo_activate) == 1 ) { // Second step of plugin registration
			$name  = get_option('mpo_name');
			$email = get_option('mpo_email');
			$this->__mpoRegister_2($form_2,$name,$email);
		} else if ( intval($this->mpo_activate) == 2 ) { // Options page
			if ( $_GET['action'] == 'upgrade' ) {
				$this->mbpoUpgradePlugin();
				exit;
			}
			if ( $this->mbpo_request['pingnow'] ) {
				if ( $this->mbpo_wp_version >= 2.1 ) {
					if ( $this->mbpo_ping_sites == "" ) { 
						$this->mbpoLog("NOT Pinging (no ping sites in services lists)", 7);
					} else if ( $this->mbpo_ping_option != 1 ) {
						$this->mbpoLog("NOT Pinging (disabled by administrator)", 6);
					} else {
						// schedule ping for now (forced ping)
						wp_schedule_single_event(time(), 'mbpo_ping', array('',4));
					}
				} else {
					$this->mbpoPingServices('',4);
				}
			} else if ( $this->mbpo_request['save'] ) {
				$this->mbpo_ping_sites  = $this->mbpo_request['uris'];
				$this->mbpo_ping_option = 0;
				if ( $this->mbpo_request['ping'] == 1 ) {
					$this->mbpo_ping_option = 1;
				}
				update_option("mbpo_ping_optimizer", $this->mbpo_ping_option);
				update_option("mbp_ping_sites", $this->mbpo_ping_sites);
				$this->mpo_options = array('limit_ping' => $this->mbpo_request['limit_ping'], 'limit_number' => $this->mbpo_request['limit_number'], 'limit_time' => $this->mbpo_request['limit_time']);
				update_option('mpo_options', $this->mpo_options);
				$msg = 'Options saved.';
			} else if ( $_GET['d'] == 'yes' ) {
				$msg = $this->__mpoDeletePingLog();
			}
			
			if ( $this->mpo_options['limit_ping'] == 1 ) {
				$limit_ping_chk = 'checked';
				$limit_ping_display = 'block ';
			} else {
				$limit_ping_chk = '';
				$limit_ping_display = 'none ';
			}
			if ( $this->mbpo_ping_option == 1 ) $ping_enable_chk = 'checked';
			else $ping_enable_chk = '';
			if ( $this->__mpoCheckLogTable() != $this->mpo_pinglog_tbl ) {
				if ( $msg != '' ) $msg .= "<br>";
				$msg .= "<font color='#ff0000'>Plugin NOT upgraded properly. Please reactivate the plugin.</font>";
			}
			if ( trim($msg) != '' ) {
				echo '<div id="message" class="updated fade"><p><strong>'.$msg.'</strong></p></div>';
			}
			require_once(MBP_PO_RELPATH . '/mpo-lib/include/options-pg.php');
		}
	}
	
	/**
	 * Sets flag if post is edited
	 * @param integer $id The Post ID
	 */
	function mbpoEditPost($id = 0) {
		if ( $id == 0 ) return;
		$this->mbpo_edited = 1;
		return $id;
	}
	
	/**
	 * Sets flag if post status changes from private to published
	 * @param integer $id The Post ID
	 */
	function mbpoPrivateToPublished($id = 0) {
		if ( $id == 0 ) return;
		$this->mbpo_pvt_to_pub = 1;
		return $id;
	}
	
	/**
	 * Gets the post title before publishing
	 * @param string $title
	 * @return string $title
	 */
	function mbpoGetPostTitle($title) {
		$this->mbpo_post_title = $title;
		return $title;
	}
	
	/**
	 * Copy of WP's "generic_ping".
	 * Uses another function to send the actual XML-RPC messages.
	 * @param string $post_title Title of the post
	 * @param integer $post_type Future post or current post
	 */
	function mbpoPingServices($post_title, $post_type) {
		global $wpdb;
		
		$this->already_pinged = array();
		$this->_post_type = $post_type;
		if ( strpos($post_title,'~#') !== false ) {
			$post_id_title = explode('~#',$post_title);
			$this->_post_title = $post_id_title[1];
			$this->_post_url = get_permalink($post_id_title[0]);
		} else {
			$this->_post_title = $post_title;
			$this->_post_url = '';
		}

		if ( $this->mbpo_wp_version >= 2.1 ) {
			// Do pingbacks
				$ping = $this->__mpoGetPingbackPost();
				$this->__mpoDeletePingBack($ping->ID);											
				pingback($ping->post_content, $ping->ID);
										
			// Do Enclosures
				$enclosure = $this->__mpoGetEnclosurePost();
				$this->__mpoDeleteEnclosure($enclosure->ID);													
				do_enclose($enclosure->post_content, $enclosure->ID);
							
			// Do Trackbacks
			$trackbacks = $this->__mpoGetTrackbacks();
			if ( is_array($trackbacks) ) {
				foreach ( $trackbacks as $trackback )
					do_trackbacks($trackback->ID);
			}
		}
		
		$services = get_option('mbp_ping_sites');
		$services = preg_replace("|(\s)+|", '$1', $services);
		$services = trim($services);
		if ( '' != $services ) {
			set_time_limit(300);
			$services = explode("\n", $services);
			foreach ($services as $service) {
				$this->mbpoSendXmlrpc($service);
			}
		}
		unset($this->already_pinged);
		set_time_limit(60);
	}
	
	/**
	 * A modified version of WP's ping functionality "weblog_ping" in functions.php
	 * Uses correct extended Ping format and logs response from service.
	 * @param string $server
	 * @param string $path
	 */
	function mbpoSendXmlrpc($server = '', $path = '') {
		include_once (ABSPATH . WPINC . '/class-IXR.php');
		
		// using a timeout of 3 seconds should be enough to cover slow servers
		$client = new IXR_Client($server, ((!strlen(trim($path)) || ('/' == $path)) ? false : $path));
		$client->timeout = 3;
		$client->useragent .= ' -- WordPress/'.$this->mbpo_wp_version;
	
		// when set to true, this outputs debug messages by itself
		$client->debug = false;
		$home = trailingslashit(get_option('home'));
		$check_url = ($this->_post_url != '') ? $this->_post_url : get_bloginfo('rss2_url');
				
		if ( !in_array($server,$this->already_pinged) ) {
			$this->already_pinged[] = $server;
			///$this->_post_title = $this->_post_title.'###'.$check_url;///
			// the extendedPing format should be "blog name", "blog url", "check url" (post url), and "feed url",
			// but it would seem as if the standard has been mixed up. It's therefore good to repeat the feed url.
			// $this->_post_type = 2 if new post and 3 if future post
			if ( $client->query('weblogUpdates.extendedPing', get_settings('blogname'), $home, $check_url, get_bloginfo('rss2_url')) ) { 
				$this->mbpoLog($server." was successfully pinged (extended format)", $this->_post_type, $this->_post_title);
			} else {
				if ( $client->query('weblogUpdates.ping', get_settings('blogname'), $home) ) {
					$this->mbpoLog($server." was successfully pinged", $this->_post_type, $this->_post_title);
				} else {
					$this->mbpoLog($server." could not be pinged. Error message: \"".$client->error->message."\"", $this->_post_type, $this->_post_title);
				}
			}
		}
	}
	
	/**
	 * Pings if the post is published.
	 * Doesn't ping if the post is edited or if future post
	 * @param integer $id The Post ID
	 */
	function mbpoPing($id) {
		global $wpdb;
		$row = $this->__mpoFetchPostDetails($id);
		
		if ( $this->mbpo_ping_sites != "" ) { 
			if ( $this->mbpo_ping_option == 1 ) {
				// if post is edited and not turned from private/draft to published
				if ( $this->mbpo_edited == 1 && $this->mbpo_pvt_to_pub == 0 && $row['post_status'] != 'draft' && $this->mbpo_post_title != '' && ($row['post_date'] <= current_time('mysql')) ) {
					$this->mbpoLog("NOT Pinging (%title% was edited)", 5, $this->mbpo_post_title);
				} else if ( $row['post_status'] != 'draft' ) { 
					$post_id_title = $row['ID'].'~#'.$this->mbpo_post_title;
					// if post_date is greater than current time/date then its a future post (don't ping it)			
					if ( ($row['post_date'] > current_time('mysql')) ) {
						if ( $this->excessive_pinging != 1 ) {
							if ( $this->mbpo_wp_version >= 2.1 ) {
								// schedule ping for future post
								wp_schedule_single_event(strtotime($row['post_date_gmt'].' GMT'), 'mbpo_ping', array($post_id_title,3));
							} else {
								$this->mbpo_future_pings[$id] = $id; 
								update_option('mbpo_future_pings', $this->mbpo_future_pings);
							}
							$this->mbpoLog("NOT Pinging (future post: %title%). Will ping after ".$this->__mbpoFormatDate($row['post_date']), 1, $this->mbpo_post_title);
							update_option('mpo_last_ping_time',current_time('mysql'));
							update_option('mpo_ping_num', get_option('mpo_ping_num')+1);
						} else {
							$this->mbpoLog("NOT Pinging (Excessive Pinging Limit Reached)", 8);
						}		
					} else if ( ($this->mbpo_pvt_to_pub == 1 || $row["post_status"] == 'publish') && $this->mbpo_post_title != '' ) {	
						if ( $this->excessive_pinging != 1 ) {
							if ( $this->mbpo_wp_version >= 2.1 ) {
								// schedule ping for new post
								wp_schedule_single_event(time(), 'mbpo_ping', array($post_id_title,2));
							} else {
								$this->mbpoPingServices($post_id_title, 2);
							}	
							update_option('mpo_last_ping_time',current_time('mysql'));
							update_option('mpo_ping_num', get_option('mpo_ping_num')+1);
						} else {
							$this->mbpoLog("NOT Pinging (Excessive Pinging Limit Reached)", 8);
						}		
					}
				}
			} else {
				if ( $row['post_status'] != 'draft' ) {
					if ( $this->mbpo_edited == 1 && $this->mbpo_pvt_to_pub == 0 && $row['post_status'] != 'draft' && $this->mbpo_post_title != '' && ($row['post_date'] <= current_time('mysql')) ) {
						$extra_msg = "It would NOT have pinged even if the ping was enabled.<br><br>Reason: %title% was edited";
					} else if ( ($row['post_date'] > current_time('mysql')) ) {
						$extra_msg = "It would have been scheduled for future ping if the ping was enabled.<br><br>Reason: future post %title%";
					} else if ( ($this->mbpo_pvt_to_pub == 1 || $row["post_status"] == 'publish') && $this->mbpo_post_title != '' ) {	
						$extra_msg = "It would have pinged if the ping was enabled.<br><br>Reason: new post %title%";
					}
					$this->mbpoLog("NOT Pinging (disabled by administrator)~^$extra_msg", 6, $this->mbpo_post_title);
				}
			}
		} else {
			if ( $row['post_status'] != 'draft' ) {
				if ( $this->mbpo_edited == 1 && $this->mbpo_pvt_to_pub == 0 && $row['post_status'] != 'draft' && $this->mbpo_post_title != '' && ($row['post_date'] <= current_time('mysql')) ) {
					$extra_msg = "It would NOT have pinged even if ping sites were available.<br><br>Reason: %title% was edited";
				} else if ( ($row['post_date'] > current_time('mysql')) ) {
					$extra_msg = "It would have been scheduled for future ping if ping sites were available.<br><br>Reason: future post %title%";
				} else if ( ($this->mbpo_pvt_to_pub == 1 || $row["post_status"] == 'publish') && $this->mbpo_post_title != '' ) {	
					$extra_msg = "It would have pinged if ping sites were available.<br><br>Reason: new post %title%";
				}
				$this->mbpoLog("NOT Pinging (no ping sites in services lists)~^$extra_msg", 7, $this->mbpo_post_title);
			}
		}
	}
	
	/**
	 * Checks if time elasped for future post, and if so, removes post form the ping list and pings
	 * For wordpress versions below 2.1
	 */
	function mbpoFuturePing() {
		global $wpdb;
		
		// future ping list is empty
		if ( count($this->mbpo_future_pings) <= 0 || $this->mbpo_wp_version >= 2.1) {
			return true;
		}
		$maxbpddc_data_recent = $this->mbpo_future_ping_time;
		
		// Check last updated date and update it if more than 15 min, and ping if any future post's time elasped
		$prev_time   = $this->mbpo_future_ping_time;
		$prev_time_parts = explode('-',$prev_time);
		$_prev_time  = mktime((int)$prev_time_parts[3], (int)$prev_time_parts[4], (int)$prev_time_parts[5], (int)$prev_time_parts[1], (int)$prev_time_parts[2], (int)$prev_time_parts[0]);
		$_now_time   = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
		$elapsed_min = ($_now_time-$_prev_time)/(60);
		$do_ping = 0;
		
		/// if last update/ping time more than 5 minutes
		if ( $elapsed_min > 5 ) {
			if ( is_array($this->mbpo_future_pings) ) {
				foreach ( $this->mbpo_future_pings as $id ) {
					$row = $this->__mpoFetchPostDetails($id);
					
					// if future published post later has been changed to draft or other status
					// then delete it from the ping list (It will be automatically be pinged when its status changes to publish)
					if ( $row['post_status'] != 'publish' && $row['post_status'] != 'future' ) {
						unset($this->mbpo_future_pings[$id]);  
					}
					if ( $row["post_date"] <= current_time('mysql') && ($row['post_status'] == 'publish' || $row['post_status'] == 'future') ) {		
						unset($this->mbpo_future_pings[$id]);
						$do_ping = 1;
						$post_title = $row['post_title'];
					}
				}
			}
			update_option("mbpo_future_pings", $this->mbpo_future_pings);
			update_option('mbpo_future_ping_time', date('Y-m-d-H-i-s'));
			if ( $do_ping == 1 ) {
				$post_id_title = $row['ID'].'~#'.$post_title;
				$this->mbpoPingServices($post_id_title,3);
			}
		}
	}
	
	/**
	 * Deletes post from future ping list if deleted
	 * @param integer $id The Post ID
	 * @return integer $id
	 */
	function mbpoFuturePingDelete($id) { 
		global $wpdb;
		if ( $this->mbpo_wp_version >= 2.1 ) {
			$row = $this->__mpoFetchPostDetails($id);	
			$post_id_title = $row['ID'].'~#'.$row['post_title'];
			wp_unschedule_event(strtotime($row['post_date_gmt'].' GMT'), 'mbpo_ping', array($post_id_title,2));
			return $id;
		}
		if ( count($this->mbpo_future_pings) <= 0 ) {
			return $id;
		}
		unset($this->mbpo_future_pings[$id]);
		update_option('mbpo_future_pings', $this->mbpo_future_pings);
		return $id;
	}
	
	/**
	 * Saves the current plugin action
	 */
	function mbpoLog($log_data,$type,$post_title='') {
		$date_time = $this->mbpo_current_date;		
		$this->__mpoAddLogData($log_data, $type, $date_time, $post_title);
		return true;
	}
	
	/**
	 * Gets log data and displays it
	 * @param integer $total_logs Total lines of log data to be shown
	 * @return string
	 */
	function mbpoGetLogData() {
		global $wpdb;
		$results = $this->__mpoGetResults();
		$noof_records = count($results);
		
		// Delete old records form log table if max limit exceeds
		if ( $noof_records > $this->mbpo_max_log ) {
			$date_time = $this->__mpoGetDateTime();
			$this->__mpoDeleteLogPerDate($date_time);
		}
		
		if ( $noof_records <= 0 ) {
			$exists = 0;
			$msg = '<br>No ping log recorded yet.';
		} else {
			$exists = 1;
			$ping_data = array();
			foreach ( (array) $results as $key => $details ) {
				$ping_data[$details[date_time]][] = array($details['post_title'],$details['log_data'],$details['type']);
			}
			$count = 0;
			foreach ( (array) $ping_data as $ping_date => $_ping_data_arr ) {
				$ping_date = $this->__mbpoFormatDate($ping_date);
				$count++; $cnt = 0;
				$bgcol = ($count%2 == 0) ? '#f8f8f8' : '#ffffff';
				foreach ( $_ping_data_arr as $key => $ping_data_arr ) {
					$ping_data = str_replace('%title%', '"'.$ping_data_arr[0].'"', $ping_data_arr[1]);
					if ( $ping_data_arr[2] == 2 || $ping_data_arr[2] == 3 || $ping_data_arr[2] == 4 ) {	
						if ( $ping_data_arr[2] == 2 ) { // new post ping
							$_data = "Pinging (new post: \"$ping_data_arr[0]\")";
						} else if ( $ping_data_arr[2] == 3 ) { // future post ping
							$_data = "Pinging (future post appeared in your blog: \"$ping_data_arr[0]\")";
						} else if ( $ping_data_arr[2] == 4 ) { // forced ping
							$_data = "Pinging (forced ping)";
						}
						if ( strpos($ping_data,'(extended format)') !== false ) {
							$_ping_data = '<font color="#009207">'.$ping_data.'</font>';
						} else if ( strpos($ping_data,'Error message:') !== false ) {
							$_ping_data = '<font color="#FF0000">'.$ping_data.'</font>';
						} else {
							$_ping_data = '<font color="#0273A8">'.$ping_data.'</font>';
						}
						if ( $cnt == 0 ) $msg .= '<div style="padding:4px;background-color:'.$bgcol.'" id="parent'.$count.'"><b><a style="cursor:hand;cursor:pointer;border-bottom:0px" onclick="mbpoShowHide(\'child'.$count.'\',\'img'.$count.'\');"><img src="'.MBP_PO_INCPATH.'images/arr_green1.gif" id="img'.$count.'" align="absmiddle"> <u>'.$ping_date.'</u></a></b> - '.$_data.'</div><div id="child'.$count.'" style="display:none;">';
						$msg .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &raquo; '.$_ping_data.'<br />';
						$cnt++;
						if ( count($_ping_data_arr) == $cnt ) $msg .= '<br></div>';
					} else {
						if ( strpos($ping_data,'~^') !== false ) {
							$msg_parts = explode('~^', $ping_data);
							$msg_part1 = $msg_parts[0];
							$msg_part2 = htmlspecialchars($msg_parts[1]);
							$msg .= '<div style="padding:4px;background-color:'.$bgcol.'"><img src="'.MBP_PO_INCPATH.'images/arr_blue1.gif" align="absmiddle"> <b>'.$ping_date.'</b> - '.$msg_part1;
							$msg .= ' <a href="#" onMouseover="tooltip(\''.$msg_part2.'\',200)" onMouseout="hidetooltip()" style="border-bottom:none;"><img src="'.MBP_PO_INCPATH.'images/help.gif" border="0" align="absmiddle" /></a></div>';
						} else {
							$msg .= '<div style="padding:4px;background-color:'.$bgcol.'"><img src="'.MBP_PO_INCPATH.'images/arr_blue1.gif" align="absmiddle"> <b>'.$ping_date.'</b> - '.$ping_data.'</div>';
						}
					}
				}
				if ( $count >= $this->mbpo_rows_to_show ) return array($msg,$exists);
			}
		}
		return array($msg,$exists);
	}
	
	/**
	 * Gets recheck data fro displaying auto upgrade information
	 */
	function mbpoRecheckData($data='') {
		if ( $data != '' ) {
			update_option('mbpo_version_check',$data);
		} else {
			$version_chk = get_option('mbpo_version_check');
			return $version_chk;
		}
	}
	
	/**
	 * Extracts plugin update data
	 */
	function mbpoExtractUpdateData() {
		$arr = array();
		$version_chk_file = "http://www.maxblogpress.com/plugin-updates/maxblogpress-ping-optimizer.php?v=".MBPO_VERSION;
		$content = wp_remote_fopen($version_chk_file);
		if ( $content ) {
			$content          = nl2br($content);
			$content_arr      = explode('<br />', $content);
			$latest_version   = trim(trim(strstr($content_arr[0],'~'),'~'));
			$recheck_interval = trim(trim(strstr($content_arr[1],'~'),'~'));
			$download_url     = trim(trim(strstr($content_arr[2],'~'),'~'));
			$msg_plugin_mgmt  = trim(trim(strstr($content_arr[3],'~'),'~'));
			$msg_in_plugin    = trim(trim(strstr($content_arr[4],'~'),'~'));
			$upgrade_url      = MBP_PO_SITEURL.'/wp-admin/options-general.php?page='.MBP_PO_PATH.'&action=upgrade&dnl='.$download_url;
			$arr = array($latest_version, $recheck_interval, $download_url, $msg_plugin_mgmt, $msg_in_plugin, $upgrade_url);
		}
		return $arr;
	}
	
	/**
	 * Interface for upgrading plugin
	 */
	function mbpoUpgradePlugin() {
		global $wp_version;
		$plugin = MBP_PO_PATH;
		echo '<div class="wrap">';
		$this->mbpoHeader();
		echo '<h3>Upgrade Plugin &raquo;</h3>';
		if ( $wp_version >= 2.5 ) {
			$res = $this->mbpoDoPluginUpgrade($plugin);
		} else {
			echo '&raquo; Wordpress 2.5 or higher required for automatic upgrade.<br><br>';
		}
		if ( $res == false ) echo '&raquo; Plugin couldn\'t be upgraded.<br><br>';
		echo '<br><strong><a href="'.MBP_PO_SITEURL.'/wp-admin/plugins.php">Go back to plugins page</a> | <a href="'.MBP_PO_SITEURL.'/wp-admin/options-general.php?page='.MBP_PO_PATH.'">'.MBPO_NAME.' home page</a></strong>';
		$this->mbpoFooter();
		echo '</div>';
		include('admin-footer.php');
	}
	
	/**
	 * Carries out plugin upgrade
	 */
	function mbpoDoPluginUpgrade($plugin) {
		set_time_limit(300);
		global $wp_filesystem;
		$debug = 0;
		$was_activated = is_plugin_active($plugin); // Check current status of the plugin to retain the same after the upgrade

		// Is a filesystem accessor setup?
		if ( ! $wp_filesystem || !is_object($wp_filesystem) ) {
			WP_Filesystem();
		}
		if ( ! is_object($wp_filesystem) ) {
			echo '&raquo; Could not access filesystem.<br /><br />';
			return false;
		}
		if ( $wp_filesystem->errors->get_error_code() ) {
			echo '&raquo; Filesystem error '.$wp_filesystem->errors.'<br /><br />';
			return false;
		}
		
		if ( $debug ) echo '> File System Okay.<br /><br />';
		
		// Get the URL to the zip file
		$package = $_GET['dnl'];
		if ( empty($package) ) {
			echo '&raquo; Upgrade package not available.<br /><br />';
			return false;
		}
		// Download the package
		$file = download_url($package);
		if ( is_wp_error($file) || $file == '' ) {
			echo '&raquo; Download failed. '.$file->get_error_message().'<br /><br />';
			return false;
		}
		$working_dir = $this->mbpo_abspath . 'wp-content/upgrade/' . basename($plugin, '.php');
		
		if ( $debug ) echo '> Working Directory = '.$working_dir.'<br /><br />';
		
		// Unzip package to working directory
		$result = $this->mbpoUnzipFile($file, $working_dir);
		if ( is_wp_error($result) ) {
			unlink($file);
			$wp_filesystem->delete($working_dir, true);
			echo '&raquo; Couldn\'t unzip package to working directory. Make sure that "/wp-content/upgrade/" folder has write permission (CHMOD 755).<br /><br />';
			return $result;
		}
		
		if ( $debug ) echo '> Unzip package to working directory successful<br /><br />';
		
		// Once extracted, delete the package
		unlink($file);
		if ( is_plugin_active($plugin) ) {
			deactivate_plugins($plugin, true); //Deactivate the plugin silently, Prevent deactivation hooks from running.
		}
		
		// Remove the old version of the plugin
		$plugin_dir = dirname($this->mbpo_abspath . PLUGINDIR . "/$plugin");
		$plugin_dir = trailingslashit($plugin_dir);
		// If plugin is in its own directory, recursively delete the directory.
		if ( strpos($plugin, '/') && $plugin_dir != $base . PLUGINDIR . '/' ) {
			$deleted = $wp_filesystem->delete($plugin_dir, true);
		} else {

			$deleted = $wp_filesystem->delete($base . PLUGINDIR . "/$plugin");
		}
		if ( !$deleted ) {
			$wp_filesystem->delete($working_dir, true);
			echo '&raquo; Could not remove the old plugin. Make sure that "/wp-content/plugins/" folder has write permission (CHMOD 755).<br /><br />';
			return false;
		}
		
		if ( $debug ) echo '> Old version of the plugin removed successfully.<br /><br />';

		// Copy new version of plugin into place
		if ( !$this->mbpoCopyDir($working_dir, $this->mbpo_abspath . PLUGINDIR) ) {
			echo '&raquo; Installation failed. Make sure that "/wp-content/plugins/" folder has write permission (CHMOD 755)<br /><br />';
			return false;
		}
		//Get a list of the directories in the working directory before we delete it, we need to know the new folder for the plugin
		$filelist = array_keys( $wp_filesystem->dirlist($working_dir) );
		// Remove working directory
		$wp_filesystem->delete($working_dir, true);
		// if there is no files in the working dir
		if( empty($filelist) ) {
			echo '&raquo; Installation failed.<br /><br />';
			return false; 
		}
		$folder = $filelist[0];
		$plugin = get_plugins('/' . $folder);      // Pass it with a leading slash, search out the plugins in the folder, 
		$pluginfiles = array_keys($plugin);        // Assume the requested plugin is the first in the list
		$result = $folder . '/' . $pluginfiles[0]; // without a leading slash as WP requires
		
		if ( $debug ) echo '> Copy new version of plugin into place successfully.<br /><br />';
		
		if ( is_wp_error($result) ) {
			echo '&raquo; '.$result.'<br><br>';
			return false;
		} else {
			//Result is the new plugin file relative to PLUGINDIR
			echo '&raquo; Plugin upgraded successfully<br><br>';	
			if( $result && $was_activated ){
				echo '&raquo; Attempting reactivation of the plugin...<br><br>';	
				echo '<iframe style="display:none" src="' . wp_nonce_url('update.php?action=activate-plugin&plugin=' . $result, 'activate-plugin_' . $result) .'"></iframe>';
				sleep(15);
				echo '&raquo; Plugin reactivated successfully.<br><br>';	
			}
			return true;
		}
	}
	
	/**
	 * Copies directory from given source to destinaktion
	 */
	function mbpoCopyDir($from, $to) {
		global $wp_filesystem;
		$dirlist = $wp_filesystem->dirlist($from);
		$from = trailingslashit($from);
		$to = trailingslashit($to);
		foreach ( (array) $dirlist as $filename => $fileinfo ) {
			if ( 'f' == $fileinfo['type'] ) {
				if ( ! $wp_filesystem->copy($from . $filename, $to . $filename, true) ) return false;
				$wp_filesystem->chmod($to . $filename, 0644);
			} elseif ( 'd' == $fileinfo['type'] ) {
				if ( !$wp_filesystem->mkdir($to . $filename, 0755) ) return false;
				if ( !$this->mbpoCopyDir($from . $filename, $to . $filename) ) return false;
			}
		}
		return true;
	}
	
	/**
	 * Unzips the file to given directory
	 */
	function mbpoUnzipFile($file, $to) {
		global $wp_filesystem;
		if ( ! $wp_filesystem || !is_object($wp_filesystem) )
			return new WP_Error('fs_unavailable', __('Could not access filesystem.'));
		$fs =& $wp_filesystem;
		require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		$archive = new PclZip($file);
		// Is the archive valid?
		if ( false == ($archive_files = $archive->extract(PCLZIP_OPT_EXTRACT_AS_STRING)) )
			return new WP_Error('incompatible_archive', __('Incompatible archive'), $archive->errorInfo(true));
		if ( 0 == count($archive_files) )
			return new WP_Error('empty_archive', __('Empty archive'));
		$to = trailingslashit($to);
		$path = explode('/', $to);
		$tmppath = '';
		for ( $j = 0; $j < count($path) - 1; $j++ ) {
			$tmppath .= $path[$j] . '/';
			if ( ! $fs->is_dir($tmppath) )
				$fs->mkdir($tmppath, 0755);
		}
		foreach ($archive_files as $file) {
			$path = explode('/', $file['filename']);
			$tmppath = '';
			// Loop through each of the items and check that the folder exists.
			for ( $j = 0; $j < count($path) - 1; $j++ ) {
				$tmppath .= $path[$j] . '/';
				if ( ! $fs->is_dir($to . $tmppath) )
					if ( !$fs->mkdir($to . $tmppath, 0755) )
						return new WP_Error('mkdir_failed', __('Could not create directory'));
			}
			// We've made sure the folders are there, so let's extract the file now:
			if ( ! $file['folder'] )
				if ( !$fs->put_contents( $to . $file['filename'], $file['content']) )
					return new WP_Error('copy_failed', __('Could not copy file'));
				$fs->chmod($to . $file['filename'], 0755);
		}
		return true;
	}
} // Eof Class

$PingOptimizerPlugin = new PingOptimizerPlugin();
?>