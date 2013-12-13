<?php

 

if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 

global $PPT;

// SERACH PLUGIN
require_once (TEMPLATEPATH ."/PPT/class/class_search.php");	
$searchData = new PPT_S;

include(str_replace("functions","",THEME_PATH)."/PPT/class/class_search_data.php");	

ppt_event_activation(); // REGISTER CRON SCHEDULES



if(isset($_POST['JUSSTARTED'])){
	update_option("JUSSTARTED","checked8");
} 
	
if(get_option("JUSSTARTED") != "checked8" && !isset($_POST['JUSSTARTED']) ){


$count_posts 	= wp_count_posts();

if($count_posts->publish > 10){
 

update_option("JUSSTARTED","checked8");

 
}else{



 
?>




<div style="padding:20px; background:#d6ffcf; border:1px solid #438b38; margin-top:20px;">

<h1>Website Reset Recommended</h1>
<p>We have detected that you are installing <?php echo constant('PREMIUMPRESS_SYSTEM'); ?> for the first time, we strongly recommend a theme reset to help you get started quickly.</p>
<small>Note, the reset will delete any pages/posts you have already setup in Wordpress and install sample data to help you get started.</small>
<br /><br />

<form method="post" target="_self">
<input name="reset" type="hidden" value="yes" />
<input name="JUSSTARTED" type="hidden" value="1" />
<select name="RESETME" style="font-size:20px;">
<option value="yes">Yes Please (recommended BUT will delete your current posts + categories)</option>
<option value="no">No Thank You</option>
</select> 

<input class="premiumpress_button" type="submit" value="Continue" style="color:white; margin-left:10px;" />

</form>
</div>


<?php }  }else{ 





PremiumPress_Header(); 
 ?>


 
 
 


<input type="hidden" value="" name="imgIdblock" id="imgIdblock" />
<script type="text/javascript">

function ChangeImgBlock(divname){
document.getElementById("imgIdblock").value = divname;
}

function ChangeCatLogo(){
 ChangeImgBlock('catlogo');
 formfield = jQuery('#catlogo').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;	
}

jQuery(document).ready(function() {
 

jQuery('#upload_logo').click(function() {
 ChangeImgBlock('logo');
 formfield = jQuery('#logo').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?><?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?><?php } ?>);
 return false;
});
 
 jQuery('#upload_fav').click(function() {
 ChangeImgBlock('fav');
 formfield = jQuery('#fav').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});



window.send_to_editor = function(html) {
 imgurl = jQuery('img',html).attr('src'); 
 jQuery('#'+document.getElementById("imgIdblock").value).val(imgurl);
 tb_remove();
} 

});

</script>

 
<script type="text/javascript">
 jQuery(document).ready(function() {
   jQuery("#themepreview").change(function() {
     jQuery("#imagePreview").empty();
     if ( jQuery("#themepreview").val()!="" ){
        jQuery("#imagePreview").append("<img src=\"<?php echo $GLOBALS['template_url']; ?>/themes/" + jQuery("#themepreview").val()  + "/screenshot.png\" style='border:1px solid #333;' />");
     }
     else{
        jQuery("#imagePreview").append("displays image here");
     }
   });
 });
</script>


 
 
 


<div class="msg msg-info"><p>Would you like to reset your website back to factory settings? <strong> Recommended for new installs.</strong> <a href="javascript:void(0);" onClick="toggleLayer('reset');">Click here to learn more.</a></p></div>

<form method="post" target="_self">
<input name="reset" type="hidden" value="yes" />

<table class="maintable" style="background:white; display:none" id="reset">

 
	<tr class="mainrow">
		<td class="titledesc">Reset</td>
		<td class="forminp">
			<select name="RESETME"><option value="no">No</option> <option value="yes">Yes (Warning: You will lose all current data)</option></select><br />
			<small>If you select reset your database will be cleared and you will start from the theme defaults.</small>

		</td>
	</tr>

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Restore Defaults" style="color:white; margin-left:10px;" /></p></td>
</tr>
</table>

</form>
































<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" />


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_setup.png" align="middle"> General Setup</h3> <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a> 						 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">System</a></li>
    <li><a rel="premiumpress_tab4" href="#">Category</a></li>
	<li><a rel="premiumpress_tab2" href="#">Page</a></li>
    <li><a rel="premiumpress_tab3" href="#">Image</a></li>
     <li><a rel="premiumpress_tab5" href="#">Search</a></li>
	<li><a rel="premiumpress_tab6" href="#">Default Settings</a></li> 
</ul>
</div>

<style>
select { border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px; }
</style>

<form method="post" target="_self" enctype="multipart/form-data">
<input name="submitted" type="hidden" value="yes" />
<input name="admin_page" type="hidden" value="general_setup" />


<div id="premiumpress_tab1" class="content">
<table class="maintable" style="background:white;"> 


 


<?php /* ============================ 1 ================================= */ ?>

<tr class="mainrow"><td></td><td class="forminp" valign="top">
		<p><b>Child Theme / Website Template </b></p>

		<select name="adminArray[theme]" style="width: 240px;  font-size:14px; backgound:red;" id="themepreview">
		<?php

		$HandlePath = PPT_THEMES_DIR;
	    $count=1;
		if($handle1 = opendir($HandlePath)) {      
			while(false !== ($file = readdir($handle1))){			
				if(strpos($file,".") ===false && strpos($file,strtolower(constant('PREMIUMPRESS_SYSTEM'))) !== false){	
				
				
							
					$TemplateString .= "<option "; 
					if (get_option("theme") == $file) { $TemplateString .= ' selected="selected"'; }   
					$TemplateString .= 'value="'.$file.'">'; 
					if($file ==strtolower(constant('PREMIUMPRESS_SYSTEM'))."-default"){ $TemplateString .= "Default (".constant('PREMIUMPRESS_SYSTEM')." Theme)";  }else{ $TemplateString .= str_replace("-"," ",str_replace(strtolower(constant('PREMIUMPRESS_SYSTEM')),"",$file)); } 					
					$TemplateString .= "</option>";			
   
				}
			}
		}
		echo $TemplateString;

		?>
		</select> 
            <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;The list will display all of the child themes found in the folder; <br /><small> 'wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/themes/'.</small><br /><br /><strong>The list is empty or will not save</strong><br />This is likely because you have renamed the theme folder to something other than '<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>' or that your file paths are not setup correctly on your hosting account.<br /><br /><strong>How to add my own child theme?</strong><br />Simply copy and rename any of the child themes in the  folders; <br /><small> 'wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/themes/'</small><br /> it will then display in this list to be selected.&quot;);"/>      

<br />

<p class="ppnote">Here you select the design for your website.</p>   
        
        
        
         <br />   <br /> 
<div id="imagePreview"><?php if(get_option('theme') !=""){ ?> <img src="<?php echo $GLOBALS['template_url']; ?>/themes/<?php echo get_option('theme'); ?>/screenshot.png" style='border:1px solid #333;' /><?php } ?></div>


