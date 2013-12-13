<?php

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

function makeRandomCode($num) { 
		  	$pass="";		
			  $i = 0; 
			  while ($i <= $num) {					
					$pass = $pass . rand(1, 100); 
					$i++; 
			  } 
			  return $pass; 
}

function AddCoupon($cc,$cat=0,$url=""){

 global $wpdb;
 
 $type = "code"; 
 


 $url = str_replace("ww12.","www.",$url);


 $id = (string)$cc->couponid;
 $endDate = explode(" ",(string)$cc->enddate); 

 
 $SQL = "SELECT count($wpdb->postmeta.meta_key) AS total
 FROM $wpdb->postmeta
 WHERE $wpdb->postmeta.meta_key='ID' AND $wpdb->postmeta.meta_value = '".$id."'
 LIMIT 1";	
		 
 $result = mysql_query($SQL);			 
 $array = mysql_fetch_assoc($result);


if($_POST['forme_codemaker'] == "yes" && $cc->couponcode == ""){
$cc->couponcode = makeRandomCode(5);
}
	
 if($array['total'] == 0){

//$cc->categories->category

	if($cc->status =="active" && $cc->couponcode != "" ){ /* ONLY ADD ACTIVE COUPONS */

	 $my_post = array();
	 $my_post['post_title'] 	= (string)$cc->merchantname;
	 $my_post['post_content'] 	= (string)$cc->label;
	 $my_post['post_excerpt'] 	= (string)$cc->label;
	 $my_post['post_author'] 	= 1;
	 $my_post['post_status'] 	= $_POST['import_status'];
	 $my_post['post_category'] = array($cat);
	 $my_post['tags_input'] = $dd1[0].",".(string)$cc->merchantname;
	 $POSTID = wp_insert_post( $my_post );	  
			 
	 // EXTRA FIELDS
	 add_post_meta($POSTID, "ID", $id);
	 add_post_meta($POSTID, "code", "".(string)$cc->couponcode);
	 add_post_meta($POSTID, "expires", $endDate[0]);
	 add_post_meta($POSTID, "url", "".$url);	  
	 add_post_meta($POSTID, "hits", "1");
	 add_post_meta($POSTID, "link", "".(string)$cc->link);	
 	add_post_meta($POSTID, "network", "".(string)$cc->network);	
	 add_post_meta($POSTID, "logo", "".(string)$cc->merchant_logo_url);	
 	add_post_meta($POSTID, "type", "coupon");	
 add_post_meta($POSTID, "featured", "no");
 
	echo "<div style='margin-left:10px; padding:8px; font-size:13px; border: 1px dashed #ddd; background:#e2ffd9; margin-right:30px;'>
				<img src='../wp-content/themes/couponpress/images/accept.png' align='middle'/> ".$cc->merchantname." - ".$cc->label." - CODE: ".$cc->couponcode."</div><br>";

	return "yes";

	}elseif($cc->couponcode == ""){

		echo "<div style='margin-left:10px; padding:8px; font-size:13px; border: 1px dashed #1923a7; background:#1923a7; margin-right:30px; color:white;'>
				<b>COUPON CODE MISSING - NOT SAVED - </b> ".$cc->merchantname." - ".$cc->label." - CODE: ".$cc->couponcode."</div><br>";
 
	}elseif($cc->status =="expired"){

		echo "<div style='margin-left:10px; padding:8px; font-size:13px; border: 1px dashed #ed9c00; background:#ffc000; margin-right:30px; color:white;'>
				<b>EXPIRED COUPON - NOT SAVED - </b> ".$cc->merchantname." - ".$cc->label."</div><br>";
 
	}

 }else{

	if($cc->status =="deleted"){
		/* MAYBE WE CAN CHECK THIS COUPON WASNT ACTIVE */
	}

		echo "<div style='margin-left:10px; padding:8px; font-size:13px; border: 1px dashed #ddd; background:#ffe5e9; margin-right:30px;'>
				<b>DUPLICATE FOUND - NOT SAVED - </b> ".$cc->merchantname." - ".$cc->label."</div><br>";

 }
 
  
 
 return false;
}





if(isset($_POST['formetocoupon_merchant'])){
 
	$MyMerchantArray = array(); $i=0;
	$xml = GetIcodesData("http://www.formetocoupon.com/services/getMerchants?key=".$_POST['forme_key']);//.
	 
	if(isset($xml->error)){
 
	print '<div class="msg msg-error"><p>'.$xml->error.'</div>';
	

	}else{
	
		foreach ($xml->merchant as $mm) {

			$MyMerchantArray[$i]['id'] 		= (string)$mm->id[0];
			$MyMerchantArray[$i]['name'] 	= (string)$mm->name[0];
			$MyMerchantArray[$i]['net'] 	= (string)$mm->network[0];
			$MyMerchantArray[$i]['url'] 	= (string)$mm->homepageurl[0];
			$i++;
 
		}

		update_option("forme_merchantlist",$MyMerchantArray);
		update_option("forme_key",$_POST['forme_key']);	
		print '<div class="msg msg-info"><p>Merchant List Updated</div>';
	
	}



}else{

	$counter = 0;
	$QuertyString = "";
	if(isset($_POST['forme_country']) && $_POST['forme_country'] != '0'){
		$QuertyString .= "&country=".$_POST['forme_country'];
	}
	if(isset($_POST['forme_deal']) && $_POST['forme_deal'] != '0'){	
		$QuertyString .= "&dealtype=".$_POST['forme_deal'];
	}
	
	if(isset($_POST['forme_incremental']) && $_POST['forme_incremental'] == 'yes'){	
		$QuertyString .= "&incremental=1";
	}
 
	foreach($_POST['masscat'] as $key){ 

		if(is_numeric($key['enable']) ){
 
			$ff = explode("**",$key['cat']);

			$xml = GetIcodesData("http://www.formetocoupon.com/services/getDeals?key=".trim(get_option("forme_key"))."&merchantids=".$ff[0].$QuertyString);
			//die("http://www.formetocoupon.com/services/getDeals?key=".get_option("forme_key")."&merchantids=".$ff[0].$QuertyString.print_r($xml));
			foreach ($xml->item as $item) { 

				$success = AddCoupon($item,$key['enable'],$ff[1]);
				if($success =="yes"){
					$counter++;
				}else{
					
				}				
			}
		}
	}

	print '<div class="msg msg-info"><p>You have imported '.$counter.' coupons</div><br /><br />[ <a href="admin.php?page=import">NEW SEARCH</a> ]';

}
 