<?php 

if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 

global $PPT;
PremiumPress_Header();  ?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_advertising.png" align="middle"> Advertising</h3>		 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a>						 
<ul>
	<li><a rel="premiumpress_tab1" href="#" <?php if(!isset($_POST['ad_zone'])){ ?>class="active"<?php } ?>>Header Banner</a></li>
	<li><a rel="premiumpress_tab2" href="#">Footer Banner</a></li>
    <li><a rel="premiumpress_tab3" href="#">Left Banner</a></li>
 	<li><a rel="premiumpress_tab4" href="#">Right Banner</a></li>
	<li><a rel="premiumpress_tab5" href="#" <?php if(isset($_POST['ad_zone'])){ ?>class="active"<?php } ?>>Category</a></li>
</ul>
</div>
<form method="post"  target="_self" <?php if($GLOBALS['sf'] ==1){ ?>onsubmit="UpgradeVIP();return false;"<?php } ?>>
<input name="submitted" type="hidden" value="yes" />
<input type="hidden" name="advertise" value="1">
<div id="premiumpress_tab1" class="content">
<table class="maintable" style="background:white;">  
	<tr class="mainrow">
		<td class="titledesc">Enable Header Ad Spot</td>
		<td class="forminp"><input type="checkbox" class="checkbox" name="advertising_top_checkbox" value="1" <?php if(get_option("advertising_top_checkbox") =="1"){ print "checked";} ?> />
		</td>
	</tr>
	<tr class="mainrow">
		<td class="titledesc" valign="top" style="font-weight:normal;"><br /><b>Ad Code Block</b> <p><small>Here you can copy/paste your Google Adsense code or HTML banner code.</small></p> </td>
		<td class="forminp"><textarea name="adminArray[advertising_top_adsense]" rows="11" cols="93"><?php echo stripslashes(get_option("advertising_top_adsense")); ?></textarea><br />
		<small>Paste in your <a target="_blank" href="http://www.google.com/adsense">Google AdSense</a> or HTML code here. This will show up in the header of your site.</small>
		</td>
	</tr>
	<tr class="mainrow">
		<td colspan="2">
		<div class="msg msg-info"><p>The banner options <b>below</b> are only if you do not wish to copy/paste banner code above. The options below are for single banner display only and for those who are less experience with HTML.</p></div>
		</td>		 
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Clickable Link</td>
		<td class="forminp">
			<input name="adminArray[advertising_top_url]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_top_url"); ?>" /><br />
			<small>Paste the full http://. link to you would like people who click the banner to go to. <br /> (e.g. http://www.google.com)</small>
		</td>
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Banner Image</td>
		<td class="forminp">
			<input name="adminArray[advertising_top_dest]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_top_dest"); ?>" /><br />
			<small>Paste the full http://. link to your banner image. <br /> (e.g. http://www.yoursite.com/banner.jpg)</small>
		</td>
	</tr>
<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="Save Banner Settings" style="color:white;" /></td>
</tr>
</table>

</div>
<div id="premiumpress_tab2" class="content">
<table class="maintable" style="background:white;">  
	<tr class="mainrow">
		<td class="titledesc">Enable Footer Ad Spot</td>
		<td class="forminp"><input type="checkbox" class="checkbox" name="advertising_footer_checkbox" value="1" <?php if(get_option("advertising_footer_checkbox") =="1"){ print "checked";} ?> />
		</td>
	</tr>
	<tr class="mainrow">
		<td class="titledesc" valign="top" style="font-weight:normal;"><br /><b>Ad Code Block</b> <p><small>Here you can copy/paste your Google Adsense code or HTML banner code.</small></p> </td>
		<td class="forminp"><textarea name="adminArray[advertising_footer_adsense]" rows="11" cols="93"><?php echo stripslashes(get_option("advertising_footer_adsense")); ?></textarea><br />
		<small>Paste in your <a target="_blank" href="http://www.google.com/adsense">Google AdSense</a> or HTML code here. This will show up in the header of your site.</small>
		</td>
	</tr>
	<tr class="mainrow">
		<td colspan="2">
		<div class="msg msg-info"><p>The banner options <b>below</b> are only if you do not wish to copy/paste banner code above. The options below are for single banner display only and for those who are less experience with HTML.</p></div>
		</td>		 
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Clickable Link</td>
		<td class="forminp">
			<input name="adminArray[advertising_footer_url]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_footer_url"); ?>" /><br />
			<small>Paste the full http://. link to you would like people who click the banner to go to. <br /> (e.g. http://www.google.com)</small>
		</td>
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Banner Image</td>
		<td class="forminp">
			<input name="adminArray[advertising_footer_dest]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_footer_dest"); ?>" /><br />
			<small>Paste the full http://. link to your banner image. <br /> (e.g. http://www.yoursite.com/banner.jpg)</small>
		</td>
	</tr>
<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="Save Banner Settings" style="color:white;" /></td>
</tr>
</table>
</div>


