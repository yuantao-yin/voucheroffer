<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header(); ?>

  
<div class="middleSidebar left">

<?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?>
                   
                    
        
        
<?php if(get_option("display_FAQ") =="yes"){ ?><input type="button" onClick="jQuery('#messageView').show()" style="float:right;color:#333333" class="button grey" tabindex="15" value="Contact Us" /> <?php } ?>


<h1><?php echo SPEC($GLOBALS['_LANG']['_tpl_contact1']) ?></h1>				






<div id="messageView" <?php if(!isset($_GET['report']) && get_option("display_FAQ") =="yes"){ ?>style="display:none;"<?php } ?>>   

    <form action="" method="post"> 
    <input type="hidden" name="action" value="1" />
    <?php if(isset($_GET['report'])){ ?><input type="hidden" name="report" value="<?php echo strip_tags($_GET['report']); ?>" /><?php } ?>
	<?php //wp_nonce_field('ContactForm') ?>
    
    <fieldset> 
    <?php echo stripslashes(get_option("contact_page_text")); ?>  
    
    
    
    <div class="full clearfix border_t box"> 
    <p class="f_half left"> 
        <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_contact2']) ?><span class="required">*</span></label><br /> 
        <input type="text" name="form[name]" value="<?php echo $userdata->user_nicename; ?>" class="short" tabindex="1" /><br /> 
        
    </p> 
    <p class="f_half left"> 
        <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_contact3']) ?><span class="required">*</span></label><br /> 
        <input type="text" name="form[email]" value="<?php echo $userdata->user_email; ?>" class="short" tabindex="2" /><br /> 
     
    </p> 
    </div> 
    <div class="full clearfix border_t box">         
    <p>
       <label for="comment"><?php echo SPEC($GLOBALS['_LANG']['_tpl_contact4']) ?><span class="required">*</span></label><br /> 
       <textarea tabindex="4" class="long" rows="4" name="form[message]"><?php if(isset($_POST['form']['message'])){  print strip_tags($_POST['form']['message']); }?></textarea><br /> 
      
    </p>        
    </div>  
    
    <div class="full clearfix border_t box"> 
    <p class="f_half left"> 
        <label for="name"><?php echo SPEC(str_replace("%a",$email_nr1,str_replace("%b",$email_nr2,SPEC($GLOBALS['_LANG']['_single10'])))) ?></span></label><br /> 
        <input type="text" name="form[code]" value="" class="short" tabindex="1" /><br /> 
		<input type="hidden" name="form[code_value]" value="<?php echo $email_nr1+$email_nr2; ?>" />
    </p> 
 
    </div>                
                            
    <div class="full clearfix border_t box"><p class="full clearfix"> 
    <input type="submit" name="submit" id="submit" class="button blue" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_contact6']) ?>" /> 
    </p></div> 
        
    </fieldset></form> 

</div> 





<?php if(get_option("display_FAQ") =="yes"){ ?>

<div class="FAQ_Content" id="FAQSTYLE" <?php if(isset($_GET['report'])){ ?>style="display:none;"<?php } ?>><div class="item categories">
	
<h5><?php echo SPEC($GLOBALS['_LANG']['_tpl_contact5']) ?></h5>

<div class="full clearfix border_t" >
	
<?php 

	$taxonomy     = 'faq';
	$orderby      = 'name'; 
	$show_count   = 1;      // 1 for yes, 0 for no
	$pad_counts   = 1;      // 1 for yes, 0 for no
	$hierarchical = 1;      // 1 for yes, 0 for no
	$title        = '';
	$fcats 		  = '';
	$i			  = 0;
	$args = array(
	  'taxonomy'     => $taxonomy,
	  'orderby'      => $orderby,
	  'show_count'   => $show_count,
	  'pad_counts'   => $pad_counts,
	  'hierarchical' => $hierarchical,
	  'title_li'     => $title,
	  'hide_empty'	=> 0
	);
	
	$cats  = get_categories( $args );
	foreach($cats as $cat){   $fcats .= $cat->cat_ID.",";
	
	if($i%2){ $ex ="space"; }else{ $ex =""; }
	if($i == 3){ print '<div class="clearfix"></div>'; $ex =""; $i=0;}
				

	print '<div class="categoryItem '.$ex.'">
				
			<a href="'.get_term_link( $cat,$cat->taxonomy  ).'" title="'.$cat->category_nicename.'">'.$cat->cat_name.'</a>
			<p>'.$cat->description.'</p>
		
		</div>';

	$i++; }

	print '<div class="clearfix"></div></div>';
	

	
print '	<div class="item featured"><h2>'.SPEC($GLOBALS['_LANG']['_tpl_contact8']).'</h2><ul>';

$posts = query_posts('posts_per_page=5&post_type=faq_type&orderby=rand');

	foreach($posts as $post){  
	
		print '<li class="featuredPosts"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><strong>'.$post->post_title.'</strong>'.get_the_date().'</a></li>';
	
	}
	
	print '</ul></div>

		<div class="item latest-half">
		
			<h2>'.SPEC($GLOBALS['_LANG']['_tpl_contact7']).'</h2>
			<ul>';
			
			$posts = query_posts('posts_per_page=5&post_type=faq_type&orderby=comment_count');
			foreach($posts as $post){  
			
				print '<li><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><strong>'.$post->post_title.'</strong>'.get_the_date().'</a></li>';
						
			}			
						
			
	print '</ul></div></div><div class="clearfix"></div>';


 ?>
</div> 
<?php } ?>



</div>  

 

<?php get_footer(); ?>