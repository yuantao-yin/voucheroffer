<?php global $PPT, $PPTImport; PremiumPress_Header(); 

if(isset($_POST['icodes_quicksetup_import']) && $_POST['icodes_quicksetup_import'] == 1){
update_option('icodesBasicImport1','');
update_option('icodesBasicImport2','');
update_option('icodesBasicImport3','');
}

if(isset($_GET['deleteallsaved']) && $_GET['deleteallsaved'] ==1){
update_option('icodes_savelist','');
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "iCodes Scheduled Search Deleted Successfully";
}

function CouponPress_Admin_Cats(){


	$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
	$Maincatcount = count($Maincategories);	
	
	foreach ($Maincategories as $Maincat) {
		 
		if($Maincat->parent ==0){
		
			print '<input name="cat[]" type="checkbox" value="'.$Maincat->cat_ID.'">' . $Maincat->cat_name."<br />";
			
			// do sub cats
			$currentcat=get_query_var('cat');
			$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0');
			$catcount = count($categories);		
			$i=1;
				
			if(count($categories) > 0){
				 
				foreach ($categories as $cat) {	
				
					print ' -- <input name="cat[]" type="checkbox" value="'.$cat->cat_ID.'">' . $cat->cat_name . "<br />";
				
				}
			
			}
		
		}	
	}
}
?>


<?php


if(isset($_POST['startsearch'])){
  
	include("_icode_results.php");

}elseif(isset($_POST['formetocoupon'])){

//update_option("FMTCSave",$_POST['masscat']);

		include("_forme_results.php");	

}


