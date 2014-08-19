<?php

require_once 'vendor/autoload.php';
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;

  class SivustonMuodostaja {
  
    private $SivustoTemplateTiedosto              = "htmlTemplatet/WebSivustoTemplate01.html";
    
    private $MuodostettuSivusto = "";  
    
    private $SivustonRunko = "";

    public function __construct( ) {
      
      global $ApplicationData;

      $this->MuodostettavaWebSivu = file_get_contents( $this->SivustoTemplateTiedosto );
      
      $this->AsetaJaTulkitseSESSIONmuuttujat(); 
           
    }
    
    private function AsetaJaTulkitseSESSIONmuuttujat(  ) {
      
      global $ApplicationData;      
     
      if ( !(isset($_SESSION['NaytettavaToiminneSivu']) ) ) 
        $_SESSION['NaytettavaToiminneSivu'] = "Etusivu";

      $ApplicationData->WriteStringToLog( $_SESSION['NaytettavaToiminneSivu'], "Session muuttujan NaytettavaToiminneSivu X01" );

      if ( isset( $_POST['EsitaKuvaHakemistotPOST'] )) {
        $_SESSION['NaytettavaToiminneSivu'] = "EsitaKuvaHakemistot";
        $ApplicationData->WriteStringToLog( $_SESSION['NaytettavaToiminneSivu'], "Session muuttujan NaytettavaToiminneSivu asettaminen POST muuttujan EsitaKuvaHakemistotPOST toimesta" );
        unset($_POST['EsitaKuvaHakemistotPOST']);
      }          

      if ( isset( $_GET['NaytaKuvaHakemisto'] )) {
        $_SESSION['NaytaKuvaHakemisto'] = urldecode( $_GET['NaytaKuvaHakemisto'] );
        $_SESSION['NaytettavaToiminneSivu'] = "EsitaPyydettyKuvaGalleriaHakemisto";
      }          
      
      $ApplicationData->WriteStringToLog( $_SESSION['NaytettavaToiminneSivu'], "Session muuttujan NaytettavaToiminneSivu X03" );


    }      
    
    public function WebSivustonRunkoa( $Sisaltoa ) {

      $this->SivustonRunko .= $Sisaltoa;
    
    }
    
    public function WebSivustonRunkoaUTF8( $Sisaltoa ) {

      $this->SivustonRunko .= mb_convert_encoding (  $Sisaltoa , "UTF-8");
    
    }

    public function WebSivustoonVasenNavigaatio( $HTMLString ) {

      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=VasenNavigaatio>" , $HTMLString, $this->MuodostettavaWebSivu );
      
    }      

    public function KorvaaTag( $HTMLTiedostonNimi ) {

      $HTMLStringSivustoon = file_get_contents( "htmlTemplatet/" . $HTMLTiedostonNimi );
      $HTMLStringSivustoon = mb_convert_encoding (  $HTMLStringSivustoon , "UTF-8");
      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=" . $HTMLTiedostonNimi . ">" , $HTMLStringSivustoon, $this->MuodostettavaWebSivu );
      
    }      


    public function AsetteleKayttajaKohtaisetKayttoliittymaToiminnot( ) {

      global $KayttajanHallinta;

      $jsTiedostonNimi = $KayttajanHallinta->NoudaKayttajanKayttoliitymaToiminnallisuudet();

      $jsStringSivustoon = file_get_contents( "js/" . $jsTiedostonNimi );
      $jsStringSivustoon = mb_convert_encoding (  $jsStringSivustoon , "UTF-8");
      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=KayttajaKohtainenMenuToiminnallisuus>" , $jsStringSivustoon, $this->MuodostettavaWebSivu );
      
    }      

    public function AsetteleFBkirjatumisLinkkiOLD() {

      $helper = new FacebookRedirectLoginHelper('http://www.heijastus.fi/keh', $appId = '1448860825387536', $appSecret = 'c391394b4db8d5a600b5b8915e8bced5' );
       
      $HTMLStringSivustoon = mb_convert_encoding (  '<a href="' . $helper->getLoginUrl() . '">Kirjaudu sis‰‰n FB tunnuksin</a>' , "UTF-8");
      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=KayttajanSisaankirjausFBTunnuksilla>" , $HTMLStringSivustoon, $this->MuodostettavaWebSivu );
      
      file_put_contents("temp/FBdebug01A", "DATAA02\n", FILE_APPEND);                 
      
      try {
        $session = $helper->getSessionFromRedirect();
      } catch(FacebookRequestException $ex) {
          // When Facebook returns an error
                file_put_contents("temp/FBdebug01", "DATAA02\n", FILE_APPEND);           
        } catch(\Exception $ex) {
          // When validation fails or other local issues
                file_put_contents("temp/FBdebug01", "DATAA03\n", FILE_APPEND);                     
                file_put_contents("temp/FBdebug01", print_r( $ex, true ) . "DATAA03B\n", FILE_APPEND);                     
        }
     
      // Tarkastetaan mik‰li on FB tunnistautuminen tehty
      if ( isset( $session ) ) {
        // graph api request for user data
        $request = new FacebookRequest( $session, 'GET', '/me' );
        $response = $request->execute();
        // get response
        $graphObject = $response->getGraphObject();
         
        file_put_contents("temp/FBdebug01", "DATAA04", FILE_APPEND);                              
        
        // print data
        file_put_contents("temp/FBdebug02X", print_r( $graphObject, true ), FILE_APPEND );
      }      
      

    }      


    public function AsetteleFBkirjatumisToiminnot() {

      $jsStringSivustoon = file_get_contents( "js/KayttajanSisaankirjausFBTunnuksilla.js" );
      $jsStringSivustoon = mb_convert_encoding (  $jsStringSivustoon , "UTF-8");
      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=KayttajanSisaankirjausFBTunnuksilla>" , $jsStringSivustoon, $this->MuodostettavaWebSivu );
      
    }      


    private function KayttajaJaAikaleimaTunnisteetSivulle() {
      
      global $ApplicationData;

      $HTMLStringSivustoon  = "<div id=\"AikaleimaTunnisteetSivulleID\">";
      $HTMLStringSivustoon .= date('d.m.Y H:m:s');
      $HTMLStringSivustoon .= "</div>";        
        
      $HTMLStringSivustoon = mb_convert_encoding (  $HTMLStringSivustoon , "UTF-8");
      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=KayttajaJaAikaleimaTunnisteetSivulle>", $HTMLStringSivustoon, $this->MuodostettavaWebSivu );
     
      if ( $_SESSION['SisaanKirjautunutKayttaja'] != "EiKirjautunutKayttaja" ) {
        
        $HTMLStringSivustoon  = "<br><br><div id=\"KirjautuneenKayttajanTiedot\">";
        $HTMLStringSivustoon .= "<hr> Kirjautunut k‰ytt‰j‰ : <br>" . $_SESSION['SisaanKirjautunutKayttaja'];
        $HTMLStringSivustoon .= "</div>";        

        $HTMLStringSivustoon = mb_convert_encoding (  $HTMLStringSivustoon , "UTF-8");
        $this->MuodostettavaWebSivu = str_replace( "<includeTAG=KirjautuneenKayttajanTiedot>", $HTMLStringSivustoon, $this->MuodostettavaWebSivu );
      }        
     
     
    }      

    public function TulostaWebSivusto() {
   
      $this->KayttajaJaAikaleimaTunnisteetSivulle();

      $this->MuodostettuSivusto = str_replace( "<includeTAG=SivustonRunko>", $this->SivustonRunko, $this->MuodostettavaWebSivu );

      $this->SiivoaKayttamattomatIncludeTAGMerkinnat();
      
      echo $this->MuodostettuSivusto;
      
      file_put_contents("temp/NaytettyWebSivu.html", $this->MuodostettuSivusto);
    }
    
    private function SiivoaKayttamattomatIncludeTAGMerkinnat() {
   
      $this->MuodostettuSivusto = str_replace( "<includeTAG=VasenNavigaatio>",                            "", $this->MuodostettuSivusto );
      $this->MuodostettuSivusto = str_replace( "<includeTAG=blue-imp-gallery-controls.html>",             "", $this->MuodostettuSivusto );      
      $this->MuodostettuSivusto = str_replace( "<includeTAG=SivustonRunko>",                              "", $this->MuodostettuSivusto );      
      $this->MuodostettuSivusto = str_replace( "<includeTAG=blue-imp-gallery-JavaScripteja.html>",        "", $this->MuodostettuSivusto );
      $this->MuodostettuSivusto = str_replace( "<includeTAG=SivustonRunko>",                              "", $this->MuodostettuSivusto );
      $this->MuodostettuSivusto = str_replace( "<includeTAG=KayttajaKohtainenMenuToiminnallisuus>",       "", $this->MuodostettuSivusto );      
      $this->MuodostettuSivusto = str_replace( "<includeTAG=KayttajanSisaankirjausFBTunnuksilla>",        "", $this->MuodostettuSivusto );            
      $this->MuodostettuSivusto = str_replace( "<includeTAG=KirjautuneenKayttajanTiedot>",                "", $this->MuodostettuSivusto );                  
      
    }
  }
?>