<?php 

$GLOBALS['stepbystep'] = 1;


include("admin-setup-test.php");
$pca = new PREMIUMPRESS_SETUP_TEST;


$hosting_info = array(

"1" => array(
	"tag" => "wp0",
	"name" => "Hosting Setup Tests",
	"desc" => "This will check your hosting setup to ensure it meets the theme requirments.",
	"notice" => "Please contact your <b>hosting provider</b> to ensure these issues are corrected, they are hosting setup problems and only the hosting provider can assist with these.",
	
		"tests" => array(
	
			"1" => array(
				"tname" => "PHP Version",
				"texpected" => "5",
				"tvalue" => "",
				"tfunc" => "test_php_version",	
				"tgood" => "PHP version looks good.",
				"tbad" => "Requires php 5.0.0+ for best results.",	
				),

			"2" => array(
				"tname" => "Running Safe Mode",
				"texpected" => "0", // 1 = ON 0 = Off
				"tvalue" => "safe_mode",
				"tfunc" => "text_php_directives",	
				"tgood" => "Great :)",
				"tbad" => "Safe mode MUST be disable for features to function correctly.",	
				),
				
			"3" => array(
				"tname" => "Register Globals",
				"texpected" => "0", // 1 = ON 0 = Off
				"tvalue" => "register_globals",
				"tfunc" => "text_php_directives",	
				"tgood" => "Spot On!",
				"tbad" => "Register globals should be turned off although this isnt 100% required its just a security feature.",	
				),
				
			"4" => array(
				"tname" => "Magic Quotes Runtime",
				"texpected" => "0", // 1 = ON 0 = Off
				"tvalue" => "magic_quotes_runtime",
				"tfunc" => "text_php_directives",	
				"tgood" => "Excellent",
				"tbad" => "Magic quotes should be turn off to ensure content is saved correctly.",	
				),
				
			"5" => array(
				"tname" => "Automatic Session Start",
				"texpected" => "0", // 1 = ON 0 = Off
				"tvalue" => "session.auto_start",
				"tfunc" => "text_php_directives",	
				"tgood" => "Bingo!",
				"tbad" => "This should be turned off as Wordpress already starts sessions manually.",	
				),
				
			"6" => array(
				"tname" => "File Uploading",
				"texpected" => "1", // 1 = ON 0 = Off
				"tvalue" => "file_uploads",
				"tfunc" => "text_php_directives",	
				"tgood" => "Phew..got me worried there :)",
				"tbad" => "You need this turned on otherwise its not possible to upload content to your website.",	
				),
				
			/*"7" => array(
				"tname" => "Display PHP Errors",
				"texpected" => "0", // 1 = ON 0 = Off
				"tvalue" => "display_errors",
				"tfunc" => "text_php_directives",	
				"tgood" => "Nice Work.",
				"tbad" => "This should be tuned off to prevent your visitors seeing any warning or error notices with Wordpress plugins and website addons.",	
				),*/
 	
			"8" => array(
				"tname" => "Short Open Tags",
				"texpected" => "1", // 1 = ON 0 = Off
				"tvalue" => "short_open_tag",
				"tfunc" => "text_php_directives",	
				"tgood" => "Very good",
				"tbad" => "This should be turn on HOWEVER its not required, its best to be turned on otherwise other PHP scripts/plugins may not work.",	
				),
				
				
				
			"9" => array(
				"tname" => "Maximum Upload File Size",
				"texpected" => "20", // 1 = ON 0 = Off
				"tvalue" => "upload_max_filesize",
				"tfunc" => "text_php_directives_higher",	
				"tgood" => "Nice One!",
				"tbad" => "It is <b>recommended</b> to have 20M of upoad transfer data for members to upload bigger photos etc. (upload_max_filesize)",	
				),

/*
			"10" => array(
				"tname" => "Max Simultaneous Uploads",
				"texpected" => "12", // 1 = ON 0 = Off
				"tvalue" => "max_file_uploads",
				"tfunc" => "text_php_directives_higher",	
				"tgood" => "",
				"tbad" => "",	
				),

			"11" => array(
				"tname" => "Floating Point Precision",
				"texpected" => "10", // 1 = ON 0 = Off
				"tvalue" => "precision",
				"tfunc" => "text_php_directives_higher",	
				"tgood" => "",
				"tbad" => "",	
				),*/

			"12" => array(
				"tname" => "Memory Capacity Limit",
				"texpected" => "32", // 1 = ON 0 = Off
				"tvalue" => "memory_limit",
				"tfunc" => "text_php_directives_higher",	
				"tgood" => "Anything above 32MB is required.",
				"tbad" => "It is <b>recommended</b> to have 32M of memory otherwise your website might fall over! (memory_limit)",	
				),				
				
			"13" => array(
				"tname" => "POST Form Maximum Size",
				"texpected" => "20", // 1 = ON 0 = Off
				"tvalue" => "post_max_size",
				"tfunc" => "text_php_directives_higher",	
				"tgood" => "Woohooo!",
				"tbad" => "It is <b>recommended</b> to have 20M of POST data transfer for form data to be saved correctly. (post_max_size)",	
				),	
								
		
		),
	
),	
 
	
);



