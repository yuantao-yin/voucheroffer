
<?php if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } global $PPT; PremiumPress_Header();  ?>


<div class="premiumpress_box premiumpress_box-50 altbox"> 
<div class="premiumpress_boxin"><div class="header"><h3><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/up_alt.png" align="middle"> Bulk Category Setup </h3>

<a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('bulk-category')">Help Me</a> 
</div>
<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="catme" type="hidden" value="yes" />
<fieldset class="last">
<legend><strong>Bulk Category Setup </strong></legend>
<label>Categories</label>
<small>Creating lots of categories is time consuming, this tools allows you to create lots of categories quickly. Enter a list of categories below, separating each with a comma. Eg. cat1,cat2,cat3</small>
<textarea name="cats" style="height:100px;width:400px;"></textarea><br />
<label>Parent Category</label>
<small>Select a parent category below if you would like the list to be created as sub categories, leave blank to create parent categories.</small><br />
<select name="pcat" style="width: 250px;" class="txt" >
<option value="0">------------</option>
<?php echo $PPT->CategoryList(); ?>
</select>

<br /><br />
<input class="premiumpress_button" type="submit" value="Create Categories" style="color:white;" />
</fieldset>
</form>
</div>

<br />
 
 
 <?php if(PREMIUMPRESS_SYSTEM == "DirectoryPress"){ ?>
  
<div class="premiumpress_boxin"><div class="header"><h3><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/Ekisho Deep Ocean HD1.png" align="middle"> Database Import Tool </h3>
<a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('directorypress-import')">Help Me</a> 
</div>
<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="premiumpress_import" type="hidden" value="yes" />
<fieldset class="last">
<p>Remember, the system you select below must be installed onto the SAME database as your Wordpress installation.</p>
<legend><strong>Select Your System</strong></legend>

<label><input name="system" type="radio" value="esyndicate" checked="checked"> eSyndicate</label>
<small>More information about esyndicat.com can be <a href="http://www.intelliants.com/affiliates/xp.php?id=16800" target="_blank">found here.</a></small><br />

<label><input name="system" type="radio" value="phpld"> phpLD</label>
<small>More information about phplinkdirectory can be <a href="http://www.phplinkdirectory.com" target="_blank"> found here.</a> </small><br />

<label><input name="system" type="radio" value="phplinkbid"> php Link Bid</label>
<small>More information about phplinkbid can be <a href="http://www.phplinkbid.com" target="_blank">found here.</a></small><br />

<label><input name="system" type="radio" value="linkbidscript"> Link Bid Script</label>
<small>More information about phplinkbid can be <a href="http://www.linkbidscript.com/" target="_blank">found here.</a></small><br />


<label><input name="system" type="radio" value="edirectory"> eDirectory</label>
<small>More information about eDirectory can be <a href="http://www.edirectory.com" target="_blank">found here.</a></small><br />
  
<label>Table Prefix</label>
<input type="text" name="table_prefix" value=""><br />
<small>If you have installed your database with a prefix, enter it above.</small><br />
 

<br /><br />



<input class="premiumpress_button" type="submit" value="Import Database" style="color:white;" />
</fieldset>
</form>
</div>
 

<?php } ?>

<?php if(PREMIUMPRESS_SYSTEM == "DirectoryPress"){ ?>
<br /> 
<div class="premiumpress_boxin"><div class="header"><h3><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/open%20alt.png" align="middle"> Dmoz Import Tool </h3>
<a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('domz')">Help Me</a> 
</div>
<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="domz" type="hidden" value="yes" />
<fieldset class="last">
<legend><strong>Domz Import </strong></legend>
 
<table class="maintable">

 
	<tr class="mainrow">
		<td class="titledesc">DMOZ Link</td>
		<td class="forminp">
			<input type="text" name="domz_link" value=""><br />
			<small>Select the link from the dmoz website to extract the content, example: http://www.dmoz.org/Arts/Animation/</small>

		</td>
	</tr>

	<tr class="mainrow">
		<td class="titledesc" valign="top">Parent Category</td>
		<td class="forminp">
<?php
		
		wp_reset_query(); 
		
		$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
		$Maincatcount = count($Maincategories);	
			 
		foreach ($Maincategories as $cat) {
		
			if($cat->parent ==0){

				print '<input name="import[cat][]" type="checkbox" value="'.$cat->cat_ID.'">' . $cat->cat_name."<br />";
			}	 
			 
				$sub_categories = get_categories('parent='.$cat->cat_ID.'&depth=1&hide_empty=0&hierarchical=0');  		
				
				foreach ($sub_categories as $cat1) {							 
						
					print ' -- <input name="import[cat][]" type="checkbox" value="'.$cat1->cat_ID.'">' . $cat1->cat_name."<br />";
					
					
					
					$sub_categories1 = get_categories('parent='.$cat1->cat_ID.'&depth=1&hide_empty=0&hierarchical=0');  		
				
					foreach ($sub_categories1 as $cat2) {							 
						
						print ' ---- <input name="import[cat][]" type="checkbox" value="'.$cat2->cat_ID.'">' . $cat2->cat_name."<br />";
						
					}
				
						
				}
		
		}
		
		?>
		</td>
	</tr>

</table>

<input class="premiumpress_button" type="submit" value="Import Now" style="color:white;" />
</fieldset>
</form>
</div>





<?php } ?>
</div> 