</td><td class="forminp" valign="top">
 
 
 <p><b>Website Language </b></p>
		<select name="adminArray[language]" class="small-input" style="width: 240px;  font-size:14px;">
		<?php
		
		$HandlePath = str_replace("functions/","",THEME_PATH) ."/template_".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/";	   
	    $count=1;
		if($handle1 = opendir($HandlePath)) {
      
	  	while(false !== ($file = readdir($handle1))){	

		if(substr($file,-4) ==".php" && substr($file,0,8) == "language"){
		$file = str_replace(".php","",$file); 
		$name = explode("_",$file);
		?>
			<option <?php if (get_option("language") == $file) { echo ' selected="selected"'; } ?> value="<?php echo $file; ?>"><?php echo $name[1]." ".$name[0]; ?></option>
		<?php
		} }}
		?>	 
		</select>  
        
                 <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;The list will display all of the files that begin with 'language_' in the folder; <br /><small> '<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/template_<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/'.</small><br /><br /><strong>The list is empty or will not save</strong><br />This is likely because you have renamed the theme folder to something other than '<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>' or that your file paths are not setup correctly on your hosting account.<br /><br /><strong>How to add my own language?</strong><br />Simply copy and rename the file 'language_english.php in the  folder; <br /><small> '<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/template_<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/'</small><br /> to something like 'language_french.php' and translate the text in that file.&quot;);"/>     
      


<p class="ppnote">Here you select your website language.</p>   

        
        
 <br />
 
 
 		<p><b>Allow User Registration</b> </p>
     
			<select name="adminArray[users_can_register]" style="width: 240px;  font-size:14px;">
			<option value="" <?php if(get_option("users_can_register") ==""){ print "selected";} ?>>Off</option>
			<option value="1" <?php if(get_option("users_can_register") =="1"){ print "selected";} ?>>On (Recommended)</option>
			</select>
            
            <br />
			 
<p class="ppnote">Here you on/off visitor registration.</p>   
 
 
 <br />
 
 
 		<p><b>Maintenance Mode </b></p>
     
			<select name="adminArray[maintenance_mode]" style="width: 240px;  font-size:14px;">
			<option value="no" <?php if(get_option("maintenance_mode") =="no"){ print "selected";} ?>>Disabled</option>
			<option value="yes" <?php if(get_option("maintenance_mode") =="yes"){ print "selected";} ?>>Enable Maintenance Mode</option>
			</select><br />
            
<p class="ppnote">Here you on/off maintenance mode which prevents visitors from accessing your website whilst you perform update work.</p>  
            
			 
 <?php if(get_option("maintenance_mode") =="yes"){ ?>

 <textarea name="adminArray[maintenance_mode_message]" type="text" class="txt" style="width:240px;height:100px; font-size:14px;"><?php echo stripslashes(get_option("maintenance_mode_message")); ?></textarea>

<p class="ppnote">Here you enter a message that will be displayed to your website visitors. Accepts HTML.</p>  

 <?php } ?>        
        
              
        
</td></tr>
<?php /* ============================ ================================= */ ?>



 






<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp" valign="top">
	
	<p><b>Upload Child Theme</b></p>
     
		 <input name="childtheme" type="file" style="font-size:14px; width:240px;" /> <br />
         
<p class="ppnote">Here you can upload new child themes for <?php echo PREMIUMPRESS_SYSTEM; ?></p>  
         
			 
            
            <p>All available child themes can be downloaded from the <a href="http://clients.premiumpress.com">VIP client area on our website.</a></p>
 
 
  		<p><b>Enable iPhone/Mobile Website *beta*</b> </p>
     
			<select name="adminArray[ppt_mobile]" style="width: 240px;  font-size:14px;">
			<option value="" <?php if(get_option("ppt_mobile") ==""){ print "selected";} ?>>Off</option>
			<option value="1" <?php if(get_option("ppt_mobile") =="1"){ print "selected";} ?>>On (Recommended)</option>
			</select>
            
            <br />
			 
<p class="ppnote">This will turn on/off the compact version of your website when viewed by mobile devices.</p>   
 
 
 
 
 

</td><td class="forminp">
 
 
 <p><b>Footer Powered By Credits </b></p>
       
<select name="adminArray[removecopyright]" style="width: 240px;  font-size:14px;">
				<option value="yes" <?php if(get_option("removecopyright") =="yes"){ print "selected";} ?>>Removed Footer Notice </option>
				<option value="no" <?php if(get_option("removecopyright") == "no"){ print "selected";} ?>>Show Notice (Thank You)</option>
			</select><br />
            
<p class="ppnote">Here you on/off the 'developed by PremiumPress' credits in the footer.</p>  
            
			 
  <br />          
	<p><b>Footer Copyright Text </b></p>
     
		<input name="adminArray[copyright]" type="text" class="txt" value="<?php echo get_option("copyright"); ?>" style="width: 240px;font-size:14px;" /><br />


<p class="ppnote">Here you enter your own copyright information which will be displayed in the website footer.</p>  
    
                 
</td></tr>
<?php /* ============================ ================================= */ if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress"){  ?>



<tr class="even"><td colspan="2" class="first last">
<div class="msg msg-info">
  <p>
<b>ShopperPress Version 6 DOES NOT require you to select a system type</b>, everything is now integrated allowing you to run download stores, shopping carts and affiliate stores all in one. </p>
</div>
</td></tr>

 
<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow" style="padding:5px; background:#dcffd1;"><td></td><td class="forminp">
<div style="padding:5px; background:#dcffd1;">
      <p><b>Enable Quantity Control</b></p>
            
            <select name="adminArray[display_ignoreQTY]" style="width: 240px;  font-size:14px;">				
				<option value="yes" <?php if(get_option("display_ignoreQTY") =="yes"){ print "selected";} ?>>Enable -  Manage Stock Levels</option>
                <option value="no" <?php if(get_option("display_ignoreQTY") =="no"){ print "selected";} ?>>Disable - I'm not managing stock Levels</option>
			</select>
            
            
            
            <p>This will setup shopperpress to monitor and prevent stock being oversold. Note. requires you to provide valid stock levels within products.</p>
</div>           
 

</td><td class="forminp">
 
 
    <p><b>Enable Credit Purchase Options</b></p>
            
            <select name="adminArray[display_credit_options]" style="width: 240px;  font-size:14px;">				
				<option value="yes" <?php if(get_option("display_credit_options") =="yes"){ print "selected";} ?>>Yes</option>
                <option value="no" <?php if(get_option("display_credit_options") =="no"){ print "selected";} ?>>No - Disabled</option>
			</select>
            
            <p>Credits are used in download stores, if you are not creating a download store then you can disable this option.</p>
       
</td></tr>

  

 <?php /***************************************** */ ?> 
     
<tr class="mainrow"><td></td><td class="forminp">

		<p><b> Amazon Buy Now Button</b></p>			
				
			
			<select name="adminArray[display_single_amazonbutton]" style="width: 240px;  font-size:14px;">
				<option value="yes" <?php if(get_option("display_single_amazonbutton") =="yes"){ print "selected";} ?>>Show the Amazon buy button</option>
				<option value="no" <?php if(get_option("display_single_amazonbutton") =="no"){ print "selected";} ?>>Hide</option>
			</select>
            
            
        <p class="ppnote">Here you can show/hide the Amazon buy now button.</p>
        
        
		<p><b> Amazon Checkout</b></p>			
				
			
			<select name="adminArray[display_amazon_checkout]" style="width: 240px;  font-size:14px;">
				<option value="yes" <?php if(get_option("display_amazon_checkout") =="yes"){ print "selected";} ?>>Turn On Amazon Checkout</option>
				<option value="no" <?php if(get_option("display_amazon_checkout") =="no"){ print "selected";} ?>>Disable</option>
			</select>            
            
           <p class="ppnote">Here you can tunr on/off the Amazon checkout options.</p>
      
      
            
