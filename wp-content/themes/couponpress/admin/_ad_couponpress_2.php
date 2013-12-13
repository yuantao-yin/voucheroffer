
<?php PremiumPress_Header(); ?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_submission.png" align="middle"> Coupon Submission Setup</h3>							 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">Setup Options</a></li>
	<li><a rel="premiumpress_tab2" href="#">Packages</a></li>
  
    <!--<li><a rel="premiumpress_tab4" href="#">Page Text</a></li> -->
    <li><a rel="premiumpress_tab5" href="#">Custom Fields</a></li>

</ul>
</div>

<style>
select { border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px; }
</style>

<form method="post" name="couponpress" target="_self">
<input name="submitted" type="hidden" value="yes" />
<input name="submit" type="hidden" value="1" />
<input name="package1" type="hidden" value="yes" />
<input name="custom" type="hidden" value="1" />
<div id="premiumpress_tab1" class="content">
<table class="maintable">
 
	
 
 
 



	<tr class="mainrow"><td></td><td class="forminp">
		<p><b>Must be logged in to submit?</b></p>
		<select name="adminArray[tpl_add_mustlogin]" style="width: 240px;  font-size:14px;">
           <option value="yes" <?php if(get_option("tpl_add_mustlogin") =="yes"){ print "selected";} ?> >Yes</option>
           <option value="no" <?php if(get_option("tpl_add_mustlogin") =="no"){ print "selected";} ?>>No</option>
           </select>

			<br />
			<small>This will stop non registered users from submitting listings.</small>
  		</td><td class="forminp" valign="top">
        
        
        
        <p><b>Default Submission Status</b></p>
		<select name="adminArray[display_listing_status]" style="width: 240px;  font-size:14px;">
				<option value="publish" <?php if(get_option("display_listing_status") =="publish"){ print "selected";} ?> >Approved</option>
                <option value="pending" <?php if(get_option("display_listing_status") =="pending"){ print "selected";} ?>>Pending Review</option>
				<option value="draft" <?php if(get_option("display_listing_status") =="draft"){ print "selected";} ?>>Unapproved</option>
			</select>
			
			<br />
			<small>Select the default status of the link when its submitted.</small>
        
		</td>
	</tr>
    
    

    
    	<tr class="mainrow"><td></td><td class="forminp" valign="top">
		<p><b>Display Country Options</b></p>
<select name="adminArray[display_country]" style="width: 240px;  font-size:14px;">
<option value="yes" <?php if(get_option("display_country") =="yes"){ print "selected";} ?> >Yes</option>
<option value="no" <?php if(get_option("display_country") =="no"){ print "selected";} ?>>No</option>
</select>
<br />
<small>Display the country selection box on the submit page.</small>
  		</td><td class="forminp">
        
        
   		<p><b>Allow file uploads</b></p>
	<select name="adminArray[display_fileupload]" style="width: 240px;  font-size:14px;">
				<option value="yes" <?php if(get_option("display_fileupload") =="yes"){ print "selected";} ?> >Enable</option>
				<option value="no" <?php if(get_option("display_fileupload") =="no"){ print "selected";} ?>>Disable</option>
			</select>
            
            <?php if(get_option("display_fileupload") =="yes"){  ?>
            <p>Maxium file uploads per post</p>
            <input type="text" name="adminArray[display_fileupload_max]" value="<?php echo get_option("display_fileupload_max"); ?>" style="width: 60px;  font-size:14px;" class="txt"> <br />

            <?php } ?>
			
			<br />
			<small>Enable/Disable file uploads.</small>     
 
        
		</td>
	</tr>
    
    
    
    
        	<tr class="mainrow"><td></td><td class="forminp">
            
            
     
        	<p><b> Price Per Category</b></p>
            
 	<select name='cat' onChange="CatPriceBox(this.value);" style="width: 240px;  font-size:14px;">
	<option value='-1'>Select One</option>
	 <?php 

		$CCode = get_option("currency_code");
		$catlist="";
 		$Maincategories = get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');		
		$Maincatcount = count($Maincategories);	 
		foreach ($Maincategories as $Maincat) { 
		if($Maincat->parent ==0){		
 
			$price  = get_option('CatPrice_'.$Maincat->cat_ID);
			if($price ==""){ $price=0; }
			$catlist .= '<option value="'.$Maincat->cat_ID.'">';
			$catlist .= $Maincat->cat_name;
			$catlist .= " (".$CCode."".$price.')';
			$catlist .= '</option>';

				$currentcat=get_query_var('cat');
				$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0');
 
				$catcount = count($categories);		
				$i=1;
	
				if(count($categories) > 0){
				$catlist .="<ul>";
					foreach ($categories as $cat) {	
						
						$price  = get_option('CatPrice_'.$cat->cat_ID);
						if($price ==""){ $price=0; }
			
						$catlist .= '<option value="'.$cat->cat_ID.'"> ---> ';
						$catlist .= $cat->cat_name;
						$catlist .= " (".$CCode."".$price.')';
						$catlist .= '</option>';						 
						$i++;		
					}
				 
				}
			
		} 
 }
