<?php

if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 
 
global $wpdb;
$import_date=time();
$import_date=date('Y-m-d H:i:s',$import_date);

function GetIcodesData($QueryString, $httpRequest="CURL"){

	if($httpRequest == "CURL"){
	
		$ch = curl_init();
		$timeout = 0;  
		curl_setopt ($ch, CURLOPT_URL, $QueryString);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$xml_raw  = curl_exec($ch);
		$xml = simplexml_load_string($xml_raw);
		curl_close($ch);
	
	}
	else{
	
		$xml = simplexml_load_file($QueryString);
	
	}

	return $xml;
}

function BuildQueryString($data){

	global $wpdb;

	$QueryString  = get_option("icodes_country")."?";
	$QueryString .= "UserName=".get_option("icodes_subscription_username");
	$QueryString .= "&SubscriptionID=".get_option("icodes_subscriptionID");
	$QueryString .= "&RequestType=".$data['display_stype'];

	if(strlen($data['keyword']) > 1){
	$QueryString .= "&Query=".$data['keyword'];
	$QueryString .= "&Action=Search";
		if($data['i_category'] !="all"){
			$QueryString .= "&NarrowBy=".$data['i_category'];
		}
	}else{	
		if($data['i_category'] !="all"){
		$QueryString .= "&Action=Category&Query=".$data['i_category'];
		}else{
		$QueryString .= "&Action=All";
		}
	}
	 
	if($data['display_coupons'] !=2){
		$QueryString .= "&Page=".$data['start_page']."&PageSize=10"; 
	}else{	
		$QueryString .= "&Page=".$data['start_page']."&PageSize=50";
	}
 

	return $QueryString;
}

function AddCoupon($cc,$cat=0){

 global $wpdb;
 

	 $dataArray = array('id','title','description','merchant','merchant_logo_url','merchant_id','program_id','voucher_code','excode','affiliate_url','merchant_url','icid','mid','network','deep_link','start_date','expiry_date','category','category_id');
	 
	 foreach($dataArray as $key){	 
	 	$code[$key] 		= str_replace("","",$cc->$key);	 
	 } 
 
	// GIVE THE COUPON AN ID TO REFERENCE ID
	$id = $code['icid'];

 $SQL = "SELECT count($wpdb->postmeta.meta_key) AS total
 FROM $wpdb->postmeta
 WHERE $wpdb->postmeta.meta_key='ID' AND $wpdb->postmeta.meta_value = '".$id."'
 LIMIT 1";	
		 
 $result = mysql_query($SQL);			 
 $array = mysql_fetch_assoc($result);
	
 if($array['total'] == 0){
 
 	// GIVE THE COUPON AN ID TO REFERENCE ID
	$id = $code['icid'];
	
	// COUPON WEBSITE URL STRIP HTTPS
	$dd = str_replace("http://","",str_replace("www.","",$code['merchant_url']));
	$dd1 = explode("/",$dd);
 
  			// CREATE THE CUSTOM TITLE AND DESCRIPTION
	$ctitle = stripslashes(get_option("icodes_custom_title"));
	if($ctitle == ""){
		$CUSTOMTITLE = $code['title'];
	}else{	    
		$CUSTOMTITLE = str_replace("[title]",$code['title'],str_replace("[code]",$code['voucher_code'],str_replace("[merchant]",$code['merchant'],str_replace("[url]",$dd1[0],str_replace("[starts]",date('l jS \of F Y h:i:s A',strtotime($code['start_date'])),str_replace("[ends]",date('l jS \of F Y h:i:s A',strtotime($code['expiry_date'])),$ctitle))))));
	}
	$cdesc = stripslashes(get_option("icodes_custom_desc"));
	if($cdesc == ""){
		$CUSTOMDESC = $code['description'];
	}else{
		$CUSTOMDESC = str_replace("[description]",$code['description'],str_replace("[code]",$code['voucher_code'],str_replace("[merchant]",$code['merchant'],str_replace("[url]",$dd1[0],str_replace("[starts]",date('l jS \of F Y h:i:s A',strtotime($code['start_date'])),str_replace("[ends]",date('l jS \of F Y h:i:s A',strtotime($code['expiry_date'])),$cdesc))))));
		  
	}

			 $my_post = array();			 
			 $my_post['post_title'] 	= $CUSTOMTITLE;
			 $my_post['post_content'] 	= $CUSTOMDESC;
			 $my_post['post_excerpt'] 	= $CUSTOMDESC;
			 
			 //$my_post['post_title'] 	= $code['title'];
			// $my_post['post_content'] 	= $code['description'];
			 //$my_post['post_excerpt'] 	= $code['description'];
			 $my_post['post_author'] 	= 1;
			 $my_post['post_status'] 	= get_option("icodes_import_status");
			 $my_post['post_category']  = array($cat);
			 //$my_post['tags_input'] = $dd1[0];
			 
			 $POSTID = wp_insert_post( $my_post );	  
					 
			 // EXTRA FIELDS
			 add_post_meta($POSTID, "ID", 		str_replace("!!aaqq","",$id));
			 add_post_meta($POSTID, "code", 	$code['voucher_code']);
			 add_post_meta($POSTID, "url", 		$code['merchant_url']);	  
			 add_post_meta($POSTID, "hits", 	"0");
			 add_post_meta($POSTID, "link", 	$code['affiliate_url']);	
			 add_post_meta($POSTID, "image", 	$code['merchant_logo_url']);
			 
			 add_post_meta($POSTID, "type", 	"coupon");
			 
			 add_post_meta($POSTID, "starts", 	$code['start_date']);
			 add_post_meta($POSTID, "expires", $code['expiry_date']);
			   add_post_meta($POSTID, "featured", "no");	

echo "<div style='margin-left:10px; padding:8px; font-size:13px; border: 1px dashed #ddd; background:#e2ffd9; margin-right:30px;'>
				<img src='../wp-content/themes/couponpress/images/accept.png' align='middle'/> ".$cc->network." - ".$cc->title."</div><br>";

 }else{

echo "<div style='margin-left:10px; padding:8px; font-size:13px; border: 1px dashed #ddd; background:#ffe5e9; margin-right:30px;'>
				<b>DUPLICATE FOUND - NOT SAVED - </b> ".$cc->network." - ".$cc->title."</div><br>";

 }
 return $POSTID;
}



