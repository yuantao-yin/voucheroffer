
<div class="itembox <?php if(get_post_meta($post->ID, "featured", true) == "yes"){?>hightlighted<?php }else{ ?><?php } ?>">
    
    <h1 class="icon-search-item"><?php if(function_exists('wp_gdsr_render_article')){ wp_gdsr_render_article(5);}  ?> <?php the_title(); ?></h1>
 
        <div class="itemboxinner">    
    
            <div class="post clearfix"> 
                                          
                <div class="thumbnail-large"> 
                
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <img src="<?php echo $PPT->Image($post->ID,"url","&amp;w=200"); ?>" class="listImage" alt="<?php the_title(); ?>" />
                    </a>  
                    
                </div> 
                
               
                <div class="text article"> 
                
                    <p><?php the_excerpt(); ?></p> 
                    
                    <div class="tags clearfix"> </div>
                
                    <div class="meta clearfix"> 
					
                           <a href="<?php the_permalink(); ?>"><?php echo $GLOBALS['_LANG']['_mored']; ?></a>
                           
            				<?php if(get_option("display_search_publisher") =="yes" && strlen($link) > 2){ ?>
                            <a href="<?php if(isset($link) && strlen($link) > 1){ echo $link; }else{ echo $url; } ?>" target="_blank" <?php if($GLOBALS['premiumpress']['nofollow'] =="yes"){ ?>rel="nofollow"<?php } ?>><?php echo $GLOBALS['_LANG']['_vps']; ?></a> 
                            <?php } ?>
            
            				<?php if(get_option("display_search_comments") =="yes"){ comments_popup_link($GLOBALS['_LANG']['_nocomments'], $GLOBALS['_LANG']['_1comment'], '% '.$GLOBALS['_LANG']['_comments']); } ?>
                    
                    </div>  
                    
                </div>
            
            </div> 
     
    
        </div>
    
</div>