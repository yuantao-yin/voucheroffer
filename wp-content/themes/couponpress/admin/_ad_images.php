<?php 
if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 


// IMAGE UPLOAD
if(isset($_FILES['ufile'])){ 
	if(strlen($_FILES['ufile']['tmp_name'][0]) > 5){ 
	copy($_FILES['ufile']['tmp_name'][0], get_option("imagestorage_path").str_replace(" ","",$_FILES['ufile']['name'][0]));
	}
	if(strlen($_FILES['ufile']['tmp_name'][1]) > 5){ 
	copy($_FILES['ufile']['tmp_name'][1], get_option("imagestorage_path").str_replace(" ","",$_FILES['ufile']['name'][1]));
	}
	if(strlen($_FILES['ufile']['tmp_name'][2]) > 5){ 
	copy($_FILES['ufile']['tmp_name'][2], get_option("imagestorage_path").str_replace(" ","",$_FILES['ufile']['name'][2]));
	}
$GLOBALS['error'] 		= 1;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "Image Saved Successfully";

}elseif(isset($_GET['dvid'])){ // DELETE VIDEO

unlink(get_option("imagestorage_path")."videos/".$_GET['dvid']);

$GLOBALS['error'] 		= 1;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "Video Deleted";



}elseif(isset($_POST['action']) && $_POST['action'] == "upVideo"){

	@session_start();
	session_unset('suckTime');

	$allowedExtensions = array("3gp","avi","mpg","mpeg","mpe4","mov","m4a","mj2","flv","wmv","mp4","ogg","webm");
 
foreach ($_FILES as $file) {

	if ($file['tmp_name'] > '' && in_array(end(explode(".",strtolower($file['name']))),$allowedExtensions)) { 
	
	
	
	// FILE SIZE
	$filesize 	=	$_FILES["file_vid"]["size"];
	$filesize 	= 	round(($filesize/1048576),2);	
	$size		=	$_POST['size']; 
	$quality	=	$_POST['quality'];
	$audio		=	$_POST['audio'];
	$type		=	$_POST['type'];
	$name		=	$_FILES["file_vid"]["name"];	 	
	$SavePath	=	get_option("imagestorage_path")."videos/";
	$filePlusPath =  $SavePath.$_FILES["file_vid"]["name"];	 
	
	if(file_exists($filePlusPath)){
	die("<h1>File Already Exists</h1><p>This video file already exists, try renaming the new one or deleting the old one.</p>");
	}
	
	// sessions for listing file
	$_SESSION['name']		= $name;
	$_SESSION['dest_file'] 	= $filePlusPath;
 	$_SESSION['type'] 		= $type;
	$_SESSION['savePath'] 	= $SavePath;
	$_SESSION['logPath'] 	= get_option("imagestorage_link")."videos/log/";
	
	//everething is OK  -> move uploaded file
	move_uploaded_file($_FILES["file_vid"]["tmp_name"],$SavePath . $_FILES["file_vid"]["name"]);
	
	
	if($type != "none"){

	
		if($type=="webm" && $audio=="11025"){$audio="22050";$type="webm"; }// for webm  audio can not be 11050
		if($type=="ogg" && $audio=="11025"){$audio="22050"; $type="ogg"; }// for ogg  audio can not be 11050	
		
		
		if($type=="flv"){ $call="ffmpeg -i ".$filePlusPath." -vcodec flv -f flv -r 30 -b ".$quality." -ab 128000 -ar ".$audio." -s ".$size." ".$SavePath.$name.".".$type." -y 2> ".$SavePath."log/".$name.".txt";}	
		if($type=="avi"){ $call="ffmpeg -i ".$filePlusPath." -vcodec mjpeg -f avi -acodec libmp3lame -b ".$quality." -s ".$size." -r 30 -g 12 -qmin 3 -qmax 13 -ab 224 -ar ".$audio." -ac 2 ".$SavePath.$name.".".$type." -y 2> ".$SavePath."log/".$name.".txt";}	
		if($type=="mp3"){ $call="ffmpeg -i ".$filePlusPath." -vn -acodec libmp3lame -ac 2 -ab 128000 -ar ".$audio."  ".$SavePath.$name.".".$type." -y 2> log/".$name.".txt";}	
		if($type=="mp4"){ $call="ffmpeg -i ".$filePlusPath."  -vcodec mpeg4 -r 30 -b ".$quality." -acodec aac -strict experimental -ab 192k -ar ".$audio." -ac 2 -s ".$size." ".$SavePath.$name.".".$type." -y 2> ".$SavePath."log/".$name.".txt";}	
		if($type=="wmv"){ $call="ffmpeg -i ".$filePlusPath." -vcodec wmv1 -r 30 -b ".$quality." -acodec wmav2 -ab 128000 -ar ".$audio." -ac 2 -s ".$size." ".$SavePath.$name.".".$type." -y 2> ".$SavePath."log/".$name.".txt";}	
		if($type=="ogg"){ $call="ffmpeg -i ".$filePlusPath." -vcodec libtheora -r 30 -b ".$quality." -acodec libvorbis -ab 128000   -ar ".$audio." -ac 2 -s ".$size." ".$SavePath.$name.".".$type." -y 2> ".$SavePath."log/".$name.".txt";}	
		if($type=="webm"){ $call="ffmpeg -i ".$filePlusPath." -vcodec libvpx  -r 30 -b ".$quality." -acodec libvorbis -ab 128000   -ar ".$audio." -ac 2 -s ".$size." ".$SavePath.$name.".".$type." -y 2> ".$SavePath."log/".$name.".txt";}
	
		/* START CONVERTING */		
		$convert = (popen("start /b ".$call, "r"));
		
		pclose($convert);	
	 
	// read every 1 second file listening.php to see if the converting has any errors or to see is it finished
	echo "<script>\n";
	  echo "jQuery(document).ready(function()\n";
	  echo "{\n";
		echo "var refreshId = setInterval(function() \n";
	   echo "{\n";
		 echo "jQuery('#timeval').load('".$GLOBALS['template_url']."/admin/_ad_images_listening.php?randval='+ Math.random());\n";
	   echo "}, 1000);\n";
		echo "});\n";
	  echo " </script>\n";
	  
	  echo '<div id="timeval"></div>';
	  
	  /* END CONVERTING */ 
	  die(); 
	
	}	
	
	
	
	}else{
	die("invalid file");
	}
}

}









