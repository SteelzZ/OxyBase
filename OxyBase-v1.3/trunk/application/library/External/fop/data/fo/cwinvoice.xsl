<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format">
<xsl:output method="xml" encoding="UTF-8"/>
<xsl:template match="/">
    <fo:root>
       <xsl:call-template name="Layout"/>
	   <xsl:call-template name="Data"/>
	</fo:root>
</xsl:template>

<xsl:template name="Layout">
 	<fo:layout-master-set>
	   <fo:simple-page-master margin-bottom="5mm" margin-left="15mm" margin-right="10mm" margin-top="5mm" master-name="right" page-height="297mm" page-width="210mm">
		  <fo:region-body margin-bottom="30mm" margin-left="0mm" margin-right="0mm" margin-top="40mm" region-name="xsl-region-body"/>
		  <fo:region-before extent="40mm" region-name="xsl-region-header"/>
		  <fo:region-after extent="15mm" region-name="xsl-region-footer"/>
	   </fo:simple-page-master>
	</fo:layout-master-set>
</xsl:template>

<xsl:template name="Data">
    <fo:page-sequence font-size="7pt"  force-page-count="no-force" initial-page-number="1" language="en" line-height="110%" master-reference="right">
	<!-- HEADER -->
     <fo:static-content flow-name="xsl-region-header">
		  <fo:block border-bottom="0.2pt solid #555555">
		     <fo:table padding-bottom="5pt">
			    <fo:table-column column-width="130pt"/>
				<fo:table-column column-width="130pt"/>
				<fo:table-column column-width="130pt"/>
				<fo:table-column column-width="130pt"/>
				<fo:table-body>
				   <fo:table-row >
					  <fo:table-cell display-align="center" overflow="hidden">
						 <fo:block>
							
						 </fo:block>
					  </fo:table-cell>
					  <fo:table-cell><fo:block></fo:block></fo:table-cell>
					  <fo:table-cell display-align="center" overflow="hidden">
					 	 <fo:block>Corne Akkermans Auctions B.V.</fo:block>
						 <fo:block>Postbus 468</fo:block>
						 <fo:block>NL-4100 AL Culemborg</fo:block>
						 <fo:block>The Netherlands</fo:block>
						 <fo:block padding-bottom="2pt"/>
						 <fo:block>Website www.akkermansauctions.com</fo:block>
						 <fo:block>Email info@akkermansauctions.com</fo:block>
					  </fo:table-cell>
					  <fo:table-cell display-align="center" overflow="hidden">
						 <fo:block>Tel number ++ 31 (0)345 531 670</fo:block>
						 <fo:block>Fax number ++ 31 (0)345 548 523</fo:block>
						 <fo:block>Mobile phone ++ 31 (0)644 812 471</fo:block>
						 <fo:block padding-bottom="2pt"/>
						 <fo:block>KvK Rivierenland: 11051514</fo:block>
						 <fo:block padding-bottom="10pt">VAT/BTW nr. NL0631.02.857.B01</fo:block>
					  </fo:table-cell>
				  </fo:table-row>
			   </fo:table-body>
		    </fo:table> 
	     </fo:block>
       </fo:static-content>
	   <!-- /HEADER -->
	   <!-- FOOTER-->
       <fo:static-content flow-name="xsl-region-footer">
          <fo:block font-size="100%" text-align="right">
                 <xsl:text>Invoice </xsl:text>
				 <xsl:text>Page </xsl:text>
			     <fo:page-number/>
          </fo:block>
	      <fo:block border-top="0.2pt solid #555555" font-size="100%" text-align="left" padding-before="5pt">
	         <fo:table padding-after="15pt">
				<fo:table-column column-width="130pt"/>
		        <fo:table-column column-width="130pt"/>
	            <fo:table-column column-width="130pt"/>
				
		        <fo:table-body>
		           <fo:table-row>
					<fo:table-cell>
					  <fo:block>ABN-AMRO Bank</fo:block>
					  <fo:block>48.11.00.628</fo:block>
					  <fo:block>BIC (Swift) #ABNANL2A</fo:block>
					  <fo:block>IBAN NL08 ABNA 0481 1006 28</fo:block>
				   </fo:table-cell>
				   <fo:table-cell>
					  <fo:block>DEUTSCHE BANK Kleve</fo:block>
					  <fo:block>32 50 859(Blz 324 700 24)</fo:block>
					  <fo:block>BIC (Swift) #DEUTDEDB324</fo:block>
					  <fo:block>IBAN DE56 3247 0024 0325 0859 00</fo:block>
				   </fo:table-cell>
				   <fo:table-cell>
					  <fo:block>POSTBANK</fo:block>
					  <fo:block>95.13.609</fo:block>
					  <fo:block>BIC (Swift) #PSTBNL21</fo:block>
					  <fo:block>IBAN NL10 PSTB 0009 5136 09</fo:block>
				   </fo:table-cell>
				   
			     </fo:table-row>
		       </fo:table-body>
		     </fo:table>
	      </fo:block>
       </fo:static-content>
       <!-- FOOTER-->
	   
	   <fo:flow flow-name="xsl-region-body">
	   <fo:block>
            <xsl:call-template name="Customer"/>
            <xsl:call-template name="InvoiceHead"/>
           
            <fo:table inline-progression-dimension.optimum="100%" width="100%" padding-top="5pt">
				<fo:table-column column-width="50pt"/>
				<fo:table-column/>
				<fo:table-column column-width="75pt"/>
				<fo:table-body>
					<fo:table-row>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" padding-bottom="10pt">Lot/Kavel</fo:block>
						</fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" padding-bottom="10pt">Description/Beschrijving</fo:block>
						</fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" padding-bottom="10pt" text-align="right">Price/Prijs &#8364;</fo:block>
						</fo:table-cell>
					</fo:table-row>
					<xsl:for-each select="/Invoice/InvoiceDetails">
					   <xsl:sort select="./BaseItemDetail/PartNumDetail/PartDesc/lot" data-type="number"/>
						<fo:table-row keep-together.within-column="always" keep-together.within-page="always">
							<fo:table-cell padding="1pt">
								<fo:block font-weight="bold">
									<xsl:value-of select="./BaseItemDetail/PartNumDetail/PartDesc/lot"/>
									<xsl:text> - </xsl:text>
									<xsl:value-of select="./BaseItemDetail/PartNumDetail/PartDesc/cms"/>
								</fo:block>
							</fo:table-cell>
							<fo:table-cell padding="1pt">
								<fo:block>
				
									<xsl:variable name="title">
										<xsl:value-of select="./BaseItemDetail/PartNumDetail/PartDesc/title"/>
									</xsl:variable>
									
									<xsl:variable name="text">
										<xsl:value-of select="./BaseItemDetail/PartNumDetail/PartDesc/description"/>
									</xsl:variable>
										
									<xsl:for-each select="./BaseItemDetail/PartNumDetail/PartDesc/title[@language='en']">
										<fo:block>
											<fo:inline font-weight="bold">
												<xsl:value-of select="."/>
											</fo:inline>
										</fo:block>
									</xsl:for-each>
									
									<xsl:for-each select="./BaseItemDetail/PartNumDetail/PartDesc/description[@language='en']">
										 <fo:block>
											<xsl:value-of select="."/>
										 </fo:block>
									</xsl:for-each>
										
									<fo:block>
										<fo:inline font-weight="bold">
											<xsl:value-of select="$title"/>
										</fo:inline>
									</fo:block>
									
									<fo:block>
										<xsl:value-of select="$text"/>
									</fo:block>
								
								</fo:block>
							</fo:table-cell>
							<fo:table-cell padding="1pt">
								<fo:block text-align="right">
									<xsl:value-of select="format-number(translate(./UnitPrice, ',' , '.'),'0.00')"/>
								</fo:block>
							</fo:table-cell>
						</fo:table-row>
					</xsl:for-each>
					
					<fo:table-row >
						<fo:table-cell padding="1pt"><fo:block></fo:block></fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" text-align="right">Sub Total:</fo:block>
						</fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" text-align="right">
								<xsl:value-of select="format-number(number(/Invoice/InvoiceSummary/InvoiceTotals/NetValue),'0.00')"/>
							</fo:block>
						</fo:table-cell>
					</fo:table-row>
					
					<fo:table-row  padding-top="10pt">
						<fo:table-cell padding="1pt"><fo:block></fo:block></fo:table-cell>
						<fo:table-cell padding="1pt"> 
							<xsl:variable name="commission" select="/Invoice/InvoiceSummary/TaxSummary[4]/Tax/TaxPercent"/>
							<fo:block font-weight="bold" text-align="right">
								<xsl:value-of select="$commission"/>
								<xsl:text> Commission:</xsl:text>
							</fo:block>
						</fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" text-align="right">
								<xsl:value-of select="format-number(/Invoice/InvoiceSummary/TaxSummary[4]/Tax/TaxAmount,'0.00')"/>
							</fo:block>
						</fo:table-cell>
					</fo:table-row>
				
					<fo:table-row>
						<fo:table-cell padding="1pt"><fo:block></fo:block></fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" text-align="right">
								<xsl:text>Verzendkosten/Shipping and Handling:</xsl:text>
							</fo:block>
						</fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" text-align="right">
								<xsl:value-of select="format-number(concat('0',/Invoice/InvoiceSummary/TaxSummary[2]/Tax/TaxPercent),'0.00')"/>
							</fo:block>
						</fo:table-cell>
					</fo:table-row>
					
					<fo:table-row>
						<fo:table-cell><fo:block></fo:block></fo:table-cell>
						<fo:table-cell padding="1pt">
						<xsl:variable name="payment" select="/Invoice/InvoiceSummary/TaxSummary[3]/Tax/TaxPercent"/>
							<fo:block font-weight="bold" text-align="right">
							    <xsl:value-of select="$payment"/>
								<xsl:text> Payment :</xsl:text>
							</fo:block>
						</fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" text-align="right">
								<xsl:value-of select="format-number(translate(/Invoice/InvoiceSummary/TaxSummary[3]/Tax/TaxAmount, ',', '.'),'0.00')"/>
							</fo:block>
						</fo:table-cell>
					</fo:table-row>
					
					<fo:table-row>
						<fo:table-cell><fo:block></fo:block></fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block padding-bottom="5pt" font-weight="bold" text-align="right">Total:</fo:block>
						</fo:table-cell>
						<fo:table-cell padding="1pt">
							<fo:block font-weight="bold" text-align="right">
								<xsl:value-of select="format-number(number(/Invoice/InvoiceSummary/InvoiceTotals/GrossValue),'0.00')"/>
							</fo:block>
						</fo:table-cell>
					</fo:table-row>
					
			   </fo:table-body>
			</fo:table>
			
			<fo:block padding-top="20pt">Signature: ______________________________</fo:block>
			
			<fo:table>
				<fo:table-column/>
				<fo:table-body>
				<fo:table-row keep-together.within-column="always" keep-together.within-page="always">
						<fo:table-cell>
						    <fo:block padding-top="20pt">Payment instructions:</fo:block>
						    <fo:block>Please add Euro 12,50 for bankcosts to your invoice from non EU countries.</fo:block>
							<fo:block>Banktransfer: All transfer and bankcharges are to be paid by payee; Only nett amounts received equal to invoice amount are accepted</fo:block>
							<fo:block>Please add 15 Euro to your total invoice if you pay pay with a personal check.</fo:block>
							<fo:block>Please add 4% to the total invoice if you pay with paypal. Paypal account c.akkermans@collectweb.nl</fo:block>
							<fo:block>Only if total amount is paid goods will be send to you.</fo:block>
							<fo:block padding-top="20pt">Payment within 14 days after invoice date with reference to the invoice number.</fo:block>
						</fo:table-cell>
				</fo:table-row>
				</fo:table-body>
			</fo:table>
				
	   </fo:block>		
	   </fo:flow>
	  
    </fo:page-sequence>
