<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

get_header(); ?>

<?php if(!isset($_POST['packageID']) && !isset($_GET['eid']) && get_option('pak_enabled') ==1 ){ $GLOBALS['nosidebar']=1; get_header();   include("_block_packages.php"); }else{ get_header();  ?>

<div class="middleSidebar left"> 

<?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?>
 
<div id="steptable" style="display:visible;">
 
		<table class="full"> 
			<tbody> 
				<tr> 
					<td class="w13"> 						
						<div class="steps <?php if(!isset($_POST['action'])){ ?>stepped<?php } ?> radius-left"> 
							<h4><?php echo SPEC($GLOBALS['_LANG']['_tpl_add1']) ?></h4> 
							<?php echo SPEC($GLOBALS['_LANG']['_tpl_add2']) ?>
						</div> 
						<?php if(!isset($_POST['action'])){ ?><div class="triangle"></div><?php } ?>
					</td> 
					<td class="w13"> 
						<div class="steps <?php if(isset($_POST['action']) && $_POST['action'] == "step1"){ ?>stepped<?php } ?>"> 
							<h4><?php echo SPEC($GLOBALS['_LANG']['_tpl_add3']) ?></h4> 
							<?php echo SPEC($GLOBALS['_LANG']['_tpl_add4']) ?>
						</div> 
                        <?php if(isset($_POST['action']) && $_POST['action'] == "step1"){ ?><div class="triangle"></div><?php } ?>
					</td> 
					<td class="w13"> 
						<div class="steps <?php if(isset($_POST['step3'])){ ?>stepped<?php } ?> radius-right"> 
							<h4><?php echo SPEC($GLOBALS['_LANG']['_tpl_add5']) ?></h4> 
							<?php echo SPEC($GLOBALS['_LANG']['_tpl_add6']) ?>
						</div> 
                        <?php if(isset($_POST['step3'])){ ?><div class="triangle"></div><?php } ?>
					</td> 
				</tr> 
			</tbody> 
		</table> 
        
</div> 
 
 

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

<?php if($PACKAGE_OPTIONS[$_POST['packageID']]['a1'] == 1){ ?>
	 
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
					   forced_root_block : false,
		   force_br_newlines : true,
		   force_p_newlines : false

		});
	</script>
<?php } ?>

 
        
<form action="" name="SUBMITFORM" id="SUBMITFORM" method="post" onsubmit="return CheckFormData();" > 
<input type="hidden" name="action" value="step1" />
<input type="hidden" name="packageID" value="<?php echo $_POST['packageID']; ?>" />
<input type="hidden" name="step1" value="1" />
<?php if(isset($_GET['eid']) && is_numeric($_GET['eid']) ){ ?><input type="hidden" name="eid" value="<?php echo $_GET['eid']; ?>" /><?php } ?>
<?php if($PACKAGE_OPTIONS[$_POST['packageID']]['a1'] == 1){ ?><input type="hidden" name="htmlcode" value="1" /><?php } ?>

<h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_add7']) ?></h3> 
<fieldset> 

