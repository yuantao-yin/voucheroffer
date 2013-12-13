<?php 
 
// SAVE ORDER SETTINGS
if(isset($_POST['saveorder'])){
 
	$SQL = "UPDATE ".$wpdb->prefix."orderdata SET 
		cus_id='".$_POST['cus_id']."',
		order_id='".$_POST['order_id']."',
		order_data='".$_POST['order_data']."',		
		order_address='".$_POST['order_address']."',
		order_country='".$_POST['order_country']."',
		 
		order_email='".$_POST['order_email']."',
		order_total='".$_POST['order_total']."',
		order_shipping ='".$_POST['order_shipping']."',
		order_status='".$_POST['order_status']."',
		payment_data='".$_POST['payment_data']."'
		WHERE order_id='".PPTCLEAN($_POST['order_id'])."' LIMIT 1";
		
		mysql_query($SQL);
	 
		
	$_GET['u'] = "yes";
}elseif(isset($_POST['deleteorder'])){

mysql_query("DELETE FROM ".$wpdb->prefix."orderdata WHERE autoid='".PPTCLEAN($_POST['delo'])."' LIMIT 1");
mysql_query("DELETE FROM ".$wpdb->prefix."orderdata WHERE order_id='".PPTCLEAN($_POST['delo'])."' LIMIT 1");

$GLOBALS['error'] 		= 1;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "Orders Deleted Successfully";

}






