<?php get_header(); ?>

<div class="middleSidebar left">


    <?php /*------------------------- CUSTOM CATEGORY TEXT ----------------------------*/ ?> 
 	
    <?php  if(strlen($GLOBALS['catText']) > 10){ ?>
    
	<div class="itembox">
    
        <h2 id="icon-home-cats"><?php echo $GLOBALS['premiumpress']['catName']; ?></h2>
        
        <div class="itemboxinner">
        
        <?php if(strlen($GLOBALS['catImage']) > 2){ ?><img src="<?php echo $GLOBALS['catImage']; ?>" style="float:left; padding-right:20px; max-width:60px;" /><?php } ?>
            
     	<?php echo $GLOBALS['catText']; ?>
 
   		</div>
   
	</div> 
    
	<?php }else{ ?>
    
	<h1 class="categoryTitle"><?php if(isset($_GET['s'])){ echo SPEC($GLOBALS['_LANG']['_search'])." ".strip_tags($_GET['s']); }elseif( isset($_GET['search-class'])) { echo SPEC($GLOBALS['_LANG']['_search'])." ".strip_tags($_GET['cs-all-0']); }else{ echo $GLOBALS['premiumpress']['catName']; } ?></h1> 
    
    <?php } ?>
    
    
    
    
    
    
    <?php /*------------------------- DISPLAY ORDER BY LIST BOX ----------------------------*/ ?> 
     
   
   <?php if($GLOBALS['query_total_num'] > 0 && !isset($GLOBALS['setflag_article']) && !isset($GLOBALS['tag_search']) && !isset($GLOBALS['setflag_faq']) ){ echo $PPTDesign->GL_ORDERBY(); } ?> 
   
   <?php  if(strlen($STORETEXT) > 10){ ?><h3><?php echo $GLOBALS['premiumpress']['catName'];  ?> <?php echo SPEC($GLOBALS['_LANG']['_coupons']) ?></h3><?php } ?>
    
    <br /><div class="clearfix"></div> 
    
    
           
    


    <?php /*------------------------- DISPLAY GALLERY BLOCK ----------------------------*/ ?> 
       
    <?php if($GLOBALS['query_total_num'] > 0){ ?>
    
    <div id="SearchContent"><br /><div class="clearfix"></div> 
        
        <div class="itembox">
        
            <h2 id="icon-home-cats"><?php echo $GLOBALS['query_total_num']; ?> <?php echo SPEC($GLOBALS['_LANG']['_gal1']) ?></h2>
            
                <div class="itemboxinner nopadding"> 
                    
                    <ul class="couponlist">
                    
                        <?php $GLOBALS['query_file']="_item_small.php"; $PPTDesign->GALLERYBLOCK(); ?>
                        
                    </ul>
                    
                </div>
        </div>
        
    </div> 
    
    <?php } ?> 
      
    <?php /*------------------------- END DISPLAY GALLERY BLOCK ----------------------------*/ ?>
    

    
    
    
      
    <?php /*------------------------- NO RESULTS BLOCK ----------------------------*/ ?>
    
    
    <?php if($GLOBALS['query_total_num'] == 0 && isset($GLOBALS['GALLERYPAGE']) ){ ?>
    
    <p><?php echo SPEC($GLOBALS['_LANG']['_gal3']) ?></p>
    
    <p><?php wp_tag_cloud('smallest=15&largest=40&number=50&orderby=count'); ?></p>
    
    <?php } ?>
     
    
    <?php /*------------------------- end NO RESULTS BLOCK ----------------------------*/ ?>          
 
    
    
    
    
    
    
    
   <?php /*------------------------- NAVIGATION BLOCK ----------------------------*/ ?>    
    
    
	<?php if($GLOBALS['query_total_num'] > 0){ ?>
    	
        <div class="clearfix"> </div>
        
        <ul class="pagination paginationD paginationD10"> 
           
            <?php echo $PPTFunction->PageNavigation(); ?>
             			 
        </ul>
        
        <div class="clearfix"> </div><br />
        
	<?php } ?>    
 
	
    
    <?php /*------------------------- end NAVIGATION BLOCK ----------------------------*/ ?>     
    
    
    
    
    
    
 
	<?php /*------------------------- LEFT ADVERTING BLOCK FOR 2 COLUMN LAYOUTS ----------------------------*/ ?>    

    
	<?php if($GLOBALS['premiumpress']['display_themecolumns'] !="3"){ if(get_option("advertising_left_checkbox") =="1"){ echo "<br /><br />".$PPT->Banner("left"); } } ?>
 
    

</div>


<?php get_footer(); ?>