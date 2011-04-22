<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
   				xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
   				xmlns:fo="http://www.w3.org/1999/XSL/Format">
<xsl:output method="xml" indent="yes" encoding="UTF-8"/>
  <xsl:template match="/invoice">
  	<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
	
	    <fo:layout-master-set>
	      <fo:simple-page-master master-name="A4"
			 page-width="210mm" page-height="297mm"
			 margin-top="1cm"   margin-bottom="1cm"
			 margin-left="1cm"  margin-right="1cm">
			  <fo:region-body   region-name="Content" margin-top="2cm" margin-bottom="3cm"/>
			  <fo:region-before region-name="Header"  extent="0cm"/>
			  <fo:region-after  region-name="Footer"  extent="2cm"/>
		  </fo:simple-page-master>
		  
		  <fo:page-sequence-master master-name="items">
		  	<fo:single-page-master-reference master-reference="A4"/>
		  </fo:page-sequence-master>
		  
	    </fo:layout-master-set>
	    
	  <fo:page-sequence master-reference="items">
	  
	    <xsl:apply-templates select="company/header" />  
        
        <!-- FOOTER -->
        <fo:static-content flow-name="Footer">
           <fo:block border-bottom-width="thin" border-bottom-style="solid" border-bottom-color="green" font-weight="bold" text-align="outside">
           	<fo:page-number />
           </fo:block>
        </fo:static-content>
        <!-- END FOOTER -->
        
        <!-- CONTENT -->
        <fo:flow flow-name="Content">
          <fo:block font-size="9px">
                
          
            <!-- INVOICE INFO -->
            <fo:block font-family="ArialUnicodeMS" space-after="5px">
			        <xsl:value-of select="company/account/invoice_number"/> <fo:inline text-decoration="underline" font-size="11pt" font-weight="normal" space-start="4px">IE-<xsl:value-of select="data/invoice_info/invoice_number"/></fo:inline>
			   	  </fo:block>
			   	  
			      <fo:block font-family="ArialUnicodeMS" space-after="8px">
			       	<xsl:value-of select="company/account/document_date"/> <fo:inline text-decoration="underline" font-size="11pt" font-weight="normal" space-start="4px"><xsl:value-of select="data/invoice_info/invoice_date"/></fo:inline>
			   	  </fo:block>
			   	  
            <fo:block margin-bottom="20px">
              <fo:table  border-collapse="collapse"  font-size="10pt" font-family="Arial">
  					    <fo:table-column column-width="100mm"/>
  					    <fo:table-column column-width="90mm"/>
  					    <fo:table-body font-family="ArialUnicodeMS">
  					    
  					        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/title"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/receiver/title"/></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					        
  					        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/line_1"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="data/invoice_info/invoice_receiver"/></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					        
  					        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/line_2"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="data/invoice_info/invoice_receiver_address"/></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					        
  					        
  					        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/line_3"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					        
  					        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left">&#160;</fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left">&#160;</fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					        
  					        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/line_4"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					         <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/line_5"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
							
										        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left">&#160;</fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left">&#160;</fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					        
  					        <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/line_6"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
  					         <fo:table-row>
  					            <fo:table-cell>
  					                <fo:block text-align="left"><xsl:value-of select="company/account/suplyer/line_7"/></fo:block>
  					            </fo:table-cell>
  					            <fo:table-cell>
  					                <fo:block text-align="left"></fo:block>
  					            </fo:table-cell>
  					        </fo:table-row>
							
  				    	</fo:table-body>   
  				    </fo:table>
            </fo:block>
            
            
			    <!-- INVOICE INFO -->
            	
            	<fo:table border-collapse="collapse"  font-size="12pt" font-family="ArialUnicodeMS">
				    <fo:table-column column-width="10mm"/>
				    <fo:table-column column-width="90mm"/>
				    <fo:table-column column-width="30mm"/>
				    <fo:table-column column-width="30mm"/>
				    <fo:table-column column-width="30mm"/>
				    <fo:table-header font-size="10px" font-weight="bold">
				        <fo:table-row>
				            <fo:table-cell padding="2pt" border-bottom="1px solid #aaaaaa">
				                <fo:block text-align="center">NR.</fo:block>
				            </fo:table-cell>
				            <fo:table-cell padding="2pt" border-bottom="1px solid #aaaaaa">
				                <fo:block text-align="center">PAVADINIMAS</fo:block>
				            </fo:table-cell>
				            <fo:table-cell padding="2pt" border-bottom="1px solid #aaaaaa">
				                <fo:block text-align="center">KAINA, Lt</fo:block>
				            </fo:table-cell>
				            <fo:table-cell padding="2pt" border-bottom="1px solid #aaaaaa">
				                <fo:block text-align="center">KIEKIS, vnt.</fo:block>
				            </fo:table-cell>
				            <fo:table-cell padding="2pt" border-bottom="1px solid #aaaaaa">
				                <fo:block text-align="center">SUMA, Lt</fo:block>
				            </fo:table-cell>
				        </fo:table-row>
				    </fo:table-header>
				    <fo:table-body font-family="ArialUnicodeMS">
				    	<xsl:for-each select="data/items/item">
				    		<fo:table-row>
					            <fo:table-cell padding="2pt" border-bottom="1px dashed #aaaaaa"  border-right="3px solid #e9fbfe">
					                <fo:block text-align="center"><xsl:value-of select="number"/></fo:block>
					            </fo:table-cell>
					            <fo:table-cell padding="2pt" border-bottom="1px dashed #aaaaaa"  border-right="3px solid #e9fbfe">
					                <fo:block text-align="left"><xsl:value-of select="title"/> </fo:block>
					            </fo:table-cell>
					            <fo:table-cell padding="2pt" border-bottom="1px dashed #aaaaaa"  border-right="3px solid #e9fbfe">
					                <fo:block text-align="center"><xsl:value-of select="price"/></fo:block>
					            </fo:table-cell>
					            <fo:table-cell padding="2pt" border-bottom="1px dashed #aaaaaa"  border-right="3px solid #e9fbfe">
					                <fo:block text-align="center"><xsl:value-of select="amount"/></fo:block>
					            </fo:table-cell>
					            <fo:table-cell padding="2pt" border-bottom="1px dashed #aaaaaa">
					                <fo:block text-align="center"><xsl:value-of select="total"/></fo:block>
					            </fo:table-cell>
					        </fo:table-row>
				    	</xsl:for-each>
				    </fo:table-body>
				</fo:table>
				<fo:block margin-left="80mm" space-before="10px" keep-together="always" keep-with-next="always" text-align="right">
					<fo:table  border-collapse="collapse"  font-size="10pt" font-family="Arial">
					    <fo:table-column column-width="30mm"/>
					    <fo:table-column column-width="30mm"/>
					    <fo:table-body font-family="ArialUnicodeMS">
					        <fo:table-row>
					            <fo:table-cell>
					                <fo:block text-align="right"><xsl:value-of select="company/items_footer/total"/></fo:block>
					            </fo:table-cell>
					            <fo:table-cell margin-left="52mm">
					                <fo:block text-align="left"><xsl:value-of select="data/pay/total_no_pvm"/></fo:block>
					            </fo:table-cell>
					        </fo:table-row>
					        <xsl:if test="data/pay/discount != '0.00 Lt (0%)'">
						        <fo:table-row>
						            <fo:table-cell>
						                <fo:block text-align="right"><xsl:value-of select="company/items_footer/discount"/></fo:block>
						            </fo:table-cell>
						            <fo:table-cell margin-left="52mm">
						                <fo:block text-align="left"><xsl:value-of select="data/pay/discount"/></fo:block>
						            </fo:table-cell>
						        </fo:table-row>
					        </xsl:if>
					        <fo:table-row>
					            <fo:table-cell>
					                <fo:block text-align="right"><xsl:value-of select="company/items_footer/sum_with_discount"/></fo:block>
					            </fo:table-cell>
					            <fo:table-cell margin-left="52mm">
					                <fo:block text-align="left"><xsl:value-of select="data/pay/sum_with_discount"/></fo:block>
					            </fo:table-cell>
					        </fo:table-row>
					        <xsl:if test="data/pay/delivery != '0.00 Lt'">
						        <fo:table-row>
						            <fo:table-cell>
						                <fo:block text-align="right"><xsl:value-of select="company/items_footer/delivery"/></fo:block>
						            </fo:table-cell>
						            <fo:table-cell margin-left="52mm">
						                <fo:block text-align="left"><xsl:value-of select="data/pay/delivery"/></fo:block>
						            </fo:table-cell>
						        </fo:table-row>
					        </xsl:if>
					        <fo:table-row>
					            <fo:table-cell>
					                <fo:block text-align="right"><xsl:value-of select="company/items_footer/pvm"/></fo:block>
					            </fo:table-cell>
					            <fo:table-cell margin-left="52mm">
					                <fo:block text-align="left"><xsl:value-of select="data/pay/pvm"/></fo:block>
					            </fo:table-cell>
					        </fo:table-row>
					        <fo:table-row>
					            <fo:table-cell>
					                <fo:block font-size="10pt" text-align="right"><xsl:value-of select="company/items_footer/sum"/></fo:block>
					            </fo:table-cell>
					            <fo:table-cell margin-left="52mm">
					                <fo:block font-size="10pt" text-align="left"><xsl:value-of select="data/pay/total"/></fo:block>
					            </fo:table-cell>
					        </fo:table-row>
				    	</fo:table-body>   
				    </fo:table>
            	</fo:block>
            	
				<fo:block space-before="20px" line-height="16px">
                	<fo:block font-family="ArialUnicodeMS" space-before="20px" font-weight="bold">
                	<xsl:value-of select="company/items_footer/sum_in_words"/> <fo:inline space-start="10px" font-weight="normal" text-decoration="underline"><xsl:value-of select="data/pay/total_in_words"/></fo:inline>
	            	</fo:block>
            	</fo:block>
            	<!-- 
            	<fo:block space-before="20px" line-height="16px">
                	<fo:block font-family="ArialUnicodeMS" space-before="20px" font-weight="bold">
                	<xsl:value-of select="company/items_footer/invoice_created"/> <fo:inline space-start="10px" font-weight="normal" text-decoration="underline"><xsl:value-of select="data/pay/invoice_by"/></fo:inline>
	            	</fo:block>
            	</fo:block>
            	-->
            	<fo:block margin="0.2mm" padding="5px" font-size="8px" background-color="#FFFFFF" space-before="10px">
                <fo:block font-family="ArialUnicodeMS" text-align="left" space-after="10px">
                		<fo:inline font-size="11px"><xsl:value-of select="company/footer/block1"/> <xsl:value-of select="data/invoice_info/invoice_number"/></fo:inline> 
	            	</fo:block >
	            	<fo:block font-family="ArialUnicodeMS" text-align="left">
                		<xsl:value-of select="company/footer/block4"/> 
	            	</fo:block >
	            	<fo:block font-family="ArialUnicodeMS" space-before="10px" text-align="left">
                		<xsl:value-of select="company/footer/block2"/> 
	            	</fo:block>
	            	<fo:block font-family="ArialUnicodeMS" space-before="10px" text-align="left">
                		<xsl:value-of select="company/footer/block3"/> 
	            	</fo:block>
            	</fo:block>
            </fo:block>
            
        </fo:flow>
    </fo:page-sequence>

   </fo:root>
 </xsl:template>
 
  <xsl:template match="header">
 	<!-- HEADER -->
	<fo:static-content flow-name="Header">
       <fo:block font-family="ArialUnicodeMS" font-size="8px" font-weight="bold" color="silver" border-bottom-style="solid" border-bottom-color="black" border-bottom-width="1px">
         <fo:block line-height="30px" 
           		  background-image="url('../data/images/logo.gif')" 
           		  background-repeat="no-repeat" 
           		  background-position-horizontal="left" 
           		  background-position-vertical="top" 
           		  text-align="right"
           		  color="#000000">
	  		   <xsl:value-of select="headline"/>
		     </fo:block>
       </fo:block> 
      <!-- <fo:block font-family="ArialUnicodeMS" color="#a1a1a1" text-align="center" font-size="8px" margin-top="5px"> 
        <xsl:value-of select="head_info"/>
      </fo:block> -->
    </fo:static-content>
    <!-- END HEADER -->
  </xsl:template>
  
 
</xsl:stylesheet>
