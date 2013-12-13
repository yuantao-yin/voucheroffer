function AjaxRequest()
{
	this.mRequest = this.getHttpRequest();
	this.mHandlers = new Array();
	var self = this;
	
	this.mRequest.onreadystatechange = function()
	{
		if(	self.mHandlers[ self.mRequest.readyState ] != undefined )
		{
			for( i = 0 ; i < self.mHandlers[ self.mRequest.readyState ].length ; i++ )
			{
				self.mHandlers[ self.mRequest.readyState ][ i ]( self );				
			}
		}
	}
}

AjaxRequest.prototype.addEventListener = function( pEventType, pFunction )
{
	if(	this.mHandlers[ pEventType ] == undefined )
	{
		this.mHandlers[ pEventType ] = new Array();
	}
	
	this.mHandlers[ pEventType ].push( pFunction );
}

AjaxRequest.prototype.getHttpRequest = function()
{
	// List of Microsoft XMLHTTP versions - newest first

	var MSXML_XMLHTTP_PROGIDS = new Array
	(
		'MSXML2.XMLHTTP.5.0',
		'MSXML2.XMLHTTP.4.0',
		'MSXML2.XMLHTTP.3.0',
		'MSXML2.XMLHTTP',
		'Microsoft.XMLHTTP'
	);

	// Do we support the request natively (eg, Mozilla, Opera, Safari, Konqueror)

	if( window.XMLHttpRequest != null )
	{
		return new XMLHttpRequest();
	}
	else
	{
		// Look for a supported IE version

		for( i = 0 ; MSXML_XMLHTTP_PROGIDS.length > i ; i++ )
		{
			try
			{
				return new ActiveXObject( MSXML_XMLHTTP_PROGIDS[ i ] );
			}
			catch( e )
			{
			}
		}
	}
	
	return( null );
}

function PremiumPressDo( fileName, div )
{
	var Ajax = new AjaxRequest();

	if( Ajax.mRequest )
	{				
		Ajax.mFileName 	= fileName;		
		var obj = document.getElementById(div);				

		Ajax.mRequest.open( "GET", fileName);
		Ajax.mRequest.onreadystatechange = function() {
			if(Ajax.mRequest.readyState == 4 && Ajax.mRequest.status == 200){
				obj.innerHTML = Ajax.mRequest.responseText;
			}
		}		
	}
	Ajax.mRequest.send( null );
}



function add_image_next(current,path,ilink,type){
	 
var searchword = document.getElementById('searchBox1').value;
var a =ilink;
a=a.split("http://").join("");
PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=add_image_next&p='+current+'&path='+path+'&link='+a+'&type='+type+'&search='+searchword,'DisplayImages');
	
}
function add_video_next(current,path,ilink,type){
	 
var searchword = document.getElementById('searchBox1').value;
var a =ilink;
a=a.split("http://").join("");
PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=add_video_next&p='+current+'&path='+path+'&link='+a+'&type='+type+'&search='+searchword,'DisplayImages');
	
}
function PremiumPressChangeStateMyAccount(currentCountry,currentState){
 
PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changestate&current='+currentCountry+'&cstate='+currentState,'PremiumPressState');
	
}
function PremiumPressChangeState(current){
 
PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changestate&current='+current,'PremiumPressState');
	
}
function PremiumPressChangeStateShipping(current){

PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changestate&current='+current+'&ship=1','PremiumPressStateShipping');
	
}
function CatPriceBox(current){
 
PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=catprice&current='+current,'PPT-catpricebox');
	
}

function CatTextBox(current){
 
PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=cattext&current='+current,'PPT-cattext');
	
}

function UpdateSliderItem(){ 

	var eid = document.getElementById('ppsedit').value;
	var s1 = document.getElementById('pps1').value;
	var s2 = document.getElementById('pps2').value;
	var s3 = document.getElementById('pps3').value;
	var s4 = document.getElementById('pps4').value;
	var s5 = document.getElementById('pps5').value;
	var s6 = document.getElementById('pps6').value;
	
	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=updateSlider&s1='+s1+'&s2='+s2+'&s3='+s3+'&s4='+s4+'&s5='+s5+'&s6='+s6+'&eid='+eid,'PPT-sliderbox');

}	

