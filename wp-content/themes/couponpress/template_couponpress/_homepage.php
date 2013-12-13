<?php get_header(); ?>

<div class="middleSidebar left">

	<?php /*------------------------------- HOME PAGE SLIDER -------------------------------------- */ ?>
    
        
    <?php echo do_shortcode('[promoslider height="190px"]') ?> 
       
   <?php if(get_option("PPT_slider") =="s2" ){  $GLOBALS['s2'] =1;echo $PPTDesign->SLIDER(2); } ?>
   
    

	<?php  /*------------------------------ HOME PAGE FEATURED IMAGE --------------------------------------- */ 
    
    
    if(get_option("display_featured_image_enable") =="1"){ print "<a href='".get_option("display_featured_image_link")."'><img src='".get_option("display_featured_image_url")."'  /></a>"; } ?>
        
        
    
	<?php  /*--------------------------- HOME PAGE CATEGORIES ------------------------------------------ */


	 if(get_option("display_homecats") =="yes"){ ?>
	
	
		<div class="itembox">
		
			<h2 id="icon-home-cats"><?php $count_posts = wp_count_posts();  $numcats = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE taxonomy='category'"); 
			echo SPEC(str_replace("%a",$count_posts->publish,str_replace("%b",number_format($numcats),$GLOBALS['_LANG']['_homepage_title']))) ?></h2>
			
			<?php echo $ThemeDesign->HomeCategories(); ?>
		 
		</div> 
	
	
	<?php } ?> 


   
 


    <?php /*------------------------- DISPLAY GALLERY BLOCK ----------------------------*/ ?> 
    
     <div class="itembox" id="itembox-home-new">
    
        <h2 id="icon-home-new"><?php echo SPEC($GLOBALS['_LANG']['_homepage2']) ?></h2>
        
        <div class="itemboxinner nopadding">	
        
        <ul class="couponlist">					
    
		<?php 
		
		$GLOBALS['query_string_new'] = $PPT->BuildSearchString(); $GLOBALS['query_file']="_item_small.php"; $PPTDesign->GALLERYBLOCK(); ?>
    
    	</ul>
    
    </div> 
    
  </div> 
  
  
 
 
   <?php /*------------------------- NAVIGATION BLOCK ----------------------------*/ ?>    
    
    
	<ul class="pagination paginationD paginationD10"> 
           
    	<?php echo $PPTFunction->PageNavigation(); ?>
             			 
	</ul>
	
    
    <?php /*------------------------- end NAVIGATION BLOCK ----------------------------*/ ?>   
  
  

    
    
	<div class="clearfix"> </div><br />
 
	<?php if(get_option("advertising_left_checkbox") =="1"){ echo "<br /><br />".$PPT->Banner("left"); } ?>
        
        
  
 </div>  


<?php get_footer(); ?>