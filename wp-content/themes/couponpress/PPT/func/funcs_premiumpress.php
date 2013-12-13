<?php

/* =============================================================================
  CSV IMPORT TOOL // V7 // 2ND APRIL
  ========================================================================== */

if(!function_exists("parse_csv_file")){

function parse_csv_file($file, $columnheadings = false, $delimiter = ',', $enclosure = "\"", $removeQuotes=true, $CATa="",$type="posts") {

// REGISTER TAXMONIES TO SAVE ERRORS
register_taxonomy( 'store', 'post', array( 'hierarchical' => true, 'labels' => "", 'query_var' => true, 'rewrite' => true ) );  
register_taxonomy( 'network', 'post', array( 'hierarchical' => true, 'label' => 'Affiliate Networks', 'query_var' => true, 'rewrite' => true ) ); 
register_taxonomy( 'location', 'post', array( 'hierarchical' => true, 'label' => 'Affiliate locations', 'query_var' => true, 'rewrite' => true ) ); 

$taxArray = get_option("ppt_custom_tax"); // to do
$TAXARRAY = array();
 
	set_time_limit(0);

	global $wpdb;
	
	if($type =="users"){	 
		require_once(ABSPATH . WPINC . '/registration.php');
		require_once(ABSPATH . WPINC . '/pluggable.php');
	}
		 
	$post_fields = array('location','store','network','category','tags','post_author','post_date','post_date_gmt','post_content','post_title','post_excerpt','post_status','comment_status','ping_status','post_password','post_name','to_ping','pinged','post_modified','post_modified_gmt','post_content_filtered','post_parent','guid','menu_order','post_type','post_mime_type','comment_count');
	 
	
	$bad_fields = array( 'ID');
	
	$AddtoCats = array();
	$user_fields = array('fname','lname','email','password','website');	
	$description="";
	$ShopperPressID = 0; 
	$ShopperPressUpdatedID = 0;

	
	$row = 1;
	$rows = array();
	$handle = fopen($file, 'r');
	$customString = array();
	
 
 
	// GET A LIST OF ALL POSTS AND THEIR sku
	$SQL = "SELECT DISTINCT post_id, meta_value FROM $wpdb->postmeta WHERE $wpdb->postmeta.meta_key = 'SKU'";
	$posts_with_SKU = $wpdb->get_results($SQL,ARRAY_A); 
 
	// SETUP COUNTERS
	$counter_failed = 0;
	$counter_success = 0;
	$counter_updated = 0;

	// GET TITLES
	$titles = fgetcsv($handle,1000,$delimiter);
 
	// LOOP THROUGH ALL CSV LINES
	while(!feof($handle)) {	
	
	// GET THE DATA FROM THE THIS LINE
	$line = fgetcsv($handle, 4096);	
		
	// CHECK WHICH SORT OF IMPORT THIS IS
	if($type =="posts"){
	
		// IF ITS VALID ARRAY - good 
		if(is_array($line)){
		
		// 1. MAKE SURE WE HAVE THE LINE HAS VALID COLUMN HEADERS // CHECK FOR TITLE ONLY
		$key = array_search('post_title', $titles);
		if(!isset($line[$key]) || $line[$key] == "" ){ $counter_failed++; continue; }
		 
		// 3. IMPORT NEW POST
		$my_post = array(); $is_new_insert = true;
		
		// CHECK THE POST DOESNT ALREADY EXISTS
		if( isset($line[array_search('SKU', $titles)]) ){ // && $line[array_search('SKU', $titles)] !=""
		
		 	// 1. check if the SKU exists within our array
			foreach($posts_with_SKU as $skuval){ 			
			
				if($skuval['meta_value'] == $line[array_search('SKU', $titles)]){
				
					if(!$is_new_insert){ continue; }
					
					$my_post['ID'] = $skuval['post_id'];
					$my_post['post_modified'] = date('Y-m-d g:i:s');
					$counter_updated++; 
					$is_new_insert = false;
				 
				}			
			}			
			//print $line[array_search('post_title', $titles)]." (".$my_post['ID'].") = ".$line[array_search('SKU', $titles)]."<br>";
			//print_r($posts_with_SKU);
		
		}		
		$post_status_types = array('pending','draft','publish');
		$my_post['post_title'] 		= $line[array_search('post_title', $titles)];
		$my_post['post_content'] 	= $line[array_search('post_content', $titles)];
		$my_post['post_excerpt'] 	= $line[array_search('post_excerpt', $titles)];
		$my_post['post_author'] 	= (is_numeric($line[array_search('post_author', $titles)]) ? $line[array_search('post_author', $titles)] : 1); 
		$my_post['post_type'] 		= (isset($line[array_search('post_type', $titles)]) && array_search('post_type', $titles) != 0 ? $line[array_search('post_type', $titles)] : "post"); 
		$my_post['post_status'] 	= (isset($line[array_search('post_status', $titles)]) && array_search('post_status', $titles) != 0 && in_array($line[array_search('post_status', $titles)],$post_status_types) ? $line[array_search('post_status', $titles)] : "publish");
		$my_post['tags_input'] 		= (isset($line[array_search('tags', $titles)]) && strlen($line[array_search('tags', $titles)]) > 5 ? explode(",",$line[array_search('tags', $titles)]) : "");
	  
	    if(isset($my_post['tags_input']) && empty($my_post['tags_input'])){ unset($my_post['tags_input']); }
	  
	   
	  
		// BUILD CATEGORY IMPORT LIST	
		if(in_array('category', $titles) && isset($line[array_search('category', $titles)]) && strlen($line[array_search('category', $titles)]) > 2){
		 
			$AddtoCats = array();	
			$cats_array = explode(",",$line[array_search('category', $titles)]);
			
			  
			foreach($cats_array as $thiscat){ if($thiscat !=""){ 
			
			$thiscat = trim($thiscat);
			
			 	// CHECK IF THE CATEGORY ALREADY EXISTS
				if ( is_term( $thiscat , 'category' ) ){
					$term = get_term_by('name', str_replace("_"," ",$thiscat), 'category');
				 
					$catID = $term->term_id;
				}else{
				
					$args = array('cat_name' => str_replace("_"," ",$thiscat) ); 
					$term = wp_insert_term(str_replace("_"," ",$thiscat), 'category', $args);
					
					if(isset($term['term_id'])){
					$catID = $term['term_id'];
					}elseif(isset($term->term_id)){
					$catID = $term->term_id;
					}
					
					 
				}
				 
				// ADD-ON NEW CATEGORY	
				array_push($AddtoCats,$catID);
										
			} } // end foreach
			
		
		}else{
		 
			if(isset($_POST['csv']['cat']) && is_array($_POST['csv']['cat'])){ 
				$cats=""; foreach($_POST['csv']['cat'] as $cat){ $cats .= $cat.",";	}
			 
				$AddtoCats = explode(",",substr($cats,0,-1));
				
			}else{
				$AddtoCats = 0; // NO CAT SELECTED
			}		
		}
		
		
		// ADD-ON CATEGORIES AND UPDATE THE COUNTER
		$my_post['post_category'] 	= $AddtoCats;
		
		if(function_exists('mb_check_encoding')){ 
			$np = array();
			foreach($my_post as $key=>$val){
				if(is_string($val)){
				$np[$key] = $val;//mb_convert_encoding($val, 'UTF-8','auto');
				//$np[$key] = utf8_encode($val);
				}else{
				$np[$key] = $val;
				}
				
			}
			$my_post = $np;
		}
		 
		// INSERT NEW POST
		$POSTID = wp_insert_post( $my_post );
		
		if($is_new_insert){ $counter_success++;	} 
		 
		// 4. IMPORT CUSTOM FIELDS		
		foreach($titles as $key=>$val){
		
			// CHECK THIS IS NOT PART OF THE POST CONTENT ARRAY
			if(!in_array($val, $post_fields)){
			
				// INSERT NEW CUSTOM FIELD			
				update_post_meta($POSTID,$val,$line[array_search($val, $titles)]);
			}	
			 
		} // end foreach
		
		
		// 5. CHECK FOR CUSTOM FIELDS
		$custom_taxonony_fields = array('store','network','location');
		foreach($custom_taxonony_fields as $tax){
 
			// IF HAS VALUE
			if( in_array($tax, $titles) && isset($line[array_search($tax, $titles)]) && $line[array_search($tax, $titles)] !="" && $line[array_search($tax, $titles)] !="1" ){
			  
				foreach(explode(",", $line[array_search($tax, $titles)]) as $value){ if(strlen($value) > 1){  // die($value."<--".$tax." -- ".$line[array_search($tax, $titles)]);
				
					if ( is_term( $value , $tax ) ){
						$term = get_term_by('name', str_replace("_"," ",$value), $tax);
						$taxID = $term->term_id;
					}else{
						$args = array('cat_name' => str_replace("_"," ",$value) ); 
						$term = wp_insert_term(str_replace("_"," ",$value), $tax, $args);
						$taxID = $term->term_id;
					}
					// INSERT NEW TAX
					wp_set_post_terms( $POSTID, $taxID, $tax, true );
				
				} } // end foreach
							
			} // end if		
		
		}
	
	} // end while loop
		
		
 	// END IF POST TYPE
 	}elseif($type =="users"){

 
		// MUST BE AN ARRAY AND NOT FIRST ROW
		if(is_array($line)){ 
		
		 
		// BUILT USER DATA
		$userdata = array();
		$userdata['username'] 	= (isset($line[array_search('username', $titles)]) ? $line[array_search('username', $titles)] : "");
		$userdata['email'] 		= (isset($line[array_search('email', $titles)]) ? $line[array_search('email', $titles)] : "email".$row."@email.com");
		$userdata['password'] 	= (isset($line[array_search('password', $titles)]) ? $line[array_search('password', $titles)] : "password");
	  
		if(strlen($userdata['username']) > 1){
		
			$USERID = wp_create_user($userdata['username'], $userdata['password'], $userdata['email']);
			//$userdata['ID'] = $USERID;
			//wp_update_user($userdata);
			$counter_success++;
		
		}else{
		
		$counter_failed++;
		
		}
	
 
	
	}// end check array
	
	 
	
	} // end if
	
	
	
	
	$row++; // incremnt row	 
	} /* end while */
 
	fclose($handle);
	
	return $counter_success."**".$counter_updated."**".$counter_failed;
}

}



