<script type="text/javascript" src="<?php echo MBP_PO_INCPATH;?>tooltip.js"></script>
<link href="<?php echo MBP_PO_INCPATH;?>tooltip.css" rel="stylesheet" type="text/css">
<script><!--//
function mbpoShowHide(Div,Img) {
	var divCtrl = document.getElementById(Div);
	if ( Img != '' ) var theImg = document.getElementById(Img);
	if ( divCtrl.style == "" || divCtrl.style.display == "none" ) {
		divCtrl.style.display = "block";
		 if ( Img != '' ) theImg.src = '<?php echo MBP_PO_INCPATH;?>images/arr_green2.gif';
	} else if ( divCtrl.style != "" || divCtrl.style.display == "block" ) {
		divCtrl.style.display = "none";
		 if ( Img != '' ) theImg.src = '<?php echo MBP_PO_INCPATH;?>images/arr_green1.gif';
	}
}//--></script>
<div class="wrap">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td width="65%">
 <?php $this->mbpoHeader();?>
 <h3><?php _e('URIs to Ping', 'mbpo'); ?></h3>
 <p>The following services will automatically be pinged when you publish new posts or drafts.  
 <strong>Not</strong> when you publish future posts or edit previously published posts, as WordPress does by default.</p>
 <p><strong>NB:</strong> This list is synchronized with the <a href="options-writing.php" target="_blank">original update services list</a>.</p>
 <form method="post">
 <p><?php _e('Separate multiple service URIs with line breaks:', 'mbpo'); ?><br />
 <textarea name="mbpo[uris]" cols="60" rows="10"><?php echo $this->mbpo_ping_sites;?></textarea></p>
 <p><input type="checkbox" name="mbpo[ping]" id="ping_checkbox" value="1" <?php echo $ping_enable_chk;?> /> Enable pinging <a onmouseout="hidetooltip();" onmouseover="tooltip('Please check this option to enable pinging in your blog when you add new post .',360)" href="#"><img border="0" src="<?php echo MBP_PO_INCPATH;?>images/help.gif" /></a></p>
 <p>
 <input type="checkbox" name="mbpo[limit_ping]" id="limit_ping" value="1" <?php echo $limit_ping_chk;?> onclick="mbpoShowHide('limit_ping_dv','')" /> Limit excessive pinging in short time
 <span id="limit_ping_dv" style="display:<?php echo $limit_ping_display;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ping at most <input type="text" name="mbpo[limit_number]" value="<?php echo $this->mpo_options['limit_number'];?>" style="width:25px" maxlength="5" /> time(s) within 
 <input type="text" name="mbpo[limit_time]" value="<?php echo $this->mpo_options['limit_time'];?>" style="width:25px" maxlength="5" /> minute(s)</span>
 <a onmouseout="hidetooltip();" onmouseover="tooltip('Please set the time limit and ping duration of your blog to prevent it from excessive pinging .',360)" href="#"><img border="0" src="<?php echo MBP_PO_INCPATH;?>images/help.gif" /></a></p>
 <p>
 <input type="submit" name="mbpo[save]" value="<?php _e('Save Settings', 'mbpo'); ?>" class="button" />
 <input type="submit" name="mbpo[pingnow]" value="<?php _e('Ping Now', 'mbpo'); ?>" class="button" onclick="return confirm('Are you sure you want to ping these services now? Pinging too often could get you banned for spamming.');" />
 </p>
 </form><br />
 <?php if ( MBPO_LOG == true ) { ?>
 <h3><?php _e('Ping Log', 'mbpo'); ?></h3>
 <p><strong><?php _e('Following are the lastest actions performed by the plugin:', 'mbpo'); ?></strong>
 <?php 
 list($pinglog,$exists) = $this->mbpoGetLogData();
 echo $pinglog;
 ?>
 </p>
 <?php if($exists){?><p><a href="?page=<?php echo MBP_PO_PATH;?>&d=yes" onclick="return confirm('All ping log data will be deleted. Are you sure?')" style="color:#FF0000;font-weight:bold"><img src="<?php echo MBP_PO_INCPATH;?>images/delete.gif" border="0" align="absmiddle"> Clear Log</a></p><?php }?>
 <?php } ?><br/><br/>
 <div style="padding:10px 2px 2px 8px;border-top:1px solid #C9DCEC">
	 <script type="text/javascript" charset="utf-8">
	  var is_ssl = ("https:" == document.location.protocol);
	  var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
	  document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	
	<script type="text/javascript" charset="utf-8">
	  var feedback_widget_options = {};
	
	  feedback_widget_options.display = "inline";  
	  feedback_widget_options.company = "maxblogpress";
	  
	  
	  feedback_widget_options.style = "idea";
	  feedback_widget_options.product = "maxblogpress_maxblogpress_ping_optimizer";
	  
	  
	  feedback_widget_options.limit = "3";
	  
	  GSFN.feedback_widget.prototype.local_base_url = "http://community.maxblogpress.com";
	  GSFN.feedback_widget.prototype.local_ssl_base_url = "http://community.maxblogpress.com";
	  
	
	  var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
	</script>
 </div>
 <?php $this->mbpoFooter();?>	 
	 </td>
     <td width="35%" valign="top">
	 	<?php include_once('mbp-support-pg.php');?>
	 </td>
   </tr>
 </table>

</div>
