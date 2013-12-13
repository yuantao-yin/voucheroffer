<?php
 
 	$ThemeDesign 	= new Theme_Design;		
	$PPT 			= new PremiumPressTheme;
	$PPTFunction 	= new PremiumPressTheme_Function; 	
	$PPTDesign 		= new PremiumPressTheme_Design;	
	$PPTImport 		= new PremiumPressTheme_Import;	

$GLOBALS['sf'] = 0;
$GLOBALS['error'] 		= 0;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "";

// RESET THE SYSTEM
if(isset($_POST['reset']) && $_POST['RESETME'] =="yes"){

include(TEMPLATEPATH ."/template_".strtolower(PREMIUMPRESS_SYSTEM)."/system_reset.php");
$GLOBALS['error'] 		= 1;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "Your website has been successfully reset.";
}



// EXPORT TO CSV FILE
if(isset($_GET['exportcsv'])){
	function orders_fetch_all($result) {$all = array();while ($all[] = mysql_fetch_assoc($result)) {}return $all;}
	$SQL = "SELECT * FROM ".$wpdb->prefix."orderdata"; 
	$result = mysql_query($SQL);
	$results = orders_fetch_all($result);
	$header = array_keys($results[0]);
	$cols = count($header); 
	$deliminator = ",";
	for ($i=0; $i<$cols; $i++)
		$output .= "\"".$header[$i]."\"".$deliminator;
		$output .= "\r\n";
	foreach ($results as $row){
		for ($i=0; $i<$cols; $i++)
		{
			$data = str_replace('"', '\'', $row[$header[$i]]);
			$output .= "\"".$data."\"".$deliminator;
		}
			$output .= "\r\n";
	}
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
			//header("Content-Type: application/vnd.ms-excel" );
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".date("Y-m-d").".csv\";");
	header("Content-Transfer-Encoding:?binary");
	header("Content-Length: ".strlen($output)); 
	echo $output;
	die();
}

/* =================== PREMIUM PRESS AMAZON DELETE SEARCH ====================== */