<div id="premiumpress_tab3" class="content">
<table class="maintable" style="background:white;">  
	<tr class="mainrow">
		<td class="titledesc">Enable Left Ad Spot</td>
		<td class="forminp"><input type="checkbox" class="checkbox" name="advertising_left_checkbox" value="1" <?php if(get_option("advertising_left_checkbox") =="1"){ print "checked";} ?> />
		</td>
	</tr>
	<tr class="mainrow">
		<td class="titledesc" valign="top" style="font-weight:normal;"><br /><b>Ad Code Block</b> <p><small>Here you can copy/paste your Google Adsense code or HTML banner code.</small></p> </td>
		<td class="forminp"><textarea name="adminArray[advertising_left_adsense]" rows="11" cols="93"><?php echo stripslashes(get_option("advertising_left_adsense")); ?></textarea><br />
		<small>Paste in your <a target="_blank" href="http://www.google.com/adsense">Google AdSense</a> or HTML code here. This will show up in the header of your site.</small>
		</td>
	</tr>
	<tr class="mainrow">
		<td colspan="2">
		<div class="msg msg-info"><p>The banner options <b>below</b> are only if you do not wish to copy/paste banner code above. The options below are for single banner display only and for those who are less experience with HTML.</p></div>
		</td>		 
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Clickable Link</td>
		<td class="forminp">
			<input name="adminArray[advertising_left_url]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_left_url"); ?>" /><br />
			<small>Paste the full http://. link to you would like people who click the banner to go to. <br /> (e.g. http://www.google.com)</small>
		</td>
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Banner Image</td>
		<td class="forminp">
			<input name="adminArray[advertising_left_dest]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_left_dest"); ?>" /><br />
			<small>Paste the full http://. link to your banner image. <br /> (e.g. http://www.yoursite.com/banner.jpg)</small>
		</td>
	</tr>
<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="Save Banner Settings" style="color:white;" /></td>
</tr>
</table>
</div>

<div id="premiumpress_tab4" class="content">
<table class="maintable" style="background:white;">  
	<tr class="mainrow">
		<td class="titledesc">Enable Right Ad Spot</td>
		<td class="forminp"><input type="checkbox" class="checkbox" name="advertising_right_checkbox" value="1" <?php if(get_option("advertising_right_checkbox") =="1"){ print "checked";} ?> />
		</td>
	</tr>
	<tr class="mainrow">
		<td class="titledesc" valign="top" style="font-weight:normal;"><br /><b>Ad Code Block</b> <p><small>Here you can copy/paste your Google Adsense code or HTML banner code.</small></p> </td>
		<td class="forminp"><textarea name="adminArray[advertising_right_adsense]" rows="11" cols="93"><?php echo stripslashes(get_option("advertising_right_adsense")); ?></textarea><br />
		<small>Paste in your <a target="_blank" href="http://www.google.com/adsense">Google AdSense</a> or HTML code here. This will show up in the header of your site.</small>
		</td>
	</tr>
	<tr class="mainrow">
		<td colspan="2">
		<div class="msg msg-info"><p>The banner options <b>below</b> are only if you do not wish to copy/paste banner code above. The options below are for single banner display only and for those who are less experience with HTML.</p></div>
		</td>		 
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Clickable Link</td>
		<td class="forminp">
			<input name="adminArray[advertising_right_url]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_right_url"); ?>" /><br />
			<small>Paste the full http://. link to you would like people who click the banner to go to. <br /> (e.g. http://www.google.com)</small>
		</td>
	</tr>	
	<tr class="mainrow">
		<td class="titledesc">Banner Image</td>
		<td class="forminp">
			<input name="adminArray[advertising_right_dest]" type="text" class="txt" style="width: 600px;" value="<?php echo get_option("advertising_right_dest"); ?>" /><br />
			<small>Paste the full http://. link to your banner image. <br /> (e.g. http://www.yoursite.com/banner.jpg)</small>
		</td>
	</tr>
<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="Save Banner Settings" style="color:white;" /></td>
</tr>
</table></div>


</form>

<form method="post" name="ad" target="_self" <?php if($GLOBALS['sf'] ==1){ ?>onsubmit="UpgradeVIP();return false;"<?php } ?>>
<?php if(!isset($_POST['ad_zone']) ){ ?>
<input type="hidden" name="ad_zone" value="1">
<?php }else{ ?>
<input name="submitted" type="hidden" value="yes" />
<?php } ?>
<div id="premiumpress_tab5" class="content">
 
<table class="maintable" style="background:white;" >

	 

<?php if(!isset($_POST['ad_zone']) ){ ?>
	<tr class="mainrow">
		<td class="titledesc">Select Category</td>
		<td class="forminp">

 	<select name='cat' onChange="document.ad.submit();">
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
			
						$catlist .= '<option value="'.$cat->term_id.'"> ---> ';
						$catlist .= $cat->cat_name;
						$catlist .= '</option>';						 
						$i++;		
					}
				 
				}
			
		} 
 }
print $catlist;
 ?>
</select>

 
			</td>
		</tr>
	 
<?php }elseif(isset($_POST['ad_zone']) && is_numeric($_POST['ad_zone']) ){ ?>
<input name="submitted" type="hidden" value="yes" />
	<tr class="mainrow">
		<td class="titledesc">Banner Code</td>
		<td class="forminp">
		<textarea name="adminArray[advertising_zone_<?php echo $_POST['cat']; ?>]" rows="11" cols="93"><?php echo $PPT->BannerZone($_POST['cat']); ?></textarea><br />
		<small>Paste in your advertising code for this category.</small>
		</td>
	</tr>

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p></td>
</tr>

<?php } ?>


</table>
</div>
<div id="premiumpress_tab6" class="content"></div>            
</form>				 
                        
</div>
</div>
<div class="clearfix"></div>

 