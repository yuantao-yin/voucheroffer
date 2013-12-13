<?php 
if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 
function StaffRoles($current,$single=false){

	if(strtolower(PREMIUMPRESS_SYSTEM) == "realtorpress"){
	
		$roles = array('administrator' => 'Admin','editor' => 'Site Manager','author' => 'Author', 'contributor' => 'Employee/Agent','subscriber' => 'Client/Website User'); //
		
	}else{
	
		$roles = array('administrator' => 'Admin','editor' => 'Site Manager','author' => 'Author', 'contributor' => 'Employee','subscriber' => 'Client/Website User'); //
		
	}

	
	$string= "";
	
	foreach($roles as $key=>$val){
	
		if($current == $key){
		if($single){
		return $val;
		}else{
		$string .="<option value='".$key."' selected=selected>".$val." (".$key.")</option> ";
		}
		
		}else{
		$string .="<option value='".$key."'>".$val." (".$key.")</option> ";
		}
		
	
	}
	
	return $string;
 

}

global $PPT,$wp_roles,$getWP, $user,$wpdb; get_currentuserinfo();


/* ====================== PREMIUM PRESS FILES CLASS INCLUDE ====================== */

$PPM = new PremiumPress_Membership;

/* ====================== PREMIUM PRESS SWITCH FUNCTIONS ====================== */

if(isset($_POST['action'])){ $_GET['action'] = $_POST['action']; }
if(isset($_GET['action'])){

	switch($_GET['action']){

		case "massdelete": {
		  
			for($i = 0; $i < 50; $i++) { 
				if(isset($_POST['d'. $i]) && $_POST['d'.$i] == "on"){ 
					wp_delete_user( $_POST['d'.$i.'-id'] ) ;					 				
				}
			}

			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= "Selected Members Deleted Successfully";
		
		} break;

		case "delete": { 

		wp_delete_user( $_GET['id'] ) ;
		 
		$GLOBALS['error'] 		= 1;
		$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
		$GLOBALS['error_msg'] 	= "Member Deleted Sccuessfully.";
		
		} break;
	
		case "edit": { 

		if( ( $_POST['password'] == $_POST['password_r'] ) && $_POST['password'] !=""){
		$_POST['userdata']['user_pass'] = $_POST['password'] ;
		} 
 
		$_POST['userdata']['jabber']  = $_POST['address']['country']."**";
		$_POST['userdata']['jabber'] .= $_POST['address']['state']."**";
		$_POST['userdata']['jabber'] .= $_POST['address']['address']."**";
		$_POST['userdata']['jabber'] .= $_POST['address']['city']."**";
		$_POST['userdata']['jabber'] .= $_POST['address']['zip']."**";
		$_POST['userdata']['jabber'] .= $_POST['address']['phone']; 
 
		if($_POST['userdata']['ID'] == 0){
		
		$data = wp_create_user( $_POST['usr'], $_POST['password'], $_POST['userdata']['user_email'] );
		 
		if(isset($data->errors)){
		die(print_r($data->errors));
		}else{
		$_POST['userdata']['ID'] = $data;
		}
			
		}
		//$_POST['userdata']['jabber'] = $_POST['workinghours1']."*".$_POST['workinghours2']."*".$_POST['workinghours3'];
		
		 
		wp_update_user( $_POST['userdata'] );

		$GLOBALS['error'] 		= 1;
		$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
		$GLOBALS['error_msg'] 	= "Member Updated Successfully.";

		} break;
	
	}

}

 if($GLOBALS['error'] == 1){ ?><div class="msg msg-<?php echo $GLOBALS['error_type']; ?>"><p><?php echo $GLOBALS['error_msg']; ?></p></div> <?php  }

/* ====================== PREMIUM PRESS EDIT PAGE ====================== */

