<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header(); if (have_posts()) : while (have_posts()) : the_post();  ?>

<div class="middleSidebar left">    

   <img src="<?php echo $PPT->Image($post,"url","&amp;w=150"); ?>" alt="<?php the_title(); ?>" style="float:right; margin-right:30px; margin-top:10px; max-width:150px; max-height:150px;" />  

    <h1><?php the_title(); ?></h1>
    
    <p><em><?php the_time('l, F jS, Y'); ?></em></p>
     
    <div id="articlepage" class="entry"><?php the_content(); ?></div>
 
    <?php comments_template(); ?>

</div>

<?php endwhile; else :  endif; get_footer(); ?>