</td><td class="forminp"><img src="<?php echo $GLOBALS['template_url']; ?>/template_shopperpress/images/help2/10.png"></td></tr>    
 
 
<?php } /* ============================ ================================= */ ?>

 

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>

</table> 
 
 
 

 
</div>
<div id="premiumpress_tab2" class="content">
<table class="maintable" style="background:white;">

	<tr class="mainrow">
		<td class="titledesc" valign="top">
        
        Hide Page Displays <br /><br /> 
        
        
        <p class="ppnote">Here you can tick any of the boxes next to pages you DO NOT want to be displayed on your website.</p>  
      
        <p class="ppnote1">You can set the display order for pages using the normal page order settings or installing <a href="http://wordpress.org/extend/plugins/my-page-order/" target="_blank">this plugin.</a></p>  
      
        
        
         
        
        </td>
		<td class="forminp">
 
	<?php

		$SAVED_DISPLAY = get_option("excluded_pages");
		if($SAVED_DISPLAY != ""){ $pageArray = explode(",",$SAVED_DISPLAY); }else{ $pageArray = array(); }
		$Pages = get_pages("parent=0"); //
		$Page_Count = count($Pages);	
 		$i=0;	 
		foreach ($Pages as $Page) {		

		print '<div style="background:#efefef; padding:8px; border:1px solid #ddd; font-size:16px; font-weight:bold;">
		<input name="nav_page['.$i.']" type="checkbox" value="'.$Page->ID.'"';

		if( in_array($Page->ID,$pageArray) ){ print 'checked="checked"'; }
		print '> ' . $Page->post_title.'';
		print ' </div><br />';
		$i++;
		}		
	

?>
		</td>
	</tr>
 
 
 <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress"){ ?>

<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">
	 
 		<p><b>Where should the pages be displayed?</b></p>
<select name="adminArray[display_showpages]" style="width: 280px;  font-size:14px;">
				<option value="yes" <?php if(get_option("display_showpages") =="yes"){ print "selected";} ?>>Main Navigation Bar (instead of categories) </option>
				<option value="next" <?php if(get_option("display_showpages") =="next"){ print "selected";} ?>>Main Navigation Bar (Next to existing categories)</option>
				<option value="top" <?php if(get_option("display_showpages") =="top"){ print "selected";} ?>>Top (above the logo)</option>
				<option value="sub" <?php if(get_option("display_showpages") =="sub"){ print "selected";} ?>>Sub Menu Bar (next to login/logout)</option>
                <option value="hide" <?php if(get_option("display_showpages") =="hide"){ print "selected";} ?>>Hide</option>
			</select><br />
		 
    <p class="ppnote">Here you can choose where the pages are displayed on your website.</p>  
      
</td><td class="forminp">
 
 <img src="<?php echo IMAGE_PATH; ?>/help2/1.png">

   
</td></tr>
<?php /* ============================ ================================= */  } ?>
 

 
<tr><td colspan="2">
<div class="msg msg-info">
  <p>
Below you setup your page links and buttons. You MUST have created the page already before they will be displayed below so if you haven't done so already, <a href="edit.php?post_type=page" style="text-decoration:underline;">click here</a> to create a new page for each of your buttons.
</p>
</div>
</td></tr>
<tr class="mainrow"><td>
<?php
$p=1;

switch(strtolower(constant('PREMIUMPRESS_SYSTEM'))){

	case "auctionpress": {
	$MakePagesArray = array("checkout_url","submit_url","messages_url","dashboard_url","contact_url","manage_url","payment_url","tc_url");
	} break;
	
	case "directorypress": {
	$MakePagesArray = array("submit_url","messages_url","dashboard_url","contact_url","manage_url","tc_url");
	} break;

	case "couponpress": {
	$MakePagesArray = array("submit_url","messages_url","dashboard_url","contact_url","manage_url","tc_url");
	} break;


	case "classifiedstheme": {
	$MakePagesArray = array("submit_url","messages_url","dashboard_url","contact_url","manage_url","tc_url");
	} break;
	
	case "shopperpress": {
	$MakePagesArray = array("checkout_url","messages_url","dashboard_url","contact_url","manage_url","payment_url","wishlist_url");
	} break;
	
	case "moviepress": {
	$MakePagesArray = array("dashboard_url","contact_url","payment_url");
	} break;
			
	default: { $MakePagesArray = array("submit_url","messages_url","dashboard_url","contact_url","manage_url","tc_url"); }

}

$pageTitles = array(
"tc_url" => "Terms and Conditions",
"checkout_url" => "Checkout",
"submit_url" => "Add/Submission",
"messages_url" => "Private Message",
"dashboard_url" => "My Account",
"contact_url" => "Contact",
"manage_url" => "Edit/Manage Listing",
"payment_url" => "Payment",
"wishlist_url" => "Wishlist"
);

foreach($MakePagesArray as $pageBit){
?>



	<?php if($p ==3){ ?><tr class="mainrow"><td><?php $p=1; } $p++; ?>
    
    </td><td class="forminp">
    
		<p><b><?php echo $pageTitles[$pageBit]; ?> Page Link </b></p>
		<?php /*<select class="txt" style="width: 240px;font-size:14px;" onChange="document.getElementById('<?php echo $pageBit; ?>').value=this.value"><option value="" selected=selected>----</option><?php echo $PPT->PageSelection(get_option($pageBit)); ?></select>*/ ?>
        
        <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" style="float:right;" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>How does this work?</b><br />Here you enter the website link for the page that the user will see when they click on the '<?php echo str_replace("_url","",$pageBit); ?> buttons. <br /><br /><b>How do i setup the <?php echo str_replace("_url","",$pageBit); ?> page?</b><br />The  <?php echo str_replace("_url","",$pageBit); ?> page is a normal page in Wordpress but when creating the page select the '<?php echo str_replace("_url","",$pageBit); ?> template' from the 'page attributes' list on the right side of the page.<br /><br /><b>The page in the listbox doesnt save / show the page i have entered?</b><br />Dont worry it's not supposed to, it's only there to help you see what pages you have already created in Wordpress. &quot;);"/> 
        
        
		<input name="adminArray[<?php echo $pageBit; ?>]" id="<?php echo $pageBit; ?>" type="text" class="txt" value="<?php echo get_option($pageBit); ?>" style="width: 280px;" />
        
    <p class="ppnote">Here you enter the link to your <?php echo strtolower($pageTitles[$pageBit]); ?>.</p>  
      
		  
        
        
 <?php } ?>       
        
        

</td></tr>    






<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">
		<p><b>Display FAQ Section</b></p>
     

			<select name="adminArray[display_FAQ]" style="width:240px;  font-size:14px;">
            <option value="yes" <?php if(get_option("display_FAQ") =="yes"){ print "selected";} ?>> Display </option>
			<option value="no" <?php if(get_option("display_FAQ") =="no" || get_option("display_FAQ") ==""){ print "selected";} ?>> Do not display</option>
			</select>	
 
           		
			<p class="ppnote">Here you turn on/off the display of FAQ's on your contact page.</p>  
    
			 

</td><td class="forminp">
 
  
<img src="<?php echo $GLOBALS['template_url']; ?>/images/help/b1.png" />
   
</td></tr>
<?php /* ============================ ================================= */ ?>














      
<td colspan="3">

 


 

