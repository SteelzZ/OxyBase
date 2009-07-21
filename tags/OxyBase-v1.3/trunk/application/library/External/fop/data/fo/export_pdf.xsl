<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
   				      xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
   				      xmlns:fo="http://www.w3.org/1999/XSL/Format">
  <xsl:output method="xml" indent="yes" encoding="UTF-8"/>
  <xsl:template match="/defect">
    <fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
    
      <fo:layout-master-set>
	      <fo:simple-page-master master-name="A4"
			                         page-width="210mm" page-height="297mm"
			                         margin-top="1cm"   margin-bottom="1cm"
			                         margin-left="1cm"  margin-right="1cm">
			    <fo:region-body region-name="Content"/>
		    </fo:simple-page-master>
		  
  		  <fo:page-sequence-master master-name="items">
  		  	<fo:single-page-master-reference master-reference="A4"/>
  		  </fo:page-sequence-master>
		  
	    </fo:layout-master-set>
	    
	    <fo:page-sequence master-reference="items">

        <!-- CONTENT -->
        <fo:flow flow-name="Content">
           
          <fo:block text-align="right" 
                    font-family="ArialUnicodeMS" 
                    font-size="8pt"
                    margin-bottom="5mm"><xsl:value-of select="received_date"/></fo:block>
                    
          <fo:block text-align="center" 
                    font-weight="bold" 
                    font-size="12pt" >UAB "AVITELOS PREKYBA"</fo:block>  
      
            
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="8pt"
                    margin-left="20mm"
                    margin-top="5mm"
                    margin-right="20mm">
               
            <fo:block>
                Įmonės kodas: 142054984
                <fo:inline space-start="50mm">Adresas: Taikos pr. 15 Klaipėda</fo:inline>
            </fo:block>          
            <fo:block>
                PVM mokėtojo kodas: LT420549811
                <fo:inline space-start="37.5mm">Tel.  8-46-383500/106</fo:inline>
            </fo:block>        
          </fo:block>                     
          
          <fo:block margin-top="15mm">
              <fo:table  border-collapse="collapse"  font-size="10pt" font-family="Arial">
                <fo:table-column column-width="110mm"/>
                <fo:table-column column-width="85mm"/>
                <fo:table-body font-family="ArialUnicodeMS">   
                      
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt">
                            <fo:block text-align="left">
                            
                              REMONTO DARBŲ AKTAS Nr.
                              <fo:inline space-start="5mm" 
                                         font-size="12pt" 
                                         text-decoration="underline"><xsl:value-of select="defect_number"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left"></fo:block>
                        </fo:table-cell>
                    </fo:table-row>
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt" number-columns-spanned="2">
                            <fo:block text-align="left">
                            
                              Vardas, pavardė, a.k., parašas:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="user_info"/>&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>      
                    </fo:table-row>
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left" font-weight="bold">
                            
                              Įgaliotas asmuo:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="representative"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              Parašas: 
                              <fo:inline space-start="5mm" 
                                         text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row> 
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Adresas:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="address"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              Telefonas: 
                              <fo:inline space-start="5mm"
                                         text-decoration="underline"><xsl:value-of select="phone"/></fo:inline>
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row>
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Gaminys ir modelis:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="product_title"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              Serijinis Nr.: 
                              <fo:inline space-start="5mm"
                                         text-decoration="underline"><xsl:value-of select="serial_number"/></fo:inline>
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row>  
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Papildoma komplektacija:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="suit"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row>  
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="6pt" padding-top="6pt" number-columns-spanned="2">
                            <fo:block text-align="left">
                            
                              Gedimas pagal klientą:
                              <fo:inline font-size="10pt"><xsl:value-of select="defect_title"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>     
                    </fo:table-row> 
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Priėmė iš kliento:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="who_accepted"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                            
                              Priemimo data:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="received_date"/></fo:inline>
                              
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row> 
                    
                     <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Iš remonto atsiėmiau:
                              <fo:inline font-size="10pt" 
                                         text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                               
                              <fo:inline text-decoration="underline" 
                                         font-size="7pt">(parašas)&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                              
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row> 
                    
                    <fo:table-row>
                        <fo:table-cell number-columns-spanned="2" padding-bottom="6pt" padding-top="6pt">
                            <fo:block text-align="left">
                            
                              Pastabos:
                              <fo:inline font-size="10pt"><xsl:value-of select="comments"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>    
                    </fo:table-row> 
                              
                     <fo:table-row>
                        <fo:table-cell number-columns-spanned="2" padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Atsiėmimo data:
                              <fo:inline space-start="5mm" 
                                 text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
                            
                            </fo:block>
                        </fo:table-cell>    
                    </fo:table-row> 
                                
                </fo:table-body>   
              </fo:table>
            </fo:block>
                  
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
            Atsiimant prekes, <fo:inline color="red">BŪTINA</fo:inline> pateikti remonto darbų aktą.
          </fo:block>
          
          <!-- SEPERATOR -->
          <fo:block margin-top="5mm" border-style="dashed" border-width="1px" margin-bottom="5mm"/>
          <!-- /SEPERATOR -->  
                  
          <fo:block text-align="right" 
                    font-family="ArialUnicodeMS" 
                    font-size="8pt"
                    margin-bottom="5mm"><xsl:value-of select="received_date"/></fo:block>
                    
          <fo:block text-align="center" 
                    font-weight="bold" 
                    font-size="12pt" >UAB "AVITELOS PREKYBA"</fo:block>  
      
            
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="8pt"
                    margin-left="20mm"
                    margin-top="5mm"
                    margin-right="20mm">
               
            <fo:block>
                Įmonės kodas: 142054984
                <fo:inline space-start="50mm">Adresas: Taikos pr. 15 Klaipėda</fo:inline>
            </fo:block>          
            <fo:block>
                PVM mokėtojo kodas: LT420549811
                <fo:inline space-start="37.5mm">Tel.  8-46-383500/106</fo:inline>
            </fo:block>        
          </fo:block>                     
          
          <fo:block margin-top="15mm">
              <fo:table  border-collapse="collapse"  font-size="10pt" font-family="Arial">
                <fo:table-column column-width="110mm"/>
                <fo:table-column column-width="85mm"/>
                <fo:table-body font-family="ArialUnicodeMS">   
                      
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt">
                            <fo:block text-align="left">
                            
                              REMONTO DARBŲ AKTAS Nr.
                              <fo:inline space-start="5mm" 
                                         font-size="12pt" 
                                         text-decoration="underline"><xsl:value-of select="defect_number"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left"></fo:block>
                        </fo:table-cell>
                    </fo:table-row>
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt" number-columns-spanned="2">
                            <fo:block text-align="left">
                            
                              Vardas, pavardė, a.k., parašas:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="user_info"/>&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>      
                    </fo:table-row>
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left" font-weight="bold">
                            
                              Įgaliotas asmuo:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="representative"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              Parašas: 
                              <fo:inline space-start="5mm" 
                                         text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row> 
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Adresas:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="address"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              Telefonas: 
                              <fo:inline space-start="5mm"
                                         text-decoration="underline"><xsl:value-of select="phone"/></fo:inline>
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row>
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Gaminys ir modelis:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="product_title"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              Serijinis Nr.: 
                              <fo:inline space-start="5mm"
                                         text-decoration="underline"><xsl:value-of select="serial_number"/></fo:inline>
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row>  
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Papildoma komplektacija:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="suit"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                              
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row>  
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="6pt" padding-top="6pt" number-columns-spanned="2">
                            <fo:block text-align="left">
                            
                              Gedimas pagal klientą:
                              <fo:inline font-size="10pt"><xsl:value-of select="defect_title"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>     
                    </fo:table-row> 
                    
                    <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Priėmė iš kliento:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="who_accepted"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                            
                              Priemimo data:
                              <fo:inline font-size="10pt"
                                         text-decoration="underline"><xsl:value-of select="received_date"/></fo:inline>
                              
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row> 
                    
                     <fo:table-row>
                        <fo:table-cell padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Iš remonto atsiėmiau:
                              <fo:inline font-size="10pt" 
                                         text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>
                        <fo:table-cell>
                            <fo:block text-align="left">
                               
                              <fo:inline text-decoration="underline" 
                                         font-size="7pt">(parašas)&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>
                              
                            </fo:block>
                        </fo:table-cell>
                    </fo:table-row> 
                    
                    <fo:table-row>
                        <fo:table-cell number-columns-spanned="2" padding-bottom="6pt" padding-top="6pt">
                            <fo:block text-align="left">
                            
                              Pastabos:
                              <fo:inline font-size="10pt"><xsl:value-of select="comments"/></fo:inline>
                            
                            </fo:block>
                        </fo:table-cell>    
                    </fo:table-row> 
                              
                     <fo:table-row>
                        <fo:table-cell number-columns-spanned="2" padding-bottom="3pt" padding-top="3pt">
                            <fo:block text-align="left">
                            
                              Atsiėmimo data:
                              <fo:inline space-start="5mm" 
                                 text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
                            
                            </fo:block>
                        </fo:table-cell>    
                    </fo:table-row> 
                                
                </fo:table-body>   
              </fo:table>
            </fo:block>
                  
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
            Atsiimant prekes, <fo:inline color="red">BŪTINA</fo:inline> pateikti remonto darbų aktą.
          </fo:block>
        </fo:flow>
        <!-- END CONTENT -->
        
      </fo:page-sequence>

   </fo:root>
 </xsl:template>
   
 
</xsl:stylesheet>
