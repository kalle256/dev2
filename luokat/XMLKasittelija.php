<?php
  
  class XMLKasittelija {

    private $KuvienTiedotXML;
  
    private $XMLKuvausTiedosto;
    
    private $KuvaHakemisto;
   
    public function __construct( $KuvaHakemisto ) {
      $this->XMLKuvausTiedosto = $KuvaHakemisto . "/xmlKuvaukset/KuvienTiedot.xml";
      $this->KuvaHakemisto = $KuvaHakemisto;
      
      if ( !is_dir($this->KuvaHakemisto . "/xmlKuvaukset") )
        mkdir( $this->KuvaHakemisto . "/xmlKuvaukset", 0777 );

    }    

    public function LataaKuvienXMLTiedot( ) {

//    $this->MuodostaXMLTemplate();
  
      if ( is_file( $this->XMLKuvausTiedosto ) )
        $this->LataaXMLTiedosto();
      else {
        $this->MuodostaXMLTemplate();
        $this->LataaXMLTiedosto();
      }
                      
    }    
 
 
    private function LataaXMLTiedosto( ) {

      $this->KuvienTiedotXML = new DOMDocument( "1.0", "UTF-8" );
      
      $this->KuvienTiedotXML->preserveWhiteSpace = FALSE;
      $this->KuvienTiedotXML->formatOutput = true;

      $this->KuvienTiedotXML->load( $this->XMLKuvausTiedosto );
 
    }


    public function NoudaHakemistonOikeudet() {

      if ( isset ( $this->KuvienTiedotXML ) ) {
        
        $HakemistonOikeudet = $this->KuvienTiedotXML->getElementsByTagName('HakemistonTiedot') ;        
        
        return $HakemistonOikeudet->item(0)->getElementsByTagName('Oikeudet')->item(0)->nodeValue;
      }                                     
    }      


    
       
    public function NoudaHakemistonSelite() {

      if ( isset ( $this->KuvienTiedotXML ) ) {
        
        $HakemistonKuvaus = $this->KuvienTiedotXML->getElementsByTagName('HakemistonTiedot') ;        
        
        return $HakemistonKuvaus->item(0)->getElementsByTagName('Kuvaus')->item(0)->nodeValue;
      }                                     
    }      
        
    public function AsetaHakemistonSelite( $SeliteString ) {

      if ( isset ( $this->KuvienTiedotXML ) ) {
        
        $this->KuvienTiedotXML->getElementsByTagName('HakemistonTiedot')->item(0)->getElementsByTagName('Kuvaus')->item(0)->nodeValue = $SeliteString;

        $this->KuvienTiedotXML->save( $this->XMLKuvausTiedosto );
      }                                     
    }      

    public function AsetaHakemistonOikeudet( $OikeudetString ) {

      if ( isset ( $this->KuvienTiedotXML ) ) {
        
        $this->KuvienTiedotXML->getElementsByTagName('HakemistonTiedot')->item(0)->getElementsByTagName('Oikeudet')->item(0)->nodeValue = $OikeudetString;

        $this->KuvienTiedotXML->save( $this->XMLKuvausTiedosto );
      }                                     
    }      

    
    public function NoudaKuvanSelite( $KuvaTiedosto ) {
      
      $KuvaTiedosto = strtolower( $KuvaTiedosto );      
      // muodostetaan xpath instanssi
      $xpath = new DomXpath( $this->KuvienTiedotXML );

      //  Tehdaan kysely xml dokumenttiin
      $HaettuKuvaSelite = $xpath->query('//KuvanTiedot[@KuvaID="' . $KuvaTiedosto . '"]')->item(0); 

      if ( !is_null($HaettuKuvaSelite) ) {
        return $HaettuKuvaSelite->getElementsByTagName( "KuvaTeksti" )->item(0)->nodeValue;                     
      }        
      else 
        return "";
    }      

    public function AsetaKuvalleSelite( $KuvaTiedosto, $Selite ) {
      
      $KuvaTiedosto = strtolower( $KuvaTiedosto );

      if ( mb_detect_encoding( $Selite ) != 'UTF-8' ) 
        $SeliteUTF8 = mb_convert_encoding ( $Selite, "UTF-8" );
      else 
        $SeliteUTF8 = $Selite;
        
//      $SeliteUTF8 = utf8_encode($SeliteUTF8);        
        
      // muodostetaan xpath instanssi
      $xpath = new DomXpath( $this->KuvienTiedotXML );

      //  Tehdaan kysely xml dokumenttiin
      $KuvanTiedot = $xpath->query('//KuvanTiedot[@KuvaID="' . $KuvaTiedosto . '"]')->item(0); 

      if (!is_null($KuvanTiedot)) {
        $KuvanTiedot->getElementsByTagName( "KuvaTeksti" )->item(0)->nodeValue = $SeliteUTF8;

      }        
      else {
       
        $KuvanTiedot = $this->KuvienTiedotXML->createElement("KuvanTiedot");
        $KuvanTiedot->setAttribute('KuvaID', $KuvaTiedosto);

        $xml_KuvaTeksti = $this->KuvienTiedotXML->createElement("KuvaTeksti",  $SeliteUTF8 );
        $KuvanTiedot->appendChild( $xml_KuvaTeksti );

        $this->KuvienTiedotXML->getElementsByTagName( "Dokumentti" )->item(0)->appendChild( $KuvanTiedot );
      }        
    
      $this->KuvienTiedotXML->save( $this->XMLKuvausTiedosto );
    }      



    private function MuodostaXMLTemplate() {  

      $xml = new DOMDocument( "1.0", "UTF-8" );
      
      $xml->preserveWhiteSpace = FALSE;
      $xml->formatOutput = true;

      $xml_Dokumentti = $xml->createElement("Dokumentti");

      $xml_HakemistonTiedot = $xml->createElement("HakemistonTiedot");
      $xml_HakemistonTiedot->setAttribute('HakemistonNimi', mb_convert_encoding ( $this->KuvaHakemisto, "UTF-8" ) );

      $xml_TiedotPerustettu = $xml->createElement("TiedotPerustettu", date('d-m-Y'));      
      $xml_HakemistonTiedot->appendChild( $xml_TiedotPerustettu );            
      
      $xml_Oikeudet = $xml->createElement("Oikeudet", "yksityinen");      
      $xml_HakemistonTiedot->appendChild( $xml_Oikeudet );            

      $xml_Kuvaus = $xml->createElement("Kuvaus" , mb_convert_encoding("Tyhjä hakemistokuvaus vielä", "UTF-8" ) );
      $xml_HakemistonTiedot->appendChild( $xml_Kuvaus );            

      $xml_Dokumentti->appendChild( $xml_HakemistonTiedot );
      
      $xml->appendChild( $xml_Dokumentti );

      $xml->save( $this->XMLKuvausTiedosto );
    }
  }    
  
?>