/* =============================================================================
  PUTS A NEW FIELD INTO THE POSITION YOU WANT IN THE SUBMISSION FORM // V7 // 30 MARCH
  ========================================================================== */

function field_put_to_position(&$oldarray, $object, $position)
{
        $count = 0;
        $newarray = array();
        foreach ($oldarray as $k) 
        {   
                // insert new object
                if ($count == $position){  
                
				// ADD IN NEW FIELD  
                $newarray[$count] = $object;
				
				$count++;				
				// ADD OLD FIELD BACK IN
                $newarray[$count] = $k;
                     
                }else{ 
                // insert old object
                $newarray[$count] = $k; 
               
				}
		$count++;
        }   
 
        return $newarray;
}

/* =============================================================================
  LANGUAGE FUNCTION // V6 // REMOVED IN V7
  ========================================================================== */
 
if(!function_exists("SPEC")){

	function SPEC($in_str){
 
	global $wpdb;
	
 	
	if(!function_exists('mb_check_encoding')){ return $in_str; }
	
		if(is_array($in_str)){
			$barray = array();
			foreach($in_str as $key => $word){
			
			if(is_array($word)){ continue; }
		 
			  $cur_encoding = mb_detect_encoding($word);	
				  if($cur_encoding == "UTF-8" && mb_check_encoding($word,"UTF-8")){				
				  }elseif(isset($GLOBALS['premiumpress']['language']) && $GLOBALS['premiumpress']['language'] =="language_chinese"){
					$word = mb_convert_encoding($word, 'UTF-8','auto'); 
				  }else{
					$word = utf8_encode($word);
				  } 
	
				$barray[$key]=$word;
			}
	
		return $barray;
		
		}else{
		  $cur_encoding = mb_detect_encoding($in_str) ;
		  if($cur_encoding == "UTF-8" && mb_check_encoding($in_str,"UTF-8")){
			return $in_str;
		 }elseif(isset($GLOBALS['premiumpress']['language']) && $GLOBALS['premiumpress']['language'] =="language_chinese"){
		  return mb_convert_encoding($in_str, 'UTF-8','auto'); 
		  }else{
		  return utf8_encode($in_str);
		  } 
		} 
	}
	
}



	
 /* =============================================================================
   CUSTOM FIELD DISPLAY FUNCTION
   ========================================================================== */

