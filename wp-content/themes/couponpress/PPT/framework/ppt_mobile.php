<?php

class PPT_MOBILE extends PPT_API {

var $page;

var $pageTitle;


function start(){

	if(isset($_POST['action'])){
	
			$message = "<p> Name : " . strip_tags($_POST['form']['name']) . "
						<p> Email : " . strip_tags($_POST['form']['email']) . "
						<p> Message : " . strip_tags($_POST['form']['message']) . "";
						
			// SEND EMAIL			 
			wp_mail(get_option("admin_email"),"New Message (mobile form)",stripslashes($message));					 
			 
			 
			
			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "success"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= "Message Sent Successfully";
	}

	if(isset($_GET['show'])){ $this->page = $_GET['show']; } 
	
	$this->_pageTitle();
	echo $this->_header();
	echo $this->_body();
	echo $this->_footer();

}

function _check(){

        $agent = $_SERVER["HTTP_USER_AGENT"]; 
		   
        $mobile = false;
        $agents = array("Alcatel", "Blackberry", "HTC", "HP", "LG", "Motorola", "Nokia", "Palm", "Samsung", "SonyEricsson", "ZTE", "Mobile", "iPhone", "iPod", "Mini", "Playstation", "DoCoMo", "Benq", "Vodafone", "Sharp", "Kindle", "Nexus", "Windows Phone");
        foreach($agents as $a){
		 
            if(stripos($agent, $a) !== false){                 
                return $a;
            }
        }
        return $mobile;
}

function _pageTitle(){

	switch($this->page){
	
		case "search": { $t = "Search"; } break;
		
		case "cats": { $t = "Categories"; } break;
		
		case "new": { $t = "New Listings"; } break;		 
		
		case "pages": { $t = "Pages"; } break;

		case "contact": { $t = "Contact"; } break;
				
		default: {$t = ""; }
	
	}
	
	if(isset($_GET['extra']) && $_GET['extra'] == "articles"){
	$t = "Articles"; 
	}
	
	$this->pageTitle = $t;

}

function _header(){

global $PPT;
	
	$GLOBALS['premiumpress']['theme'] 				= get_option('theme');


	$STRING = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale = 1.0" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>'.$this->pageTitle.'</title>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="'.PPT_THEME_URI.'/PPT/css/css.pagination.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="'.PPT_THEME_URI.'/PPT/css/css.paginationD.css" media="screen" />
	<style>.menu{color:#FFFFFF; background:url('.PPT_FW_IMG_URI.'mobile/header_bg.png) repeat-x top;} a { color:#8e0000; } </style>
	<link rel="stylesheet" type="text/css" href="'.PPT_CUSTOM_CHILD_URL.'css/styles.css" media="screen" />';
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "moviepress"){
	$STRING .= '<script src="'.PPT_CHILD_JS.'swfobject.js" type="text/javascript"></script>';
	}
	
	$STRING .= '<style>'.$this->_css().'</style>
	<script type="text/javascript"> 
	var $ = jQuery.noConflict();
	$(function() {
		$(\'#activator\').click(function(){
				$(\'#box\').animate({\'top\':\'65px\'},500);
		});
		$(\'#boxclose\').click(function(){
				$(\'#box\').animate({\'top\':\'-400px\'},500);
		});
		$(\'#activator_options\').click(function(){
				$(\'#box_share\').animate({\'top\':\'65px\'},500);
		});
		$(\'#boxclose_options\').click(function(){
				$(\'#box_share\').animate({\'top\':\'-400px\'},500);
		});
 
	}); 
	</script> 
	<script type="text/javascript" src="'.PPT_THEME_URI.'/PPT/img/mobile/photoslide.js"></script> 
	</head>
	
	<body onload="setTimeout(function() { window.scrollTo(0, 1) }, 100);" />
	
	
	        <div class="box" id="box_share"> 
        	<div class="box_content"> 
            	<div class="box_content_tab"> 
                Quick Links
                </div> 
                <div class="box_content_center"> 
                
           			<div class="toggle_container">
                    <ul>
                        <li><a href="#">Archive one</a></li>
                        
                    </ul>
            		</div>
            
                <a class="boxclose_right" id="boxclose_options">close</a> 
                <div class="clear"></div> 
                
                </div> 
           </div> 
        </div> 
		
	
	        <div class="box" id="box"> 
        	<div class="box_content"> 
            
            	<div class="box_content_tab"> 
                Search
                </div> 
                
                <div class="box_content_center"> 
                <div class="form_content"> 
                <form method="get"  action="'.HOME_URI.'" name="searchBox" id="searchBox">
      			<input name="show" value="search" type="hidden">
			    <input type="text" value="Enter search term.." name="s" id="s" onfocus="this.value=\'\';" class="form_input_box"/>      
			    <select class="form_input_select" name="cat"><option value="">---------</option>'.$PPT->CategoryList().'</select>  
   
        
                <a class="boxclose" id="boxclose">Close</a> 
                <input type="submit" class="form_submit" value="Submit" /> 
                </form> 
                </div> 
                
                <div class="clear"></div> 
                </div> 
            
           </div> 
        </div>
		
		
		

		
		
	';
	
	return $STRING;

}

function _footer(){

	$STRING ="";

	if(isset($_GET['show'])){
	 
		/*$STRING = '<div id="footer">	 
		<span></span>
		<a onclick="jQuery(\'html, body\').animate( { scrollTop: 0 }, \'slow\' );"  href="javascript:void(0);" title="Go on top" class="right_bt"><img src="'.PPT_FW_IMG_URI.'mobile/top.png" alt="" title="" border="0" /></a>
		</div>';*/
	
	}
	
	
	$STRING .= '</body>
	</html>';
	
	return $STRING;
}

function _body(){

	global $PPT, $PPTDesign, $PPTFunction, $ThemeDesign, $current_user; get_currentuserinfo();	
	
	$GLOBALS['premiumpress']['thumbresize'] = get_option('thumbresize');
	if($GLOBALS['premiumpress']['thumbresize'] == "on"){
	$imgExtra = "&amp;w=50&amp;h=50";
	}else{
	$imgExtra = "";
	}
	//
	$HEADING = '<div class="menu">
	 
	<a href="'.HOME_URI.'" class="left_bt1"><img src="'.PPT_FW_IMG_URI.'mobile/home.png" alt="'.$this->pageTitle.'" border="0" /></a>	
	<span>'.$this->pageTitle.'</span>			
	<a href="#" class="right_bt" id="activator"><img src="'.PPT_FW_IMG_URI.'mobile/search.png" alt="'.$this->pageTitle.'" border="0" /></a>	
	<!--<a href="#" class="right_bt" id="activator_options"><img src="'.PPT_FW_IMG_URI.'mobile/options.png" alt="'.$this->pageTitle.'" border="0" /></a>-->			
	</div><div class="content">';


	$STRING = '<div id="main_container">';
	
	
	
	switch($_GET['show']){
	
		case "search":{
		
			$STRING .= $HEADING; $extra="";
			
			$query_string 	= $PPT->BuildSearchString($query_string);	
			
			if(isset($_GET['extra'])){
			
				switch($_GET['extra']){
				
					case "featured": 	{ $extra="&meta_key=featured&meta_value=yes"; } break;
					case "new": 		{ $extra="&orderby=modified&order=desc"; } break;
					case "articles": 	{ $extra="&post_type=article_type&orderby=modified&order=desc"; $query_string = str_replace("meta_key=","a=", str_replace("orderby=meta_value","b=", $query_string)); } break;
				}			
			}			
			
			// Load Category Data
			if(isset($_GET['catID'])){  $extra .= "&cat=".$_GET['catID']; $thisCat = get_category($_GET['catID'],false);  $this->pageTitle = $thisCat->name; }
			
			// Load the title
			if(isset($_GET['s'])){  $STRING .= "<h2 class='cattitle'>Search: ".strip_tags($_GET['s'])."</h2>";   
			}elseif(isset($_GET['extra']) && $_GET['extra'] == "articles"){ $STRING .= "<h2 class='cattitle'>Latest Articles</h2>"; 
			}elseif(isset($thisCat->name)){ $STRING .= '<h2 class="cattitle"><a href="'.HOME_URI.'?show=cats">Categories</a> &gt; '.$thisCat->name.'</h2>'; }
			
			
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$a  = query_posts($query_string.'numberposts=25&post_type=post&paged='.$paged.$extra); //&order=DESC&orderby=date&showposts=8
			
			// GET A COUNTER FOR ALL
			//$allsearch = &new WP_Query($query_string."&showposts=-1");
		 
			if(count($a) > 0){
			
				foreach ($a as $post){			
				
				$STRING .= '<div class="corner_wrap">
				<div class="entry">
				
				<div style="float:left; width:27%">
				<img src="'.$PPT->Image($post->ID,"url").$imgExtra.'" width="50" height="50" class="left_pic"/> 
				</div>
				
				<div style="float:left; width:72%;">				
					<h2><a href="?show=single&id='.$post->ID.'">'.$post->post_title.'</a></h2>
					<div class="subtext">'.$this->_subtext($post, 'search').'</div>
					<p class="excerpt">'.substr(strip_tags($post->post_excerpt),0,100).'</p>										 
				</div> 	
				<div class="clear"></div> 
					<a href="?show=single&id='.$post->ID.'">
						<div class="moreinfo">
							More Info..
						</div>
					</a>
					</div>	
				     <div class="clear"></div>                     
				</div>
				<div class="shadow_wrap"></div>';		
				}
			
				$STRING .= ' <ul class="pagination paginationD paginationD10">'.$PPTFunction->PageNavigation(true).'</ul>';
			
				$STRING .= '</div>';
			
			}else{
			
				$STRING .= '<div class="corner_wrap">no results found</div>';
			
			}
			
	 
		} break;

		case "pages": {
		
			$STRING .= $HEADING;
			
			$STRING .= '<div class="toogle_wrap">
				<div class="trigger"><a href="#">Browse by page</a></div>
				<div class="toggle_container" style="display: block; display:visible; ">
				<ul>'. $this->Pages() .'</ul>
				</div>
			</div>';		
		
		} break;
				
		case "cats": {
		
			$STRING .= $HEADING;
			
			$STRING .= '<div class="toogle_wrap">
				<div class="trigger"><a href="#">Browse by category</a></div>
				<div class="toggle_container" style="display: block; display:visible; ">
				<ul>'. $this->Categories() .'</ul>
				</div>
			</div>';		
		
		} break;		
		
		case "single": {		
		
			if(isset($_GET['pageID'])){
			 
			$post = get_page( $_GET['pageID'] );			
			}else{
			$post = get_post($_GET['id']);
			}			
			
		 	$STRING .= $HEADING;
			
			$this->pageTitle = $post->post_title;
			
			$hits = get_post_meta($post->ID, "hits", true);
			$img = get_post_meta($post->ID, "image", true);
			$imgs = get_post_meta($post->ID, "images", true);
			if($img == ""){
				$img = get_post_meta($post->ID, "url", true);
			}
			if($hits == ""){
			$hits = 0;
			}
			
			// CHECK AND UPDATE THE HIT COUNTER
			$PPT->UpdateHits($post->ID,get_post_meta($post->ID, "hits", true));
			
			// Load in special data for moviepress
			if(strtolower(PREMIUMPRESS_SYSTEM) == "moviepress"){	
						
				$GLOBALS['MoveID'] 		= get_post_meta($post->ID, "MoveID", true);
				$GLOBALS['video'] 		= get_post_meta($post->ID, "filename", true);
				$GLOBALS['embed'] 		= get_post_meta($post->ID, "embed", true);
								
				if($GLOBALS['MoveID'] == ""){
				$GLOBALS['MoveID'] = $GLOBALS['video'];
				}
				$GLOBALS['membershipPackage'] = $current_user->yim['user_package'];
				$GLOBALS['access'] 		= explode(",",get_post_meta($post->ID, "package_access", true));
				
				 if(!is_array($GLOBALS['access']) || $GLOBALS['access'] =="" || $GLOBALS['access'][0] == "00" || $GLOBALS['access'][0] == "" || in_array($GLOBALS['membershipPackage'],$GLOBALS['access'])){ 
	
					$extra .='<div id="videoID" style="display:visible;">';
					if($GLOBALS['embed'] == 1){ 
					$extra .= get_post_meta($post->ID, "code", true); 
					}else{ $extra .= $ThemeDesign->VIDEOPLAYER($GLOBALS['MoveID']);   }
					$extra .='</div>';
	
					if(get_option('teaser_enabled') == 1 && ( $GLOBALS['membershipPackage'] == "" || $GLOBALS['membershipPackage'] == 0 )){ 
					$extra .='<div id="teaserID" style="display:none;">'.stripslashes(get_option("teaser_text")).'</div>'; 
					} 

				}else{
				
				$extra .='This video is only for ';
				
				 foreach($GLOBALS['access'] as $key){ $extra .= strip_tags($PACKAGE_OPTIONS[$key]['name'])." "; }  
				  
				 }
				 
				$extra = str_replace('640','270',str_replace('400','180',$extra)); 
			}
			
			$post_categories = wp_get_post_categories( $post->ID );
			$cats = array();
				
			foreach($post_categories as $c){
				$cat = get_category( $c );
				//$cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
				$catSTRING .= "<a href='".HOME_URI."?show=search&catID=".$c."'>".$cat->name."</a> ";
			}
			
			 //
			$STRING .= '
			
			<div class="corner_wrap">

        	<img src="'.$PPT->Image($post->ID,"url").$imgExtra.'" width="50" height="50" class="left_pic"/>
			
            <h3>'.$post->post_title.'</h3>';
			
			if(!isset($_GET['pageID'])){		 
			
            $STRING .= '<div class="post_details">
            '.$this->_customtext($post).'
			<div class="clear"></div>
            </div>';
			
			}
			
			
			
            $STRING .= '<div class="entry1">			
           		<p>'.nl2br($post->post_content).$extra.'</p>  
            </div> 
			
			<div class="services_content">				
				<p>Listed in '.$catSTRING.' </p>
				<p>Created: '.date('l jS \of F Y', strtotime($post->post_date)).'</p>
				<p>Visitors: '.$hits.'</p>				 
				'.$this->customfields($post).'';
							
				// Load in image gallery
				if(strlen($imgs) > 2){
				
					$STRING .= '				
					
					<div id="thumbsWrapper">
						<div id="content" style="display: block; ">
						'.$this->gallery($imgs).'
						</div>
					</div>
					<div class="clear"></div>	
					';		
				}
			
			$STRING .= '</div></div>
			<div class="shadow_wrap"></div>
			<span id="loading">Loading Image</span><div class="placeholder"></div><div id="panel">
					<div id="wrapper">
						<a id="prev"></a>
						<a id="next"></a>
					</div>
				</div>	';
			
		
		} break;
				
		case "contact": {		
 
			$STRING .= $HEADING;
			
			$STRING .= '<div class="corner_wrap"><div class="form_content">';
			
			$STRING .= $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']);
					
			$STRING .= nl2br(stripslashes(get_option("contact_page_text")));
					
            $STRING .= ' <form action="" method="post"> 
    				<input type="hidden" name="action" value="1" />
					
                    <label>Name:</label>
                    <input type="text" class="form_input" name="form[name]">
                    <label>Email:</label>              
                    <input type="text" class="form_input" name="form[email]">
                    <label>Message:</label>
                    <textarea class="form_textarea" name="form[message]"></textarea>
                    <input type="submit" class="form_submit_right" value="Submit" style="float:none;">
                    </form>
					
                    </div></div>';
		
		
			$STRING .= '</div>';
				
		} break;
		
		default: {
		
			$GLOBALS['premiumpress']['logo_url'] = get_option("logo_url");
		
			$STRING .= '
			<div class="logo">			
			<a href="'.HOME_URI.'"><img src="'.$PPT->Logo(true).'" title="'.$this->pageTitle.'" border="0" /></a>
			</div>
			 
			
			<div class="homeicons">
				<ul>
					<li><a href="#" id="activator"><img src="'.PPT_FW_IMG_URI.'mobile/icon3.png" border="0" alt="" title=""/>Search</a></li>
				 	<li><a href="'.HOME_URI.'?show=cats"><img src="'.PPT_FW_IMG_URI.'mobile/icon1.png" border="0" alt="" title=""/>Categories</a></li>
					<li><a href="'.HOME_URI.'?show=search&extra=new"><img src="'.PPT_FW_IMG_URI.'mobile/icon2.png" border="0" alt="" title=""/>New</a></li>
					
					
					<li><a href="'.HOME_URI.'?show=pages"><img src="'.PPT_FW_IMG_URI.'mobile/icon4.png" border="0" alt="" title=""/>Pages</a></li>
					<li><a href="'.HOME_URI.'?show=search&extra=articles"><img src="'.PPT_FW_IMG_URI.'mobile/icon5.png" border="0" alt="" title=""/>Articles</a></li>				 
					<li><a href="'.HOME_URI.'?show=contact"><img src="'.PPT_FW_IMG_URI.'mobile/icon6.png" border="0" alt="" title=""/>Contact</a></li>
										
				</ul>
			</div>
			
			 
			
			';	
		
		}
	
	
	}
		
	
	
	$STRING .= '</div>';

return $STRING;

}

function _css(){

$STRING = 'html{width:100%;height:100%;background:url('.PPT_FW_IMG_URI.'mobile/bg.jpg) repeat #a3a4a6;}
body {background:url('.PPT_FW_IMG_URI.'mobile/top_bg.jpg) repeat-x top center;margin:0px auto;padding:0px;font-family:Arial, Helvetica, sans-serif;font-size:14px;color:#423932;width:100%;height:100%;}
a{text-decoration:none;outline:none;}
.clear{clear:both;}
.clear_left{clear:both;float:left; height:15px;}
p{padding:0px;margin:0px;line-height:21px;text-align:left;}
blockquote{color:#247495; padding:0 0 0 10px; border-left: 2px #ddd solid; margin:0px 0 10px 0;}
img.left_pic{ float:left; padding:5px 15px 0px 0;border:none;}
/*---------------------starting main container and general style-----------------------*/
#main_container{width:100%;height:100%;margin:auto;position:relative;}
.logo {   width:300px; margin:auto auto;  }
.logo img {  max-width:300px;  padding-top:5px;}


a.left_bt{float:left;color:#FFFFFF; width:67px; height:43px; text-align:center; background:url('.PPT_FW_IMG_URI.'mobile/left_bt.png) no-repeat center; line-height:43px; font-size:14px; font-weight:bold; margin:4px 0 0 4px; text-shadow:1px 1px #085C8F;}
a.left_bt1{float:left;color:#FFFFFF; line-height:43px;  margin:6px 0 0 4px; background:transparent;  }

a.right_bt{float:right;color:#FFFFFF;margin:6px 4px 0 0; }
a.left_nav{float:left;color:#FFFFFF; width:67px; height:43px; text-align:center; background:url('.PPT_FW_IMG_URI.'mobile/left_bt.png) no-repeat center; line-height:43px; font-size:14px; font-weight:bold; margin:0px 0 10px 0;text-shadow:1px 1px #085C8F;}
a.right_nav{float:right;color:#FFFFFF; width:67px; height:43px; text-align:center; background:url('.PPT_FW_IMG_URI.'mobile/left_bt.png) no-repeat center; line-height:43px; font-size:14px; font-weight:bold; margin:0px 0 10px 0;text-shadow:1px 1px #085C8F;}
h1{padding:0px; margin:0px;}
h1 a{color:#FFFFFF; font-size:22px; text-shadow:1px 1px #000; text-decoration:none;}
.menu {text-align:center !important; height:50px !important;line-height:55px !important; padding:0px; margin:0px;  padding: 0px 0px 0px 0px  !important; display: block !important;}
.menu span{color:#FFFFFF; font-size:18px; text-shadow:1px 1px #085C8F; text-decoration:none; padding:0 10px 0 0; font-weight:normal; margin:0px;  }
h3{padding:15px 10px 10px 0px; margin:0px;font-size:20px; font-weight:normal; text-decoration:none;color:#5e4934;}
h4{padding:20px 10px 10px 0px; margin:0px;font-size:16px; font-weight:bold; text-decoration:none;color:#757575;}
h5{padding:10px 10px 10px 0px; margin:0px;font-size:14px; font-weight:bold; text-decoration:none;color:#5e4934;}
h6{padding:0 10px 5px 0px; margin:0px;font-size:14px; font-weight:bold; text-decoration:none;color:#666;}
/*----------------------menu-------------------------*/
.homeicons{width:100%;padding:25px 0px 0px 0;text-align:center;}
.homeicons ul{list-style:none;padding:0px;margin:0px;display:inline;line-height:25px;}
.homeicons ul li{list-style:none;display:inline-block;width:85px; height:125px; margin:0px 6px 5px 6px; background:url('.PPT_FW_IMG_URI.'mobile/icon_bg.png) no-repeat center top;}
.homeicons ul li a{ font-size:14px; font-weight:bold; color:#302f2f; text-decoration:none; text-shadow:1px 1px #dcdcdc;}
.homeicons ul li a img{padding:0px; width:85px; height:85px;}
/*---------------------content style----------------- */
.content{padding:15px 8px 50px 8px;}
.entry1{clear:both;padding:0 10px 20px 10px;}
.corner_wrap{-moz-border-radius:8px; -webkit-border-radius:8px;-khtml-border-radius:8px; background:url('.PPT_FW_IMG_URI.'mobile/box_wrap_bottom.jpg) no-repeat center bottom #FFFFFF; width:100%; text-align:left; margin:0px;}
.shadow_wrap{ width:100%; margin:auto; background:url('.PPT_FW_IMG_URI.'mobile/box_wrap_shadow.png) no-repeat center top; height:6px;}

.corner_wrap img { float:left; max-width:50px; max-height:50px; padding-left:10px; padding-top:15px; padding-right:20px; padding-bottom:10px; }
.corner_wrap h2{ padding:10px 15px 5px 0px; margin:0px;font-size:14px; font-weight:bold; text-decoration:none;color:#5e4934;}
.corner_wrap h2 a{ font-size:14px; font-weight:bold; text-decoration:none;color:#5e4934;}
 
.corner_wrap p { padding-bottom:10px; }

.entry img{max-width: 100%; margin:0 0 10px 0;}
.entry ul{padding:0px 0 10px 10px; margin:0px; list-style:none;}
.entry ul li{padding:2px 0 2px 16px; margin:0px; background:url('.PPT_FW_IMG_URI.'mobile/bullet.gif) no-repeat left;}
img.photo{margin:5px 0 0 10px;border:2px #ddd solid;}
a.button{width:100%;-moz-border-radius:8px;-webkit-border-radius:8px;-khtml-border-radius:8px;background:url('.PPT_FW_IMG_URI.'mobile/box_top_bg.jpg) no-repeat center top #3F3F3F;margin:15px 0 10px 0;color:#FFFFFF;
display:block; text-align:center; padding:12px 0 12px 0; font-size:18px; font-weight:normal;text-shadow:1px 1px #000;}
.post_details{ clear:both; background-color:#efefef; margin:5px 10px 10px 10px; padding:7px 0 7px 5px; color:#666; font-size:12px; -moz-border-radius:8px;-webkit-border-radius:8px;-khtml-border-radius:8px;  }
.post_details a{ font-weight:bold;color:#666; font-size:14px;}
.comment{padding:0 10px 20px 10px;}
.comment span a{ display:block; font-style:normal; text-decoration:underline; color:#8e0000;}
.services_content{-moz-border-radius:8px;-webkit-border-radius:8px;-khtml-border-radius:8px;background-color:#efefef;margin:0px;color:#666; padding:15px 10px 15px 10px; margin:0 0 0px 0; border-top:1px solid #999}
.contact_info{ padding:10px 0 15px 0;}
.subtext { font-size:10px;  height:20px; }
.subtext a { font-size:10px; }
.excerpt { font-size:11px; }
/*-------------------------sliding from top boxes---------------------------------*/
.box{position:absolute;top:-400px;width:100%;color:#7F7F7F;margin:auto;padding:0px;z-index:999999;text-align:center;}
.box_content_center{background-color:#3F3F3F;margin:0 8px 0 8px;color:#FFFFFF;-moz-border-radius-bottomleft:8px;-webkit-border-bottom-left-radius:8px;-khtml-border-bottom-left-radius:8px;-moz-border-radius-bottomright:8px;-webkit-border-bottom-right-radius:8px;-khtml-border-bottom-right-radius:8px;}
.box_content_tab{background-color:#171717;margin:0 8px 0 8px;color:#FFFFFF; text-align:center;-moz-border-radius-topleft:8px;-webkit-border-top-left-radius:8px;-khtml-border-top-left-radius:8px;-moz-border-radius-topright:8px;-webkit-border-top-right-radius:8px;-khtml-border-top-right-radius:8px;border-bottom:1px #595959 solid; padding:12px 0 12px 0; font-size:18px;}
a.boxclose{cursor:pointer;color:#FFFFFF; width:67px; height:43px;background:url('.PPT_FW_IMG_URI.'mobile/close_bt.png) no-repeat center; line-height:43px; font-size:14px; font-weight:bold; margin:20px 0px 20px 0; text-align:center;
display:block; float:right;text-shadow:1px 1px #085C8F;}
a.boxclose_right{cursor:pointer;color:#FFFFFF; width:67px; height:43px;background:url('.PPT_FW_IMG_URI.'mobile/close_bt.png) no-repeat center; line-height:43px; font-size:14px; font-weight:bold; margin:20px 25px 20px 0; text-align:center;
display:block; float:right;text-shadow:1px 1px #085C8F;}
.form_content{padding:20px 15px 10px 15px; text-align:left;}
.form_content label{ font-size:16px; font-weight:bold;line-height:28px;}
.form_input{width:100%;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px; background-color:#fff7ea; height:30px; border:1px #aa7c2a solid; color:#000000; font-size:13px;}
input.form_input_box{width:100%;-moz-border-radius:8px;-webkit-border-radius:8px;-khtml-border-radius:8px; background-color:#6F6F6F; height:32px; border:1px #808080 solid; color:#fff;}
.form_input_select{width:100%;-moz-border-radius:8px;-webkit-border-radius:8px;-khtml-border-radius:8px; background-color:#6F6F6F; height:32px; border:1px #808080 solid; color:#fff; margin-top:10px;}

textarea.form_textarea{width:100%;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px; background-color:#fff7ea; height:80px; border:1px #aa7c2a solid; color:#000000;}
input.form_submit{cursor:pointer;color:#FFFFFF; width:67px; height:43px;background:url('.PPT_FW_IMG_URI.'mobile/left_bt.png) no-repeat center; border:none; line-height:43px; font-size:14px; font-weight:bold; margin:20px 15px 20px 0;display:block; float:right;text-shadow:1px 1px #085C8F; font-family:Arial, Helvetica, sans-serif;}
input.form_submit_right{cursor:pointer;color:#FFFFFF; width:67px; height:43px;background:url('.PPT_FW_IMG_URI.'mobile/left_bt.png) no-repeat center; border:none; line-height:43px; font-size:14px; font-weight:bold; margin:20px 0px 20px 0;display:block; float:right;text-shadow:1px 1px #085C8F; font-family:Arial, Helvetica, sans-serif;}
.box_content h3{font-size:22px; font-weight:normal; padding:15px 0 10px 0; margin:0px;color:#FFFFFF;text-shadow:1px 1px #085C8F;}

/*-----------categories toggle-------------------*/
.toogle_wrap{width:100%;-moz-border-radius:8px;-webkit-border-radius:8px;-khtml-border-radius:8px;background:url('.PPT_FW_IMG_URI.'mobile/box_wrap_bottom.jpg) no-repeat center bottom #FFFFFF;margin:0px 0 0 0;color:#636363;}
.trigger{padding:5px 0 6px 10px;margin:0;}
.trigger a{color: #636363;text-decoration: none;display: block;padding:6px 0 6px 0px;font-size:18px;}
.active {background:url('.PPT_FW_IMG_URI.'mobile/toggle_close.gif) no-repeat left;background-position:7px 7px;}
.trigger a:hover, .trigger a:hover:focus{color:#8e0000;}
.toggle_container{overflow: hidden;padding:10px;clear: both;}
.toggle_container ul{ padding:0; margin:0px; list-style:none;}
.toggle_container ul li{ padding:12px 0 12px 0px; margin:0px 0 5px 0;width:100%;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px; background-color:#6F6F6F;}
.toggle_container ul li a{ text-decoration:none; color:#FFFFFF; font-size:14px; font-weight:bold; padding:0 0 0 10px;}
.toggle_container ul li a:hover, .toggle_container ul li a:focus{color:#fff6c7; }
.toggle_container ul ul {  margin-top:10px; }
.toggle_container ul ul li{ padding:5px 0 5px 0px; padding-left:20px; margin:0px 0 5px 0;width:100%; background:url('.PPT_FW_IMG_URI.'mobile/sub.png) no-repeat 10px 0px }
.cattitle { font-size:16px; padding-top:0px; margin-top:0px;}
/*------------gallery------------------------------*/
span#description{text-shadow:1px 1px 1px #000;display:none;}
span#loading{position:absolute;width:100%; height:50px;text-align:center;left:0px; top:165px;background:#000;color:#FFFFFF;padding:0px; line-height:50px; display:none;}
#thumbsWrapper{width:100%;}
#content{display:none;  margin:0px; clear:both;}
#content img{margin:8px;cursor:pointer; background:white; padding:5px; border:1px solid #666; }
.placeholder{float:left;clear:both;width:100%;height:30px;}
#panel{width:100%;position:fixed;bottom:0px;left:0px;right:0px;height:0px;top:65px;text-align:center; }
#panel img{cursor:pointer;position:relative;display:none;-moz-box-shadow:0px 0px 10px #111;-webkit-box-shadow:0px 0px 10px #111;box-shadow:0px 0px 10px #111;}
#wrapper{position:relative;margin:0px auto 0px auto;}
a#next, a#prev{width:51px;height:178px;position:fixed;cursor:pointer;outline:none;display:none;z-index:999999;}
a#next{right:0px;top:50%;margin-top:-100px;background:url('.PPT_FW_IMG_URI.'mobile/right_nav_a.png) no-repeat center;}
a#prev{left:0px;top:50%;margin-top:-100px;background:url('.PPT_FW_IMG_URI.'mobile/left_nav_a.png) no-repeat center;}

.moreinfo { border-top:1px solid #666;  line-height:30px; padding:5px; display:block;   background: #efefef url('.PPT_FW_IMG_URI.'mobile/moreinfo.png) right no-repeat; font-weight:bold; color:#666; padding-left:10px; -moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;}
.moreinfo:hover { background:#fff3df url('.PPT_FW_IMG_URI.'mobile/moreinfo.png) right no-repeat; -moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;}
/*-------------------footer-----------------------*/
#footer{position:relative;margin-top:-50px;height:50px;clear:both;text-align:center;background:url('.PPT_FW_IMG_URI.'mobile/footer_bg.jpg) repeat-x center;color:#FFFFFF;}
#footer span{color:#FFFFFF; font-size:18px; text-shadow:1px 1px #085C8F; text-decoration:none; padding:0 10px 0 0; line-height:50px;}

ul.pagination { margin-right:0px; margin-left:0px; -moz-border-radius: 8px; -webkit-border-radius: 8px;-khtml-border-radius: 8px; }
.notification {	border: 1px solid;	border-bottom-width: 2px;	color: #4f4f4f;	display: block;	font-family: "Lucida Grande", "Lucida Sans Unicode", Arial, sans-serif;	font-size: 11px;	line-height: 19px;	margin-bottom: 20px;	overflow: hidden;	position: relative;	-moz-box-shadow: 0px 1px 2px rgba(0,0,0,0.15), 0px 0px 2px rgba(0,0,0,0.05);	-webkit-box-shadow: 0px 1px 2px rgba(0,0,0,0.15), 0px 0px 2px rgba(0,0,0,0.05);	box-shadow: 0px 1px 2px rgba(0,0,0,0.15), 0px 0px 2px rgba(0,0,0,0.05); 	-moz-border-radius: 4px;	-webkit-border-radius: 4px;	border-radius: 4px; }
.notification p {	padding: 16px 16px 16px 42px;	text-shadow: 0px 1px 0px rgba(255,255,255,0.65);}
.notification p strong {	color: #303030;	font-weight: bold;}
.notification.success {	background-color: #dde6ba;	border-color: #d0e289 #c6d881 #b8cb71;	background: -moz-linear-gradient(top,		#fff,		#e6efc2 2%,		#d9e2b7	);	background: -webkit-gradient(linear, left top, left bottom,		from(#fff),		color-stop(0.02, #e6efc2),		to(#d9e2b7)	);	background: linear-gradient(top,		#fff,		#e6efc2 2%,		#d9e2b7	);
	filter: PROGID:DXImageTransform.Microsoft.Gradient(StartColorStr=\'#e6efc2\',EndColorStr=\'#d9e2b7\');}
.notification.success p {	background: transparent url(\''.PPT_FW_IMG_URI.'/tick.png\') no-repeat scroll 14px 17px;}
.notification.success p strong {	color: #417800;}
';


return $STRING;

}

	function Categories($type=0){
	
			$catlist="";	
			$Maincategories= get_categories('pad_counts=1&use_desc_for_title=1&hide_empty=0&hierarchical=0&exclude='.str_replace("-","",$GLOBALS['premiumpress']['excluded_articles'].",".$GLOBALS['premiumpress']['excluded_articles']));
			
			$Maincatcount = count($Maincategories);	 
	
			$i=1;
			foreach ($Maincategories as $Maincat) { if(strlen($Maincat->name) > 1){ 
	 
			if($Maincat->parent ==0){
			
	
					$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0&exclude='.str_replace("-","",$GLOBALS['premiumpress']['excluded_articles']).",".str_replace("-","",$GLOBALS['premiumpress']['excluded_articles'])); 
					$catcount = count($categories);	
				 
	
					$ThisClass = ($i == count($Maincategories) - 1) ? '' : ''; //last
					$catlist .= '<li class="'.$ThisClass.$class.'">  <a href="'.HOME_URI.'?show=search&catID='.$Maincat->term_id.'" title="'.$Maincat->category_nicename.'">';
					$catlist .= $Maincat->name;
					$catlist .= " (".$Maincat->count.")"; 
					$catlist .= '</a>';
								 
			
					// do sub cats
					$currentcat=get_query_var('cat');				

					if(count($categories) > 0){
					$catlist .="<ul>";
					 
						foreach ($categories as $cat) {		
					
							$catlist .= '<li class="sub '.$ThisClass.'"> <a href="'.HOME_URI.'?show=search&catID='.$cat->term_id.'">';
							$catlist .= $cat->cat_name;
							$catlist .= " (".$cat->count.")"; 
							$catlist .= '</a></li>';
							 
							$i++;		
						}
					 $catlist .="</ul>";
					}
		
				$catlist .= '</li>';
				$i++;
			} 
	 } }
	 
	 return $catlist;
}

	function Pages($type=0){
	
		global $wpdb;

		$STRING = "";
		$Pages = get_pages("parent=0&sort_column=menu_order&exclude=".get_option("excluded_pages"));
		$Page_Count = count($Pages);	
 		 $i=1;
		foreach ($Pages as $Page) { if(strtolower($Page->post_title) != "contact"){	
		
		if($i == 1){$xbit = "pfirst"; }elseif($i == $Page_Count){ $xbit = "plast";  }else{ $xbit = ""; }
		
		$STRING.= '<li class="'.$xbit.'"><a href="'.HOME_URI.'?show=single&pageID='.$Page->ID .'" title="'.$Page->post_title.'">';
		
		 
			
			$STRING .= $Page->post_title;
			$STRING .= '</a>';

			 
				$inner_pages = get_pages('child_of='.$Page->ID.'&sort_column=post_date&orderby=menu_order&sort_order=desc');
				$inner_pages_count = count($inner_pages);
	
				if($inner_pages_count > 0){
					$STRING .= '<ul>';
					foreach($inner_pages as $inner_page){
		
						$STRING.= '<li><a href="'.HOME_URI.'?show=single&pageID='.$inner_page->ID.'" title="'.$inner_page->post_title.'">';
						$STRING .= $inner_page->post_title;
						$STRING .= '</a></li>';
		
					}
					$STRING .= '</ul>';
				}
		 
		$STRING .= '</li>'; $i++;
		}
		}		
		
		return  $STRING;
	}
	
	
	function customfields($post){

	global $wpdb,$PPT;$row=1; $STRING = "";

	
	$FieldValues = get_option("customfielddata");
 

	if(is_array($FieldValues)){ 

		foreach($FieldValues as $key => $field){

			if(isset($field['show']) && $field['enable'] == 1 ){ 				 
			
			$imgArray = array('jpg','gif','png');

			$value = $PPT->GetListingCustom($post->ID,$field['key'] ); 
 
			if(is_array($value) || strlen($value) < 1){   }else{			
		
				$STRING .= "<div class='full clearfix border_t box'><p class='f_half left'><br />"; 
				$STRING .= "<b>".$field['name']."</b></p><p class='f_half left'><br />";
		
				switch($field['type']){
				 case "textarea": {			
					$STRING .= "<br />".nl2br(stripslashes($value));
				 } break;
				 case "list": {
					$STRING .=  $value;
				 } break;
				 default: {
					
					$pos = strpos($value, 'http://'); 					
					if($field['key'] == "skype"){
						$STRING .= "<a href='skype:".$value."?add'>" .  $value ."</a>";
					}elseif ($pos === false) {
						$STRING .=  $value;
					}elseif(in_array(substr($value,-3),$imgArray)){
						$STRING .= "<img src='".strip_tags($value)."' style='max-width:250px;margin-left:20px;'>";
					}else{
						$STRING .= "<a href='".$value."' target='_blank'";
						if($GLOBALS['premiumpress']['nofollow'] =="yes"){ $STRING .= 'rel="nofollow"'; }
						$STRING .= ">" .  $value ."</a>";				
					} 
					
				 }
		
				}
				$row++;
				$STRING .= "</p></div>";
				
				}

				} 
			}
		}
		
		return $STRING;
	
	}
	
	
	
	function gallery($images){	
	 
	 global $PPT;
	 
	 if($images == ""){ return; } 
	 
	 
		$imgBits = explode("/",get_permalink());	
		$tt = count($imgBits)-2; $it=0;
		
		while($tt > 0){  
			$imagePath .= $imgBits[$it]."/";
			$tt--;$it++;		 
		 }
			 
		 $string = "";
		 $image_array = explode(",",$images); 
	
			foreach($image_array as $image){ if(strlen($image) > 2){ 
				
				$string .= '<img alt="'.$PPT->ImageCheck($image,"full").'"  style="opacity: 1; " src="'.$PPT->ImageCheck($image,"t","&amp;w=50&amp;h=50").'" alt="image" />';
				
				
				
			} }	
			
			return $string;
	 
	 }	
	
	
	
	
	function _subtext($post, $page){

	 global $PPT; $extra = ""; $STRING = "";
	 
	 // Load in the category
	 $post_categories = wp_get_post_categories( $post->ID );
	 $cats = array();
	 foreach($post_categories as $c){
		$cat = get_category( $c );
		//$cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
		$catSTRING .= "<a href='".HOME_URI."?show=search&catID=".$c."'>".$cat->name."</a>";
	 }
	 
 
	 $STRING = "<p><small>".$catSTRING." | ".date('l jS \of F Y', strtotime($post->post_date) )."</small></p>";
	 return $STRING;	
	}	
	
	function _customtext($post, $page="single"){

	 global $PPT; $extra = ""; $STRING = "";
	 
	 
	// Load custom header content for our themes
	switch(strtolower(PREMIUMPRESS_SYSTEM)){
	
		case "classifiedstheme": 	
		case "realtorpress": 	
		case "shopperpress": {  
			$op = get_post_meta($post->ID, "old_price", true);
			$oldPrice = $PPT->Price($op,get_option('currency_code'),get_option('display_currency_position'),1); ;
			$Price = $PPT->Price(get_post_meta($post->ID, "price", true),get_option('currency_code'),get_option('display_currency_position'),1); ;
			$STRING = "<b>Price ".$Price."</b>";
			if(strlen($op) > 0){
				$STRING .= " <strike>".$oldPrice."</strike>";
			}
		
		} break;
		case "auctionpress": { } break;	
		case "couponpress":	{ 
		
			$type 	= get_post_meta($post->ID, "type", true);
			$code 	= get_post_meta($post->ID, "code", true);
			$link 	= $PPT->CheckLink($post);
			
			if(strlen($code) > 1){
				$STRING = "<b>Coupon Code:</b> <a href='".$link."' target='_blank' style='min-width:100px; background:#cfffd1; color:#229227; border:1px dashed #666; padding:2px; margin-right:5px; float:right; '>".$code."</a>";
			}elseif($type == "offer"){
				$STRING = "<b>Promotional Offer</b>";
			}
			
		} break;		

		case "directorypress": {  
		
		$link = $PPT->CheckLink($post);	
		if(strlen($link) > 2){ 
        $STRING = '<div style="text-align:Center;"><a href="'.$link.'" target="_blank" rel="nofollow" style="border-bottom:1px #666 dotted">Visit Website</a></div>';
        }
       
		
		} break;
		case "moviepress": {  } break;
		
		default: { 
				
		}
	
	} 

		return $STRING;	
	}	
	
		

}
 

?>