if(isset($_GET['id'])  ){ 

	// START TO MAKE PAGE OUTPUT
	global $PPT,$wpdb; PremiumPress_Header(); 
 
	$currency_symbol = get_option("currency_symbol");
	$order   = array("\r\n", "\n", "\r");
	$replace = '<br />'; 
	$SQL = "SELECT * FROM ".$wpdb->prefix."orderdata LEFT JOIN $wpdb->users ON ($wpdb->users.ID = ".$wpdb->prefix."orderdata.cus_id)  WHERE ".$wpdb->prefix."orderdata.order_id = '".$_GET['id']."' GROUP BY order_id LIMIT 1"; 
	$posts = mysql_query($SQL, $wpdb->dbh) or die(mysql_error().' on line: '.__LINE__);
	while ($order = mysql_fetch_object($posts)) {
	
	?>
	
	<?php if(isset($_GET['u']) ){  ?>
	<div class="msg msg-ok">
	  <p><strong>Great,</strong> Order details updated successfully.</p>
	</div>
	<?php } ?>
	
	
	<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100" <?php if(isset($_POST['delo'])){ echo "style='display:none;'"; } ?>><div class="premiumpress_boxin"><div class="header">
	<h3><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/advanced.png" align="middle"> #<?php echo $order->order_id ?></h3>							 
	<ul>
		<li><a rel="premiumpress_tab1" href="#" class="active">Order Items</a></li>
		<li><a rel="premiumpress_tab2" href="#">Details</a></li>
		<li><a rel="premiumpress_tab3" href="#">Payment Status</a></li>
	 
	</ul>
	</div>
	
	<form method="post" target="_self">
	<input name="saveorder" type="hidden" value="yes" />
	<input type="hidden" name="autoid" value="<?php echo $order->autoid ?>">
	<input type="hidden" name="order_total" value="<?php echo $order->order_total ?>">
	<input type="hidden" name="order_shipping" value="<?php echo $order->order_shipping ?>">
	<input type="hidden" name="order_id" value="<?php echo $order->order_id ?>">
	
	<div id="premiumpress_tab1" class="content">
	<?php
	
	$product_array = explode(",",$order->order_items); $tt=0;
	 
	if(!empty($product_array)){ ?>
	
	 <table width="100%"  class="maintable" style="background:white;">
	 
  	<tr class="mainrow">
		  <td class="titledesc" colspan="4">
		  <b> Purchase Information</b>
			
            <textarea style="height:300px; width:760px;" name="order_data"><?php echo strip_tags(str_replace("<br/>","\r\n\r\n",$order->order_data)); ?></textarea>
		  
          <?php if(substr($order->order_items,-2) == "x1"){   ?>
          <p style="font-size:16px;"><a href="post.php?post=<?php echo str_replace("x1","",$order->order_items); ?>&action=edit"><img src="../wp-content/themes/<?php echo strtolower(constant('PREMIUMPRESS_SYSTEM')); ?>/images/admin/icon-edit.gif" align="middle"> View/Edit this listing</a></p>
          <?php } ?>
		  </td>		  
		</tr>
        
        
         
       <tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /> <a href="<?php echo $GLOBALS['template_url']; ?>/admin/_invoice.php?id=<?php echo $order->order_id ?>" class="premiumpress_button" style="background:#1687f0;" target="_blank">View Invoice</a></p>


</td>
</tr> 
        
	</table>
	 
	</div>
	
	<div id="premiumpress_tab2" class="content">
	
	
	 
	<table class="maintable" style="background:white;">
	
		<tr class="mainrow">
			<td class="titledesc">Customer </td>
			<td class="forminp">
            <?php if(is_numeric($order->cus_id)){ $user = new WP_User($order->cus_id);  
  ?>
            
				<input type="hidden" class="txt" name="cus_id" value="<?php echo $order->cus_id ?>" style="width:100px;"><b><?php echo $user->user_login; ?></b> - <a class="ico" href="admin.php?page=members&edit=<?php echo $user->ID; ?>" rel="permalink">Edit Member Account</a>
                
                <?php }else{ ?>
                Guest (no user ID saved)
                <?php } ?>
               <br /> <small>IP Address: <?php echo $order->order_ip ?> </small>
			</td>
		</tr>
	
	
	 
		<tr class="mainrow">
			<td class="titledesc">Address</td>
			<td class="forminp">
	
				<textarea style="height:200px; width:500px;"  name="order_address"><?php echo strip_tags(str_replace("<p>","\r\n\r\n",$order->order_address)); ?></textarea>
			</td>
		</tr>
        
        <?php if(strlen($order->order_addressShip) > 1){ ?>
        
 		<tr class="mainrow">
			<td class="titledesc">Shipping Address</td>
			<td class="forminp">
	
				<textarea style="height:200px; width:500px;"  name="order_address"><?php echo strip_tags(str_replace("<p>","\r\n\r\n",$order->order_addressShip)); ?></textarea>
			</td>
		</tr>    
        
        <?php } ?>   
        
		<tr class="mainrow">
			<td class="titledesc">Country</td>
			<td class="forminp">
				<input type="text" class="txt" name="order_country" value="<?php echo $order->order_country ?>" style="width:500px;">
			</td>
	
		</tr>
 
		<tr class="mainrow">
	
			<td class="titledesc">Contact Email</td>
			<td class="forminp">
				<input type="text" class="txt" name="order_email" value="<?php echo $order->order_email ?>" style="width:500px;">
			</td>
		</tr>
        
        
      <tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>  
	 
	</table>
	</div>
	
	
	<div id="premiumpress_tab3" class="content">
    
	<table class="maintable" style="background:white;"> 
    
  		<tr class="mainrow">
		  <td class="titledesc" colspan="4">
		  <b>Order Status</b>
				<select name="order_status" style="font-size:14px; width:300px;">
                <option value="0" <?php if($order->order_status ==0){ echo "selected=selected"; } ?>>Awaiting Payment  </option>
                <option value="3" <?php if($order->order_status ==3){ echo "selected=selected"; } ?>>Paid & Completed  </option>            
                <option value="8" <?php if($order->order_status ==8){ echo "selected=selected"; } ?>>Refunded </option>
                <option value="6" <?php if($order->order_status ==6){ echo "selected=selected"; } ?>>Error/Failed </option>
                
                </select>
		  </td>
		</tr>
        
 
       
 		<tr class="mainrow">
		  <td class="titledesc" colspan="4">
		  <b>Payment/ Test Data</b>
          <p>This field will be populated with any POST data sent back by the payment gateway or inital listing setup, this is used for testing and reference only.</p>
				<textarea style="height:300px; width:760px;" name="payment_data"><?php echo strip_tags(str_replace("<br/>","\r\n\r\n",$order->payment_data)); ?></textarea>
		  
		  </td>		  
		</tr>
        
         
       <tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>         
        
 	</table> 
      
	</div>
	
	
	</div></div>
	
	</form> 
    
  
  <div style="clear:both;"></div>
    
<form method="post" target="_self">
<input name="deleteorder" type="hidden" value="yes" />
	<input type="hidden" name="delo" value="<?php echo $order->autoid ?>">
 	 
	 <table>
     
              
       <tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Delete Order (cannot be undone)" style="color:#fff;<?php if(isset($_POST['delo'])){ echo "display:none;"; } ?>" /></p></td>
</tr>         
        
 	</table> 
    
    </form>
<?php  } } }else{  
























 


if(isset($_GET['ajaxcall'])){
 
 
$path=dirname(realpath($_SERVER['SCRIPT_FILENAME']));
$path_parts = pathinfo($path);
$p = str_replace("wp-content","",$path_parts['dirname']);	
$p = str_replace("themes","",$p);
$p = str_replace("PPT","",$p);

$p = str_replace("directorypress","",$p);
$p = str_replace("auctionpress","",$p);
$p = str_replace("couponpress","",$p);
$p = str_replace("shopperpress","",$p);
$p = str_replace("realtorpress","",$p);
$p = str_replace("shopperpress","",$p);
$p = str_replace("classifiedstheme","",$p);

$p = str_replace("template_","",$p);
$p = str_replace("\\\\","",$p);
$p = str_replace("////","",$p);
 
require( $p.'/wp-config.php' );
	
	// Gets the data
	$id=isset($_POST['id']) ? $_POST['id'] : '';
	$search=isset($_POST['search']) ? $_POST['search'] : '';
	$multiple_search=isset($_POST['multiple_search']) ? $_POST['multiple_search'] : '';
	$items_per_page=isset($_POST['items_per_page']) ? $_POST['items_per_page'] : '';
	$sort=isset($_POST['sort']) ? $_POST['sort'] : '';
	$page=isset($_POST['page']) ? $_POST['page'] : 1;
	//$extra_cols=isset($_POST['extra_cols']) ? $_POST['extra_cols'] : array();

}else{

	// START TO MAKE PAGE OUTPUT
	global $PPT,$wpdb; PremiumPress_Header(); 


	mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."orderdata` (
  `autoid` mediumint(10) NOT NULL AUTO_INCREMENT,
  `cus_id` int(10) NOT NULL DEFAULT  '0',
  `order_id` varchar(50) NOT NULL,
  `order_ip` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `order_data` blob NOT NULL,
  `order_items` varchar(255) NOT NULL,
  `order_address` blob NOT NULL,
  `order_addressShip` blob NOT NULL,
  `order_country` varchar(150) NOT NULL,
  `order_email` varchar(255) NOT NULL,
  `order_total` varchar(10) NOT NULL,
  `order_subtotal` varchar(10) NOT NULL,
  `order_tax` varchar(10) NOT NULL,
  `order_coupon` varchar(10) NOT NULL,
  `order_couponcode` varchar(100) NOT NULL,
  `order_currencycode` varchar(10) NOT NULL,
  `order_shipping` varchar(10) NOT NULL,
  `order_status` int(1) NOT NULL DEFAULT '0',
  `cus_name` varchar(100) NOT NULL,
  `payment_data` blob NOT NULL,
  PRIMARY KEY (`autoid`))");

	// Gets the data
	$search=isset($_GET['ct_search']) ? $_GET['ct_search'] : '';
	$multiple_search=isset($_GET['ct_multiple_search']) ? $_GET['ct_multiple_search'] : array();
	$items_per_page=isset($_GET['ct_items_per_page']) ? $_GET['ct_items_per_page'] : '';
	$sort=isset($_GET['ct_sort']) ? $_GET['ct_sort'] : '';
	$page=isset($_GET['ct_page']) ? $_GET['ct_page'] : 1;

}


