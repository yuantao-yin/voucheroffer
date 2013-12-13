<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/
 
get_header(); 
 
 if(isset($_GET['u']) ){ ?> 
<script>jQuery(document).ready(function() {jQuery('#messageView').hide();jQuery('#messageBox').show();});</script>
<?php }elseif(isset($_GET['mid']) && is_numeric($_GET['mid']) ){ $msgData = wp_get_single_post( $_GET['mid'] );  ?>
<script>jQuery(document).ready(function() {jQuery('#messageView').hide();jQuery('#messageBox').hide(); jQuery('#messageRead').show(); });</script>
<?php } ?>
 
 


<div class="middleSidebar left"> 
 
<?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?>    
    


<?php if(isset($msgData)){ ?>    

    <div id="messageRead" style="display:none;">
    
    <h3><?php echo $msgData->post_title; ?></h3> 
    
    <div class="full clearfix border_t border_b padding">
    <p style="font-size:16px;"><?php echo $msgData->post_content; ?></p>
    <p>Date Sent: <?php echo $msgData->post_date; ?></p>
    </div>
    <br />
    <?php if($msgData->post_author != 0){ ?>
    <br />
    <div class="reply"><a href="<?php echo get_option("messages_url"); ?>/?u=<?php the_author_meta('user_nicename',$msgData->post_author); ?>">Reply to <?php the_author_meta('user_nicename',$msgData->post_author); ?></a></div>
    <?php } ?>              
    </div>  
  
<?php } ?>    
    
    
    
    
<div id="messageBox" style="display:none;">
 
    <h4><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages13']) ?></h4> 
    
    <form action="" method="post" onsubmit="return CheckMessageData(this.message_name.value,this.message_subject.value,this.message_message.value,'Please complete all fields.');"> 
    <input type="hidden" name="action" value="add" />
    <fieldset> 
                             
        <div class="full clearfix border_t box"> 
        <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_username']) ?> <span class="required">*</span></label><br />
            <input type="text" name="message_name" id="message_name"  class="short" tabindex="1" value="<?php if(isset($_GET['u'])){ print strip_tags($_GET['u']); } ?>" /><br />
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages9']) ?></small> 
        </p> 
        <p class="f_half left"> 
            <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages10']) ?> <span class="required">*</span></label><br /> 
            <input type="text" name="message_subject" id="message_subject" class="short" tabindex="2" /><br /> 
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages11']) ?></small> 
        </p> 
        </div> 
        
        <div class="full clearfix border_t box"> 
        <p>
            <label for="comment"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages12']) ?> <span class="required">*</span></label><br /> 
            <textarea tabindex="4" class="long" rows="4" name="message_message" id="message_message"></textarea>           
        </p>
        </div>                            
        
        <div class="full clearfix border_t box"> 
        <p class="full clearfix"> 
            <input type="submit" name="submit" id="submit" class="button blue" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_messages13']) ?>" />  <input type="button" onClick="jQuery('#messageBox').hide()" class="button red" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_messages14']) ?>" /> 
        </p> 
        </div>	
    
    </fieldset> 
    </form> 
					 
</div>     
    
    
    
    
    
    
    
    
    
    
    
    
    
    
<div id="messageView" style="display:visible;">    
   
<input type="button" onClick="jQuery('#messageBox').show()" style="float:right;color:#333333" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_messages8']) ?>" /> 
      
      
<h1><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages1']) ?></h1> 

 <div class="full clearfix border_t">

<table id="hor-zebra">

    <thead>

    	<tr>

        	<th scope="col"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages2']) ?></th>

            <th scope="col"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages3']) ?></th>
            
            <th scope="col"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages4']) ?></th>

            <th scope="col"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages5']) ?></th>


        </tr>
    </thead>

    <tbody>
    
    <?php
	
	$i=1;
	$posts = query_posts('posts_per_page=100&post_type=ppt_message&meta_key=username&meta_value='.$userdata->user_login);
	foreach($posts as $post){  ?>
			

    	<tr <?php if($i%2){ ?>class="odd"<?php } ?>>

        	<td><a href="?mid=<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></a></td>

            <td><?php echo get_the_date(); ?></td>
            
            <td><?php the_author_meta('user_nicename',$post->post_author); ?></td>

            <td><a href="?mid=<?php echo $post->ID; ?>"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages6']) ?></a> | <a href="javascript:void(0);" onClick="document.getElementById('messageID').value='<?php echo $post->ID; ?>';messageDel2.submit();"><?php echo SPEC($GLOBALS['_LANG']['_tpl_messages7']) ?></a> </td>
           
        </tr>
        
     <?php $i++; } ?>   
        
    </tbody>
</table>

<form method="post" action="" id="messageDel2" name="messageDel2">
<input type="hidden" name="action" value="delete" />
<input type="hidden" name="messageID" id="messageID" value="" />
</form>

</div>

</div>
                    

     
        

</div>



 
<?php get_footer(); ?>