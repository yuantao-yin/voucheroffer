<?php

/******************************************************************************************************

	UPS Shipping Calculator
	Version 1.0

	by mrchip
	2010-10-29
	
	DISCLAIMER: USE THIS SCRIPT AT YOUR OWN RISK. I AM NOT RESPONSIBLE FOR ANYTHING.

	This is the rate XML file that gets sent to UPS with the information you provided.

	If you have any questions please feel free to contact me, I normally reply in 24 hours M-F.

	Thank you for purchasing my script.
	

*******************************************************************************************************/

$xml_data = '
<?xml version="1.0"?>
<AccessRequest xml:lang="en-US">
    <AccessLicenseNumber>'.$this->ups_access_key.'</AccessLicenseNumber>
    <UserId>'.$this->ups_user_id.'</UserId>
    <Password>'.$this->ups_password.'</Password>
</AccessRequest>
<RatingServiceSelectionRequest xml:lang="en-US">
    <Request>
        <TransactionReference>
            <CustomerContext>Rating and Service</CustomerContext>
            <XpciVersion>1.0</XpciVersion>
        </TransactionReference>
        <RequestAction>Rate</RequestAction>
        <RequestOption>Shop</RequestOption>
    </Request>
    <PickupType>
        <Code>'.$this->pickup_type.'</Code>
    </PickupType>
    <Shipment>
        <Shipper>
            <Address>
                <StateProvinceCode>'.$this->from_state.'</StateProvinceCode>
                <PostalCode>'.$this->from_zip.'</PostalCode>
                <CountryCode>'.$this->from_country.'</CountryCode>
            </Address>
        </Shipper>
        <ShipFrom>
            <Address>
                <StateProvinceCode>'.$this->from_state.'</StateProvinceCode>
                <PostalCode>'.$this->from_zip.'</PostalCode>
                <CountryCode>'.$this->from_country.'</CountryCode>
            </Address>
        </ShipFrom>
        <ShipTo>
            <Address>
                <StateProvinceCode>'.$this->ship_state.'</StateProvinceCode>
                <PostalCode>'.$this->ship_zip.'</PostalCode>
                <CountryCode>'.$this->ship_country.'</CountryCode>
                <ResidentialAddressIndicator>'.$residential_address.'</ResidentialAddressIndicator>
            </Address>
        </ShipTo>
        <Service>
            <Code>'.$this->service.'</Code>
        </Service>
        <Package>
            <PackagingType>
                <Code>'.$this->package_type.'</Code>
            </PackagingType>
            <PackageWeight>
                <UnitOfMeasurement>
                    <Code>'.$this->weight_type.'</Code>
                </UnitOfMeasurement>
                <Weight>'.$this->weight.'</Weight>
            </PackageWeight>
        </Package>
    </Shipment>
</RatingServiceSelectionRequest>';

?>