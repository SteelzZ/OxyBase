<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" 
   				      xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
   				      xmlns:fo="http://www.w3.org/1999/XSL/Format">
  <xsl:output method="xml" indent="yes" encoding="UTF-8"/>
  <xsl:template match="/guarantee">
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
                    margin-bottom="5mm">2008.11.26 08:59:52</fo:block>
                    
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
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="12pt"
                    margin-top="15mm">
             REMONTO DARBŲ AKTAS Nr. <fo:inline space-start="10mm" 
                                                font-size="10pt" 
                                                text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>       
          </fo:block>           

          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="4mm">
             Vardas, pavardė, a.k., parašas: <fo:inline space-start="5mm"
                                                        text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>       
          </fo:block> 
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Įgaliotas asmuo: <fo:inline space-start="5mm" 
                                         text-decoration="underline">Tomas Bartkus</fo:inline>  
             <fo:inline space-start="65mm">Parašas:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
          </fo:block> 
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Adresas: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Preilos 63-2 Neringa</fo:inline>  
             <fo:inline space-start="68mm">Telefonas:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">869989468</fo:inline>  
          </fo:block> 
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Gaminys ir modelis: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             <fo:inline space-start="63mm">Serijinis Nr.:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">869989468</fo:inline>  
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Papildoma komplektacija: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Gedimas pagal klientą: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="5mm">
             Priėmė iš kliento: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             <fo:inline space-start="43mm">Priemimo data:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">2008 11 26</fo:inline>  
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Iš remonto atsiėmiau: <fo:inline space-start="5mm" 
                                 text-decoration="underline">vardas</fo:inline>  
             <fo:inline space-start="68.5mm">&#160;</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline" font-size="7pt">(parašas)&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Pastabos: <fo:inline space-start="5mm" 
                                 text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
             
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Atsiimimo data: <fo:inline space-start="5mm" 
                                 text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
             
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
                    margin-bottom="5mm">2008.11.26 08:59:52</fo:block>
                    
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
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="12pt"
                    margin-top="15mm">
             REMONTO DARBŲ AKTAS Nr. <fo:inline space-start="10mm" 
                                                font-size="10pt" 
                                                text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>       
          </fo:block>           

          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="4mm">
             Vardas, pavardė, a.k., parašas: <fo:inline space-start="5mm"
                                                        text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>       
          </fo:block> 
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Įgaliotas asmuo: <fo:inline space-start="5mm" 
                                         text-decoration="underline">Tomas Bartkus</fo:inline>  
             <fo:inline space-start="65mm">Parašas:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
          </fo:block> 
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Adresas: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Preilos 63-2 Neringa</fo:inline>  
             <fo:inline space-start="68mm">Telefonas:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">869989468</fo:inline>  
          </fo:block> 
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Gaminys ir modelis: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             <fo:inline space-start="63mm">Serijinis Nr.:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">869989468</fo:inline>  
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Papildoma komplektacija: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Gedimas pagal klientą: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="5mm">
             Priėmė iš kliento: <fo:inline space-start="5mm" 
                                 text-decoration="underline">Pavadinimas</fo:inline>  
             <fo:inline space-start="43mm">Priemimo data:</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline">2008 11 26</fo:inline>  
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Iš remonto atsiėmiau: <fo:inline space-start="5mm" 
                                 text-decoration="underline">vardas</fo:inline>  
             <fo:inline space-start="68.5mm">&#160;</fo:inline>     
             <fo:inline space-start="5mm" 
                        text-decoration="underline" font-size="7pt">(parašas)&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Pastabos: <fo:inline space-start="5mm" 
                                 text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
             
          </fo:block>
          
          <fo:block font-family="ArialUnicodeMS" 
                    font-size="10pt"
                    margin-top="2mm">
             Atsiimimo data: <fo:inline space-start="5mm" 
                                 text-decoration="underline">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</fo:inline>  
             
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
