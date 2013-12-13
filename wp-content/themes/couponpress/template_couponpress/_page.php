<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header( ); ?> 

<div class="middleSidebar left">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                                
    <h1><?php the_title(); ?></h1>
    
    <div class="entry">
    
    <?php the_content(); ?>     
    
    </div>		
    
    <?php endwhile; endif; ?>

</div>
 
 
<?php get_footer(); ?>