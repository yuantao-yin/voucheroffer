<?php
/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/
?> 
 
<div class="middleSidebar left">  

<?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?> 
 

<div id="step2box" style="display:visible;">
 
 
<script language="javascript" type="text/javascript">

		function CheckFormData()
		{
 
 		
			var title 	= document.getElementById("title1"); 
			var short 	= document.getElementById("short1");
			var descrip = document.getElementById("short2");
			var website = document.getElementById("website");
			var tag 	= document.getElementById("tags1"); 
			var email1 	= document.getElementById("email1");
			
			//shortCount = countWords(short.value);
			//descriptCount = countWords(descrip.value);
	 
			
			/*if(shortCount < 15)
			{
				//alert('Please add more content to your search description. You entered '+shortCount+' words, the minimum is 15 words.');
				//short.focus();
				//return false;
			}
			
			if(descriptCount < 30)
			{
				//alert('More content required for your main description. You entered '+descriptCount+' words, the minimum is 30 words..');
				//short.focus();
				//return false;
			}*/
						
			if(title.value == '')
			{
				alert('<?php echo SPEC($GLOBALS['_LANG']['_tpl_add43']) ?>');
				title.focus();
				return false;
			}
			if(short.value == '')
			{
				alert('<?php echo SPEC($GLOBALS['_LANG']['_tpl_add44']) ?>');
				short.focus();
				return false;
			}
 		

			if(tag.value == '')
			{
				alert('<?php echo SPEC($GLOBALS['_LANG']['_tpl_add45']) ?>');
				tag.focus();
				return false;
			} 
			
			if(email1.value == '')
			{
				alert('<?php echo SPEC($GLOBALS['_LANG']['_tpl_add46']) ?>');
				email1.focus();
				return false;
			} 			
			
			return true;
		}

 
</script>   

 
	 
	<script type="text/javascript" src="<?php echo PPT_PATH; ?>js/tiny_mce/tiny_mce.js"></script>
	<script type="text/javascript">
		tinyMCE.init({
			mode : "exact",
			elements : "short2",
			//theme : "simple",
			theme : "advanced",
			   height:"250",
				//width:"600",

			// Theme options
			theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,forecolor",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,| formatselect,fontselect,fontsizeselect, image, link",
			theme_advanced_buttons3 : "", 
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

		});
	</script>
 

 
        
<form action="" name="SUBMITFORM" id="SUBMITFORM" method="post" onsubmit="return CheckFormData();" > 
<input type="hidden" name="action" value="submit" />
<input type="hidden" name="htmlcode" value="1" />

<h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_article-add1']) ?></h3> 
<fieldset>  
                             
        <div class="full clearfix border_t box"> 
        
            <p class="f_half left"> 
                <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add10']) ?> <span class="required">*</span></label><br />
                <input type="text" name="form[title]" id="title1" class="short" tabindex="1" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['title']; }elseif(isset($data)){ echo $data['post_title']; } ?>" /><br />
                 
            </p> 
            <p class="f_half left"> 
                <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add11']) ?> <span class="required">*</span></label><br /> 
                <select name='CatSel[1]' class="short" tabindex="14"><?php echo $PPT->CategoryList($DefaultCat,false,$PACKAGE_OPTIONS[$_POST['packageID']]['pricecats'],"&taxonomy=article"); ?></select> <br />
                 
            </p> 
        
        </div> 
        
        <div class="full clearfix border_t box">         
        <p>
            <label for="comment"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add12']) ?><span class="required">*</span></label><br /> 
            <textarea tabindex="4" class="long" rows="4" name="form[short]" id="short1"><?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['short']; }elseif(isset($data)){ echo $data['post_excerpt']; } ?></textarea><br /> 
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add13']) ?></small> 
        </p>        
        </div>  
          
          
        <div class="full clearfix border_t box">         
        <p>
            <label for="comment"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add14']) ?><span class="required">*</span></label><br /> 
            <textarea tabindex="4" class="long" rows="4" name="form[description]" id="short2" style="height:300px;"><?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['description']; }elseif(isset($data)){ echo $data['post_content']; } ?></textarea><br /> 
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add15']) ?></small> 
        </p>        
        </div>  
          
         <div class="full clearfix border_t box"> 

        <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add16']) ?><span class="required">*</span></label><br />
            <input type="text" name="form[tags]"  class="short" tabindex="1" id="tags1" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['tags']; }elseif(isset($data)){ echo $tags; } ?>" />
        </p>
        
 
        <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add19']) ?><span class="required">*</span></label><br />
            <input type="text" name="form[email]"  class="short" tabindex="1" id="email1" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['email']; }elseif(isset($data)){ echo $data['email']; 
			}else{ print $userdata->user_email; } ?>" /><br />
             
        </p>
        
        </div>  
        
        
         <div class="full clearfix border_t box"> 

        <p class="f_half left"> 
        	<?php $email_nr1 = rand("0", "9");$email_nr2 = rand("0", "9"); ?>
            <label for="name"><?php echo SPEC(str_replace("%a",$email_nr1,str_replace("%b",$email_nr2,SPEC($GLOBALS['_LANG']['_single10'])))) ?><span class="required">*</span></label><br />
            <input type="text" name="code" value="" class="long" tabindex="1" /><br /> 
            <input type="hidden" name="code_value" value="<?php echo $email_nr1+$email_nr2; ?>" />
        </p>
        
        <p class="f_half left">
          <input type="submit" name="submit" id="submit" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_article-add2']); ?>" style="margin-top:15px;" />
        </p>  
        
      </div> 
          
        <div class="clearfix"></div>                         
        
       
    
</fieldset> 
</form>         
        
     
     
</div> 

</div>    

 

<?php get_footer(); ?>