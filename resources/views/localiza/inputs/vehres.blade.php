<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/" xmlns:ns="http://www.opentravel.org/OTA/2003/05">
  <soapenv:Body>
    <tem:OTA_VehRes xmlns="http://tempuri.org/">
      <tem:OTA_VehResRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" EchoToken="{{ $token }}" Target="Test" PrimaryLangID="por">
        <tem:POS>
          <tem:Source ISOCountry="CO">
            <ns:RequestorID xmlns="http://www.opentravel.org/OTA/2003/05" ID="{{ $requestor_id }}" Type="5"/>
          </tem:Source>
        </tem:POS>
        <tem:VehResRQCore xmlns="http://tempuri.org/">
          <ns:VehRentalCore xmlns="http://www.opentravel.org/OTA/2003/05" ReturnDateTime="{{ $return_datetime }}" PickUpDateTime="{{ $pickup_datetime }}">
            <ns:PickUpLocation LocationCode="{{ $pickup_location }}" CodeContext="internal code"/>
            <ns:ReturnLocation LocationCode="{{ $return_location }}" CodeContext="internal code"/>
          </ns:VehRentalCore>
          <ns:Customer>
            <ns:Primary>
              <ns:PersonName>
                <ns:Surname>{{ $fullname }}</ns:Surname>
              </ns:PersonName>
              <ns:Email>{{ $email }}</ns:Email>
              <ns:CitizenCountryName Code="CO"/>
              <ns:Telephone CountryCode="{{ $phone_country_code }}" PhoneNumber="{{ $phone }}"/>
            </ns:Primary>
          </ns:Customer>
          <ns:VehPref CodeContext="internal code" Code="{{ $category }}"/>
          <ns:RateQualifier RateQualifier="{{ $rate_qualifier }}"/>
        </tem:VehResRQCore>
        <tem:VehResRQInfo>
          <ns:RentalPaymentPref PaymentType="2">
            <ns:Voucher ValueType="2"/>
          </ns:RentalPaymentPref>
          <ns:Reference Type="41" ID="{{ $reference_token }}"/>
        </tem:VehResRQInfo>
      </tem:OTA_VehResRQ>
    </tem:OTA_VehRes>
  </soapenv:Body>
</soapenv:Envelope>
