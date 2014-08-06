<?php


  class KayttajaOikeuksienHallinta {
  
    private $KayttajaOikeusTiedosto            = "../KayttoOikeudet.xml";
    
    public function __construct( ) {
      
      if ( !(isset( $_SESSION['SisaanKirjautunutKayttaja']) ) )
        $this->TarkastaKirjautuvaKayttaja();

      if ( !is_file( $this->KayttajaOikeusTiedosto ) )
        $this->MuodostaSalasanatXMLTemplate();
        
      if ( isset( $_GET['KirjauduUlosSivustoilta'] ) )
        $this->KirjaaKayttajaUlos();        
    }

    private function KirjaaKayttajaUlos() {

      unset ($_SESSION['SisaanKirjautunutKayttaja']);

    }
 
    private function TarkastaKirjautuvaKayttaja( ) {

      if ( isset ( $_POST['KayttajaNimi'] ) && isset ( $_POST['Salasana'] ) ) {
     
        $KuvausKayttajistaDOM = new DOMDocument( "1.0", "UTF-8" );

        $KuvausKayttajistaDOM->load( $this->KayttajaOikeusTiedosto );
        
        $KayttajanTiedot = $KuvausKayttajistaDOM->getElementsByTagName('KayttajanTiedot') ;  
        
        foreach ($KayttajanTiedot as $YhdenKayttajanTiedot) {

          if ( ( $_POST['KayttajaNimi'] == $YhdenKayttajanTiedot->getAttribute('KayttajaNimi')) && 
               ( $_POST['Salasana'] == $YhdenKayttajanTiedot->getElementsByTagName('Salasana')->item(0)->nodeValue ) ) {

//            echo $YhdenKayttajanTiedot->getElementsByTagName('Salasana')->item(0)->nodeValue.'<br>';          
            $_SESSION['SisaanKirjautunutKayttaja'] = $_POST['KayttajaNimi'];
          }            
        }
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