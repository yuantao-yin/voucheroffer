<?php
/* 
 * License: Copyright (c) 2008 Pawan Agrawal. All rights reserved.
 *
 * This code is part of commercial software and for your personal use 
 * only. You are not allowed to resell or distribute this script.
 *
 */
 
/**
 * MBP Ping Optimizer
 * Holds all the necessary functions and variables
 */
 
class PingOptimizer {
	
	
	// wordpress default tables
	var $wp_posts_table		= 'posts';
	var $wp_postmeta_table	= 'postmeta';
		
	/**
	* Creates log table
	*/
	function __mpoCreatePingTable() {
		$sql 	= mysql_query("SHOW TABLES LIKE '$this->mpo_pinglog_tbl'");
		$exists	= mysql_fetch_row($sql);
		if (!$exists) {
			$query = "CREATE TABLE `{$this->mpo_pinglog_tbl}` (
						`id` int(11) NOT NULL auto_increment,
						`date_time` datetime NOT NULL,
						`post_title` text, 
						`log_data` text,
						`type` tinyint(4) DEFAULT 1, 
						PRIMARY KEY (`id`)
					);";
			mysql_query($query);
			return true;					
		}
	}
	
	/**
	* stores the future ping and time, checks the excessive pinging
	*/
	function __mpoPingSet() {
		$this->mbpo_current_date = current_time('mysql');
		$this->mbpo_ping_sites   = get_option("mbp_ping_sites");
		$this->mbpo_ping_option  = get_option('mbpo_ping_optimizer');
		$this->mbpo_future_pings = get_option('mbpo_future_pings');
		$this->mpo_options       = get_option('mpo_options');
		if ( $this->mbpo_wp_version < 2.1 ) {
			if( !is_array($this->mbpo_future_pings) ) {
				$this->mbpo_future_pings = array();
			}
			if( !$this->mbpo_future_ping_time = get_option('mbpo_future_ping_time') ) {
				$this->mbpo_future_ping_time = date('Y-m-d-H-i-s');
			}
		}
		// Check if ping limit reached
		if ( $this->mpo_options['limit_ping'] == 1 ) {
			$last_ping_time  = get_option('mpo_last_ping_time');
			$curr_time       = current_time('mysql');
			$limit_time      = $this->mpo_options['limit_time'] * 60;
			$limit_number    = $this->mpo_options['limit_number'];
			$_last_ping_time = intval(strtotime($last_ping_time));
			$_curr_time      = intval(strtotime($curr_time));
			$mpo_ping_num    = get_option('mpo_ping_num');
			if ( $_last_ping_time <= 0 ) $_last_ping_time = $_curr_time;
			if ( ($limit_time >= ($_curr_time - $_last_ping_time)) && ($mpo_ping_num >= $limit_number) ) {
				$this->excessive_pinging = 1;
			} else {
				if ( $mpo_ping_num >= $limit_number ) update_option('mpo_ping_num',0);
				$this->excessive_pinging = 0;
			}
		} else {
			$this->excessive_pinging = 0;
		}		
	}
	
	/**
	 * Formats the date to mm-dd-yyyy format
	 * @param datetime $datetime
	 * @return datetime $datetime. Formatted Date
	 */
	function __mbpoFormatDate($datetime) {
		if ( $datetime != '' ) {
			$datetime_parts = explode(' ',$datetime);
			$date_parts     = explode('-',$datetime_parts[0]);
			$datetime       = $date_parts[1].'-'.$date_parts[2].'-'.$date_parts[0].' '.$datetime_parts[1];
		}
		return $datetime;
	}	
	
	/**
	* Ping log delete operation
	*/
	function __mpoDeletePingLog() {
		$query = "DELETE FROM " . $this->mpo_pinglog_tbl;
		mysql_query($query);
		$msg = 'Ping Log Deleted.';
		return $msg;		
	}
	
	/**
	* Checks if log table is created
	*/
	function __mpoCheckLogTable() {
		$query = "SHOW TABLES LIKE '$this->mpo_pinglog_tbl'";
		$sql   = mysql_query($query);
		$rs	   = mysql_fetch_array($sql);
		return $rs[0];
	}
	
	/**
	* Grabs the trackback
	*/
	function __mpoGetTrackbacks() {
		$query = "SELECT 
						ID 
					FROM 
						$this->wp_posts_table
					WHERE 
						CHAR_LENGTH(TRIM(to_ping)) > 7 
						AND post_status = 'publish'";
		$sql	= mysql_query($query);
		while($rs = mysql_fetch_object($sql)) {
			$trackbacks[] = $rs;	
		}
		return $trackbacks;					
	}
	
	/**
	* Extract particular post details 
	*/
	function __mpoFetchPostDetails($id) {
		$query = "SELECT 
						ID,
						post_date,
						post_date_gmt,
						post_modified,
						post_status 
					FROM 
						$this->wp_posts_table WHERE id='" . $id . "'";
		$sql	= mysql_query($query);
		$rs		= mysql_fetch_array($sql, MYSQL_ASSOC);
		return $rs;
	}
	
	/**
	* inserts log data in the database
	*/
	function __mpoAddLogData($log_data, $type, $date_time, $post_title='') {	
		if ( MBPO_LOG == true ) {
			$query = "INSERT INTO $this->mpo_pinglog_tbl (date_time, post_title, log_data, type) 
			          VALUES ('$date_time', '$post_title', '$log_data', '$type')";
			mysql_query($query);
		}		
	}
	
	/**
	* Lists the ping lists 
	*/
	function __mpoGetResults() {
		$query = "SELECT *FROM $this->mpo_pinglog_tbl ORDER BY date_time DESC";
		$sql   = mysql_query($query)or die(mysql_error());
		while($rs = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			$results[] = $rs;
		}
		return $results;
	}
	
	/**
	* Fetch data time from the log table
	*/
	function __mpoGetDateTime() {
		$query = "SELECT date_time FROM $this->mpo_pinglog_tbl ORDER BY date_time DESC, id DESC LIMIT {$this->mbpo_max_log},1";
		$sql   = mysql_query($query);
		$rs	   = mysql_fetch_array($sql);
		return $rs['date_time'];
	}
	
	/**
	* Delete log record as per the date time
	*/
	function __mpoDeleteLogPerDate($date_time) {
		$query = "DELETE FROM $this->mpo_pinglog_tbl WHERE date_time <= '$date_time'";
		mysql_query($query);
	}
	
	/**
	* return the latest post value to be pinged
	*/
	function __mpoGetPingbackPost() {
			$query = "SELECT * FROM 
										$this->wp_posts_table, 
										$this->wp_postmeta_table 
									WHERE 
										$this->wp_posts_table.ID = $this->wp_postmeta_table.post_id
										AND $this->wp_postmeta_table.meta_key = '_pingme' 
									LIMIT 1";
			$sql	= mysql_query($query);
			$data	= mysql_fetch_object($sql);
			return $data;
	}
	
	/**
	* Delete pingback value
	*/
	function __mpoDeletePingBack($post_id) {
		$query = "DELETE FROM $this->wp_postmeta_table WHERE post_id = $post_id AND meta_key = '_pingme';";	
		mysql_query($query);			
	}
	
	/**
	* return the latest post value for the elclosure
	*/
	function __mpoGetEnclosurePost() {
		$query = "SELECT * FROM 
								$this->wp_posts_table, 
								$this->wp_postmeta_table 
							WHERE 
								$this->wp_posts_table.ID = $this->wp_postmeta_table.post_id 											
								AND $this->wp_postmeta_table.meta_key = '_pingme' 
							LIMIT 1";
		$sql	= mysql_query($query);
		$data	= mysql_fetch_object($sql);
		return $data;									
	}
	
	/**
	* Delete enclosure value
	*/
	function __mpoDeleteEnclosure($post_id) {
		$query_del_enclosure = "DELETE 
										FROM $this->wp_postmeta_table 
								WHERE 
									post_id = $post_id 
									AND meta_key = '_encloseme';";
		mysql_query($query_del_enclosure);			
	} 
	
	/**
	 * Plugin registration form
	 */
	function mpoRegistrationForm($form_name, $submit_btn_txt='Register', $name, $email, $hide=0, $submit_again='') {
		$wp_url = get_bloginfo('wpurl');
		$wp_url = (strpos($wp_url,'http://') === false) ? get_bloginfo('siteurl') : $wp_url;
		$thankyou_url = $wp_url.'/wp-admin/options-general.php?page='.$_GET['page'];
		$onlist_url   = $wp_url.'/wp-admin/options-general.php?page='.$_GET['page'].'&amp;mbp_onlist=1';
		if ( $hide == 1 ) $align_tbl = 'left';
		else $align_tbl = 'center';
		?>
		
		<?php if ( $submit_again != 1 ) { ?>
		<script><!--
		function trim(str){
			var n = str;
			while ( n.length>0 && n.charAt(0)==' ' ) 
				n = n.substring(1,n.length);
			while( n.length>0 && n.charAt(n.length-1)==' ' )	
				n = n.substring(0,n.length-1);
			return n;
		}
		function mpoValidateForm_0() {
			var name = document.<?php echo $form_name;?>.name;
			var email = document.<?php echo $form_name;?>.from;
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			var err = ''
			if ( trim(name.value) == '' )
				err += '- Name Required\n';
			if ( reg.test(email.value) == false )
				err += '- Valid Email Required\n';
			if ( err != '' ) {
				alert(err);
				return false;
			}
			return true;
		}
		//-->
		</script>
		<?php } 
		$form_action = ($submit_again == 1)?'http://www.maxblogpress.com/plugindoptin/index.php' : 'https://maxblogpress.infusionsoft.com/AddForms/processFormSecure.jsp';
		?>
		<table align="<?php echo $align_tbl;?>">
		<form name="<?php echo $form_name;?>" method="post" action="<?php echo $form_action;?>" <?php if($submit_again!=1){;?>onsubmit="return mpoValidateForm_0()"<?php }?>>
		<input value="bd14e0a4621d14aa236c89ac619ff737" name="infusion_xid" type="hidden" id="infusion_xid" />
		<input value="CustomFormWeb" name="infusion_type" type="hidden" id="infusion_type" />
		<input value="MBP Ping Optimizer" name="infusion_name" type="hidden" id="infusion_name" />
		<input value="MBP Ping Optimizer" name="Contact0_Plugin" type="hidden" id="Contact0_Plugin" />
		 <input type="hidden" name="Contact0_PluginBlogURL" id="Contact0_PluginBlogURL" value="<?php echo $thankyou_url;?>" />
		 <input type="hidden" name="Contact0_Tracking" id="Contact0_Tracking" value="mpo-m" />		
		 <?php if ( $submit_again == 1 ) { ?> 	
		 <input type="hidden" name="submit_again" value="1">
		 <?php } ?>		 
		 <?php if ( $hide == 1 ) { ?> 
		<input value="bd14e0a4621d14aa236c89ac619ff737" name="infusion_xid" type="hidden" id="infusion_xid" />
		<input value="CustomFormWeb" name="infusion_type" type="hidden" id="infusion_type" />
		<input value="MBP Ping Optimizer" name="infusion_name" type="hidden" id="infusion_name" />	
		<input value="MBP Ping Optimizer" name="Contact0_Plugin" type="hidden" id="Contact0_Plugin" />	 
		 <input type="hidden" name="Contact0Website" id="Contact0Website" value="<?php echo $thankyou_url;?>" />	
		<input type="hidden" id="Contact0FirstName" name="Contact0FirstName" value="<?php echo $name;?>" size="25" maxlength="150" />
		<input type="hidden" id="Contact0Email" name="Contact0Email" value="<?php echo $email;?>" size="25" maxlength="150" />
		 <?php } else { ?>
		 <tr>
		   <td>First Name: </td>
		   <td><input type="text" id="Contact0FirstName" name="Contact0FirstName" value="<?php echo $name;?>" size="25" maxlength="150" /></td></tr>
		 <tr><td>Email: </td><td><input type="text" id="Contact0Email" name="Contact0Email" value="<?php echo $email;?>" size="25" maxlength="150" /></td></tr>
		 <?php } ?>
		 <tr><td>&nbsp;</td><td><input type="submit" name="submit" value="<?php echo $submit_btn_txt;?>" class="button" /></td></tr>
		 </form>
		</table>
		<?php
	}	
	
	/**
	 * Register Plugin - Step 1
	 */
	function __mpoRegister_1($form_name='frm1') {
		global $userdata;
		$name  = trim($userdata->first_name);
		$email = trim($userdata->user_email);
		?>
		<div class="wrap"><h2> <?php echo MBPO_NAME.' '.MBPO_VERSION; ?></h2>

		 
		   <table align="center" width="680" border="0" cellspacing="1" cellpadding="3" style="border:2px solid #e3e3e3; padding: 8px 8px 8px 8px; background-color:#f1f1f1;margin-left:130px">
             <tr>
               <td>
	
			 <table width="620" cellpadding="5" cellspacing="1" style="border:1px solid #e9e9e9; padding: 8px 8px 8px 8px; background-color:#ffffff; text-align:left;margin-left:20px">
			  <tr><td align="center"><h3>Please register the plugin to activate it. (Registration is free)</h3></td></tr>
			  <tr><td align="left">In addition you'll also receive complimentary subscription to MaxBlogPress Newsletter (worth $97) which will give you insider tips on how to make more money from your blog as well as how to bring thousands of new visitors to your blog for free..</td></tr>
			  <tr><td><!--<div style="border-bottom:1px solid #CCC;"></div>--></td></tr>
			  <tr><td align="center"><br/><strong>Fill the form below to register the plugin:</strong></td></tr>
			  <tr><td align="center"><?php $this->mpoRegistrationForm($form_name,'Register',$name,$email);?></td></tr>
			   <tr><td><br/><font style="font-size:11px"><strong>Note:</strong> If you've already registered any of the free MaxBlogPress plugins then simply enter the name/email from which you have registered before. The plugin will activate immediately.</font></td></tr>
			  <tr><td align="center"><br/><font size="1">[ Your contact information will be handled with the strictest confidence <br />and will never be sold or shared with third parties.<strong>Also, you can unsubscribe at anytime.</strong>]</font></td></td></tr>
			 </table>
				   
			   </td>
             </tr>
           </table>

		<p style="text-align:center;margin-top:3em;"><strong><?php echo MBPO_NAME.' '.MBPO_VERSION; ?> by <a href="http://www.maxblogpress.com/" target="_blank" >MaxBlogPress</a></strong></p>
	    </div>
		<?php
	}	
	
	/**
	 * Register Plugin - Step 2
	 */
	function __mpoRegister_2($form_name='frm2',$name,$email) {
		$msg = 'You have not clicked on the confirmation link yet. A confirmation email has been sent to you again. Please check your email and click on the confirmation link to activate the plugin.';
		if ( trim($_GET['submit_again']) != '' && $msg != '' ) {
			echo '<div id="message" class="updated fade"><p><strong>'.$msg.'</strong></p></div>';
		}
		?>
		<div class="wrap"><h2> <?php echo MBPO_NAME.' '.MBPO_VERSION; ?></h2>
		 <center>
		 <table width="640" cellpadding="5" cellspacing="1" style="border:4px solid #CCC;padding:10px;background-color:F1F1F1">
		  <tr><td align="center"><h3>Almost Done....</h3></td></tr>
		  <tr><td><h3>Step 1:</h3></td></tr>
		  <tr><td>A confirmation email has been sent to your email "<?php echo $email;?>". You must click on the link inside the email to activate the plugin.</td></tr>
		  <tr><td><strong>The confirmation email will look like:</strong><br /><img src="http://www.maxblogpress.com/images/activate-plugin-email.jpg" vspace="4" border="0" /></td></tr>
		  <tr><td>&nbsp;</td></tr>
		  <tr><td><h3>Step 2:</h3></td></tr>
		  <tr><td>Click on the button below to Verify and Activate the plugin.</td></tr>
		  <tr><td><?php $this->mpoRegistrationForm($form_name.'_0','Verify and Activate',$name,$email,$hide=1,$submit_again=1);?></td></tr>
		 </table>
		 <p>&nbsp;</p>
		 <script type="text/javascript">
			function __mpoShowHide(Div, Img) {
				var divCtrl = document.getElementById(Div);
				var Img     = document.getElementById(Img);
				if(divCtrl.style=="" || divCtrl.style.display=="none") {
					divCtrl.style.display = "block";
					Img.src = '<?php echo MBP_PO_INCPATH?>images/minus.gif';
				}
				else if(divCtrl.style!="" || divCtrl.style.display=="block") {
					divCtrl.style.display = "none";
					Img.src = '<?php echo MBP_PO_INCPATH?>images/plus.gif';
				}
			}		 
		 </script>
		 <div align="left" style="padding:10px;"><a style="cursor:hand;cursor:pointer;" onclick="__mpoShowHide('adv_option','adv_img');"><img src="<?php echo MBP_PO_INCPATH?>images/plus.gif" id="adv_img" border="0" /><strong>Troubleshooting</strong></a></div>
		 
		 <div id="adv_option" style="display:none">		 
		 <table width="640" cellpadding="5" cellspacing="1" style="border:4px solid #CCC;padding:10px;background-color:F1F1F1">
           <!--<tr><td><h3>Troubleshooting</h3></td></tr>-->
           <tr><td><strong>The confirmation email is not there in my inbox!</strong></td></tr>
           <tr><td>Dont panic! CHECK THE JUNK, spam or bulk folder of your email.</td></tr>
           <tr><td>&nbsp;</td></tr>
           <tr><td><strong>It's not there in the junk folder either.</strong></td></tr>
           <tr><td>Sometimes the confirmation email takes time to arrive. Please be patient. WAIT FOR 6 HOURS AT MOST. The confirmation email should be there by then.</td></tr>
           <tr><td>&nbsp;</td></tr>
           <tr><td><strong>6 hours and yet no sign of a confirmation email!</strong></td></tr>
           <tr><td>Please register again from below:</td></tr>
           <tr><td><?php $this->mpoRegistrationForm($form_name,'Register Again',$name,$email,$hide=0,$submit_again=2);?></td></tr>
           <tr><td><strong>Help! Still no confirmation email and I have already registered twice</strong></td></tr>
           <tr><td>Okay, please register again from the form above using a DIFFERENT EMAIL ADDRESS this time.</td></tr>
           <tr><td>&nbsp;</td></tr>
           <tr>
             <td><strong>Why am I receiving an error similar to the one shown below?</strong><br />
                 <img src="http://www.maxblogpress.com/images/no-verification-error.jpg" border="0" vspace="8" /><br />
               You get that kind of error when you click on &quot;Verify and Activate&quot; button or try to register again.<br />
               <br />
               This error means that you have already subscribed but have not yet clicked on the link inside confirmation email. In order to  avoid any spam complain we don't send repeated confirmation emails. If you have not recieved the confirmation email then you need to wait for 12 hours at least before requesting another confirmation email. </td>
           </tr>
           <tr><td>&nbsp;</td></tr>
           <tr><td><strong>But I've still got problems.</strong></td></tr>
           <tr><td>Stay calm. <strong><a href="http://www.maxblogpress.com/contact-us/" target="_blank">Contact us</a></strong> about it and we will get to you ASAP.</td></tr>
         </table>
		 </div>
		 </center>		
		<p style="text-align:center;margin-top:3em;"><strong><?php echo MBPO_NAME.' '.MBPO_VERSION; ?> by <a href="http://www.maxblogpress.com/" target="_blank" >MaxBlogPress</a></strong></p>
	    </div>
		<?php
	}	
}
$PingOptimizer = new PingOptimizer();
?>