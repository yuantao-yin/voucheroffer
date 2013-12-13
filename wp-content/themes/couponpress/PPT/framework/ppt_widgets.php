<?php


/*

ppt_widget_1 = ARTICLES WIDGET
ppt_widget_2 = FEATURED PRODUCTS
*/
 
 
 
function ppt_widget_4_display($args){ 
     
	global $PPT,$PPTDesign; $STRING = ""; $URL = get_bloginfo('url'); extract($args); $desc = get_option("ppt_control_4_widget_desc");
	
	if(isset($GLOBALS['premiumpress']['catID']) && is_numeric($GLOBALS['premiumpress']['catID'])){ $ex = "&amp;cct=".$GLOBALS['premiumpress']['catID']; }else{ $ex = ""; }
	
	// CURRENCY SYMBOL
	if(!isset($_SESSION['currency_symbol'])){ $_SESSION['currency_symbol'] = $GLOBALS['premiumpress']['currency_symbol']; } 
	
	// BUILD STRING
	$STRING .= $before_widget.$before_title.get_option("ppt_control_4_widget_title").$after_title;			
			
	// DESCRIPTION
	if(strlen($desc) > 1){ 
	
	if(!isset($GLOBALS['premiumpress']['catName'])){ $GLOBALS['premiumpress']['catName'] = ""; }
			
		$STRING .= '<p class="quicksearch-text">'.str_replace("%cat%",$GLOBALS['premiumpress']['catName'],$desc).'</p>';	
	}else{
	$STRING .= '<br />';
	}		
	 
			
			$a = get_option('ppt_control_4_widget_low');
			$b = get_option('ppt_control_4_widget_high');
			$c =get_option("ppt_control_4_widget_range");
			if(!is_numeric($a)){$a = 0; }
			if(!is_numeric($b)){$b = 100; }
			if(!is_numeric($c)){$c = 100; }
			
			$i=0; 
			while($i < $b){
			
			if($i%$c){
			
				$val1= $i;
				$val2= $i+$c;
				
				$STRING .= '<a href="'.$URL.'/?s=&nbsp;&amp;quick=between&amp;a='.$val1.'&amp;b='.$val2.'" class="quicksearch marginright" id="q'.$i.'">'.
				$PPT->Price($val1,$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1).' - '.
				$PPT->Price($val2,$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1).' </a>';
				$i=$i+$c;
				 
			}
			
			$i++;
			}
			 
			$STRING .= '<br />
			<a href="'.$URL.'/?s=&nbsp;&amp;quick=new'.$ex.'" class="quicksearch" id="q8">New Products</a>
			<a href="'.$URL.'/?s=&nbsp;&amp;quick=discount'.$ex.'" class="quicksearch marginright" id="q9">Discount Products</a>
			<a href="'.$URL.'/?s=&nbsp;&amp;quick=featured'.$ex.'" class="quicksearch marginright" id="q10">Featured Products</a>';
			
	$STRING .= $after_widget;
	
	echo $STRING;
	
}