print $catlist;
 ?>
</select>

<div id="PPT-catpricebox"></div>

	 <small>Here you can setup additional prices per category.</small>
     
            

  		</td><td class="forminp" valign="top">
        
        
    
        
		</td>
	</tr>

 
 
    
    

 

<td colspan="3"><p><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></p></td>
</tr>

</table>
</div>



<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" />


<div id="premiumpress_tab2" class="content">

<table class="maintable">

<tr class="mainrow">

	<td class="forminp" valign="top">
      

<input type="hidden" value="" name="imgIdblock" id="imgIdblock" />
<script>

function ChangeImgBlock(divname){
document.getElementById("imgIdblock").value = divname;
}

jQuery(document).ready(function() {

jQuery('#upload_h1').click(function() {
 ChangeImgBlock('icon1');
 formfield = jQuery('#icon1').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});
 
jQuery('#upload_h2').click(function() {
 ChangeImgBlock('icon2');
 formfield = jQuery('#icon2').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
}); 

jQuery('#upload_h3').click(function() {
 ChangeImgBlock('icon3');
 formfield = jQuery('#icon3').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

jQuery('#upload_h4').click(function() {
 ChangeImgBlock('icon4');
 formfield = jQuery('#icon4').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

jQuery('#upload_h5').click(function() {
 ChangeImgBlock('icon5');
 formfield = jQuery('#icon5').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

jQuery('#upload_h6').click(function() {
 ChangeImgBlock('icon6');
 formfield = jQuery('#icon6').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

jQuery('#upload_h7').click(function() {
 ChangeImgBlock('icon7');
 formfield = jQuery('#icon7').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

jQuery('#upload_h8').click(function() {
 ChangeImgBlock('icon8');
 formfield = jQuery('#icon8').attr('name');
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
<div style="width:300px;"></div>

<?php if(get_option("pak_enabled") ==1){   $packdata = get_option("packages"); ?>


<?php $i=1; while($i < 9){ ?>



<div style="background:#eee; border:1px solid #ddd; padding:10px; font-size:18px; font-weight:bold;">
<a href="javascript:void(0);" onclick="toggleLayer('package<?php echo $i; ?>');">
<img src="<?php echo $GLOBALS['template_url']; ?>/images/add.png" align="middle"> 
<?php if(isset($packdata[$i]['name']) && strlen($packdata[$i]['name']) > 2){ echo strip_tags($packdata[$i]['name']); }else{ echo "Unnamed Package ".$i; } ?></a></div>

<table width="100%"  border="0" style="border:1px solid #ddd; background:#fff;display:none" id="package<?php echo $i; ?>"><tr>
<td  style="background:#fff;">
	<tr class="mainrow">
	  <td class="titledesc" valign="top">On/Off</td>
		<td class="forminp">
			<input type="checkbox" name="package[<?php echo $i; ?>][enable]" value="1" <?php if(isset($packdata[$i]['enable']) && $packdata[$i]['enable'] ==1){ echo "checked"; } ?>><p class="ppnote">Check this box to enable this package to be displayed.</p>
		</td>
	</tr>

	<tr class="mainrow" style="background:#fff;">
	  <td class="titledesc" valign="top">Name</td>
		<td class="forminp">
			<input type="text" name="package[<?php echo $i; ?>][name]" style="width:300px;" value="<?php echo $packdata[$i]['name']; ?>"><p class="ppnote">Give this package a name which will be displayed on the submission page.</p>
		</td>
	</tr>
</td></tr>
	<tr class="mainrow" style="background:#fff;">
	  <td class="titledesc" valign="top">Icon</td>
		<td class="forminp">
			<input type="text" name="package[<?php echo $i; ?>][icon]" id="icon<?php echo $i; ?>" style="width:300px;" value="<?php echo $packdata[$i]['icon']; ?>">
            
            <input onClick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','icon<?php echo $i; ?>');" type="button"   value="View Images"  />

 			<input id="upload_h<?php echo $i; ?>" type="button" size="36" name="upload_h<?php echo $i; ?>" value="Upload Image"  />


            <p class="ppnote">The icon is displayed on the listing page for posts assigned to this package.</p>
		</td>
	</tr>
</td></tr>
<tr><td>

	<tr class="mainrow" style="background:#fff;">
	  <td class="titledesc" valign="top">Price</td>
		<td class="forminp">
			<?php echo get_option("currency_code"); ?> <input name="package[<?php echo $i; ?>][price]" type="text" size="5" value="<?php echo $packdata[$i]['price']; ?>">
            
            <?php if(get_option('gateway_paypal') == "yes"){ ?>
            
            
            <img src="<?php echo $GLOBALS['template_url']; ?>/images/paypals.png" />
            
            <input type="checkbox" name="package[<?php echo $i; ?>][rec]" value="1" <?php if(isset($packdata[$i]['price']) && $packdata[$i]['rec'] ==1){ echo "checked"; } ?>> Recurring? 
            
             
            
            <p class="ppnote">Check the box if you wish this to be a recurring subscription. The package will renew automatically after the days you specify in the field below expire. (Days Before Expiry). <b>Works with Paypal only.</b></p>
            
            <?php } ?>
		</td>
	</tr>

	<tr class="mainrow"  style="background:#fff;">
	  <td class="titledesc"  valign="top">Expiry</td>
		<td class="forminp">
			# <input type="text" name="package[<?php echo $i; ?>][expire]" style="width:50px;" value="<?php echo $packdata[$i]['expire']; ?>"> days (example: 5 days)<p class="ppnote">Give a numeric value for amount of time the listing will display before its automatically removed. Leave blank for never.</p>
		</td>
	</tr>

</td>
  </tr>
  <tr>
    <td>

<div class="msg msg-info"><p>Tick the box next to the items you would like this package to have enabled.</p></div>
<table width="100%"  border="0">
  <tr>
    <td><input type="checkbox" name="package[<?php echo $i; ?>][a1]" value="1" <?php if(isset($packdata[$i]['a1']) && $packdata[$i]['a1'] ==1){ echo "checked"; } ?>> <strong>HTML Description Box </strong><br /><p>This will allow the user to design their listing in HTML. Without this HTML will be stripped from content.</p></td>
    <td><input type="checkbox" name="package[<?php echo $i; ?>][a2]" value="1" <?php if(isset($packdata[$i]['a2']) && $packdata[$i]['a2'] ==1){ echo "checked"; } ?>> <strong>Multi-Categories</strong><br />
    <p>This will allow the user to submit their listing to up to 3 categories.</p></td>
  </tr>
  <tr>
    <td><input type="checkbox" name="package[<?php echo $i; ?>][a3]" value="1" <?php if(isset($packdata[$i]['a3']) && $packdata[$i]['a3'] ==1){ echo "checked"; } ?>> <strong>File Uploads</strong><br /><p>This will allow the user to upload files.</p></td>
    <td><input type="checkbox" name="package[<?php echo $i; ?>][a4]" value="1" <?php if(isset($packdata[$i]['a4']) && $packdata[$i]['a4'] ==1){ echo "checked"; } ?>> <strong>Google Maps</strong><br /><p>This will allow the user to enter their location to be plotted onto a map.</p></td>
  </tr>
 
 
</table>

 <div class="msg msg-info"><p>Tick the box below if you wish to show additional charges for each category.</p></div>

<table width="100%"  border="0">
  <tr>
    <td><input type="checkbox" name="package[<?php echo $i; ?>][pricecats]" value="1" <?php if(isset($packdata[$i]['pricecats']) && $packdata[$i]['pricecats'] ==1){ echo "checked"; } ?>> 
    <strong>Show Price Per Category</strong><br /><p>This will show any additional prices you have set for each category and add the value to the current package price.</p></td> 
 
</table>


</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<br />


<?php $i++; } } ?>




      
      </td>
		<td class="forminp" valign="top">
        

      <p><b> <input type="checkbox" name="pak_enabled" value="1" <?php if(get_option("pak_enabled") ==1){ echo "checked"; } ?>>Enable Packages</b></p>

      <p class="ppnote">Turn on/off website submission packages.</p>
      
      <?php if(get_option("pak_enabled") ==1){ ?>
      
         <p><b>Sidebar Help Text</b></p>
         <p class="ppnote">This is the text that appears on the sidebar whilst users add new listings. You can include HTML.</p>
      
         <textarea name="adminArray[pak_help_text]" cols="" rows="" style="width: 260px; height:130px; font-size:14px;"><?php echo stripslashes(get_option("pak_help_text")); ?></textarea>
         
         <p><b>Bottom Text </b></p>
  <p class="ppnote">This is the text that appears at the bottom of the package selection page. You can include HTML.</p>
          
<textarea name="adminArray[pak_text]" cols="" rows="" style="width: 260px; height:130px; font-size:14px;"><?php echo stripslashes(get_option("pak_text")); ?></textarea>
 
 <?php } ?>
			
		</td>
	</tr>

<tr>

 

 
<td colspan="3">


 





 
</td>
</tr>
<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></p></td>
</tr></table>
</div>




<div id="premiumpress_tab3" class="content">

<table class="maintable">










</table>
</div>
<div id="premiumpress_tab4" class="content">
 
</div>
<div id="premiumpress_tab5" class="content">
<table class="maintable">

<div class="msg msg-info" style="line-height:30px;margin-left:10px;margin-top:20px;"><p>
Custom fields allow you to setup your own input fields displayed when a member submits a new post to your website. <br /><small>Example: What is your Twitter username?</small></p>
</div>
<?php 

$cf1 = get_option("customfielddata"); 

?>




  
  <?php $i=1; while($i < 16){ ?>
  
<div style="width:255px; float:left; background:#efefef; border:1px solid #ddd; margin-left:10px;margin-top:10px;padding:7px;" >


<?php if($cf1[$i]['enable'] !=1){ ?>
<input type="checkbox" name="customfield[<?php echo $i; ?>][enable]" value="1" <?php if($cf1[$i]['enable'] ==1){ echo "checked"; } ?>> Enable Field
<?php } ?>
<div style="<?php if($cf1[$i]['enable'] ==1){ echo "display:visible"; }else{ echo "display:none"; } ?>">

<div style="padding:5px; background:#EDFFE1">
<p><input type="checkbox" name="customfield[<?php echo $i; ?>][enable]" value="1" <?php if($cf1[$i]['enable'] ==1){ echo "checked"; } ?>> Enable Field <input type="checkbox" name="customfield[<?php echo $i; ?>][show]" value="1" <?php if($cf1[$i]['show'] ==1){ echo "checked"; } ?>> Display Field        <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<strong>Enable Field?</strong><br />This simply means the field has been turned on. If members have already filled in this field or data exists within the field already it will be displayed on your listing page.<br><br><strong>Display Field?</strong><br />Enable this if you want the field to display on the submission page, if you untick this then the field will not dislay thus new members cannot fill in the their data. &quot;);"/>       </p>

</div>



<p><b>Display Caption - <a href="javascript:void();" onClick="jQuery('#options<?php echo $i; ?>').show();">Show</a>/<a href="javascript:void();" onClick="jQuery('#options<?php echo $i; ?>').hide();">Hide</a> Options </b></p> 
<input name="customfield[<?php echo $i; ?>][name]" type="text" style="width: 230px; font-size:14px;" class="txt" value="<?php if(isset($cf1[$i]['name'])){ echo $cf1[$i]['name']; } ?>" /> <small>Example: What is your website link? </small>

<div id="options<?php echo $i; ?>" style="display:none">
<div style="padding:5px; background:#F1FFDF">

<p><b>Display Field Type</b></p>
<select name="customfield[<?php echo $i; ?>][type]"  style="width: 230px; font-size:14px;" class="txt">
	<option value="text" <?php if(isset($cf1[$i]['type']) && $cf1[$i]['type'] =="text"){ echo "selected"; } ?>>Text Box (best for short answers)</option>
	<option value="textarea" <?php if(isset($cf1[$i]['type']) && $cf1[$i]['type'] =="textarea"){ echo "selected"; } ?>>Text Area (best for longer answers)</option>
	<option value="list" <?php if(isset($cf1[$i]['type']) && $cf1[$i]['type'] =="list"){ echo "selected"; } ?>>List Box (drop down menu of options)</option>
</select>
  </div> 
  
  <div style="padding:5px; background:#EDF3FE"> 
    
<p><b>Default values within the field</b></p>  <input name="customfield[<?php echo $i; ?>][value]" type="text" style="width: 230px; font-size:14px;" class="txt" value="<?php if(isset($cf1[$i]['value'])){ echo $cf1[$i]['value']; } ?>" /><br /><small>Example: http://</small>

       <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<strong>What does this mean?</strong><br />Sometimes you might want to add content to a text field for display when the page loads to help prompt the user for the correct input. <br><br> For example, if your asking the user to enter their website link and want them to include the http:// at the beginning you can enter the http:// as the default value so that they realise you require this also.<br><br><br /><strong>How do i add list box values?</strong><br />List box values should be entered like this:<br><br>Value1,Value2,Value3<br><br>Notice each new listbox option is seperated with a comma.&quot;);"/>      

</div>
 <div style="padding:5px; background:#FFE1E2"> 
<p><b> Custom Field ID<b></p> <input name="customfield[<?php echo $i; ?>][key]" type="text" style="width: 230px; font-size:14px;" class="txt" value="<?php if(isset($cf1[$i]['key'])){ echo $cf1[$i]['key']; } ?>" /><br /><small>Example: key_<?php echo $i;?> </small>
       <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<strong>What does this mean?</strong><br />A custom field ID is used to reference your new custom field within the Wordpress database.<br><br>Each ID <b>must be a unique</b> value with no spaces such as <b>Key_1</b> and <b>Key_2</b>.<br><br><br /><strong>Why is this useful?</strong><br />This allows you to store and attach input data from users to their posts so if you later decide to customize your website design/template you can reuse the data where you want to.&quot;);"/>   
</div>


<p><b>Package Page Description</b></p>
 <input name="customfield[<?php echo $i; ?>][desc1]" type="text" style="width: 230px; font-size:14px;" class="txt" value="<?php if(isset($cf1[$i]['desc1'])){ echo $cf1[$i]['desc1']; } ?>" /> <small>Explain alitle bit about what the field values are for.</small>

<p><b>Submit Page Description</b></p>
 <input name="customfield[<?php echo $i; ?>][desc2]" type="text" style="width: 230px; font-size:14px;" class="txt" value="<?php if(isset($cf1[$i]['desc2'])){ echo $cf1[$i]['desc2']; } ?>" /> <small>Explain what values the user should enter.</small>

<p><b>Enable this field on which packages?</b></p>
  
 
<div class="msg msg-info" style="line-height:30px;  ">
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack1]" value="1" <?php if(isset($cf1[$i]['pack1']) && $cf1[$i]['pack1'] ==1){ echo "checked"; } ?>> <b>Package 1 &nbsp;&nbsp;</b>
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack2]" value="1" <?php if(isset($cf1[$i]['pack2']) && $cf1[$i]['pack2'] ==1){ echo "checked"; } ?>> <b>Package 2</b><br />
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack3]" value="1" <?php if(isset($cf1[$i]['pack3']) && $cf1[$i]['pack3'] ==1){ echo "checked"; } ?>> <b>Package 3 &nbsp;&nbsp;</b>
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack4]" value="1" <?php if(isset($cf1[$i]['pack4']) && $cf1[$i]['pack4'] ==1){ echo "checked"; } ?>> <b>Package 4</b><br />

 
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack5]" value="1" <?php if(isset($cf1[$i]['pack5']) && $cf1[$i]['pack5'] ==1){ echo "checked"; } ?>> <b>Package 5 &nbsp;&nbsp;</b>
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack6]" value="1" <?php if(isset($cf1[$i]['pack6']) && $cf1[$i]['pack6'] ==1){ echo "checked"; } ?>> <b>Package 6</b><br />
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack7]" value="1" <?php if(isset($cf1[$i]['pack7']) && $cf1[$i]['pack7'] ==1){ echo "checked"; } ?>> <b>Package 7 &nbsp;&nbsp;</b>
<input type="checkbox" name="customfield[<?php echo $i; ?>][pack8]" value="1" <?php if(isset($cf1[$i]['pack8']) && $cf1[$i]['pack8'] ==1){ echo "checked"; } ?>> <b>Package 8</b>
 </div> 
 </div>
 
 
</div>
 </div>

 
</div></div>
<?php $i++; } ?>
 

<div style="clear:both;"></div>

<br />
<center><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></center>
</table>
</div>
<div id="premiumpress_tab6" class="content"></div>            
					 
</form>                 
</div>
 
<div class="clearfix"></div> 

 
</div>