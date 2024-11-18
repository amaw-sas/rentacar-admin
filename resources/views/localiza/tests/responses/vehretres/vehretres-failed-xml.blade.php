<s:Envelope
	xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
	<s:Body
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xmlns:xsd="http://www.w3.org/2001/XMLSchema">
		<OTA_VehRetResResponse
			xmlns="http://tempuri.org/">
			<OTA_VehRetResRS TimeStamp="0001-01-01T00:00:00" Target="Test" Version="0" TransactionStatusCode="Start" RetransmissionIndicator="false">
				<Success/>
				<VehRetResRSCore>
					<VehReservation ReservationStatus="Failed" CreatorID="07334927"
						xmlns="http://www.opentravel.org/OTA/2003/05">
						<Customer>
							<Primary>
								<PersonName>
									<GivenName>PRUEBA TEST TESTE AMAW</GivenName>
									<Surname>PRUEBA TEST TESTE AMAW</Surname>
								</PersonName>
								<Telephone PhoneTechType="1" PhoneUseType="5" CountryAccessCode="57" AreaCityCode="57" PhoneNumber="25543545"/>
							</Primary>
						</Customer>
						<VehSegmentCore>
							<ConfID ID="AV1BRQW35U" Type="14"/>
							<ConfID ID="" Type="24"/>
							<VehRentalCore OneWayIndicator="true" ReturnDateTime="2024-09-15T12:00:00" PickUpDateTime="2024-09-10T12:00:00">
								<PickUpLocation LocationCode="AABOT" CodeContext="internal code"/>
								<ReturnLocation LocationCode="AABOT" CodeContext="internal code"/>
							</VehRentalCore>
							<Vehicle FuelType="Unspecified" DriveType="Unspecified" AirConditionInd="true" TransmissionType="Manual" Description="ECONÔMICO COM AR" OdometerUnitOfMeasure="Km" VendorCarType="MDMR" BaggageQuantity="2" PassengerQuantity="5" CodeContext="internal code" Code="C">
								<VehType VehicleCategory="1" DoorCount="4"/>
								<VehClass Size="1"/>
								<VehMakeModel Name="Fiat Mobi 1.0"/>
								<PictureURL>https://www.localiza.com/colombia-site/geral/Frota/MOBI.png</PictureURL>
							</Vehicle>
							<RentalRate>
								<RateDistance Unlimited="true" DistUnitName="Km" VehiclePeriodUnitName="Day"/>
								<VehicleCharges>
									<VehicleCharge IncludedInRate="true" RateConvertInd="false" IncludedInEstTotalInd="true" Description="Diárias" DecimalPlaces="2" CurrencyCode="COP" TaxInclusive="false" Amount="676237.6" Purpose="1">
										<Calculation UnitCharge="135247.52" UnitName="Day" Quantity="5" Total="676237.6"/>
									</VehicleCharge>
								</VehicleCharges>
								<RateQualifier RatePeriod="Daily" RateQualifier="018117" RateCategory="16"/>
								<RateRestrictions MinimumDayInd="true" MaximumDayInd="false"/>
							</RentalRate>
							<Fees>
								<Fee IncludedInRate="true" RateConvertInd="false" IncludedInEstTotalInd="true" Description="Taxa" DecimalPlaces="2" CurrencyCode="COP" TaxInclusive="false" Amount="82123.76" Purpose="6">
									<Calculation Percentage="10" Total="82123.76"/>
								</Fee>
								<Fee IncludedInRate="false" RateConvertInd="false" IncludedInEstTotalInd="true" Description="IVA" DecimalPlaces="2" CurrencyCode="COP" Amount="171638.6584" Purpose="7"/>
							</Fees>
							<TotalCharge RateTotalAmount="676237.6" EstimatedTotalAmount="1075000.0184" CurrencyCode="COP" DecimalPlaces="2"/>
							<TPA_Extensions>
								<OfferInfo
									xmlns="">
									<MinPerc>38.52</MinPerc>
									<MaxPerc>38.52</MaxPerc>
									<AutonomyId>6</AutonomyId>
								</OfferInfo>
							</TPA_Extensions>
						</VehSegmentCore>
						<VehSegmentInfo>
							<PaymentRules/>
							<PricedCoverages>
								<PricedCoverage Required="false">
									<Coverage CoverageType="7">
										<Details CoverageTextType="Description">Proteções com co-participação regular</Details>
									</Coverage>
									<Charge IncludedInRate="false" RateConvertInd="false" IncludedInEstTotalInd="true" Description="Proteções com co-participação regular" DecimalPlaces="2" CurrencyCode="COP" TaxInclusive="false" Amount="145000">
										<Calculation UnitCharge="29000" UnitName="Day" Quantity="5" Total="145000"/>
									</Charge>
								</PricedCoverage>
							</PricedCoverages>
							<LocationDetails CodeContext="internal code" ExtendedLocationCode="BOG" AtAirport="true" Code="AABOT" Name="AEROPORTO INTERNACIONAL EL DORADO - BOGOTÁ">
								<Address Type="2" FormattedInd="false" UseType="7">
									<AddressLine>DIAGONAL 24C, 99-45, FONTIBÓN</AddressLine>
									<AddressLine>4.701389,-74.146944</AddressLine>
									<CityName>BOGOTA</CityName>
									<PostalCode>.</PostalCode>
									<StateProv StateCode="CD"/>
									<CountryName Code="CO"/>
									<TPA_Extensions>
										<int
											xmlns="">9691
										</int>
									</TPA_Extensions>
								</Address>
								<Telephone PhoneTechType="1" PhoneUseType="5" PhoneNumber="01 8000 520 001" FormattedInd="false"/>
								<AdditionalInfo>
									<OperationSchedules>
										<OperationSchedule>
											<OperationTimes>
												<OperationTime Mon="false" Tue="false" Weds="false" Thur="false" Fri="false" Sat="false" Sun="true" Start="00:00:00" End="23:59:00"/>
												<OperationTime Mon="true" Tue="false" Weds="false" Thur="false" Fri="false" Sat="false" Sun="false" Start="00:00:00" End="23:59:00"/>
												<OperationTime Mon="false" Tue="true" Weds="false" Thur="false" Fri="false" Sat="false" Sun="false" Start="00:00:00" End="23:59:00"/>
												<OperationTime Mon="false" Tue="false" Weds="true" Thur="false" Fri="false" Sat="false" Sun="false" Start="00:00:00" End="23:59:00"/>
												<OperationTime Mon="false" Tue="false" Weds="false" Thur="true" Fri="false" Sat="false" Sun="false" Start="00:00:00" End="23:59:00"/>
												<OperationTime Mon="false" Tue="false" Weds="false" Thur="false" Fri="true" Sat="false" Sun="false" Start="00:00:00" End="23:59:00"/>
												<OperationTime Mon="false" Tue="false" Weds="false" Thur="false" Fri="false" Sat="true" Sun="false" Start="00:00:00" End="23:59:00"/>
												<OperationTime Mon="false" Tue="false" Weds="false" Thur="false" Fri="false" Sat="false" Sun="false" Start="06:00:00" End="22:00:00" Text="Horário de funcionamento somente para feriados"/>
											</OperationTimes>
										</OperationSchedule>
									</OperationSchedules>
								</AdditionalInfo>
							</LocationDetails>
						</VehSegmentInfo>
					</VehReservation>
				</VehRetResRSCore>
			</OTA_VehRetResRS>
		</OTA_VehRetResResponse>
	</s:Body>
</s:Envelope>