$theme_info = array(

"0" => array(
	"tag" => "as1",
	"name" => "Basic Theme Setup",
	"desc" => "Here we will test to ensure your folder paths are setup correctly.",
	"notice" => "Please go through the options above and ensure they are all setup correctly.",	
	
		"tests" => array(
	
			"1" => array(
				"tname" => "Child Theme",
				"texpected" => "",
				"tvalue" => "theme",
				"tfunc" => "test_info",	
				"tgood" => " You're using the child theme: <b>".get_option("theme")."</b>",
				"tbad" => " ",	
				),
				
 
 			"2" => array(
				"tname" => "Welcome Email",
				"texpected" => "",
				"tvalue" => "email_signup",
				"tfunc" => "test_blank",	
				"tgood" => "<b><a href='admin.php?page=emails'>Click here to edit this email.</a></b> ",
				"tbad" => "<b><a href='admin.php?page=emails'>Click here to edit this email.</a></b> ",	
				),
					

		
		),
	
),	

"1" => array(
	"tag" => "ap2",
	"name" => "Folder Paths",
	"desc" => "Here we will test to ensure your folder paths are setup correctly.",
	"notice" => "Please go through the options above and ensure they are all setup correctly.",	
	
		"tests" => array(
	
			"1" => array(
				"tname" => "Image Storage Path",
				"texpected" => "",
				"tvalue" => get_option('imagestorage_path'),
				"tfunc" => "test_is_writable",	
				"tgood" => "Great, looks good.<br><small>".get_option('imagestorage_path')."</small>",
				"tbad" => "This paths needs to be CHMOD 777 otherwise images and member uploads will not save correctly.",	
				),
			"2" => array(
				"tname" => "Image Cache Folder",
				"texpected" => "",
				"tvalue" => get_option('imagestorage_path')."cache/",
				"tfunc" => "test_is_writable",	
				"tgood" => "Great, looks good.<br><small>".get_option('imagestorage_path')."cache/</small>",
				"tbad" => "This paths needs to be CHMOD 777 otherwise images and member uploads will not save correctly.",	
				),
				
			"3" => array(
				"tname" => "Link to Image Storage",
				"texpected" => "",
				"tvalue" => get_option('imagestorage_link'),
				"tfunc" => "test_is_link",	
				"tgood" => "Great, looks good.<br><small>".get_option('imagestorage_link')."</small>",
				"tbad" => "This paths needs to be CHMOD 777 otherwise images and member uploads will not save correctly.",	
				), 
		
		),
		



		
	
),	

 
"2" => array(
	"tag" => "as1sad",
	"name" => "Page Setup",
	"desc" => "Here we will test to ensure you have setup all the necessary theme pages.",
	"notice" => "Please go through the options above and ensure they are all setup correctly.",	
	
		"tests" => array(
	
			"0" => array(
				"tname" => "Add Page",
				"texpected" => "",
				"tvalue" => "tpl-add.php",
				"tfunc" => "test_page",	
				"tgood" => " Great, please check the button link is correct:<br><br> Button linked is<br><small>".get_option("submit_url")."</small><b><br><br><a href='admin.php?page=setup&tab=3'>Click here to edit the page link.</a></b>",
				"tbad" => "You have not setup a page with the page template  <b><a href='post-new.php?post_type=page'>Click here to do it now</a></b>",	
				),
				
 			"1" => array(
				"tname" => "My Account Page",
				"texpected" => "",
				"tvalue" => "tpl-myaccount.php",
				"tfunc" => "test_page",	
				"tgood" => " Great, please check the button link is correct:<br><br> Button linked is<br><small>".get_option("dashboard_url")."</small><b><br><br><a href='admin.php?page=setup&tab=3'>Click here to edit the page link.</a></b>",
				"tbad" => "You have not setup a page with the page template  <b><a href='post-new.php?post_type=page'>Click here to do it now</a></b>",	
				),
 	
 			"2" => array(
				"tname" => "Contact Page",
				"texpected" => "",
				"tvalue" => "tpl-contact.php",
				"tfunc" => "test_page",	
				"tgood" => " Great, please check the button link is correct:<br><br> Button linked is<br><small>".get_option("contact_url")."</small><b><br><br><a href='admin.php?page=setup&tab=3'>Click here to edit the page link.</a></b>",
				"tbad" => "You have not setup a page with the page template  <b><a href='post-new.php?post_type=page'>Click here to do it now</a></b>",	
				),
				
 			"3" => array(
				"tname" => "Manage Page",
				"texpected" => "",
				"tvalue" => "tpl-edit.php",
				"tfunc" => "test_page",	
				"tgood" => " Great, please check the button link is correct:<br><br> Button linked is<br><small>".get_option("manage_url")."</small><b><br><br><a href='admin.php?page=setup&tab=3'>Click here to edit the page link.</a></b>",
				"tbad" => "You have not setup a page with the page template  <b><a href='post-new.php?post_type=page'>Click here to do it now</a></b>",	
				),
				
 			"4" => array(
				"tname" => "Messages Page",
				"texpected" => "",
				"tvalue" => "tpl-messages.php",
				"tfunc" => "test_page",	
				"tgood" => " Great, please check the button link is correct:<br><br> Button linked is<br><small>".get_option("messages_url")."</small><b><br><br><a href='admin.php?page=setup&tab=3'>Click here to edit the page link.</a></b>",
				"tbad" => "You have not setup a page with the page template  <b><a href='post-new.php?post_type=page'>Click here to do it now</a></b>",	
				),
		
		),
	
),	
 
	
);



