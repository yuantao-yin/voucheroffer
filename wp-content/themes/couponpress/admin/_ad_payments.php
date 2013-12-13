<?php
if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; }

global $wpdb,$PPT; PremiumPress_Header(); ?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_payment.png" align="middle"> Payment Setup</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a>							 
<ul>
 
 
</ul>
</div>



<div id="premiumpress_tab1" class="content">

 <br />
 <table><tr class="mainrow"><td>
 

<?php include(str_replace("functions/","",THEME_PATH) . '/PPT/func/func_paymentgateways.php');

$i=1;$p=1;
if(is_array($gatway)){
foreach($gatway as $Value){

 
?>
 
 
 	<?php if($p ==3){ ?><tr class="mainrow"><td><?php $p=1; } $p++; ?>
    
    </td><td class="forminp" valign="top">
    
    
    

<h3 class="title"> <a href="javascript:void(0);" onClick="toggleLayer('g_<?php echo $i ?>');" style="text-decoration:none; color:#333;font-size:16px;">
<?php echo $Value['name'] ?> 
 - <img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/edit1.png" align="middle"> Edit
 
 <?php if(get_option($Value['function']) =="yes"){ ?>
 
 <img src="<?php echo $GLOBALS['template_url']; ?>/images/admin/star.png" align="middle"> <span style="color:red;">Enabled </span>
 
 <?php } ?>
</a>


 </h3>
 
 <?php if(strlen($Value['website']) > 0){ ?><p>Merchant Website: <a href="<?php echo $Value['website']; ?>" target="_blank"><?php echo $Value['website']; ?></a></p><?php } ?>
 <!--<p>Includes Callback: <img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/<?php echo $Value['callback']; ?>.png" /></p>-->
 
 <form method="post"  target="_self">
<input name="submitted" type="hidden" value="yes" />
 
<table class="maintable" style="background:white; display:none;" id="g_<?php echo $i ?>">	

	<?php foreach($Value['fields'] as $key => $field){   ?>
	<tr class="mainrow"><td class="titledesc"><?php echo $field['name'] ?></td><td class="forminp"><?php echo MakeField($field['type'], $field['fieldname'],get_option($field['fieldname']),$field['list'], $field['default']) ?></td></tr>
	<?php } ?>

<tr>
<td colspan="3"><input class="premiumpress_button" type="submit" value="<?php _e('Save changes','cp')?>" style="color:white;" /></td>
</tr>
</table>
</form>    
    
    
    
    
 
        
 <?php $i++; } }  ?>  
 

</td></tr></table> 
 
 
 
 



<?php  ?>		 

</div>                    
 
</div>
<div class="clearfix"></div>



 