if(!isset($GLOBALS['startsearch'])){

?>

<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100" <?php if(isset($_POST['startsearch']) || isset($_POST['formetocoupon']) ){ echo 'style="display:none;"'; } ?>><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/h-ico/refresh.png" align="middle"> Import Coupons</h3> <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a> 							 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">iCodes Search</a></li>
 
	 <li><a rel="premiumpress_tab3" href="#">ForMeToCoupon</a></li> 
	 
</ul>
</div>




<?php

function GetDataMe($QueryString, $httpRequest="CURL"){
 
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

if(isset($_GET['fdo']) && $_GET['fdo'] == "1"){

	ob_implicit_flush();

	$ctype = get_option('icodes_importtype');  $loopCounter=0;
	
	print "<div style='padding:10px; background:#dcffe1; border:1px solid #43a751; margin-right:20px; margin-left:20px; margin-top:30px;'>";
	
	print "<h2>Connecting to your ForMeToAccount account.....</h2>";
	
	echo "<h3>Creating a categrory for each of your formetocoupon merchants</h3>";
	
	$MyMerchantArray = array(); $i=0; $ExistingMerchantList = get_option('forme_merchantlist');
	
	if(is_array($ExistingMerchantList)){ 
	
		$LoopList = $ExistingMerchantList;
	
	}else{
	
		$xml = GetDataMe("http://www.formetocoupon.com/services/getMerchants?key=".get_option('forme_key'));
		
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
			print '<div class="msg msg-info"><p>Merchant List Updated</div>';
			$LoopList = get_option('forme_merchantlist');
		}
	
	}
	
	if(is_array($LoopList)){
	
		$i=1;
		foreach ( $LoopList as $mm) {
		
		// [id] => 6 [name] => 1-800 Contacts [net] => CJ [url] => http://www.1800contacts.com/ ) 1
	
			$MyMerchantArray[$i]['id'] 		= $mm['id'];
			$MyMerchantArray[$i]['name'] 	= $mm['name'];
			$MyMerchantArray[$i]['net'] 	= $mm['net'];
			$MyMerchantArray[$i]['url'] 	= $mm['url'];
		 
			 print '<div class="msg msg-info"><p>Found Merchant: '.$MyMerchantArray[$i]['name'].'...';
			 
			 
			if ( is_term( $MyMerchantArray[$i]['name'] , 'category' ) ){
				 echo "Skipping category creation, <b>".$MyMerchantArray[$i]['name']."</b> already exists...<br>";
				 $term = get_term_by('name', $MyMerchantArray[$i]['name'], 'category');
				 $ThisCatID = $term->term_id;
			 }else{
				$ThisCatID = wp_create_category1($MyMerchantArray[$i]['name'], $ParentcatID);
				echo "Creating new category <b>".$MyMerchantArray[$i]['name']."</b>...<br>";	
			 
			 }
			 
			 // NOW LETS FIND ANY COUPONS FOR THESE MERCHANTS
			 
			 $xml = GetDataMe("http://www.formetocoupon.com/services/getDeals?key=".trim(get_option("forme_key"))."&merchantids=".$mm['id'].$QuertyString);
			//die("http://www.formetocoupon.com/services/getDeals?key=".get_option("forme_key")."&merchantids=".$ff[0].$QuertyString.print_r($xml));
			foreach ($xml->item as $item) { 
//die(print_r($item));
				$success = AddCoupon($item,$key['enable'],$ff[1]);
				if($success =="yes"){
					$counter++;
				}else{
					
				}				
			}
			 
			 print '</div>';
			 
			 $i++;
		}
 
	
	}

	echo '</div>';
		 
	ob_flush();   
    flush(); 	
	sleep(1);		
		
}elseif(isset($_GET['dome']) && $_GET['dome'] == "1"){

	ob_implicit_flush();

	$ctype = get_option('icodes_importtype');  $loopCounter=0;
	
	print "<div style='padding:10px; background:#dcffe1; border:1px solid #43a751; margin-right:20px; margin-left:20px; margin-top:30px;'>";
	
	print "<h2>Connecting to your icodes account.....</h2>";
	
	echo "<h3>Creating a categrory for each of your icodes categories</h3>";
	
	$catlist = get_option('icodes_categorylist');
	
	// CREATE A PARENT ACCOUNT TO KEEP THEM ALL IN
	 if ( is_term( "iCode Categories" , 'category' ) ){			 
		$term = get_term_by('name', "iCode Categories", 'category');
		$ParentcatID = $term->term_id;
	}else{
		$my_cat = array('cat_name' => 'iCode Categories', 'category_description' => '', 'category_nicename' => '', 'category_parent' => '');
		$ParentcatID = wp_insert_category1($my_cat); 		 
	}
	
	
 
	
	if(is_array($catlist)){
	
		foreach($catlist as $cat){
		
		$count =1; $pagec=0;
		
		 if ( is_term( $cat['name'] , 'category' ) ){
			 echo "Skipping category creation, <b>".str_replace("_"," ",$cat['name'])."</b> already exists...<br>";
			 $term = get_term_by('name', str_replace("_"," ",$cat['name']), 'category');
			 $ThisCatID = $term->term_id;
		 }else{
			$ThisCatID = wp_create_category1(str_replace("_"," ",$cat['name']), $ParentcatID);
			echo "Creating new category <b>".str_replace("_"," ",$cat['name'])."</b>...<br>";	
		 
		 }
		 
		// NOW LETS CHECK FOR COUPONS FOR THIS CATEGORY
		$QueryString  = get_option('icodes_country')."?";
		$QueryString .= "UserName=".get_option('icodes_subscription_username');
		$QueryString .= "&SubscriptionID=".get_option('icodes_subscriptionID');
		$QueryString .= "&RequestType=Codes&Action=Category";
		$QueryString .= "&Query=".$cat['id'].get_option("icodes_Relationship")."&Page=0&PageSize=10";
 
 		echo "<div style='background:white; padding:15px; margin-top:10px; margin-bottom:10px;'>";
		
		 
		//loop until all pages are found
		while($count < 100){ // 10 is a falback 
			
			$ff = $pagec+1;
			$QueryString = str_replace("Page=".$pagec,"Page=".$ff,$QueryString);		
			$xml = $PPTImport->GetIcodesData($QueryString,$ctype);
			$pagec = $ff;
		 
			 // START THE COUPON IMPORT PROCESS 
			$counterA=0; $counterB=0;  
			$total_items += trim($xml->Results);
			$message1 = trim($xml->Message);
			if($message1==''){
				foreach ($xml->item as $item) {
							 
					if($PPTImport->ICODESADDCOUPON($item,$ThisCatID,'Codes')){$counterA++;}else{ $counterB++;}
				}				
				if($counterA > 0){
				echo "Added ".$counterA." coupons";
				}
				if($counterB > 0){
				 echo "Updated ".$counterB." coupons";
				}
				
				echo " from iCodes feed (page ".$pagec." of ".$xml->TotalPages.") <br />"; 
				 
				// increment the page counter
				if($pagec >= $xml->TotalPages){
					$count=100; 
				} 
				
			}else{
				$count=100;
				echo "&nbsp;0 coupons found for this category <br />";
			}
			
			  
			$count++;		 
		}
		echo '</div>';
		 
		ob_flush();   
    	flush(); 	
		sleep(1);
		
		//if($loopCounter > 1){die();} $loopCounter++;
		}
	
	}
	
	print "</div>";
	
	// SETUP THE AUTO IMPORT TOOL
	$icodesAutoSetup = array('enabled'=>'yes', "date"=>date('Y-m-d H:i:s'));
	update_option("icodesBasicImport1",$icodesAutoSetup);
	

}elseif(isset($_GET['dome']) && $_GET['dome'] == "2"){ // merchant import

	ob_implicit_flush();

	$ctype = get_option('icodes_importtype');  $loopCounter=0;
	
	print "<div style='padding:10px; background:#dcffe1; border:1px solid #43a751; margin-right:20px; margin-left:20px; margin-top:30px;'>";
	
	print "<h2>Connecting to your icodes account.....</h2>";
	
	echo "<h3>Creating a categrory for each of your icodes merchants</h3>";
	
	$catlist = get_option('icodes_merchantlist'); 
	
	if($catlist == ""){ echo "<b style='color:red;'>Your merchant list is empty, click on the 'Icodes Subscription Settings' tab and update the merchants list.</b>"; } 
	
	// CREATE A PARENT ACCOUNT TO KEEP THEM ALL IN
	 if ( is_term( "iCode Merchants" , 'category' ) ){			 
		$term = get_term_by('name', "iCode Merchants", 'category');
		$ParentcatID = $term->term_id;
	}else{
		$my_cat = array('cat_name' => 'iCode Merchants', 'category_description' => '', 'category_nicename' => '', 'category_parent' => '');
		$ParentcatID = wp_insert_category1($my_cat); 		 
	}
 
 
	
	if(is_array($catlist)){
	
		foreach($catlist as $cat){
		
		$count =1; $pagec=0;
		
		 if ( is_term( $cat['name_merchant'] , 'category' ) ){
			 echo "Skipping category creation, <b>".$cat['name_merchant']."</b> already exists...<br>";
			 $term = get_term_by('name', $cat['name_merchant'], 'category');
			 $ThisCatID = $term->term_id;
		 }else{
			$ThisCatID = wp_create_category1($cat['name_merchant'], $ParentcatID);
			echo "Creating new category <b>".$cat['name_merchant']."</b>...<br>";	
		 
		 }
		 
		// NOW LETS CHECK FOR COUPONS FOR THIS CATEGORY
		$QueryString  = get_option('icodes_country')."?";
		$QueryString .= "UserName=".get_option('icodes_subscription_username');
		$QueryString .= "&SubscriptionID=".get_option('icodes_subscriptionID');
		$QueryString .= "&RequestType=Codes&Action=Merchant";
		$QueryString .= "&Query=".$cat['name_merchant'].get_option("icodes_Relationship")."&Page=0&PageSize=10";
 
 		echo "<div style='background:white; padding:15px; margin-top:10px; margin-bottom:10px;'>";
		
		  
		//loop until all pages are found
		while($count < 100){ // 10 is a falback 
			
			$ff = $pagec+1;
			$QueryString = str_replace("Page=".$pagec,"Page=".$ff,$QueryString);		
			$xml = $PPTImport->GetIcodesData($QueryString,$ctype);
			$pagec = $ff;
		  
			 // START THE COUPON IMPORT PROCESS 
			$counterA=0; $counterB=0;  
			$total_items += trim($xml->Results);
			$message1 = trim($xml->Message);
			if($message1==''){
				foreach ($xml->item as $item) {
							 
					if($PPTImport->ICODESADDCOUPON($item,$ThisCatID,'Codes')){$counterA++;}else{ $counterB++;}
				}				
				if($counterA > 0){
				echo "Added ".$counterA." coupons";
				}
				if($counterB > 0){
				 echo "Updated ".$counterB." coupons";
				}
				
				echo " from iCodes feed (page ".$pagec." of ".$xml->TotalPages.")  <br />"; 
				 
				// increment the page counter
				if($pagec >= $xml->TotalPages){
					$count=100; 
				} 
				
			}else{
				$count=100;
				echo "&nbsp;0 coupons found for this merchant <br />";
			}
			
			  
			$count++;		 
		}
		echo '</div>';
		 
		ob_flush();   
    	flush(); 	
		sleep(1);
		
		//if($loopCounter > 1){ $catlist=""; } $loopCounter++;
		}
	
	}
	
	print "</div>";
	
	// SETUP THE AUTO IMPORT TOOL
	$icodesAutoSetup = array('enabled'=>'yes', "date"=>date('Y-m-d H:i:s'));
	update_option("icodesBasicImport2",$icodesAutoSetup);
 
	

}elseif(isset($_GET['dome']) && $_GET['dome'] == "3"){

	ob_implicit_flush();

	$ctype = get_option('icodes_importtype');  $loopCounter=0;
	
	print "<div style='padding:10px; background:#dcffe1; border:1px solid #43a751; margin-right:20px; margin-left:20px; margin-top:30px;'>";
	
	print "<h2>Connecting to your icodes account.....</h2>";
	
	echo "<h3>Creating a categrory for each of your icodes categories</h3>";
	
	$catlist = get_option('icodes_categorylist');
	
	// CREATE A PARENT ACCOUNT TO KEEP THEM ALL IN
	 if ( is_term( "iCode Categories" , 'category' ) ){			 
		$term = get_term_by('name', "iCode Categories", 'category');
		$ParentcatID = $term->term_id;
	}else{
		$my_cat = array('cat_name' => 'iCode Categories', 'category_description' => '', 'category_nicename' => '', 'category_parent' => '');
		$ParentcatID = wp_insert_category1($my_cat); 		 
	}
	
	
 
	
	if(is_array($catlist)){
	
		foreach($catlist as $cat){
		
		$count =1; $pagec=0;
		
		 if ( is_term( $cat['name'] , 'category' ) ){
			 echo "Skipping category creation, <b>".$cat['name']."</b> already exists...<br>";
			 $term = get_term_by('name', str_replace("_"," ",$cat['name']), 'category');
			 $ThisCatID = $term->term_id;
		 }else{
			$ThisCatID = wp_create_category1(str_replace("_"," ",$cat['name']), $ParentcatID);
			echo "Creating new category <b>".str_replace("_"," ",$cat['name'])."</b>...<br>";	
		 
		 }
		 
		// NOW LETS CHECK FOR COUPONS FOR THIS CATEGORY
		$QueryString  = get_option('icodes_country')."?";
		$QueryString .= "UserName=".get_option('icodes_subscription_username');
		$QueryString .= "&SubscriptionID=".get_option('icodes_subscriptionID');
		$QueryString .= "&RequestType=Offers&Action=Category";
		$QueryString .= "&Query=".$cat['id'].get_option("icodes_Relationship")."&Page=0&PageSize=10";
 
 		echo "<div style='background:white; padding:15px; margin-top:10px; margin-bottom:10px;'>";
		
		 
		//loop until all pages are found
		while($count < 100){ // 10 is a falback 
			
			$ff = $pagec+1;
			$QueryString = str_replace("Page=".$pagec,"Page=".$ff,$QueryString);		
			$xml = $PPTImport->GetIcodesData($QueryString,$ctype);
			$pagec = $ff;
		 
			 // START THE COUPON IMPORT PROCESS 
			$counterA=0; $counterB=0;  
			$total_items += trim($xml->Results);
			$message1 = trim($xml->Message);
			if($message1==''){
				foreach ($xml->item as $item) {
							 
					if($PPTImport->ICODESADDCOUPON($item,$ThisCatID,'offer')){$counterA++;}else{ $counterB++;}
				}				
				if($counterA > 0){
				echo "Added ".$counterA." offers";
				}
				if($counterB > 0){
				 echo "Updated ".$counterB." offers";
				}
				
				echo " from iCodes feed (page ".$pagec." of ".$xml->TotalPages.") <br />"; 
				 
				// increment the page counter
				if($pagec >= $xml->TotalPages){
					$count=100; 
				} 
				
			}else{
				$count=100;
				echo "&nbsp;0 offers found for this category <br />";
			}
			
			  
			$count++;		 
		}
		echo '</div>';
		 
		ob_flush();   
    	flush(); 	
		sleep(1);
		
		//if($loopCounter > 1){die();} $loopCounter++;
		}
}

	print "</div>";
	
	// SETUP THE AUTO IMPORT TOOL
	$icodesAutoSetup = array('enabled'=>'yes', "date"=>date('Y-m-d H:i:s'));
	update_option("icodesBasicImport3",$icodesAutoSetup);

}elseif(isset($_GET['dome']) && $_GET['dome'] == "4"){


	ob_implicit_flush();

	$ctype = get_option('icodes_importtype');  $loopCounter=0;
	
	print "<div style='padding:10px; background:#dcffe1; border:1px solid #43a751; margin-right:20px; margin-left:20px; margin-top:30px;'>";
	
	print "<h2>Connecting to your icodes account.....</h2>";
	
	echo "<h3>Creating a categrory for each of your icodes merchants</h3>";
	
	$catlist = get_option('icodes_merchantlist');
	
	if($catlist == ""){ echo "<b style='color:red;'>Your merchant list is empty, click on the 'Icodes Subscription Settings' tab and update the merchants list.</b>"; } 
	
	// CREATE A PARENT ACCOUNT TO KEEP THEM ALL IN
	 if ( is_term( "iCode Merchants" , 'category' ) ){			 
		$term = get_term_by('name', "iCode Merchants", 'category');
		$ParentcatID = $term->term_id;
	}else{
		$my_cat = array('cat_name' => 'iCode Merchants', 'category_description' => '', 'category_nicename' => '', 'category_parent' => '');
		$ParentcatID = wp_insert_category1($my_cat); 		 
	}
 
 
	
	if(is_array($catlist)){
	
		foreach($catlist as $cat){
		
		$count =1; $pagec=0;
		
		 if ( is_term( $cat['name_merchant'] , 'category' ) ){
			 echo "Skipping category creation, <b>".$cat['name_merchant']."</b> already exists...<br>";
			 $term = get_term_by('name', $cat['name_merchant'], 'category');
			 $ThisCatID = $term->term_id;
		 }else{
			$ThisCatID = wp_create_category1($cat['name_merchant'], $ParentcatID);
			echo "Creating new category <b>".$cat['name_merchant']."</b>...<br>";	
		 
		 }
		// print_r($cat);
		// NOW LETS CHECK FOR COUPONS FOR THIS CATEGORY
		$QueryString  = get_option('icodes_country')."?";
		$QueryString .= "UserName=".get_option('icodes_subscription_username');
		$QueryString .= "&SubscriptionID=".get_option('icodes_subscriptionID');
		$QueryString .= "&RequestType=Offers&Action=Merchant";
		$QueryString .= "&Query=".$cat['id'].get_option("icodes_Relationship")."&Page=0&PageSize=10";
 //die($QueryString);
 		echo "<div style='background:white; padding:15px; margin-top:10px; margin-bottom:10px;'>";
		
		  
		//loop until all pages are found
		while($count < 100){ // 10 is a falback 
			
			$ff = $pagec+1;
			$QueryString = str_replace("Page=".$pagec,"Page=".$ff,$QueryString);		
			$xml = $PPTImport->GetIcodesData($QueryString,$ctype);
			$pagec = $ff;
		  
			 // START THE COUPON IMPORT PROCESS 
			$counterA=0; $counterB=0;  
			$total_items += trim($xml->Results);
			$message1 = trim($xml->Message);
			if($message1==''){
				foreach ($xml->item as $item) {
							 
					if($PPTImport->ICODESADDCOUPON($item,$ThisCatID,'offer')){$counterA++;}else{ $counterB++;}
				}				
				if($counterA > 0){
				echo "Added ".$counterA." offers";
				}
				if($counterB > 0){
				 echo "Updated ".$counterB." offers";
				}
				
				echo " from iCodes feed (page ".$pagec." of ".$xml->TotalPages.")  <br />"; 
				 
				// increment the page counter
				if($pagec >= $xml->TotalPages){
					$count=100; 
				} 
				
			}else{
				$count=100;
				echo "&nbsp;0 coupons found for this merchant <br />";
			}
			
			  
			$count++;		 
		}
		echo '</div>';
		 
		ob_flush();   
    	flush(); 	
		sleep(1);
		
		//if($loopCounter > 1){ $catlist=""; } $loopCounter++;
		}
	
	}
	
	print "</div>";
	
	// SETUP THE AUTO IMPORT TOOL
	$icodesAutoSetup = array('enabled'=>'yes', "date"=>date('Y-m-d H:i:s'));
	update_option("icodesBasicImport4",$icodesAutoSetup);

}

//ini_set('output_buffering','on');
 
?>



<div id="premiumpress_tab1" class="content">



<div style="height:40px; margin-bottom:10px; background:#efefef; border:1px solid #ddd;padding:10px;margin:20px;">

    <div class="msg msg-info" style="float:right; width:150px;"><p>
	<span id="show_wp0"><a href="#" onclick="jQuery('#table_wp0').show();jQuery('#hide_wp0').show();jQuery('#show_wp0').hide();" style="font-weight:bold; text-decoration:underline">Show Details</a></span>
	<span id="hide_wp0" style="display:none;"><a href="#" onclick="jQuery('#table_wp0').hide();jQuery('#show_wp0').show();jQuery('#hide_wp0').hide();" style="font-weight:bold; text-decoration:underline">Hide Details</a></span> </p></div> 
    
    <b style="font-size:18px;">Icodes Subscription Settings</b> <br> 
    
    <small style="font-size:12px;">Here you can configure and enter your iCodes account details.</small>

	</div>
    
    

<div id="table_wp0" style="display:none">

<form method="post" target="_self">
<input name="submitted" type="hidden" value="yes" />

<table class="maintable" style="background:white;">

 <?php /* ============================ 1 ================================= */  ?>
<tr class="mainrow"><td></td><td class="forminp">

		<p><b>Subscription ID </b></p>
 
 <input name="adminArray[icodes_subscriptionID]" type="text" class="txt" value="<?php echo get_option("icodes_subscriptionID"); ?>" style="width: 240px; font-size:14px;"><br />
			<small>Your icodes subscription ID. eg 67d96d458abdef21792e6d8e590244eXXX</small>
</td><td class="forminp">
 
 
 <p><b>Subscription Username</b></p>
 <input name="adminArray[icodes_subscription_username]" type="text" class="txt" value="<?php echo get_option("icodes_subscription_username"); ?>" style="width: 240px; font-size:14px;"><br />
			<small>Your icodes username: eg. joeblogs</small>
 
        
</td></tr>


<tr class="mainrow"><td></td><td class="forminp">

 <p><b>Search Type</b></p>
 
 <select name="adminArray[icodes_importtype]" style="width: 240px; font-size:14px;" class="txt">
            <option value="CURL">cURL Search (Requires cURL Installed on your server) </option>
            <option value="url" <?php if(get_option("icodes_importtype") == "url"){ echo 'selected'; } ?>>URL Search (Recommended)</option>                   
            </select>
 
   <br /><br />         
 <p><b>Search Database</b></p>

		<select name="adminArray[icodes_country]" style="width: 240px; font-size:14px;" class="txt">
        <option value="http://webservices.icodes.co.uk/ws2.php"> iCodes UK Database</option>
        <option value="http://webservices.icodes-us.com/ws2_us.php" <?php if(get_option("icodes_country") == "http://webservices.icodes-us.com/ws2_us.php"){ echo 'selected'; } ?>> iCodes USA Database</option>
        </select><br />
			<small>Select which iCodes database to connect to, you must be signed for that website to import coupons successfully.</small>    
            
    <br /><br />         
 <p><b>RelationShip</b></p>
            
	<select name="adminArray[icodes_Relationship]" style="width: 240px; font-size:14px;" class="txt">
        <option value="&Relationship=joined">Only Joined Merchants (recommended)</option>
        <option value="" <?php if(get_option("icodes_Relationship") == ""){ echo 'selected'; } ?>> All </option>
        </select><br />
			<small>Select the default status for newly imported coupon codes.</small>
    
            
</td><td class="forminp" valign="top">


            
            

<input name="icodes_update_categorylist" type="checkbox" value="1" <?php if(get_option("icodes_subscription_username") == ""){ ?>checked="checked"<?php } ?> /> <b>Update Category List</b> <br />
<small>This the box if you want to update your category list.</small>

<br /><br /><br />
 
<input name="icodes_update_networklist" type="checkbox" value="1" <?php if(get_option("icodes_subscription_username") == ""){ ?>checked="checked"<?php } ?> /> <b>Update Network List</b> <br />
<small>This the box if you want to update your network list.</small>

<br /><br /><br />
 
<input name="icodes_update_merchantlist" type="checkbox" value="1" <?php if(get_option("icodes_subscription_username") == ""){ ?>checked="checked"<?php } ?> /> <b>Update Merchant List</b> <br />
<small>This the box if you want to update your merchant list. <b>Note: this maybe slow due to the large amount of merchants</b></small>

 <p><b>Default Import Status</b></p>
            
	<select name="adminArray[icodes_import_status]" style="width: 240px; font-size:14px;" class="txt">
        <option value="publish"> Published</option>
        <option value="draft" <?php if(get_option("icodes_import_status") == "draft"){ echo 'selected'; } ?>> Draft</option>
        </select><br />
<br />

<input name="icodes_quicksetup_import" type="checkbox" value="1" /> <b>Disable Auto Import</b> <br />
<small>This will clear the 'Quick Import Tools' automatic import process.</small>
		 
            
</td></tr>
<?php   /* ============================ ================================= */ ?>


<td colspan="3"> </td>
</tr>

<tr class="mainrow" style="background:#d5edbc; "><td></td><td class="forminp" valign="top">

<p><b>Customize The Imported Coupon Title<b></p>

<textarea style="width:300px; height:80px;" name="adminArray[icodes_custom_title]"><?php echo stripslashes(get_option("icodes_custom_title")); ?></textarea>

<p class="ppnote">[title] [code] [merchant] [url] [starts] [ends]</p>

<p>[title] at [url] = <u>10% off at couponpress.com</u></p>
<p>Get [title] with [code] at [url] starting [starts] = <br /><br /> <u>Get 10% off with MYCODE at couponpress.com starting Starting Monday 8th of August 2011 03:12:46 PM</u></p>

</td><td class="forminp" valign="top">

<p><b>Customize The Imported Coupon Description<b></p>

<textarea style="width:300px; height:80px;" name="adminArray[icodes_custom_desc]"><?php echo stripslashes(get_option("icodes_custom_desc")); ?></textarea>

<p class="ppnote">[description] [code] [merchant] [url] [starts] [ends]</p>
<p>[description] = save XX using this coupon...</p>
<p>[code] = DSFDS%#$%</p>
<p>[merchant] = PremiumPress</p>
<p>[url] = premiumpress.com</p>
<p>[starts] = Monday 8th of August 2011 03:12:46 PM</p>
<p>[ends] = Monday 22th of August 2011 06:12:22 PM</p>
    

</td></tr>

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Start Settings" style="color:white;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - For more information about iCodes, <a href="http://www.icodes-us.com" target="_blank">click here.</a></p></td>
</tr>
</table> 
</form>
</div>



<?php if(strlen(get_option("icodes_subscriptionID")) > 5){ ?>


<div style="height:40px; margin-bottom:10px; background:#efefef; border:1px solid #ddd;padding:10px;margin:20px;">

    <div class="msg msg-info" style="float:right; width:150px;"><p>
	<span id="show_wp7"><a href="#" onclick="jQuery('#table_wp7').show();jQuery('#hide_wp7').show();jQuery('#show_wp7').hide();" style="font-weight:bold; text-decoration:underline">Show Details</a></span>
	<span id="hide_wp7" style="display:none;"><a href="#" onclick="jQuery('#table_wp7').hide();jQuery('#show_wp7').show();jQuery('#hide_wp7').hide();" style="font-weight:bold; text-decoration:underline">Hide Details</a></span> </p></div> 
    
    <b style="font-size:18px;">Quick Import Tools (Recommended)</b> <br> 
    
    <small style="font-size:12px;">Here are a set of tools to quickly import iCodes data into your website.</small>

	</div>
    
    

<div id="table_wp7" style="display:none">

<?php 

$check1 = get_option('icodesBasicImport1'); 
$check2 = get_option('icodesBasicImport2'); 
$check3 = get_option('icodesBasicImport3'); 
 ?>

<div style="padding:20px; padding-top:0px;">
<h2>iCodes quick import options</h2>
<p>Below are a set of tools helping you quickly import everything from your icodes account into your CouponPress website</p>

<table id="ct"><thead><tr id="ct_sort">
<th width="90" class="first">Description</th>
<th width="40" class="last">Actions</th>
</tr></thead><tbody>
                                                    
                           
<tr class="even">
<td width="90" class="first">
<b>Import Categories &amp; Coupons</b><br />
<p>This option will import all categories from your iCodes account then import all coupons into each category.</p>
<p>Once completed, twice daily the system will automatically import new coupons into your webiste for you.</p>
<p><b style="color:#666;">Ideal for setting up your account for the first time</b></p>    
</td>
<td width="50" class="last">
<br />
<center>
<a href="admin.php?page=import&dome=1" style="padding:20px; font-size:18px; border: 1px solid #9BF878; background:#D3F8CB; color:#006600;">Run Import Tool Now</a> 
<div class="clear" style="clear:both;"></div>
<?php if(is_array($check1) ){ echo "<div style='padding-top:30px'><small>Automatically Updated on <br/>".date('l jS \of F Y h:i:s A', strtotime($check1['date']))."</small></div>"; } ?>
</center>
 </td>
</tr>

<tr class="even">
<td width="90" class="first">
<b>Import Merchants &amp; Coupons</b><br />
<p>This option will import all merchants from your iCodes account as categories then import all coupons into each category.</p>
<p>Once completed, twice daily the system will automatically import new coupons into your webiste for you.</p>
<p><b style="color:#666;">Ideal for setting up your account for the first time</b></p>    
</td>
<td width="50" class="last">
<br />
<center>
<a href="admin.php?page=import&dome=2" style="padding:20px; font-size:18px; border: 1px solid #9BF878; background:#D3F8CB; color:#006600;">Run Import Tool Now</a> 
<div class="clear" style="clear:both;"></div>
<?php if(isset($check2) && is_array($check2) ){ echo "<div style='padding-top:30px'><small>Automatically Updated on <br/>".date('l jS \of F Y h:i:s A', strtotime($check2['date']))."</small></div>"; } ?>
</center>
 </td>
</tr>


<tr class="even">
<td width="90" class="first">
<b>Import Merchants &amp; Offers</b><br />
<p>This option will import all merchants from your iCodes account as categories then import all offers into each category.</p>
<p>Once completed, twice daily the system will automatically import new coupons into your webiste for you.</p>
<p><b style="color:#666;">Ideal for setting up your account for the first time</b></p>    
</td>
<td width="50" class="last">
<br />
<center>
<a href="admin.php?page=import&dome=4" style="padding:20px; font-size:18px; border: 1px solid #9BF878; background:#D3F8CB; color:#006600;">Run Import Tool Now</a> 
<div class="clear" style="clear:both;"></div>
<?php if(isset($check2) && is_array($check2) ){ echo "<div style='padding-top:30px'><small>Automatically Updated on <br/>".date('l jS \of F Y h:i:s A', strtotime($check2['date']))."</small></div>"; } ?>
</center>
 </td>
</tr>


<tr class="even">
<td width="90" class="first">
<b>Import Categories &amp; Offers</b><br />
<p>This option will import all categories from your iCodes account then import all offers into each category.</p>
<p>Once completed, twice daily the system will automatically import new offers into your webiste for you.</p>
<p><b style="color:#666;">Ideal for setting up your account for the first time</b></p>    
</td>
<td width="50" class="last">
<br />
<center>
<a href="admin.php?page=import&dome=3" style="padding:20px; font-size:18px; border: 1px solid #9BF878; background:#D3F8CB; color:#006600;">Run Import Tool Now</a> 
<div class="clear" style="clear:both;"></div>
<?php if(is_array($check3) ){ echo "<div style='padding-top:30px'><small>Automatically Updated on <br/>".date('l jS \of F Y h:i:s A', strtotime($check3['date']))."</small></div>"; } ?>
</center>
 </td>
</tr>

<tr class="even">
<td width="90" class="first">
   
</td>
<td width="50" class="last">
 
 </td>
</tr>
</tbody></table>
</div>

</div>



<div style="height:40px; margin-bottom:10px; background:#efefef; border:1px solid #ddd;padding:10px;margin:20px;">

    <div class="msg msg-info" style="float:right; width:150px;"><p>
	<span id="show_wp2"><a href="#" onclick="jQuery('#table_wp2').show();jQuery('#hide_wp2').show();jQuery('#show_wp2').hide();" style="font-weight:bold; text-decoration:underline">Show Details</a></span>
	<span id="hide_wp2" style="display:none;"><a href="#" onclick="jQuery('#table_wp2').hide();jQuery('#show_wp2').show();jQuery('#hide_wp2').hide();" style="font-weight:bold; text-decoration:underline">Hide Details</a></span> </p></div> 
    
    <b style="font-size:18px;">Hourly Import Settings (Advanced)</b> <br> 
    
    <small style="font-size:12px;">Here you can configure and automate coupon code imports on a timely interval.</small>	</div>
    
    

<div id="table_wp2" style="display:none">

<form method="post" target="_self" style="padding:10px; background:#dcffe1; border:1px solid #43a751; margin-right:20px; margin-left:20px;">
<input type="hidden" name="icodes_save_me" value="1" />

<table width="100%" border="1">
  <tr>
    <td><b>Want to import?</b>
    
    
   <select name="icodes_s_1" class="txt">
        <option value="Codes">Coupon Codes</option>
        <option value="Offers">Offers</option>
        </select> 
    
    </td>
    <td><b>Where from?</b>
    
    <select name="icodes_s_2" class="txt" onchange="icodesList(this.value);">
    <!--<option value="Full">All Coupons</option>-->
    <option value="New">New Coupons</option>
    <option value="Expiring">Expiring Coupons</option>
        <option value="Category">From Category</option>
        <option value="Merchant">From Merchant</option>
        <option value="Network">From Network</option>
        </select>    
    
    <div id="icodesList"></div>
    </td>
 
     <td><b>In what order?</b>
     
    <select name="icodes_s_4" class="txt">
        <option value="StartDateLow">Start Date (Low)</option>
        <option value="StartDateHigh">Start Date (High)</option>
        <option value="ExpiryDateLow">Expiry Date (Low)</option>
         <option value="ExpiryDateHigh">Expiry Date (High)</option>
         <option value="id">id</option>
        </select>  
     
     
     </td>
    <td><b>Add to which category?</b>
    
 <select name="icodes_s_5" class="txt" ><?php echo $PPT->CategoryList(); ?></select>   
    </td>
    <td><b>How often?</b>
    
    
    <select name="icodes_s_6" class="txt">
        <option value="hourly">Every Hour</option>
        <option value="twicedaily">Twice Daily</option>
        <option value="daily">Once Daily</option>
        </select>        
    </td>
  </tr>
</table>

<p><input class="premiumpress_button" type="submit" value="Create Scheduled Search" style="color:white;float:right; margin-right:20px;" /></p>

  
</form>


<?php $icodesSaveList = get_option('icodes_savelist'); if(is_array($icodesSaveList)){   ?>

 <div style="margin-left:20px;margin-right:20px;">
<h2 style="margin-left:10px;">Saved Schedules</h2>
<p style="margin-left:10px;">Below is a list of all your current saved coupon searches. <b>(The time now is <?php echo date('l jS \of F Y h:i:s A'); ?> )</b></p>       
					  
					      <table id="ct"><thead><tr id="ct_sort">
                          
                          <th width="90" class="first">Description</th>
                          
                          <th width="40"class="last">Import Count</th> 
                          <th width="90">Status</th> 
                          <th width="40"class="last">Actions</th>
                          
                          </tr></thead><tbody>
                          <?php  foreach($icodesSaveList as $slide){ ?>                          
                           
                          <tr>
                          
                          <td width="90" class="first"><b><?php echo $slide['Time']; ?></b>, import <?php echo $slide['RequestType']; ?> (order by <?php echo $slide['Sort']; ?>) from iCodes <?php echo $slide['Action']; ?> <?php echo $PPTImport->GetIcodesCateoryName($slide['ActionID'],$slide['Action']); ?> into <b><?php echo get_cat_name($slide['Map']);  ?> </b>    
                           </td>
					         
                           <td width="50">New <?php echo $slide['CountGood']; ?> <br /> (Duplicates <?php echo $slide['CountBad']; ?>)</td>      
                           
                           <td width="50">
                           
                           <?php if($slide['PageTotal'] == 0){
						   
						   echo 'Shceduled';
						   
						   }elseif($slide['PageTotal'] > 0 && ( $slide['Page']-1 == $slide['PageTotal'] || $slide['Page']-1 > $slide['PageTotal']  ) ){ echo "<b>Import Finished</b> <br><small>no more coupons</small>"; }else{ ?>
                           
                           Page <?php echo $slide['Page']-1; ?> of <?php echo $slide['PageTotal']; } ?>
                           
                           <?php echo ' <br><small>Next scheduled import on '.date('l jS \of F Y h:i:s A',wp_next_scheduled( "ppt_".$slide['Time']."_event"))."</small>"; ?>
                           
                           </td> 
                           
                                               
                          <td width="50" class="last"> <a href="admin.php?page=import&icodes_run=<?php echo $slide['ID']; ?>">Run Now</a> | <a href="admin.php?page=import&icodes_s_del=<?php echo $slide['ID']; ?>">Delete</a> <br /><br /> <small><a href="admin.php?page=import&icodes_debug=<?php echo $slide['ID']; ?>">Debug Query String</a> </small> </td>
                          
                          </tr>
                          
                          
                          <?php   } ?>
                          
                          
                         </tbody> </table>
</div>

<a href="admin.php?page=import&deleteallsaved=1" style="margin-left:20px;">Delete All Saved Search</a>
<?php } ?>



</div>

















<div style="height:40px; margin-bottom:10px; background:#efefef; border:1px solid #ddd;padding:10px;margin:20px;">

    <div class="msg msg-info" style="float:right; width:150px;"><p>
	<span id="show_wp1"><a href="#" onclick="jQuery('#table_wp1').show();jQuery('#hide_wp1').show();jQuery('#show_wp1').hide();" style="font-weight:bold; text-decoration:underline">Show Details</a></span>
	<span id="hide_wp1" style="display:none;"><a href="#" onclick="jQuery('#table_wp1').hide();jQuery('#show_wp1').show();jQuery('#hide_wp1').hide();" style="font-weight:bold; text-decoration:underline">Hide Details</a></span> </p></div> 
    
    <b style="font-size:18px;">Manual Import (Basic)</b> <br> 
    
    <small style="font-size:12px;">Here you can manually select and import coupon codes.</small>

	</div>
    


<div id="table_wp1" style="display:none">

<form method="post" target="_self">
<input type="hidden" name="start_page" value="1" />
<input type="hidden" name="icodes" value="1" />
<input type="hidden" name="startsearch" value="1" />

<table class="maintable" style="background:white;">
  
 

	 <tr class="mainrow">
		<td class="titledesc">Keyword Search</td>
		<td class="forminp">
			<input name="keyword" value="" type="text" class="txt"  style="width: 500px; height:40px; font-size:18px; background:#D9F9D8"><br />
			<small>Example: "free delivery" (Leave blank to return call coupons)</small>
		</td>
	</tr> 



     	<tr class="mainrow">
		<td class="titledesc">Search For</td>
		<td class="forminp">
		 
		  <input name="display_stype" type="radio" value="Codes" checked /> Coupon Codes <input name="display_stype" type="radio" value="Offers" /> Offers <input name="display_stype" type="radio" value="PrintableVouchers" /> Printable Vouchers
		<br />
			<small>What type of coupons would you like to search for?</small>

		</td>
	</tr> 
       
     	<tr class="mainrow">
		<td class="titledesc">Display Options</td>
		<td class="forminp">
		 
		 Display Coupons <input name="display_coupons" type="radio" value="1" checked onClick="toggleLayer('scat'); toggleLayer('smasscats'); toggleLayer('normalcats');" /> Mass Import  <input name="display_coupons" type="radio" value="2" onClick="toggleLayer('scat'); toggleLayer('smasscats'); toggleLayer('normalcats');" />
		<br />
			<small>Would you like to select which coupons to import or mass import them all?</small>

		</td>
	</tr>

	 <tr class="mainrow" id="scat" style="display:visible">
		<td class="titledesc">Display Category</td>
		<td class="forminp">

	<select style="width:250px; font-size:13px;" name="i_category">
    <option value="all" selected>All Categories</option>  
    <?php
	
	$catlist = get_option("icodes_categorylist");
	foreach($catlist as $cat){
	print '<option value="'.$cat['id'].'">'.str_replace("_"," ",$cat['name']).'</option>';
	}
	
	?></select>		 
    
    <small>This will display a list of the categories you have joined in your icodes account.</small>
		</td>
	</tr> 

<!--
	 <tr class="mainrow" id="scat" style="display:visible">
		<td class="titledesc">Network List</td>
		<td class="forminp">

	<select style="width:250px; font-size:13px;" name="i_network">
    <option value="all" selected>All Categories</option>  
    <?php
	
	$catlist = get_option("icodes_networklist");
	foreach($catlist as $cat){
	print '<option value="'.$cat['id'].'">'.str_replace("_"," ",$cat['name']).'</option>';
	}
	
	?></select>		 
    
    <small>This will display a list of the categories you have joined in your icodes account.</small>
		</td>
	</tr>  -->




     	<tr class="mainrow" id="normalcats" style="display:visible;">
		<td class="titledesc">Import Category</td>
		<td class="forminp">		 
		<?php echo CouponPress_Admin_Cats(); ?>
		<br />
		<small>Select 1 or more categories to import selected coupons into.</small>
		</td>
	</tr> 

   	<tr class="mainrow" id="smasscats" style="display:none">
		<td class="titledesc">Mass Import Category</td>
		<td class="forminp">
 

		 <table width="100%"  border="0">

		<?php
		
	
		
	$catlist = get_option("icodes_categorylist");
	$icodescatList = '<select style="width:250px; font-size:13px;" name="masscat[%name][cat]">';
	foreach($catlist as $cat){
		$icodescatList .= '<option value="'.$cat['id'].'">'.str_replace("_"," ",$cat['name']).'</option>';
	}
	
	$icodescatList .= "</select>";
	$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
	$Maincatcount = count($Maincategories);	
	$i=0;
	
	foreach ($Maincategories as $Maincat) {
		 
		if($Maincat->parent ==0){
		
			print '<tr><td>';

			print ' <input name="masscat['.$i.'][enable]" type="checkbox" value="'.$Maincat->cat_ID.'">' . $Maincat->cat_name."";
			
			// do sub cats
			$currentcat=get_query_var('cat');
			$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0');
			$catcount = count($categories);		
			 
				
			if(count($categories) > 0){
				 
				foreach ($categories as $cat) {	
				
					print ' <br/><br/> -- <input name="masscat['.$i.'][enable]" type="checkbox" value="'.$cat->cat_ID.'">' . $cat->cat_name . "<br />";
				
				}
			
			}
 
			print '</td><td>'.str_replace("%name",$i,$icodescatList).'</td></tr>';
			$i++;
		}	

	}
		
		?>
		 </table>
		<br />
			<small>Select one or more categories to import these products into.</small>

		</td>
	</tr>

  
   
       
 

    
    
       	<tr class="mainrow">
		<td class="titledesc">Order Results By</td>
		<td class="forminp">
		 
    	 
<select name="orderby" style="width: 240px; font-size:14px;" class="txt">
            <option>start_date</option>
            <option>expiry_date</option>
            <option>max_start_date</option>
            <option>min_expiry_date</option>
            <option>id</option>                   
            </select>

		</td>
	</tr>     
    

    

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Start Search" style="color:white;" /></p></td>
</tr>
</table>

</form>

</div>


<?php } ?>

</div>


















 
<div id="premiumpress_tab3" class="content">



<div style="height:40px; margin-bottom:10px; background:#efefef; border:1px solid #ddd;padding:10px;margin:20px;">

    <div class="msg msg-info" style="float:right; width:150px;"><p>
	<span id="show_wp0a"><a href="#" onclick="jQuery('#table_wp0a').show();jQuery('#hide_wp0a').show();jQuery('#show_wp0a').hide();" style="font-weight:bold; text-decoration:underline">Show Details</a></span>
	<span id="hide_wp0a" style="display:none;"><a href="#" onclick="jQuery('#table_wp0a').hide();jQuery('#show_wp0a').show();jQuery('#hide_wp0a').hide();" style="font-weight:bold; text-decoration:underline">Hide Details</a></span> </p></div> 
    
    <b style="font-size:18px;">ForMeToCoupon Subscription Settings</b> <br> 
    
    <small style="font-size:12px;">Here you can configure and enter your ForMeToCoupon account details.</small>

	</div>
    
    

<div id="table_wp0a" style="display:none">

<form method="post" target="_self">
<input name="submitted" type="hidden" value="yes" />

<input type="hidden" name="formetocoupon" value="1" />
<input type="hidden" name="formetocoupon_merchant" value="1" />

<table class="maintable" style="background:white;">

 <?php /* ============================ 1 ================================= */  ?>
<tr class="mainrow"><td></td><td class="forminp">

		<p><b>Subscription ID </b></p>
 
 <input name="forme_key" value="<?php echo get_option("forme_key"); ?>" type="text" class="txt"  style="width: 500px; height:40px; font-size:18px; background:#D9F9D8">
 
 <br />
			<small>Your subscription ID. eg 67d96d458abdef21792e6d8e590244eXXX can be <a href="http://www.formetocoupon.com/accountExampleApiCalls">found here</a></small>
</td><td class="forminp">
 
 
 
</td></tr>


<tr class="mainrow"><td></td><td class="forminp">
           
</td><td class="forminp" valign="top">


            
   
            
</td></tr>
<?php   /* ============================ ================================= */ ?>


<td colspan="3"> </td>
</tr>

<tr class="mainrow" style="background:#d5edbc; "><td></td><td class="forminp" valign="top">

<p><b>Customize The Imported Coupon Title<b></p>

<textarea style="width:300px; height:80px;" name="adminArray[icodes_custom_title]"><?php echo stripslashes(get_option("icodes_custom_title")); ?></textarea>

<p class="ppnote">[title] [code] [merchant] [url] [starts] [ends]</p>

<p>[title] at [url] = <u>10% off at couponpress.com</u></p>
<p>Get [title] with [code] at [url] starting [starts] = <br /><br /> <u>Get 10% off with MYCODE at couponpress.com starting Starting Monday 8th of August 2011 03:12:46 PM</u></p>

</td><td class="forminp" valign="top">

<p><b>Customize The Imported Coupon Description<b></p>

<textarea style="width:300px; height:80px;" name="adminArray[icodes_custom_desc]"><?php echo stripslashes(get_option("icodes_custom_desc")); ?></textarea>

<p class="ppnote">[description] [code] [merchant] [url] [starts] [ends]</p>
<p>[description] = save XX using this coupon...</p>
<p>[code] = DSFDS%#$%</p>
<p>[merchant] = PremiumPress</p>
<p>[url] = premiumpress.com</p>
<p>[starts] = Monday 8th of August 2011 03:12:46 PM</p>
<p>[ends] = Monday 22th of August 2011 06:12:22 PM</p>
    

</td></tr>

<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Start Settings" style="color:white;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; - For more information about iCodes, <a href="http://www.icodes-us.com" target="_blank">click here.</a></p></td>
</tr>
</table> 
</form>
</div>





















<?php if(strlen(get_option("forme_key")) > 5){ ?>


<div style="height:40px; margin-bottom:10px; background:#efefef; border:1px solid #ddd;padding:10px;margin:20px;">

    <div class="msg msg-info" style="float:right; width:150px;"><p>
	<span id="show_wp7"><a href="#" onclick="jQuery('#table_wp7').show();jQuery('#hide_wp7').show();jQuery('#show_wp7').hide();" style="font-weight:bold; text-decoration:underline">Show Details</a></span>
	<span id="hide_wp7" style="display:none;"><a href="#" onclick="jQuery('#table_wp7').hide();jQuery('#show_wp7').show();jQuery('#hide_wp7').hide();" style="font-weight:bold; text-decoration:underline">Hide Details</a></span> </p></div> 
    
    <b style="font-size:18px;">Quick Import Tools (Recommended)</b> <br> 
    
    <small style="font-size:12px;">Here are a set of tools to quickly import ForMeToCoupon data into your website.</small>

	</div>
    
    

<div id="table_wp7" style="display:none">

<?php 

$check1 = get_option('formetocouponBasicImport1'); 
$check2 = get_option('icodesBasicImport2'); 
$check3 = get_option('icodesBasicImport3'); 
 ?>

<div style="padding:20px; padding-top:0px;">
<h2>iCodes quick import options</h2>
<p>Below are a set of tools helping you quickly import everything from your icodes account into your CouponPress website</p>

<table id="ct"><thead><tr id="ct_sort">
<th width="90" class="first">Description</th>
<th width="40" class="last">Actions</th>
</tr></thead><tbody>
                                                    
                           
<tr class="even">
<td width="90" class="first">
<b>Import Merchants &amp; Coupons</b><br />
<p>This option will import all categories from your iCodes account then import all coupons into each category.</p>
<p>Once completed, twice daily the system will automatically import new coupons into your webiste for you.</p>
<p><b style="color:#666;">Ideal for setting up your account for the first time</b></p>    
</td>
<td width="50" class="last">
<br />
<center>
<a href="admin.php?page=import&fdo=1" style="padding:20px; font-size:18px; border: 1px solid #9BF878; background:#D3F8CB; color:#006600;">Run Import Tool Now</a> 
<div class="clear" style="clear:both;"></div>
<?php if(is_array($check1) ){ echo "<div style='padding-top:30px'><small>Automatically Updated on <br/>".date('l jS \of F Y h:i:s A', strtotime($check1['date']))."</small></div>"; } ?>
</center>
 </td>
</tr>

 

 

<tr class="even">
<td width="90" class="first">
   
</td>
<td width="50" class="last">
 
 </td>
</tr>
</tbody></table>
</div>

</div>

<?php } ?>




<?php 	//$sData = get_option('FMTCSave');   ?>

<form method="post" target="_self">
<input type="hidden" name="formetocoupon" value="1" />
<table class="maintable" style="background:white;">



<?php

$mynetworks = get_option("forme_merchantlist");

if($mynetworks == ""){ ?>



<?php }else{ ?>


 
	<tr class="mainrow">
		<td valign="top" style="width:450px;">
        
        
       <p><b> <input name="forme_incremental" type="checkbox" value="yes"> Incremental</p></b>        
      		 
		<small>Including this option will download only items added or modified since your last search. The very first call will return changes in the last 48 hours.</small>  <br /> 
        
       <p><b> <input name="forme_codemaker" type="checkbox" value="yes" checked> Missing Code Tool</p></b>        
   			 
		<small>Tick this box if you wish the system to generate a fake coupon code for merchants without valid codes.</small>     <br />
        
        <p><b> Country </p></b>
        
        
 
			<select name="forme_country" style="font-size:14px; width:240px;">
            <option value="0">-------</option>
            <option value="uk">United Kingdom</option>
            <option value="us">United States</option>
          <option value="canada">Canada</option>
                   
            </select>
<br />
			<small>Select this option if you only want to import from a chosen country.</small> <br /><br />
            
                   
        
   <p><b> Import Post Status </p></b>
      
  	<select name="import_status" style="font-size:14px; width:260px;">
        <option value="publish"> Published</option>
        <option value="draft"> Draft</option>
        </select><br />
			<small>Select the default status for newly imported coupon codes.</small>      
        
        
        </td>
		<td valign="top">
        
        
<p><b>Deal Type</p></b>

	<select name="forme_deal"  multiple style="height:250px; font-size:14px; width:240px; background:#E7FDDF">          
			<option value="0">All Deal Types</option>            
			<option value="coupon">coupon</option>
            <option value="freeshipping">freeshipping</option>
          	<option value="rebates">rebates</option>
			<option value="clearance">clearance</option>
			<option value="sale">sale</option>
			<option value="newcustomer">newcustomer</option>
			<option value="dollar">dollar</option>
			<option value="percent">percent</option>
			<option value="gift">gift</option>
			<option value="dod">dod</option>                   
            </select>

		</td>
	</tr>  

 
</table> 
  
 
 

		<?php


$o=0; 
$icodescatList = '<p>Import all coupons from this merchant;</p><select style="width:250px; font-size:13px;" name="masscat[%name][cat]"><option value="0"> ----- </option>';
foreach($mynetworks as $name){ 

//$ff = explode("**",$sData[$o]['cat']);
//if($ff[0] == $name['id']){ $exa = "%sel".$o."%"; }else{ $exa = ""; }
$icodescatList .= '<option value="'.$name['id'].'**'.$name['url'].'" '.$exa .'>'.$name['name'].' ('.$name['net'].$ff[0].')</option>';
$o++;
}
$icodescatList .= '</select><br />';


	$Maincategories= get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');
	$Maincatcount = count($Maincategories);	
	$i=0;
	
	foreach ($Maincategories as $Maincat) {
		 
		if($Maincat->parent ==0 && strtolower($Maincat->cat_name) != "articles"){
		
			print '<div style="width:255px; float:left; background:#efefef; border:1px solid #ddd; margin-left:10px;margin-top:10px;padding:7px;" >';

			print ' <p><b style="font-size:16px;">' . $Maincat->cat_name." </p></b>"; //<input name="masscat['.$i.'][enable]" type="checkbox" value="'.$Maincat->cat_ID.'">
			
 
			print ''.str_replace("%name",$i,str_replace("%sel".$i."%","selected=selected",$icodescatList)).'';
			
			
			// do sub cats
			$currentcat=get_query_var('cat');
			$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0');
			$catcount = count($categories);		
			 
			
			print '<p>into these stores;</p><br><div style="padding:10px; background:#e1ffd5; ">';
				
			if(count($categories) > 0){
				 
				foreach ($categories as $cat) {	
				
					print '<div style="height:40px;"> <input name="masscat['.$i.'][enable]" type="checkbox" value="'.$cat->cat_ID.'">&nbsp;&nbsp;' . $cat->cat_name . "</div> ";
				
				}
			
			}
			
			print '</div></div>';
			
			
			$i++;
		}	

	}
	
	 
		
		?>
		 
		<br />
			<small>Select one or more categories to import these coupons into.</small>

 









<tr>
<td colspan="3"><p><input class="premiumpress_button" type="submit" value="Start Import" style="color:white;" /></p></td>
</tr>

<?php } ?>

</table>
</form>

</div> 



















 
<div id="premiumpress_tab5" class="content">tab 5</div>
<div id="premiumpress_tab6" class="content">tab 6</div>
<div class="clearfix"></div>  


<?php } ?>
 


 


</div>