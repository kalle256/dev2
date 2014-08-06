<?php

    include '../luokat/XMLKasittelija.php';

    $TiedostonTiedot = pathinfo( $_POST['KuvaPolkuineen'] );
     
    $HakemistoPolku = str_replace( "/thumbs", "", $TiedostonTiedot['dirname'] );

    $HakemistoPolku = "../" . $HakemistoPolku;
    
    $TiedostonNimi = $TiedostonTiedot['basename'];
    
    file_put_contents("debugAjaxXX", $HakemistoPolku  );    

    $XMLTietojenKasittelytAjax     = new XMLKasittelija( $HakemistoPolku );

    $XMLTietojenKasittelytAjax->LataaKuvienXMLTiedot();

    $KuvanTeksti = $XMLTietojenKasittelytAjax->NoudaKuvanSelite( $TiedostonNimi );
    
//    if ( mb_detect_encoding( $KuvanTeksti ) != 'ISO-8859-1' ) 
  
    if ( !(isset ( $_POST['EiMerkistoMuunnos']) ) )
      $KuvanTeksti = htmlentities($KuvanTeksti, ENT_QUOTES, "UTF-8");  
 
    header("Content-type: text/html; charset=UTF-8");
    echo $KuvanTeksti;    
    
    file_put_contents( "Debug00X",  $KuvanTeksti . " " . $_POST['KuvaPolkuineen'] . " <-> " . mb_convert_encoding (  $KuvanTeksti ,  "UTF-8" ));    
    

?>