</td>
 
 
     
    <tr><td colspan="2">
<div class="msg msg-info">
  <p>
By default the text displayed on the contact page is found in the language file, if you enter text before it will override the default text and only display the contents you add below.
</p>
</div>
</td></tr>
    
    
    
    	<tr class="mainrow">
		<td class="titledesc">Contact Page Text</td>
		<td class="forminp">
		<textarea name="adminArray[contact_page_text]" type="text" style="width:550px;height:250px;"><?php echo stripslashes(get_option("contact_page_text")); ?></textarea>
		
        <p class="ppnote">Here you enter your own custom text which will be displayed on the contact page. HTML code is accepted.</p>  
    
        
		</td>
	</tr>

 

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>

</table>
</div>















<div id="premiumpress_tab3" class="content"><table class="maintable" style="background:white;">	






<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">
		<p><b>Website Logo</b></p>
     

			<input name="adminArray[logo_url]" id="logo" type="text" class="txt" style="width: 240px;font-size:14px;" value="<?php echo get_option("logo_url"); ?>" />
            
            
          <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot; <strong>How does this work?</strong><br />Create your logo first <u>then</u> either enter the full http:// web link to your image in the box OR upload it using the 'image manager' tool then it should be visible when selecting the 'view uploaded images' link.<br /><br /><b>Where are images stored?</b><br />All <?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?> images are stored in the folder: <br /><small> 'wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/thumbs/'</small>.&quot;);"/>       
                 
            <br />
            
<p class="ppnote">Here you enter the link to your website logo. Accepts both image name and full http:// image link.</p>  
    
            
		 
<p> 

<input onClick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','logo');" type="button"   value="View Images"  />

 <input id="upload_logo" type="button" size="36" name="upload_logo" value="Upload Image"  />



</p>	 

</td><td class="forminp">
 
 
 <p><b>Browser .favicon Image </b></p>
 
<input name="adminArray[faviconLink]" type="text" class="txt" value="<?php echo get_option("faviconLink"); ?>" id="fav" style="width: 240px;font-size:14px;" />

         <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>What is a Fav Icon?</b><br />A favicon (short for favorites icon), also known as a shortcut icon, website icon, URL icon, or bookmark icon is a 16x16 or 32x32 pixel square icon associated with a particular website or webpage. It is displayed next to your website name in the browser address bar.<br /><br />More information on fav icons can be <a href='http://en.wikipedia.org/wiki/Favicon' target='_blank'>found here.</a>  &quot;);"/>  
        
<p class="ppnote">Here you enter the full http:// link to your .fav icon.</p>  
  
<br /><input id="upload_fav" type="button" size="36" name="upload_fav" value="Upload FavIcon" />
   
</td></tr>
<?php /* ============================ ================================= */ ?>







<tr class="even"><td colspan="2" class="first last">
<div class="msg msg-info">
  <p>
Below you can view and configure your image storage paths, <b>we strongly recommend you do not touch these settings</b> unless you are customizing your storage path locations and are familiar with storage paths.
</p>
</div>
</td></tr>



<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow" style="padding:5px; background:#dcffd1;"><td></td><td class="forminp" valign="top">



		<p><b>Theme File Storage Path</b></p>
     
     <?php 
	 
	 $ss1 = stripslashes(get_option("imagestorage_path"));
	 $ss2 = stripslashes(get_option("imagestorage_link"));
	 $ss3 = stripslashes(get_option("upload_path"));
	 $ss4 = stripslashes(get_option("upload_url_path"));
	 
	 
	 if(strlen($ss1) < 2){ $ss1 = PPT_THUMBS; } 	 
	 if(strlen($ss2) < 2){ $ss2 = $GLOBALS['template_url']."/thumbs/"; }
	 
	 if(strlen($ss3) < 2){ $ss3 = "wp-content/themes/".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/thumbs"; }
	 if(strlen($ss4) < 2){ $ss4 = $GLOBALS['template_url']."/thumbs/"; }
	 ?>
	 
<?php if (strpos($ss1, strtolower(constant('PREMIUMPRESS_SYSTEM'))) === false) { ?>
<div class="msg msg-error">  <p>Your current path is wrong!</p></div>
<?php } ?>	 

<input name="adminArray[imagestorage_path]" type="text" class="txt" style="width: 240px; font-size:14px;" value="<?php echo $ss1;  ?>" />

               <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>How does this work?</b><br />This is the hosting server path to your images folder where you will store your member and website images. <br /><br /><b>What should i enter here?</b><br /> It is recommended to enter the link below into the box: <br /> <small><?php print str_replace("PPT","thumbs",str_replace("\"","/",PPT_PATH)); ?></small><br /><br /> <b>My images dont save or upload</b>The common issue here is that the path you entered is incorrect and/or the folder is NOT CHMOD 777, please contact your hosting provider for the correct path and confirm that the path you have entered is CHMOD 777. &quot;);"/>  


<br /><p class="ppnote1"><b>Recommended Path:</b><br /> <span style="font-size:10px;"><?php print PPT_THUMBS; ?></span></p>
 

 			       
 
<p><b>Theme Storage Link</b></p>

<?php if (strpos($ss2, strtolower(constant('PREMIUMPRESS_SYSTEM'))) === false) { ?>
<div class="msg msg-error">  <p>Your current path is wrong!</p></div>
<?php } ?>
 

<input name="adminArray[imagestorage_link]" type="text" class="txt" style="width: 240px; font-size:14px;"  value="<?php echo $ss2; ?>" />

<img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>How does this work?</b><br />This is the website link (http://..) to your images folder where you will store your product and website images. <br /><br /><b>What should i enter here?</b><br /> It is recommended to enter the link below into the box: <br /> <small><?php echo $GLOBALS['template_url']; ?>/thumbs/</small> &quot;);"/>  

<br /><p class="ppnote1"><b>Recommended Path:</b><br /> <span style="font-size:10px;"><?php echo PPT_THUMBS_URI; ?></span></p>


<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress"){   ?>

<p><b>Secure File Download Path (Server Path NOT http://)</b></p>

 

 

<input name="adminArray[download_server_path]" type="text" class="txt" style="width: 240px; font-size:14px;"  value="<?php echo get_option('download_server_path'); ?>" />
<p class="ppnote"><b>Note, this is for download stores only.</b> Here you enter the directory path to where your file downloads are located.</p>
<p class="ppnote1"><b>Recommended Path:</b><br /> <span style="font-size:10px;"><?php print PPT_THUMBS; ?></span></p>

<?php } ?> 
 
</td><td class="forminp" valign="top">


<?php if(defined('MULTISITE') && MULTISITE != false){ ?>

<div class="msg msg-info">  <p>You are running WP Network (multi website feature) therefore the network storage path cannot be adjusted. <br /><br /> It is recommended you upload all media to your theme 'thumbs' folder; <br /><br /> <?php print PPT_THUMBS; ?> </p></div>
<?php }else{ ?>

<p><b>Wordpress Storage Folder</b></p>

<?php if (strpos($ss3, strtolower(constant('PREMIUMPRESS_SYSTEM'))) === false) { ?>
<div class="msg msg-error">  <p>Your current path is wrong!</p></div>
<?php } ?>

 
<input name="adminArray[upload_path]" type="text" class="txt" style="width: 240px; font-size:14px;"  value="<?php echo $ss3; ?>" />

 
<br /><p class="ppnote1"><b>Recommended Path:</b><br /> <span style="font-size:10px;"><?php echo "wp-content/themes/".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/thumbs"; ?></span></p>


 <p><b>Wordpress Storage Link</b></p>
 
 <?php if (strpos($ss4, strtolower(constant('PREMIUMPRESS_SYSTEM'))) === false) { ?>
<div class="msg msg-error">  <p>Your current path is wrong!</p></div>
<?php } ?>
 

<input name="adminArray[upload_url_path]" type="text" class="txt" style="width: 240px; font-size:14px;"  value="<?php echo $ss4; ?>" />

<br /><p class="ppnote1"><b>Recommended Path:</b><br /> <span style="font-size:10px;"><?php echo substr(PPT_THUMBS_URI,0,-1); ?></span></p>


<input type="hidden" name="adminArray[uploads_use_yearmonth_folders]" value="0" />

<?php } ?> 
           
</td></tr>
<?php /* ============================ ================================= */ ?>














<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>

<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">
		<p><b>Website thumbnail API</b></p>
     

			<select name="adminArray[display_previewimage_type]" style="width:240px;  font-size:14px;">
            <option value="off" <?php if(get_option("display_previewimage_type") =="off" || get_option("display_previewimage_type") ==""){ print "selected";} ?>> Disabled (Default Recommendation)</option>
			<option value="directory" <?php if(get_option("display_previewimage_type") =="directory"){ print "selected";} ?>> Enable PremiumPress Free API</option>
            <option value="custom" <?php if(get_option("display_previewimage_type") =="custom"){ print "selected";} ?>>Enable ShrinkTheWeb API</option>
			</select>	
 <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>How does this work?</b><br />Our thumbnail API will try to generate a image preview of any website from the website URL entered. <br /><br /><b>Only used on some websites.</b><br /> <br /><a href='http://www.premiumpress.com/documentation/' target='_blank'>see documentation for full details.</a> &quot;);"/>  
           		