if(get_option("icodes_subscriptionID") =="" || strlen(get_option("icodes_subscriptionID")) < 5 ){ die("<h1> iCodes Subscription Key Missing</h1><p>You need to enter your subsscription key into the configuration settings</p>"); }
 



/* MASS IMPORT TOOLS FOR CATEGORY INTEGRATION */

if($_POST['display_coupons'] == "2"){
	$counter = 0; if(isset($_POST['total_items'])){ $total_items = $_POST['total_items'];}else{ $total_items =0; }

	foreach($_POST['masscat'] as $key){ 

		if(is_numeric($key['enable']) ){
			
			$_POST['i_category'] = $key['cat'];
			$string = BuildQueryString($_POST);
			$xml = GetIcodesData($string);
			$total_items += trim($xml->Results);
			$message1 = trim($xml->Message);
			if($message1==''){
				foreach ($xml->item as $item) {
					AddCoupon($item,$key['enable']);
					$counter++;
				}
			}	
	
		}
	}

}else{
/* ------------------------------------------ */



 
$run=0;
$cats = "";
if(is_array($_POST['cat'])){
foreach($_POST['cat'] as $cat){			
	$cats .= $cat.",";
}}else{
$cats = 1;
}
$string = BuildQueryString($_POST);
$xml = GetIcodesData($string);
$total_items = trim($xml->Results);
$message1 = trim($xml->Message);
 
if($message1 !=''){

	print "<h1>".$message1."</h1> <p> <a href='admin.php?page=import'>Click here to perform a new search.</a> </p>";

}else{

?>
 
<div id="ShopperPressAlert"tyle="font-size:16px; font-weight:bold;"></div>
<div style="padding:8px; font-size:16px;">Total Found: <?php echo $total_items; ?> - Page <?php echo $_POST['start_page']; ?> of <?php echo round($total_items/10); ?></div>
<div style="width:100%; height:500px; border:2px solid #ddd; overflow:auto; background:#fff;">


<?php
	
	foreach ($xml->item as $item) {
	
		$id = $item->id;
		if($id==""){
			$id = $item->icid;
		} 
		$network = $item->network;
		$merchant = $item->merchant;
		$merchant_logo_url = $item->merchant_logo_url;
		$merchant_url = $item->merchant_url;
		$affiliate_url = $item->affiliate_url;
		$category = $item->category;	
		$mid = $item->mid;
		$title = $item->title;
		$description = $item->description;	 
		$voucher_code = $item->voucher_code;		
		$excode = $item->excode;
		$start_date = $item->start_date;
		$expiry_date = $item->expiry_date;

		if($_POST['display_stype'] == "Offers"){
		$type = "offer";
		}elseif($_POST['display_stype'] =="PrintableVouchers"){
		$type = "print";
		}else{
		$type = "code";
		}

		
		?>
<div style="clear:both; border-bottom:1px dashed #666;">
<img src="<?php echo $merchant_logo_url; ?>" style="float:left; margin-left:20px; padding-right:20px; padding-bottom:120px; margin-top:30px;">
<h2 style="font-size:16px;"><?php echo $title; ?> - <?php echo $id; ?></h2>
<p><?php echo $description; ?></p>
<p style="background:#efefef; border:1px solid #dddddd; padding:3px; margin-left:12%; margin-right:50px;"><b><small> <?php if($_POST['display_stype'] == "Codes"){ ?>Code: <?php echo $voucher_code; ?>  -<?php } ?> starts: <?php echo $start_date; ?> // ends: <?php echo $expiry_date; ?> </small></b></p>
<p>
[<a href='javascript:void(0);' onClick='addiCodes("<?php echo $cats; ?>","<?php echo str_replace("'","",$title); ?>","<?php echo str_replace("'","",$description); ?>","<?php echo str_replace("&","**",$affiliate_url); ?>","<?php echo $voucher_code; ?>","<?php echo $expiry_date; ?>","<?php echo $merchant_url; ?>","<?php echo $type; ?>","<?php echo $_POST['import_status']; ?>","<?php echo str_replace("'","",$merchant_logo_url); ?>","<?php echo $start_date; ?>","<?php echo $merchant; ?>");'>Add Product</a>] 
[ <a href="<?php echo $affiliate_url; ?>" target="_blank">View Product</a>]
[ <a href="<?php echo $merchant_url; ?>" target="_blank">View Merchant</a>]
</p>
</div>
<?php }} ?>

</div>

<?php } ?>


