<?php
 /*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/
 

/**
* Info: creates error display for customer pages
* 		
* @version  1.0
*/

function cp_show_errors($wp_error) {
	global $error,$PPTDesign;
	
	if ( !empty( $error ) ) {
		$wp_error->add('error', $error);
		unset($error);
	}

	if ( !empty($wp_error) ) {
		if ( $wp_error->get_error_code() ) {
			$errors = '';
			$messages = '';
			
			foreach ( $wp_error->get_error_codes() as $code ) {			
			
				$severity = $wp_error->get_error_data($code);
				foreach ( $wp_error->get_error_messages($code) as $error ) {
					if ( 'message' == $severity )
						$messages .= '	' . $error . "<br />\n";
					else
						$errors .= '	' . $error . "<br />\n";
				}
			}
			if ( !empty($errors) )
				echo $PPTDesign->GL_ALERT( $errors ,"error");
			if ( !empty($messages) )			
				echo $PPTDesign->GL_ALERT( $messages ,"success"); //echo '<p>' . apply_filters('login_messages', $messages) . "</p>\n";
		}
	}
}


 
function cp_head($cp_msg) {

	global $pagenow, $user_ID, $cp_options;

	include(TEMPLATEPATH . '/header.php');	 
 
}
 
function cp_footer() {

	global $pagenow, $user_ID, $cp_options;
 
	include(TEMPLATEPATH . '/footer.php');
}


function cp_show_login() {

	if ( isset( $_REQUEST['redirect_to'] ) )
		$redirect_to = $_REQUEST['redirect_to'];
	else
		$redirect_to = admin_url();

	if ( is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;
	else
		$secure_cookie = '';

	$user = wp_signon('', $secure_cookie);
	if($redirect_to == ""){ $redirect_to = admin_url(); }
	$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);

	if ( !is_wp_error($user) ) {
	
		if($user->yim['user_status'] == "suspended"){
		wp_logout();
		die("<h1>Account Suspended</h1><p>We are sorry but your account has been suspended.</p>");
		}elseif($user->yim['user_status'] == "fired"){	
		wp_logout();
		die("<h1>You're Fired!!</h1><p>We are sorry but your account has been terminated and you are now fired.</p>");		
		}
	
		 if($user->user_level > 2){
			$redirect_to = admin_url();
		 }else{
			$redirect_to = get_option("dashboard_url");
			 if($redirect_to == ""){ $redirect_to = $GLOBALS['bloginfo_url']; }
		 }
		header("location: ".$redirect_to);
		 exit();	
	}

	$errors = $user;
	// Clear errors if loggedout is set.
	if ( !empty($_GET['loggedout']) )
		$errors = new WP_Error();

	cp_head(__('Login','cp'));	

	// If cookies are disabled we can't log in even with a valid user+pass
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
		$errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a>.",'cp'));		
	if	( isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] )			$errors->add('loggedout', __(SPEC($GLOBALS['_LANG']['_zz7']),'cp'), 'message');
	elseif	( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )	$errors->add('registerdisabled', __(SPEC($GLOBALS['_LANG']['_zz8']),'cp'));
	elseif	( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )	$errors->add('confirm', __(SPEC($GLOBALS['_LANG']['_zz9']),'cp'), 'message');
	elseif	( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )	$errors->add('newpass', __(SPEC($GLOBALS['_LANG']['_zz10']),'cp'), 'message');
	elseif	( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )	$errors->add('registered', __(SPEC($GLOBALS['_LANG']['_zz11']),'cp'), 'message');

  ?>

 <div class="middleSidebar left">  

<?php echo cp_show_errors($errors); ?>

<h1><?php echo SPEC($GLOBALS['_LANG']['_customlogin1']) ?></h1>
    
    
 <fieldset style="background:transparent;"> 
<form class="loginform" action="<?php bloginfo('wpurl'); ?>/wp-login.php" method="post" > 
<input type="hidden" name="testcookie" value="1" /> 
<input type="hidden" name="rememberme" id="rememberme" value="forever" />  

<legend><?php echo SPEC($GLOBALS['_LANG']['_customlogin2']) ?> <a href="wp-login.php?action=register" style="color:blue;"><?php echo SPEC($GLOBALS['_LANG']['_customlogin3']) ?></a></legend> 


<div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_username']); ?>:</label><br /> 
    <input type="text" name="log" value="<?php echo esc_attr(stripslashes($_POST['log'])); ?>" id="user_login" class="short" tabindex="10" /><br /> 
</p> 
<p class="f_half left"> 
    <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_password']); ?>:</label><br /> 
    <input type="password"name="pwd" id="user_pass" class="short" tabindex="11" /><br /> 
</p> 
</div> 

<?php do_action('login_form'); ?>

<div class="full clearfix border_t box"><p class="full clearfix"> 
<input type="submit" name="submit" id="submit" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_customlogin4']) ?>" /> 
</p></div> 