include('_creativeTable.php');
$ct=new CreativeTable();

// Data Gathering
if(isset($_GET['cid'])){
	$params['sql_query']				= 'SELECT order_id,order_date,cus_name,order_total,order_status FROM '.$wpdb->prefix.'orderdata WHERE cus_id='.$_GET['cid'];
}elseif(isset($_GET['status']) && is_numeric($_GET['status'])){
$params['sql_query']				= 'SELECT order_id,order_date,cus_name,order_total,order_status FROM '.$wpdb->prefix.'orderdata WHERE order_status='.$_GET['status'];
}else{
	$params['sql_query']				= 'SELECT order_id,order_date,cus_name,order_total,order_status FROM '.$wpdb->prefix.'orderdata';
}
$params['search']					= $search;
$params['multiple_search']			= $multiple_search;
$params['items_per_page']			= $items_per_page;
$params['sort']						= $sort;
$params['page']						= $page;
$params['header']					= 'ORDER ID,DATE,USERNAME,TOTAL,STATUS'; // If you need to use the comma use &#44; instead of ,
$params['width']					= '90,80,60,50,70';
$params['search_init']				 = true;
$params['search_html']				= '<span id="#ID#_search_value">Search Orders...</span><a id="#ID#_advanced_search" href="javascript: ctShowAdvancedSearch(\'#ID#\');" title="Advanced Search">
<img src="../wp-content/themes/'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'/images/admin/advanced_search.png" /></a><div id="#ID#_loader"></div>';
$params['items_per_page_init']	= '10,20,50,100'; // default: '10*$i';

 
 