</xsl:template>


<xsl:template name="Customer">
    <xsl:variable name="customer" select="/Invoice/InvoiceHeader/Party[@stdValue='BY']"/>
	<fo:block padding-bottom="85pt" padding-top="75pt">
    <fo:table>
		<fo:table-column/>
		<fo:table-column column-width="220pt"/>
		<fo:table-body>
			<fo:table-row>
				<fo:table-cell><fo:block></fo:block></fo:table-cell>
				<fo:table-cell>
					<fo:block font-size="150%" line-height="120%">
						<fo:block font-weight="bold">
							<xsl:for-each select="$customer/Name/*[starts-with(name(),'Name')]">
							   <xsl:value-of select="concat(. , ' ')"/> 
							</xsl:for-each>
						</fo:block>
						<fo:block>
							<fo:block>
								<xsl:for-each select="$customer/Street/*[starts-with(name(),'Street')]">
								   <xsl:value-of select="concat(. , ' ')"/> 
								</xsl:for-each>
							</fo:block>
							<fo:block>
								<xsl:if test="$customer/PostalInfo/PostalCode!=''">
									<xsl:value-of select="concat($customer/PostalInfo/PostalCode,' ')"/>
								</xsl:if>
								<xsl:value-of select="$customer/PostalInfo/City"/>
							</fo:block>
							<fo:block>
								<xsl:value-of select="$customer/PostalInfo/Country"/>
							</fo:block>
							<fo:block>
								<xsl:value-of select="$customer/PostalInfo/WatNumber"/>
							</fo:block>
						</fo:block>
					</fo:block>
				</fo:table-cell>
			</fo:table-row>
		</fo:table-body>
  </fo:table>
  </fo:block>
