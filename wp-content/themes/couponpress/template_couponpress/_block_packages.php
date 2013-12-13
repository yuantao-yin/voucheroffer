<?php

/*
LAST UPDATED: 27th March 2011
EDITED BY: MARK FAIL
*/

$packdata = get_option("packages"); $customdata = get_option("customfielddata"); $showmaps = get_option('display_googlemaps');  ?>


<div class="padding"> 

	<div class="payicon"></div>
	
    <h1><?php echo $GLOBALS['_LANG']['_s8']; ?></h1><br />
    
    <p><?php echo $GLOBALS['_LANG']['_s9']; ?></p><br /> 
 
	<?php $i=1; $div=1; foreach($packdata as $package){ ?>
 
	<?php if($div == 1){ ?><div class="grid col4" id="griddler"><?php } ?>
 
	<?php if(isset($package['enable']) && $package['enable'] == 1){ ?>

	<article>

		<header>

			<hgroup class="top">

				<h1><?php echo $package['name']; ?></h1>

			</hgroup>

			<hgroup class="price">

				<h2><?php if($package['price'] > 0){ echo $PPT->Price($package['price'],"<span class='currency'>".$GLOBALS['premiumpress']['currency_symbol']."</span>",$GLOBALS['premiumpress']['currency_position'],1); }else{ echo $GLOBALS['_LANG']['_free'];} ?></h2>

			</hgroup>

		</header>

		<section>
        
		<div class="clearfix"></div>

			<ul>
            <li class="time"><?php if(isset($packdata[$i]['rec']) &&$packdata[$i]['rec'] ==1){ 
			echo $packdata[$i]['expire']." ".$GLOBALS['_LANG']['_35_3'];  ?>
                <div class="tooltip">
						<div>
							<h3><?php echo SPEC($GLOBALS['_LANG']['_block_p1']); ?></h3>
							<p><?php echo SPEC(str_replace("%a",$packdata[$i]['expire']." ".$GLOBALS['_LANG']['_35_3'],$GLOBALS['_LANG']['_block_p2'])) ?></p>
						</div>
					</div>
            <?php
			}elseif( $packdata[$i]['expire'] > 1 ){ echo $packdata[$i]['expire']." ".$GLOBALS['_LANG']['_35_3']; ?>
            
             <div class="tooltip">
						<div>
							<h3><?php echo SPEC($GLOBALS['_LANG']['_block_p3']); ?></h3>
							<p><?php echo SPEC(str_replace("%a",$packdata[$i]['expire']." ".$GLOBALS['_LANG']['_35_3'],$GLOBALS['_LANG']['_block_p4'])) ?></p>
						</div>
					</div>
            
            <?php }else{  echo $GLOBALS['_LANG']['_35_9'];} ?></li>
            
          
            
            
            <li class="<?php if( $packdata[$i]['a1'] == 1 ){ echo "yes"; }else{ echo "no"; } ?>"><?php echo $GLOBALS['_LANG']['_35_4']; ?></li>
            <li class="<?php if( $packdata[$i]['a2'] == 1 ){ echo "yes"; }else{ echo "no"; } ?>"><?php echo $GLOBALS['_LANG']['_35_5']; ?></li>
            <li class="<?php if( $packdata[$i]['a3'] == 1 ){ echo "yes"; }else{ echo "no"; } ?>"><?php echo $GLOBALS['_LANG']['_35_6']; ?></li>
           <?php if($showmaps != "no"){ ?> <li class="<?php if( $packdata[$i]['a4'] == 1 ){ echo "yes"; }else{ echo "no"; } ?>"><?php echo $GLOBALS['_LANG']['_35_8']; ?></li><?php } ?>
             

			<?php  
     
           if(is_array($customdata)){  foreach($customdata as $cdata){ 
			 
            if(isset($cdata['enable']) && $cdata['enable'] ==1 && $cdata['show'] == 1){
            
            	if( isset($cdata['pack'.$i]) && $cdata['pack'.$i] == 1 && strlen($cdata['name']) > 1 ){ $astyle = 'yes'; }else{ $astyle = 'no';}
                                    
				echo '<li class="'.$astyle.'">'. $cdata['name'];
				
					if(strlen($cdata['desc1']) > 1){
					
						echo '<div class="tooltip">
							<div>
								<h3>'. $cdata['name'].'</h3>
								<p>'.$cdata['desc1'].'</p>
							</div>
						</div>';	
							 
					}
                    
            	}
				
				echo '</li>';
			
            }
            
			} 
            
            ?>

			 

			</ul>

			<div class="griddler-controls"><a class="button" href="#" onclick="document.getElementById('packageID').value='<?php echo $i; ?>';document.hiddenPackage.submit();"><?php echo SPEC($GLOBALS['_LANG']['_block_p5']); ?></a></div>			

		</section>

	</article>
    
    <?php } ?>
    
   <?php if($div == 4){ ?></div><div class="clearfix"></div><?php $div=0; } ?>
    
    <?php $i++; $div++; } ?> 
 
 




<div class="extrainfo"><?php echo stripslashes(get_option("pak_text")); ?></div>

 


<form name="hiddenPackage" action="" method="post">
<input type="hidden" name="packageID" id="packageID" value="1">
</form> 

</div> 

<script type="text/javascript">
jQuery(document).ready(function() {  
	var $gridSections = jQuery("#griddler article");	
	$gridSections.hover
	(
		function()
		{
			$gridSections.removeClass("selected");
		}
	);
});
</script>
 