if(isset($_GET['runnow'])){

	$PPT->AmazonRunSearch($_GET['runnow']);
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Schedule Deleted Successfully";
	
}elseif(isset($_GET['delf']) && !isset($_POST['feed'])){
 
	$PPT->AmazonDeleteSearch($_GET['delf']);
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Schedule Deleted Successfully";

}elseif(isset($_POST['feed']) && ( strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress" || strtolower(constant('PREMIUMPRESS_SYSTEM')) == "classifiedstheme" ) ){
 
	if($_POST['schedule']['name'] != ""){

	if(isset($_POST['cat'][0])){
	$PPT->AmazonSavedSearch();
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Schedule Search Saved Successfully";
	}else{
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "No category selected, scheduled search NOT saved";
	}

	}else{
 
	$GLOBALS['amazonsearch'] =1;

	} 
}
// EBAY IMPORT TOOLS
if(isset($_POST['ebay'])){
update_option("ebay_api",$_POST['ebay_api']);
//update_option("ebay_tracking",$_POST['ebay_tracking']);
//update_option("ebay_customid",$_POST['ebay_customid']);
$GLOBALS['ebaysearch'] =1;
}

/* =================== PREMIUM PRESS AMAZON DELETE SEARCH ====================== */





if(isset($_POST['admin_slider'])){

	if($_POST['admin_slider'] == "reset"){
	
	update_option("slider_array","");
	
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Slider Reset Successfully";	
	
	}else{


	$nowSlide = array();
	$nowSlide = get_option("slider_array");
	if(is_array($nowSlide)){ $c= count($nowSlide)+1; }else{ $c=1; $nowSlide = array(); }
	
	//print_R($nowSlide);
	//print "<br><br>".count($nowSlide)."<br>";
	//die(0);
	
	if(!isset($_POST['eid'])){
	
		$nowSlide[$c]['order'] = $_POST['s6'];
		$nowSlide[$c]['s1'] = $_POST['s1'];
		$nowSlide[$c]['s2'] = $_POST['s2'];
		$nowSlide[$c]['s3'] = $_POST['s3'];
		$nowSlide[$c]['s4'] = $_POST['s4'];
		$nowSlide[$c]['s5'] = $_POST['s5']; 
	
	}else{
	
		$nowSlide[$_POST['eid']]['order'] = $_POST['s6'];
		$nowSlide[$_POST['eid']]['s1'] = $_POST['s1'];
		$nowSlide[$_POST['eid']]['s2'] = $_POST['s2'];
		$nowSlide[$_POST['eid']]['s3'] = $_POST['s3'];
		$nowSlide[$_POST['eid']]['s4'] = $_POST['s4'];
		$nowSlide[$_POST['eid']]['s5'] = $_POST['s5']; 
	}	
	
	update_option("slider_array",$nowSlide);
	
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Slider Updated Successfully";
	
	}

}

/*********************************************************************
/************** GENERAL SETUP PAGE OPTIONS **************************/
 
if(isset($_POST['admin_page']) && $_POST['admin_page'] == "email_manager"){

 
	// ADMIN EMAIL SETUP
	if(isset($_POST['emailrole1'])){	
	update_option("emailrole1", $_POST['emailrole1']);
	update_option("emailrole2", $_POST['emailrole2']);
	update_option("emailrole3", $_POST['emailrole3']);
	update_option("emailrole4", $_POST['emailrole4']);
	}
 
}


/*********************************************************************
/************** GENERAL SETUP PAGE OPTIONS **************************/ 
 

if(isset($_POST['admin_page']) && $_POST['admin_page'] == "general_setup"){


	// TURNS ON/OFF USER REGISTRATION
	$allow_register = (int)trim($_POST['users_can_register']);
	update_option("users_can_register", $allow_register);
	
	// if SHRINKTHEWEB
	if($_POST['adminArray']['display_previewimage_type'] == "custom"){			
		$stw_1 = (int)trim($_POST['stw_1']); update_option("stw_1", $stw_1);
		$stw_2 = (int)trim($_POST['stw_2']); update_option("stw_2", $stw_2);
		$stw_3 = (int)trim($_POST['stw_3']); update_option("stw_3", $stw_3);
	
	}	
	
	// ORDER BY LISTBOX DATA
	$check_right = (int)trim($_POST['listbox_display']);
	update_option("listbox_display", $check_right);
		
	$check_right2 = (int)trim($_POST['listbox_custom']);
	update_option("listbox_custom", $check_right2);
		
	$check_right = (int)trim($_POST['listbox_display_cats']);
	update_option("listbox_display_cats", $check_right);	
		
		
	$check_right = (int)trim($_POST['listbox_display_order']);
	update_option("listbox_display_order", $check_right);
		
		//custom field value
		$String = $_POST['a1']."**".$_POST['a2']."**".$_POST['a3']."**".$_POST['a4']."**";
		$String .= $_POST['b1']."**".$_POST['b2']."**".$_POST['b3']."**".$_POST['b4']."**";
		$String .= $_POST['c1']."**".$_POST['c2']."**".$_POST['c3']."**".$_POST['c4']."**";
		$String .= $_POST['d1']."**".$_POST['d2']."**".$_POST['d3']."**".$_POST['d4']."**";
		$String .= $_POST['e1']."**".$_POST['e2']."**".$_POST['e3']."**".$_POST['e4']."**";
		$String .= $_POST['f1']."**".$_POST['f2']."**".$_POST['f3']."**".$_POST['f4']."**";
		$String .= $_POST['g1']."**".$_POST['g2']."**".$_POST['g3']."**".$_POST['g4']."**";
		$String .= $_POST['h1']."**".$_POST['h2']."**".$_POST['h3']."**".$_POST['h4']."**";
		$String .= $_POST['i1']."**".$_POST['i2']."**".$_POST['i3']."**".$_POST['i4']."**";
		$String .= $_POST['j1']."**".$_POST['j2']."**".$_POST['j3']."**".$_POST['j4']."**";
		$String .= $_POST['k1']."**".$_POST['k2']."**".$_POST['k3']."**".$_POST['k4']."**";
		$String .= $_POST['l1']."**".$_POST['l2']."**".$_POST['l3']."**".$_POST['l4']."**";
		
		update_option("listbox_custom_string", $String);
		
		
 	// // CATEGORY DISPLAY
	if(isset($_POST['nav_cat']) && is_array($_POST['nav_cat']) ){	 
		update_option("nav_cats",$_POST['nav_cat']);	
	}
	
	// ADVANCED SEARCH
	$check_enabled = (int)trim($_POST['display_advanced_search']);
	update_option("display_advanced_search", $check_enabled);
 
	// PAGES DISPLAY
	 
	if(isset($_POST['nav_page'])){	
			$hide_pages = ""; 
		foreach($_POST['nav_page'] as $page_id){
				$hide_pages .= $page_id.",";
		}
		update_option("excluded_pages",$hide_pages);	
	}else{
	update_option("excluded_pages","");
	}
	
	
	
}




/*****************************************************************************
/************** CLASSIFIEDSTHEME DESIGN SETUP OPTIONS **************************/

if(isset($_POST['admin_page']) && $_POST['admin_page'] == "classifiedstheme_setup"){


	// LAYOUT CHOICE
	$f = (int)trim($_POST['display_themecolumns']);
	update_option("display_themecolumns", $f);


	// LAYOUT CHOICE
	$f = (int)trim($_POST['display_homecolumns']);
	update_option("display_homecolumns", $f); 
	
}


// MOVIEPRESS TEASER SAVE

if(isset($_POST['adminArray']['teaser_timer'])){



	// LAYOUT CHOICE
	$f = (int)trim($_POST['teaser_enabled']);
	update_option("teaser_enabled", $f);
}

/*****************************************************************************
/************** SHOPPERPRESS DESIGN SETUP OPTIONS **************************/


if(isset($_POST['admin_page']) && $_POST['admin_page'] == "shopperpress_setup"){

	// DEFAUL HOME PAGE TICK BOX
	$check_right = (int)trim($_POST['display_default_homepage']);
	update_option("display_default_homepage", $check_right);

	// LAYOUT CHOICE
	$f = (int)trim($_POST['display_themecolumns']);
	update_option("display_themecolumns", $f);

	if($f == 2){ update_option("display_sidebar_basket", "right"); }	
}





/*****************************************************************************
/************** auctionpress DESIGN SETUP OPTIONS **************************/

if(isset($_POST['admin_page']) && $_POST['admin_page'] == "auctionpress_setup"){


	// LAYOUT CHOICE
	$f = (int)trim($_POST['display_themecolumns']);
	update_option("display_themecolumns", $f);

}



/*****************************************************************************
/************** DIRECTORYPRESS DESIGN SETUP OPTIONS **************************/

if(isset($_POST['admin_page']) && $_POST['admin_page'] == "directorypress_setup"){


	// LAYOUT CHOICE
	$f = (int)trim($_POST['display_themecolumns']);
	update_option("display_themecolumns", $f);

}



/*******************************************************************************/


// DELETE IMAGES (MULTIPLE)
if(isset($_POST['deleteimages'])){
	for($i = 1; $i < 50; $i++) { 
		if(isset($_POST['d'. $i]) && $_POST['d'.$i] == "on"){ 
			unlink(get_option("imagestorage_path").$_POST['d'.$i.'-id']);
			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= "Images Deleted Successfully";
		}
	}
}


 
// CUSTOM FIELD OPTIONS
if(isset($_POST['customfield']) && is_array($_POST['customfield'])){

	$i=1; while($i < 20){
	$_POST['customfield'][$i]['key'] = str_replace(" ","",$_POST['customfield'][$i]['key']);
	$i++;
	}
 
	update_option("customfielddata",$_POST['customfield']);
}

 
 
 
 
 
 
 
 /***************************************** REALTORPRESS *********************************************** */

//  PACKAGES AND PACKAGE OPTIONS
if(isset($_POST['package']) && is_array($_POST['package'])){
	update_option("packages",$_POST['package']);	  
}

 // if setup
if(isset($_POST['submit'])){
		$enable_listing = (int)trim($_POST['display_submit']);
		update_option("display_submit", $enable_listing);
}
if(isset($_POST['package1'])){
		$check_enabled = (int)trim($_POST['pak_enabled']);
		update_option("pak_enabled", $check_enabled);
}
 
 

// NORMAL SUBMIT FORM VALUES
if(isset($_POST['submitted']) && $_POST['submitted'] == "yes"){

if(isset($_POST['ctax'])){

$_POST['adminArray']['citytax_'.$_POST['form']['state']] = $_POST['citytax'];

// die($_POST['adminArray']['citytax_'.$_POST['form']['state']]."<--".$_POST['form']['state']."--".$_POST['citytax']);
}



	$update_options = $_POST['adminArray']; 
	if(is_array($update_options )){
	foreach($update_options as $key => $value){
		update_option( trim($key), trim($value) );
	} }
 
 
	// ADVERTISING OPTIONS
	if(isset($_POST['advertise'])){
		$check_right = (int)trim($_POST['advertising_right_checkbox']);
		$check_left = (int)trim($_POST['advertising_left_checkbox']);
		$check_top = (int)trim($_POST['advertising_top_checkbox']);
		$check_footer = (int)trim($_POST['advertising_footer_checkbox']);
		update_option("advertising_right_checkbox", $check_right);
		update_option("advertising_left_checkbox", $check_left);
		update_option("advertising_top_checkbox", $check_top);
		update_option("advertising_footer_checkbox", $check_footer);
	}		
 
		
 
				



	// admin 2 - categories
	if(isset($_POST['fea_cat']) && is_array($_POST['fea_cat']) ){	 
		update_option("fea_cats",$_POST['fea_cat']);	
	}
 
 
 
 
 
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Changes Saved Successfully";          
}








/********************************* COUPONPRESS SPECIAL OPTIONS *************************/


if(isset($_POST['featuredstores']) && is_array($_POST['featured_stores']) ){	 

	update_option("featured_stores",$_POST['featured_stores']);	

}


if(isset($_POST['icodes_update_categorylist']) && $_POST['adminArray']['icodes_subscription_username'] != ""){

global $PPTImport;

	$QueryString  = $_POST['adminArray']['icodes_country']."?";
	$QueryString .= "UserName=".$_POST['adminArray']['icodes_subscription_username'];
	$QueryString .= "&SubscriptionID=".$_POST['adminArray']['icodes_subscriptionID'];
	$QueryString .= "&RequestType=CategoryList";
	
	$xml = $PPTImport->GetIcodesData($QueryString,$_POST['adminArray']['icodes_importtype']);
	
	$c=0; $categorylist = array();
	$total_items += trim($xml->Results);
	$message1 = trim($xml->Message);
	if($message1==''){
		foreach ($xml->item as $item) {	
	 
			$categorylist[$c]['id'] 	= str_replace("","",$item->id);
			$categorylist[$c]['name'] 	= str_replace("","",$item->category);			 
			$c++;
		}
	}else{ die("<h1>Category List Update Failed</h1><p>".$xml->Message."</p><p>".$QueryString."</p>");}	
	
	update_option("icodes_categorylist",$categorylist);	
}


if(isset($_POST['icodes_update_networklist'])  && $_POST['adminArray']['icodes_subscription_username'] != ""){

	$QueryString  = $_POST['adminArray']['icodes_country']."?";
	$QueryString .= "UserName=".$_POST['adminArray']['icodes_subscription_username'];
	$QueryString .= "&SubscriptionID=".$_POST['adminArray']['icodes_subscriptionID'];
	$QueryString .= "&RequestType=NetworkList";
	$xml = $PPTImport->GetIcodesData($QueryString,$_POST['adminArray']['icodes_importtype']);

	$c=0; $categorylist = array();
	$total_items += trim($xml->Results);
	$message1 = trim($xml->Message);
	if($message1==''){
		foreach ($xml->item as $item) {	
	  
			$categorylist[$c]['id'] 	= str_replace("","",$item->id);
			$categorylist[$c]['name'] 	= str_replace("","",$item->network);			 
			$c++;
		}
	}else{ die("<h1>Network List Update Failed</h1><p>".$xml->Message."</p><p>".$QueryString."</p>");}	
 
	update_option("icodes_networklist",$categorylist);	
}

if(isset($_POST['icodes_update_merchantlist'])  && $_POST['adminArray']['icodes_subscription_username'] != "" ){

	$QueryString  = $_POST['adminArray']['icodes_country']."?";
	$QueryString .= "UserName=".$_POST['adminArray']['icodes_subscription_username'];
	$QueryString .= "&SubscriptionID=".$_POST['adminArray']['icodes_subscriptionID'];
	$QueryString .= "&RequestType=MerchantList&Action=Full".get_option("icodes_Relationship");
 //die($QueryString);
	$xml = $PPTImport->GetIcodesData($QueryString,$_POST['adminArray']['icodes_importtype']);
 
	$c=0; $categorylist = array();
	$total_items += trim($xml->Results);
	$message1 = trim($xml->Message);
	if($message1==''){
		foreach ($xml->item as $item) {
		 
	   //die(print_r($item));
			$categorylist[$c]['id'] 			= str_replace("","",$item->icid);
			$categorylist[$c]['name_merchant'] 	= str_replace("","",$item->merchant);
			$categorylist[$c]['mid'] 	= str_replace("","",$item->merchant_id);
			
			//$categorylist[$c]['name'] 			= str_replace("","",$item->network);
			//$categorylist[$c]['category'] 		= str_replace("","",$item->category);
			//$categorylist[$c]['logo'] 			= str_replace("","",$item->merchant_logo_url);
					 
			$c++;
		}
	}else{ die("<h1>Merchant List Update Failed</h1><p>".$xml->Message."</p><p>".$QueryString."</p>");}	

	update_option("icodes_merchantlist",$categorylist);	
	
	 
}

if(isset($_POST['icodes_save_me'])){

//die(print_r($_POST));

	$cList = get_option("icodes_savelist");
	update_option("icodes_savelist",""); 
	if(is_array($cList)){ $d= count($cList); }else{ $d=0; }

	$cList[$d]['ID'] 			= $d;	
	$cList[$d]['RequestType'] 	= $_POST['icodes_s_1'];
	$cList[$d]['Action'] 		= $_POST['icodes_s_2'];
	$cList[$d]['ActionID'] 		= $_POST['icodes_s_3'];
	$cList[$d]['Sort'] 			= $_POST['icodes_s_4'];	
	$cList[$d]['Map'] 			= $_POST['icodes_s_5'];	
	$cList[$d]['Time'] 			= $_POST['icodes_s_6'];	
	$cList[$d]['Page'] 			= 1;	
	$cList[$d]['PageSize'] 		= 10;	
	$cList[$d]['PageTotal'] 	= 0;
	$cList[$d]['CountGood'] 	= 0; 
	$cList[$d]['CountBad'] 		= 0;
			
	update_option("icodes_savelist",$cList);	
	
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "iCodes Scheduled Search Saved Successfully";
}
function pptreindex_array($src) {

	if(!is_array($src)){ return; }

    $dest = array();
	$i=0; 
    foreach ($src as $value) {
        if (is_array($value)) {
		
			foreach ($value as $k => $v) {
				if($k == "ID"){
					$dest[$i][$k] = $i;
				}else{
					$dest[$i][$k] = $v;
				}			 
			}         
		   
		$i++;   
        }		
    }

    return $dest;
}
if(isset($_GET['icodes_s_del']) && !isset($_POST['icodes_save_me']) ){

$c=0;
 	$cff = get_option("icodes_savelist");
 	update_option("icodes_savelist",""); 
	foreach($cff as $key=> $d){
	
		if($_GET['icodes_s_del'] != $d['ID']){
	
		$cList[$c]['ID'] 			= $d['ID'];	
		$cList[$c]['RequestType'] 	= $d['RequestType'];
		$cList[$c]['Action'] 		= $d['Action'];
		$cList[$c]['ActionID'] 		= $d['ActionID'];
		$cList[$c]['Sort'] 			= $d['Sort'];	
		$cList[$c]['Map'] 			= $d['Map'];	
		$cList[$c]['Time'] 			= $d['Time'];	
		$cList[$c]['CountGood'] 	= $d['CountGood'];
		$cList[$c]['CountBad'] 		= $d['CountBad'];
		$cList[$c]['Page'] 			= $d['Page'];
		$cList[$c]['PageSize'] 		= $d['PageSize'];
		$cList[$c]['PageTotal'] 	= $d['PageTotal'];
		$c++;				
		}
	
	}
	
	$cList = pptreindex_array($cList);
	
	update_option("icodes_savelist",$cList);
	
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "iCodes Scheduled Search Deleted";
}

if(isset($_GET['icodes_run'])){

	$num = $PPTImport->ICODESIMPORT($_GET['icodes_run']);
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "info"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "This test imported ".$num." coupons.";
}

if(isset($_GET['icodes_debug'])){

	$PPTImport->ICODESIMPORT($_GET['icodes_run'],true);
 
}
/***************************************** DIRECTORYPRESS *********************************************** */

 
 
 		// if setup
		if(isset($_POST['featured'])){
		$check_right = (int)trim($_POST['display_featuredbox']);
		update_option("display_featuredbox", $check_right);
		$check_right = (int)trim($_POST['display_middle_featuredbox']);
		update_option("display_middle_featuredbox", $check_right);
		$check_right = (int)trim($_POST['display_featured_image_enable']);
		update_option("display_featured_image_enable", $check_right); 
		}		
		// if setup
		if(isset($_POST['featured1'])){				
		$check_right = (int)trim($_POST['display_featuredbox1']);
		update_option("display_featuredbox1", $check_right);
		$check_right = (int)trim($_POST['display_featuredbox2']);
		update_option("display_featuredbox2", $check_right);
		$check_right = (int)trim($_POST['display_featuredbox3']);
		update_option("display_featuredbox3", $check_right);
		$check_right = (int)trim($_POST['display_featuredbox4']);
		update_option("display_featuredbox4", $check_right);		
		}
 
 
 /*********************************************************************************************************/ 












/********************************* DIRECTORYPRESS SPECIAL OPTIONS *************************/



if(isset($_POST['premiumpress_import'])){

	include(TEMPLATEPATH."/PPT/func/func_import.php");
	$PPIMPORT = new PremiumPressImport();
	$result = $PPIMPORT->StartImport($_POST['system'],$_POST['table_prefix']);
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "The system successfully imported ".$result." from your ".$_POST['system']." database";

}




// DOMZ IMPORT TOOLS
if(isset($_POST['domz']) && $GLOBALS['sf']==0){
include(TEMPLATEPATH."/PPT/func/func_domz.php");
 
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "You successfully imported ".$i." Links";  
}




// BULK CATEGORY IMPORT
if(isset($_POST['catme'])){ 
	global $wpdb;	 $pCat = "yes"; $skip=false;
	
	
	$cat = explode(",",trim(ereg_replace( "\n", ",", $_POST['cats'])));
	foreach($cat as $catName){
	
	if($_POST['pcat'] != "0"){
		wp_create_category1($catName, $_POST['pcat']); 
	}else{
	
	
		$catName = strip_tags(trim($catName));
		 
		if($catName == "["){   $pCat = "no"; $skip=true; }elseif($catName == "]"){ $pCat =  "yes"; $skip=true; } // setup parent cats
	  
	  
	  	if($catName != "[" && $catName != "]"){
			if($pCat ==  "yes"){		 
				$args = array('cat_name' => $catName ); 
				$cat_id = wp_insert_term($catName, 'category', $args);	
			
			}else{
			 
				if(!isset($cat_id->errors['term_exists'][0])){
				wp_create_category1($catName, $cat_id['term_id']);
				}
			}		
		}	 
	
	}
	//wp_create_category1('Affiliate Sub Category 2', $cat_id['term_id']);			
		
 	} 
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Categories Created Successfully";		
}


// CSV FILE IMPORT
if(isset($_POST['csvimport'])){

global $PPT;

	if(strlen($_FILES['import']['tmp_name']) > 0 || $_POST['file_csv'] !="0" ){
	 
	if($_POST['file_csv'] == "0"){
		$filename = $_FILES['import']['tmp_name'];
	}else{
		$path = FilterPath();
		$HandlePath =  str_replace("wp-admin","",$path)."/wp-content/themes/".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/thumbs/";
		$filename = $HandlePath.$_POST['file_csv'];
	}
 
	
	$numB = parse_csv_file($filename, $_POST['heading'], $_POST['del'], $_POST['enc'], $_POST['rq'], $_POST['csv']['cat'],$_POST['type']);		
	$totals = explode("**",$numB);			
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= $totals[0]." Products Added <br /> ".$totals[1]." Products Updated";								
	}else{
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Please select a CSV file to import.";
	}  
}


////////////////////// CHILD THEME UPLOAD


if(isset($_FILES['childtheme']) && strlen($_FILES['childtheme']['name']) > 4){

	if(substr($_FILES['childtheme']['name'], -3) != "zip"){
	
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= "Child Themes should be uploaded as a .zip file. Please select a .zip file.";
	
	}else{
	
		if(is_writable( str_replace("thumbs","themes",get_option('imagestorage_path')))){
		
		
			$copy = @copy($_FILES['childtheme']['tmp_name'], str_replace("thumbs","themes",get_option('imagestorage_path')).$_FILES['childtheme']['name']);
				
			if($copy){	
			
				 				
				
				include(TEMPLATEPATH."/PPT/class/class_pclzip.php");  
				$zip = new PclZip(str_replace("thumbs","themes",get_option('imagestorage_path')).$_FILES['childtheme']['name']);
		
		
				if ($zip->extract(PCLZIP_OPT_PATH, str_replace("thumbs","themes",get_option('imagestorage_path'))) == 0) {
				
				$GLOBALS['error'] 		= 1;
				$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
				$GLOBALS['error_msg'] 	= "Child Theme uploaded successfully.";
				
				}else{
				
				$GLOBALS['error'] 		= 1;
				$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
				$GLOBALS['error_msg'] 	= "Upload Failed";
				
				}
						
			}	
		
		}else{
		
		
				$GLOBALS['error'] 		= 1;
				$GLOBALS['error_type'] 	= "info"; //ok,warn,error,info
				$GLOBALS['error_msg'] 	= "Your themes folder path is not CHMOD 777 (writable) therefore new child themes cannot be upload. <br>PAth is: ".str_replace("thumbs","themes",get_option('imagestorage_path'));	
		
		}
	
	}

}












/********************************* SHOPPERPRESS SPECIAL OPTIONS *************************/

	// if setup
	if(isset($_POST['weight'])){
		$check_right = (int)trim($_POST['enable_weightshipping']);
		update_option("enable_weightshipping", $check_right);
	}
 
	// if setup
	if(isset($_POST['promo'])){
		$check_right = (int)trim($_POST['enable_promotionqty']);
		update_option("enable_promotionqty", $check_right);
	}
	
	
 
	// if shipping method 
	if(isset($_POST['shipping_method'])){
	
		$b=1;while($b < 11){	
		$pack1_b = (int)trim($_POST['pak_enable_'.$b]);
		update_option("pak_enable_".$b, $pack1_b);
		update_option("pak_name_".$b, $_POST['pak_name_'.$b]);
		update_option("pak_price_".$b, $_POST['pak_price_'.$b]);
		update_option("pak_del_".$b, $_POST['pak_del_'.$b]);		
		$b++; }
			
	}
	
	if(isset($_POST['adminArray']['custom_field1'])){	
	 

		$pack1_v = (int)trim($_POST['custom_field1_required']); 
		update_option("custom_field1_required", $pack1_v);
 
		$pack1_b = (int)trim($_POST['custom_field2_required']);
		update_option("custom_field2_required", $pack1_b);
		
		$pack1_b = (int)trim($_POST['custom_field3_required']);
		update_option("custom_field3_required", $pack1_b);
		
		$pack1_b = (int)trim($_POST['custom_field4_required']);
		update_option("custom_field4_required", $pack1_b);
		
		$pack1_b = (int)trim($_POST['custom_field5_required']);
		update_option("custom_field5_required", $pack1_b);		
	}

	// if credit packages
	if(isset($_POST['credit_packages'])){

		$pack1_b = (int)trim($_POST['credit_enable_1']);
		update_option("credit_enable_1", $pack1_b);
		$pack2_b = (int)trim($_POST['credit_enable_2']);
		update_option("credit_enable_2", $pack2_b);
		$pack3_b = (int)trim($_POST['credit_enable_3']);
		update_option("credit_enable_3", $pack3_b);
		$pack4_b = (int)trim($_POST['credit_enable_4']);
		update_option("credit_enable_4", $pack4_b);
		$pack5_b = (int)trim($_POST['credit_enable_5']);
		update_option("credit_enable_5", $pack5_b);
		
		update_option("credit_name_1", $_POST['credit_name_1']);
		update_option("credit_name_2", $_POST['credit_name_2']);
		update_option("credit_name_3", $_POST['credit_name_3']);
		update_option("credit_name_4", $_POST['credit_name_4']);
		update_option("credit_name_5", $_POST['credit_name_5']);
				
		update_option("credit_price_1", $_POST['credit_price_1']);
		update_option("credit_price_2", $_POST['credit_price_2']);
		update_option("credit_price_3", $_POST['credit_price_3']);
		update_option("credit_price_4", $_POST['credit_price_4']);
		update_option("credit_price_5", $_POST['credit_price_5']);	
				
		update_option("credit_del_1", $_POST['credit_del_1']);
		update_option("credit_del_2", $_POST['credit_del_2']);
		update_option("credit_del_3", $_POST['credit_del_3']);
		update_option("credit_del_4", $_POST['credit_del_4']);
		update_option("credit_del_5", $_POST['credit_del_5']);			
	}	
	
	
	
	
	
	
	
	
	
	
	

// DELETE COUPON CODES
if(isset($_GET['delc'])){
$i=0;
$NewArray=array();
$ArrayCoupon = get_option("coupon_array");
foreach($ArrayCoupon as $value){
	if($i !=$_GET['delc'] && strlen($value['name']) > 1){

		$NewArray[$i]['name'] = $value['name'];
		$NewArray[$i]['price'] = $value['price'];
		$NewArray[$i]['percentage'] = $value['percentage'];

	}
$i++; }
update_option("coupon_array", $NewArray);
$GLOBALS['error'] 		= 1;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "Changes Saved Successfully"; 
}

// SETUP COUPON CODES
if(isset($_POST['couponcode'])){
$NewArray=array();
$ArrayCoupon = get_option("coupon_array");
$i=0;
if(is_array($ArrayCoupon)){
	foreach($ArrayCoupon as $value){
	$NewArray[$i]['name'] 		= $value['name'];
	$NewArray[$i]['price'] 		= $value['price'];
	$NewArray[$i]['percentage'] = $value['percentage'];
	$i++;
	}
}
$NewArray[$i]['name']= $_POST['coupon']['name'];
$NewArray[$i]['price']= $_POST['coupon']['price'];
$NewArray[$i]['percentage']= $_POST['coupon']['percentage'];
update_option("coupon_array", $NewArray);
$GLOBALS['error'] 		= 1;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "Coupon Added Scuessfully";	
}

 


?>