<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tem="http://tempuri.org/">
    <soapenv:Header />
    <soapenv:Body>
        <OTA_VehRetRes xmlns="http://tempuri.org/">
            <OTA_VehRetResRQ RetransmissionIndicator="false" TransactionStatusCode="Start" Target="Test" TimeStamp="0001-01-01T00:00:00" EchoToken="{{ $token }}" Version="0">
                <VehRetResRQCore>
                    <UniqueID xmlns="http://www.opentravel.org/OTA/2003/05" ID="{{ $reserve_code }}" Type="14" />
                </VehRetResRQCore>
            </OTA_VehRetResRQ>
        </OTA_VehRetRes>
    </soapenv:Body>
</soapenv:Envelope>