function DeleteSliderItem(thisone){
	 
	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=deleteSlider&eid='+thisone,'PPT-sliderbox');
		
}

function EditsliderItem(thisone){
	
	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=editSlider&eid='+thisone,'PPT-sliderbox');
	
	
}

function addiCodes(catid, title, desc, url, code, s5,s6,s7,status,img,starts,merchant){


	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=icodes&type='+s7+'&catid='+catid+'&title='+title+'&desc='+desc+'&url='+url+'&code='+code+'&exp='+s5+'&mer='+s6+'&st='+status+'&img='+img+'&starts='+starts+'&merchant='+merchant,'ShopperPressAlert');
}

 


function icodesList(a1){

	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=icodesList&type='+a1,'icodesList');
}

function changeProperty(a1,div){

	PremiumPressDo('wp-content/themes/couponpress/PPT/ajax/actions.php?action=changeProperty&d='+a1,div);
}

function changeProperty1(a1,div){

	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changeProperty&d='+a1,div);
}


function UserActionForm(val,val1,act,div,cap,cap1){

	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=UserActionForm&act='+act+'&data='+val+'&data1='+val1+'&cap='+cap+'&cap1='+cap1,div);
}











// SHOPPERPRESS CART OPTIONS

  function addProduct(SessionID, siteurl, ui, clicked, clickparts, ajaxURL, error_text, ok_text) {


            // ASSIGN DEFAULTS
            productextra = 0;
            productprice = 0;
            productqty   = 1;
            productshipping =0;

			// this is for wordpress featured page buy links on a 2 tier dynamic url /88/88/
			if(ajaxURL ==1){	mydomain ="../../";		}
            
            //calculate & update new cart total
			varcarttotal = jQuery("#carttotal").text();
  	 		
			prodparts = clickparts.split("|");			
			
			productid = prodparts[0];
			productname = prodparts[1];
			productprice = prodparts[2]; 
		  
            
            // CUSTOM FIELDS
            CustomField1 = jQuery("#CustomField_1").text();
            CustomField2 = jQuery("#CustomField_2").text();
            CustomField3 = jQuery("#CustomField_3").text();
            CustomField4 = jQuery("#CustomField_4").text();
            CustomField5 = jQuery("#CustomField_5").text();
            CustomField6 = jQuery("#CustomField_6").text();
            CustomField7 = jQuery("#CustomField_7").text();
           
            CustomField1_Required = jQuery("#CustomField_1_required").text();
            CustomField2_Required = jQuery("#CustomField_2_required").text();
            CustomField3_Required = jQuery("#CustomField_3_required").text();
            CustomField4_Required = jQuery("#CustomField_4_required").text();
            CustomField5_Required = jQuery("#CustomField_5_required").text();
            CustomField6_Required = jQuery("#CustomField_6_required").text();
            CustomField7_Required = jQuery("#CustomField_6_required").text();
             
            if(CustomField1_Required ==1 && CustomField1 == ""){ alert(error_text); return false; }
            if(CustomField2_Required ==1 && CustomField2 == ""){ alert(error_text); return false; }
            if(CustomField3_Required ==1 && CustomField3 == ""){ alert(error_text); return false; }
            if(CustomField4_Required ==1 && CustomField4 == ""){ alert(error_text); return false; }
            if(CustomField5_Required ==1 && CustomField5 == ""){ alert(error_text); return false; }
            if(CustomField6_Required ==1 && CustomField6 == ""){ alert(error_text); return false; }
            if(CustomField7_Required ==1 && CustomField6 == ""){ alert(error_text); return false; }
            
            if( CustomField3 == ""){  CustomField3=0; }
            if( CustomField6 == ""){  CustomField6=0; }
            
            // custom qty			
			FindQty = jQuery("#CustomQty").text();				
			if(FindQty != ""){ productqty = FindQty; }		
			if(FindQty  == ""){ FindQty =1; }
            
            // custom shipping	
            FindShip = jQuery("#CustomShipping").text();	
            if(FindShip != ""){ productshipping = FindShip;} 	
            
            // custom extra	 price options,
            // custom fields 3 and 6 can adjust the item price				 	
			if(CustomField3 != ""){productextra += parseFloat(CustomField3*FindQty); }  
			if(CustomField6 != ""){productextra += parseFloat(CustomField6*FindQty); }
			  
			//remove any commas
			varcarttotal = varcarttotal.replace(',', '');
			newtotal = parseFloat(varcarttotal)+ ( parseFloat(productprice) * parseFloat(productqty) +parseFloat(productextra) );
			
			newtotal = Math.round(newtotal*100)/100;
			newtotal = newtotal.toFixed(2);
			 
			
			jQuery("#carttotal").text(newtotal);
			jQuery.get(siteurl+"PPT/ajax/updatecart.php", { sid: SessionID, newprice: newtotal } );

			//calculate and update product qty
			varcartqty = jQuery("#cartqty").text();           
             
			if(varcartqty ==""){ varcartqty=0; }
 
			newqty = parseFloat(varcartqty)+parseFloat(productqty);
			jQuery("#cartqty").text(newqty);
			jQuery.get(siteurl+"PPT/ajax/updatecart.php", { sid: SessionID, action: "increaseqty" } );
			
			if(jQuery("#ddcproduct"+productid).text()!="") {

				//already in list, just increase qty
				newprodqty = parseFloat(jQuery("#ddcproduct"+productid).attr("qty"))+1;
				jQuery("#ddcproduct"+productid).attr("qty", newprodqty);
				jQuery("#ddcproduct"+productid+" > #cell2").html(newprodqty);

			}
								 
			jQuery.get(siteurl+"PPT/ajax/updatecart.php", { sid: SessionID, action: "addproduct", id: productid, qty: productqty, ship:productshipping, c1:CustomField1, c2:CustomField2, c3:CustomField3,  c4:CustomField4, c5:CustomField5, c6:CustomField6,c7:CustomField7 } ); //, size: productsize, color: productcolor,   extra:productextra
             
	jQuery('#stockdisplay').hide();			 
	jQuery('#BUYBUTTON').addClass("added");
	jQuery('#BUYBUTTON').text(ok_text);
            
  
  }
  
  
  
  
   function increaseQty(SessionID, siteurl, productid, shippingCost, max) {

 
	current = parseFloat(jQuery("#CheckoutQty"+productid).text());
  
	if(current >  max){
		alert("You have reached the maximum quantity for this product.");
		return;
	}

	//just decrease qty
	productname = jQuery("#ddcproduct"+productid).attr("productname");
	newprodqty = parseFloat(jQuery("#ddcproduct"+productid).attr("qty"))+1;    
    
	jQuery("#ddcproduct"+productid).attr("qty", newprodqty);
	jQuery("#CheckoutQty"+productid).html(newprodqty);	
	
	//calculate and update product qty
	varcartqty = jQuery("#cartqty").text();
     
	newqty = parseFloat(varcartqty)+1;
 
	//protect against going below zero.
	if(newqty < 0) newqty = 0;
	
	jQuery("#cartqty").text(newqty);
	
	//update session item list 
	jQuery.get(siteurl+"PPT/ajax/updatecart.php", { sid: SessionID, action: "increaseQty", id: productid } );
	

	//update price
	varcarttotal = jQuery("#CheckoutSubTotal").val();
 
	newtotal = parseFloat(varcarttotal)+parseFloat(jQuery("#ddcproduct"+productid).attr("price"));
     
	newtotal = Math.round(newtotal*100)/100;
	newtotal = newtotal.toFixed(2);

     
	//protect against going below zero.
	if(newtotal < 0) newtotal = 0;
	
	jQuery("#carttotal_text").text(newtotal);
	jQuery("#CheckoutSubTotal_text").text(newtotal);
	jQuery("#CheckoutSubTotal").val(newtotal);

	withShipping = parseFloat(newtotal) + parseFloat(shippingCost);
 	jQuery("#CheckoutTotal").text(withShipping.toFixed(2));
	jQuery.get(siteurl+"PPT/ajax/updatecart.php", { sid: SessionID, newprice: newtotal } );	
	
  }
  
 
  
  
  
  
  
    function removeProduct(SessionID,siteurl,productid,shippingCost) {

  	//if qty = 1, fade out item
 
	if(parseFloat(jQuery("#ddcproduct"+productid).attr("qty"))==1) {

		//fade out & remove item entirely
		jQuery("#ddcproductCheckoutt"+productid).fadeOut(500, function() {
			jQuery("#ddcproduct"+productid).remove();
		});

	} else {
		//just decrease qty
		productname = jQuery("#ddcproduct"+productid).attr("productname");
		newprodqty = parseFloat(jQuery("#ddcproduct"+productid).attr("qty"))-1;
		jQuery("#ddcproduct"+productid).attr("qty", newprodqty);
		jQuery("#CheckoutQty"+productid).html(newprodqty);
	}
	
	//calculate and update product qty
	varcartqty = jQuery("#cartqty").text();
	newqty = parseFloat(varcartqty)-1;
	
	//protect against going below zero.
	if(newqty < 0) newqty = 0;
	
	jQuery("#cartqty").text(newqty);
	
	//update session item list 
	jQuery.get(siteurl+"PPT/ajax/updatecart.php", { sid: SessionID, action: "removeproduct", id: productid } );
	
	//update price
	var ShoppingCartTotal = jQuery("#CheckoutSubTotal").val();    
    var RemovingProductPrice = jQuery("#ddcproduct"+productid).attr("price");
   
	newtotal =  parseFloat(ShoppingCartTotal) - parseFloat(RemovingProductPrice);
  
	//newtotal = Math.round(newtotal*100)/100;
	//newtotal = parseFloat(ShoppingCartTotal) - newtotal.toFixed(2);
     
	//protect against going below zero.
	if(parseFloat(newtotal) < 0) newtotal = 0;
 
 
	//jQuery("#carttotal_text").text(newtotal);
	jQuery("#CheckoutSubTotal_text").text(newtotal);

	//withShipping = parseFloat(newtotal) + parseFloat(shippingCost);
 	//jQuery("#CheckoutTotal").text(withShipping.toFixed(2));
	jQuery.get(siteurl+"PPT/ajax/updatecart.php", { sid: SessionID, newprice: newtotal } );	
	
  }
  
  
  
  
  
  
  
  
  