$wordpress_info = array(

"1" => array(
	"tag" => "wp1",
	"name" => "Wordpress Setup",
	"desc" => "Here we will check basic information about your Wordpress setup",
	"notice" => "Please go through the options above and ensure they are all setup correctly.",	
	
	"tests" => array(
 
			"0" => array(
				"tname" => "Wordpress Version",
				"texpected" => "3.0",
				"tvalue" => "version",
				"tfunc" => "test_blog_data",	
				"tgood" => "This theme will work with ALL Wordpress versions 2.8 and above, your current version looks fine.",
				"tbad" => "Requires Wordpress version 3.0 or higher.",	
				),					
			"1" => array(
				"tname" => "Blog Title",
				"texpected" => "",
				"tvalue" => "name",
				"tfunc" => "test_blog_data",	
				"tgood" => "Looks good, <b><a href='options-general.php'>click here to edit</a></b>",
				"tbad" => "Value is missing or incomplete. <b><a href='options-general.php'>Click here to edit</a></b>",	
				),				
			"2" => array(
				"tname" => "Admin Email",
				"texpected" => "",
				"tvalue" => "admin_email",
				"tfunc" => "test_blog_data",	
				"tgood" => "Looks good, <b><a href='options-general.php'>click here to edit</a></b>",
				"tbad" => "Value is missing or incomplete. <b><a href='options-general.php'>Click here to edit</a></b>",	
				),	
				
			"3" => array(
				"tname" => "Template Install Path",
				"texpected" => "",
				"tvalue" => "",
				"tfunc" => "test_install_path",	
				"tgood" => "",
				"tbad" => "Your theme folder name doesnt look to be installed correctly, it should be: http://example/home/wp/wp-content/".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/ but you have ".get_bloginfo( "template_url" )."/",	
				),	
				
			"4" => array(
				"tname" => "Permalink Setup",
				"texpected" => "/%postname%/",
				"tvalue" => "permalink_structure",
				"tfunc" => "test_option",	
				"tgood" => "Looks good, <b><a href='options-permalink.php'>click here to edit</a></b>",
				"tbad" => "The recommended permalink structure for our themes is a custom one: /%postname%/. <b><a href='options-permalink.php'>click here to edit</a></b>",	
				),
				
				
			"5" => array(
				"tname" => "User Registration",
				"texpected" => "1",
				"tvalue" => "users_can_register",
				"tfunc" => "test_option",	
				"tgood" => "",
				"tbad" => "You need to turn user registration on to allow members to signup.",	
				), 
				
				
						
	),
),		
		
"2" => array(
	"tag" => "wpd1",
	"name" => "Wordpress Plugins",
	"desc" => "Here we will test to ensure the necessary plugins are installed.",
	"notice" => "You can download all the recommened plugins from our <b><a href='http://www.premiumpress.com/plugins/' target='_blank'>website here.</a></b>",	
	"tests" => array(
 
			"0" => array(
				"tname" => "User Photo Plugin",
				"texpected" => "",
				"tvalue" => "userphoto_exists",
				"tfunc" => "test_plugin_exists",	
				"tgood" => "Great",
				"tbad" => "This plugin will allow members to add a profile picture, its not 100% required but recommended.",	
				),
				
			"1" => array(
				"tname" => "Star Rating Plugin",
				"texpected" => "",
				"tvalue" => "wp_gdsr_render_article",
				"tfunc" => "test_plugin_exists",	
				"tgood" => "Great",
				"tbad" => "This plugin will allow members to rate website items, its not 100% required but is recommended.",	
				),			 
				
		),
				
	
),	
 
	
);
 
