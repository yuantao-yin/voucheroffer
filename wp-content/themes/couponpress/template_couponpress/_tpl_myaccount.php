<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header(); ?> 

<div class="middleSidebar left"><div class="padding"> 
 
<?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?>

<div id="My" style="display:visible;">

    <h1><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount1']) ?></h1> 
    
    
    <div class="full clearfix border_t box"> <br />
    <p class="f_half left"> 
        <a href="javascript:void(0);" onClick="jQuery('#My').hide(); jQuery('#MyDetails').show()" title="<?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount2']) ?>">
        <img src="<?php echo IMAGE_PATH; ?>a1.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount2']) ?></b><br />
        <?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount3']) ?>  
        </a>
    </p> 
    <p class="f_half left"> 
        <a href="<?php echo get_option("messages_url"); ?>" title="<?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount4']) ?>">
        <img src="<?php echo IMAGE_PATH; ?>a2.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount4']) ?></b><br />
        <?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount5']) ?>  
        </a> 
    </p> 
    </div> 
    
    
    <div class="full clearfix border_t box"> <br />
    <p class="f_half left"> 
        <a href="<?php echo get_option("manage_url"); ?>" title="<?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount6']) ?>">
        <img src="<?php echo IMAGE_PATH; ?>a3.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount6']) ?></b><br />
        <?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount7']) ?>
        </a>
    </p> 
    <p class="f_half left"> 
        <a href="<?php echo get_option('submit_url'); ?>" title="<?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount8']) ?>">
        <img src="<?php echo IMAGE_PATH; ?>a5.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount8']) ?></b><br />
       <?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount9']) ?>
        </a> 
    </p> 
    </div>  
    
    
    <div class="full clearfix border_t box"> <br />
    <p class="f_half left"> 
        <a href="<?php echo get_option("contact_url"); ?>" title="<?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount10']) ?>">
        <img src="<?php echo IMAGE_PATH; ?>a4.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount10']) ?></b><br />
        <?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount11']) ?> 
        </a>

    </p> 
    <p class="f_half left"> 
        <a href="<?php echo wp_logout_url(); ?>" title="<?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount12']) ?>">
        <img src="<?php echo IMAGE_PATH; ?>a6.png" style="float:left; padding-right:20px; margin-top:10px;" />
        <b><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount12']) ?></b><br />
        <?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount13']) ?>
        </a> 
    </p> 
    </div>          
    

</div>





<div id="MyDetails" style="display:none;">

        

<h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount14']) ?></h3>		



<form action="" method="post" enctype="multipart/form-data"> 
<input type="hidden" name="action" value="1" />

<?php if(isset($ADD[0])){ ?>
<script type="text/javascript"> jQuery(document).ready(function() { PremiumPressChangeStateMyAccount('<?php echo $ADD[0]; ?>', '<?php echo $ADD[1]; ?>'); }); </script>
<?php } ?>

<fieldset> 
 

<div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount15']) ?> <span class="required"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount16']) ?></span></label><br /> 
    <input type="text" value="<?php echo $userdata->user_login; ?>" disabled class="short" /><br /> 
</p> 
<p class="f_half left"> 
    <label for="email">Email <span class="required">*</span></label><br /> 
    <input type="text" name="form[user_email]" value="<?php echo $userdata->user_email; ?>" class="short" tabindex="10" /><br /> 
</p> 
</div> 



<div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount17']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="form[user_url]" value="<?php echo $userdata->user_url; ?>" class="short" tabindex="11" /><br /> 
</p> 
<p class="f_half left"> 
    <label for="comment2"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount18']) ?> <span class="required">*</span></label><br /> 
	<textarea tabindex="5" class="short" rows="4" name="form[description]"><?php echo $userdata->description; ?></textarea><br /> 

</p> 
</div>
                        
                        
 
  <h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount19']) ?></h3> 

 
<div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount20']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="form[first_name]" value="<?php echo $userdata->first_name; ?>" class="short" tabindex="12" /><br /> 
   
</p> 
<p class="f_half left"> 
    <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount21']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="form[last_name]" value="<?php echo $userdata->last_name; ?>" class="short" tabindex="13" /><br /> 
     
</p> 
</div>	
 

 <div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount22']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="address[country]" value="<?php echo $ADD[0]; ?>" class="short" tabindex="14" /><br /> 
     
</p> 
<p class="f_half left" style="margin-top:-25px;">
   
    <div id="PremiumPressState" ><input type="text" name="address[state]" value="<?php echo $ADD[1]; ?>" class="short" tabindex="15" /></div> 
    <br /> 
     
</p> 
</div>	


 <div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount24']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="address[city]" value="<?php echo $ADD[3]; ?>" class="short" tabindex="16" /><br /> 
 
</p> 
<p class="f_half left"> 
    <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount25']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="address[address]" value="<?php echo $ADD[2]; ?>" class="short" tabindex="17" /><br /> 
  
</p> 
</div>

 	
 <div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount26']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="address[zip]" value="<?php echo $ADD[4]; ?>" class="short" tabindex="18" /><br /> 
 
</p> 
<p class="f_half left"> 
    <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount27']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="address[phone]" value="<?php echo $ADD[5]; ?>" class="short" tabindex="19" /><br /> 
 
</p> 
</div>

		<?php 
		if(function_exists('userphoto_exists')){

		echo '<h3>'.SPEC($GLOBALS['_LANG']['_tpl_myaccount28']).'</h3><div class="full clearfix border_t box"> ';

			do_action('show_user_profile');

		  echo "<div id='user-photo'>";
			if(userphoto_exists($user_ID))
				userphoto($user_ID);
			else
				echo get_avatar($userdata->user_email, 96);
		  echo "</div>";
		 	
		 ?>

		
		<?php if($userdata->userphoto_image_file): ?>

			<p><input type="checkbox" name="userphoto_delete" id="userphoto_delete" /> <?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount29']) ?></p>
            
		<?php endif; 
		
		print '</div><div class="clearfix"></div> '; } ?>
 
   
                    
                        
 <h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount30']) ?></h3> 

<div class="full clearfix border_t box"> 

<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount31']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="password" class="short" tabindex="20" /><br /> 
  
</p> 
<p class="f_half left"> 
    <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount32']) ?> <span class="required">*</span></label><br /> 
    <input type="text" name="password_r" class="short" tabindex="21" /><br /> 
     
</p> 
</div>                     
                        
                        
                        
<div class="full clearfix border_t box"><p class="full clearfix"> 
<input type="submit" name="submit" id="submit" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_myaccount33']) ?>" /> 
</p></div> 

	
</fieldset></form> 



</div>


 


</div> </div>

<?php get_footer(); ?>