function ChangeCustomQty(val){

  document.getElementById('CustomQty').innerHTML = val;

}

function ChangeCustomShip(val){

 document.getElementById('CustomShipping').innerHTML = val;
}

 

function ChangeCustomFieldValue(val,divName){
 
 document.getElementById(divName).innerHTML = val;

}

function CheckoutAlert(text, CheckoutLink){

var answer = confirm (text)
if (answer)
window.location= CheckoutLink;

}




function ShopperPress_ChangeStateWithValue(current){
	
	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changestate&current='+current+'&val=100','ShopperPressState');
	
}
function ShopperPress_ChangeState1(current){
	
	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changestate&current='+current,'ShopperPressState');
	
}

function ShopperPress_ChangeState(current){
	
	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changestate&current='+current,'ShopperPressState');
	
}

function ShopperPress_ChangeState_Value(current){
	
	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=changestatevalue&current='+current,'ShopperPressState_amount');
	
}



function addAmazonProduct(catid, country, asnid){

	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=addAmazonProduct&country='+country+'&catid='+catid+'&asnid='+asnid,'ShopperPressAlert');
}
function addEbayProduct(catid, country, ID, api, aff1, aff2){

	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=addEbayProduct&country='+country+'&catid='+catid+'&ID='+ID+'&API='+api+'&aff1='+aff1+'&aff2='+aff2,'ShopperPressAlert');
}
function UpdateWishlist(itemID,action){

	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=WishList&itemID='+itemID+'&act='+action,'WishlistAlert');
}
function JobWatchList(itemID,action){

	PremiumPressDo('wp-content/themes/couponpress/PPT/ajax/actions.php?action=JobWatchList&itemID='+itemID+'&act='+action,'WishlistAlert');
}

function addMovie(catid, ID, NETWORK){

	PremiumPressDo('../wp-content/themes/couponpress/PPT/ajax/actions.php?action=addMovie&catid='+catid+'&ID='+ID+'&network='+NETWORK,'ShopperPressAlert');
}


