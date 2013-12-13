<script>
function openit(){
	var curr = document.getElementById('openit');
	var curr2 = document.getElementById('closeit');
	if ( curr.style.display == 'none' ) {
		curr.style.display = 'block';
	} else if ( curr.style.display == 'block' ) {
		curr.style.display = 'none';
	}
	
	curr2.style.display = 'none';
}
function closeit(){
	var curr = document.getElementById('openit');
	var curr2 = document.getElementById('closeit');
	if ( curr.style.display == 'block' ) {
		curr.style.display = 'none';
	}
	curr2.style.display = 'block';
}
</script>
<?php
global $current_user;
?>	
	<table cellspacing="1" cellpadding="1" width="100%" border="0" style="border:1px solid #CCCCCC;margin-top:5px;">
	  <tr>
	    <td valign="top" style="background-color:#FFFEEB" >
		  <div style="padding:5px 2px 5px 15px " >
		   <small style="font-size:xx-small">
		   <strong>If you find this plugin useful then we'll appreciate a review post for this plugin in your blog.</strong> <div align="right"><a style="cursor:pointer" onclick="openit()" id="closeit"><strong>Read more..</strong></a></div><br> <span id="openit" style="display:none;">This will help lots of other people know about the plugin and get benefited by it. You can even earn by spreading the word for this plugin. <a href="http://www.maxblogpress.com/affiliates/" target="_blank" style="text-decoration:none">Learn More...</a> <div><br> Thanking you, <br><br> <strong>MaxBlogPress Team</strong></div>  <div id="finalclose" align="right"><a  style="cursor:pointer" onclick="closeit()" ><strong>Close</strong></a></div></span> 
		    </small>
		  </div>
	    </td>
	  </tr>
</table>
<br>
	<table cellspacing="3" cellpadding="5" width="100%" border="0" style="border:1px solid #C3D9FF">
	  <tr>
	    <td valign="top" style="background-color:#e8eefa" >
		   <div style="padding:5px 0px 0px 15px "><strong>Help</strong></div>
		  <div style="padding:5px 2px 5px 15px " >
			<ul>
				<li><a href="http://help.maxblogpress.com/spaces/maxblogpress/manuals/2175" target="_blank"  style="text-decoration:none">Online Documentation</a></li>
				<li><a href="http://community.maxblogpress.com" target="_blank"  style="text-decoration:none">Community</a></li>
			</ul>
		  </div>
		<div style="padding:10px 0px 0px 15px"><strong>MaxBlogPress Products</strong></div>
			<div style="padding:5px 2px 5px 15px">
<ul>
		<li><a href="http://www.maxblogpress.com/subscribersmagnet/" style="text-decoration:none" target="_blank">MaxBlogPress Subscribers Magnet</a></li>
	  <li><a href="http://www.mbpninjaaffiliate.com/" target="_blank" style="text-decoration:none">MaxBlogPress Ninja Affiliate</a></li>
	  <li><a href="http://www.maxblogpress.com/wordpresswizard20/" target="_blank" style="text-decoration:none">Wordpress Wizard 2.0</a></li>
	  <li><a href="http://www.maxblogpress.com/365k/" target="_blank" style="text-decoration:none">The $365K Blog Traffic Formula</a></li>
  </ul>
			</div>			
		<div style="padding:10px 0px 0px 15px"><strong>Get Connected With MaxBlogPress</strong></div>
			<div style="padding:5px 2px 5px 15px">
				<ul>
				  <li><a href="http://www.maxblogpress.com/facebook" target="_blank" style="text-decoration:none"><img border="0" src="<?php echo MBP_PO_INCPATH;?>images/facebook_icon.jpg" width="20" height="20" />&nbsp;Facebook</a></li>
				  <li><a href="http://www.maxblogpress.com/twitter" target="_blank" style="text-decoration:none"><img border="0" src="<?php echo MBP_PO_INCPATH;?>images/TwitterIcon.png" width="20" height="20" />&nbsp;Twitter</a></li>
				</ul>
		  </div>			
	    </td>
	  </tr>
</table>
	<br>

<script type="text/javascript"><!--
		function mbp_subscribers_magnet_subscribers(name,email) {
			var name_fld = document.getElementById('ofaValidateFormW' + '_' + name);
			var email_fld = document.getElementById('ofaValidateFormW' + '_' + email);
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			var msg = '';
			if ( name_fld.value == '' ) msg = '- Name Required\n';
			if ( reg.test(email_fld.value) == false ) msg += '- Valid Email Required';
			if ( msg == '' ) return true;
			else alert(msg);
			return false;
		}//-->
</script>
	<table cellspacing="3" cellpadding="5" width="100%" border="0" style="border:1px solid #80C65A">
	  <tr>
	    <td valign="top" style="background-color:#DDF8CC" >
	      <div align="center"><span style="font-size:14px;padding:10px 0px 0px 15px"><strong>Subscribe to MaxBlogPress Newsletter</strong></span></div><br>
		  <div align="center"><span style="font-size:11px;padding:10px 0px 0px 15px">Get insider tips on how to make more money from your blog as well as how to bring thousands of new visitors to your blog for free.</span></div>
		  <div style="padding:5px 2px 5px 15px " >
		  <form action="http://www.aweber.com/scripts/addlead.pl" method="post" onsubmit="return mbp_subscribers_magnet_subscribers('name','from')"><input type="hidden" name="meta_web_form_id" value="1913417573"><input type="hidden" name="meta_split_id" value=""><input type="hidden" name="unit" value="maxbp"><input type="hidden" name="redirect" value="http://www.maxblogpress.com/almost-done-max/"><input type="hidden" name="meta_redirect_onlist" value="http://www.maxblogpress.com/subscribe-thank-you/"><input type="hidden" name="meta_adtracking" value="mbp-plugin-sidebar"><input type="hidden" name="meta_message" value="1"><input type="hidden" name="meta_required" value="name,from"><input type="hidden" name="meta_forward_vars" value="0"><div align="center" style="padding:6px;;">	
				<table align="center" border="0" cellpadding="1" cellspacing="0">
				<tr>
					<td align="right">Name:</td>
					<td><input  type="text" name="name" id="ofaValidateFormW_name" size="25" value="<?php echo $current_user->user_nicename;?>" />  </td>
				</tr>
				<tr>
					<td align="right">Email:</td>
					<td><input  type="text" name="from"  id="ofaValidateFormW_from" size="25" value="<?php echo $current_user->user_email;?>" 	/>  </td>
				</tr>
				<tr>
					<td>  </td>
					<td align="left"><input name="submit" type="submit" style="border:1px solid #aaaaaa; border-right-width:2px; border-bottom-width:2px; background-color:#787878; color:#ffffff; font-weight:normal" value="Subscribe Me" /></td>
				</tr>
				<tr><td  colspan="2">
				<small style="font-size: xx-small"><i>Your contact information will be handled with the strictest confidence and will never be sold or shared with third parties</i></small>
				</td></tr>
				</table>
		</form>
		</div> 
		</td>
	  </tr>
	</table>
<!--End of email subscribers-->
