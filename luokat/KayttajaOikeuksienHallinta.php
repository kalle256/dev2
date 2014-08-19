<?php

  class KayttajaOikeuksienHallinta {
  
    public $KayttajaOikeusTiedosto;
    
    private $KuvausKayttajistaDOM;
    
    public function __construct( $KayttajaOikeusTiedosto ) {
      
      global $ApplicationData;

      $this->KayttajaOikeusTiedosto = $KayttajaOikeusTiedosto;
      
      $this->LataaKayttoOikeudetDOM();

      if ( isset ( $_POST['KayttajaNimi'] ) && isset ( $_POST['Salasana'] ) )
        $this->TarkastaKirjautuvaKayttaja();

      if ( isset ( $_POST['FBKayttajaEtuNimi'] ) && isset ( $_POST['FBKayttajaSukuNimi'] ) && isset ( $_POST['FBKayttajaID'] ) )
        $this->TarkastaFBnTunnistusPalvelunKauttaKirjautuvaKayttaja();


      if ( !is_file( $this->KayttajaOikeusTiedosto ) )
        $this->MuodostaSalasanatXMLTemplate();
        
      if ( isset( $_POST['KirjauduUlosSivustoilta'] ) )
        $this->KirjaaKayttajaUlos();        
        
      if ( !(isset( $_SESSION['SisaanKirjautunutKayttaja']) ) )        
        $_SESSION['SisaanKirjautunutKayttaja'] = "EiKirjautunutKayttaja";

      $ApplicationData->WriteStringToLog( $_SESSION['SisaanKirjautunutKayttaja'], "Session muuttujan lokitusta sisaankirjauduttaessa" );
      
    }
    

    private function TarkastaFBnTunnistusPalvelunKauttaKirjautuvaKayttaja( ) {

      $KayttajanTiedot = $this->KuvausKayttajistaDOM->getElementsByTagName('KayttajanTiedot') ;  

      $TempKayttajaNimi = "";
        
      foreach ($KayttajanTiedot as $YhdenKayttajanTiedot) {

        if ( ( strtolower ($_POST['FBKayttajaEtuNimi'] )  == strtolower ( $YhdenKayttajanTiedot->getAttribute('FBKayttajaEtuNimi')) ) &&  
               strtolower ($_POST['FBKayttajaSukuNimi'])  == strtolower ( $YhdenKayttajanTiedot->getAttribute('FBKayttajaSukuNimi')) ) {
                
          $_SESSION['SisaanKirjautunutKayttaja'] = $YhdenKayttajanTiedot->getAttribute('KayttajaNimi');
          $TempKayttajaNimi = $YhdenKayttajanTiedot->getAttribute('KayttajaNimi');
        }            
      }
      if (  $_SESSION['SisaanKirjautunutKayttaja'] != $TempKayttajaNimi ) {

//        $ApplicationData->WriteUnsuccesfulLogInTOlog( $_SESSION['SisaanKirjautunutKayttaja'], "Session muuttujan lokitusta sisaankirjauduttaessa" );  
        echo "--FBSISAANKIRJAUTUMINENEPAONNISTUI--";
        exit();
      }        
    }

    

    private function LataaKayttoOikeudetDOM() {
      
      $this->KuvausKayttajistaDOM = new DOMDocument( "1.0", "UTF-8" );

      $this->KuvausKayttajistaDOM->load( $this->KayttajaOikeusTiedosto );
        
    }      

    private function KirjaaKayttajaUlos() {

      global $ApplicationData;
      
      session_unset();
      session_destroy();
      session_write_close();
      setcookie(session_name(),'',0,'/');
      session_regenerate_id(true);

    }
 
    public function SaakoHakemistonEsittaaKayttajalle( $HakemistonOikeusvausString ) {
      
      global $ApplicationData;

      $KayttajanTiedot = $this->KuvausKayttajistaDOM->getElementsByTagName('KayttajanTiedot') ;  
        
      foreach ($KayttajanTiedot as $YhdenKayttajanTiedot) {

        if ( $_SESSION['SisaanKirjautunutKayttaja'] == $YhdenKayttajanTiedot->getAttribute('KayttajaNimi')) {
            
          $OikeudetElementit = $YhdenKayttajanTiedot->getElementsByTagName('Oikeudet');
        
          foreach ( $OikeudetElementit as $YksiOikeusElementti) {

            if ( $YksiOikeusElementti->nodeValue == $HakemistonOikeusvausString || $YksiOikeusElementti->nodeValue == "KaikkiOikeudet" )
              return true;
          }
          return false;
        }                    
      }
    }

 
    public function NoudaKayttajanKayttoliitymaToiminnallisuudet( ) {

      $KayttajanTiedot = $this->KuvausKayttajistaDOM->getElementsByTagName('KayttajanTiedot');
        
      foreach ($KayttajanTiedot as $YhdenKayttajanTiedot) {

        if ( $_SESSION['SisaanKirjautunutKayttaja'] == $YhdenKayttajanTiedot->getAttribute('KayttajaNimi') ) {
          
          if ( isset ( $YhdenKayttajanTiedot->getElementsByTagName('OikeudetKayttoliittymanToiminnallisuuteen')->item(0)->nodeValue ) ) 
            return $YhdenKayttajanTiedot->getElementsByTagName('OikeudetKayttoliittymanToiminnallisuuteen')->item(0)->nodeValue;
        }                                     
      }      
    }      
 
 
    private function TarkastaKirjautuvaKayttaja( ) {

      $KayttajanTiedot = $this->KuvausKayttajistaDOM->getElementsByTagName('KayttajanTiedot') ;  
        
      foreach ($KayttajanTiedot as $YhdenKayttajanTiedot) {

        if ( ( $_POST['KayttajaNimi'] == $YhdenKayttajanTiedot->getAttribute('KayttajaNimi')) && 
             ( $_POST['Salasana'] == $YhdenKayttajanTiedot->getElementsByTagName('Salasana')->item(0)->nodeValue ) ) {

//          echo $YhdenKayttajanTiedot->getElementsByTagName('Salasana')->item(0)->nodeValue.'<br>';          
          $_SESSION['SisaanKirjautunutKayttaja'] = $_POST['KayttajaNimi'];
        }            
      }
      if (  $_SESSION['SisaanKirjautunutKayttaja'] != $_POST['KayttajaNimi'] ) {

        echo "--SISAANKIRJAUTUMINENEPAONNISTUI--";
        exit();
      }        
    }
        

     private function MuodostaSalasanatXMLTemplate() {  

      $xml = new DOMDocument( "1.0", "UTF-8" );
      
      $xml->preserveWhiteSpace = FALSE;
      $xml->formatOutput = true;

        $xml_OikeudetDokumentti = $xml->createElement("OikeudetDokumentti");

          $xml_KayttajanTiedot = $xml->createElement("KayttajanTiedot");
          $xml_KayttajanTiedot->setAttribute('KayttajaNimi', 'Kalle' );          

            $xml_Salasana = $xml->createElement("Salasana", "Salasanani123");      
            $xml_KayttajanTiedot->appendChild( $xml_Salasana );            
      
            $xml_Oikeudet = $xml->createElement("Oikeudet", "yksityinen");      
            $xml_KayttajanTiedot->appendChild( $xml_Oikeudet );            

            $xml_KayttajanKuvaus = $xml->createElement("KayttajanKuvaus" , mb_convert_encoding("Näiden sivustojen pääkäyttäjä", "UTF-8" ) );
            $xml_KayttajanTiedot->appendChild( $xml_KayttajanKuvaus );            

      $xml_OikeudetDokumentti->appendChild( $xml_KayttajanTiedot );

          $xml_KayttajanTiedot = $xml->createElement("KayttajanTiedot");
          $xml_KayttajanTiedot->setAttribute('KayttajaNimi', 'Vieras' );          

            $xml_Salasana = $xml->createElement("Salasana", "SalasananiXXX");      
            $xml_KayttajanTiedot->appendChild( $xml_Salasana );            
      
            $xml_Oikeudet = $xml->createElement("Oikeudet", "vieras");      
            $xml_KayttajanTiedot->appendChild( $xml_Oikeudet );            

            $xml_KayttajanKuvaus = $xml->createElement("KayttajanKuvaus" , mb_convert_encoding("Näiden sivustojen vieras", "UTF-8" ) );
            $xml_KayttajanTiedot->appendChild( $xml_KayttajanKuvaus );            

      $xml_OikeudetDokumentti->appendChild( $xml_KayttajanTiedot );
      
      $xml->appendChild( $xml_OikeudetDokumentti );

      $xml->save( $this->KayttajaOikeusTiedosto );
    }

  
  }
?>