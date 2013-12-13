<?php
 
if(isset($_GET['id'])){

	$te = explode("themes",$_SERVER['SCRIPT_FILENAME']);
	$tf = explode("admin",trim($te[1])); 
	$themeName = str_replace("\\","",str_replace("\\\\","",str_replace("/","",str_replace("////","",$tf[0]))));

	$path=dirname(realpath($_SERVER['SCRIPT_FILENAME']));
	$path_parts = pathinfo($path);
	$p = str_replace("wp-content","",$path_parts['dirname']);	
	$p = str_replace("themes","",$p);
	$p = str_replace("functions","",$p);
	$p = str_replace($themeName,"",$p);
	$p = str_replace("\\\\","",$p);
	$p = str_replace("////","",$p);
			 
	require( $p.'/wp-config.php' );


	$currency_symbol = get_option("currency_code");
	$order   = array("\r\n", "\n", "\r");
	$replace = '<br />'; 
	$SQL = "SELECT * FROM ".$wpdb->prefix."orderdata LEFT JOIN $wpdb->users ON ($wpdb->users.ID = ".$wpdb->prefix."orderdata.cus_id)  WHERE ".$wpdb->prefix."orderdata.order_id = ('".strip_tags(PPTCLEAN($_GET['id']))."') GROUP BY order_id LIMIT 1"; 
	$posts = mysql_query($SQL, $wpdb->dbh) or die(mysql_error().' on line: '.__LINE__);
	while ($order = mysql_fetch_object($posts)) {
	
	$product_array = explode(",",$order->order_items); $tt=0;
 
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Invoice</title>
<style>
body {
	background: #FFFFFF;
}
body, td, th, input, select, textarea, option, optgroup {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
}
h1 {
	text-transform: uppercase;
	color: #CCCCCC;
	text-align: right;
	font-size: 24px;
	font-weight: normal;
	padding-bottom: 5px;
	margin-top: 0px;
	margin-bottom: 15px;
	border-bottom: 1px solid #CDDDDD;
}
.div1 {
	width: 100%;
	margin-bottom: 20px;
}
.div2 {
	float: left;
	display: inline-block;
}
.div3 {
	float: right;
	display: inline-block;
	padding: 5px;
}
.heading td {
	background: #E7EFEF;
}
.address, .product {
	border-collapse: collapse;
}
.address {
	width: 100%;
	margin-bottom: 20px;
	border-top: 1px solid #CDDDDD;
	border-right: 1px solid #CDDDDD;
}
.address th, .address td {
	border-left: 1px solid #CDDDDD;
	border-bottom: 1px solid #CDDDDD;
	padding: 5px;
}
.address td {
	width: 50%;
}
.product {
	width: 100%;
	margin-bottom: 20px;
	border-top: 1px solid #CDDDDD;
	border-right: 1px solid #CDDDDD;
}
.product td {
	border-left: 1px solid #CDDDDD;
	border-bottom: 1px solid #CDDDDD;
	padding: 5px;
}
</style>
</head>

<body>
<div style="page-break-after: always;"> 
  <h1>Invoice</h1> 
  <div class="div1"> 
    <table width="100%"> 
      <tr> 
        <td><?php echo stripslashes(get_option("invoice_address")); ?></td> 
        <td align="right" valign="top"><table> 
            <tr> 
              <td><b>Invoice Date:</b></td> 
              <td><?php echo $order->order_date; ?></td> 
            </tr><tr> 
              <td><b>Order ID:</b></td> 
              <td><?php echo $order->order_id; ?></td> 
            </tr> 
          </table></td> 
      </tr> 
    </table> 
  </div> 
  <table class="address"> 
    <tr class="heading"> 
      <td width="50%"><b>To</b></td> 
       
    </tr> 
    <tr> 
      <td> 
       <?php echo str_replace("\r\n\r\n","<p>",$order->order_address); ?>    
        
        
         </td> 
 
    </tr> 
  </table> 
  <table class="product"> 
  
    <tr class="heading"> 
      <td><b>Product</b></td> 
      <td><b>Model</b></td> 
      <td align="right"><b>Quantity</b></td> 
      <td align="right"><b>Unit Price</b></td> 
      <td align="right"><b>Total</b></td> 
    </tr> 
    
    <?php foreach($product_array as $PID){ 
		$aa = explode("x",$PID);
		$bb = explode("-",$aa[1]);
		 
		if(is_numeric($aa[0])){ $pdata = get_post($aa[0]);
		$price = get_post_meta($aa[0], "price", true);
		
	?>
    <tr> 
      <td><b><?php echo $pdata->post_title;?></b><br><?php  foreach($aa as $val){ echo str_replace("-","<br>",str_replace($bb[0],"",str_replace($aa[0],"",$val))); }  ?> </td> 
      <td><?php echo $aa[0]; ?></td> 
      <td align="right"><?php echo $bb[0]; ?></td> 
      <td align="right"><?php echo $price; ?></td> 
      <td align="right"><?php $tt += $price*$aa[1]; echo $PPT->Price($price*$aa[1],$currency_symbol,$GLOBALS['premiumpress']['currency_position'],1); ?></td> 
    </tr> 
    
    <?php }} ?>
    


     <tr> 
      <td align="right" colspan="4"><b>Sub Total:</b></td> 
      <td align="right"><?php echo $PPT->Price($order->order_subtotal,$currency_symbol,$GLOBALS['premiumpress']['currency_position'],1); ?></td> 
    </tr> 
<?php if($order->order_shipping > 0){ ?>     
     <tr> 
      <td align="right" colspan="4"><b>Shipping:</b></td> 
      <td align="right"><?php echo $PPT->Price($order->order_shipping,$currency_symbol,$GLOBALS['premiumpress']['currency_position'],1); ?></td> 
    </tr>
<?php } ?>    
<?php if($order->order_tax > 0){ ?>    
     <tr> 
      <td align="right" colspan="4"><b>Tax:</b></td> 
      <td align="right"><?php echo $PPT->Price($order->order_tax,$currency_symbol,$GLOBALS['premiumpress']['currency_position'],1); ?></td> 
    </tr>  
<?php } ?>
<?php if($order->order_coupon > 0){ ?>
     <tr> 
      <td align="right" colspan="4"><b>Coupon/Discounts:</b></td> 
      <td align="right"><?php echo $PPT->Price($order->order_coupon,$currency_symbol,$GLOBALS['premiumpress']['currency_position'],1); ?></td> 
    </tr>  
<?php } ?>              
     <tr> 
      <td align="right" colspan="4"><b>Total:</b></td> 
      <td align="right"><?php echo $PPT->Price($order->order_total,$currency_symbol,$GLOBALS['premiumpress']['currency_position'],1); ?></td> 
    </tr> 
      </table> 
      

</div> 
</body>
</html>
<?php 	} } ?>