<?php
/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/
?>
  
<div class="middleSidebar left">	
    
<?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?>   
    
    
<h1><?php echo SPEC($GLOBALS['_LANG']['_tpl_articles1']) ?></h1>

<div class="full clearfix border_t">
 

<div class="FAQ_Content"><div class="item categories">
	
<h5><?php echo SPEC($GLOBALS['_LANG']['_tpl_articles2']) ?></h5>

<div class="full clearfix">
	
<?php 

	$taxonomy     = 'article';
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
	

	
print '	<div class="item featured"><h2>'.SPEC($GLOBALS['_LANG']['_tpl_articles3']).'</h2><ul>';

$posts = query_posts('posts_per_page=10&post_type=article_type&orderby=rand');

	foreach($posts as $post){  
	
		print '<li class="featuredPosts"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><strong>'.$post->post_title.'</strong>'.get_the_date().'</a></li>';
	
	}
	
	print '</ul></div>

		<div class="item latest-half">
		
			<h2>'.SPEC($GLOBALS['_LANG']['_tpl_articles4']).'</h2>
			<ul>';
			
			$posts = query_posts('posts_per_page=10&post_type=article_type&orderby=comment_count');
			foreach($posts as $post){  
			
				print '<li><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><strong>'.$post->post_title.'</strong>'.get_the_date().'</a></li>';
						
			}			
						
			
	print '</ul></div></div><div class="clearfix"></div>';


 ?>

</div> 




</div>

</div>  

 

<?php get_footer(); ?>