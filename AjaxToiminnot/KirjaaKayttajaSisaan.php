<?php

  if ( isset ( $_POST['KayttajaNimi'] ) && isset ( $_POST['Salasana'] ) ) {
     
    include '../luokat/KayttajaOikeuksienHallinta.php';

    $KayttajanHallinta     = new KayttajaOikeuksienHallinta( "../../KayttoOikeudet.xml" );

    $KuvausKayttajistaDOM = new DOMDocument( "1.0", "UTF-8" );

    $KuvausKayttajistaDOM->load( $KayttajanHallinta->KayttajaOikeusTiedosto );
        
    $KayttajanTiedot = $KuvausKayttajistaDOM->getElementsByTagName('KayttajanTiedot') ;  
        
    foreach ($KayttajanTiedot as $YhdenKayttajanTiedot) {

      if ( ( $_POST['KayttajaNimi'] == $YhdenKayttajanTiedot->getAttribute('KayttajaNimi')) && 
        ( $_POST['Salasana'] == $YhdenKayttajanTiedot->getElementsByTagName('Salasana')->item(0)->nodeValue ) ) {

//            echo $YhdenKayttajanTiedot->getElementsByTagName('Salasana')->item(0)->nodeValue.'<br>';          
        $_SESSION['SisaanKirjautunutKayttaja'] = $_POST['KayttajaNimi'];
      }            
    }
  }    
    

?>