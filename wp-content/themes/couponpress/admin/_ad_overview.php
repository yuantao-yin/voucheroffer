
<?php if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } global $wpdb; global $PPT;  PremiumPress_Header();
function premiumpress_monthlysales($type=0){

global $wpdb;

$string="";	$v_array=array();
for($i=0;$i!=13;$i++){
$val=0;
$SearchDate = date("Y-m-d",mktime(0, 0, 0, date("m")-$i  , date("d"), date("Y")));
if($type ==0){
				$SQL = "SELECT sum(order_total) AS total FROM ".$wpdb->prefix."orderdata WHERE order_date LIKE '%".substr($SearchDate,0,-3)."%'";
				}elseif($type ==1){
				$SQL = "SELECT count(ID) AS total FROM $wpdb->posts WHERE post_date LIKE '%".substr($SearchDate,0,-3)."%' AND post_type='post' AND post_status='publish'";
				}
				$order_total = $wpdb->get_row($SQL);
				$month = substr($SearchDate,5,2);
				switch($month){
				case "01": { $ThisMonth = "Jan"; } break;
				case "02": { $ThisMonth = "Feb"; } break;
				case "03": { $ThisMonth = "Mar"; } break;
				case "04": { $ThisMonth = "Apr"; } break;
				case "05": { $ThisMonth = "May"; } break;
				case "06": { $ThisMonth = "Jun"; } break;
				case "07": { $ThisMonth = "Jul"; } break;
				case "08": { $ThisMonth = "Aug"; } break;
				case "09": { $ThisMonth = "Sep"; } break;
				case "10": { $ThisMonth = "Oct"; } break;
				case "11": { $ThisMonth = "Nov"; } break;
				case "12": { $ThisMonth = "Dec"; } break;
				}
		 	  
				if($order_total->total == ""){
					$val = 0;
				}else{
					$val = $order_total->total;
				}
				$v_array[] = $ThisMonth."--".$val."**";
		}
		$string="";
		foreach($v_array as $value){			
			$string .= $value;		
		}			 	
		$string = substr($string,0,-1);
		return $string;

}

$CURRENCYCODE = get_option('currency_code');
$CURRENCYPOST = get_option('display_currency_position');

  ?>

 
<SCRIPT type="text/javascript" src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/_flash.js"></SCRIPT>
 


<div class="premiumpress_box altbox" style="width:500px;"><div class="premiumpress_boxin">
<div class="header">
<h3><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } } ?>/images/premiumpress/h-ico/dashboard.png" align="middle"> Website Summary</h3>							 
</div><div class="content">
<table cellspacing="0"><thead><tr><th></th><td></td><td></td></tr>
</thead><tbody>
<?php 
$count_posts 	= wp_count_posts(); 
$count_pages 	= wp_count_posts('page');
$comments 		= $wpdb->get_row("SELECT count(*) as count FROM $wpdb->comments");
$articles 		= $wpdb->get_row("SELECT count(*) AS count FROM $wpdb->posts WHERE post_type='article_type'");
$order_total 	= $wpdb->get_row("SELECT sum(order_total) AS total FROM ".$wpdb->prefix."orderdata");
wp_reset_query();

if(PREMIUMPRESS_SYSTEM == "ShopperPress"){

$txt['label1'] = "Total Store Products";
$txt['label2'] = "Live Products";
$txt['label3'] = "Pending/Draft Products";
$txt['label4'] = "Deleted Products";
$txt['label5'] = "Monthly Sales";


}elseif(PREMIUMPRESS_SYSTEM == "DirectoryPress"){

$txt['label1'] = "Total Directory Listings";
$txt['label2'] = "Live Websites";
$txt['label3'] = "Pending/Draft Websites";
$txt['label4'] = "Deleted Websites";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "CouponPress"){

$txt['label1'] = "Total Website Coupons";
$txt['label2'] = "Live Coupons";
$txt['label3'] = "Pending/Draft Coupons";
$txt['label4'] = "Deleted Coupons";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "ClassifiedsTheme"){

$txt['label1'] = "Total Website Classifieds";
$txt['label2'] = "Live Classifieds";
$txt['label3'] = "Pending/Draft Classifieds";
$txt['label4'] = "Deleted Classifieds";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "RealtorPress"){

$txt['label1'] = "Total Real Estate";
$txt['label2'] = "Live Real Estate";
$txt['label3'] = "Pending/Draft Property";
$txt['label4'] = "Deleted Real Estate";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "AuctionPress"){

$txt['label1'] = "Total Auctions";
$txt['label2'] = "Live Auctions";
$txt['label3'] = "Pending/Draft Auctions";
$txt['label4'] = "Deleted Auctions";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "MoviePress"){

$txt['label1'] = "Total Videos";
$txt['label2'] = "Live Videos";
$txt['label3'] = "Pending/Draft Videos";
$txt['label4'] = "Deleted Videos";
$txt['label5'] = "New Each Month";

}
?>
 
