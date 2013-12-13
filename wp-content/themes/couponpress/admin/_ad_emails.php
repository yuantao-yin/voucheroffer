<?php 

if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 

global $PPT,$wpdb;

/* ====================== PREMIUM PRESS FILES CLASS INCLUDE ====================== */

$PPM = new PremiumPress_Membership;

if(get_option("database_table_emails") != "installed1"){

mysql_query("CREATE TABLE IF NOT EXISTS `premiumpress_emails` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `email_type` enum('email','collection') NOT NULL,
  `email_parent` int(11) NOT NULL DEFAULT '0',
  `email_title` varchar(255) NOT NULL,
  `email_description` blob NOT NULL,
  `email_html` int(1) NOT NULL DEFAULT '0',
  `email_interval` int(1) NOT NULL DEFAULT '0',
  `email_modified` date NOT NULL,
  PRIMARY KEY (`ID`)
)");

mysql_query("INSERT INTO `premiumpress_emails` (`ID`, `email_type`, `email_parent`, `email_title`, `email_description`, `email_html`, `email_interval`, `email_modified`) VALUES
(1, 'email', 0, 'Example Signup Email', 0x48656c6c6f2028646973706c61795f6e616d65293c6272202f3e3c6272202f3e5468616e6b20796f7520666f72206a6f696e696e67206f75722077656273697465206f6e2028757365725f72656769737465726564292c20776520686f706520796f7520656e6a6f7920796f757220736572766963652e3c6272202f3e3c6272202f3e596f7572206c6f67696e2064657461696c73206172653b3c6272202f3e3c6272202f3e757365726e616d653a20757365725f6c6f67696e3c6272202f3e70617373776f72643a202873656e7420696e206120736570657261746520656d61696c293c6272202f3e3c6272202f3e496620796f75206861766520616e79207175657374696f6e7320706c65617365206665656c206672656520746f20636f6e746163742075733c6272202f3e3c6272202f3e4b696e6420526567617264733c6272202f3e3c6272202f3e4d616e6167656d656e74, 1, 0, '2011-06-03'),
(2, 'email', 0, 'Example New Orders', 0x48656c6c6f2028757365726e616d65293c6272202f3e3c6272202f3e5468616e6b20796f7520666f7220706c6163696e6720616e206f7264657220776974682075732c207765206172652063757272656e746c7920726576696577696e6720796f757220707572636861736520616e642077696c6c20636f6e7461637420796f75204153415020696620746865726520697320616e797468696e6720746f206469637573732e3c6272202f3e3c6272202f3e4b696e6420526567617264733c6272202f3e3c6272202f3e4d616e6167656d656e74266e6273703b, 1, 0, '2011-06-03'),
(3, 'email', 0, 'Example Paid Order Email', 0x48692028757365726e616d65293c6272202f3e3c6272202f3e5468616e6b20796f7520666f7220796f75266e6273703b, 1, 0, '2011-06-03'),
(4, 'email', 0, 'Admin Contact Form', 0x48692041646d696e3c6272202f3e3c6272202f3e596f7520686176652072656369657665642061206e6577206d6573736167652076696120746865207765627369746520636f6e7461637420666f726d2c2069742072656164733b3c6272202f3e3c6272202f3e266e6273703b, 1, 0, '2011-06-03'),
(5, 'email', 0, 'Admin New Order Received ', 0x48692041646d696e3c6272202f3e3c6272202f3e596f7520686176652072656369657665642061206e6577206f726465722c20706c65617365206c6f67696e20746f207468652061646d696e206172656120746f20636865636b2f75706461746520616e6420636f6e6669726d2e3c6272202f3e266e6273703b, 1, 0, '2011-06-03')");




update_option("email_signup","1");
update_option("email_message_neworder","2");
update_option("email_order_after","3");
update_option("email_admin_contact","4");
update_option("email_admin_neworder","5");


update_option("emailrole1","administrator");
update_option("emailrole2","editor");
update_option("emailrole3","contributor");
update_option("database_table_emails", "installed1");

}

/* ====================== PREMIUM PRESS SWITCH FUNCTIONS ====================== */

if(isset($_POST['action'])){ $_GET['action'] = $_POST['action']; }
if(isset($_GET['action'])){

	switch($_GET['action']){
	
		case "massemail": {
		
			if($_POST['email']['package_access'][0] == 0){ // SEND TO ALL MEMBERS
			
				$SQL = "SELECT * FROM $wpdb->users";
				
			}else{
			
					$paks = "";
					
					
					
				if(defined('THEME_WEB_PATH')){	
				
					foreach($_POST['email']['package_access'] as $package_ID){
						$paks .= "premiumpress_subscriptions.package_ID=".$package_ID." OR ";
					}				
				
					$SQL = "SELECT $wpdb->users.ID FROM $wpdb->users LEFT JOIN `premiumpress_subscriptions` ON ($wpdb->users.user_login = premiumpress_subscriptions.user_login) 
					WHERE premiumpress_subscriptions.subscription_status = 1 AND ( ".$paks." premiumpress_subscriptions. package_ID=9999999 )
					GROUP BY $wpdb->users.user_login ";
					
				}else{ // FIND THE PACKAGE
			
			
					foreach($_POST['email']['package_access'] as $package_ID){
						$paks .= " ( $wpdb->postmeta.meta_key = 'packageID' AND $wpdb->postmeta.meta_value = '".$package_ID."' ) OR ";
						 
					}
					
					$SQL = "SELECT $wpdb->posts.post_author AS ID FROM $wpdb->posts 
					LEFT JOIN $wpdb->postmeta ON ($wpdb->postmeta.post_id = $wpdb->posts.ID  AND  ".$paks." ( $wpdb->postmeta.meta_key = 'packageID' AND $wpdb->postmeta.meta_value = '99999999' ) )
					GROUP BY $wpdb->posts.post_author";
					
			 
				}
			
			}
			
			
			
	 
			$emails = $wpdb->get_results($SQL);
	 
			foreach ($emails as $email){
 
				SendMemberEmail($email->ID,$_POST['email']);
				
			}

			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= "Emails Sent Successfully";
				
		} break;

		case "massdelete": {
		 
			for($i = 1; $i < 50; $i++) { 
				if(isset($_POST['d'. $i]) && $_POST['d'.$i] == "on"){ 
					$data = $wpdb->get_results("DELETE FROM premiumpress_emails WHERE ID='".$_POST['d'.$i.'-id']."' LIMIT 1");					
				}
			}

			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= "Selected Emails Deleted Successfully";
		
		} break;

		case "delete": { 

		$data = $wpdb->get_results("DELETE FROM premiumpress_emails WHERE ID='".$_GET['id']."' LIMIT 1");
		$GLOBALS['error'] 		= 1;
		$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
		$GLOBALS['error_msg'] 	= "Email Deleted Sccuessfully.";
		
		} break;
	
		case "edit": { 

		if($_POST['ID'] == 0){
			$wpdb->query("INSERT INTO `premiumpress_emails` ( `email_type`, `email_parent`, `email_title`, `email_description`, `email_html`, `email_interval`, `email_modified`) VALUES( 'email', 0, '', '', 1, 0, '2011-06-03')"); 
			$_POST['ID'] = $wpdb->insert_id;
		} 
		

		$STRING="UPDATE premiumpress_emails SET ";
		foreach($_POST['form'] as $key => $value){
			$STRING .=" ".$key." ='".PPTCLEAN($value)."',";		
		}
		$STRING = substr($STRING,0,-1);
		$STRING .= ", email_modified=NOW() WHERE ID='".$_POST['ID']."' LIMIT 1";
		$data = $wpdb->get_results($STRING);

		$GLOBALS['error'] 		= 1;
		$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
		$GLOBALS['error_msg'] 	= "Email Updated Successfully.";

		unset($_GET['edit']);

		} break;
	
	}

}


/* ====================== PREMIUM PRESS EDIT PAGE ====================== */


?>


	<!-- TinyMCE -->
	<script type="text/javascript" src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/PPT/js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
		tinyMCE.init({
			mode : "exact",
			elements : "elm1",
			//theme : "simple",
			theme : "advanced",
			width:'800px',
			height:'250px',
			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,| formatselect,fontselect,fontsizeselect,code",
			theme_advanced_buttons3 : "", 
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			//theme_advanced_resizing : true,
		   forced_root_block : false,
		   force_br_newlines : true,
		   force_p_newlines : false
			
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

function hideEmailTools(val){

if(val =="collection"){
toggleLayer('EAG');toggleLayer('email_box');toggleLayer('email_box1');toggleLayer('email_box2');
}


}
	</script>
	<!-- /TinyMCE -->
    
<?php
	
	if(isset($_GET['edit'])){ 
 
if($_GET['edit'] == 0){ 
 
}else{
$data = $wpdb->get_results("SELECT * FROM premiumpress_emails WHERE ID='".$_GET['edit']."' LIMIT 1");
}
PremiumPress_Header();

	
?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_emails.png" align="middle"> Add/ Edit Email</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('add')">Help Me</a>							 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">Email Details</a></li>
	<li><a href="#" onclick="window.location.href='admin.php?page=emails'">Search Results</a></li>
</ul>
</div>

<style>
select { border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px; }
</style>

<form method="post" target="_self" enctype="multipart/form-data">
<input name="action" type="hidden" value="edit" />
<input name="ID" type="hidden" value="<?php echo $_GET['edit']; ?>" />
<div id="premiumpress_tab1" class="content">

<table class="maintable" style="background:white;">
 
 <?php if(defined('THEME_WEB_PATH')){ ?>
	<tr class="mainrow">
		<td class="titledesc">Setup</td>

		<td class="forminp">

			<select name="form[email_type]" style="width: 400px;  font-size:14px;" onChange="hideEmailTools(this.value)" >
			<option value="collection" <?php if($data[0]->email_type =="collection"){ print "selected"; } ?>>Collection (group of emails)</option>
			<option  <?php if($data[0]->email_type =="email"){ print "selected"; } ?> value="email">Email</option>
			</select><br />

			<div id="EAG" style="display:visible"><small>Add to group</small><br />
			<select name="form[email_parent]" style="width: 400px;  font-size:14px;">
			<option value="0" <?php if($data[0]->email_parent != "0"){ print "selected"; } ?>>---------------</option>
			<?php echo $PPM->collections($data[0]->email_parent); ?>
			</select></div>

		</td>
	</tr>
    
    <?php }else{ ?>
    
    <input type="hidden" name="form[email_type]" value="email" />
    <?php } ?>


 <?php if(isset($data[0]->email_parent) && $data[0]->email_parent == "collection"){ ?>

	<tr class="mainrow">
		<td class="titledesc">Collection</td>

		<td class="forminp">
			<small>Title</small><br />
			<input name="form[email_title]" type="text" style="width: 400px;  font-size:14px;" value="<?php echo stripslashes($data[0]->email_title); ?>" /><br />
		</td>
	</tr>

<?php }else{ ?>

	<tr class="mainrow">
		<td class="titledesc">Subject</td>
		<td class="forminp">			 
			<input name="form[email_title]" type="text" style="width: 400px;  font-size:14px;" value="<?php echo stripslashes($data[0]->email_title); ?>" /><br />
		</td>
	</tr>

	<tr class="mainrow" id="email_box" style="display:visible">
		<td colspan="3">
		<textarea name="form[email_description]" id="elm1" style="width: 400px; height:150px; font-size:14px;" ><?php echo stripslashes($data[0]->email_description); ?></textarea>
       <p>Shortcodes for customizing email content can be found here: <a href="http://www.premiumpress.com/tutorial/email-shortcodes/" target="_blank">http://www.premiumpress.com/tutorial/email-shortcodes/</a></p>
		</td>
	</tr>


	<tr class="mainrow" id="email_box1" style="display:visible">
		<td class="titledesc">Email Type</td>
		<td class="forminp">			 
			<select name="form[email_html]">
			<option value="1" <?php if($data[0]->email_html == 1){ echo "selected"; } ?>>Text/HTML</option>
			<option value="2" <?php if($data[0]->email_html == 2){ echo "selected"; } ?>>Plain Text</option>
			</select>
		</td>
	</tr>

 <?php if(defined('THEME_WEB_PATH')){ ?>
 
	<tr class="mainrow" id="email_box2" style="display:visible">
		<td class="titledesc">Email Interval <br /> <small>The number of days this message will be sent after the previous message.</small></td>
		<td class="forminp">			 
			 
			<input name="form[email_interval]" type="text" style="width: 80px;  font-size:14px;" value="<?php echo $data[0]->email_interval; ?>" /><br />
		</td>
	</tr>

<?php  } ?> 

			 

			

<?php  } ?> 
	 
 

 
 
<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p></td>
</tr>

</table>

</div>
</form>
</div>
</div>
























<?php }else{ 


if(!isset($_GET['p']) || $_GET['p']==""){ $_GET['p']=1; }
$GLOBALS['results_per_page'] = 100;
$GLOBALS['user_fields'] = array();
$GLOBALS['meta_fields'] = array();
$GLOBALS['members_fields'] = array();

$checkbox = 0;
$TotalResults 		= $PPM->scope();
$EMAIL_SEARCH_DATA 	= $PPM->EQuery();
PremiumPress_Header();


$email_collections = $wpdb->get_results("SELECT ID, email_title FROM premiumpress_emails WHERE email_parent=0");
 
?>
 
<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_emails.png" align="middle"> Email Management</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a>	
<ul>	 
<li><a rel="premiumpress_tab1" href="#"  class="active">Email Configuration</a></li>
<li><a rel="premiumpress_tab3" href="admin.php?page=emails">My Emails</a></li>
<li><a href="#" onclick="window.location.href='admin.php?page=emails&edit=0'">Create Email</a></li>
<li><a rel="premiumpress_tab2" href="#">Send Emails</a></li>

</ul>
</div>

<div id="premiumpress_tab3" class="content">

<form class="plain" method="post" name="orderform" id="orderform">
<input type="hidden" name="action" value="massdelete">
<fieldset>
<table cellspacing="0"><thead><tr>

<td class="tc"><input type="checkbox" id="data-1-check-all" name="data-1-check-all" value="true" onClick="da(2);return false;" /></td>
<th>Email Subject <br /><small>Order By Subject </small>  <a href="admin.php?page=emails&sort=email_title&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/su.png" align="middle"></a> <a href="admin.php?page=emails&sort=email_title&order=desc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/sd.png" align="middle"></a></th>
<td>Email Information <br /> <small>Order By Interval</small> <a href="admin.php?page=emails&sort=email_interval&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/sd.png" align="middle"></a>  <a href="admin.php?page=emails&sort=email_interval&order=desc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/sd.png" align="middle"></a> </td>

<!--<td class="tc">Email Group <br /> <small>Order By Group <a href="admin.php?page=emails&sort=email_parent&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/su.png" align="middle"></a> <a href="admin.php?page=emails&sort=email_parent&order=desc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/sd.png" align="middle"></a></small></td>
<td class="tc"><?php if(isset($_GET['id'])){ print "Send Order"; }else{ print "Number of Emails"; } ?> </td>-->
<td class="tc">Actions <br /> </td>

</tr></thead><tfoot><tr><td colspan="6">
<label>with selected do:
<select name="data-1-groupaction"><option value="delete">delete</option></select></label>
<input class="button altbutton" type="submit" value="OK" style="color:white;" />
</td></tr></tfoot><tbody>



<?php foreach($EMAIL_SEARCH_DATA as $email) { ?>


<tr class="first">

<td class="tc">
<input type="checkbox" value="on" name="d<?php echo $checkbox; ?>" id="d<?php echo $checkbox; ?>"/>
<input type="hidden" name="d<?php echo $checkbox; ?>-id" value="<?php echo $email->ID ?>">
</td>
<td><?php echo stripslashes($email->email_title); ?>  <br /> <small>Last Modified: <?php echo $email->email_modified; ?></small></td>
<td>
<?php if($email->email_parent == 0){ echo "Type: ". $email->email_type; ?>
 
<?php }else{ ?>
	<small>
	Interval: <?php echo $email->email_interval; ?> days<br />
	Type: <?php if($email->email_html ==1){ echo "text/HTML"; }else{ echo "plain text"; } ?><br />
	</small>
<?php }?>
</td>
<!--<td class="tc"><?php echo $PPM->WhichCollection($email_collections, $email->email_parent); ?></td>
<td class="tc">

<?php if($email->email_parent == 0){ 

if($email->email_type=="email"){ print "----"; }else{
$countEmails = $wpdb->get_results("SELECT count(*) AS total FROM premiumpress_emails WHERE email_parent=".$email->ID); } ?>
<div style="font-size:22px;"><?php echo $countEmails[0]->total; ?></div>
<?php }else{ ?>
<div style="font-size:22px;"><?php echo $checkbox+1; ?></div>
<small><?php echo $email->email_interval; ?> Day Interval</small>
<?php } ?>

</td>-->
<td class="tc">
<ul class="actions">
<?php if($email->email_type=='collection'){ ?>
<li><a class="ico" href="admin.php?page=emails&sort=email_parent&id=<?php echo $email->ID; ?>" rel="permalink">view</a></li>

<?php } ?>
<li><a class="ico" href="admin.php?page=emails&edit=<?php echo $email->ID; ?>" rel="permalink"><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/pencil.png" alt="edit" /></a></li>
<li><a class="ico" class="submitdelete" href="admin.php?page=emails&action=delete&id=<?php echo $email->ID; ?>" onclick="if ( confirm('Are you sure you want to delete this email?') ) { return true;}return false;"><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/delete.png" alt="delete" /></a></li>

</ul>
</td>
</tr>


</td>	</tr>
	</tbody>
<?php $checkbox++;  } ?>

</table>




<input type="hidden" name="totalorders" value="<?php echo $checkbox; ?>">
<div class="pagination"><ul><li><?php echo $PPM->viewing('admin.php?page=emails'); ?></li></ul></div>

</form>
</div>




















<div id="premiumpress_tab2" class="content">

<form class="fields" method="post" target="_self" enctype="multipart/form-data"  style="padding:20px; padding-right:40px;">
<input name="action" type="hidden" value="massemail" />


<fieldset>
<legend><strong>Email Broadcast</strong></legend>


<label>Email Subject</label>
<input name="email[subject]" type="text" style="width: 400px;  font-size:14px;" value="<?php echo $data[0]->email_title; ?>" />
<br />

<label>Email Content</label>
<textarea name="email[description]" id="elm1" style="width: 400px; height:150px; font-size:14px;" ><?php echo $data[0]->email_description; ?></textarea>
 

<label>Email Type			 
			<select name="email[email_html]">
			<option value="1" <?php if($data[0]->email_html == 1){ echo "selected"; } ?>>Text/HTML</option>
			<option value="2" <?php if($data[0]->email_html == 2){ echo "selected"; } ?>>Plain Text</option>
			</select></label>
 

<label>Send Email To:</label>





<select name="email[package_access][]" size="2" style="font-size:14px;padding:5px; width:95%; height:100px; background:#e7fff3;" multiple="multiple">
<option value="0">---- ALL MEMBERS ----</option>



<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "directorypress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "couponpress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "classifiedstheme"){
 $packdata = get_option("packages");  ?>
                
<?php if(isset($packdata[1]['enable']) && $packdata[1]['enable'] ==1){ ?><option value="1"><?php echo $packdata[1]['name']; ?></option><?php } ?>
<?php if(isset($packdata[2]['enable']) && $packdata[2]['enable'] ==1){ ?><option value="2"><?php echo $packdata[2]['name']; ?></option><?php } ?>
<?php if(isset($packdata[3]['enable']) && $packdata[3]['enable'] ==1){ ?><option value="3"><?php echo $packdata[3]['name']; ?></option><?php } ?>
<?php if(isset($packdata[4]['enable']) && $packdata[4]['enable'] ==1){ ?><option value="4"><?php echo $packdata[4]['name']; ?></option><?php } ?>
<?php if(isset($packdata[5]['enable']) && $packdata[5]['enable'] ==1){ ?><option value="5"><?php echo $packdata[5]['name']; ?></option><?php } ?>
<?php if(isset($packdata[6]['enable']) && $packdata[6]['enable'] ==1){ ?><option value="6"><?php echo $packdata[6]['name']; ?></option><?php } ?>
<?php if(isset($packdata[7]['enable']) && $packdata[7]['enable'] ==1){ ?><option value="7"><?php echo $packdata[7]['name']; ?></option><?php } ?>
<?php if(isset($packdata[8]['enable']) && $packdata[8]['enable'] ==1){ ?><option value="8"><?php echo $packdata[8]['name']; ?></option><?php } ?>

 
<?php } ?>
</select>
 
 <p><small>Hold the SHIFT key to select multiple items.</small></p>

<p><input class="premiumpress_button" type="submit" value="Send Message" style="color:white;" /></p>

</fieldset>

</form>
</div>











<div id="premiumpress_tab1" class="content">

<form class="fields" method="post" target="_self" style="padding:20px; padding-right:40px;">
<input name="submitted" type="hidden" value="yes" />
<input type="hidden" name="admin_page" value="email_manager" />

<fieldset>
<legend><strong>Emails sent to users</strong></legend>


<table class="maintable" style="background:white;">	

<tr><td colspan="2">
<div class="msg msg-info">
  <p>
The email options below give you more control over the email content sent to users during events on your website such as registration etc. First <b>create an email</b> and then assign it to  the events below.</p>
</div>
</td></tr>



<?php /*****************************************************/ ?>


<tr class="mainrow"><td style="width:350px;">


<p><b>Signup Email</b></p>

			<select name="adminArray[email_signup]" style="width: 240px;  font-size:14px;">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_signup"),$type="!=0"); ?></select>
            <br /><br /><small>Sent to the user when they first join your website.</small>

</td><td style="width:350px;">

<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>

<p><b>New Private Message</b></p>

			<select name="adminArray[email_message_new]" style="width: 240px;  font-size:14px;">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_message_new"),$type="!=0"); ?></select>
            <br /><br />
            <small>Sent to the user when they receive a new private message.</small>

<?php }else{ ?>

<p><b>New Order Placed</b></p>

			<select name="adminArray[email_message_neworder]" style="width: 240px;  font-size:14px;">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_message_neworder"),$type="!=0"); ?></select>
            <br /><br />
            <small>Sent to the user when they place an order (NOT yet Paid)</small>
<?php } ?>            
</td>
</tr>
 
 
<tr class="mainrow"><td style="width:350px;">

<p><b>After Payment</b></p>

 	<select name="adminArray[email_order_after]" style="width: 240px;  font-size:14px;">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_order_after"),$type="!=0"); ?></select>
            <br /><small>Sent to the user after they have paid for their listing.</small>

</td><td style="width:350px;">

<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>

        <p><b>Edit/Updated Listing</b></p>
        
        <select name="adminArray[email_user_listingedit]" style="width: 240px;  font-size:14px;">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_user_listingedit"),$type="!=0"); ?></select>
        <br /><br /><small>Sent when a user edits/updates a free/paid listing.</small>	

<?php } ?>

</td></tr>
 
  
    
 <?php /*****************************************************/ 
 
 
 switch(strtolower(constant('PREMIUMPRESS_SYSTEM'))){

case "auctionpress": {
 
 ?>


     	<tr class="mainrow">
		<td style="width:350px;">
         
        <p><b>New Bid  - sent to auction item owner</b></p>
        
        <select name="adminArray[email_bid_new]" style="width: 240px;  font-size:14px;">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_bid_new"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the user when they have made a new bid on an item.</small>
            
        
        </td>

		<td style="width:350px;">
        
        <p><b>New Auction Item Submission</b></p>
        
			<select name="adminArray[email_auction_new]" style="width: 240px;  font-size:14px;">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_auction_new"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the user after they submit a new auction item.</small>
            
		</td>
	</tr>
 


    
     	<tr class="mainrow">
		<td class="titledesc"> 
        
        <p><b>Auction Ended - Sent to both users</b></p>
        
        <select name="adminArray[email_auction_end]" style="width: 240px;  font-size:14px;">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_auction_end"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the user when their auction has ended.</small>
            
        </td>

		<td class="forminp">
			
		</td>
	</tr>
    
      	<tr class="mainrow">
		<td class="titledesc">
        
        
        
        
        </td>

		<td class="forminp">
			
            
		</td>
	</tr>   
    

    
 <?php
 
 } break;

case "directorypress": { ?>




<?php

} break;


}
 
?>       

 
</table>
 
</fieldset>


<fieldset>
<legend><strong>Emails sent to website admin's</strong></legend>


<table class="maintable" style="background:white;">	

 
   	<tr class="mainrow">
		<td>
        
        <p><b>Contact Form</b></p>
        
        <select name="adminArray[email_admin_contact]" style="width: 240px;  font-size:14px;">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_contact"),$type="!=0"); ?></select>
        <br /><br /><small>Email sent when a visitor completes the contact page form.</small>	
        
        </td> 
		<td>
        
        <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>
        <p><b>New Listing Added</b></p>
        
        <select name="adminArray[email_admin_newlisting]" style="width: 240px;  font-size:14px;">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_newlisting"),$type="!=0"); ?></select>
        <br /><br /><small>Email sent when a new listing has been added by a website user.</small>
        <?php }else{ ?>
        <p><b>New Order Received</b></p>
        
        <select name="adminArray[email_admin_neworder]" style="width: 240px;  font-size:14px;">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_neworder"),$type="!=0"); ?></select>
        <br /><br /><small>Email sent when a new order has been placed on the website.</small>        
        
        <?php } ?>		
            
		</td>
	</tr>  
 
   	<tr class="mainrow">
		<td>
        
        <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>
        
        <p><b>Edit/Updated Listing</b></p>
        
        <select name="adminArray[email_admin_listingedit]" style="width: 240px;  font-size:14px;">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_listingedit"),$type="!=0"); ?></select>
        <br /><br /><small>Email sent when a member edits/updates a free/paid listing.</small>	
        
        <?php } ?>
        
        </td> 
		<td>
        
    
        		
            
		</td>
	</tr>  
 
 
 

<tr>
<td colspan="3">

 <?php $PPTroles = array('administrator' => 'Super Admin','editor' => 'Site Manager','contributor' => 'Employee' ); // ?>

  <b>Send admin emails to:</b>
        
        <?php $r=1;foreach($PPTroles as $key=>$name){ ?>
        
        <input name="emailrole<?php echo $r; ?>" type="checkbox" value="<?php echo $key; ?>" <?php if(get_option('emailrole'.$r) == $key){ echo "checked=checked";} ?>/> <?php echo $name; ?>  
    
        <?php $r++; } ?>

</td>
</tr>


</table>
</fieldset>





<table>
<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p></td>
</tr>
</table>




 

</form>
</div>

</div>

 



<?php
 

}

 
?>