</xsl:template>

<xsl:template name="InvoiceHead">
    <xsl:variable name="invoice" select="/Invoice/InvoiceHeader"/>
    <xsl:variable name="customer" select="/Invoice/InvoiceHeader/Party[@stdValue='BY']"/>
	<fo:block font-size="150%" line-height="120%" font-weight="bold" padding-bottom="10pt">
		<fo:block>
			<xsl:text>AUCTION / VEILING  No.: 14</xsl:text>
		</fo:block>
		<fo:block>
			<xsl:text>INVOICE / FACTUUR No.: </xsl:text>
			<xsl:value-of select="$invoice/InvoiceNumber"/>
		</fo:block>
		<fo:block>
			<xsl:text> Date / Datum: </xsl:text>
			<xsl:value-of select="$invoice/InvoiceDate"/>
		</fo:block>
	</fo:block>
	<xsl:if test="$customer/Contact/Email!=''">
		<fo:block padding-bottom="10pt">
		  <xsl:text>Email: </xsl:text>
		  <xsl:value-of select="$customer/Contact/Email"/>
		</fo:block>
	</xsl:if>
	<xsl:if test="$customer/PartyID!=0">
		<fo:block padding-bottom="10pt">
		  <xsl:text>Bidding ID / Uw Biednummer: </xsl:text>
		  <xsl:value-of select="$customer/PartyID"/>
		</fo:block>
	</xsl:if>
</xsl:template>
		
</xsl:stylesheet>
