<?php

class Theme_Design {

/******************************************************************* DEPRECIATED IN 6.3 (SEE PPT/CLASS/CLASS_DESING.PHP) */
function LAY_NAVIGATION(){

	global $PPTDesign;
	
	return $PPTDesign->LAY_NAVIGATION();
}
/*************************************************************************************************/


function PopularCoupons($num=5){


		global $wpdb;
		$STRING = "";

		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$posts = query_posts('post_type=post&meta_key=hits&orderby=meta_value_num&order=DESC');		

		foreach($posts as $post){
 
		$STRING  .="<li><a href='".get_permalink($post->ID)."'>".$post->post_title."</a> </li>";
		
		}

		wp_reset_query();
		
		print $STRING;
}

 	
	function HomeCategories(){
	
		$SHOWCATCOUNT = get_option("display_categories_count");
		 
		$SHOW_SUBCATS = get_option("display_50_subcategories");
	
			if(is_home()){ 
	
				$Maincategories = get_categories('orderby='.get_option("display_homecats_orderby").'&pad_counts=1&use_desc_for_title=1&hide_empty=0&hierarchical=0&child_of=0&exclude='.str_replace("-","",$GLOBALS['directorypress']['excluded_articles'])); 
					
			}elseif( isset($GLOBALS['premiumpress']['catID']) ){
			
			
				$arg= array();
				$arg['child_of'] = $GLOBALS['premiumpress']['catID'];
				$arg['hide_empty'] = false;
				$arg['pad_counts'] = 1;
				$arg['exclude'] = str_replace("-","",$GLOBALS['directorypress']['excluded_articles']);
				$Maincategories = get_categories( $arg );
	 
				//$Maincategories = get_categories('orderby='.get_option("display_homecats_orderby").'pad_counts=1&use_desc_for_title=1&hierarchical=0&hide_empty=1&child_of='.$GLOBALS['premiumpress']['catID'].'&exclude='.); 
	  
			} 
	 
	
			if(isset($Maincategories)){
	
			$catlist=""; 
			$Maincatcount = count($Maincategories);	
	 
	
			if($Maincatcount > 0){ $catlist .= '<div class="homeCategories"><ul>';}
	 
				foreach ($Maincategories as $Maincat) { if(strlen($Maincat->name) > 1){ 
		 
		 
					if(is_home() && $Maincat->parent ==0){		
							
						$catlist .= '<li><span><a href="'.get_category_link( $Maincat->term_id ).'" title="'.$Maincat->category_nicename.'" style="font-size:16px; color:#454444;"><b>';
						$catlist .= $Maincat->name;
						if($SHOWCATCOUNT =="yes"){ $catlist .= " (".$Maincat->count.')</b></a></span>'; }else{ $catlist .= '</b></a></span>'; }
						//$catlist .= '</span></a>';		
	
							if($SHOW_SUBCATS == "yes"){
							
								$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0&exclude='.str_replace("-","",$GLOBALS['directorypress']['excluded_articles']).",".$GLOBALS['directorypress']['excluded_pages']); 
								$catcount = count($categories);	
								
							 
								$currentcat=get_query_var('cat');	
								if(count($categories) > 0){
								$catlist .= '<div style="margin-left:10px; margin-bottom:30px;">';
									foreach ($categories as $cat) {
										$catlist .= '<a href="'.get_category_link( $cat->term_id ).'" title="'.$cat->cat_name.'" class="sm">';
										$catlist .= $cat->cat_name;
										//if(get_option("display_categories_count") =="yes"){ $catlist .= " (".$cat->count.")"; }
										$catlist .= '</a> ';
									}
									$catlist .= '</div>';
								}	 
								
							}
						
						 $catlist .= '</li>';
						
					} elseif(!is_home() && isset($GLOBALS['premiumpress']['catID']) && $Maincat->category_parent == $GLOBALS['premiumpress']['catID']){
			
						$catlist .= '<li><span><a href="'.get_category_link( $Maincat->term_id ).'" title="'.$Maincat->category_nicename.'" style="font-size:16px; color:#454444;"><b>';
						$catlist .= $Maincat->name;
						if($SHOWCATCOUNT =="yes"){ $catlist .= " (".$Maincat->count.')</b></a></span>'; }else{ $catlist .= '</b></a></span>'; }
						//$catlist .= '</span></a>';
						
							if($SHOW_SUBCATS == "yes"){
							
								$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0&exclude='.str_replace("-","",$GLOBALS['directorypress']['excluded_articles']).",".$GLOBALS['directorypress']['excluded_pages']); 
								$catcount = count($categories);	
								
							 
								$currentcat=get_query_var('cat');	
								if(count($categories) > 0){
								$catlist .= '<div style="margin-left:10px; margin-bottom:30px;">';
									foreach ($categories as $cat) {
										$catlist .= '<a href="'.get_category_link( $cat->term_id ).'" class="sm">';
										$catlist .= $cat->cat_name;
										//if(get_option("display_categories_count") =="yes"){ $catlist .= " (".$cat->count.")"; }
										$catlist .= '</a> ';
									}
									$catlist .= '</div>';
								}	 
								
							}					
						
						
						
						
						$catlist .= '</li>';
			
					}
				}
			}
		
	
			if($Maincatcount > 0){ $catlist .= '</ul><div class="clearfix"></div></div>'; }
	
			echo $catlist;
	
			}	
		 
	
	}
	
	
	function SINGLE_CUSTOMFIELDS($post,$FieldValues){

	global $wpdb,$PPT;$row=1;

	if($FieldValues ==""){ 
		$FieldValues = get_option("customfielddata");
	}

	if(is_array($FieldValues)){ 

		foreach($FieldValues as $key => $field){

			if(isset($field['show']) && $field['enable'] == 1 && $field['key'] != "coupon"){ 				 
			
			$imgArray = array('jpg','gif','png');

			$value = $PPT->GetListingCustom($post->ID,$field['key'] );
 
			if(is_array($value) || strlen($value) < 1){   }else{			
		
				print "<div class='full clearfix border_t box'><p class='f_half left'><br />"; 
				print "<b>".$field['name']."</b></p><p class='f_half left'><br />";
		
				switch($field['type']){
				 case "textarea": {			
					print "<br />".nl2br(stripslashes($value));
				 } break;
				 case "list": {
					print  $value;
				 } break;
				 default: {
					
					$pos = strpos($value, 'http://'); 					
					if($field['key'] == "skype"){
						print "<a href='skype:".$value."?add'>" .  $value ."</a>";
					}elseif ($pos === false) {
						print  $value;
					}elseif(in_array(substr($value,-3),$imgArray)){
						print "<img src='".strip_tags($value)."' style='max-width:250px;margin-left:20px;'>";
					}else{
						print "<a href='".$value."' target='_blank'";
						if($GLOBALS['premiumpress']['nofollow'] =="yes"){ print 'rel="nofollow"'; }
						print ">" .  $value ."</a>";				
					} 
					
				 }
		
				}
				$row++;
				print "</p></div>";
				
				}

				} 
			}
		}
	
	}	
		
		
		