<?php if(isset($data) && get_option('pak_enabled') ==1 ){ ?>
        <div class="full clearfix border_t box" id="packageBox"> <div style="background:#CBFFC8" class="padding clearfix">
        
         
                <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add8']) ?></label> 
                
                <select class="full" name="NEWpackageID">
                <?php echo $PPTDesign->PACKAGES($_POST['packageID'],true); ?>
                </select>
                
                <br /><br /><em><?php echo SPEC($GLOBALS['_LANG']['_tpl_add9']) ?> <?php echo strip_tags($PACKAGE_OPTIONS[$_POST['packageID']]['name']); ?></em> 
                   
        
        </div>
        
        </div>
 <?php } ?>       
                             
        <div class="full clearfix border_t box"> 
        
            <p class="f_half left"> 
                <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add10']) ?> <span class="required">*</span></label><br />
                <input type="text" name="form[title]" id="title1" class="short" tabindex="1" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['title']; }elseif(isset($data)){ echo $data['post_title']; } ?>" /><br />
                 
            </p> 
            <p class="f_half left"> 
                <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add11']) ?> <span class="required">*</span></label><br /> 
                <select name='CatSel[1]' class="short" tabindex="2" onChange="jQuery('#extracat1').show();"><?php echo $PPT->CategoryList($DefaultCat,false,$PACKAGE_OPTIONS[$_POST['packageID']]['pricecats']); ?></select> <br />
                
				<?php if($PACKAGE_OPTIONS[$_POST['packageID']]['a2'] ==1){ ?>
                
                    <span id="extracat1" style="display:<?php if(isset($data) && isset($data['cats'][1]->cat_ID) ){ echo "visible"; }else{ echo "none"; } ?>;">
                    <select name='CatSel[2]' class="short" <?php if(isset($data) && isset($data['cats'][2]->cat_ID) ){  }else{ ?>onChange="jQuery('#extracat2').show();"<?php } ?>><option value="0">----------</option>
                    <?php echo $PPT->CategoryList($DefaultCat1,false,$PACKAGE_OPTIONS[$_POST['packageID']]['pricecats']); ?></select>
                    </span>
                    <span id="extracat2" style="display:<?php if(isset($data) && isset($data['cats'][2]->cat_ID) ){ echo "visible"; }else{ echo "none"; } ?>;">
                    <select name='CatSel[3]' class="short"><option value="0">----------</option><?php echo $PPT->CategoryList($DefaultCat2,false,$PACKAGE_OPTIONS[$_POST['packageID']]['pricecats']); ?></select>
                    </span>
                
                <?php } ?>
                
            </p> 
        
        </div> 
        
        <div class="full clearfix border_t box">         
        <p>
            <label for="comment"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add12']) ?><span class="required">*</span></label><br /> 
            <textarea tabindex="3" class="long" rows="4" name="form[short]" id="short1"><?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['short']; }elseif(isset($data)){ echo $data['post_excerpt']; } ?></textarea><br /> 
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
            <input type="text" name="form[tags]"  class="short" tabindex="4" id="tags1" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['tags']; }elseif(isset($data)){ echo $tags; } ?>" /><br />
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add17']) ?></small> 
        </p>
        
		<?php if(get_option("display_country") =="yes"){  ?>
         
        <p class="f_half left">
         
        <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add18']) ?></label><br />
        <select name="form[country]" onChange="PremiumPressChangeState(this.value)" class="short" tabindex="6">
                <?php if(isset($data) && isset($data['country']) ){ ?><option value="<?php echo $data['country']; ?>" selected="selected"><?php echo $data['country']; ?></option><?php } ?>
                <?php if(file_exists(PPT_THEME_DIR. '/_countrylist.php')){ include(PPT_THEME_DIR. '/_countrylist.php' ); } ?>
        </select>  
        
        <?php  if(isset($data) && isset($data['state']) && strlen($data['state']) > 1){ ?><br /><label><?php echo $data['state'];  ?></label><?php } ?>
        </p>
         <div id="PremiumPressState"> </div><!-- AJAX STATE LIST -->  
                  
                  
           
        <?php } ?>
        
        </div> 
        
        <div class="full clearfix border_t box"> 
        
        <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add19']) ?><span class="required">*</span></label><br />
            <input type="text" name="form[email]"  class="short" tabindex="7" id="email1" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['email']; }elseif(isset($data)){ echo $data['email']; 
			}else{ print $userdata->user_email; } ?>" /><br />
             
        </p>
        
        
        <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add20']) ?></label><br />
            <input type="text" name="form[url]"  class="short" tabindex="8" id="url" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['url']; }elseif(isset($data)){ echo $data['url']; } ?>" /><br />
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add21']) ?></small> 
        </p>       
        
           
        </div>  
    
        
        
        <h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_add22']) ?></h3>
        
         <div class="full clearfix border_t box"> 


         <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add47']) ?> <span class="required">*</span></label><br />            
            <select name="form[type]" class="short" tabindex="9">
                <option value="coupon" <?php if(isset($data) && $data['type'] == "coupon" ){ echo "selected=selected"; } ?>><?php echo SPEC($GLOBALS['_LANG']['_tpl_add48']) ?></option>
                <option value="print" <?php if(isset($data) && $data['type'] == "print" ){ echo "selected=selected"; } ?>><?php echo SPEC($GLOBALS['_LANG']['_tpl_add49']) ?></option>
                <option value="offer" <?php if(isset($data) && $data['type'] == "offer" ){ echo "selected=selected"; } ?>><?php echo SPEC($GLOBALS['_LANG']['_tpl_add50']) ?></option>
                
        </select>            
        </p>       
      
        <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add51']) ?> <span class="required">*</span></label><br />
            <input type="text" name="form[code]"  class="short"  tabindex="10" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['code']; }elseif(isset($data)){ echo $data['code']; } ?>" /><br />
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add52']) ?></small> 
        </p>
        
        </div>
        
        <div class="full clearfix border_t box"> 
        
        
         <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add53']) ?> <span class="required">*</span></label><br />
            <input type="text" name="form[starts]" tabindex="11" class="short date-pick dp-applied"  id="start-date" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['starts']; }elseif(isset($data)){ 
			
			if(is_numeric(str_replace("-","",$data['starts']))){ echo $data['starts']; }
			
			 } ?>" /><br />
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add54']) ?></small> 
        </p> 
        
        
         <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add55']) ?> <span class="required">*</span></label><br />
            <input type="text" tabindex="12" name="form[expires]"  class="short date-pick dp-applied"  id="end-date" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['expires']; }elseif(isset($data)){ 
			if(is_numeric(str_replace("-","",$data['expires']))){ echo $data['expires']; }
			
			  } ?>" /><br />
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add56']) ?></small> 
        </p>  
  
        
        <?php if($PACKAGE_OPTIONS[$_POST['packageID']]['a4'] ==1){ ?>

        <p class="f_half left"> 
            <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add23']) ?></label><br />
            <input type="text" name="form[map_location]"  class="short" tabindex="13" value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['form']['map_location']; }elseif(isset($data)){ echo get_post_meta($_GET['eid'], 'map_location', true); } ?>" /><br />
            <small><?php echo SPEC($GLOBALS['_LANG']['_tpl_add24']) ?></small> 
        </p>    
     
        
            
       <?php } ?> 
       </div>
       
       <?php $PPTDesign->GL_customfields($data,$_POST['packageID']); ?> 
       
          
          
        <div class="clearfix"></div>                         
        
        <div class="full clearfix border_t box"> 
        <p class="full clearfix"> 
            <input type="submit" name="submit" id="submit" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_add30']) ?> >" />  
        </p> 
        </div>	
    