function GetCustomFieldList($value1="", $key="meta_key"){
	
		global $wpdb;
		
		$SQL = "SELECT ".$key." FROM $wpdb->postmeta WHERE meta_key !='_".$wpdb->prefix."page_template' AND meta_key !='_edit_last' AND meta_key !='_edit_lock' AND meta_key !='_encloseme' AND meta_key !='_pingme' GROUP BY ".$key."";
	
		$last_posts = (array)$wpdb->get_results($SQL);
		
		print "<option value='title' ";if((is_array($value1) && in_array("title",$value1)) || ( !is_array($value1) && $value1 == "title") ){print "selected"; } print ">Post Title</option>";
		print "<option value='author'";if( (is_array($value1) && in_array("author",$value1)) || ( !is_array($value1) && $value1 == "author") ){print "selected"; } print ">Post Author</option>";
		print "<option value='modified'";if( (is_array($value1) && in_array("modified",$value1)) || ( !is_array($value1) && $value1 == "modified") ){print "selected"; } print ">Last Modified</option>";
		print "<option value='comment_count'";if( (is_array($value1) && in_array("comment_count",$value1)) || ( !is_array($value1) && $value1 == "comment_count") ){print "selected"; } print ">Comment Count</option>";
		 
	
		foreach($last_posts as $value){
			
			if(is_array($value1) && in_array($value->meta_key,$value1)){
				print "<option value='".$value->meta_key."' selected>".$value->meta_key."</option>";
			}elseif(!is_array($value1) && $value1 == $value->meta_key){
				print "<option value='".$value->meta_key."' selected>".$value->meta_key."</option>";
			}else{
				print "<option value='".$value->meta_key."'>".$value->meta_key."</option>";
			}		
		
		}	
	
}


 /* =============================================================================
   BASIC PHP FUNCTIONS // V7 //
   ========================================================================== */
 
if(!function_exists('nl2br2')){
	function nl2br2($string) {
	 $string = str_replace('\\r\\n', '<br>', $string);
		$string = str_replace('', '<br>', $string);
		$string = str_replace('', '', $string);
		$string = stripslashes($string);
		return $string;
	} 
}
if(!function_exists('multisort')){
function multisort($array, $sort_by) {
 
 		if(!is_array($array)){ return; }
		
		foreach ($array as $key => $value) {
			$evalstring = '';
			foreach ($sort_by as $sort_field) {
				$tmp[$sort_field][$key] = $value[$sort_field];
				$evalstring .= '$tmp[\'' . $sort_field . '\'], ';
			}
		}
		$evalstring .= '$array';
		$evalstring = 'array_multisort(' . $evalstring . ');';
		eval($evalstring);
	
		return $array;
}
}
if(!function_exists('FilterPath')){
function FilterPath(){	
		$path=dirname(realpath($_SERVER['SCRIPT_FILENAME']));
		$path_parts = pathinfo($path); 
		if($path == ""){
			return $_SERVER['DOCUMENT_ROOT'];
		}else{
			$path = $path_parts['dirname'];
			if($path_parts['basename'] != ""){ $path .= "/".$path_parts['basename']; }
			return $path;
		}		
}
}
if(!function_exists('RandomID')){
function RandomID($PasswordLenght = 10) {  
		$pass=""; 
		$salt = "0123456789123456789123456789123456789"; 
		srand((double)microtime()*1000000); 
		$i = 0; while ($i <= $PasswordLenght) {  $num = rand() % 33; $tmp = substr($salt, $num, 1); $pass = $pass . $tmp; $i++; }  
		return $pass; 
}
}

/* =============================================================================
   ORDER CATEGORY/PAGE RESULTS BY HIERCHY
   ========================================================================== */

function get_page_hierchy($parent,$args){ // updated Jan 7th 2012

	global $wpdb;
	
	$cats = get_pages($args);
  
	$ret = new stdClass;
	foreach($cats as $cat){
 
		if(  $cat->post_parent==$parent){
			$id = $cat->ID;
			$ret->$id = $cat;
			$ret->$id->children = get_page_hierchy($id,$args);
		}
	}

	return $ret;
}

function get_cat_hierchy($parent,$args){ // updated Jan 7th 2012

	global $wpdb;
	
	$cats = get_categories($args);
	$ret = new stdClass;

	foreach($cats as $cat){
		if($cat->parent==$parent){
			$id = $cat->cat_ID;
			$ret->$id = $cat;
			$ret->$id->children = get_cat_hierchy($id,$args);
		}
	}

	return $ret;
}

/* =============================================================================
   Short Codes / V7 / Feb 25th
   ========================================================================== */

function ppt_shortcode_widths_one_half( $atts, $content = null ) {  return '<div class="f_half left">' . do_shortcode($content) . '</div>'; }
function ppt_shortcode_widths_f1( $atts, $content = null ) {  return '<div class="f1 left">' . do_shortcode($content) . '</div>'; }
function ppt_shortcode_widths_f2( $atts, $content = null ) {  return '<div class="f2 left">' . do_shortcode($content) . '</div>'; }
function ppt_shortcode_widths_f3( $atts, $content = null ) {  return '<div class="f3 left">' . do_shortcode($content) . '</div>'; }
function ppt_shortcode_widths_f4( $atts, $content = null ) {  return '<div class="f4 left">' . do_shortcode($content) . '</div>'; }

add_shortcode('one_half', 	'ppt_shortcode_widths_one_half');
add_shortcode('one_third', 	'ppt_shortcode_widths_f3');
add_shortcode('one_forth', 	'ppt_shortcode_widths_f1');
add_shortcode('two_thirds', 'ppt_shortcode_widths_f2');
add_shortcode('three_forths', 'ppt_shortcode_widths_f4');


function ppt_shortcode_formatter($content) {
	$new_content = '';

	/* Matches the contents and the open and closing tags */
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';

	/* Matches just the contents */
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';

	/* Divide content into pieces */
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	/* Loop over pieces */
	foreach ($pieces as $piece) {
		/* Look for presence of the shortcode */
		if (preg_match($pattern_contents, $piece, $matches)) {

			/* Append to content (no formatting) */
			$new_content .= $matches[1];
		} else {

			/* Format and append to content */
			$new_content .= wptexturize(wpautop($piece));
		}
	}

	return $new_content;
}

// Remove the 2 main auto-formatters
remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

// Before displaying for viewing, apply this function
add_filter('the_content', 'ppt_shortcode_formatter', 99);
add_filter('widget_text', 'ppt_shortcode_formatter', 99);

/* =============================================================================
   IMAGE CLEAN UP ADDED IN V7 / Feb 20th
   ========================================================================== */
	
function DELETEIMAGES(){
 
$result = read_all_files(get_option("imagestorage_path"),"unknown");

if(is_array($result)){

	foreach($result['files'] as $file){
	
		$fb = explode("unknown-",$file);
		if(isset($fb[1])){
			unlink(get_option("imagestorage_path")."unknown-".$fb[1]);
		} 
	}
}

}

/*****************************************************************
	IMAGE DISPLAY FOR ADMIN FILE MANAGER AND MEMBERS PAGE
*******************************************************************/

