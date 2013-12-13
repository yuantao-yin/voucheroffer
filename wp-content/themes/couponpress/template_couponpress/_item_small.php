<?php

// LOAD COUPON CODE
$code 	= get_post_meta($post->ID, "code", true);			

// CHECK IF THE LISTING HAS EXPIRED
$PPT->CheckExpired($post->ID,$post->post_date);	

// COUPON EXPIRES
$ExpireDate = $PPT->TimeDiff(get_post_meta($post->ID, "expires", true));

 
?>
<li <?php if($featured == "yes"){ echo "class='featured'"; }?>>
	<div class="side1">
            
		<a href="<?php echo get_permalink($post->ID)?>" title="<?php echo $post->post_title?>" class="clearfix">
                
			<img src="<?php echo $PPT->Image($post->ID,"url","&amp;w=100"); ?>" width="100" alt="<?php echo $post->post_title?>" />
                
		</a> 
        
        <div class="clearfix"></div>  
              
		<div class="info"><?php if(function_exists('wp_gdsr_render_article_thumbs')){  wp_gdsr_render_article_thumbs(32); 
		}else{ comments_popup_link($GLOBALS['_LANG']['_nocomments'], $GLOBALS['_LANG']['_1comment'], '% '.$GLOBALS['_LANG']['_comments']); }?></div>              
                 
	</div>
            
	<div class="side2">
    
    <?php if(!isset($GLOBALS['setflag_faq']) && !isset($GLOBALS['setflag_article'])){  ?>
    
  	 <div class="coupon"> 
    
		<?php if($code != "" && $GLOBALS['premiumpress']['system'] =="normal"){ ?>            
                    
        <div class="couponcode clearfix" >
                
             <strong id="coupon_code_feat_<?php echo $post->ID; ?>"><?php echo $code; ?></strong>
                        
              <script language="javascript" type="text/javascript">
                 set_copy_command ( "<?php echo $code; ?>" , "coupon_code_feat_<?php echo $post->ID; ?>" , <?php echo $post->ID; ?>, "<?php echo $link; ?>" ) ;
              </script>
          
          </div>
                  
         <?php }elseif($code != "" && $GLOBALS['premiumpress']['system'] =="link"){ ?>
          
            <div id="clickreveal-<?php echo $post->ID; ?>" class="clicktoreveal-code clearfix">
            
               <em><?php echo SPEC($GLOBALS['_LANG']['_item1']) ?></em> <a href="<?php echo $link; ?>" target="_blank"><?php echo $code; ?></a>
             
            </div>
          
          <?php }elseif($code != "" && $link != "" && $GLOBALS['premiumpress']['system'] =="clicktoreveal"){ ?>
          
        
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
              
               <a href="<?php echo $GLOBALS['template_url']; ?>/_print.php?cid=<?php echo $post->ID; ?>&amp;lightbox[iframe]=true&amp;lightbox[width]=700&amp;lightbox[height]=450" class="lightbox oprint" rel="nofollow"><?php echo SPEC($GLOBALS['_LANG']['_item3']) ?></a> 
               
              <?php }else{ ?>
              
             
              <a href="<?php echo $link; ?>" target="_blank" rel="nofollow"><?php echo SPEC($GLOBALS['_LANG']['_item4']) ?></a>
                        
              <?php } ?>          
          
          </div> 
          
          <?php }  ?>
          
        <div class="clearfix"></div>  
        
      	<div class="category"><?php the_category(', ') ?></div> 
         
   </div>
   
   <?php } ?>
   
               
                
                
		<h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo $post->post_title?></a></h3>

		<p class="excerpt"><?php echo $post->post_excerpt ?>&nbsp;&nbsp;</p>
        
        <?php if(strlen($ExpireDate) > 1){ ?><p class="expires"><?php echo $ExpireDate; ?> </p> <?php } ?>
 
		<div class="clearfix"></div>
        
 
        <div class="optionslist-wrapper clearfix">
           
              <div class="optionslist">
              
              <a class="addthis_button oshare" href="http://www.addthis.com/bookmark.php"><?php echo SPEC($GLOBALS['_LANG']['_item5']) ?></a>
              
              <?php if(!isset($GLOBALS['setflag_faq']) && !isset($GLOBALS['setflag_article'])){ ?>
              
              <a href="<?php echo $GLOBALS['template_url']; ?>/_print.php?cid=<?php echo $post->ID; ?>&amp;lightbox[iframe]=true&amp;lightbox[width]=700&amp;lightbox[height]=450" class="lightbox oprint" rel="nofollow"><?php echo SPEC($GLOBALS['_LANG']['_item6']) ?></a> 
              <?php } ?>
              <a href="<?php echo get_permalink($post->ID)?>" title="<?php echo $post->post_title?>" class="ohits"><?php echo get_post_meta($post->ID, 'hits', true); ?> <?php echo SPEC($GLOBALS['_LANG']['_item7']) ?></a>  
              <a href="<?php echo $GLOBALS['premiumpress']['contact_url']; ?>?report=<?php echo $post->ID; ?>" class="oreport" rel="nofollow"><?php echo SPEC($GLOBALS['_LANG']['_item8']) ?></a> 
            
              </div>
                       
           
       </div>
 
 
   
   </div>
            
   <div class="clearfix"></div>
            
            
	<div id="coupon_Tool_tip_action_<?php echo $post->ID; ?>" class="couponTooltip"><?php echo SPEC($GLOBALS['_LANG']['_item9']) ?></div>
          
            
</li>