<?php

class ups_rates {
	
		var $version				= '1.0';
		var $debug 					= true;
	
		var $ups_access_key			= UPS_ACCESSKEY;
		var $ups_user_id			= UPS_USERID;
		var $ups_password			= UPS_PASSWORD;

		var $default_rate;
		
		var $pickup_type			= '01';
    	var $package_type 			= '02';
    	var $request_type;
    	var $weight_type			= 'LBS';
    	
    	var $from_state;
    	var $from_zip;
    	var $from_country			= 'US';

    	var $ship_state;        
    	var $ship_zip;
    	var $ship_country			= 'US';
    	
    	var $currency_type			= '$';
    	var $form_name 				= 'shipping_methods';
    	var $select_class			= '';
    	
    	var $pickup					= true;
    	var $pickup_cost			= '0.00';
    	
    	var $service 				= '';
    	var $residential 			= true;
    	
    	var $weight 				= 1;
    	var $subtotal 				= 1.00;
    	
    	var $rates					= array();
 
    	

		
		function check_settings() {
	
			// Checking UPS Account Information
			if ($this->ups_access_key == NULL && $this->ups_access_key == '') $error_msg = 'You did not specify a UPS Access Key<br>';
			if ($this->ups_user_id == NULL && $this->ups_user_id == '') $error_msg .= 'You did not specify a UPS User ID<br>';
			if ($this->ups_password == NULL && $this->ups_password == '') $error_msg .= 'You did not specify a UPS Access Key<br>';
			
			// Checking Ship From & Ship To Information
			if ($this->from_state == NULL && $this->from_state == '') $error_msg .= 'You did not enter a ship from state location<br>';
			if ($this->from_zip == NULL && $this->from_zip == '') $error_msg .= 'You did not enter a ship from zip location<br>';
			if ($this->from_country == NULL && $this->from_country == '') $error_msg .= 'You did not enter a ship from country location<br>';
			
			if ($this->ship_zip == NULL && $this->ship_zip == '') $error_msg .= 'You did not enter a ship to zip location<br>';
			if ($this->ship_country == NULL && $this->ship_country == '') $error_msg .= 'You did not enter a ship to country location<br>';
			
			// If you have the above variable $debug = true, then the script will halt on any errors and display what error occured, you can switch off this option if you want by setting $debug to false.
			if ($this->debug == true && $error_msg != NULL) exit('<strong>UPS Rates - Version '.$this->version.'</strong><br><br>'.$error_msg.'<br>Please check the readme.txt file for more information.');
		
		}
		
		function service_codes($code) {
		
			// These are all the codes provided by UPS. Check the value in $code and apply for the array.
			
			$service_codes = array( '14' => 'Next Day Air Early AM',
									'01' => 'Next Day Air',
									'13' => 'Next Day Air Saver',
									'59' => '2nd Day Air AM', 
									'02' => '2nd Day Air',
									'12' => '3 Day Select', 
									'03' => 'Ground',

									'11' => 'Standard',
									'07' => 'Worldwide Express',
									'54' => 'Worldwide Express Plus', 
									'08' => 'Worldwide Expedited', 
									'65' => 'Saver',

									'82' => 'UPS Today Standard',
									'83' => 'UPS Today Dedicated Courier',
									'84' => 'UPS Today Intercity', 
									'85' => 'UPS Today Express', 
									'86' => 'UPS Today Express Saver');
									
									
			return $service_codes[$code];
		
		}
		
		function fetch_rates() {

			$this->check_settings();
			
			if ($this->residential == true) $residential_address = '1';
			
			// Attach UPS XML
			include('ups-xml/rates.php');

			// Connect to UPS and assign data to $data.
			$url = 'https://onlinetools.ups.com/ups.app/xml/Rate';
			
			$ch = curl_init(); 
        	curl_setopt ($ch, CURLOPT_URL, $url);
	        curl_setopt ($ch, CURLOPT_HEADER, 0);
    	    curl_setopt ($ch, CURLOPT_POST, 1);
        	curl_setopt ($ch, CURLOPT_POSTFIELDS, $xml_data);
	        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    	    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        	curl_setopt ($ch, CURLOPT_TIMEOUT, 10); 
	        $data = curl_exec ($ch);
    	    curl_close ($ch);
        	
        	$xml = simplexml_load_string($data);
        	
        	$status = $xml->Response->ResponseStatusCode;

        	
        	if ($status == '1') {
        	
        		// UPS was succesful
        		$num_of_rates = count($xml->RatedShipment);
        		
				$aa=1;
        		// Extract Information
        		for($i=0; $i<$num_of_rates; $i++) {
        			$service_code = strval($xml->RatedShipment[$i]->Service->Code);
        			$service_cost = strval($xml->RatedShipment[$i]->TotalCharges->MonetaryValue);
					$service_desc = 'UPS '.$this->service_codes($service_code);
        			$service_rates[$i]['code'] = $service_code;
        			$service_rates[$i]['cost'] = $service_cost;
        			$service_rates[$i]['desc'] = $service_desc;
        			
        			if($aa ==1){ $selected = 'checked'; }else{ $selected = ''; }
        			$option_list .= '<input '.$selected.' name="form[shippingmethod]" type="radio" value="'.$service_cost.'" onClick="document.getElementById(\'shippingmethod_name\').value=\''.$service_desc.' - '.$this->currency_type.$service_cost.'\'" >'.$service_desc.' - '.$this->currency_type.$service_cost.'<br />';
        			 
        			$aa++;
        			
        		}
        		
        		if ($this->pickup == true) {
        			$i++;
        			$service_rates[$i]['code'] = '00';
        			$service_rates[$i]['cost'] = $this->pickup_cost;
        			$service_rates[$i]['desc'] = 'Pickup';
        			
        			if ($this->default_rate == '00') $selected = ' selected';
        			
        			if ($this->pickup_cost == '') $this->pickup_cost = '0.00';
        			
        			//$option_list .= '<option value="00"'.$selected.'>Pickup - '.$this->currency_type.$this->pickup_cost.'</option>';
        		}
        		
        		$this->rates = $service_rates;
        	
        	} else {
        	
        		// UPS failed return only Pickup
        		if ($this->debug == true) return  'UPS returned this error: "<strong>'.$xml->Response->Error->ErrorDescription.'</strong>"';
        		
        		$option_list .= '<option value="00">Pickup - '.$this->curreny_type.$this->pickup_cost.'</option>';
        		
        	}
        	
        	if ($this->select_class != '') $class = ' class="'.$this->select_class.'"';
			
			$option_list .= '<input id="shippingmethod_name" name="form[shippingmethod_name]" type="hidden" value="'.$spn.'">';
        	return $option_list;
		
		}
		
		
	}

?>