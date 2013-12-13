<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title> 
 
<?php wp_head(); ?> 
 
</head> 

<body <?php ppt_body_class(); ?>>

 
	<div class="wrapper w_960"> 
	
		<div id="header" class="full">
        
            <div id="hpages">
            
                <ul>
                
                    <?php echo $PPT->Pages(); ?>
                
                </ul>
            
            </div>
        
         	<div class="clearfix"></div>
            
         
            <div class="f_half left" id="logo"> 
            
             <a href="<?php echo $GLOBALS['bloginfo_url']; ?>/" title="<?php bloginfo('name'); ?>">
             
			 	<img src="<?php echo $PPT->Logo(); ?>" alt="<?php bloginfo('name'); ?>" />
                
			 </a>
            
            </div>        
        
            <div class="left padding5" id="banner"> 
            
           	 <?php echo $PPT->Banner("top"); ?>
             
            </div>        
        
        <div class="clearfix"></div>
        
        </div> 
		
        <div class="full"> 
            
            <?php if(has_nav_menu('PPT-CUSTOM-MENU-PAGES')){ wp_nav_menu( $GLOBALS['blog_custom_menu'] ); }else{ ?>
            
                <ul class="menu">           
                 
                 <li class="first"><a href="<?php echo $GLOBALS['bloginfo_url']; ?>/" title="<?php bloginfo('name'); ?>"><?php echo SPEC($GLOBALS['_LANG']['_home']) ?></a></li> 
                        
                   <?php echo $PPTDesign->LAY_NAVIGATION(); ?>             
                     
                </ul> 
                
             <?php } ?>
             
            
        </div>
        

        <div class="full">
    
        
        <div id="submenubar"> 

       
            <form method="get"  action="<?php echo $GLOBALS['bloginfo_url']; ?>/" name="searchBox" id="searchBox">
              <table width="100%" border="0" id="SearchForm">
              <tr>
                <td><input type="text" value="Enter a store or category to find Coupons & Deals" name="s" id="s" onfocus="this.value='';"/></td>
        		<td><div class="searchBtn" onclick="document.searchBox.submit();"> &nbsp;</div> </td>
                <td>&nbsp;&nbsp;<?php if(get_option("display_advanced_search") ==1){ ?><a href="javascript:void(0);" onClick="jQuery('#AdvancedSearchBox').show();"><small><?php echo SPEC($GLOBALS['_LANG']['_head2']) ?></small></a><?php } ?></td>
              </tr>
            </table>
            </form>
     
             
            <ul class="submenu_account">            
            <?php  if ( $user_ID ){ ?>
            <li><a href="<?php echo wp_logout_url(); ?>" title="<?php echo SPEC($GLOBALS['_LANG']['_head3']) ?>"><?php echo SPEC($GLOBALS['_LANG']['_head3']) ?></a></li>
            <li><a href="<?php echo $GLOBALS['premiumpress']['dashboard_url']; ?>" title="<?php echo SPEC($GLOBALS['_LANG']['_head4']) ?>"><?php echo SPEC($GLOBALS['_LANG']['_head4']) ?></a></li>
            <li><b><?php echo $current_user->user_login; ?></b></li>
            <?php  }else{ ?>
            
            <li><a href="<?php echo $GLOBALS['bloginfo_url']; ?>/wp-login.php" title="<?php echo SPEC($GLOBALS['_LANG']['_head5']) ?>"><?php echo SPEC($GLOBALS['_LANG']['_head5']) ?></a> |  <a href="<?php echo $GLOBALS['bloginfo_url']; ?>/wp-login.php?action=register" title="<?php echo SPEC($GLOBALS['_LANG']['_head6']) ?>"><?php echo SPEC($GLOBALS['_LANG']['_head6']) ?></a></li>
            
            <?php } ?>
            </ul>
        
        
        </div>
        
        
        </div>
        
 
		<div id="page" class="clearfix full box">
        
        
        <?php if(get_option("display_advanced_search") ==1 ){  PPT_AdvancedSearch('preset-default');  } ?>
 
  
		<?php if(get_option("PPT_slider") =="s1"  && is_home() && !isset($_GET['s']) && !isset($_GET['search-class']) ){ echo $PPTDesign->SLIDER(); } ?> 
         
         
        
        <div id="content">


		<?php
 
            if(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_sidebar1.php") && !isset($GLOBALS['nosidebar']) && !isset($GLOBALS['nosidebar-left']) ){
            
                include(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_sidebar1.php");
            
            }elseif($GLOBALS['premiumpress']['display_themecolumns'] =="3" && !isset($GLOBALS['nosidebar']) && !isset($GLOBALS['nosidebar-left']) ){
			
				include("_sidebar1.php");
			
			
			}
        
        ?>