$ct->table($params);



if(isset($_GET['ajaxcall'])){

	$params['extra_cols']						= $extra_cols;
	$ct->table($params);
	
	$out_ajax=array('items_per_page'=>'', 'body'=>'', 'pager'=>'',);
	if(strpos($_GET['op'],'items_per_page')!==false)
		$out_ajax['items_per_page']=utf8_encode($ct->draw_items_per_page());
	
	if(strpos($_GET['op'],'body')!==false)
		$out_ajax['body']=utf8_encode($ct->draw_body());
	
	if(strpos($_GET['op'],'pager')!==false)
		$out_ajax['pager']=utf8_encode(getCreativePagerLite($page,$ct->total_items,$ct->items_per_page));
	
	echo json_encode($out_ajax);
	die();

}else{

	$ct->pager = getCreativePagerLite($page,$ct->total_items,$ct->items_per_page);
	$out=$ct->display();

}


 
echo TablePageMeta(); ?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_orders.png" align="middle"> Order Management</h3> <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a>	
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">Orders</a></li>
    <li><a rel="premiumpress_tab2" href="#">Invoice Details</a></li>
 
</ul>
</div>
<div id="premiumpress_tab1" class="content"> <fieldset><?php echo $out;?></fieldset></div>

<div id="premiumpress_tab2" class="content">
<form method="post" target="_self" enctype="multipart/form-data">
<input name="submitted" type="hidden" value="yes" />
 
<table class="maintable" style="background:white;">

	<tr class="mainrow">
		<td class="titledesc" valign="top">Invoice Address <br /><small>This is the information that will appear at the top of all your invoices.</small>
        
         
        
        </td>
		<td class="forminp">
 
 <textarea name="adminArray[invoice_address]" type="text" style="width:550px;height:250px;"><?php echo stripslashes(get_option("invoice_address")); ?></textarea>
		
		</td>
	</tr>
<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" /></p></td>
</tr>

</table>
</form>
</div>
<div class="clearfix"></div> 

</div>



<form method="post" target="_self" name="DeleteOrder">
<input name="deleteorder" type="hidden" value="yes" />
<input type="hidden" name="delo" value="0" id="delo">
 	 
 
    
    </form>
    
    <a href="admin.php?page=orders&exportcsv=1">Export to CSV</a>

<?php } ?>