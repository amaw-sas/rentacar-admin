<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
    <s:Body
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <OTA_VehAvailRate
            xmlns="http://tempuri.org/">
            <OTA_VehAvailRateRQ PrimaryLangID="esp"
                RetransmissionIndicator="false" TransactionStatusCode="Start" Version="0"
                TimeStamp="0001-01-01T00:00:00" EchoToken="{{ $token }}"
                MaxPerVendorInd="false">
                <POS>
                    <Source ISOCountry="CO">
                        <RequestorID
                            ID="{{ $requestor_id }}" Type="5" xmlns="http://www.opentravel.org/OTA/2003/05" />
                    </Source>
                </POS>
                <VehAvailRQCore>
                    <VehRentalCore PickUpDateTime="{{ $pickupDateTime }}"
                        ReturnDateTime="{{ $returnDateTime }}"
                        xmlns="http://www.opentravel.org/OTA/2003/05">
                        <PickUpLocation
                            LocationCode="{{ $pickupLocation }}" CodeContext="internal code" />
                        <ReturnLocation
                            LocationCode="{{ $returnLocation }}" CodeContext="internal code" />
                    </VehRentalCore>
                    <Customer
                        xmlns="http://www.opentravel.org/OTA/2003/05">
                        <Primary>
                            <CitizenCountryName
                                Code="CO" />
                        </Primary>
                    </Customer>
                </VehAvailRQCore>
            </OTA_VehAvailRateRQ>
        </OTA_VehAvailRate>
    </s:Body>
</s:Envelope>
