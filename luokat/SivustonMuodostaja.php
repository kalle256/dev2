<?php

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


    private function KayttajaJaAikaleimaTunnisteetSivulle() {
      
      global $ApplicationData;

      $HTMLStringSivustoon  = "<div id=\"AikaleimaTunnisteetSivulleID\">";
      $HTMLStringSivustoon .= date('d.m.Y H:m:s');
      $HTMLStringSivustoon .= "</div>";        
        
      $HTMLStringSivustoon = mb_convert_encoding (  $HTMLStringSivustoon , "UTF-8");
      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=KayttajaJaAikaleimaTunnisteetSivulle>", $HTMLStringSivustoon, $this->MuodostettavaWebSivu );
     
      if ( $_SESSION['SisaanKirjautunutKayttaja'] != "EiKirjautunutKayttaja" ) {
        
        $HTMLStringSivustoon  = "<br><br><div id=\"KirjautuneenKayttajanTiedot\">";
        $HTMLStringSivustoon .= "<hr> Kirjautunut käyttäjä : <br>" . $_SESSION['SisaanKirjautunutKayttaja'];
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
      $this->MuodostettuSivusto = str_replace( "<includeTAG=KirjautuneenKayttajanTiedot>",                "", $this->MuodostettuSivusto );                  
      
    }
  }
?>