if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "moviepress" && ( strlen(get_option("imagestorage_path")) < 5 || !is_writable(get_option("imagestorage_path")."videos/") ) ){
?>
	<div class="wrap">
	<h1> Video Storage Folder is NOT Writable</h1>
	<p> Your video storage folder is not writable (CHMOD 777) this means you cannot upload/manage your videos.</p>
	<p>Please make sure the folder exists and has write permissions.</p>
    <p><b>Path:</b> <?php echo get_option("imagestorage_path")."videos/"; ?>
	</div>
<?php
}

if(strlen(get_option("imagestorage_path")) < 5 || !is_writable(get_option("imagestorage_path")) ){

?>
	<div class="wrap">
	<h1> Image Storage Folder is NOT Writable </h1>
	<p> Your image configuration maybe incorrect and therefore your image storage folder is not writable which means images cannot be saved or edited. </p>
    <p><b>Path:</b> <?php echo get_option("imagestorage_path"); ?></p>
	<p><a href="admin.php?page=setup">Please click here to adjust the settings under Image Configuration.</a></p>
	</div>
<?php

}elseif( isset($_GET['tab']) && $_GET['tab'] == "nw" ) {

if(isset($GLOBALS['error_msg']) && strlen($GLOBALS['error_msg']) > 4){ print "<p style='background:green; color:white; padding:5px;'>".$GLOBALS['error_msg']."</p>"; }

?>
<style>
#adminmenu,#wphead,#footer,.update-nag { display:none;}
#wpbody { margin-left:0px; }
form { padding:40px; }
</style>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<input type="hidden" name"upload" value="1">
<td>
<table width="250px" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
 
<tr>
<td>Select file
<input name="ufile[]" type="file" id="ufile[]" size="50" style="width:200px;" /></td>
</tr>
<tr>
<td>Select file
<input name="ufile[]" type="file" id="ufile[]" size="50" style="width:200px;" /></td>
</tr>
<tr>
<td>Select file
<input name="ufile[]" type="file" id="ufile[]" size="50" style="width:200px;" /></td>
</tr>
<tr>
<td align="center"><input type="submit" class="premiumpress_button" name="Submit" value="Save Images" style="color:#fff;" /></td>
</tr>
</table>
</td>
</form>
<?php 

}else{




















// IMAGE DELETION
if(isset($_GET['del'])){
 $videoTypes = array('flv','avi','3gp','mpg','mpeg','mpe4','mov','m4a','mj2','wmv','mp4','ogg','webm');
if(PREMIUMPRESS_SYSTEM == "MoviePress" && in_array(substr($_GET['del'],-3),$videoTypes) ){
unlink(get_option("imagestorage_path").$_GET['del']);
}else{
unlink(get_option("imagestorage_path").$_GET['del']);
}
 

$GLOBALS['error'] 		= 1;
$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
$GLOBALS['error_msg'] 	= "Image Deleted Successfully";
}












$GLOBALS['image_path'] = get_option("imagestorage_path");
$GLOBALS['image_http'] = get_option("imagestorage_link");




function CheckExt($filename){

$goodexts = array("jpg",'gif','jpeg','bmp','png');

$ext = substr($filename,-3);

if(in_array(strtolower($ext),$goodexts)){
return $filename;
}

switch($ext){

case "flv":{
return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/video.png"; 
} break;
case "tml":{
return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/code.png"; 
} break;
case "doc":{
return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/text.png"; 
} break;
default: { return $GLOBALS['bloginfo_url']."/wp-includes/images/crystal/interactive.png"; 
 };

}
 
return $ext;


}
function read_all_files($root = '.',$keyword=""){ 
  $files  = array('files'=>array(), 'dirs'=>array()); 
  $directories  = array(); 
  $last_letter  = $root[strlen($root)-1]; 
  $root  = ($last_letter == '\\' || $last_letter == '/') ? $root : $root.DIRECTORY_SEPARATOR; 
  
  $directories[]  = $root; 
  
  while (sizeof($directories)) { 
    $dir  = array_pop($directories); 
    if ($handle = opendir($dir)) { 
      while (false !== ($file = readdir($handle))) { 
        if ($file == '.' || $file == '..') { 
          continue; 
        } 
        $file  = $dir.$file; // 
        if (is_dir($file)) { 
		
		 if (strpos($file, "cache") === false) { 
		 
          $directory_path = $file.DIRECTORY_SEPARATOR; 
          array_push($directories, $directory_path); 
          $files['dirs'][]  = str_replace($GLOBALS['image_path'],"",$directory_path); 
		  
		 }else{ }
		  
        } elseif (is_file($file)) { 
		
			if($keyword == ""){
			$files['files'][]  = str_replace($GLOBALS['image_path'],$GLOBALS['image_http'],$file);
			}else{
			
				$pos = strpos(str_replace($GLOBALS['image_path'],"",$file), $keyword);
				if ($pos === false) { }else {
					$files['files'][]  = str_replace($GLOBALS['image_path'],$GLOBALS['image_http'],$file);
				}
			
			}
		
           
        } 
      } 
      closedir($handle); 
    } 
  } 
  
  return $files; 
} 




if(isset($_GET['search'])){ $keyword=$_GET['search']; }else{  $keyword=""; }    
$result = read_all_files($GLOBALS['image_path'],$keyword);
 
 
 
$checkbox=1;
$numPerRow = 30;
$numPics = count($result['files']);
if(!isset($_GET['p'])){ $c_page = 0; }else { $c_page = $_GET['p']*$numPerRow; } 
$dd = ceil($numPics/$numPerRow);

 





















PremiumPress_Header();  ?>





 

<script>
function EditMe(name){
tb_show('', 'media-upload.php?type=image&amp;tab=library&amp;post_id=0&amp;s='+name+'&amp;m=0&amp;TB_iframe=true');
return false;
}

jQuery(document).ready(function() {
jQuery('#upload_image_button').click(function() {
 
 formfield = jQuery('#upload_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

window.send_to_editor = function(html) {
 imgurl = jQuery('img',html).attr('src');
 jQuery('#upload_image').val(imgurl);
 tb_remove();
}

});
</script>












 

<input type="hidden" id="startWidth" value="400"/>
<input type="hidden" id="startHeight" value="300"/>
<input type="hidden" id="minWidth" value="0"/>
<input type="hidden" id="minHeight" value="0"/>
<input type="hidden" id="maxWidth" value="5000" />
<input type="hidden" id="maxHeight" value="5000"/>
<input type="hidden" id="imageQuality" value="100" />
<input type="hidden" id="saveAsLocal" />
<input type="hidden" id="resizeWidth" checked="checked"/>
<input type="hidden" id="resizeHeight" checked="checked"/>
<input type="hidden" id="forceAspect" />
<input type="hidden" id="fullScreen" />
<input type="hidden" id="processInBackground" />

<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_images.png" align="middle"> <?php echo $numPics; ?> Files Found</h3> <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()">Help Me</a>							 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">File Manager</a></li>
    <?php if(defined('MULTISITE') && MULTISITE != false){ ?>
    <li><a rel="premiumpress_tab2" href="#">Upload Files</a></li>
    <?php }else{ ?>
	<li><a rel="premiumpress_tab1" href="javascript:void(0);" id="upload_image_button">Upload Files</a></li>
    <?php } ?>
    
    <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "moviepress" ){ ?>
    <li><a rel="premiumpress_tab4" href="#">Video Manager</a></li>
 	<li><a rel="premiumpress_tab3" href="#">Upload Videos</a></li>
    <?php } ?>
</ul>
</div>

<form method="get" name="SearchForm" action="admin.php" style="padding:5px;">
<input type="hidden" name="page" value="images" />
<input type="hidden" name="p" value="0" />
<input name="search" type="text" style="font-size:18px; width:200px; background:#efefef" id="search">
<input type="submit" style="font-size:18px; background:#a70303; color:white;padding:5px;" value="Search">

</form>

<form class="plain" method="post" name="orderform" id="orderform">
<input type="hidden" name="deleteimages" value="1">
<div id="premiumpress_tab1" class="content">

	<?php 
	
	$hidden_files = array('_tbs.php','txt');
	$thispic=-1;
	for ($i=0; $i < $numPics; $i++){ 
	if($i > $c_page-1 && $i < $c_page+$numPerRow ){
	$currThumb = $thispic + $i + 1; if(strlen($result['files'][$currThumb]) > 1){ 
	
	$a = explode("/",$result['files'][$currThumb]); 	
	$f =count($a);
	
	if(is_array($result['dirs']) && count($result['dirs']) > 0){
		foreach($result['dirs'] as $part){
			$fi = str_replace($part,"",$a[$f-1]);
		}
	}else{
	
	$a = explode("/",$result['files'][$currThumb]);
	
	$fi = $a[count($a)-1];
	
	
	}
	  
	if(!in_array($fi,$hidden_files) && !in_array(substr($fi,-3),$hidden_files)  ){ 
	?>
    
    
	<div style="float:left; width:150px; min-height:190px; margin:4px; margin-top:0px;border:1px solid #dddddd; background:#fff; padding:5px; overflow:hidden;">
	<div style="height:110px; overflow:hidden;">
	<a href="<?php echo $result['files'][$currThumb] ?>" rel="image_group" title="<?php  echo $fi; ?>">
	<img src="<?php echo CheckExt($result['files'][$currThumb]) ?>" style="max-width:150px;" border="0" />
	</a>
	</div>
	<div style="background:#efefef; border:1px solid #ddd; padding:5px; margin-top:8px;"> 
	<input type="checkbox" value="on" name="d<?php echo $checkbox; ?>" id="d<?php echo $checkbox; ?>"/>
	<input type="hidden" name="d<?php echo $checkbox; ?>-id" value="<?php  echo $fi; ?>">
	  <a href="admin.php?page=images&del=<?php  echo $fi; ?>"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/cross.png" align="middle"> Delete</a>
      <a href="javascript:void(0);" onclick="EditMe('<?php echo substr($fi,0,-4); ?>');"><img src="<?php echo $GLOBALS['template_url']; ?>/images/premiumpress/led-ico/pencil.png" align="middle"> Edit</a> </div>
	<small><b><?php  echo $fi; ?></b></small>
	</div>
	<?php $checkbox++; } } } } ?>


<div class="clearfix"></div>

<div class="pagination">
<ul>
<li><b>Showing Page <?php echo $_GET['p']+1; ?> of <?php if($dd ==0){ echo "1"; }else{ echo $dd; }; ?></b></li>

<?php
$pc = $dd;
if($dd > 1){
while($pc > 0){
$pnum = $pc; 
?>
<li><a href="admin.php?page=images&p=<?php echo $pnum-1; ?>"><?php echo $pc; ?></a></li>
<?php $pc--; } } ?>
</ul>
</div>
<label>with selected image do:
<select name="data-1-groupaction"><option value="bigdelete">delete</option></select></label>
<input class="button altbutton" type="submit" value="OK" style="color:white;" />
</form>
</div>








<div id="premiumpress_tab2" class="content">
<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
<tr>
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1">
<input type="hidden" name"upload" value="1">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
 
<tr>
<td>Select file
<input name="ufile[]" type="file" id="ufile[]" size="50" /></td>
</tr>
<tr>
<td>Select file
<input name="ufile[]" type="file" id="ufile[]" size="50" /></td>
</tr>
<tr>
<td>Select file
<input name="ufile[]" type="file" id="ufile[]" size="50" /></td>
</tr>
<tr>
<td align="center"><input type="submit" class="premiumpress_button" name="Submit" value="Save Images" style="color:#fff;" /></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
</div>

<div id="premiumpress_tab3" class="content">

<table width="500" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
 
<script language="javascript">

function ShowDiv()
{
document.getElementById("loading").style.display = '';
}

function check_extension() 
{ 
var allowed = {'flv':1,'avi':1,'3gp':1,'mpg':1,'mpeg':1,'mpe4':1,'mov':1,'m4a':1,'mj2':1,'wmv':1,'mp4':1,'ogg':1,'webm':1};
 var fileinput = document.getElementById("file_vid");
	
	var y = fileinput.value.split(".");
	var ext = y[(y.length)-1];
	ext=ext.toLowerCase();
	
	if (allowed[ext]) {
        document.chooseF.confirm.disabled = false;
		return true;
      } else {
        alert("This is an unsupported file type. Supported files are: 3gp,avi,mpg,mpeg,mpe4,mov,m4a,mj2,flv,wmv,mp4,ogg,webm");
        document.chooseF.confirm.disabled = true;
		return false;
      }


}


</script>


<form name="choose" action="admin.php?page=images" method="post" enctype="multipart/form-data" style="padding:20px;">
<input type="hidden" name="action" value="upVideo" />
<?php if(!extension_loaded("ffmpeg")){ ?><input type="hidden" name="type" value="none" /><?php } ?>
<tr>
  <td> <input type="file" name="file_vid" id="file_vid" onchange="check_extension();" class="small-input" style="width: 240px;  font-size:14px;"/> 
  </td>
  <td>&nbsp;</td>
</tr> 
 <?php if(extension_loaded("ffmpeg")){ ?>   
<tr>
  <td>
  
  <p><b>Convert File to:</b></p> <br />
    <select name="type" class="small-input" style="width: 240px;  font-size:14px;">
      <option value="none" >do not convert</option>
      <option value="flv" >flv</option>
      <option value="mp4" >mp4</option>
      <option value="wmv" >wmv</option>
      <option value="avi" >avi</option>
     <!-- <option value="mp3" >mp3</option>
      <option value="ogg" >ogg</option>
      <option value="webm" >webm</option>-->
    </select></td>
  <td>
  
  <p><b>Video quality:</b></p> <br />
    <select name="quality" class="small-input" style="width: 240px;  font-size:14px;">
      <option value="1000000" >high</option>
      <option value="800000">medium</option>
      <option value="450000">low</option>
    </select></td>
</tr>  
 
<tr>
  <td>
  
  <p><b>Audio quality:</b></p> <br />
    <select name="audio" class="small-input" style="width: 240px;  font-size:14px;">
      <option value="44100" >high</option>
      <option value="22050">medium</option>
      <option value="11025">low</option>
    </select></td>
  <td>
  
  <p><b>Video size:</b></p> <br />
    <select name="size" class="small-input" style="width: 240px;  font-size:14px;">
      <option value="320x240">320x240</option>
      <option value="512x384" selected="selected">512x384</option>
      <option value="640x360">640x360</option>
      <option value="854x480">854x480</option>
      <option value="1280x720">1280x720</option>
    </select></td>
</tr>  

<?php } ?>
 
<tr>
  <td align="center">&nbsp;</td>
<td align="center"><input type="submit" class="premiumpress_button" name="Submit" value="Save Images" style="color:#fff;" /></td>
</tr>
</form>
</table>

</div>

<div id="premiumpress_tab4" class="content">

<?php if(is_array($videos)){ foreach($videos as $vid){ if(strlen($vid) > 3){ ?>

<div style="margin:5px; padding:5px; font-size:15px; background:#efefef; border:1px solid #ddd; width:390px; float: left; "><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/moviepress.png" align="middle" /> <b><?php echo $vid; ?></b> - <a href="<?php echo $GLOBALS['videoPath'].$vid; ?>" target="_blank">watch</a> | <a href="admin.php?page=images&dvid=<?php echo $vid; ?>">delete </a> </div>
<?php } } } ?>

<div class="clearfix"></div>  
</div>
<div id="premiumpress_tab5" class="content"></div>
<div id="premiumpress_tab6" class="content"></div>            
					 
                        
</div>
</div>
<div class="clearfix"></div>  

 

<?php } ?>