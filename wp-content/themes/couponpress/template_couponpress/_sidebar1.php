<?php
/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/
?>
<div class="leftSidebar left">
			 

 

    <?php if(isset($GLOBALS['premiumpress']['catID']) && is_numeric($GLOBALS['premiumpress']['catID']) && $PPT->CountCategorySubs($GLOBALS['premiumpress']['catID']) > 0 && get_option('display_sub_categories') =="left" ){ ?>
    
    <div class="itembox">
    
        <h2 id="sidebar-cats-sub"><?php echo SPEC($GLOBALS['_LANG']['_subcategories']) ?></h2>
        
        <div class="itemboxinner nopadding">
        
        <?php echo $PPTDesign->HomeCategories(); ?>
        
        </div>
    
    
    </div>
        
    <?php } ?>

    
	<?php if(get_option('display_categories') =="yesleft" && $PPTDesign->CanDisplayElement(get_option("display_categories_pages"))  ){ ?> 
    
    
    
    <div class="itembox">
    
        <h2 id="sidebar-cats"><?php echo SPEC($GLOBALS['_LANG']['_categories']); ?></h2>
        
        <div class="itemboxinner nopadding">
        
            <ul class="category" id="Accordion">
            
            <?php echo $PPTDesign->SideBarNavigationBox(); ?>
            
            </ul>
        
        </div>    
    
    </div>    
                       
 
    <?php  } ?> 



<?php 

/****************** INCLUDE WIDGET ENABLED SIDEBAR *********************/

if(function_exists('dynamic_sidebar')){ dynamic_sidebar('Left Sidebar (3 Column Layouts Only)');  }

/****************** end/ INCLUDE WIDGET ENABLED SIDEBAR *********************/

?>


<?php if(get_option('advertising_left_checkbox') =="1"){ ?><center><?php echo $PPT->Banner("left");?></center><?php } ?>  

 &nbsp;&nbsp;
 
</div>