function ppt_control_4(){

  
if ($_POST['ppt_control_4_submit']) {
 
 	update_option("ppt_control_4_widget_low",$_POST['ppt_control_4_widget_low']);
 	update_option("ppt_control_4_widget_high",$_POST['ppt_control_4_widget_high']);
	 update_option("ppt_control_4_widget_title",$_POST['ppt_control_4_widget_title']);
	  update_option("ppt_control_4_widget_range",$_POST['ppt_control_4_widget_range']);
	  update_option("ppt_control_4_widget_desc",$_POST['ppt_control_4_widget_desc']);
}

$val1=get_option("ppt_control_4_widget_high");
$val2=get_option("ppt_control_4_widget_low");
$val3=get_option("ppt_control_4_widget_range");
?>
<input type="hidden" id="ppt_control_4_submit" name="ppt_control_4_submit" value="1" />
 
<p><b>Box Title:</b></p>
<input style="width: 220px;  font-size:14px;" type="text" id="ppt_control_4_widget_title" name="ppt_control_4_widget_title" value="<?php echo get_option("ppt_control_4_widget_title"); ?>" />
<small>Use enter the title for your box</small>

<p><b>Box Description:</b></p>
<input style="width: 220px;  font-size:14px;" type="text" id="ppt_control_4_widget_title" name="ppt_control_4_widget_desc" value="<?php echo get_option("ppt_control_4_widget_desc"); ?>" />
<small>Use %cat% to relace with current category name</small>

<p><b>Minimum Amount: <u>0</u> - 100</b></p>
<select name="ppt_control_4_widget_low" style="width: 220px;  font-size:14px;">
<?php $i=0; while($i < 100100){ if($val2 == $i){ $ex ="selected=selected"; }else{ $ex ="";  } ?><option value="<?php echo $i; ?>" <?php echo $ex; ?>><?php echo number_format($i,0); ?></option><?php $i=$i+50; } ?>
</select> 

<p><b>Maximum Amount: 0 - <u>100</u></b></p>
<select name="ppt_control_4_widget_high" style="width: 220px;  font-size:14px;">
<?php $i=0; while($i < 1500100){ if($val1 == $i){ $ex ="selected=selected"; }else{ $ex ="";  } ?><option value="<?php echo $i; ?>" <?php echo $ex; ?>><?php echo number_format($i,0); ?></option><?php $i=$i+500; } ?>
</select> 
 

<p><b>Price Range:</b></p>
<select name="ppt_control_4_widget_range" style="width: 220px;  font-size:14px;">
<?php $i=100; while($i < 100100){ if($val3 == $i){ $ex ="selected=selected"; }else{ $ex ="";  } ?><option value="<?php echo $i; ?>" <?php echo $ex; ?>><?php echo number_format($i,0); ?></option><?php $i=$i+50; } ?>
</select> 
 
   
<?php
}



 
 





