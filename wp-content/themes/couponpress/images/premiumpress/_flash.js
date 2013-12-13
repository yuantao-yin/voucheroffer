var DEFAULTBACKGROUNDCOLOR="#FFFFFF";
var DEFAULTFLASHVERSION="6,0,47,0";
var DEFAULTQUALITY="high";
var DEFAULTALIGNMENT="left";
var DEFAULTMENU="true";
var DEFAULTNAME="flash";

function DisplayPremiumPress(swf,width,height,params,pairs) {
	
	var flashvars="";
	var writeAmp=false;
	for(var i in pairs){
		if (writeAmp){flashvars=flashvars+"&";}else{writeAmp=true;}
	
		if(window.encodeURIComponent){
			flashvars=flashvars+i+"="+encodeURIComponent(pairs[i]);
		}else{ 
	  		flashvars=flashvars+i+"="+escape(pairs[i]);
		}

	}
	
	if(!params){
		params = new Object();
	}
	if(!params.version) {
		params.version=DEFAULTFLASHVERSION;
	}
	if(!params.align){
		params.align=DEFAULTALIGNMENT;
	}
	if(!params.bgcolor){
		params.bgcolor=DEFAULTBACKGROUNDCOLOR;
	}
	if(!params.quality){
		params.quality=DEFAULTQUALITY;
	}
	if(!params.menu){
		params.menu=DEFAULTMENU;
	}
	if(!params.name){
		params.name=DEFAULTNAME;
	}
	if(!params.flashvars){
		params.flashvars=flashvars;
	}
	
	if(parseInt(params.version.substring(0,1))<6){
		swf=swf+"?"+params.flashvars;
		params.flashvars="";
	}
	
	var objectParams = "";
	var embedParams = "";
	for(var i in params) {
		if(i!="version" && i!="align" && i!="name"){
			objectParams += "<PARAM NAME="+i+" VALUE=\""+params[i]+"\">\n";
			embedParams += i+"=\""+params[i]+"\" ";
		}
	}
	document.write("<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" ");
	document.write("codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version="+params.version+"\"");
	document.write("WIDTH=\""+width+"\" HEIGHT=\""+height+"\" id=\""+params.name+"\" ALIGN=\""+params.align+"\">\n");
	document.write("<PARAM NAME=movie VALUE=\""+swf+"\">\n"); 
	document.write("<PARAM NAME=wmode VALUE=transparent>\n"); 
	document.write(objectParams);
	document.write("<EMBED src=\""+swf+"\" WIDTH=\""+width+"\" HEIGHT=\""+height+"\" name=\""+params.name+"\" ALIGN=\""+params.align+"\" " );
	document.write(embedParams);
	document.write(" TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/go/getflashplayer\"></EMBED>");
	document.write("</OBJECT>");
}