</fieldset> 
</form>         
        
     
     


</div>    














<!-- STEP 3 SECTION -->
<div id="step3box" style="display:none;">

 
<h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_add31']) ?></h3>

<div class="full clearfix border_t box"> 

	<script language="javascript" type="text/javascript"> function SaveDD(){ document.getElementById("description_hidden").value = document.getElementById("description_display").innerHTML; return true; } </script>

    <form action="#" name="SaveListing" method="post" enctype="multipart/form-data" onSubmit="return SaveDD()"> 
    <input type="hidden" name="step3" value="1" />
    <input type="hidden" name='CatSel[1]' value="<?php echo $_POST['CatSel'][1]; ?>" />
    <input type="hidden" name='CatSel[2]' value="<?php echo $_POST['CatSel'][2]; ?>" />
    <input type="hidden" name='CatSel[3]' value="<?php echo $_POST['CatSel'][3]; ?>" />
    <input type="hidden" name="action" value="<?php if(isset($_GET['eid']) && is_numeric($_GET['eid']) ){ ?>edit<?php }else{ ?>add<?php } ?>" />
    <?php if(isset($_POST['eid']) && is_numeric($_POST['eid']) ){ ?><input type="hidden" name="eid" value="<?php echo $_POST['eid']; ?>" /><?php } ?>
    <input type="hidden" name="packageID" value="<?php echo $_POST['packageID']; ?>" />
    <input type="hidden" name="NEWpackageID" value="<?php echo $_POST['NEWpackageID']; ?>" />
    <?php if($PACKAGE_OPTIONS[$_POST['packageID']]['a1'] == 1){ ?><input type="hidden" name="htmlcode" value="1" /><?php } ?>
    
    <?php $keysArray = array(
	
	'title' 		=> SPEC($GLOBALS['_LANG']['_tpl_add10']),
	'short' 		=> SPEC($GLOBALS['_LANG']['_tpl_add12']),
	'description' 	=> SPEC($GLOBALS['_LANG']['_tpl_add14']),
	'tags' 			=> SPEC($GLOBALS['_LANG']['_tpl_add16']),
	'country'		=> SPEC($GLOBALS['_LANG']['_tpl_add18']),
	'state' 		=> SPEC($GLOBALS['_LANG']['_tpl_add32']),
	'url' 			=> SPEC($GLOBALS['_LANG']['_tpl_add20']),	
	'email' 		=> SPEC($GLOBALS['_LANG']['_tpl_add19']),
	'type'			=> SPEC($GLOBALS['_LANG']['_tpl_add47']),
	'code'			=> SPEC($GLOBALS['_LANG']['_tpl_add51']),
	'starts'		=> SPEC($GLOBALS['_LANG']['_tpl_add53']),
	'expires'		=> SPEC($GLOBALS['_LANG']['_tpl_add55']),
	'map_location'	=> SPEC($GLOBALS['_LANG']['_tpl_add23']),
	
	);  ?>
    
    
    <?php if(!isset($_POST['form']['trackingID'])){ ?>	<input type="hidden" name="form[trackingID]" value="<?php echo $PPT->RandomID(7); ?>" /><?php } ?>
   
   
    <div class="full clearfix border_t box">   
      <p class="f1 left"><b><?php echo SPEC($GLOBALS['_LANG']['_tpl_add11']) ?></b></p>
	  <p class="f4 left"> <?php echo $PPT->CategoryFromID($_POST['CatSel']); ?></p>      
    </div>
        
    <?php if(is_array($_POST['form'])){ foreach($_POST['form'] as $key=>$value){ if($value != ""){ ?>
        
    <div class="full clearfix border_t box">  
      <div class="f1 left"><p><b><?php echo $keysArray[$key]; ?></b></p></div>
	  <div class="f4 left" id="<?php echo $key; ?>_display"><p><?php echo $PPTDesign->TPLADD_Value($key,$value); ?>&nbsp;</p></div>      
    </div>         
    
   <input type="hidden" name="form[<?php echo $key; ?>]" id="<?php echo $key; ?>_hidden" value="<?php echo strip_tags(PPTCLEAN($value,$key)); ?>" />
 
   <?php } } } ?>
   
   <?php $i = 0; if(is_array($_POST['custom'])){  foreach($_POST['custom'] as $cus){ if($cus['value'] != ""){ ?>
   
    <div class="full clearfix border_t box">  
      <div class="f1 left"><p> <b><?php echo $PPTDesign->GL_CustomKeyName($cus['name']); ?></b></p></div>
	  <div class="f4 left"><p><?php echo nl2br($cus['value']); ?>&nbsp;</p></div>      
    </div>   
  	<input type="hidden" name="custom[<?php echo $i; ?>][name]" value="<?php echo $cus['name']; ?>" />
    <input type="hidden" name="custom[<?php echo $i; ?>][value]" value="<?php echo PPTCLEAN($cus['value'],$cus['type']); ?>" />
   
   <?php }else{ echo '<input type="hidden" name="custom['.$i.'][name]" value="'.$cus['name'].'" /><input type="hidden" name="custom['.$i.'][value]" value="" />'; }  $i++; } }  ?>

 
    
    <br />
    
    <div class="clearfix"></div>

	<?php if(get_option("display_fileupload") =="yes" ){ $canShow=1; if(get_option('pak_enabled') ==1){  if($PACKAGE_OPTIONS[$_POST['packageID']]['a3'] ==1){ }else{ $canShow=0;}  }else{ $canShow=1; }
	
	
	if($canShow ==1){ ?>
    <div class="full clearfix border_t box">
    
    <?php if(!isset($_GET['eid']) || ( isset($_GET['eid']) && count(explode(",",get_post_meta($_POST['eid'], 'images', true))) < get_option('display_fileupload_max') ) ){ ?>    
    <h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_add33']) ?></h3>
    <p><?php echo SPEC($GLOBALS['_LANG']['_tpl_add34']) ?></p>
    <input class="wwIconified" type="file" name="d[]" /> 
    <?php } ?>
         
    </div>      
            
            <?php if(isset($_GET['eid'])){ ?>
            <h2><?php echo SPEC($GLOBALS['_LANG']['_tpl_add35']) ?></h2>
            <div class='PhotoSwitcher1 full clearfix box'><ul>
            <?php echo $PPT->editlistingimages($_GET['eid']);	 ?>
            </ul></div>	
            <div class="clearfix"></div> 
            <?php } ?> 
            
    <?php } } ?> 
    
    	
     	<input type="submit" style="float:right;color:#333333" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_add42']) ?>" />
        
       
		<input type="button" onClick="jQuery('#step2box').show();jQuery('#step3box').hide()" style="float:right;color:#333333" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_tpl_add41']) ?>" /> 
        
         

    </form>

