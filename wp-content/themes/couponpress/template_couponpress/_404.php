<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header(); ?>


    <div class="middleSidebar left"> <div class="padding">

		<h2 class="center"><?php echo SPEC($GLOBALS['_LANG']['_404_title']) ?></h2> 
        
        <?php if($current_user->wp_user_level == "10"){  ?>
        
        <div style="background:#ffe3e3;border:1px solid #a70c0d; padding:20px;">
        
        <h1 style="color:#a70c0d; font-size:20px;">Admin Help: Why am i seeing this page?</h1>        
        <p>This error 404 page means that for some reason the page could not be found or loaded correctly.</p>        
        <p>It maybe that your Wordpress permalink cache needs refreshing, <b><a href="<?php echo $GLOBALS['bloginfo_url']; ?>/wp-admin/options-permalink.php" target="_blank">click this link to view your admin permalink structure</a></b> then come back and refresh this page.</p>        
        <p>If the error continues try checking your website link is correct in your browser window or re-creating the page from scratch.</p>          
        <p><em>Note: You are seeing this page because you are logged into an admin account, normal website visitors will not see this message.</em></p>
        
        </div>
        <?php } ?>
              

	</div> </div>


<?php get_footer(); ?>