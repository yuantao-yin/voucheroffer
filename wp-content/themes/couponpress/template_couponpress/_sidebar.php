<?php
/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/
?>
<div id="sidebar" class="rightSidebar left">

<?php if(isset($GLOBALS['tpl-add'])){ $uFinfo = get_option("pak_help_text"); ?>

<?php if(strlen($uFinfo) > 1){ ?>

    <div class="itembox" style="margin-top:20px;">
    
        <h2 id="icon-sidebar-add"><?php echo SPEC($GLOBALS['_LANG']['_side1']) ?></h2>
        
        <div class="itemboxinner">
        
            <?php echo nl2br(stripslashes($uFinfo)); ?>
        
        </div>    
    
    </div> 
<?php } ?>    
    

<?php }elseif(is_single() ||   isset($GLOBALS['premiumpress']['taxonomy']) && ( $GLOBALS['premiumpress']['taxonomy'] =="article" || $GLOBALS['premiumpress']['taxonomy'] =="faq" ) ){ ?>

<?php if($post->post_type =="article_type" || $post->post_type =="faq_type" || ( $GLOBALS['premiumpress']['taxonomy'] == "article" || $GLOBALS['premiumpress']['taxonomy'] =="faq" ) ){ 
if($post->post_type == "faq_type"){ $GLOBALS['premiumpress']['taxonomy']="faq_type";}
	if($GLOBALS['premiumpress']['taxonomy'] == "article"){
	$tty = $GLOBALS['premiumpress']['taxonomy'];
	}else{
	$tty = $GLOBALS['premiumpress']['taxonomy'];
	}	
	if($tty == ""){ $tty="article"; }

	$taxonomy     = str_replace("_type","",$tty);
	$orderby      = 'name'; 
	$show_count   = 1;      // 1 for yes, 0 for no
	$pad_counts   = 1;      // 1 for yes, 0 for no
	$hierarchical = 1;      // 1 for yes, 0 for no
	$title        = '';
	$fcats 		  = '';
	
	$args = array(
	  'taxonomy'     => $taxonomy,
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
	  'title_li'     => $title,
	  'hide_empty'	=> 0
	);
 
	?>
    <div class="itembox" style="margin-top:20px;">
    
        <h2 id="icon-sidebar-singleinfo"><?php echo SPEC($GLOBALS['_LANG']['_categories']) ?></h2>
        
        <div class="itemboxinner nopadding">
        
        	<ul class="category" id="Accordion">
        
			<?php 
            $cats  = get_categories( $args );
            foreach($cats as $cat){   $fcats .= $cat->cat_ID.",";
            
                        
            print '<li><a href="'.get_term_link( $cat,$cat->taxonomy  ).'" title="'.$cat->category_nicename.'">'.$cat->cat_name.'</a></li>';
        
              }
            ?>
            </ul>
    
    	</div>
    
    </div>
    
    

  <?php if(function_exists('dynamic_sidebar')){ dynamic_sidebar('Right Sidebar - Article Page'); } ?>
  
  
  <?php }else{ 
  
  
  $STOREIMAGE =  $PPT->CategoryExtras($GLOBALS['singleCategory'][0]->cat_ID,"image",1); 
  $STORETEXT =  $PPT->CategoryExtras($GLOBALS['singleCategory'][0]->cat_ID,"text");
  
  ?>
  
  

      <div class="addthis_toolbox">   
        <div class="custom_images">
                <a class="addthis_button_twitter"><img src="<?php echo $GLOBALS['template_url']; ?>/template_couponpress/images/twitter.png" width="60" height="60" alt="Twitter" /></a>
                <a class="addthis_button_facebook"><img src="<?php echo $GLOBALS['template_url']; ?>/template_couponpress/images/facebook.png" width="60" height="60" alt="Facebook" /></a>
                <a class="addthis_button_myspace"><img src="<?php echo $GLOBALS['template_url']; ?>/template_couponpress/images/myspace.png" width="60" height="60" alt="MySpace" /></a>
                <a class="addthis_button_delicious"><img src="<?php echo $GLOBALS['template_url']; ?>/template_couponpress/images/delicious.png" width="60" height="60" alt="Digg" /></a>
                <a class="addthis_button_digg"><img src="<?php echo $GLOBALS['template_url']; ?>/template_couponpress/images/digg.png" width="60" height="60" alt="Digg" /></a>
        </div>
    </div>
    
    <?php if(strlen($STORETEXT) > 1){ ?>
    
    <div class="itembox">
    
        <h2 id="sidebar-single-store"><?php echo $GLOBALS['singleCategory'][0]->name; ?></h2>
        
        <div class="itemboxinner">
        
       <?php if(strlen($STOREIMAGE) > 2){ ?> <img src="<?php echo $STOREIMAGE; ?>" style="float:right; padding-right:20px; max-width:100px;" /><?php } ?>
        
    	<?php    echo $STORETEXT;   ?>
        
        <p><a href="<?php echo get_category_link($GLOBALS['singleCategory'][0]->cat_ID); ?>" title="<?php echo $GLOBALS['singleCategory'][0]->name; ?> coupons"><?php echo SPEC($GLOBALS['_LANG']['_side6']) ?> <?php echo $GLOBALS['singleCategory'][0]->name; ?></a></p>
        
        </div>    
    
    </div>
    
    <?php } ?>
    
    
	<?php if(get_option("display_sidebar_memberinfo") =="yes"){ ?>
    
      <div class="itembox" style="margin-top:20px;">
    
        <h2 id="icon-sidebar-singleinfo"><?php echo SPEC($GLOBALS['_LANG']['_side2']) ?></h2>
        
        <div class="itemboxinner">
        
        <a href="<?php echo get_author_posts_url( $post->post_author, $post->user_nicename ); ?>" title="<?php echo get_the_author(); ?>">
		<?php if(function_exists('userphoto') && userphoto_exists($post->post_author)){ echo userphoto($post->post_author); }else{ echo get_avatar($post->post_author,52); } ?>
        </a>
        
        <h3><?php the_author_posts_link(); ?></h3> 
            
        <p><?php the_author_meta('description'); $count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = ".$post->post_author." AND post_type IN ('post') and post_status = 'publish'" ); ?></p> 
        
        <div class="full box clearfix"> 
       <p><img src="<?php echo IMAGE_PATH; ?>icon1.png" alt="send email" align="middle" /> <a href="<?php echo get_author_posts_url( $post->post_author, $post->user_nicename ); ?>" title="<?php echo get_the_author(); ?>">
		<?php echo SPEC(str_replace("%a",get_the_author(),str_replace("%b",$count,$GLOBALS['_LANG']['_side3']))) ?> </a></p>
        <p><img src="<?php echo IMAGE_PATH; ?>icon2.png" alt="send email" align="middle" /> <a href="<?php echo get_option("messages_url"); ?>/?u=<?php the_author(); ?>"><?php echo SPEC(str_replace("%a",get_the_author(),$GLOBALS['_LANG']['_side4'])) ?></a></p>
        </div> 
        
         <em><?php echo SPEC($GLOBALS['_LANG']['_side5']) ?> <?php the_time('l, F jS, Y') ?></em>  

        
        </div>    
    
    </div>  
	<?php } ?>
        
    
    
    <?php  
	
	// GOOGLE MAPS INTEGRATION	
    // $GLOBALS['mapType'] defined in header.php
    if(isset($GLOBALS['mapType']) && strlen($GLOBALS['mapType']) > 2  && $GLOBALS['mapType'] !="no" && strlen($GLOBALS['map']) > 1) { 
						
	
	?>
    
	<div class="itembox">
    
        <h2 id="icon-sidebar-map"><?php echo SPEC($GLOBALS['_LANG']['_side7']) ?></h2>
        
        <div class="itemboxinner">
        
        <?php  if($GLOBALS['mapType'] == "yes1"){ ?>                      
                        
         <iframe id="GoogleMap" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"  style="padding-right:10px; width:100%; height:250px;"
                        src="http://maps.google.com/maps/api/staticmap?zoom=10&size=300x250&markers=color:red|label:Here|<?php echo $GLOBALS['map']; ?>&sensor=false&key=<?php echo get_option("google_maps_api"); ?>">
         </iframe>                      
                        
         <?php }elseif($GLOBALS['mapType'] == "yes2"){ ?>                        
                     
         <div id="map_sidebar2" style="width: 100%; height: 200px"></div>                        
                        
         <?php } ?>
        
         </div>    
    
	</div> 
    
    <?php } ?>
    
               
 <?php } ?>   
     
    
<?php }else{ ?>
 
 

<a href="<?php echo $GLOBALS['premiumpress']['submit_url']; ?>" title="add coupons" id="icon-side-addcoupon">&nbsp;&nbsp;</a>
 
<a href="<?php echo $GLOBALS['premiumpress']['manage_url']; ?>" title="add coupons" id="icon-side-managecoupon">&nbsp;&nbsp;</a>

<div class="clearfix"></div> 
  
 

    <?php if(isset($GLOBALS['premiumpress']['catID']) && is_numeric($GLOBALS['premiumpress']['catID']) && $PPT->CountCategorySubs($GLOBALS['premiumpress']['catID']) > 0 && get_option('display_categories') =="yes" ){ ?>
    
    <div class="itembox">
    
        <h2 id="sidebar-cats-sub"><?php echo SPEC($GLOBALS['_LANG']['_side8']) ?></h2>
        
        <div class="itemboxinner nopadding">
        
        <?php echo $ThemeDesign->HomeCategories(); ?>
        
        </div>
    
    
    </div>
        
    <?php } ?>
    
    
    
    
    <?php $fData = $ThemeDesign->FeaturedCategories(); if(strlen($fData) > 5){ ?>
    
     <div class="itembox">
    
        <h2 id="sidebar-featuredstore"><?php echo SPEC($GLOBALS['_LANG']['_side9']) ?></h2>
        
        <div class="itemboxinner nopadding">
        
         <ul id="featuredstores" class="clearfix">
         
         <?php echo $ThemeDesign->FeaturedCategories(); ?>
         
         </ul>
         
        <div class="clearfix">&nbsp;</div>
        
        </div>    
     
    </div> 
    
    <?php } ?> 
    
   
    
     <div id="popularcoupons" class="itembox">
    
        <h2 id="sidebar-popular"><?php echo SPEC($GLOBALS['_LANG']['_side10']) ?></h2>
        
        <div class="itemboxinner nopadding">
        
         <ul class="CouponPopList">
         
         <?php echo $ThemeDesign->PopularCoupons(); ?>
         
         </ul>
         
          <div class="clearfix">&nbsp;</div>
        
        </div>
    
    
    </div>  
      
    
    
    
    <?php if(get_option("display_sidebar_articles") =="yes"){ ?>
    
    <div class="itembox" style="margin-top:20px;">
    
        <h2 id="icon-sidebar-article"><?php echo SPEC($GLOBALS['_LANG']['_side11']) ?></h2>
        
        <div class="itemboxinner">
        
        	<ul id="sidebar_recentarticle">
            <?php echo $PPT->Articles(get_option('display_sidebar_articles_count'),true); ?>
        	</ul>
            
        </div>    
    
    </div> 
    
    <?php } ?>

    
   
<?php } ?> 



<?php 	
	
/****************** INCLUDE WIDGET ENABLED SIDEBAR *********************/

if(function_exists('dynamic_sidebar')){ 

	if(is_single() && !isset($GLOBALS['ARTICLEPAGE']) ){
	dynamic_sidebar('Listing Page Sidebar');
	}elseif(is_single() && isset($GLOBALS['ARTICLEPAGE']) ){
	dynamic_sidebar('Article/FAQ Page Sidebar');	
	}elseif(is_page()){
	dynamic_sidebar('Pages Sidebar') ;
	}else{
	dynamic_sidebar('Right Sidebar');  
	}

}

/****************** end/ INCLUDE WIDGET ENABLED SIDEBAR *********************/

?>

<?php 					
			if(isset($GLOBALS['premiumpress']['catID']) && is_numeric($GLOBALS['premiumpress']['catID'])){ 					
				echo $PPT->BannerZone($GLOBALS['premiumpress']['catID']); 					
			}
?>
    
<?php if(get_option('advertising_right_checkbox') =="1"){ ?><center><?php echo $PPT->Banner("right");?></center><?php } ?>  
  
</div>
 