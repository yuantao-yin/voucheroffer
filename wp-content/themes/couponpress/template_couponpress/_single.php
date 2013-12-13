<?php 
/*
LAST UPDATED: 1st April 2011
EDITED BY: MARK FAIL
*/

// RMEOVE LEFT SIDEBAR
$GLOBALS['nosidebar-left'] = 1;

$GLOBALS['singleCategory'] 	= get_the_category($post->ID); 
  
get_header( ); 
if (have_posts()) : while (have_posts()) : the_post();


// SETUP GLOBAL VALUES FROM CUSTOM DATA
$GLOBALS['images'] 		= get_post_meta($post->ID, 'images', true);
$GLOBALS['map'] 		= get_post_meta($post->ID, "map_location", true);

// LOAD THE LINK CLOAKING FILE
$link 	= $PPT->CheckLink($post);
		
// LOAD COUPON CODE
$code 	= get_post_meta($post->ID, "code", true); 

 // COUPON EXPIRES
$ExpireDate = $PPT->TimeDiff(get_post_meta($post->ID, "expires", true));
?> 

<div class="middleSidebar left">







<div class="itembox" id="itembox-single-top">
    
    <h1 id="icon-single-top"><?php the_title(); ?> <?php if(isset($GLOBALS['packageIcon']) && strlen($GLOBALS['packageIcon']) > 2){ echo "<img src='".$PPT->ImageCheck($GLOBALS['packageIcon'])."' class='floatr'>"; } ?></h1>
 
        <div class="itemboxinner">
    
            <div class="post clearfix"> 
                                          
                <div class="thumbnail-large"> 
                
                  <img src="<?php echo $PPT->Image($post->ID,"url","&amp;w=200"); ?>" class="listImage" alt="<?php the_title(); ?>" />                    
                    
                </div> 
                
               
                <div class="text">  
                    
                    
					<?php if($code != "" && $GLOBALS['premiumpress']['system'] =="normal"){ ?>            
                    <div class="coupon" >
                    <div class="couponcode clearfix" >
                            
                         <strong id="coupon_code_feat_<?php echo $post->ID; ?>"><?php echo $code; ?></strong>
                                    
                          <script language="javascript" type="text/javascript">
                             set_copy_command ( "<?php echo $code; ?>" , "coupon_code_feat_<?php echo $post->ID; ?>" , <?php echo $post->ID; ?>, "<?php echo $link; ?>" ) ;
                          </script>
                      
                      </div>
                      </div>
                      <div id="coupon_Tool_tip_action_<?php echo $post->ID; ?>" class="couponTooltip"><?php echo SPEC($GLOBALS['_LANG']['_item9']) ?></div>
 
                     <?php }elseif($code != "" && $GLOBALS['premiumpress']['system'] =="link"){ ?>
                      
                        <div id="clickreveal-<?php echo $post->ID; ?>" class="clicktoreveal-code clearfix">
                        
                           <em><?php echo SPEC($GLOBALS['_LANG']['_item1']) ?></em> <a href="<?php echo $link; ?>" target="_blank"><?php echo $code; ?></a>
                         
                        </div>
                      
                      <?php }elseif($code != "" && $GLOBALS['premiumpress']['system'] =="clicktoreveal"){ ?>
                      
                    
                      <div id="hide-<?php echo $post->ID; ?>" class="clicktoreveal-link clearfix" style="display:visible">
                      
                                <a href="<?php echo $link; ?>" target="_blank" onclick="jQuery('#clickreveal-<?php echo $post->ID; ?>').show();jQuery('#hide-<?php echo $post->ID; ?>').hide();" rel="nofollow">
                                
                                <?php echo SPEC($GLOBALS['_LANG']['_item2']) ?>
                                
                                </a>
                      
                      </div>          
                      
                      <div id="clickreveal-<?php echo $post->ID; ?>" class="clicktoreveal-code" style="display:none;">
                      
                            <?php echo SPEC($GLOBALS['_LANG']['_item1']) ?> <?php echo $code; ?>
                            
                      </div>
                        
                        
				  <?php }elseif($code == ""){ ?> 
              
              
                      <div class="clicktoreveal-link clearfix" style="display:visible">
                      
                          <?php if(get_post_meta($post->ID, "type", true) == "print"){ ?>
                          
                           <a href="<?php echo $GLOBALS['template_url']; ?>/_print.php?cid=<?php echo $post->ID; ?>&lightbox[iframe]=true&lightbox[width]=700&lightbox[height]=450" class="lightbox oprint"><?php echo SPEC($GLOBALS['_LANG']['_item3']) ?></a> 
                           
                          <?php }else{ ?>
                          
                         
                          <a href="<?php echo $link; ?>" target="_blank" rel="nofollow"><?php echo SPEC($GLOBALS['_LANG']['_item4']) ?></a>
                                    
                          <?php } ?>          
                      
                      </div> 
                      
                      
                     <?php } ?>
          
          
                      
                      <div class="clearfix"></div>
                      
                     <!-- <p><?php the_excerpt(); ?></p> -->
                     
                     <div class="post-content">
                    
                    	<?php the_content(); ?>
                        
                        <?php if(strlen($ExpireDate) > 1){ ?><p class="expires"><?php echo $ExpireDate; ?> </p> <?php } ?>
                        
                        <?php $ThemeDesign->SINGLE_CUSTOMFIELDS($post,$CustomFields); ?> 
                        
                        <?php echo $ThemeDesign->SINGLE_IMAGEGALLERY($GLOBALS['images']); ?>
                        
                     </div> 
                     
                     
                    <div class="optionslist-wrapper clearfix">
                       
                          <div class="optionslist">
                          
                          <a class="addthis_button oshare" href="http://www.addthis.com/bookmark.php"><?php echo SPEC($GLOBALS['_LANG']['_item5']) ?></a>
                          <a href="<?php echo $GLOBALS['template_url']; ?>/_print.php?cid=<?php echo $post->ID; ?>&amp;lightbox[iframe]=true&amp;lightbox[width]=700&amp;lightbox[height]=450" class="lightbox oprint" rel="nofollow"><?php echo SPEC($GLOBALS['_LANG']['_item6']) ?></a>  
                          </div>
                                   
                       
                   </div>
                
                </div>
            
            </div>
    
        </div>
    
</div>
    
 
    
<?php endwhile; else :  endif; wp_reset_query();  ?>  
   
   
   
   
   
   
    
    
    
    
    
    
    <?php if(get_option("display_related_coupons") =="yes"){
	
	$STORECURRENTID = $post->ID; $postslist = query_posts('numberposts=20&order=DESC&orderby=meta_value&showposts=10&&meta_key=hits&post_type=post&cat='.$GLOBALS['singleCategory'][0]->cat_ID); if(count($postslist) > 1){ ?>
    
    <div class="itembox">
    
        <h2 id="icon-single-related"><?php echo SPEC($GLOBALS['_LANG']['_single3']) ?></h2>
        
        <ul class="CouponPopList">
        
        <?php foreach ($postslist as $loopID => $p) :  $post=$p;		
		
		
		if($p->ID != $STORECURRENTID){  
		
		
		// LOAD COUPON CODE
		$code 	= get_post_meta($p->ID, "code", true);  		
		
		?>
       
        <?php include("_item_small.php"); ?>
        
        <?php } endforeach; ?>
    
        </ul>
        
    </div>

	<?php } } ?>






</div>
 
 
<?php wp_reset_query();  get_footer(); ?>