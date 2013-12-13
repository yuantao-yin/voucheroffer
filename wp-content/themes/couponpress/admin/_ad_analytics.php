
<?php if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; }  PremiumPress_Header();  ?>


<div class="clearfix"></div> 

<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_analytics.png" align="middle"> Analytics</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a>							 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">Google Analytics</a></li>
	<li><a rel="premiumpress_tab2" href="#">Webmaster Tools</a></li>
    <li><a rel="premiumpress_tab3" href="#">Google Adsense</a></li>
	<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress" ){ ?><li><a rel="premiumpress_tab4" href="#">Google Maps</a></li><?php } ?>
</ul>
</div>


<form method="post" target="_self">
<input name="submitted" type="hidden" value="yes" />

<div id="premiumpress_tab1" class="content">

<table class="maintable" style="background:white;">
	<tr class="mainrow">
		<td class="titledesc" style="font-weight:normal;" valign="top"><br /><b>Google Analytics Code</b> <br /><br />
			<small>Copy/paste your google analytics site code in the box opposite.</small>
		</td><td class="forminp">
			 
			<textarea name="adminArray[analytics_code]" type="text" class="txt" class="txt" style="width:550px;height:100px;"><?php echo stripslashes(get_option("analytics_code")); ?></textarea>
 
			<p class="ppnote" style="width:600px;">Google analytics is a free web analytics tool from Google that allows you to track your website visitors and statistics. </p>
<p class="ppnote1" style="width:600px;">If you don't already have an account, its strongly recommended you signup and start learning more about where your website visitors come from. <a href="http://www.google.com/analytics/" target="_blank">http://www.google.com/analytics/</a></p>

		</td>
	</tr>


 	<tr class="mainrow">
		<td class="titledesc">Enable Event Tracking</td>
		<td class="forminp">
			
			<select name="adminArray[analytics_tracking]" style="width: 150px;">
				<option value="yes">Allow Tracking</option>
				<option value="no">Disable</option>
			</select><br />
			<p class="ppnote" style="width:600px;">This will allow Google analytics to track your website visitor click history to see which products/items are most popular. <b>This is strongly recommended for all website owners.</b> </p> 
			
		</td>
	</tr>

<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></td>
</tr>

</table>
</div>
<div id="premiumpress_tab2" class="content">
<table class="maintable" style="background:white;">
	<tr class="mainrow">
		<td class="titledesc" style="font-weight:normal;" valign="top"><br /><b>Verification Code</b> <br /><br />

<p><small>To verify your website enter your meta string opposite.</small></p>
		</td><td class="forminp">
			 
			<textarea name="adminArray[google_webmaster_code]" type="text" class="txt"  style="width:500px;height:40px;"><?php echo stripslashes(get_option("google_webmaster_code")); ?></textarea>
 
			<br />
<p class="ppnote" style="width:600px;">Google Webmaster Tools provides you with detailed reports about your pages' visibility on Google.</p>
			

<p class="ppnote1" style="width:600px;">If you don't have an account with Google Webmaster Tools you can signup here: <a href="http://www.google.com/webmasters/tools/" target="_blank">http://www.google.com/webmasters/tools/</a></p>


		</td>
	</tr>

<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></td>
</tr>

</table>
</div>
<div id="premiumpress_tab3" class="content">
<table class="maintable">
	<tr class="mainrow">
		<td class="titledesc" valign="top" style="font-weight:normal;"><br />		
		<b>Adsense Tracking Code</b><br />
		<p><small>Copy/paste your Adsense javascript code opposite.</small></p>
		</td><td class="forminp">
			 
			<textarea name="adminArray[google_adsensetracking_code]" type="text" class="txt" class="txt" style="width:500px;height:80px;"><?php echo stripslashes(get_option("google_adsensetracking_code")); ?></textarea>
 
			<br />
			 
<p class="ppnote" style="width:600px;">Google AdSense is a free program that enables website publishers of all sizes to display relevant Google ads and earn.</p>
<p class="ppnote1" style="width:600px;">If you don't have a Google adsense account you can create one here: <a href="https://www.google.com/adsense/" target="_blank">https://www.google.com/adsense/</a></p>

		</td>
	</tr>

<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></td>
</tr>

</table>
</div>
<div id="premiumpress_tab4" class="content">
 
<table class="maintable" style="background:white;">	

	<tr class="mainrow">
		<td class="titledesc">Google Map API Key</td>

		<td class="forminp">
			<input name="adminArray[google_maps_api]" type="text" class="txt" style="width: 450px;" value="<?php echo get_option("google_maps_api"); ?>" /><br />
			<small>If you don't have a Google maps API key you can get on here: <a href="http://code.google.com/apis/maps/signup.html" target="_blank">http://code.google.com/apis/maps/signup.html</a></small>
		</td>
	</tr> 


<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></p></td>
</tr>


</table>
</div>
<div id="premiumpress_tab5" class="content">tab 5</div>
<div id="premiumpress_tab6" class="content">tab 6</div>            
					 
</form>
                      
</div>
</div>
<div class="clearfix"></div>   