<?php if($_POST['display_coupons'] != 2){?>

       <div style="background:#eee; border:1px solid #ddd; padding:10px; margin-top:10px; font-size:16px;">
        <div style="float:left; width:<?php if($_POST['start_page'] > 1){ ?>78%<?php }else{ ?>90%<?php } ?>; text-align:left"> <a href="admin.php?page=import">[New Search]</a></div>
        
        <div style="float:left; text-align:right;"> 
        <?php if($_POST['start_page'] > 1){ ?><a href="javascript:void(0);" onClick="gobackpage(<?php echo $_POST['start_page']; ?>)">Previous Page</a> - <?php } ?>   <a href="#" onClick="document.subform.submit();">Next Page</a> </div>
 
	<div style="clear:both;"></div></div>
    
    <?php }else{ 

	$totalSoFar = $counter*$_POST['start_page'];
	?>
    
    
        <div style="background:#eee; border:1px solid #ddd; padding:10px; margin-top:10px; font-size:16px;">
			<a href="admin.php?page=import">[New Search]</a> - 
			<b>Number of coupons added:</b>		
			
			<?php 

			if($totalSoFar > $total_items){ echo $total_items; }else{ echo $totalSoFar . " of ". $total_items; } 

			if($totalSoFar < $total_items){ ?>
             - <a href="#" onClick="document.subform.submit();">Next Page</a>

			<?php } ?>
		</div>  
    
 
    <?php } ?>





    <script>
	
	function gobackpage(page){

	document.getElementById('start_page').value = page -1;
	document.subform.submit();
	
	}
	
	</script> 
        
    <form method="post" target="_self" id="subform" name="subform">			
    
 	<input type="hidden" name="total_items" value="<?php echo $total_items; ?>">
    <?php
 
	foreach($_POST as $key=>$val){
	
		if(is_array($val)){
		
			foreach($val as $key=>$val1){
				 print '<input type="hidden" name="'.$key.'" value="'.$val1.'">';			 
			}
		
		
		}else{

			if($key == "start_page"){	
				if($val ==""){ $val=2; }else{ $val++; }	
				print '<input type="hidden" name="'.$key.'" value="'.$val.'" id="start_page">';	
			}else{	
				print '<input type="hidden" name="'.$key.'" value="'.$val.'">';
			}
		}

	}
	
	if(is_array($_POST['cat'])){ foreach($_POST['cat'] as $cat){			
		print '<input type="hidden" name="cat[]" value="'.$cat.'">';	
	} }
 
		if(is_array($_POST['masscat'])){ $i=0; foreach($_POST['masscat'] as $cat){			
		print '<input type="hidden" name="masscat['.$i.'][enable]" value="'.$cat['enable'].'">';	
		print '<input type="hidden" name="masscat['.$i.'][cat]" value="'.$cat['cat'].'">';	
		$i++;
	} }
	?>
	
    </form>