<p class="ppnote">Here you can choose which API to use when generating website image thumbnails.</p>  
 

 
 

</td><td class="forminp">
 
 
 <p><b>cURL Image Storage</b></p>
 
 		<select name="adminArray[image_preview_storage]" style="width: 240px;  font-size:14px;" <?php if(get_option("display_previewimage_type") =="off"){ print "disabled"; } ?>>
			<option value="yes" <?php if(get_option("image_preview_storage") =="yes"){ print "selected";} ?> >Enable</option>
			<option value="no" <?php if(get_option("image_preview_storage") =="no"){ print "selected";} ?>>Disable</option>
			</select>
			
			<br />
<p class="ppnote">Here you can enable image storage of captured website thumbnails using cURL.</p>  
 

   
</td></tr>
<?php /* ============================ ================================= */ ?>



<?php } ?>


<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">
		<p><b>Thumbnail Resize</b></p>
     

			<select name="adminArray[thumbresize]" style="width:240px;  font-size:14px;">
            <option value="on" <?php if(get_option("thumbresize") =="on" || get_option("thumbresize") ==""){ print "selected";} ?>> Enabled (Recommended)</option>
			<option value="off" <?php if(get_option("thumbresize") =="off"){ print "selected";} ?>> Disabled</option>
            
			</select>	
 
			<br />
           <p class="ppnote">Here you can enable/Disable the thumbnail resize script which will automatically resize images for best fit</p>  

            
			 

</td><td class="forminp">
 
 
 
   
</td></tr>
<?php /* ============================ ================================= */ ?>









     
    <?php if(get_option("display_previewimage_type") =="custom"){  /* ============================ SHRINK THE WEB API ================================= */?>
    
    
    
    	<tr class="mainrow">
		<td class="titledesc" colspan="2">
        
        
        
        <div style="border:1px solid #666; background:#e6f4ff;">
       <a href="http://www.shrinktheweb.com/a/markfail" target="_blank"> <img src="<?php echo $GLOBALS['template_url']; ?>/images/stw.jpg"></a>
        
        <div style="padding:20px;">
     
     
     
     <table>
       	<tr class="mainrow">
		  
		<td class="forminp" valign="top">
        
        
		<p><b>ShrinkTheWeb API Details</b></p>			
			ACCESS_KEY: <input name="adminArray[STW_access_key]" type="text" value="<?php echo get_option("STW_access_key"); ?>" style="width: 100px;  font-size:14px;">
<br />
SECRET_KEY: <input name="adminArray[STW_secret_key]" type="text" value="<?php echo get_option("STW_secret_key"); ?>" style="width: 100px;  font-size:14px;">
<br />
 
 
 
 
			<small>The details above are found in the ShrinkTheWeb admin pages.</small>
            
            
            
            <div style="clear:both;"></div>
            
            <img src="<?php echo $GLOBALS['template_url']; ?>/images/stw2.png">
           
            
            </td>
		<td class="forminp" valign="top">
        
        
           <b>ShrinkTheWeb PRO Features </b>
            
            <p><small>(the below options are for ShrinkTheWeb PRO account holders only, please refer to their manual <a href="http://www.shrinktheweb.com/uploads/PRO_Feature_Documentation.pdf" target="_blank">here</a> for help with this)</small></p>
            
           <input name="stw_1" type="checkbox" value="1" <?php if(get_option("stw_1") =="1"){ print "checked=checked"; } ?> /> Specific Page Capture <br />
            
           <input name="stw_2" type="checkbox" value="1"<?php if(get_option("stw_2") =="1"){ print "checked=checked"; } ?> />  
           Refresh on-Demand <br />
            
           <input name="stw_3" type="checkbox" value="1" <?php if(get_option("stw_3") =="1"){ print "checked=checked"; } ?> />  Full Page Capture <br /><br />
            
            Custom Image Size <br />
            
            <input name="adminArray[stw_4]" type="text" style="width:80px;" value="<?php echo get_option("stw_4"); ?>"/> 
            e.g. 200 <br /><small>(will create image sizes of 200px width)</small><br /><br />
            
            Custom Delay <br />            
            
            <input name="adminArray[stw_5]" type="text" style="width:80px;" value="<?php echo get_option("stw_5"); ?>"/> 
            e.g. 40 <br /><small>To specify wait after page load in seconds - max 45s</small><br /><br />
             
             
            Custom quality <br />
            
             <input name="adminArray[stw_6]" type="text" style="width:80px;" value="<?php echo get_option("stw_6"); ?>"/> 
             e.g. 80 <br />
             <small>Where X represents the output image quality percent e.g.  for 80%</small>  
        
        
        </a>
		</td>
	</tr>
     </table>
     
        </div>
        
        </div>
        
        
        
        
        </td>		 
	</tr>
    
    <?php }  /* ============================ SHRINK THE WEB API ================================= */   ?>
    
    
 
 
 


<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>