</div>  
 
</div> 
<!-- end STEP 3 SECTION --> 












 
<!-- STEP 4 SECTION -->
<div id="step4box" style="display:none;">

<?php if(isset($_POST['step3'])){ if($newPrice > 0){ ?>

<h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_add36']) ?></h3>

<div class="full clearfix border_t box"> <br />

<p style="font-size:16px;"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add37']) ?> <?php echo $PPT->Price($newPrice,$GLOBALS['premiumpress']['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1); ?></p>
 
                <table cellpadding="0" cellspacing="0" class="select notoppadding" style="padding:0px; margin:0px; margin-top:20px; ">
                
                <?php 
                $i=1;
                if(is_array($gatway)){
                foreach($gatway as $Value){
                if(get_option($Value['function']) =="yes"){
				
				if( $Value['function'] == "gateway_bank"){ ?>
                <tr style="height:60px; background:#eee; border-bottom:1px solid #cccccc;">
                
                <td colspan="3" style="padding:10px;"> <b><?php echo get_option($Value['function']."_name"); ?></b> <br /><br /> <?php echo nl2br(get_option("bank_info")); ?> </td>
                
                </tr><tr><td colspan=3><hr /></td></tr>
                
                <?php  }elseif( $Value['function'] != "gateway_paypalpro" && $Value['function'] != "gateway_ewayAPI" && $Value['function'] !="gateway_blank_form" ){  ?>     
                
                <tr style="height:60px; background:#eee; border-bottom:1px solid #cccccc;">
                <td width="80"><?php if(strlen(get_option($Value['function']."_icon")) > 5){ echo "<img src='".get_option($Value['function']."_icon")."' />"; } ?></td>
                <td width="434">&nbsp;&nbsp;<b><?php echo get_option($Value['function']."_name"); ?></b></td>
                <td width="123"><?php echo $Value['function']($_POST); ?></td>
                </tr>
                <tr><td colspan="3"><hr /></td></tr>
                
                <?php 
    
                }else{
                echo "<tr><td colspan=3>";
                echo $Value['function']($_POST);
                echo "</td></tr><td colspan=3><hr /></td></tr>";		
                }
    
    
                } } }  ?>
                </table>
                
    <?php if($current_user->wp_user_level == "10"){  ?>
 
     <p style="padding:6px; color:white;background:red;"><b>Admin View Only</b> - <a href="#" onclick="document.AdminTest.submit();" style="color:white;">Click here to skip payment and test callback link.</a> </p>


    <form name="AdminTest" id="AdminTest" action="<?php echo $GLOBALS['bloginfo_url']; ?>/callback/" method="post">
    <input type="hidden" name="custom" value="<?php echo $_POST['orderid']; ?>">
    <input type="hidden" name="payment_status" value="Completed">
    <input type="hidden" name="mc_gross" value="<?php echo $newPrice; ?>" />
    </form> 


	<?php } }else{ ?>   
    
    <h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_add38']) ?></h3>
    
    <div class="full clearfix border_t box"> <br />
    
    <p style="font-size:16px;"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add39']) ?> <a href="<?php echo get_option("manage_url"); ?>"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add40']) ?></a></p>       
    
    <?php } ?>            

</div>

<?php }else{ ?>

<h3><?php echo SPEC($GLOBALS['_LANG']['_tpl_add38']) ?></h3>

<div class="full clearfix border_t box"> <br />

<p style="font-size:16px;"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add39']) ?> <a href="<?php echo get_option("manage_url"); ?>"><?php echo SPEC($GLOBALS['_LANG']['_tpl_add40']) ?></a></p>

  
</div>

<?php } ?>


 
</div> 
<!-- end STEP 4 SECTION -->        
        

</div>

 

<?php } ?>

<?php get_footer(); ?>