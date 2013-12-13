<?php



/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */

if(!function_exists('get_custom_field')) {
	function get_custom_field($field) {
		global $post;
		$custom_field = get_post_meta($post->ID, $field, true);
		echo $custom_field;
	}
}

/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */

function premiumpress_customfields_box() {
	if( function_exists( 'add_meta_box' )) {

	add_meta_box( 'premiumpress_customfields_0', __( 'CouponPress Listing Options', 'sp' ), 'premiumpress_couponpress', 'post', 'normal', 'high' );
 
	
		add_meta_box( 'premiumpress_customfields_5', __( 'Article Options', 'sp' ), 'premiumpress_article', 'article_type', 'normal', 'high' );
	add_meta_box( 'premiumpress_customfields_7', __( 'FAQ Options', 'sp' ), 'premiumpress_faq', 'faq_type', 'normal', 'high' );

	}
}

/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */
 
function premiumpress_couponpress(){

	global $post;
	
	$ThisPack 	= get_post_meta($post->ID, 'packageID', true);
	//$fea 		= get_post_meta($post->ID, 'featured', true);
 	$packdata 	= get_option("packages");
	$cf1 		= get_option("customfielddata"); 
 	$tdC =1;  
	//if($fea == "yes"){ $a1 = 'selected'; $a2=""; }else{$a1 = ''; $a2="selected"; } 

	$couponType = get_post_meta($post->ID, 'type', true);
	//if($fea == "coupon"){ $a1 = 'selected'; $a2=""; }elseif($fea == "print"){$a2 = ''; $a2="selected"; }elseif($fea == "article"){$a2 = ''; $a4="selected"; }else{$a1 = ''; $a3="selected"; } 
	
	$fea1 		= get_post_meta($post->ID, 'featured', true);
	if($fea1 == "yes"){ $f1 = 'selected'; $f2=""; }else{$f1 = ''; $f2="selected"; } 





?>
 
<!--[if IE]><script type="text/javascript" src="<?php echo $GLOBALS['template_url']; ?>/PPT/js/jquery.bgiframe.js"></script><![endif]-->
 <script type="text/javascript" src="<?php echo $GLOBALS['template_url']; ?>/PPT/js/jquery.date.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['template_url']; ?>/PPT/js/jquery.date_pick.js"></script>
</script><link rel="stylesheet" type="text/css" media="screen" href="<?php echo $GLOBALS['template_url']; ?>/PPT/css/css.date.css">

<?php 
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
	jQuery(\'#end-date\').bind(
		\'dpClosed\',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				jQuery(\'#start-date\').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});
			
</script>        
';


 
?>
 
<input type="hidden" value="" name="imgIdblock" id="imgIdblock" />
<script>

function ChangeImgBlock(divname){
document.getElementById("imgIdblock").value = divname;
}

jQuery(document).ready(function() {

jQuery('#upload_g_featured_image').click(function() {
 ChangeImgBlock('g_featured_image');
 formfield = jQuery('#g_featured_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

jQuery('#upload_g_image').click(function() {
 ChangeImgBlock('g_image');
 formfield = jQuery('#g_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});
 
jQuery('#upload_g_images').click(function() {
 ChangeImgBlock('g_images');
 formfield = jQuery('#g_images').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
}); 
 

window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {

	if(document.getElementById("imgIdblock").value !=""){
	
	 imgurl = jQuery('img',html).attr('src'); 
	 cvbalue = document.getElementById(document.getElementById("imgIdblock").value).value;
	 jQuery('#'+document.getElementById("imgIdblock").value).val(imgurl+","+cvbalue);
	 document.getElementById("imgIdblock").value = "";
	 tb_remove();
	 
	} else {
	
	  window.original_send_to_editor(html);
	
	}   
}



});
</script>
<?php


	// Use nonce for verification ... ONLY USE ONCE!
	echo '<input type="hidden" name="sp_noncename" id="sp_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	echo '<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100" style="width:680px !important;"><div class="premiumpress_boxin" style="width:680px !important;"><div class="header">
<h3 style="background:none;font-size:18px;margin-left:0px;padding-left:0px;"><img src="../wp-content/themes/couponpress/images/premiumpress/h-ico/open alt.png" align="middle"> Options</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe(\'addlisting\')">Help Me</a>						 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active"> Details</a></li>
    <li><a rel="premiumpress_tab3" href="#">Image Setup</a></li>
	<li><a rel="premiumpress_tab2" href="#">Custom Fields</a></li>
    <li><a rel="premiumpress_tab4" href="#">Package Setup</a></li>
  
</ul>
</div>

<div id="premiumpress_tab1" class="content">';

	echo '<table width="100%"  border="0"><tr width="50%"><td valign="top">';
	

		
		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Coupon Type</b>", 'sp' ) . '</label><br />';
		echo '<select name="field[type]" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"><option ';
		
		if($couponType == "coupon"){ echo "selected=selected"; }
		
		echo ' value="coupon">Coupon</option><option ';
		
		if($couponType == "print"){ echo "selected=selected"; }
		
		echo ' value="print">Printable Coupon</option><option ';
		
		if($couponType == "offer"){ echo "selected=selected"; }
		
		echo ' value="offer">Offer</option>
	 
		</select><br /><br />';

	 
		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Coupon Code</b> ", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[code]" value="'.get_post_meta($post->ID, 'code', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />';
 

	
		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Start Date</b> <small>Format yyyy-mm-dd</small>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[starts]" id="start-date" class="date-pick dp-applied" value="'.get_post_meta($post->ID, 'starts', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />';


		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>End Date</b> <small>Format yyyy-mm-dd</small>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[expires]" id="end-date" class="date-pick dp-applied" value="'.get_post_meta($post->ID, 'expires', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />';
		
		
		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Featured Coupon</b> <small>(yes/no, highlighted in search results)</small>", 'sp' ) . '</label><br />';
		echo '<select name="field[featured]" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;">
		<option value="yes" '.$f1.'>yes - hightlight this listing</option>
		<option value="no" '.$f2.'>no</option></select><br /><br />';	

	echo '</td><td width="50%" valign="top">';
	
	

		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Merchant/Website Link</b> <br><small>Used to generate the display image.</small>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[url]" value="'.get_post_meta($post->ID, 'url', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"/><br /><br />';


		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Affiliate Link</b> <br><small>The link visitors will go to when they click the coupon</small>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[link]" value="'.get_post_meta($post->ID, 'link', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"/><br /><br />';

		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Hits/View Counter</b> ", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[hits]" value="'.get_post_meta($post->ID, 'hits', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />';


		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Item SKU</b><br><small>(optional, used for CSV import/update)</small>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[SKU]" value="'.get_post_meta($post->ID, 'SKU', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"/><br /><br />';

  
	echo '</td></tr></table>';

echo '</div>

<div id="premiumpress_tab2" class="content">

<tr class="even"><td colspan="2" class="first last">
<div class="msg msg-info" style="margin:10px;">
  <p>
Custom fields allow you to customize the submission form on your website with your own questions. You can add/edit custom fields in the admin area under <b><a href="admin.php?page=submit" style="text-decoration:underline;">"Submission -> Custom Fields"</a> tab.</b>
</p>
</div>
</td></tr>	
';

	echo '<table width="100%"  border="0">';


	for($i=1; $i < 20; $i++){ if($cf1[$i]['enable'] ==1){

		if($tdC == 1){ echo '<tr> ';}

			echo '<td width="50%" valign="top">';
		
			$Value= get_post_meta($post->ID, $cf1[$i]['key'], true);
		
			echo '<label style="font-size:14px; line-height:30px;"><b>' .stripslashes($cf1[$i]['name']) . '</b></label><br />';
		
			switch($cf1[$i]['type']){
				 case "textarea": {
					echo '<textarea class="adfields" name="'.$cf1[$i]['key'].'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3; height:60px;">';
					echo $Value;
					echo '</textarea>';
				 } break;
				 case "list": {
					$listval = $Value; 
					$listvalues = explode(",",$cf1[$i]['value']);
					echo '<select name="'.$cf1[$i]['key'].'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;">';
					foreach($listvalues as $value){ 
						if($listval ==  $value){ 
						echo '<option value="'.$value.'" selected>'.$value.'</option>'; 
						}else{
						echo '<option value="'.$value.'">'.$value.'</option>'; 
						}
					}
					echo '</select>';		
		
				 } break;
				 default: {
					echo '<input type="text" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" name="'.$cf1[$i]['key'].'" size="55" maxlength="100" value="'.$Value.'">';
				 }	
				}
	
			echo '<br /><br />';	
		echo '</td>';

		echo '<input type="hidden"  name="custom['.$i.'][name]" value="'.$cf1[$i]['key'].'" />';

		if($tdC == 2){ echo '</tr> '; $tdC=1; }else{ $tdC++; }
	 	 
		

		}
	}


echo '</table>';

echo '</div>

<div id="premiumpress_tab3" class="content">';

	echo '<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" /> <table width="100%"  border="0"><tr width="50%"><td valign="top">';

		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Main Display Image</b><br><small>By default the image is generated from the website URL, if you set an image here it will override the default image.</small>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[image]" id="g_image" value="'.get_post_meta($post->ID, 'image', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"/><br /><br />';
		?>
		<a href='javascript:void(0);' onClick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_image');"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/find.png" align="middle"> View Uploaded Images</a>
        
        <input id="upload_g_image" type="button" size="36" name="upload_g_image" value="Upload Image" />
         
		<br />
		<?php
/*
		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Featured Image</b> <small>(used for homepage slider)</small>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[featured_image]" id="g_featured_image" value="'.get_post_meta($post->ID, 'featured_image', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"/><br /><br />';

		?>
		<a href='javascript:void(0);' onClick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_featured_image');"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/find.png" align="middle"> View Uploaded Images</a>  <a href="admin.php?page=images" target="_blank" style="margin-left:50px;"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/monitor.png" align="middle"> Upload Image </a>
		<br />
		<?php*/

	echo '</td><td width="50%" valign="top">';

		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Gallery Images</b> <small>(seperate multiple images with a comma)</small>", 'sp' ) . '</label><br />';
		echo '<textarea name="field[images]" id="g_images" style="font-size:10px;padding:5px; width:95%; background:#e7fff3; height:100px;" />'.str_replace(",",",\n",get_post_meta($post->ID, 'images', true)).'</textarea><br /><br />';
 
		?>
		<a href='javascript:void(0);' onClick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_images');"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/find.png" align="middle"> View Uploaded Images</a> 
        
        <input id="upload_g_images" type="button" size="36" name="upload_g_images" value="Upload Image" />
         
		<br />
		<?php

	echo '</td></tr></table>';
	
echo '</div>

<div id="premiumpress_tab4" class="content">';

	echo '<table width="100%"  border="0"><tr width="50%"><td valign="top">';

		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Package</b>", 'sp' ) . '</label><br />';

		echo '<select name="field[packageID]" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;">
		<option value="1"'; if($ThisPack ==1){ echo 'selected'; } echo '>'.$packdata[1]['name'].'</option>
		<option value="2"'; if($ThisPack ==2){ echo 'selected'; } echo '>'.$packdata[2]['name'].'</option>
		<option value="3"'; if($ThisPack ==3){ echo 'selected'; } echo '>'.$packdata[3]['name'].'</option>
		<option value="4"'; if($ThisPack ==4){ echo 'selected'; } echo '>'.$packdata[4]['name'].'</option>
		<option value="5"'; if($ThisPack ==5){ echo 'selected'; } echo '>'.$packdata[5]['name'].'</option>
		<option value="6"'; if($ThisPack ==6){ echo 'selected'; } echo '>'.$packdata[6]['name'].'</option>
		<option value="7"'; if($ThisPack ==7){ echo 'selected'; } echo '>'.$packdata[7]['name'].'</option>
		<option value="8"'; if($ThisPack ==8){ echo 'selected'; } echo '>'.$packdata[8]['name'].'</option>
		</select><br /><br />';
		
			echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Day's Till Package Expire</b> (numeric value)", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[pexpires]" value="'.get_post_meta($post->ID, 'pexpires', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />';


	echo '</td><td width="50%" valign="top">'; 


	echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Country</b> ", 'sp' ) . '</label><br />';
				
		echo '  <select name="field[country]"  style="font-size:14px;padding:5px; width:95%; background:#e7fff3;">
<option value="'.get_post_meta($post->ID, 'country', true).'" selected="selected">'.get_post_meta($post->ID, 'country', true).'</option> 
<option value="0">-----------</option>   
<option value="United Kingdom">United Kingdom</option> 
<option value="United States">United States of America</option> 
<option value="Afghanistan">Afghanistan</option> 
<option value="Albania">Albania</option> 
<option value="Algeria">Algeria</option> 
<option value="American Samoa">American Samoa</option> 
<option value="Andorra">Andorra</option> 
<option value="Angola">Angola</option> 
<option value="Anguilla">Anguilla</option> 
<option value="Antarctica">Antarctica</option> 
<option value="Antigua and Barbuda">Antigua and Barbuda</option> 
<option value="Argentina">Argentina</option> 
<option value="Armenia">Armenia</option> 
<option value="Aruba">Aruba</option> 
<option value="Australia">Australia</option> 
<option value="Austria">Austria</option> 
<option value="Azerbaijan">Azerbaijan</option> 
<option value="Bahamas, The">Bahamas, The</option> 
<option value="Bahrain">Bahrain</option> 
<option value="Bangladesh">Bangladesh</option> 
<option value="Barbados">Barbados</option> 
<option value="Belarus">Belarus</option> 
<option value="Belgium">Belgium</option> 
<option value="Belize">Belize</option> 
<option value="Benin">Benin</option> 
<option value="Bermuda">Bermuda</option> 
<option value="Bhutan">Bhutan</option> 
<option value="Bolivia">Bolivia</option> 
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
<option value="Botswana">Botswana</option> 
<option value="Bouvet Island">Bouvet Island</option> 
 
<option value="Brazil">Brazil</option> 
<option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
<option value="British Virgin Islands">British Virgin Islands</option> 
<option value="Brunei">Brunei</option> 
<option value="Bulgaria">Bulgaria</option> 
<option value="Burkina Faso">Burkina Faso</option> 
<option value="Burundi">Burundi</option> 
<option value="Cambodia">Cambodia</option> 
<option value="Cameroon">Cameroon</option> 
 
<option value="Canada">Canada</option> 
<option value="Canary Islands">Canary Islands</option> 
<option value="Cape Verde">Cape Verde</option> 
<option value="Cayman Islands">Cayman Islands</option> 
<option value="Central African Republic">Central African Republic</option> 
<option value="Chad">Chad</option> 
<option value="Chile">Chile</option> 
<option value="China">China</option> 
<option value="Christmas Island">Christmas Island</option> 
 
<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
<option value="Colombia">Colombia</option> 
<option value="Comoros">Comoros</option> 
<option value="Congo">Congo</option> 
<option value="Cook Islands">Cook Islands</option> 
<option value="Costa Rica">Costa Rica</option> 
<option value="Croatia">Croatia</option> 
<option value="Cuba">Cuba</option> 
<option value="Cyprus">Cyprus</option> 
 
<option value="Czech Republic">Czech Republic</option> 
<option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option> 
<option value="Denmark">Denmark</option> 
<option value="Djibouti">Djibouti</option> 
<option value="Dominica">Dominica</option> 
<option value="Dominican Republic">Dominican Republic</option> 
<option value="East Timor">East Timor</option> 
<option value="Ecuador">Ecuador</option> 
<option value="Egypt">Egypt</option> 
 
<option value="El Salvador">El Salvador</option> 
<option value="Equatorial Guinea">Equatorial Guinea</option> 
<option value="Eritrea">Eritrea</option> 
<option value="Estonia">Estonia</option> 
<option value="Ethiopia">Ethiopia</option> 
<option value="Falkland Islands (Malvinas)" >Falkland Islands (Malvinas)</option> 
<option value="Faroe Islands">Faroe Islands</option> 
<option value="Fiji">Fiji</option> 
<option value="Finland">Finland</option> 
 
<option value="France">France</option> 
<option value="French Guiana">French Guiana</option> 
<option value="French Polynesia">French Polynesia</option> 
<option value="French Southern/Antarctic Lands">French Southern/Antarctic Lands</option> 
<option value="Gabon">Gabon</option> 
<option value="Gambia">Gambia</option> 
<option value="Georgia">Georgia</option> 
<option value="Germany">Germany</option> 
<option value="Ghana">Ghana</option> 
 
<option value="Gibraltar">Gibraltar</option> 
<option value="Greece">Greece</option> 
<option value="Greenland">Greenland</option> 
<option value="Grenada">Grenada</option> 
<option value="Guadeloupe">Guadeloupe</option> 
<option value="Guam">Guam</option> 
<option value="Guatemala">Guatemala</option> 
<option value="Guinea">Guinea</option> 
<option value="Guinea-Bissau">Guinea-Bissau</option> 
 
<option value="Guyana">Guyana</option> 
<option value="Haiti">Haiti</option> 
<option value="Heard and McDonald Islands">Heard and McDonald Islands</option> 
<option value="Honduras">Honduras</option> 
<option value="Hong Kong">Hong Kong</option> 
<option value="Hungary">Hungary</option> 
<option value="Iceland">Iceland</option> 
<option value="India">India</option> 
<option value="Indonesia">Indonesia</option> 
 
<option value="Iraq">Iraq</option> 
<option value="Ireland">Ireland</option> 
<option value="Islamic Republic of Iran">Islamic Republic of Iran</option> 
<option value="Israel">Israel</option> 
<option value="Italy">Italy</option> 
<option value="Ivory Coast">Ivory Coast</option> 
<option value="Jamaica">Jamaica</option> 
<option value="Japan">Japan</option> 
<option value="Jordan">Jordan</option> 
 
<option value="Kazakhstan">Kazakhstan</option> 
<option value="Kenya">Kenya</option> 
<option value="Kiribati">Kiribati</option> 
<option value="Korea, South">Korea, South</option> 
<option value="Kuwait">Kuwait</option> 
<option value="Kyrgyzstan">Kyrgyzstan</option> 
<option value="Lao Peoples Democratic Republic">Lao Peoples Democratic Republic</option> 
<option value="Latvia">Latvia</option> 
<option value="Lebanon">Lebanon</option> 
 
<option value="Lesotho">Lesotho</option> 
<option value="Liberia" >Liberia</option> 
<option value="Libya">Libya</option> 
<option value="Liechtenstein">Liechtenstein</option> 
<option value="Lithuania">Lithuania</option> 
<option value="Luxembourg">Luxembourg</option> 
<option value="Macau">Macau</option> 
<option value="Macedonia">Macedonia</option> 
<option value="Madagascar">Madagascar</option> 
 
<option value="Malawi">Malawi</option> 
<option value="Malaysia">Malaysia</option> 
<option value="Maldives">Maldives</option> 
<option value="Mali">Mali</option> 
<option value="Malta">Malta</option> 
<option value="Man, Isle of">Man, Isle of</option> 
<option value="Marshall Islands">Marshall Islands</option> 
<option value="Martinique">Martinique</option> 
<option value="Mauritania">Mauritania</option> 
 
<option value="Mauritius" >Mauritius</option> 
<option value="Mayotte">Mayotte</option> 
<option value="Mexico">Mexico</option> 
<option value="Micronesia">Micronesia</option> 
<option value="Moldova, Republic of">Moldova, Republic of</option> 
<option value="Monaco">Monaco</option> 
<option value="Mongolia">Mongolia</option> 
<option value="Monserrat">Monserrat</option> 
<option value="Morocco">Morocco</option> 
 
<option value="Mozambique">Mozambique</option> 
<option value="Namibia">Namibia</option> 
<option value="Nauru">Nauru</option> 
<option value="Nepal">Nepal</option> 
<option value="Netherlands">Netherlands</option> 
<option value="Netherlands Antilles">Netherlands Antilles</option> 
<option value="New Caledonia">New Caledonia</option> 
<option value="New Zealand">New Zealand</option> 
<option value="Nicaragua">Nicaragua</option> 
 
<option value="Niger">Niger</option> 
<option value="Nigeria">Nigeria</option> 
<option value="Niue">Niue</option> 
<option value="Norfolk Island">Norfolk Island</option> 
<option value="Northern Mariana Islands">Northern Mariana Islands</option> 
<option value="Norway">Norway</option> 
<option value="Oman">Oman</option> 
<option value="Pakistan">Pakistan</option> 
<option value="Palau">Palau</option> 
 
<option value="Panama">Panama</option> 
<option value="Papua New Guinea">Papua New Guinea</option> 
<option value="Paraguay">Paraguay</option> 
<option value="Peru">Peru</option> 
<option value="Philippines">Philippines</option> 
<option value="Pitcairn">Pitcairn</option> 
<option value="Poland">Poland</option> 
<option value="Portugal">Portugal</option> 
<option value="Puerto Rico">Puerto Rico</option> 
 
<option value="Qatar">Qatar</option> 
<option value="Reunion">Reunion</option> 
<option value="Romania">Romania</option> 
<option value="Russia">Russia</option> 
<option value="Rwanda">Rwanda</option> 
<option value="Saint Lucia">Saint Lucia</option> 
<option value="Samoa">Samoa</option> 
<option value="San Marino">San Marino</option> 
<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
 
<option value="Saudi Arabia">Saudi Arabia</option> 
<option value="Scotland">Scotland</option> 
<option value="Senegal">Senegal</option> 
<option value="Seychelles">Seychelles</option> 
<option value="Sierra Leone">Sierra Leone</option> 
<option value="Singapore">Singapore</option> 
<option value="Slovakia">Slovakia</option> 
<option value="Slovenia">Slovenia</option> 
<option value="Solomon Islands">Solomon Islands</option> 
<option value="Somalia">Somalia</option> 
 
<option value="South Africa">South Africa</option> 
<option value="South Georgia/Sandwich Islands">South Georgia/Sandwich Islands</option> 
<option value="Spain">Spain</option> 
<option value="Sri Lanka">Sri Lanka</option> 
<option value="St. Helena">St. Helena</option> 
<option value="St. Kitts and Nevis">St. Kitts and Nevis</option> 
<option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option> 
<option value="St. Vincent and the Grenadines">St. Vincent and the Grenadines</option> 
<option value="Sudan">Sudan</option> 
<option value="Suriname">Suriname</option> 
<option value="Svalbard/Jan Mayen Islands">Svalbard/Jan Mayen Islands</option> 
<option value="Swaziland">Swaziland</option> 
<option value="Sweden">Sweden</option> 
<option value="Switzerland">Switzerland</option> 
<option value="Syria">Syria</option> 
<option value="Taiwan">Taiwan</option> 
<option value="Tajikistan">Tajikistan</option> 
<option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
 
<option value="Thailand">Thailand</option> 
<option value="Togo">Togo</option> 
<option value="Tokelau">Tokelau</option> 
<option value="Tonga">Tonga</option> 
<option value="Trinidad and Tobago">Trinidad and Tobago</option> 
<option value="Tunisia">Tunisia</option> 
<option value="Turkey">Turkey</option> 
<option value="Turkmenistan">Turkmenistan</option> 
<option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
 
<option value="Tuvalu">Tuvalu</option> 
<option value="U.S. Minor Outlying Islands">U.S. Minor Outlying Islands</option> 
<option value="Uganda">Uganda</option> 
<option value="Ukraine">Ukraine</option> 
<option value="United Arab Emirates">United Arab Emirates</option> 
<option value="Uruguay">Uruguay</option> 
<option value="Uzbekistan">Uzbekistan</option> 
 
<option value="Vanuatu">Vanuatu</option> 
<option value="Vatican City State (Holy See)">Vatican City State (Holy See)</option> 
<option value="Venezuela">Venezuela</option> 
<option value="Vietnam">Vietnam</option> 
<option value="Virgin Islands">Virgin Islands</option> 
<option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option> 
<option value="Western Sahara">Western Sahara</option> 
<option value="Yemen">Yemen</option> 
<option value="Zambia">Zambia</option> 
<option value="Zimbabwe">Zimbabwe</option> 
        </select></span>
        </div>
	


		<label style="font-size:14px; line-height:30px;">' . __("<b>State</b> ", 'sp' ) . '</label><br />
		<input type="text" name="field[state]" value="'.get_post_meta($post->ID, 'state', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />
 
		 
		<br /><br />';

 		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Map Location</b> (optional) ", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[map_location]" value="'.get_post_meta($post->ID, 'map_location', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />';
 
	echo '</td></tr></table>';
	
echo '</div>

<div id="premiumpress_tab5" class="content">';

	echo '<table width="100%"  border="0"><tr width="50%"><td valign="top">';

	 
 
 
	echo '</td><td width="50%" valign="top">';


 
 
	echo '</td></tr></table>';
	
echo '</div>

</div>



</div> 
<div class="clear"></div>
';

} 
 
 
function premiumpress_faq(){

	global $post;

	$ThisPack = get_post_meta($post->ID, 'packageID', true);
 	$packdata = get_option("packages");

	// Use nonce for verification ... ONLY USE ONCE!
	echo '<input type="hidden" name="sp_noncename" id="sp_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';


	echo '<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" /> <table width="100%"  border="0"><tr width="50%"><td valign="top">';

		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Display Image</b> (example: imagename.jpg or http://../imagename.jpg)", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[image]" id="g_image" value="'.get_post_meta($post->ID, 'image', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"/><br /><br />';
		?>
		<a href='javascript:void(0);' onClick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_image');"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/find.png" align="middle"> View Uploaded Images</a> <a href="admin.php?page=images" target="_blank" style="margin-left:50px;"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/monitor.png" align="middle"> Upload Image </a> 
		<br />
		<?php 

	
	echo '</td><td width="50%" valign="top">';

	echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Search Description</b>  ", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[post_excerpt]" value="'.get_post_meta($post->ID, 'post_excerpt', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;" /><br /><br />';




	echo '</td></tr></table>';

} 
 
 function premiumpress_article(){
 

	global $post;
	$type = get_post_meta($post->ID, 'type', true);
	// Use nonce for verification ... ONLY USE ONCE!
	echo '<input type="hidden" name="sp_noncename" id="sp_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
?>

 
 
<input type="hidden" value="" name="imgIdblock" id="imgIdblock" />
<script>

function ChangeImgBlock(divname){
document.getElementById("imgIdblock").value = divname;
}

jQuery(document).ready(function() {

jQuery('#upload_g_image').click(function() {
 ChangeImgBlock('g_image');
 formfield = jQuery('#g_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});


window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {

	if(document.getElementById("imgIdblock").value !=""){
	
	 imgurl = jQuery('img',html).attr('src'); 
	 cvbalue = document.getElementById(document.getElementById("imgIdblock").value).value;
	 jQuery('#'+document.getElementById("imgIdblock").value).val(imgurl);
	 document.getElementById("imgIdblock").value = "";
	 tb_remove();
	 
	} else {
	
	  window.original_send_to_editor(html);
	
	}   
}



});
</script>
<?php

echo '<link rel="stylesheet" type="text/css" href="'.PPT_PATH.'js/lightbox/jquery.lightbox.css" />
        <script type="text/javascript" src="'.PPT_PATH.'js/lightbox/jquery.lightbox.js"></script>   
         <script type="text/javascript">
          jQuery(document).ready(function(){
            jQuery(\'.lightbox\').lightbox();
          });
        </script>';
		
	echo '<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" /> <table width="100%"  border="0"><tr width="50%"><td valign="top">';

		echo '<label style="font-size:14px; line-height:30px;">' . __("<b>Main Image</b>", 'sp' ) . '</label><br />';
		echo '<input type="text" name="field[image]" id="g_image" value="'.get_post_meta($post->ID, 'image', true).'" style="font-size:14px;padding:5px; width:95%; background:#e7fff3;"/><br /><br />';
		?>
		<a href='javascript:void(0);' onClick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_image');"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/find.png" align="middle"> View Uploaded Images</a>
        
        <input id="upload_g_image" type="button" size="36" name="upload_g_image" value="Upload Image" />
		<br />
		<?php


	echo '</td><td width="50%" valign="top">';


 
	echo '</td></tr></table>';

} 

 

/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */

function premiumpress_postdata($post_id, $post) {

global $wpdb;
	
	if ( !wp_verify_nonce( $_POST['sp_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post->ID ))
		return $post->ID;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	}

	$mydata = array();
 
	// CUSTOM FIELDS
	if(is_array($_POST['field']) ){
		foreach($_POST['field'] as $key=>$val){		
			if(substr($val,-1) == ","){			
				$mydata[$key] = substr($val,0,-1);	
			}else{
				$mydata[$key] = $val;	
			}						
		}	
	}

	// CUSTOM FIELDS
	if(is_array($_POST['custom']) ){
		foreach($_POST['custom'] as $in_array){
	
			$mydata[$in_array['name']] = $_POST[$in_array['name']];				
		}	
	} 
 
	
	foreach ($mydata as $key => $value) {
		if( $post->post_type == 'revision' ) return;
		$value = implode(',', (array)$value); 
		if(get_post_meta($post->ID, $key, FALSE)) { 
			update_post_meta($post->ID, $key, $value);
		} else { 
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key);
	}
	
	if(isset($_POST['pts_post_type']) && strlen($_POST['pts_post_type']) > 1){	 
 
	  mysql_query("UPDATE ".$wpdb->prefix."posts SET post_type='".$_POST['pts_post_type']."' WHERE ID='".$post->ID."' LIMIT 1");
	   
	}	

}

/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */

add_action('admin_menu', 'premiumpress_customfields_box');
add_action('save_post',  'premiumpress_postdata', 1, 2);


/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */

add_filter( 'manage_posts_columns', 'premiumpress_columns' );
add_action('manage_posts_custom_column', 'premiumpress_custom_column', 10, 2); //Just need a single function to add multiple columns

add_filter('manage_posts_columns', 'scompt_custom_columnsDP');

function scompt_custom_columnsDP($defaults) {

   // unset($defaults['comments']);
   unset($defaults['author']);
    unset($defaults['tags']);
 
    return $defaults;
}
function premiumpress_columns($defaults) {

    $defaults['pak'] 		= "Package";
    $defaults['code'] 		= 'Coupon Code';
    $defaults['hits'] 		= 'Views';
    $defaults['image'] 		= 'Display Image';

    return $defaults;
}


function premiumpress_custom_column($column_name, $post_id) {

    global $wpdb, $PPT;

	$PACKAGE_OPTIONS = get_option("packages"); 

    if( $column_name == 'pak' ) {

			$pak = get_post_meta($post_id, "packageID", true);
			if ( !empty( $pak ) ) {

				print strip_tags($PACKAGE_OPTIONS[$pak]['name']);
			} else {
				echo 'No Package Set';  //No Taxonomy term defined
			}

	} else if( $column_name == 'hits' ) {   //Adding 2nd column
	
print get_post_meta($post_id, "hits", true);

	} else if( $column_name == 'code' ) {   //Adding 2nd column
	
print get_post_meta($post_id, "code", true);

	} else if( $column_name == 'image' ) {   //Adding 2nd column
 
			//if ( !empty( $img ) && strlen($img) > 2 ) {
				echo "<img src='".$PPT->Image($post_id,"url")."' style='max-height:80px'>";
			//} else {
			//	_e('<font color="red">No Image Set</font>');
			//}

	} else {
            echo '<i>'.$column_name.'</i>'; //Only 2 columns, blank now.
        }

}


 

?>