</table>
</div>
<div id="premiumpress_tab4" class="content">
 
 
 <table class="maintable" style="background:white;">	
   	<tr class="mainrow">
		<td class="titledesc" valign="top">Display Categories <br /><br />
        
        
        <p class="ppnote">Here you tick the box next to categories YOU DO WANT to be displayed on your website.</p>
        
			 
            
       </td>
		<td class="forminp">			 

			
		</td>
	</tr> 
 
 <tr class="even"><td colspan="2" class="first last">
		<?php
		 
	$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
	$Maincatcount = count($Maincategories);	
	$SAVED_DISPLAY = get_option("nav_cats");
	$i=0;
	foreach ($Maincategories as $Maincat) {
		 
		if($Maincat->parent ==0){
		
		print '<div style="background:#efefef; padding:8px; border:1px solid #ddd; font-size:12px; font-weight:bold; float:left; width:350px; margin-right:10px; margin-bottom:10px; "><input name="nav_cat['.$i.'][ID]" type="checkbox" 
		value="'.$Maincat->cat_ID.'"';
		if( isset($SAVED_DISPLAY[$i]['ID']) ){ print 'checked="checked"'; }
		print 'style="margin-right:10px;">' . $Maincat->cat_name.' ';
		print ' ( Order: <input name="nav_cat['.$i.'][ORDER]" type="text" value="';
		if(isset($SAVED_DISPLAY[$i]['ORDER']) && is_numeric($SAVED_DISPLAY[$i]['ORDER']) ){ print $SAVED_DISPLAY[$i]['ORDER']; }
		print '" style="width:30px;"> )';
		print ' </div> ';
										$i++;
		}	
	}	 
	?>
</td></tr>
    
    
 <?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">
		<p><b>Hidden Categories</b></p>
     
 
   
<select class="txt" onChange="document.getElementById('article_cats').value += '-'+this.value+','" style="width: 240px;  font-size:14px;">
<?php echo $PPT->CategoryList(0,true); ?>
</select>

              <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>How does this work?</b><br />Some times you may want to completely hide a category from dislay on your website, enter a category ID here todo this.<br /><br />Category ID's entered will be hidden from your website, separate multiple categories with a comma. <b>NOTE,</b> category ID's must have a minus sign in front of them, like this: -10,-20  &quot;);"/>  
        

		<input name="adminArray[article_cats]" id="article_cats" type="text" class="txt" value="<?php echo get_option("article_cats"); ?>" style="width: 240px; font-size:14px;" /><br />

  
			<br />
            
            <p class="ppnote">Here you can enter category ID's and they will be excluded from page content display.</p>
        
            
			  

</td><td class="forminp">
 
 
	 
<p><b>Category Count</b></p>			
			<select name="adminArray[display_categories_count]" style="width: 240px;  font-size:14px;">
				<option value="yes" <?php if(get_option("display_categories_count") =="yes"){ print "selected";} ?>>Show</option>
				<option value="no" <?php if(get_option("display_categories_count") =="no"){ print "selected";} ?>>Hide</option>
			</select><br />
            
            
  <p class="ppnote">Here you show/hide the category count next to the category names. ie: Category Name (100)</p>
                  
            
			 
   
</td></tr>
<?php /* ============================ ================================= */ ?>
   
    

	<tr class="mainrow">
		 <td></td>
		<td class="forminp">
		<div class="msg msg-info">
  <p>
Here you can setup customized category headers/descriptions which will appear at the top of the category.
</p>
</div> </td>
		<td class="forminp"><img src="<?php echo $GLOBALS['template_url']; ?>/template_<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/images/help/a24.png">
		</td>
	</tr>
    
    
 
 
	<tr class="mainrow">
		<td class="titledesc">Select Category</td>
		<td class="forminp">

 	<select name='cat' onChange="CatTextBox(this.value);"  style="width: 440px;  font-size:14px;">
	<option value='-1'>Select One</option>
	 <?php 

		$catlist="";
 		$Maincategories = get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');		
		$Maincatcount = count($Maincategories);	 
		foreach ($Maincategories as $Maincat) { 
		if($Maincat->parent ==0){		
 
			$catlist .= '<option value="'.$Maincat->term_id.'">';
			$catlist .= $Maincat->cat_name;
			$catlist .= " (".$Maincat->count.')';
			$catlist .= '</option>';

				$currentcat=get_query_var('cat');
				$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0');
 
				$catcount = count($categories);		
				$i=1;
	
				if(count($categories) > 0){
				$catlist .="<ul>";
					foreach ($categories as $cat) {		
			
						$catlist .= '<option value="'.$cat->term_id.'"> -- ';
						$catlist .= $cat->cat_name."";
						$catlist .= '</option>';						 
						$i++;		
					}
				 
				}
			
		} 
 }
print $catlist;
 ?>
</select><br />

 

<div id="PPT-cattext"></div>


			</td>
		</tr>


 
 


<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>

</table> 
 
</div>
<div id="premiumpress_tab5" class="content" style="padding-left:20px;padding-top:0px;">
 
 
<?php  $searchData->presets_form(); ?><br />
 
<p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p>
</div>
<div id="premiumpress_tab6" class="content">

<table class="maintable" style="background:white;">


 

<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">
		<p><b># Results Per Page</b></p>
     

	<input name="adminArray[posts_per_page]" type="text" class="txt" style="width: 240px;font-size:14px;" value="<?php $pp = get_option("posts_per_page"); if($pp == ""){ echo "12"; }else{  echo $pp; } ?>" /><br />
    
 <p class="ppnote">Here you choose how many listings to be displayed per page. Enter a numeric value such as 10,20,30</p>
      
    
			 
           		
			 

</td><td class="forminp">
 
 
		<p><b>Display Order</b></p>
<select name="adminArray[display_defaultorder]" style="width: 240px;  font-size:14px;">
<?php /* ============================ ================================= */ if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress"){  ?>
<option value="meta_value&meta_key=price*desc" <?php if(get_option("display_defaultorder") =="meta_value&meta_key=price*desc"){ print "selected";} ?>>Price (Highest First)</option>
<option value="meta_value&meta_key=price*asc" <?php if(get_option("display_defaultorder") =="meta_value&meta_key=price*asc"){ print "selected";} ?>>Price (Lowest First) </option>
<?php } ?>

<option value="meta_value&meta_key=featured*desc" <?php if(get_option("display_defaultorder") =="meta_value&meta_key=featured*desc"){ print "selected";} ?>>Featured Listings (First)</option>
<option value="meta_value&meta_key=featured*asc" <?php if(get_option("display_defaultorder") =="meta_value&meta_key=featured*asc"){ print "selected";} ?>>Featured Listings (Last) </option>

<option value="author*asc" <?php if(get_option("display_defaultorder") =="author*asc"){ print "selected";} ?>>Author (A-z) </option>
<option value="author*desc" <?php if(get_option("display_defaultorder") =="author*desc"){ print "selected";} ?>>Author (Z-a)</option>

<option value="date*asc" <?php if(get_option("display_defaultorder") =="date*asc"){ print "selected";} ?>>Date (Newest Last)</option>
<option value="date*desc" <?php if(get_option("display_defaultorder") =="date*desc"){ print "selected";} ?>>Date (Newest First)</option>

<option value="title*asc" <?php if(get_option("display_defaultorder") =="title*asc"){ print "selected";} ?>>Product Title (A-z)</option>
<option value="title*desc" <?php if(get_option("display_defaultorder") =="title*desc"){ print "selected";} ?>>Product Title (Z-a)</option>

<option value="modified*asc" <?php if(get_option("display_defaultorder") =="modified*asc"){ print "selected";} ?>>Date Modified (Newest Last)</option>
<option value="modified*desc" <?php if(get_option("display_defaultorder") =="modified*desc"){ print "selected";} ?>>Date Modified (Newest First)</option>
  

<option value="ID*asc" <?php if(get_option("display_defaultorder") =="ID*asc"){ print "selected";} ?>>Product ID (0 - 1)</option>
<option value="ID*desc" <?php if(get_option("display_defaultorder") =="ID*desc"){ print "selected";} ?>>Product ID (1 - 0)</option>