<tr class="first">
<th><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/monitor.png" align="middle"> <?php echo $txt['label1']; ?></th>
<td class="tc"><a class="ico-comms" href="edit.php"><?php echo $count_posts->publish+$count_posts->draft+$count_posts->pending+$count_posts->trash; ?></a></td>
<td><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/find.png" align="middle"> <a href="edit.php">View</a> </td>
</tr>

<tr class="first">
<th>--- <?php echo $txt['label2']; ?></th>
<td class="tc"><a class="ico-comms" href="edit.php"><?php echo $count_posts->publish; ?></a></td>
<td><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/find.png" align="middle"> <a href="edit.php">View</a> </td>
</tr>

<tr class="first">
<th>--- <?php echo $txt['label3']; ?></th>
<td class="tc"><a class="ico-comms" href="edit.php?post_status=pending"><?php echo $count_posts->draft+$count_posts->pending; ?></a></td>
<td><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/find.png" align="middle"> <a href="edit.php?post_status=pending">View</a> </td>
</tr>

<tr class="first">
<th>--- <?php echo $txt['label4']; ?></th>
<td class="tc"><a class="ico-comms" href="edit.php?post_status=trash"><?php echo $count_posts->trash; ?></a></td>
<td><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/find.png" align="middle"> <a href="edit.php?post_status=trash">View</a> </td>
</tr>


<tr class="first">
<th><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/note.png" align="middle"> Total Articles</th>
<td class="tc"><a class="ico-comms" href="edit.php?post_type=article_type"><?php echo $articles->count; ?></a></td>
<td><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/find.png" align="middle">  <a href="edit.php?post_type=article_type">View</a></td>
</tr>

<tr class="first">
<th><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/page.png" align="middle"> Total Pages</th>
<td class="tc"><a class="ico-comms" href="edit-pages.php"><?php echo $count_pages->publish; ?></a></td>
<td><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/find.png" align="middle">  <a href="edit-pages.php">View</a></td>
</tr>

<tr class="first">
<th><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/comment.png" align="middle"> Total Comments</th>
<td class="tc"><a class="ico-comms" href="edit-comments.php?comment_status=all"><?php echo $comments->count; ?></a></td> 
<td><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/find.png" align="middle"> <a href="edit-comments.php?comment_status=all">View</a></td>
</tr>

<tr><td colspan="3">

<form class="fields">
<fieldset class="last">
<legend><strong>Sales Summary </strong></legend>
Order Total: <?php if($order_total->total ==""){ $oT = "0"; }else{ $oT = $order_total->total;} echo $PPT->Price($oT,$CURRENCYCODE,$CURRENCYPOST,1,true);   ?>
</fieldset></form>

</td></tr>								
									 
</tbody></table></div></div></div>
<div class="premiumpress_box" style="width:330px;"><div class="premiumpress_boxin">
<div class="header"><h3><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/h-ico/monitor.png" align="middle" > <?php echo $txt['label5']; ?></h3>
</div><div class="content"><ul class="simple">				 
<SCRIPT language="JavaScript">
	DisplayPremiumPress("<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/admin/charts/chart.swf?xml_file=<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/admin/charts/data.php?d=<?php if(PREMIUMPRESS_SYSTEM == "ShopperPress"){ 
echo premiumpress_monthlysales();
}else{ 
echo premiumpress_monthlysales(1); } ?>","330","365",{menu:"false",bgcolor:"#ffffff",version:"6,0,47,0",align:"middle", zindex:"0",wmode:"transparent"});
</SCRIPT>
</ul></div></div></div>
<div class="clearfix"></div>

