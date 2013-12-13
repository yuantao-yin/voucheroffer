<?php 

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

if(!isset($GLOBALS['nosidebar'])){ get_sidebar(); } ?>
 
    </div> <!-- end CONTENT -->

</div> <!-- end SIDEBAR -->



 

    <div id="footer" class="clearfix full">
    
        <div class="w_960" style="margin:0 auto;"> 
        
            <div class="b_third_col col first_col" style="padding-left:15px;"> 
             <?php echo stripslashes(get_option("footer_text")); ?>
            </div> 
                                   
            
            
            
        <div class="clearfix"></div>
                        
        <div id="copyright" class="full">
            <p>&copy; <?php echo date("Y"); ?> <?php echo get_option("copyright"); ?> <?php $PPT->Copyright(); ?></p>
        </div> 
                        
    
    </div> 
        
 

</div>  <!-- end WRAPPER -->

        
</div> 

<?php wp_footer(); ?> 
 
</body>
</html>