<option value="rand*asc" <?php if(get_option("display_defaultorder") =="rand*asc"){ print "selected";} ?>>Random Display</option>
 </select>
 
<p class="ppnote">Here you choose the default display order of website listings.</p>
  
  

   
</td></tr>
<?php /* ============================ ================================= */ ?>





<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp">


<p><b>Default Display Currency</b></p>
     
<input type="text" name="adminArray[currency_code]" style="width: 240px; font-size:14px;;" value="<?php echo get_option("currency_code"); ?>">

<p class="ppnote">Here you enter the currency symbol for your website. Eg. $ &#8364;, &pound; etc</p>


<p class="ppnote1"><b>Note.</b> Payment currency codes are setup separately within the <a href="admin.php?page=payments" style="text-decoration:underline;">payments area here</a></p>

 


</td><td class="forminp">
 
<p><b>Currency Symbol Position</b></p>
			
			<select name="adminArray[display_currency_position]" style="width: 240px;  font-size:14px;">
				<option value="l" <?php if(get_option("display_currency_position") =="l"){ print "selected";} ?>>Left (eg. $100)</option>
				<option value="r" <?php if(get_option("display_currency_position") =="r"){ print "selected";} ?>>Right (eg. 100$)</option>				
			</select>
            
<p class="ppnote">Here you choose which side the currency code is displayed.</p>
            
            
<p><b>Display Price Format (Round Up/Down)</b></p>
			
			<select name="adminArray[display_currency_format]" style="width: 240px;  font-size:14px;">
				<option value="0" <?php if(get_option("display_currency_format") =="0"){ print "selected";} ?>>0 (eg. $100)</option>
				<option value="1" <?php if(get_option("display_currency_format") =="1"){ print "selected";} ?>>1 (eg. $100.0)</option>	
                <option value="2" <?php if(get_option("display_currency_format") =="2"){ print "selected";} ?>>2 (eg. $100.00) Recommended</option>
                 <option value="3" <?php if(get_option("display_currency_format") =="3"){ print "selected";} ?>>3 (eg. $100.000)</option>
                 <option value="4" <?php if(get_option("display_currency_format") =="4"){ print "selected";} ?>>4 (eg. $100.0000)</option>		
			</select>
            
<p class="ppnote">Here you choose how many digets at the end of price tags.</p>
            
   			 
   
</td></tr>
<?php /* ============================ ================================= */ ?>





<tr class="mainrow"><td></td><td class="forminp">

	<p><b>Automatic Listing Removal</b></p>
			
			<select name="adminArray[post_prun]" style="width: 240px;  font-size:14px;">

				<option value="no" <?php if(get_option("post_prun") == "no"){ print "selected"; } ?>>Disable</option>

				<option value="yes" <?php if(get_option("post_prun") == "yes"){ print "selected"; } ?>>Enable</option>

			</select><br />
            
<p class="ppnote">Here you can enable listings to be automatically move/delete listings after X number of days. </p>
 
            
 
            
            <br />

			<?php if(get_option("post_prun") == "yes"){  ?>


            
            <p><b>What should we do with the listing?</b></p>
            
            <?php $vv1 = get_option('prun_status'); ?>
            <select name="adminArray[prun_status]" style="width: 240px;  font-size:14px;">

				<option value="draft" <?php if($vv1 =="draft"){ echo "selected"; } ?> >Set to draft</option>
                <option value="delete" <?php if($vv1 =="delete"){ echo "selected"; } ?>>Delete</option>
                
                <?php 
				
				if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "directorypress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "couponpress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "classifiedstheme"){
				
				$packdata = get_option("packages");  ?>
                
                <?php if(isset($packdata[1]['enable']) && $packdata[1]['enable'] ==1){ ?><option value="pak1" <?php if($vv1 =="pak1"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[1]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[2]['enable']) && $packdata[2]['enable'] ==1){ ?><option value="pak2" <?php if($vv1 =="pak2"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[2]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[3]['enable']) && $packdata[3]['enable'] ==1){ ?><option value="pak3" <?php if($vv1 =="pak3"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[3]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[4]['enable']) && $packdata[4]['enable'] ==1){ ?><option value="pak4" <?php if($vv1 =="pak4"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[4]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[5]['enable']) && $packdata[5]['enable'] ==1){ ?><option value="pak5" <?php if($vv1 =="pak5"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[5]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[6]['enable']) && $packdata[6]['enable'] ==1){ ?><option value="pak6" <?php if($vv1 =="pak6"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[6]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[7]['enable']) && $packdata[7]['enable'] ==1){ ?><option value="pak7" <?php if($vv1 =="pak7"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[7]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[8]['enable']) && $packdata[8]['enable'] ==1){ ?><option value="pak8" <?php if($vv1 =="pak8"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[8]['name']; ?></option><?php } ?>
                
                
<?php
}

		$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
		$Maincatcount = count($Maincategories);				 
		foreach ($Maincategories as $cat) {		
			if($cat->parent ==0){
				print '<option  value="'.$cat->cat_ID.'"';
				if($vv1 == $cat->cat_ID){ print "selected"; }
				print ' >Move to ' . $cat->cat_name."</option>";
			}else{
				print '<option value="'.$cat->cat_ID.'" ';
				if($vv1 == $cat->cat_ID){ print "selected"; }
				print '> -- Move to  ' . $cat->cat_name."</option>";
			} 
		
		}
		
?>     

			</select>	

				<br />
                
            <p><b>After how many days?</b></p> 
			<input name="adminArray[prun_period]" type="text" style="width: 40px;" maxlength="3" value="<?php echo get_option("prun_period"); ?>" /> Days 
            
            <p class="ppnote">Here you enter a numeric value, X days after first published, the above action is then performed </p>
 
           

		 

            <?php } ?>
 

</td><td class="forminp" valign="top">
 

		<p><b>Automatic Listing Expiry</b></p>
			
			
			<select name="adminArray[feature_expiry]" style="width: 240px;  font-size:14px;" >
				<option value="yes" <?php if(get_option("feature_expiry") =="yes"){ print "selected";} ?>>Enable </option>
				<option value="no" <?php if(get_option("feature_expiry") =="no" || get_option("feature_expiry") ==""){ print "selected";} ?>>Disable</option>
			</select>
            
                          <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>What is listing expiry?</b><br /><br />When adding/editing a listing you can choose how long the listing will stay on your website before it expires, the options here allow you to determine what happens to the listing when it expires. <br><br> For example if you entered the value of 10 as an expiry date for any listng then the listing will expire after 10 days and here you could choose to delete all expired listing which means after 10 days it will be deleted from your website. &quot;);"/>    
          
            
 <p class="ppnote">Here you choose to automatically remove listings from your website when they expiry.</p>
 
  
 
