<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header(); ?>

<style>.photo { float:right; }</style>


<div class="middleSidebar left">    

<?php if ( have_posts() )the_post(); ?>
 
	
<?php if(function_exists('userphoto') && userphoto_exists($post->post_author)){ echo userphoto($post->post_author); }else{ echo get_avatar($post->post_author,52); } ?>
						
<h1><?php echo get_the_author(); ?></h1>

<p><?php the_author_meta( 'description' ); ?></p>

<p><img src="<?php echo IMAGE_PATH; ?>icon2.png" alt="<?php echo SPEC(str_replace("%a",get_the_author(),$GLOBALS['_LANG']['_author_send'])) ?>" align="middle" /> <a href="<?php echo get_option("messages_url"); ?>/?u=<?php the_author(); ?>"><?php echo SPEC(str_replace("%a",get_the_author(),$GLOBALS['_LANG']['_author_send'])) ?></a></p>

 

<h3><?php echo SPEC(str_replace("%a",get_the_author(),$GLOBALS['_LANG']['_author_send'])) ?></h3> 

   <div class="full clearfix border_t">
    
    <table id="hor-zebra">    
        <thead>    
            <tr>    
                <th scope="col"><?php echo SPEC($GLOBALS['_LANG']['_title']) ?></th>    
                <th scope="col"><?php echo SPEC($GLOBALS['_LANG']['_created']) ?></th>
            </tr>
        </thead>    
        <tbody>
        
        <?php
        
        $i=1;
        $posts = query_posts('caller_get_posts=1&author='.$post->post_author.'&post_type=post&post_status=publish&orderby=post_date&order=DESC'); 
        foreach($posts as $post){  
        
     
        $price_current 	= get_post_meta($post->ID, "price_current", true);
     
        $bid_status = get_post_meta($post->ID, "bid_status", true);
        if($bid_status == "open" || $bid_status ==""){$bid_status_text = "Running"; }else{ $bid_status_text = "<span style='color:#ccc;'>Ended</span>"; }	
        
        ?>
                
    
            <tr <?php if($i%2){ ?>class="odd"<?php } ?>>
    
                <td><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a></td>
    
                <td><?php echo get_the_date(); ?></td>
     
               
            </tr>
            
         <?php $i++; } ?>   
            
        </tbody>
    </table>
     
     
    
    </div>

</div>


<?php get_footer(); ?>
