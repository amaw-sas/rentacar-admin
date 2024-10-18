<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
  <s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <OTA_VehRetResResponse xmlns="http://tempuri.org/">
      <OTA_VehRetResRS TimeStamp="0001-01-01T00:00:00" Target="Test" Version="0" TransactionStatusCode="Start" RetransmissionIndicator="false">
        <Success/>
        <Warnings>
          <Warning xmlns="http://www.opentravel.org/OTA/2003/05" Type="3" ShortText="LLNRRE045" Code="95" Status="NotProcessed">Esta reserva foi Cancelada e não poderá sofrer modificações.</Warning>
        </Warnings>
        <VehRetResRSCore>
          <VehReservation xmlns="http://www.opentravel.org/OTA/2003/05">
            <VehSegmentCore>
              <ConfID ID="" Type="14"/>
            </VehSegmentCore>
          </VehReservation>
        </VehRetResRSCore>
      </OTA_VehRetResRS>
    </OTA_VehRetResResponse>
  </s:Body>
</s:Envelope>
