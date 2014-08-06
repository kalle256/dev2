<?php
  session_start();

  if ( isset( $_GET['NaytaKuvaHakemisto'] ))
    $_SESSION['NaytaKuvaHakemisto'] = urldecode( $_GET['NaytaKuvaHakemisto'] );

  if ( !$_SESSION['NaytaKuvaHakemisto'] )
    $_SESSION['NaytaKuvaHakemisto'] = "kuvat/Raippaluoto";

  date_default_timezone_set('Europe/Helsinki');

  ini_set("display_errors", 1);
  ini_set("error_reporting", E_ALL | E_STRICT);
  
  
  include 'luokat/SivustonMuodostaja.php';
  include 'luokat/GallerianMuodostaja.php';
  include 'luokat/PainikkeidenMuodostaja.php';
  include 'luokat/HakemistoLinkkienRakentaja.php'; 
  include 'luokat/KayttajaOikeuksienHallinta.php';   
  include 'luokat/ApplicationData.php';   
  
  
  $ApplicationData       = new ApplicationData();  

  $KayttajanHallinta     = new KayttajaOikeuksienHallinta( );
  
  $HTMLRakentaja         = new SivustonMuodostaja( );

  $KuvaHakemistoLinkit   = new HakemistoLinkkienRakentaja( "kuvat" );

  $PainikeToiminnot      = new PainikkeidenMuodostaja("");

  $Galleriat             = new GallerianMuodostaja( $_SESSION['NaytaKuvaHakemisto'] ); 
  
  
  $HTMLRakentaja->WebSivustoonVasenNavigaatio( $PainikeToiminnot->MuodostaPainikkeet() );

  if ( !(isset( $_SESSION['SisaanKirjautunutKayttaja']) ) )
    $HTMLRakentaja->WebSivustonRunkoaUTF8( file_get_contents( "htmlTemplatet/EtusivuEiKirjautuneelleKayttajalle.html"));    
  else {
    $HTMLRakentaja->KorvaaTag("blue-imp-gallery-controls.html");    
    $HTMLRakentaja->WebSivustonRunkoa( $Galleriat->MuodostaKuvaGalleria() );
    $HTMLRakentaja->KorvaaTag("blue-imp-gallery-JavaScripteja.html");
  }          

  if ( isset($_SESSION['SisaanKirjautunutKayttaja']) )  
    $HTMLRakentaja->WebSivustonRunkoaUTF8( $KuvaHakemistoLinkit->MuodostaHakemistoLinkitHTML() );

  file_put_contents("DEBUG-KuvaHakemistonKuvaus", $Galleriat->KuvaHakemistonKuvaus );

  $HTMLRakentaja->TulostaWebSivusto();

?>