<?php if(PREMIUMPRESS_SYSTEM == "DirectoryPress"){ ?>

<div class="premiumpress_box premiumpress_box-50 altbox"> 
<div class="premiumpress_boxin"><div class="header"><h3><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/exclamation.png" align="middle"> Broken Link Checker </h3>
<a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('linkcheck')">Help Me</a> 
</div>
<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="borken_links" type="hidden" value="yes" />
<fieldset class="last">
<legend><strong>Broken Link Checker </strong></legend>

<?php if(isset($_POST['borken_links'])){ ?>
<table width="100%"  border="0">
  <tr style="background:#666; height:30px;">
    <td style="color:#fff; width:55px; text-align:center;">&nbsp;ID</td>
    <td style="color:#fff;">&nbsp;Title / Link</td>
    <td style="color:#fff;">&nbsp;Edit</td>
  </tr>
<?php
global $wpdb;
$SQL = "SELECT $wpdb->posts.post_title, $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->postmeta.*
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->postmeta.meta_key ='url' AND ( $wpdb->posts.ID = $wpdb->postmeta.post_id )	";
 
$result = mysql_query($SQL);
while ($row = mysql_fetch_assoc($result)) { 
$error =  checkDomainAvailability($row['meta_value']);
if($error != 1){
?>
  <tr>
    <td style="text-align:center;"><?php echo $row['ID']; ?></td>
    <td><?php echo $row['post_title']; ?> <br /> <?php echo $error; ?> <a href="<?php echo $row['meta_value']; ?>" target="_blank">+</a></td>
    <td><a href="post.php?action=edit&post=<?php echo $row['ID']; ?>">Edit</a></td>
  </tr>
<?php } } ?>
</table>

<?php }else{ ?>
<input class="premiumpress_button" type="submit" value="Check for broken links" style="color:white;" />
<?php } ?>
</fieldset>
</form>
</div></div> 

 

<?php } ?>
 



<div class="premiumpress_box premiumpress_box-50"> 
<div class="premiumpress_boxin"><div class="header"><h3><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/copy%20doc.png" align="middle"> CSV File Import</h3>
<a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('csv')">Help Me</a> 
</div>
<form class="fields" method="post" target="_self" enctype="multipart/form-data"  style="padding:10px;">
<input name="csvimport" type="hidden" value="yes" />
<fieldset class="last">
<legend><strong>CSV File Import Options</strong></legend>
 <br />

<input type="file" name="import" class="input" style="width: 350px; font-size:14px;">
<br /><br />
<b>OR</b><br /> <br />Select from uploaded file;<br />


<select  class="input" name="file_csv" style="width: 350px; font-size:14px;">
<option value="0">-- ----- -- </option>
		<?php
	   
		$path = $PPT->FilterPath();
	    $HandlePath =  $path."wp-content/themes/".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/thumbs/";
 		$HandlePath = str_replace("//","/",str_replace("wp-admin","",$HandlePath));

	    $count=1;
		if($handle1 = opendir($HandlePath)) {
      
	  	while(false !== ($file = readdir($handle1))){	

		if(substr($file,-4) ==".csv"){
	
		?>
			<option value="<?php echo $file; ?>"><?php echo $file; ?></option>
		<?php
		} }}
		?>	 
</select>

           <img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/help.png" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>How does this work?</b><br>If you are having problems importing large CSV files to your hosting account it maybe easier to upload them via FTP instead. <br><br><b>Where should i put my CSV file?</b></br> Upload your CSV file to your 'thumbs' folder within your theme installation, if uploaded correctly it will be displayed in this list.<br>  Your thumbs folder path: <br /> <small><?php print get_option("imagestorage_path"); ?>thumbs/</small> &quot;);"/>  
        
<br /> <br />

<a href="javascript:void(0);" onClick="toggleLayer('mops');">more options</a>
<div style="display:none;" id="mops">
<label>Import Type</label>
<select  class="input" name="type"><option value="posts" selected>Wordpress Posts</option><option value="users">Wordpress Members</option></select>
<label>Column Delimiter</label>
<input name="del" type="text" class="txt"  value="," size="5">
<label>Enclosure</label>
<input name="enc" type="text" class="txt"  value="/" size="5">
<label>Document Column Headings</label>
<select  class="input" name="heading"><option value="yes">Yes</option><option value="no" selected>No</option></select>
<label>Remove Quotes</label>
<select  class="input" name="rq"><option value="yes">Yes</option><option value="no" selected>No</option></select>

<label><b>Select which category to add imported items too.</b></label>
<p>Note: You only need to select a category if your CSV file doesn't include a category already.</p>
<div style="background:#eee; padding:8px;">
<?php
		
		wp_reset_query(); 
		
		$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
		$Maincatcount = count($Maincategories);	
			 
		foreach ($Maincategories as $cat) {
		
			if($cat->parent ==0){

				print '<input name="csv[cat][]" type="checkbox" value="'.$cat->cat_ID.'">' . $cat->cat_name."<br />";
			}	 
			 
				$sub_categories = get_categories('parent='.$cat->cat_ID.'&depth=1&hide_empty=0&hierarchical=0');  		
				
				foreach ($sub_categories as $cat1) {							 
						
					print ' -- <input name="csv[cat][]" type="checkbox" value="'.$cat1->cat_ID.'">' . $cat1->cat_name."<br />";
					
					
					
					$sub_categories1 = get_categories('parent='.$cat1->cat_ID.'&depth=1&hide_empty=0&hierarchical=0');  		
				
					foreach ($sub_categories1 as $cat2) {							 
						
						print ' ---- <input name="csv[cat][]" type="checkbox" value="'.$cat2->cat_ID.'">' . $cat2->cat_name."<br />";
						
					}
				
						
				}
		
		}
		
		?>
	</div>	 
		<br />
			<small>Select one or more categories to import these products into.</small>
            
</div>
            
<input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" />
</fieldset>
</form>
</div></div> 



<div class="clearfix"></div>  