function ppt_widget_3_display($args){ 
     
	global $PPT,$PPTDesign; $STRING = ""; extract($args);
	
	if(isset($GLOBALS['IS_CHECKOUTPAGE'])){ return; }
	
	
     if(isset($GLOBALS['premiumpress']['catID']) && is_numeric($GLOBALS['premiumpress']['catID']) && $PPT->CountCategorySubs($GLOBALS['premiumpress']['catID']) > 0 ){ 
	 
	 $STRING .= $before_widget.$before_title."Sub Categories".$after_title;
    
     $STRING .= $PPTDesign->HomeCategories(0);
    
	 $STRING .= $after_widget;	
        
     } 
	
	
	
		
	if( $PPTDesign->CanDisplayElement(get_option("display_categories_pages"))  ){ 
			
			$STRING .= $before_widget.$before_title.get_option("ppt_control_3_widget_title").$after_title;
		
			$STRING .= '<ul class="category vertical" id="Accordion">'. $PPTDesign->SYS_CATEGORIES() .'</ul><div class="clearfix"></div>';
			
			$STRING .= $after_widget;			
			
	}
			
	print $STRING;
	
}
function ppt_control_3(){

  
if ($_POST['ppt_control_3_submit']) {
 
	update_option("ppt_control_3_widget_title",$_POST['ppt_control_3_widget_title']); 
	update_option("display_categories_pages", $_POST['display_categories_pages']);
	//update_option("display_tabbed_cats", $_POST['display_tabbed_cats']);
	update_option("display_categories_count_inner", $_POST['display_categories_count_inner']);
	 
}
 
?>
<input type="hidden" id="ppt_control_3_submit" name="ppt_control_3_submit" value="1" />
 
<p><b>Box Title:</b></p>
<input class="widefat" type="text" id="ppt_control_3_widget_title" name="ppt_control_3_widget_title" value="<?php echo get_option("ppt_control_3_widget_title"); ?>" />

 
            <p><b>Display on which pages</b></p>
 
            <select name="display_categories_pages" style="width: 220px;  font-size:14px;">
				<option value="0" <?php if(get_option("display_categories_pages") =="0"){ print "selected";} ?>>All Pages</option>
				<option value="no-single" <?php if(get_option("display_categories_pages") =="no-single"){ print "selected";} ?>>All BUT Single Pages</option>
                <option value="no-home" <?php if(get_option("display_categories_pages") =="no-home"){ print "selected";} ?>>All BUT Home Page</option>
                <option value="no-page" <?php if(get_option("display_categories_pages") =="no-page"){ print "selected";} ?>>All BUT Pages</option>
                <!--<option value="no-listing" <?php if(get_option("display_categories_pages") =="no-listing"){ print "selected";} ?>>All BUT Listing Page</option>-->
			</select><br />
			<small>Show/Hide the category box on the right menu.</small>  
            
 
            
            <?php if(get_option("display_tabbed_cats") =="yes"){ ?>
            
            	<p><b>Tabbed Categories Count</b></p>			
			<select name="display_categories_count_inner" style="width: 240px;  font-size:14px;">
				<option value="yes" <?php if(get_option("display_categories_count_inner") =="yes"){ print "selected";} ?>>Show</option>
				<option value="no" <?php if(get_option("display_categories_count_inner") =="no"){ print "selected";} ?>>Hide</option>
			</select><br />
			<small>Show/Hide the category count showing how many items are within each category on the inner page sections.</small> 

	<?php } ?>
 
<?php
}
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
function ppt_widget_2_display($args){ 
     
	global $PPT, $wpdb; $STRING = ""; extract($args);
	
	$num = get_option("display_sidebar_products_num");
	$cat = get_option('display_sidebar_products_cat'); 
 
	if($cat != ""){		$ex = "&cat=".$cat; 	}elseif(is_numeric($GLOBALS['premiumpress']['catID'])){		$ex = "&cat=".$GLOBALS['premiumpress']['catID'];	}else{		$ex =""; 	}
		 
	$posts = query_posts('posts_per_page='.$num.'&meta_key=price&orderby=rand'.$ex); 

	if(count($posts) > 0){ //meta_value_num

		$STRING .= $before_widget.$before_title.get_option("ppt_control_2_widget_title").$after_title.'<ul class="FeaturedList">';
		 
		foreach($posts as $post){ if($post->ID != $GLOBALS['thisID']){ 
						  
		$STRING .= '<li>';
							 
				if(strlen($PPT->Image($post->ID,"url")) > 5){ 
					$STRING .= '<a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><img src="'.$PPT->Image($post->ID,"url","&amp;w=80").'" alt="'.$post->post_title.'" width=80/>';
				} 
					$STRING .= '<h3>'.$post->post_title.'</h3>
					<p>
					<strike>'.$PPT->Price(GetPrice(get_post_meta($post->ID, "old_price", true)),$_SESSION['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1).'</strike> 
					<b>'.$PPT->Price(GetPrice(get_post_meta($post->ID, "price", true)),$_SESSION['currency_symbol'],"l",1).'</b></p>';
					
			
					
					$STRING .= '</a>';
				} 
				
		$STRING .= '</li>';
			
		}
		
		$STRING .= '</ul>'.$after_widget;
		
	}
		
	wp_reset_query();
	 
	
        
	echo $STRING;
	
}



function ppt_control_2(){

  
if ($_POST['ppt_control_2_submit']) {

 	update_option("ppt_control_2_widget_title",$_POST['ppt_control_2_widget_title']); 
	update_option("display_sidebar_products_cat", $_POST['display_sidebar_products_cat']);
	update_option("display_sidebar_products_num", $_POST['display_sidebar_products_num']);
}
 
?>


<input type="hidden" id="ppt_control_2_submit" name="ppt_control_2_submit" value="1" />

<p><b>Box Title:</b></p>
<input class="widefat" type="text" id="ppt_control_2_widget_title" name="ppt_control_2_widget_title" value="<?php echo get_option("ppt_control_2_widget_title"); ?>" />


<p><b>Show How Many Products?</b></p>

<input name="display_sidebar_products_num" value="<?php echo get_option("display_sidebar_products_num"); ?>" class="txt" style="width:50px; font-size:14px;" type="text"> # Products
            
<p><b>From Which Category?</b></p>

 	<select name="display_sidebar_products_cat"  style="width: 240px;  font-size:14px;">
	<option value=''>All/Current Category</option>
	 <?php 

		$catlist="";
 		$Maincategories = get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');		
		$Maincatcount = count($Maincategories);	 
		foreach ($Maincategories as $Maincat) { 
		if($Maincat->parent ==0){		
 
			$catlist .= '<option value="'.$Maincat->term_id.'">';
			$catlist .= $Maincat->cat_name;
			$catlist .= " (".$Maincat->count.')';
			$catlist .= '</option>';

				$currentcat=get_query_var('cat');
				$categories= get_categories('child_of='.$Maincat->cat_ID.'&depth=1&hide_empty=0');
 
				$catcount = count($categories);		
				$i=1;
	
				if(count($categories) > 0){
				$catlist .="<ul>";
					foreach ($categories as $cat) {		
			
						$catlist .= '<option value="'.$cat->term_id.'"> -- ';
						$catlist .= $cat->cat_name."";
						$catlist .= '</option>';						 
						$i++;		
					}
				 
				}
			
		} 
 }
print $catlist;
 ?>
</select>
 
 
 
<?php
} 
 
 
 
 
 
 
 
 
 
 
 