<?php
if(!isset($extraSearch)){ $extraSearch=""; }
$checkbox=0;
$SQL = "SELECT count(*) AS total FROM ".$wpdb->prefix."orderdata LEFT JOIN $wpdb->users ON ($wpdb->users.ID = ".$wpdb->prefix."orderdata.cus_id)  ".$extraSearch." "; 
$results = mysql_query($SQL, $wpdb->dbh);
if(!empty($results)){

?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header"><h3><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/h-ico/smiley.png" align="middle"> Recent Orders</h3><a class="premiumpress_button" href="admin.php?page=orders">View All Orders</a></div><div id="premiumpress_tab1" class="content">
 
<table cellspacing="0"><thead><tr><td class="tc"><input type="checkbox" id="data-1-check-all" name="data-1-check-all" value="true" onClick="da(2);return false;" /></td>
<td class="tc">Amount</td><th>Order ID / Status</th><td>Description</td><td class="tc">Date</td><td class="tc">Actions</td></tr></thead><tfoot><tr><td colspan="6"></td></tr></tfoot><tbody>
<?php

 

$values = mysql_fetch_assoc($results);

$SQL = str_replace("count(*) AS total","*",$SQL). " GROUP BY order_id LIMIT 10";
$posts = mysql_query($SQL, $wpdb->dbh);
if ($posts && mysql_num_rows($posts) > 0) {
 
while ($order = mysql_fetch_object($posts)) {

	$totalPro =0;
	$totalQty =0;
	$OD = explode(",",$order->order_items);
	foreach($OD as $val){
		$val1 = explode("x",$val); 
		$totalPro ++;
		$totalQty += $val1[1];
	}
	


?>

<tr class="first">
<td class="tc">
<input type="checkbox" value="on" name="d<?php echo $checkbox; ?>" id="d<?php echo $checkbox; ?>"/>
<input type="hidden" name="d<?php echo $checkbox; ?>-id" value="<?php echo $order->autoid ?>">
</td>
<td class="tc"><span class="tag tag-gray">

<?php 

echo $PPT->Price($order->order_total,$CURRENCYCODE,$CURRENCYPOST,1);
?>
 

</span></td>
<th>
<a class="row-title" href="admin.php?page=orders&id=<?php echo $order->order_id; ?>">  <?php echo $order->order_id ?></a><br />
<?php if($order->order_status ==0){ ?>
Awaiting Payment
<?php }elseif($order->order_status ==3){ ?>
Paid & Completed 
<?php }elseif($order->order_status ==5){ ?>
<b style="color:green;">Payment Received</b>
<?php }elseif($order->order_status ==6){ ?>
<b style="color:red;">Payment Failed</b>
<?php }elseif($order->order_status ==7){ ?>
<b style="color:blue;">Payment Pending</b>
<?php }elseif($order->order_status ==8){ ?>
<b>Payment Refunded</b>
<?php } ?>
</th>
<td>
<?php echo substr(strip_tags(str_replace("<br/>","\r\n\r\n",$order->order_data)),0,100); ?>..
<a href="admin.php?page=orders&delo=<?php echo $order->autoid ?>" style="font-size:11px">full details</a> 
<small>

<?php if(strlen($order->user_nicename) > 1){ ?><br />Ordered By <a href="admin.php?page=members&edit=<?php echo $order->cus_id ?>"><?php echo $order->user_nicename  ?></a><?php } ?> </small></p>

</td>
<td class="tc"><?php echo $order->order_date ?> <br> <small>@ <?php echo $order->order_time ?></small></td>
<td class="tc">

<ul class="actions">

<li><a class="ico" href="admin.php?page=orders&id=<?php echo $order->order_id; ?>" rel="permalink"><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/images/premiumpress/led-ico/pencil.png" alt="edit" /></a></li>

</ul>


</td></tr></td></tr></tbody>
<?php $checkbox++; } } ?>

</table>
<div class="pagination">
<ul>
<li>Showing <?php echo $checkbox; ?> of <?php echo $values['total']; ?> orders</li>
</ul>
</div>
</div>                  
</div>

<?php } ?>

</div>
<div class="clearfix"></div> 