function read_all_files($root = '.',$keyword=""){ 
  $files  = array('files'=>array(), 'dirs'=>array()); 
  $directories  = array(); 
  $last_letter  = $root[strlen($root)-1]; 
  $root  = ($last_letter == '\\' || $last_letter == '/') ? $root : $root.DIRECTORY_SEPARATOR; 
  
  $directories[]  = $root; 
  
  $image_path = get_option("imagestorage_path");
  $image_http = get_option("imagestorage_link");
  
  while (sizeof($directories)) { 
    $dir  = array_pop($directories); 
    if ($handle = opendir($dir)) { 
      while (false !== ($file = readdir($handle))) { 
        if ($file == '.' || $file == '..') { 
          continue; 
        } 
        $file  = $dir.$file; // 
        if (is_dir($file)) { 
		
		 if (strpos($file, "cache") === false) { 
		 
          $directory_path = $file.DIRECTORY_SEPARATOR; 
          array_push($directories, $directory_path); 
          $files['dirs'][]  = str_replace($image_path,"",$directory_path); 
		  
		 }else{ }
		  
        } elseif (is_file($file)) { 
		
			if($keyword == ""){
			$files['files'][]  = str_replace($image_path,$image_http,$file);
			}else{
			
				$pos = strpos(str_replace($image_path,"",$file), $keyword);
				if ($pos === false) { }else {
					$files['files'][]  = str_replace($image_path,$image_http,$file);
				}			
			}
        } 
      } 
      closedir($handle); 
    } 
  } 
  
  return $files; 
}
function CheckExt($filename){
$goodexts = array("jpg",'gif','jpeg','bmp','png');
$ext = substr($filename,-3);
if(in_array(strtolower($ext),$goodexts)){return $filename;}
switch($ext){
	case "flv":{return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/video.png"; } break;
	case "tml":{return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/code.png"; } break;
	case "doc":{return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/text.png"; } break;
	default: { return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/interactive.png"; };
} 
return $ext;
}
 
/*****************************************************************
	THIS FUNCTION IS USED TO CLEAN DATABASE INPUT
*******************************************************************/
function PPTOUTPUT($string,$key="")
{  
 
	if(is_string($string)) {
	
		if($key == "description" || $key == "short"){
		
			if($key == "description" && isset($_POST['htmlcode'])){ 
			
			$string = preg_replace('/<p[^>]*>/', '', $string); // Remove the start <p> or <p attr="">
			//$string = preg_replace('</p>', '', $string); // Replace the end
			 
			$string = stripslashes($string);
			
			}else{
				$string = stripslashes(strip_tags($string));				
			}
			
		}else{
		
			$string = stripslashes(strip_tags($string));
		 
		}		
	
	}elseif(is_array($string)){
	 
		$newArray = array();
		foreach($string as $key => $value) {
			$newArray[$key] = PPTOUTPUT($string[$key],$key);
		}
		
		return $newArray;
	}

	return $string;

}
function PPTCLEAN($string,$type='')
{ 
 
	if($type == "textarea" || $type == "short"){
		return nl2br(strip_tags($string));
	}	

	if(is_string($string)) { 
		  if(get_magic_quotes_gpc())  // prevents duplicate backslashes
		  {
			$string = stripslashes($string);
		  }
		  if (phpversion() >= '4.3.0')
		  {
			$string = mysql_real_escape_string($string);
		  }
		  else
		  {
			$string = mysql_escape_string($string);
		  } 
		  
				
		return $string;
		
	}elseif(is_array($string)) {
		 
		foreach($string as $key => $value) {
			PPTCLEAN($string[$key]); 
		}
	} 
	

	
	return $string;
}



/*****************************************************************
	THIS FUNCTION IS USED TO STIP SLAHES FROM GLOBAL ARRAYS
*******************************************************************/
 
if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {  // PHP6 safe 

	if(!function_exists('array_stripslashes')) 
	{ 
		function array_stripslashes(&$var) 
		{ 
		if(is_string($var)) 
		$var = stripslashes($var); 
		else 
		if(is_array($var)) 
		foreach($var as $key => $value) 
		array_stripslashes($var[$key]); 
		} 
	}
	
	array_stripslashes($_GET); 
	array_stripslashes($_POST); 
	array_stripslashes($_COOKIE); 
	array_stripslashes($_REQUEST); 
	array_stripslashes($_FILES); 

}






function GetThemeName(){

	global $wpdb;
	$tpath = get_bloginfo("template_url");
	$t = explode("themes/",$tpath);
	return $t[1];

}




 

/*
		CATEGORY TOOLS FOR IMPORT
*/
if(!function_exists("wp_create_category1")){

	function wp_create_category1( $cat_name, $parent = 0 ) {
	if ( $id = category_exists1($cat_name) )
		return $id;

	return wp_insert_category1( array('cat_name' => $cat_name, 'category_parent' => $parent) );
}

}
if(!function_exists("category_exists1")){

	function category_exists1($cat_name, $parent = 0) {
		$id = term_exists($cat_name, 'category', $parent);
		if ( is_array($id) )
			$id = $id['term_id'];
		return $id;
	}

}

if(!function_exists("wp_insert_category1")){ 

	function wp_insert_category1($catarr, $wp_error = false) {
	
	$cat_defaults = array('cat_ID' => 0, 'cat_name' => '', 'category_description' => '', 'category_nicename' => '', 'category_parent' => '');
	$catarr = wp_parse_args($catarr, $cat_defaults);
	extract($catarr, EXTR_SKIP);

	if ( trim( $cat_name ) == '' ) {
		if ( ! $wp_error )
			return 0;
		else
			return new WP_Error( 'cat_name', __('You did not enter a category name.') );
	}

	$cat_ID = (int) $cat_ID;

	// Are we updating or creating?
	if ( !empty ($cat_ID) )
		$update = true;
	else
		$update = false;

	$name = $cat_name;
	$description = $category_description;
	$slug = $category_nicename;
	$parent = $category_parent;

	$parent = (int) $parent;
	if ( $parent < 0 )
		$parent = 0;

	if ( empty($parent) || !category_exists1( $parent ) || ($cat_ID && cat_is_ancestor_of($cat_ID, $parent) ) )
		$parent = 0;

	$args = compact('name', 'slug', 'parent', 'description');

	if ( $update )
		$cat_ID = wp_update_term($cat_ID, 'category', $args);
	else
		$cat_ID = wp_insert_term($cat_name, 'category', $args);

	if ( is_wp_error($cat_ID) ) {
		if ( $wp_error )
			return $cat_ID;
		else
			return 0;
	}

	return $cat_ID['term_id'];
	}

}

function stripchars($val){

$val1 = str_replace("¡ê","&pound;",$val);
return $val1;

}



if(!function_exists("AmazonSearchSave")){

 function AmazonSearchSave($result){

	global $wpdb; 

	$count=0; 
	
	if(!isset($importcounter)){	$importcounter=0; }

	foreach($result->Items->Item as $val){
	
	
	 $SQL = "SELECT count($wpdb->postmeta.meta_key) AS total
	 FROM $wpdb->postmeta
	 WHERE $wpdb->postmeta.meta_key='amazon_guid' AND $wpdb->postmeta.meta_value = '".$val->ASIN."'
	 LIMIT 1";	
		
		 
	 $result = mysql_query($SQL);			 
	 $array = mysql_fetch_assoc($result);
	
	 if($array['total'] > 0){ 
	 
	 }else{
 
 
	$data['title'] 		= str_replace("!!aaqq","",$val->ItemAttributes->Title);
	$data['asin'] 		= str_replace("!!aaqq","",$val->ASIN);
	$data['url'] 		= str_replace("!!aaqq","",$val->DetailPageURL);
 
	 
		if($_POST['amazon']['country'] =="co.uk" || $_POST['amazon']['country'] =="jp"){	
			$data['price'] 	=  substr($val->ItemAttributes->ListPrice->FormattedPrice,2,10);
			if( $data['price'] =="" ){
				$data['price'] 		= substr($val->OfferSummary->LowestNewPrice->FormattedPrice,2,10);
			}
		}elseif($_POST['amazon']['country'] =="de" || $_POST['amazon']['country'] =="fr"){
			$data['price'] 	=  substr($val->OfferSummary->LowestNewPrice->FormattedPrice,4,10);
		}elseif($_POST['amazon']['country'] =="ca"){
			$data['price'] 	=  substr($val->OfferSummary->LowestNewPrice->FormattedPrice,5,10);
		}else{	
			if( isset($val->OfferSummary->LowestNewPrice->Amount) ){	
			$data['price'] 		= substr($val->OfferSummary->LowestNewPrice->FormattedPrice,1,10);
			$data['old_price'] 	=  substr($val->ItemAttributes->ListPrice->FormattedPrice,1,10);
			}else{
			$data['price'] 	=  substr($val->ItemAttributes->ListPrice->FormattedPrice,1,10);
			}
		}
	
		$data['qty']		= str_replace("!!aaqq","",$val->ItemAttributes->NumberOfItems);
		$data['desc']		= nl2br(str_replace(".",",<br/>",$val->EditorialReviews->EditorialReview->Content));
		$data['image'] 		= str_replace("!!aaqq","",$val->LargeImage->URL);
		$data['thumbnail']	= str_replace("!!aaqq","",$val->MediumImage->URL);
		$data['images'] ="";
		$data['warranty']	= str_replace("!!aaqq","",$val->ItemAttributes->Warranty);
		
		// IMAGE SETS	
		if(isset($val->ImageSets->ImageSet)){
			foreach($val->ImageSets->ImageSet as $img){
				$data['images'] .= $img->MediumImage->URL.",";
			}
		}
		// GET PRODUCT FEATURES
		$excerpt="<ul>";
		foreach($val->ItemAttributes->Feature as $feature){
		$excerpt .="<li>".$feature."</li>";
		}
		$excerpt.="</ul>";
		//	GET ATTRIBUTES
		$extra_data = "<ul class=ExtraData>";
		foreach($val->ItemAttributes as $at1){foreach($at1 as $key => $att){
		$extra_data .= "<li><span>".$key."</span>:";
			if(is_array($att)){
				foreach($att as $in){
					$extra_data .= $in;
				}
			}else{
				$extra_data .= $att;
			}
		$extra_data .= "</li>";
		}}
	
		$extra_data .="</ul>";
	
		// SWITCH VALUES IF EMPTY	
		if(strlen($excerpt) < 10){ $excerpt = $extra_data; $extra_data=""; }
	
	
		$cc = explode("/",str_replace("http://www.amazon.".$_POST['amazon']['country']."/","",$val->DetailPageURL));
		$data['nicename'] =$cc[0];
	
		if($_POST['amazon_ID'] ==""){ $_POST['amazon_ID'] ="YOURUSERID"; }
	
	
		$AFFLINK = "http://www.amazon.".$_POST['amazon']['country']."/o/ASIN/%asin%/%amazon_id%";
		$AFFLINK = str_replace("%asin%",$data['asin'],$AFFLINK);
		$AFFLINK = str_replace("%amazon_id%",$_POST['amazon_ID'],$AFFLINK);
	 
		// CHECK THIS PRODUCT DOESNT ALREADY EXIST		
		/*$result = mysql_query("SELECT count($wpdb->posts.ID) AS total FROM $wpdb->posts
		INNER JOIN $wpdb->postmeta ON
		($wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_value='".$data['asin']."')
		LIMIT 1", $wpdb->dbh) or die(mysql_error().' on line: '.__LINE__);
				
		$array = mysql_fetch_assoc($result);*/
		
		if ($data['price'] > 0 && strlen($data['title']) > 3 ) { //$array['total'] ==0 && 
	
				if(strlen($excerpt) < 10){ $excerpt = $extra_data; }		
				
				$data['desc'] 	= str_replace(":"," : ",$data['desc']);
				$excerpt 		= str_replace(":"," : ",$excerpt);
				
				
				if(strlen($data['title']) > 45){
				$ssss = explode(" ",str_replace("with","",$data['title']));
				$name = ""; $i=0;
					 while($i < count($ssss)){
						 if(strlen($name) < 45){
							$name .= $ssss[$i]." ";
							}
					  $i++;		 
					 }		 
				}else{
				$name  = $data['title'];
				}
			 
				$my_post = array();
				$my_post['post_title'] 	= $name;	  
				$my_post['post_content'] 	= $data['desc'].$extra_data;
				$my_post['post_excerpt'] 	= substr($data['desc'],0,200);
				$my_post['post_author'] 	= 1;
				$my_post['post_status'] 	= "publish";
				$my_post['post_category'] 	= $_POST['amazon']['cat'];
				$my_post['tags_input'] = str_replace(" ",",",str_replace("-","",str_replace("/","",str_replace("&","",$data['title']))));
				$POSTID = wp_insert_post( $my_post );	  
				  
				$data['price'] 		= str_replace(",","",$data['price']);
				$data['old_price']  = str_replace(",","",$data['old_price']);
				  
				// EXTRA FIELDS
				add_post_meta($POSTID, "amazon_link", $AFFLINK);
				add_post_meta($POSTID, "amazon_guid", $data['asin']);
				add_post_meta($POSTID, "price", $data['price']);
				if(isset($data['old_price']) && strlen($data['old_price']) > 1){ add_post_meta($POSTID, "old_price", $data['old_price']); }
				add_post_meta($POSTID, "warranty", $data['warranty']);
				  
				add_post_meta($POSTID, "image", $data['image']);
				add_post_meta($POSTID, "images", $data['images']);	  
				add_post_meta($POSTID, "qty", 1);	
				add_post_meta($POSTID, "featured", "no");
	
				$importcounter++;
				$emailString .= "Product ".$importcounter.": ".$data['title']." \n\n";
				   
				  
				// CHECK FOR COMMENTS	  
				$time = current_time('mysql', $gmt = 0);	
					
				  if(isset($val->CustomerReviews->Review)){ foreach($val->CustomerReviews->Review as $review){
			
					$data = array(
						'comment_post_ID' => $POSTID,
						'comment_author' => $review->Reviewer->Name,
						'comment_author_email' => 'admin@admin.com',
						'comment_author_url' => 'http://',
						'comment_content' => nl2br($review->Content),
						//'comment_type' => ,
						'comment_parent' => 0,
						'user_ID' => 1,
						'comment_author_IP' => '127.0.0.1',
						'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
						'comment_date' => $review->Date,
						'comment_date_gmt' => $time,
						'comment_approved' => 1,
					);
					
					wp_insert_comment($data);
					
				  } 
			}
	 
		}
	
	  }

	}
	
	return $importcounter; 
}

}


if(!function_exists('curPageURL')){

	function curPageURL() {
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	
}

if(!function_exists("FilterPath")){

	function FilterPath(){
		$path=dirname(realpath($_SERVER['SCRIPT_FILENAME']));
		$path_parts = pathinfo($path); 
		if($path == ""){
			return $_SERVER['DOCUMENT_ROOT'];
		}else{
			$path = $path_parts['dirname'];
			if($path_parts['basename'] != ""){ $path .= "/".$path_parts['basename']; }
			return $path;
		}
	}
}



function PPTUsersByRole( $roles, $data="ID" ) {
	global $wpdb;
	if ( ! is_array( $roles ) ) {
		$roles = explode( ",", $roles );
		array_walk( $roles, 'trim' );
	}
	$sql = '
		SELECT	'.$data.'
		FROM		' . $wpdb->users . ' INNER JOIN ' . $wpdb->usermeta . '
		ON		' . $wpdb->users . '.ID				=		' . $wpdb->usermeta . '.user_id
		WHERE	' . $wpdb->usermeta . '.meta_key		=		\'' . $wpdb->prefix . 'capabilities\'
		AND		(
	';
	$i = 1;
	foreach ( $roles as $role ) {
		$sql .= ' ' . $wpdb->usermeta . '.meta_value	LIKE	\'%"' . $role . '"%\' ';
		if ( $i < count( $roles ) ) $sql .= ' OR ';
		$i++;
	}
	$sql .= ' ) ';
	$sql .= ' ORDER BY ID ASC ';
	$userIDs = $wpdb->get_col( $sql );
	return $userIDs;
}

function SendMemberEmail($user_id, $email_id, $extraMessage=""){
 
	global $wpdb, $post; $subject = ""; $message = "";	

	
	if (!is_object($user_id) && strpos($user_id, "@") === false) { // not found
	
		if(!function_exists('get_userdata')){ 
		require_once( ABSPATH . WPINC . '/pluggable.php'); 
		}
 
		$user_info = get_userdata($user_id);
				
	}else{
	
		$user_info = "";
	
	}
	
 
	if(is_numeric($email_id)){
	 
		$email_info = $wpdb->get_results("SELECT * FROM premiumpress_emails WHERE ID= ('".$email_id."') LIMIT 1");
		$subject 	 	 = FindAndReplaceMe($email_info[0]->email_title,$user_info,$user_id);
		$message 		 = FindAndReplaceMe($email_info[0]->email_description,$user_info,$user_id);
		
	}else{
		
		$subject 	 	 = FindAndReplaceMe($email_id['subject'],$user_info,$user_id);
		$message 		 = FindAndReplaceMe($email_id['description'],$user_info,$user_id);
		
	}


	// REPLACE ../ WITH WEBSITE URL
	$message	= str_replace("../",get_home_url()."/",$message);
	
	
	// ADD EXTRA POST VALUES FROM FORM DATA
	$message	= FindAndReplaceMe($message,$_POST,$user_id);
	$subject	= FindAndReplaceMe($subject,$_POST,$user_id);
 
	if(isset($post->ID)){
		foreach($post as $key=>$value){
			$_POST['form'][$key] =  $value;
		}
	}	
	 
	// ADD EXTRA POST VALUES FROM FORM DATA
	if(isset($_POST['form'])){
	$message	= FindAndReplaceMe($message,$_POST['form'],$user_id);
	$subject	= FindAndReplaceMe($subject,$_POST['form'],$user_id);
	}
	// ADD EXTRA POST VALUES FROM FORM DATA
	if(isset($_POST['custom'])){
	$message	= FindAndReplaceMe($message,$_POST['custom'],$user_id);
	$subject	= FindAndReplaceMe($subject,$_POST['custom'],$user_id);
	} 
	// ADD EXTRA POST VALUES FROM FORM DATA
	if(isset($_POST['shipping'])){
	$message	= FindAndReplaceMe($message,$_POST['shipping'],$user_id);
	$subject	= FindAndReplaceMe($subject,$_POST['shipping'],$user_id);
	}
			
	// ADD ANY EXTRA MESSAGE DATA TO THE MESSAGE
	$message .=  $extraMessage;		

	// GET ARRAY OF ADMIN EMAIL ADDRESSES
 	$AdminEmails = PPTUsersByRole('administrator','user_email');
 
	// OVERRIDE TO SEND TO ADMINS
	if($user_id == "admin"){	
	
		$SendRoles = "";
		$PPTroles = array('administrator' => 'Super Admin','editor' => 'Site Manager','contributor' => 'Employee','subscriber' => 'Client');
		$r=1;foreach($PPTroles as $key=>$name){  if(get_option('emailrole'.$r) == $key){  $SendRoles .= $key.","; } $r++;	}
		$SendRoles = substr( $SendRoles,0,-1);		
	
		$SendToEmail = PPTUsersByRole($SendRoles,'user_email');
	
	}elseif(is_string($user_id) && strpos($user_id, "@") !== false){	
		$SendToEmail = $user_id;	
		 
	}else{
		$SendToEmail = $user_info->user_email;
	}	
  
	$headers  		 = "From: " . strip_tags($AdminEmails[0]) . "\r\n";
	$headers 		.= "Reply-To: " . strip_tags($AdminEmails[0]) . "\r\n";
	$headers 		.= "Return-Path: " . strip_tags($AdminEmails[0]) . "\r\n"; 
	
	// STRIP HTML TAGS TO SEND PLAIN TEXT
	if( ( isset($email_id['email_html']) && $email_id['email_html'] == 2 ) || ( isset($email_info[0]->email_html) && $email_info[0]->email_html == 2) ){
	
	$message	= strip_tags(br2nl($message));
	
	}else{
	
	$message	= nl2br($message);
	
	$headers 	.=  "Content-Type: text/html; charset=\"" .get_option('blog_charset') . "\"\n"; 
	add_filter('wp_mail_content_type','set_contenttype');	
	apply_filters( 'wp_mail_content_type', "text/html" );
		
	}
	
	
	// NEW ONES FOR VERSION 7	
	$message = str_replace("(blog_name)",get_bloginfo('name'),$message);
	$message = str_replace("(blog_link)",get_bloginfo('siteurl'),$message); 
	$message = str_replace("(date)",date('l jS \of F Y'),$message);
	$message = str_replace("(time)",date('h:i:s A'),$message);
	
	// RMEOVE SHORT TAGS	
		
	$message = str_replace("(firstname)","",$message);
	$message = str_replace("(lastname)","",$message);
 	$message = str_replace("(email)","",$message);
 	$message = str_replace("(website)","",$message);
 	$message = str_replace("(username)","",$message);
 	$message = str_replace("(user_registered)",date("Y-m-d"),$message);
	 
 
 	//die(print_r($_POST));
 	//die($SendToEmail." -- ".stripslashes($subject)." -- ".stripslashes($message)); 
 	wp_mail($SendToEmail,stripslashes($subject),stripslashes($message),$headers);


}
function br2nl($string)
{
	$a = $string;
    return preg_replace('/<br\\s*?\/??>/i', "\n", $a);
}
function set_contenttype($content_type){
return 'text/html';
}


function FindAndReplaceMe($text, $myarray=array(),$userID=0){
 
	if(is_array($myarray)){ 
	
		foreach($myarray as $key=>$name){
		
		if(is_array($name)){ continue; } 		
		if(is_object($name)){ continue; } 
		
			if($key == "user_login"){
			$text = str_replace("(username)",$name,$text);
			$text = str_replace("(display_name)",$name,$text);
			$text = str_replace("user_login",$name,$text);
			}	
			if($key == "first_name"){
			$text = str_replace("(firstname)",$name,$text);
			}
			if($key == "last_name"){
			$text = str_replace("(lastname)",$name,$text);
			}	
			if($key == "user_email"){
			$text = str_replace("(email)",$name,$text);
			}	
			if($key == "user_url"){
			$text = str_replace("(website)",$name,$text);
			}		
			if($key == "user_registered"){
			$text = str_replace("(created)",$name,$text);
			}	  	
			if($key == "tags"){		 
			$text = str_replace("(tags)",$name,$text);
			}
			if(!is_array($name)){		
			$text = str_replace("(".$key.")",$name,$text);
			}
		} 
	
	}elseif(is_object($myarray)){ 
  
 		if(isset($myarray->data->user_login)){ 
			$text = str_replace("(username)",$myarray->data->user_login,$text);
			$text = str_replace("(display_name)",$myarray->data->user_login,$text);
			$text = str_replace("user_login",$myarray->data->user_login,$text);
		} 
 		if(isset($myarray->data->user_registered)){ 
			$text = str_replace("(user_registered)",$myarray->data->user_registered,$text);
		}  
 	 
	 	$text = str_replace("(firstname)",get_user_meta($userID, 'first_name', true),$text);
	  	$text = str_replace("(lastname)",get_user_meta($userID, 'last_name', true),$text);
	 	$text = str_replace("(user_registered)",get_user_meta($userID, 'user_registered', true),$text);
	 
		if(isset($myarray->data->user_email)){ 
			$text = str_replace("(email)",$myarray->data->user_email,$text);
		} 
 	
		if(isset($key)){
		$text = str_replace("(".$key.")",$name,$text);
		}
	
	}
	
	

 
	return $text;

}

function CouponDiscount($code){
	
		$ArrayCoupon = get_option("coupon_array");
	
		if(is_array($ArrayCoupon)){
			foreach($ArrayCoupon as $value){
				if($code ==$value['name']){
				
					return $value;
			
				}
			}
		}	
}
	
	
function DoSubscription($userID,$packageID,$type="add"){

	global $wpdb;

	if(is_numeric($userID)){
	
		if(!function_exists('get_userdata')){ 
			require_once( ABSPATH . WPINC . '/pluggable.php'); 
		}
		
		$data = new WP_User($userID);
 		$user_login = $data->user_login;
	}else{
		$user_login = $userID;
	}

	switch($type){
	
		case "add": {
	
			$pdetails = $wpdb->get_results("SELECT * FROM premiumpress_packages WHERE ID= ('".$packageID."') LIMIT 1");
		
			$ENDDATE = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+$pdetails[0]->package_durationA,   date("Y")));
 
			$wpdb->get_results("INSERT INTO `premiumpress_subscriptions` (
			`user_login` ,
			`package_ID` ,
			`start_date` ,
			`end_date` ,
			`subscription_status` ,
			`email_last_interval` ,
			`email_last_date` ,
			`paid_amount` ,
			`paid_recurring`
			) VALUES ( '".strip_tags($user_login)."', '".$packageID."', NOW(), '".$ENDDATE."', '0', '', '', '".$pdetails[0]->package_price."', '".$pdetails[0]->package_recurring."')");
		
		} break;		
		
		
		case "update": {
 
			$FF = $wpdb->get_results("SELECT count(*) AS total FROM `premiumpress_subscriptions` WHERE package_ID='".$packageID."' AND user_login='".$user_login."' LIMIT 1 ");
			if($FF[0]->total ==0){
			
			
					$pdetails = $wpdb->get_results("SELECT * FROM premiumpress_packages WHERE ID= ('".$packageID."') LIMIT 1");
		
					$ENDDATE = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+$pdetails[0]->package_durationA,   date("Y")));
 
					$wpdb->get_results("INSERT INTO `premiumpress_subscriptions` (
					`user_login` ,
					`package_ID` ,
					`start_date` ,
					`end_date` ,
					`subscription_status` ,
					`email_last_interval` ,
					`email_last_date` ,
					`paid_amount` ,
					`paid_recurring`
					) VALUES ( '".strip_tags($user_login)."', '".$packageID."', NOW(), '".$ENDDATE."', '1', '', '', '".$pdetails[0]->package_price."', '".$pdetails[0]->package_recurring."')");
			
			}else{
 
				$wpdb->get_results("UPDATE `premiumpress_subscriptions` SET subscription_status=1 WHERE package_ID='".$packageID."' AND user_login='".$user_login."' LIMIT 1 ");
				
			}
			
		
		} break;
	
	
	}

}

 

function send_download($data){

	global $wpdb;
	
 
	$wpdb->get_results("UPDATE premiumpress_files SET file_downloads=file_downloads+1 WHERE file_name = ('".$data['file_name']."') LIMIT 1");

	$file_path = $data['file_path'] . $data['file_name'];

 	header('Content-Description: File Transfer');
	header("Content-Type: ".$data['file_type']."");
	header('Content-Type: application/octet-stream');
	header("Content-disposition: attachment; filename=".$data['file_name']."");
	header("Content-Length: ".$data['file_size']."");
    ob_clean();
    flush();
	readfile($file_path);
	exit;

}

    function returnMIMEType($filename)
    {
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

 
        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";
				
            default :
            return "application/octet-stream";
        }
    }

 
function ppt_metabox() {

	global $post;

	// Disallows things like attachments, revisions, etc...
	$safe_filter =				array( 'public' => true, 'show_ui' => true );

	// Allow to be filtered, just incase you really need to switch between
	// those crazy types of posts
	$args =						apply_filters( 'pts_metabox', $safe_filter );

	// Get the post types based on the above arguments
	$post_types =				get_post_types( (array)$args );

	// Populate necessary post_type values
	$cur_post_type =			$post->post_type;
	$cur_post_type_object =		get_post_type_object( $cur_post_type );

	// Make sure the currently logged in user has the power
	$can_publish =				current_user_can( $cur_post_type_object->cap->publish_posts );
?>

<div class="misc-pub-section misc-pub-section-last post-type-switcher">

	<label for="pts_post_type">Post Type:</label>
    
	<span id="post-type-display"><?php echo $cur_post_type_object->label; ?></span>
    
<?php	if ( $can_publish ) : ?>
	<a href="javascript:void(0);" class="edit-post-type hide-if-no-js" onClick="jQuery('#post-type-select').show();">Edit</a>
	<div id="post-type-select" style="display:none;">
		<select name="pts_post_type" id="pts_post_type">
<?php
		foreach ( $post_types as $post_type ) {
		
		if($post_type == "ppt_alert" || $post_type == "ppt_message"){ continue; }
			$pt = get_post_type_object( $post_type );
			if ( current_user_can( $pt->cap->publish_posts ) ) :
?>
			<option value="<?php echo $pt->name; ?>"<?php if ( $cur_post_type == $post_type ) : ?>selected="selected"<?php endif; ?>><?php echo $pt->label; ?></option>
<?php
			endif;
		}
?>
		</select>
		<input type="hidden" name="hidden_post_type" id="hidden_post_type" value="<?php echo $cur_post_type; ?>" />
		<a href="#pts_post_type" class="save-post-type hide-if-no-js button" onClick="jQuery('#post-type-select').hide();alert('This will be updated when you save the post.')">OK</a>
		<a href="javascript:void(0);" onClick="jQuery('#post-type-select').hide();">Cancel</a>
</div>
<?php
	endif; ?>
    
    
</div> <?php
}

 
function  aws_signed_request($region,$params)
{

	global $wpdb;

	if($region == ""){ $region ="com"; }
	
	if($region == "it"){
	$host = "webservices.amazon.it"; // must be in small case
	}elseif($region == "es"){
	$host = "webservices.amazon.es"; // must be in small case
	}elseif($region == "cn"){
	$host = "webservices.amazon.cn"; // must be in small case
    
	}else{
	$host = "ecs.amazonaws.".$region; // must be in small case
	}
	
    $method = "GET";
    
    $uri = "/onca/xml";
    
    
    $params["Service"]          = "AWSECommerceService";
    $params["AWSAccessKeyId"]   = get_option("amazon_KEYID");
    $params["Timestamp"]        = gmdate("Y-m-d\TH:i:s\Z");
    $params["Version"]          = "2011-08-01";
	$params[ 'AssociateTag' ] 	= get_option("affiliates_20_ID");

    /* The params need to be sorted by the key, as Amazon does this at
      their end and then generates the hash of the same. If the params
      are not in order then the generated hash will be different thus
      failing the authetication process.
    */
    ksort($params);
    
    $canonicalized_query = array();

    foreach ($params as $param=>$value)
    {
        $param = str_replace("%7E", "~", rawurlencode($param));
        $value = str_replace("%7E", "~", rawurlencode($value));
        $canonicalized_query[] = $param."=".$value;
    }
    
    $canonicalized_query = implode("&", $canonicalized_query);

    $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;
    
    /* calculate the signature using HMAC with SHA256 and base64-encoding.
       The 'hash_hmac' function is only available from PHP 5 >= 5.1.2.
    */
    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, get_option("amazon_SECRET"), true));
    
    /* encode the signature for the request */
    $signature = str_replace("%7E", "~", rawurlencode($signature));
    
    /* create request */
    $request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature;
 	
	//die($request);
    /* I prefer using CURL */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    //$xml_response = curl_exec($ch);
    
    /* If cURL doesn't work for you, then use the 'file_get_contents'
       function as given below.
    */
    $xml_response = file_get_contents($request);
    
    if ($xml_response === False)
    {
        return False;
    }
    else
    {
        /* parse XML */
        $parsed_xml = @simplexml_load_string($xml_response);
        return ($parsed_xml === False) ? False : $parsed_xml;
    }
}

function ppt_comments($comment, $args, $depth) { $GLOBALS['comment'] = $comment; ?> 
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<article id="comment-<?php comment_ID(); ?>" class="comment">
		<footer class="comment-meta">
			<div class="comment-author vcard">  
            
            <?php
			
		 
			 // GET USER PHOTO
            $img = get_user_meta($comment->user_id, "pptuserphoto",true);
			if($img == ""){
			$img = get_avatar($comment->user_id,52);
			}else{
			$img = "<img src='".get_option('imagestorage_link').$img."' class='photo' alt='user ".$comment->user_id."' />";
			}
			
			echo $img;
		    
			?>  
		 
             <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
            
                 <div class="comment-small"><?php printf(__('Posted %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this comment">Permalink</a>', 'your-theme'),
                    get_comment_date(),
                    get_comment_time(),
                    '#comment-' . get_comment_ID() );
                    edit_comment_link(__('Edit', 'your-theme'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>'); ?></div>
			</div> 
		</footer>
		<div class="comment-content">
        	<?php comment_text() ?>
		</div>
</article>
</li>      
<?php } 
	
?>