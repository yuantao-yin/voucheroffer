<?php
header("Content-type: text/xml"); 
print '<?xml version="1.0" encoding="utf-8"?>
<graph>
	<general_settings bg_color="FFF8F0" type_graph="h" />
	<header text="" font="Verdana" color="000000" size="18" />
	<subheader text="" font="Verdana" color="595959" size="12" />
	<legend font="Verdana" color="000000" font_size="16" />
	<legend_popup font="Verdana" bgcolor="FFFFE3" font_size="10" />
	<Xheaders rotate="0" color="000000" size="10" title="" title_color="000000" />
	<Yheaders color="000000" size="10" title="" title_rotate="90" title_color="000000" />
	<grid grid_width="270" grid_height="330" grid_color="EDEDED" grid_alpha="100" grid_thickness="1" bg_color="ffffff" bg_alpha="70" alternate_bg_color="FFFFFE" border_color="000000" border_thickness="1" />
	<bars view_value="1" width="16" space="10" alpha="70" view_double_bar="1" color_double_bar="666666" pieces_grow_bar="200" />';
	$graphVal = explode("**", strip_tags(trim($_REQUEST['d'])));
	
	foreach($graphVal as $value){
	
		 $ff = explode("--", strip_tags(trim($value)));
		 if($ff[0] !="" && $ff[1] !=""){
			 print '<data name="'.$ff[0].'" value="'.$ff[1].'" color="333333" />';
		 }
	}
 
echo '</graph>';
?>