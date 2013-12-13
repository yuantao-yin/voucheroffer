<?php
 // Eway payment in PHP
 $pathvalue="http://www.ewaypayment.com.php5-2.dfw1-2.websitetestlink.com/php/";
   
        $ewayurl.="?CustomerID=".$_POST['CustomerID'];
		$ewayurl.="&UserName=".$_POST['username'];
		$ewayurl.="&Amount=".$_POST['Amount'];
		$ewayurl.="&Currency=GBP";
		$ewayurl.="&PageTitle="."";
	    $ewayurl.="&PageDescription="."";
		$ewayurl.="&PageFooter="."";	
		$ewayurl.="&Language=EN";
		$ewayurl.="&CompanyName=".$_POST['company'];
		$ewayurl.="&CustomerFirstName=".$_POST['n1'];
	    $ewayurl.="&CustomerLastName=".$_POST['n2'];	
		$ewayurl.="&CustomerAddress=".$_POST['n4'];
		$ewayurl.="&CustomerCity=".$_POST['n7'];
		$ewayurl.="&CustomerState=".$_POST['n9'];
		$ewayurl.="&CustomerPostCode=".$_POST['n5'];
		$ewayurl.="&CustomerCountry=".$_POST['n6'];	
		$ewayurl.="&CustomerEmail=".$_POST['n3'];
		$ewayurl.="&CustomerPhone=".$_POST['n8'];	
		$ewayurl.="&InvoiceDescription=".$_POST['InvoiceDescription'];
		$ewayurl.="&CancelURL=".$_POST['callback'];
		$ewayurl.="&ReturnUrl=".$_POST['callback']."?order_id=".$_POST['RefNum']."&force=1";
		$ewayurl.="&CompanyLogo=https://www.eway.co.uk/secure/images/eWAYLogo1.gif";
		$ewayurl.="&PageBanner=https://www.eway.co.uk/Join/Secure/_images/TopBannerV6.gif";
		$ewayurl.="&MerchantReference=".$_POST['RefNum'];
		$ewayurl.="&MerchantInvoice=Shopping Cart Order";
		$ewayurl.="&MerchantOption1="; 
		$ewayurl.="&MerchantOption2=";
		$ewayurl.="&MerchantOption3=";
		$ewayurl.="&ModifiableCustomerDetails=".$_POST['ModDetails'];
			
	    $spacereplace = str_replace(" ", "%20", $ewayurl);	
	    $posturl="https://nz.ewaygateway.com/Request/$spacereplace";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		if (CURL_PROXY_REQUIRED == 'True') 
		{
			$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
			curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
			curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
		}
		
		$response = curl_exec($ch);
		
		function fetch_data($string, $start_tag, $end_tag)
		{
			$position = stripos($string, $start_tag);  
			$str = substr($string, $position);  		
			$str_second = substr($str, strlen($start_tag));  		
			$second_positon = stripos($str_second, $end_tag);  		
			$str_third = substr($str_second, 0, $second_positon);  		
			$fetch_data = trim($str_third);		
			return $fetch_data; 
		}
		
		
		$responsemode = fetch_data($response, '<result>', '</result>');
	    $responseurl = fetch_data($response, '<uri>', '</uri>');
		 
		if($responsemode=="True")
		{ 			  	  	
		  header("location: ".$responseurl);
		  exit;
		}
		else
		{
		  $cartError = "Payment Gateway Busy. Please try again shortly.";
		}
?>