function ppt_widget_1_display($args){ 
     
	global $PPT,$PPTDesign; $STRING = ""; extract($args);
	
		$cArticles = get_option('display_sidebar_articles_count');  
		
		$STRING .= $before_widget.$before_title.get_option("ppt_control_1_widget_title").$after_title.'<ul id="sidebar_recentarticle">';
				
        	if($cArticles > 1){ $STRING .=  $PPT->Articles($cArticles,false,true); }else{ $STRING .= $PPT->Articles($cArticles,true,true); }  
        
		$STRING .= '</ul>'.$after_widget;
			
		echo $STRING;
	
}
function ppt_control_1(){

  
if ($_POST['ppt_control_1_submit']) {
 
	update_option("ppt_control_1_widget_title",$_POST['ppt_control_1_widget_title']); 
	update_option("display_sidebar_articles_count", $_POST['display_sidebar_articles_count']);
}
 
?>
<input type="hidden" id="ppt_control_1_submit" name="ppt_control_1_submit" value="1" />

<p><b>Title:</b></p>
<input class="widefat" type="text" id="ppt_control_1_widget_title" name="ppt_control_1_widget_title" value="<?php echo get_option("ppt_control_1_widget_title"); ?>" /><br />


<p>How many articles to display?</p>
<input class="widefat" type="text" id="display_sidebar_articles_count" name="display_sidebar_articles_count" value="<?php echo get_option("display_sidebar_articles_count"); ?>" />
 
<?php
}

wp_register_sidebar_widget(
    'ppt_widget_1',        // your unique widget id
    '** ARTICLES **',          // widget name
    'ppt_widget_1_display',  // callback function
    array(                  // options
        'description' => 'Article display widget from PremiumPress.'
    )
);
 wp_register_widget_control( 'ppt_widget_1', 'ppt_control_1', 'ppt_control_1');

if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "shopperpress"){  

 wp_register_sidebar_widget(
    'ppt_widget_2',        // your unique widget id
    '** FEATURED ITEMS **',          // widget name
    'ppt_widget_2_display',  // callback function
    array(                  // options
        'description' => 'A featured item display widget from PremiumPress.'
    )
);
wp_register_widget_control( 'ppt_widget_2', 'ppt_control_2', 'ppt_control_2');
}

 wp_register_sidebar_widget(
    'ppt_widget_3',        // your unique widget id
    '** CATEGORIES **',          // widget name
    'ppt_widget_3_display',  // callback function
    array(                  // options
        'description' => 'A category display widget from PremiumPress.'
    )
);
wp_register_widget_control( 'ppt_widget_3', 'ppt_control_3', 'ppt_control_3');

 
 wp_register_sidebar_widget(
    'ppt_widget_4',        // your unique widget id
    '** QUICK SEARCH **',          // widget name
    'ppt_widget_4_display',  // callback function
    array(                  // options
        'description' => 'A price search widget from PremiumPress.'
    )
);

wp_register_widget_control( 'ppt_widget_4', 'ppt_control_4', 'ppt_control_4');



//register_taxonomy( 'ram', 'post', array( 'hierarchical' => true, 'label' => 'RAM', 'query_var' => true, 'rewrite' => true ) ); 
?>