</form> 
<div class="full clearfix border_t box"><p class="full clearfix"> 
<a href="wp-login.php?action=lostpassword" style="color:blue;"><?php echo SPEC($GLOBALS['_LANG']['_customlogin5']) ?></a> 
</p></div> 
</fieldset>


    
    
</div>			
	         
        
 <?php  	cp_footer();
}


function cp_show_register() {

	global $cp_pluginpath, $cp_options;
	if ( !get_option('users_can_register') ) {
		wp_redirect(get_bloginfo('wpurl').'/wp-login.php?registration=disabled');
		exit();
	}

	$user_login = '';
	$user_email = '';
   
	if ( isset($_POST['user_login']) ) {
		if( !$cp_options['captcha'] || ( $cp_options['captcha'] && ($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code']) ) ) 
			) {
			unset($_SESSION['security_code']);
			require_once( ABSPATH . WPINC . '/registration.php');

			$user_login = $_POST['user_login'];
			$user_email = $_POST['user_email'];
			$errors = register_new_user($user_login, $user_email);
			
			if ( !is_wp_error($errors) ) {
			
			
				// 1. SEND WELCOME EMAIL
				$emailID = get_option("email_signup");					 
				if(is_numeric($emailID) && $emailID != 0){
					SendMemberEmail($errors, $emailID);
				}			
			
				wp_redirect('wp-login.php?checkemail=registered');
				exit();
				
			}
		} else {
			$user_login = $_POST['user_login'];
			$user_email = $_POST['user_email'];
			$errors = new WP_error();
			$errors->add('captcha', __("<strong>ERROR</strong>: You didn't correctly enter the captcha, please try again.",'cp'));		
		}
	}
	
	cp_head(__('Register','cp'));
	
    ?>
 


<div class="middleSidebar left"> 

<?php  echo cp_show_errors($errors); ?>

<h1><?php echo SPEC($GLOBALS['_LANG']['_customlogin6']) ?></h1> 


<form class="loginform" name="registerform" id="registerform" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" method="post">  
<fieldset style="background:transparent;"> 
<legend><?php echo SPEC($GLOBALS['_LANG']['_customlogin7']) ?></legend> 


<div class="full clearfix border_t box"> 
<p class="f_half left"> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_username']); ?>:</label><br /> 
    <input type="text" name="user_login" value="<?php echo esc_attr(stripslashes($user_login)); ?>" id="user_login" class="short" /><br /> 
</p> 
<p class="f_half left"> 
    <label for="email"><?php echo SPEC($GLOBALS['_LANG']['_email']); ?>:</label><br /> 
    <input type="text" name="user_email" id="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>"  class="short" /><br /> 
</p> 
</div>

<?php do_action('register_form'); ?> 

<div class="full clearfix border_t box"><p class="full clearfix"> 
<input type="submit" name="submit" id="submit" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_customlogin4']) ?>" /> 
</p></div> 

	
</fieldset>
</form> 

 

</div>

 
    
    
 
<?php   cp_footer();
}



function cp_password() {
	
	
	if ( $_POST['user_login'] ) {
 
		$errors = new WP_Error();
		$errors = retrieve_password();
		if ( !is_wp_error($errors) ) {
			wp_redirect('wp-login.php?checkemail=confirm');
			exit();
		}
		
		if ( 'invalidkey' == $_GET['error'] ) 
		$errors->add('invalidkey', __(SPEC($GLOBALS['_LANG']['_zz6']),'cp'));

		$errors->add('registermsg', __(SPEC($GLOBALS['_LANG']['_zz5']),'cp'), 'message');	
		
		do_action('lostpassword_post');
	}

	cp_head("Lost Password");
  ?>

 
<div class="middleSidebar left">  
 
 

<?php  echo cp_show_errors($errors);   ?>

<h1><?php echo SPEC($GLOBALS['_LANG']['_lostpassword']); ?></h1>    
    
<form class="loginform" name="lostpasswordform" id="lostpasswordform" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" method="post">


<fieldset style="background:transparent;"> 

<legend><?php echo SPEC($GLOBALS['_LANG']['_customlogin8']) ?></legend> 

<div class="full clearfix border_t box"> 
<p> 
    <label for="name"><?php echo SPEC($GLOBALS['_LANG']['_username']); ?>:</label><br /> 
    <input type="text"  name="user_login" id="user_login" value="<?php echo esc_attr(stripslashes($_POST['user_login'])); ?>"/><br /> 
   
</p> 
 
</div> 

<?php do_action('lostpassword_form'); ?>

<div class="full clearfix border_t box"><p class="full clearfix"> 
<input type="submit" name="submit" id="submit" class="button grey" tabindex="15" value="<?php echo SPEC($GLOBALS['_LANG']['_customlogin9']) ?>" /> 
</p></div> 

</fieldset>
</form>  
		
  
		
 </div> 
 
 
 
 
 
 
<?php cp_footer(); } ?>