PremiumPress_Header(); 
?>
 
	<script type="text/javascript" src="<?php echo $GLOBALS['template_url'];  ?>/PPT/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="<?php echo $GLOBALS['template_url'];  ?>/PPT/js/jquery.stepbystep.js"></script>
		<script type="text/javascript">
 
			jQuery(document).ready(function(){
				//example 1
				jQuery("#processPanel").processPanel();
				//example 2
				var timesOpened = 0;
				jQuery("#processPanel2").processPanel({
					kind: "freeChoice",
					icons: false,
					nextPrevButtons: false,
					style: "orange-gray",
					onOpen: function(event, step, content, stepNumber){
						jQuery(".message").remove();
						if(stepNumber==2)
						{
							timesOpened++;
							content.prepend("<div class='message'>You've opened this step " + timesOpened + " time" + (timesOpened>1 ? "s":"") + ".</div>");
						}
					}
				});
				 
				 
			});
		</script>
        
        <div class="bar">
				<b>Welcome!</b> Here we will help you test your website setup for basic installation problems. 
		</div>
            
		

<div class="panel" id="processPanel">

<a href="#content1" label="1">Hosting Check</a>
<a href="#content2" label="2">Wordpress Check</a>
<a href="#content3" label="3">Theme Setup Check</a>
                
<div class="clearfix"></div> <br />
<div id="content1"><?php echo $pca->BuildTable($hosting_info); ?> </div>				
<div id="content2"><?php echo $pca->BuildTable($wordpress_info); ?></div>
<div id="content3"><?php echo $pca->BuildTable($theme_info); ?></div>

</div>