<?php if(get_option("feature_expiry") =="yes"){ ?>	 
 
 <p class="ppnote1">Only listings with a valid expiry date will follow the rules set here.</p>
 
 
 		<p><b>What happens to expired posts?</b></p>
			
			<?php $vv1 = get_option("feature_expiry_do"); ?>
			<select name="adminArray[feature_expiry_do]" style="width: 240px;  font-size:14px;" <?php if(get_option("feature_expiry") !="yes"){ print 'disabled';} ?>>
				<option value="draft" <?php if($vv1 =="draft"){ echo "selected"; } ?> >Set to draft</option>
                <option value="delete" <?php if($vv1 =="delete"){ echo "selected"; } ?>>Delete</option>
                
                <?php 
				
				if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "directorypress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "couponpress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "classifiedstheme"){
				
				$packdata = get_option("packages");  ?>
                
                <?php if(isset($packdata[1]['enable']) && $packdata[1]['enable'] ==1){ ?><option value="pak1" <?php if($vv1 =="pak1"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[1]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[2]['enable']) && $packdata[2]['enable'] ==1){ ?><option value="pak2" <?php if($vv1 =="pak2"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[2]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[3]['enable']) && $packdata[3]['enable'] ==1){ ?><option value="pak3" <?php if($vv1 =="pak3"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[3]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[4]['enable']) && $packdata[4]['enable'] ==1){ ?><option value="pak4" <?php if($vv1 =="pak4"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[4]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[5]['enable']) && $packdata[5]['enable'] ==1){ ?><option value="pak5" <?php if($vv1 =="pak5"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[5]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[6]['enable']) && $packdata[6]['enable'] ==1){ ?><option value="pak6" <?php if($vv1 =="pak6"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[6]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[7]['enable']) && $packdata[7]['enable'] ==1){ ?><option value="pak7" <?php if($vv1 =="pak7"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[7]['name']; ?></option><?php } ?>
                <?php if(isset($packdata[8]['enable']) && $packdata[8]['enable'] ==1){ ?><option value="pak8" <?php if($vv1 =="pak8"){ echo "selected"; } ?>>Down Grade to <?php echo $packdata[8]['name']; ?></option><?php } ?>
                
                
<?php
}

		$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
		$Maincatcount = count($Maincategories);				 
		foreach ($Maincategories as $cat) {		
			if($cat->parent ==0){
				print '<option  value="'.$cat->cat_ID.'"';
				if($vv1 == $cat->cat_ID){ print "selected"; }
				print ' >Move to ' . $cat->cat_name."</option>";
			}else{
				print '<option value="'.$cat->cat_ID.'" ';
				if($vv1 == $cat->cat_ID){ print "selected"; }
				print '> -- Move to  ' . $cat->cat_name."</option>";
			} 
		
		}
		
?>                
                
			</select><br />
			<small>Here you can decide what to do with any expired listings.</small> 

<?php } ?>
   
</td>
</tr>
<?php /* ============================ ================================= */ ?>






 


<?php /* ============================ 1 ================================= */ ?>
<tr class="mainrow"><td></td><td class="forminp" valign="top">


		<p><b>Use `nofollow` for links</b></p>
			
			
			<select name="adminArray[display_nofollow]" style="width: 240px;  font-size:14px;">
				<option value="yes" <?php if(get_option("display_nofollow") =="yes"){ print "selected";} ?>>Yes</option>
				<option value="no" <?php if(get_option("display_nofollow") =="no"){ print "selected";} ?>>No</option>
			</select>
            
                        <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>What is nofollow?</b><br /><br />nofollow is a value that can be assigned to the rel attribute of an HTML element to instruct some search engines that a hyperlink should not influence the link target's ranking in the search engine's index. <br/><br/> It is intended to reduce the effectiveness of certain types of search engine spam, thereby improving the quality of search engine results and preventing spamdexing from occurring.&quot;);"/>    


<p class="ppnote">Here you choose to insert a rel="nofollow" tag into external links where possible</p>
 
 
 

</td><td class="forminp">
 
 
 		<p><b>Enable Link Cloaking</b></p>
     
	<select name="adminArray[display_linkcloak]" style="width: 240px;  font-size:14px;" >
				<option value="yes" <?php if(get_option("display_linkcloak") =="yes"){ print "selected";} ?>>Enable </option>
				<option value="no" <?php if(get_option("display_linkcloak") =="no" || get_option("display_linkcloak") ==""){ print "selected";} ?>>Disable</option>
			</select>
            
            
                         <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>What is link cloaking?</b><br /><br />Link cloaking simply disguises an external link such as http://google.com with an internal link such as http://mywebsite.com/link=123 a so that it does not display the actual URL to your website visitors. It is most often applied with affiliate links which can be long or look ugly from an SEO stand point.<br><br><b>Should i enable this option?</b><br><br>Unless you are very familiar with link cloaking it is <b>not recommended</b> to use this option, it has only been added to provide resources for those who wish to use it.<br><br><b>How does it work?</b><br><br>The link cloaking file path you enter below will replace the external link, the post ID is passed to the file and the system will then auto redirect the user to the link. a&quot;);"/>    
           
            
<p class="ppnote">Here you can enable/display external link cloaking which hides affiliate links for better SEO.</p>
 
			 
 

 

   
</td></tr>
<?php /* ============================ ================================= */ ?>


 



    
    
 <td colspan="3" style="background:#70cf41 !important;">
<?php


 


$lv = explode("**",get_option("listbox_custom_string"));
?>
<div >

<table width="650"  border="0" style=" border:5px solid #45921f;">

  
 <td colspan="4">
<img src="<?php echo $GLOBALS['template_url']; ?>/images/help/b2.png" style="float:right;">  
 		<p><b><input type="checkbox" class="checkbox" name="listbox_custom" value="1" <?php if(get_option("listbox_custom") =="1"){ print "checked";} ?> /> Enable Display</b><br />
        
        
        
 		<small>Check the box to display the 'order by' list box on your website.</small></p>
 		<br />
			 
           
     <p><b>List box Caption</b></p>       
	 <input name="adminArray[listbox_custom_title]" type="text" style="width: 400px;" value="<?php echo get_option("listbox_custom_title"); ?>" /><br />
			<small>This will be the title caption for your new list box.</small>
 
 </td>

  <tr>
    <td width="51%" height="47"><strong>List Item Caption <br />
    <small>(e.g: Order By Hits ASC)</small></strong></td>
    <td width="13%"><div align="center"><strong>Order by Field </div></td>
    <td width="24%"><div align="center"><strong>Display Order </strong></div></td>
    <td width="24%"> <div align="center"><strong>Extra <br />
    <small>(advanced use only. e.g &amp;meta_value=yes)</small></strong></div></td>
    </tr>
    
    <?php 
	
	$carray = array('a','b','c','d','e','f','g','h','i','j','k','l');
	$C1 = 0; $C2=0; while($c < 12){ ?>
  <tr>
    <td><input type="text" name="<?php echo $carray[$C2]; ?>1" style="width:250px;" value="<?php echo $lv[$C1++]; ?>"></td>
    <td><select name="<?php echo $carray[$C2]; ?>2"><?php echo $PPT->GetCustomFieldList($lv[$C1++]); ?></select></td>
    <td><select name="<?php echo $carray[$C2]; ?>3"><option value="asc">Ascending Order</option><option value="desc" <?php if($lv[$C1++] == "desc"){ print "selected='selected'";} ?>>Descending Order</option></select></td>
    <td><input name="<?php echo $carray[$C2]; ?>4" type="text" size="20" value="<?php echo $lv[$C1++]; ?>"></td>
    </tr>
    
    <?php $C2++; $c ++; } ?>
    
 
 </table>
 </div>
 
 <p>&nbsp;</p></td>




 

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>
</table>
</div>            
					 
                        
</div>
</div>
<div class="clearfix"></div>  





 
</form>
 

 
 
 <?php } ?>