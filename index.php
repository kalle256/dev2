<?php
  session_start();

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

  $KayttajanHallinta     = new KayttajaOikeuksienHallinta( "../../KayttoOikeudet.xml" );
  
  $HTMLRakentaja         = new SivustonMuodostaja();

  $KuvaHakemistoLinkit   = new HakemistoLinkkienRakentaja( "../kuvat" );

  $PainikeToiminnot      = new PainikkeidenMuodostaja();

  $Galleriat             = new GallerianMuodostaja( ); 
  
  $HTMLRakentaja->WebSivustoonVasenNavigaatio( $PainikeToiminnot->MuodostaPainikkeet() );


  if ( $_SESSION['NaytettavaToiminneSivu'] == "Etusivu" ){
    $HTMLRakentaja->WebSivustonRunkoaUTF8( file_get_contents( "htmlTemplatet/EtusivuEiKirjautuneelleKayttajalle.html"));    
  }    
  else if ( $_SESSION['NaytettavaToiminneSivu'] == "EsitaPyydettyKuvaGalleriaHakemisto" ){
    $HTMLRakentaja->KorvaaTag("blue-imp-gallery-controls.html");    
    $HTMLRakentaja->WebSivustonRunkoa( $Galleriat->MuodostaKuvaGalleria( $_SESSION['NaytaKuvaHakemisto'] ) );
    $HTMLRakentaja->KorvaaTag("blue-imp-gallery-JavaScripteja.html");
  }
  else if ( $_SESSION['NaytettavaToiminneSivu'] == "EsitaKuvaHakemistot" )
    $HTMLRakentaja->WebSivustonRunkoaUTF8( $KuvaHakemistoLinkit->MuodostaHakemistoLinkitHTML() );
  else { 
    $HTMLRakentaja->WebSivustonRunkoaUTF8( " VIRHE _SESSION['NaytettavaToiminneSivu'] käsittelyssä" );    
  }  

  $HTMLRakentaja->AsetteleKayttajaKohtaisetKayttoliittymaToiminnot();
  
  $HTMLRakentaja->AsetteleFBkirjatumisToiminnot();  
  
  $HTMLRakentaja->TulostaWebSivusto();
  
?>