if(isset($_GET['edit'])){  

$data = new WP_User($_GET['edit']);

PremiumPress_Header();

$ADD = explode("**",$data->jabber);	

?>

<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_members.png" align="middle"> Add/Edit Member</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('add')">Help Me</a> 						 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">Details</a></li>
    <li><a rel="premiumpress_tab2" href="#">Contact Details</a></li>
    <li><a rel="premiumpress_tab4" href="#">User Photo</a></li>
    <li><a href="#" onclick="window.location.href='admin.php?page=orders&cid=<?php echo $_GET['edit']; ?>'">Order History</a></li>
	<!--<li><a href="admin.php?page=members">Search Results</a></li>-->
</ul>
</div>

<style>
select { border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px; }
.photo { max-width:160px; }
</style>

<form method="post" target="_self" enctype="multipart/form-data">
<input name="action" type="hidden" value="edit" />
<input name="userdata[ID]" type="hidden" value="<?php echo $_GET['edit']; ?>" />
<div id="premiumpress_tab1" class="content">

 
<table class="maintable" style="background:white;">


	<tr class="mainrow">
		<td class="titledesc" valign="top">
        
        <?php if(function_exists('userphoto') && userphoto_exists($data->ID)){ echo userphoto($data->ID); }else{ echo get_avatar($data->ID,152); } ?>
        
       

        </td>


		<td class="forminp">
        
        <table width="100%" border="1"><tr><td>
        
        <small>Company Role</small><br />
        <select name="userdata[role]" id="role" style="width: 250px;  font-size:14px;">
        <?php echo StaffRoles($data->roles[0]); ?>            
        </select><br />
        
        <p class="ppnote">for more information about roles <a href="http://codex.wordpress.org/Roles_and_Capabilities" target="_blank">click here.</a></p>
        
        </td><td>
        
        <small>Status</small><br />
        <select name="userdata[yim][user_status]" id="role" style="width: 250px;  font-size:14px;">
        <option <?php if($data->yim['user_status'] == "active"){ print "selected=selected"; } ?> value="active">Active</option>
        <option <?php if($data->yim['user_status'] == "suspended"){ print "selected=selected"; } ?> value="suspended">Suspended</option>  
        <option <?php if($data->yim['user_status'] == "fired"){ print "selected=selected"; } ?> value="fired">Fired</option>         
        </select><br />
    
    	</td></tr>   
          
        
          <tr>
            <td><small>First Name(s)</small><br />
			<input name="userdata[first_name]" type="text" style="width: 250px;  font-size:14px;" value="<?php echo $data->first_name; ?>" /></td>
            <td><small>Last Name</small><br />
			<input name="userdata[last_name]" type="text" style="width: 250px;  font-size:14px;" value="<?php echo $data->last_name; ?>" /></td>
          </tr>
          
           <tr>
          <td colspan="2"> <small>Email Address (must be unique for each user)</small><br />
		<input name="userdata[user_email]" type="text" style="width: 550px;  font-size:14px;" value="<?php echo $data->user_email; ?>" />  </td>
          </tr>
          
          <tr>
          <td><small>Nick Name <em>Required by Wordpress</em></small><br />
			<input name="userdata[nickname]" type="text" style="width: 250px;  font-size:14px;" value="<?php echo $data->nickname; ?>" /></td>
          <td>
          
          
          
          <small>Website Username - <em>This cannot be changed.</em></small><br />
			<input type="text"  value="<?php echo esc_attr($data->user_login); ?>" <?php if($_GET['edit'] != 0){ ?>disabled="disabled"<?php } ?> name="usr" style="width: 250px;  font-size:14px;" /> <br />

          
         </td>
          </tr>
          

        </table>
        
        <p style="margin-left:20px;"><b>Change Password </b> - <em>Only complete if you want to change the password</em></p>
        
        <table width="100%" border="1">
          <tr>
          <td><small>New Password</small><br />
			 <input name="password" type="text" style="width: 250px;  font-size:14px;" value="" /></td>
          <td><small>Re-Type New Password</small><br />
			 <input name="password_r" type="text" style="width: 250px;  font-size:14px;" value="" /></td>          
          </tr></table>

 
  <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "moviepress"){  $packdata = get_option("packages");  
	 
echo'<script type="text/javascript" charset="utf-8">
Date.firstDayOfWeek = 0;
Date.format = \'yyyy-mm-dd\';
jQuery(function()
{
	jQuery(\'.date-pick\').datePicker()
	jQuery(\'#start-date\').bind(
		\'dpClosed\',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				jQuery(\'#end-date\').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	 
});
			
</script>        
';	 
	 
	 
	 ?>   
  <script type="text/javascript" src="<?php echo $GLOBALS['template_url']; ?>/PPT/js/jquery.date.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['template_url']; ?>/PPT/js/jquery.date_pick.js"></script>

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $GLOBALS['template_url']; ?>/PPT/css/css.date.css">  
       
   <table width="100%" border="1"><tr><td>
        
        
        <small>Account Package</small><br />
        <select name="userdata[yim][user_package]" id="role" style="width: 250px;  font-size:14px;">
                <option value="0" <?php if($data->yim['user_package'] == 0 || $data->yim['user_package'] == ""){ echo "selected=selected"; }?>>No Package Set</option>
<?php if(isset($packdata[1]['enable']) && $packdata[1]['enable'] ==1){ ?><option value="1" <?php if($data->yim['user_package'] == 1){ echo "selected=selected"; }?>><?php echo $packdata[1]['name']; ?></option><?php } ?>
<?php if(isset($packdata[2]['enable']) && $packdata[2]['enable'] ==1){ ?><option value="2" <?php if($data->yim['user_package'] == 2){ echo "selected=selected"; }?>><?php echo $packdata[2]['name']; ?></option><?php } ?>
<?php if(isset($packdata[3]['enable']) && $packdata[3]['enable'] ==1){ ?><option value="3" <?php if($data->yim['user_package'] == 3){ echo "selected=selected"; }?>><?php echo $packdata[3]['name']; ?></option><?php } ?>
<?php if(isset($packdata[4]['enable']) && $packdata[4]['enable'] ==1){ ?><option value="4" <?php if($data->yim['user_package'] == 4){ echo "selected=selected"; }?>><?php echo $packdata[4]['name']; ?></option><?php } ?>
<?php if(isset($packdata[5]['enable']) && $packdata[5]['enable'] ==1){ ?><option value="5" <?php if($data->yim['user_package'] == 5){ echo "selected=selected"; }?>><?php echo $packdata[5]['name']; ?></option><?php } ?>
<?php if(isset($packdata[6]['enable']) && $packdata[6]['enable'] ==1){ ?><option value="6" <?php if($data->yim['user_package'] == 6){ echo "selected=selected"; }?>><?php echo $packdata[6]['name']; ?></option><?php } ?>
<?php if(isset($packdata[7]['enable']) && $packdata[7]['enable'] ==1){ ?><option value="7" <?php if($data->yim['user_package'] == 7){ echo "selected=selected"; }?>><?php echo $packdata[7]['name']; ?></option><?php } ?>
<?php if(isset($packdata[8]['enable']) && $packdata[8]['enable'] ==1){ ?><option value="8" <?php if($data->yim['user_package'] == 8){ echo "selected=selected"; }?>><?php echo $packdata[8]['name']; ?></option><?php } ?>

          
        </select><br />
        
        </td><td>

			<small>Package Expiry Date</small><br /> 
			<input type="text" name="userdata[yim][user_package_expires]"  id="start-date" class="date-pick dp-applied" value="<?php echo $data->yim['user_package_expires']; ?>" />
 
		</td></tr>   </table>

  <?php } ?>    
          
 
 		
			 

		</td>
	</tr>
    
    
    
    
     
    <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "auctionpress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress"){ ?>   
   
    
     	<tr class="mainrow">
		<td class="titledesc">Account Balance</td>

		<td class="forminp">

			 
			<input name="userdata[aim]" type="text" style="width: 250px;  font-size:14px;" value="<?php echo $data->aim; ?>" />

 
		</td>
	</tr>   
 
    
 <?php } ?>  
    
 
    	<tr class="mainrow">
		<td class="titledesc">Account Notice</td>

		<td class="forminp">

			<small><b>HTML allowed.</b> This will be displayed to the member when they login to their account.</small><br />
			<textarea name="userdata[yim][text]" style="width: 400px; height:200px;  font-size:14px;"><?php echo $data->yim['text']; ?></textarea><br />

 
		</td>
	</tr>
    
  
 
 
<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p></td>
</tr>

</table>

</div>

<div id="premiumpress_tab2" class="content">

<table class="maintable" style="background:white;">


 
    
    
  
  	<tr class="mainrow">
		<td class="titledesc">Contact Details</td>

		<td class="forminp"><table width="100%" border="1">
        
        
        
          <tr>
          
          <td colspan="2"><small>Address</small><br />
			 <input type="text" name="address[address]" value="<?php echo $ADD[2]; ?>" style="width: 550px;  font-size:14px;" tabindex="17" /></td>          
          </tr>
          
    <tr>
             
          <td><small>State</small><br />
			 <input type="text" name="address[state]" value="<?php echo $ADD[1]; ?>" style="width: 250px;  font-size:14px;" tabindex="15" /></td>  
             
             
                  <td><small>City</small><br />
			 <input type="text" name="address[city]" value="<?php echo $ADD[3]; ?>" style="width: 250px;  font-size:14px;" tabindex="16" /></td>
            
          
                     
          </tr> 
          
              
 		<tr>
          
        <td><small>Country</small><br /> <input type="text" name="address[country]" value="<?php echo $ADD[0]; ?>" style="width: 250px;  font-size:14px;" tabindex="14" /></td>      
           
        <td><small>Zip/Postcode</small><br /><input type="text" name="address[zip]" value="<?php echo $ADD[4]; ?>"style="width: 250px;  font-size:14px;" tabindex="18" /></td>
             
             
          <tr>
         
          <td colspan="2"><small>Phone</small><br />
			 <input type="text" name="address[phone]" value="<?php echo $ADD[5]; ?>" style="width: 250px;  font-size:14px;" tabindex="19" /></td>          
                 
           </tr> 
           
           
           <tr>       
              <td colspan="2"><small>Comments/Feedback</small><br />
			 <textarea name="userdata[description]" style="width: 550px; height:200px;  font-size:14px;"><?php echo nl2br(stripslashes($data->description)); ?></textarea></td>  
                 
          </tr>  
         
        </table>
        
        

		</td>
	</tr>
       
    
    
    
    
    
<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p></td>
</tr>

</table>
</div>


<div id="premiumpress_tab3" class="content">

<table class="maintable" style="background:white;">


 
    
   	<tr class="mainrow">
		<td class="titledesc">Order History</td>

		<td class="forminp">  
        
 
  
 
    	</td>
	</tr>  
    
 

</table>
</div>


 

<div id="premiumpress_tab4" class="content">
<table class="maintable" style="background:white;">

        <?php if(function_exists('userphoto_exists')){  ?> 
  	<tr class="mainrow">
		<td class="titledesc">User Photo</td>

		<td class="forminp">

 

	

	<style>.photo { float:left; } #userphoto td { display:block; } </style>

		<?php
		if(userphoto_exists($_GET['edit'])){ 	
		userphoto($_GET['edit']); }else{ 
		echo get_avatar($_GET['edit'], 96); } 
		
		do_action('show_user_profile');
		?>
		
	<p> <input type="checkbox" name="userphoto_delete" id="userphoto_delete" /> <?php _e('Delete existing photo?','cp') ?> </p>

 

		</td>
	</tr>  
    <?php }else{ ?>

Please install the User photo plugin found here: <a href="http://premiumpress.com/plugins/" target="_blank">http://premiumpress.com/plugins/</a>
<?php } ?>
<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p></td>
</tr>

</table>
</div>
</form>
</div>
</div>
































<?php }else{

//$packages = $wpdb->get_results("SELECT ID, package_name FROM premiumpress_packages");

if(!isset($_GET['p']) || $_GET['p']==""){ $_GET['p']=1; }
$GLOBALS['results_per_page'] = 10;
$GLOBALS['user_fields'] = array('ID','user_login','user_pass','user_nicename','user_email','user_url','user_registered','user_activation_key','user_status','display_name');
$GLOBALS['meta_fields'] = array(
	'Last Name'		=>	'last_name',
	'First Name'	=>	'first_name',
	'Description'	=>	'description'
);
$GLOBALS['members_fields'] = array(
	'User Name'		=>	'user_nicename',
	'Email Address'	=>	'user_email',
	'Display Name'	=>	'display_name',
	'URL'			=>	'user_url'
);
 
$TotalResults 			= $PPM->scope();
$MEMBER_SEARCH_DATA 	= $PPM->MQuery();
PremiumPress_Header();  ?>


<script>
<?php /*
function da(x){for(var j=0;j<=x;j++){box=eval("document.orderform.d"+j);box.checked=true;}}
function du(x){for(var j=0;j<=x;j++){box=eval("document.orderform.d"+j);box.checked=false;}}*/ ?>
function clearMe(){

document.getElementById("query").value = "";
}
</script>
<style>.photo { max-width:50px; max-height: 50px; }

.PremiumPress_Members_AlphaSearch { margin-bottom:20px; }
.PremiumPress_Members_AlphaSearch ul, .PremiumPress_Members_AlphaSearch li { display:inline; list-style:none;text-indent:0; margin-bottom:20px;}
.PremiumPress_Members_AlphaSearch span { font-size:10px;font-style:italic; }
.PremiumPress_Members_AlphaSearch a { display:inline-block;padding:6px; padding-top:0px; padding-bottom:0px;  border:1px solid #ddd; background:#fff; margin-right:7px; }
.PremiumPress_Members_AlphaSearch a:hovor { background:#ddd; color:white; }
.PremiumPress_Members_AlphaSearch_Selected a { background:#990000; color:white; }


</style>

<div class="PremiumPress_Members_search">
		<form method="get" action="admin.php?page=members">			
			<input type="text" id="query" name="query" class="blur" value="Keyword.." onclick="clearMe();" />
			in <?php echo $PPM->selectPaired('by','by','','','All Fields',array($_REQUEST['by'])) ?>
			<input type="hidden" name="page" value="members" />			
			<input type="submit" value="Search" />
		</form>
</div>


<div class="PremiumPress_Members_AlphaSearch">
<div style="height:30px;">Quick Alphabetically Search <span>(by first name)</span>:</div>
<ul>
<li><span><a href="admin.php?page=members">All Person's</a></span></li>
<?php echo $PPM->alpha('admin.php?page=members');?>
</ul>
</div>

 
  
<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_members.png" align="middle"> Member Management</h3> <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a> 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active"> Results</a></li>
	<li><a href="#" onclick="window.location.href='admin.php?page=members&edit=0'">Add Person</a></li>
    
</ul>
</div>

<div id="premiumpress_tab1" class="content">

<form class="plain" method="post" name="orderform" id="orderform">
<input type="hidden" name="action" value="massdelete">
<fieldset>
<table cellspacing="0"><thead><tr>
<td class="tc"><input type="checkbox" id="data-1-check-all" name="data-1-check-all" value="true"/></td>
<td class="tc">Photo</td>
<th>Username <br /> <small>Order By Username </small><a href="admin.php?page=members&sort=user_nicename&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/su.png" align="middle"></a> <a href="admin.php?page=members&sort=user_nicename&order=desc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/sd.png" align="middle"></a></th>
<td>First Name / Last Name <br /><small>Order By First Name <a href="admin.php?page=members&sort=first_name&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/su.png" align="middle"></a> <a href="admin.php?page=members&sort=first_name&order=DESC"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/sd.png" align="middle"></a> Last Name <a href="admin.php?page=members&sort=last_name&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/su.png" align="middle"></a> <a href="admin.php?page=members&sort=last_name&order=DESC"><img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/sd.png" align="middle"></a> </small>    </td>
<td class="tc">Account Type</td>
<td class="tc">Actions</td>
</tr></thead>


<tfoot><tr><td colspan="6">
<label>
<select name="data-1-groupaction"><option value="delete">delete</option></select> selected items.</label>
<input class="button altbutton" type="submit" value="OK" style="color:white;" />
</td></tr></tfoot><tbody>


<?php


	$c = 0;  $checkbox=0;
	foreach($MEMBER_SEARCH_DATA as $user) {


		$user = new WP_User($user);
		$r = $user->roles;
		$r = array_shift($r);
		if(!empty($_REQUEST['role']) and $_REQUEST['role'] != $r) {
			continue;
		}
		$nu = $current_user; 
		$timesince = $PPT->TimeDiff($user->user_registered,true);
 
?>


<tr class="first">
<td class="tc"><?php if($user->ID !=1){ ?>
<input type="checkbox" value="on" name="d<?php echo $checkbox; ?>" id="d<?php echo $checkbox; ?>"/>
<input type="hidden" name="d<?php echo $checkbox; ?>-id" value="<?php echo $user->ID; ?>">
<?php } ?>
</td>

<td class="tc"><?php 

 
if(function_exists('userphoto') && userphoto_exists($user->ID)){ echo userphoto($user->ID); }else{ echo get_avatar($user->ID,32); }

?></td>
<td><a href="<?php echo $e;?>"><?php echo $user->user_nicename; ?></a> <br /> <small><a href='mailto:<?php echo $user->user_email;?>' title='e-mail: <?php echo $user->user_email;?>'><?php echo $user->user_email;?> <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/note.png" align="middle" /></a> </small></td>
<td><?php echo $user->first_name.' '.$user->last_name;?>  <br /> <small>Created about <?php echo $timesince; ?></small> </td>
<td>

  <?php echo StaffRoles($r,true);?><br />
 

</td>
<td class="tc">
<ul class="actions">
<li><a class="ico" href="admin.php?page=members&edit=<?php echo $user->ID; ?>" rel="permalink"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/pencil.png" alt="edit" /></a></li>
<?php if($user->ID !=1){ ?><li><a class="ico" class='submitdelete' href='admin.php?page=members&action=delete&id=<?php echo $user->ID; ?>' onclick="if ( confirm('Are you sure you want to delete this member?') ) { return true;}return false;">
<img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/cross.png" alt="delete" /></a>
</li><?php } ?>

 

</ul>
</td>
</tr>


</td>	</tr>
	</tbody>
<?php $checkbox++;  } ?>

</table>




<input type="hidden" name="totalorders" value="<?php echo $checkbox; ?>">
<div class="pagination"><ul><li><?php echo $PPM->viewing('admin.php?page=members'); ?></li></ul></div>
</form>
</div>




<div id="premiumpress_tab2" class="content">




<form class="fields" method="post" target="_self" enctype="multipart/form-data" style="padding:10px;">
<input name="csvimport" type="hidden" value="yes" />
<input type="hidden" class="hidden" name="type"  value="users" />
<input name="enc" type="hidden"  value="/" size="5">
<input name="del" type="hidden"  value="," size="5">
<input type="hidden"  name="heading"  value="yes" />
<input type="hidden"   name="rq"  value="yes" />


<fieldset style="padding:20px; margin-left:30px;">
<legend><strong>CSV Member Import Options</strong></legend>

<input type="file" name="import" class="input">
<p>Click the button above to select a .csv file that contains the information of the members you would like to import.</p>
 
<p><a href="">Click here to download a sample .csv file.</a> Your file should follow this format. </p> 
 
<input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" />
</fieldset>
</form>
 




</div>
</div>
 



<?php
 
}

?>