		 function SINGLE_IMAGEGALLERY($images){	
	 
	 global $PPT;
	 
	 if($images == ""){ return; } 
	 
	 
		$imgBits = explode("/",get_permalink());	
		$tt = count($imgBits)-2; $it=0;
		
		while($tt > 0){  
			$imagePath .= $imgBits[$it]."/";
			$tt--;$it++;		 
		 }
			 
		 $string = "";
		 $image_array = explode(",",$images); 
	
			foreach($image_array as $image){ if(strlen($image) > 2){ 
			 
				switch(substr($image,-3)){
						
					case "pdf": {
							$class="";
							$pic1 = "".$imagePath."wp-content/themes/".strtolower(PREMIUMPRESS_SYSTEM)."/PPT/img/icon-pdf.gif";
							
					} break;
							
					case "": {
							
					} break;	
							
					default: {
							$pic1 = $image;
							//$class='class="lightbox"';
					}			
						
				}
				
				$string .= '<a '.$class.' href="'.$PPT->ImageCheck($image,"t","&amp;w=400").'"><img class="small" src="'.$PPT->ImageCheck($pic1,"t","&amp;w=150").'" alt="img" style="max-width:350px;"/></a>';
				
				
				
			} }	
			
			return $string;
	 
	 }
	


	function FeaturedCategories(){

		global $wpdb,$PPT; $STRING = "";
 
		$SAVED_DISPLAY = get_option("featured_stores");

			if(is_array($SAVED_DISPLAY)){
				$SHOWRESULTS = $PPT->multisort( $SAVED_DISPLAY , array('ORDER') );
			}else{
				$SHOWRESULTS = array();
			} 		

			foreach($SHOWRESULTS as $ThisCat){  if( isset($ThisCat['ID']) && $ThisCat['ID'] > 0 ){ 
 
				$catBits = get_category($ThisCat['ID'],false);

				if(!empty($catBits)){
				$storeimage = $PPT->CategoryExtras($ThisCat['ID'],"image",1);
			
				$STRING .= "<li>";
				$STRING .= "<a href='".get_category_link( $ThisCat['ID'] )."' title='".$catBits->cat_name."' style='text-decoration:none;'";

				if(isset($GLOBALS['premiumpress']['analytics_tracking']) && $GLOBALS['premiumpress']['analytics_tracking'] =="yes"){
				$STRING .= "onclick=\"pageTracker._trackEvent('STORE CLICK', 'IMAGE CLICK', '".$catBits->cat_name."');\"";
				}
				$STRING .= ">";

				//$STRING .= "<div style='width:150px;'>";
				if($storeimage != ""){ 
					$STRING .= "<img src='".$storeimage."' style='bordr:0px; text-decoration:none;' />";
				}
				//$STRING .= "</div>";				
				
				$STRING .= "</a><div style='clear:both;'></div>
				<a href='".get_category_link( $catBits->term_id )."' title='".$catBits->cat_name."' style='font-size:12px;'";
				if(isset($GLOBALS['premiumpress']['analytics_tracking']) && $GLOBALS['premiumpress']['analytics_tracking'] =="yes"){
				$STRING .= "onclick=\"pageTracker._trackEvent('STORE CLICK', 'TEXT CLICK', '".$catBits->cat_name."');\"";
				}
				$STRING .= ">";
				//$STRING .= $catBits->cat_name." (".$catBits->category_count.")";
				$STRING .= "</a>";
				$STRING .= "</li>";
				}
			
			} }

 
 
		return $STRING; 
	 
	}








function couponpress_storelist($options='') {

 
	$options['columns'] = 3;   
	$options['more'] = "View more";	
	$options['hide'] = "no";
	$options['num_show'] = 5;	
	$options['toggle'] = "no";
	$show_empty = "0";
	
    $list = '<div id="CouponPressStores">';

// GET ALL PARENT CAT ID'S
 

	$tags = get_categories('order=ASC&hide_empty='.$show_empty.'&exclude='.$this->GetParentIDS()); 
	$groups = array();
	
	
	
	if( $tags && is_array( $tags ) ) {
		foreach( $tags as $tag ) {
			$first_letter = strtoupper( $tag->name[0] );
			$groups[ $first_letter ][] = $tag;
		}
	if( !empty ( $groups ) ) {	
		$count = 0;
		$howmany = count($groups);
		
		// this makes 2 columns
		if ($options['columns'] == 2){
		$firstrow = ceil($howmany * 0.5);
	    $secondrow = ceil($howmany * 1);
	    $firstrown1 = ceil(($howmany * 0.5)-1);
	    $secondrown1 = ceil(($howmany * 1)-0);
		}
		
		
		//this makes 3 columns
		if ($options['columns'] == 3){
	    $firstrow = ceil($howmany * 0.33);
	    $secondrow = ceil($howmany * 0.66);
	    $firstrown1 = ceil(($howmany * 0.33)-1);
	    $secondrown1 = ceil(($howmany * 0.66)-1);
		}
		
		//this makes 4 columns
		if ($options['columns'] == 4){
	    $firstrow = ceil($howmany * 0.25);
	    $secondrow = ceil(($howmany * 0.5)+1);
	    $firstrown1 = ceil(($howmany * 0.25)-1);
	    $secondrown1 = ceil(($howmany * 0.5)-0);
		$thirdrow = ceil(($howmany * 0.75)-0);
	    $thirdrow1 = ceil(($howmany * 0.75)-1);
		}
		
		//this makes 5 columns
		if ($options['columns'] == 5){
	    $firstrow = ceil($howmany * 0.2);
	    $firstrown1 = ceil(($howmany * 0.2)-1);
	    $secondrow = ceil(($howmany * 0.4));
		$secondrown1 = ceil(($howmany * 0.4)-1);
		$thirdrow = ceil(($howmany * 0.6)-0);
	    $thirdrow1 = ceil(($howmany * 0.6)-1);
		$fourthrow = ceil(($howmany * 0.8)-0);
	    $fourthrow1 = ceil(($howmany * 0.8)-1);
		}
		
		foreach( $groups as $letter => $tags ) { 
			if ($options['columns'] == 2){
			if ($count == 0 || $count == $firstrow || $count ==  $secondrow) { 
			    if ($count == $firstrow){
				$list .= "\n<div class='holdleft noMargin'>\n";
				$list .="\n";
				} else {
				$list .= "\n<div class='holdleft'>\n";
				$list .="\n";
				}
				}
				}
			if ($options['columns'] == 3){
			if ($count == 0 || $count == $firstrow || $count ==  $secondrow) { 
			    if ($count == $secondrow){
				$list .= "\n<div class='holdleft noMargin'>\n";
				$list .="\n";
				} else {
				$list .= "\n<div class='holdleft'>\n";
				$list .="\n";
				}
				}
				}
			if ($options['columns'] == 4){				
			if ($count == 0 || $count == $firstrow || $count ==  $secondrow || $count == $thirdrow) { 
			    if ($count == $thirdrow){
				$list .= "\n<div class='holdleft noMargin'>\n";
				$list .="\n";
				} else {
				$list .= "\n<div class='holdleft'>\n";
				$list .="\n";
				}
				}
				}
			if ($options['columns'] == 5){
			if ($count == 0 || $count == $firstrow || $count ==  $secondrow || $count == $thirdrow || $count == $fourthrow ) { 
			    if ($count == $fourthrow){
				$list .= "\n<div class='holdleft noMargin'>\n";
				$list .="\n";
				} else {
				$list .= "\n<div class='holdleft'>\n";
				$list .="\n";
				}
				}
				}
		
    $list .= '<div class="tagindex">';
	$list .="\n";
	$list .='<h4>' . apply_filters( 'the_title', $letter ) . '</h4>';
	$list .="\n";
	$list .= '<ul class="links">';
	$list .="\n";			
	$i = 0;
	foreach( $tags as $tag ) {

		$url = get_category_link( $tag->term_id );

		$name = apply_filters( 'the_title', $tag->name );
	//	$name = ucfirst($name);
		$i++;
		$counti = $i;
		if ($options['hide'] == "yes"){
		$num2show = $options['num_show'];
		$num2show1 = ($options['num_show'] +1);
		$toggle = ($options['toggle']);
		
		if ($i != 0 and $i <= $num2show) {
			$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a></li>';
			$list .="\n";
			}
		if ($i > $num2show && $i == $num2show1 && $toggle == "no") {
			$list .=  "<li class=\"morelink\">"."<a href=\"#x\" class=\"more\">".$options['more']."</a>"."</li>"."\n";
			}
		if ($i >= $num2show1){
               $list .= '<li class="hideli"><a title="' . $name . '" href="' . $url . '">' . $name . '</a></li>';
			   $list .="\n";
		}
		} else {
			$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a></li>';
			$list .="\n";
		}	
		
	} 
		if ($options['hide'] == "yes" && $toggle != "no" && $i == $counti && $i > $num2show) {
			$list .=  "<li class=\"morelink\">"."<a href=\"#x\" class=\"more\">".$options['more']."</a>"."<a href=\"#x\" class=\"less\">".$options['toggle']."</a>"."</li>"."\n";
		}	 
	$list .= '</ul>';
	$list .="\n";
	$list .= '</div>';
	$list .="\n\n";
		if ($options['columns'] == 3 || $options['columns'] == 2){
		if ( $count == $firstrown1 || $count == $secondrown1) { 
			$list .= "</div>"; 
			}	
			}
		if ($options['columns'] == 4){
		if ( $count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1) { 
			$list .= "</div>"; 
			}	
			}
		if ($options['columns'] == 5){		
		if ( $count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1 || $count == $fourthrow1) { 
			$list .= "</div>"; 
			}	
			}
				 
		$count++;
			} 
		} 
	$list .="</div>";
	$list .= "<div style='clear: both;'></div></div>";
		}
	else $list .= '<p>Sorry, but no stores were found</p>';

print $list ;

}


function GetParentIDS(){

	global $wpdb;

	$CATS = "";
	$Maincategories = get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');		
	$Maincatcount = count($Maincategories);	 
	foreach ($Maincategories as $Maincat) { 
		if($Maincat->parent ==0){
		$CATS .= $Maincat->cat_ID.",";
		}
	}

	return $CATS;		

}







 
}	



/* ============================= PREMIUM PRESS REGISTER WIDGETS ========================= */
 

if ( function_exists('register_sidebar') ){


register_sidebar(array('name'=>'Right Sidebar',
	'before_widget' => '<div class="itembox">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2>',
	'after_title' 	=> '</h2><div class="itemboxinner widget">',
));

register_sidebar(array('name'=>'Left Sidebar (3 Column Layouts Only)',
	'before_widget' => '<div class="itembox">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2>',
	'after_title' 	=> '</h2><div class="itemboxinner widget">',
));

register_sidebar(array('name'=>'Listing Page Sidebar',
	'before_widget' => '<div class="itembox">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2>',
	'after_title' 	=> '</h2><div class="itemboxinner widget">',
));

register_sidebar(array('name'=>'Pages Sidebar',
	'before_widget' => '<div class="itembox">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2>',
	'after_title' 	=> '</h2><div class="itemboxinner widget">',
));

register_sidebar(array('name'=>'Article/FAQ Page Sidebar',
	'before_widget' => '<div class="itembox">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2>',
	'after_title' 	=> '</h2><div class="itemboxinner widget">',
));
  
} 
 
 
 
 
 
 
 
?>