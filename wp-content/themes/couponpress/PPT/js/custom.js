/*
 GALLERY IMAGE SWITCHED (THUMB/LIST)
*/
jQuery(document).ready(function() { 
	jQuery("a.switch_thumb").toggle(function(){										
		jQuery(this).addClass("swap");
		jQuery("ul.display").fadeOut("fast", function() {
		jQuery(this).fadeIn("fast").addClass("thumb_view");
		});		
		}, function () {
		jQuery(this).removeClass("swap");
		jQuery("ul.display").fadeOut("fast", function() {
		jQuery(this).fadeIn("fast").removeClass("thumb_view");
		});
		
	}); 		
 
}); 
/*
LAYER TOGGLE FUNCTION
*/
function toggleLayer( whichLayer )
{
  var elem, vis;
  if( document.getElementById ) 
    elem = document.getElementById( whichLayer );
  else if( document.all ) 
      elem = document.all[whichLayer];
  else if( document.layers ) 
    elem = document.layers[whichLayer];
  vis = elem.style;
 
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}
/*
QTY CHECKER
*/
function CheckRemaindingQty(total){

	FindQty = jQuery("#CustomQty").text();
	if(FindQty ==""){ FindQty=1; }
	
	ThisQty = document.getElementById('QtyTotal').value; 
	Remain = FindQty*1 + ThisQty*1;
	
	document.getElementById('QtyTotal').value = Remain ;
	 
	if( (  total*1 - Remain*1  ) < 1){ 
	
	//document.getElementById('QtyTotal').disabled=true; 
	 
	} 

}


// VALIDATE BIDDING PRICE
function CheckBidValue(price,text, minval){
	 
  var ValidChars = "0123456789.";
  var IsNumber=true;
  var Char;

  if(price ==''){alert(text);return false;} 	
	
   for (i = 0; i < price.length && IsNumber == true; i++){ 
   
      Char = price.charAt(i); 
      if (ValidChars.indexOf(Char) == -1){
		  
         alert(text);
		 return false;
		 
         }		 
     }

	if(price <= minval){
		alert(text);
		return false;
	}
	
	
	return true;
	
}

function CheckMessageData(a,b,c,text){
	 
	if(a =='' || b =='' || c ==''){
	
		alert(text);
		return false;
	
	}
	
	return true;
	
	
}

function countWords(heyslay){
	heyslay=heyslay.split("\n").join(" ");
	chocolate=heyslay.split(" ");
	heyslay=0;
	for(da=0;da<chocolate.length;da++){
		if(chocolate[da].length>0){
			heyslay++;
		}
	}
	return heyslay;
}
