<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header(); ?> 

<div class="middleSidebar left">

<?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?> 

<h1><?php echo $GLOBALS['_LANG']['_tpl_edit_title']; ?></h1>

<p><?php echo $GLOBALS['_LANG']['_tpl_edit_text']; ?></p>

<table id="gradient-style">
    <thead>
    	<tr>
        	<th scope="col"><?php echo $GLOBALS['_LANG']['_tpl_edit_b1']; ?></th>
            <th scope="col"><?php echo $GLOBALS['_LANG']['_tpl_edit_b2']; ?></th>
            <th scope="col"><?php echo $GLOBALS['_LANG']['_tpl_edit_b3']; ?></th>
            <th scope="col" align="center"><?php echo $GLOBALS['_LANG']['_tpl_edit_b4']; ?></th>
        </tr>
    </thead>
    <tbody>
		<?php echo $PPTDesign->MANAGE($user_ID); ?>
    </tbody>
</table>

</div> 

<?php get_footer(); ?>