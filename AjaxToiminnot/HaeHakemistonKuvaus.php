<?php

    include '../luokat/XMLKasittelija.php';

    file_put_contents( "Debug0007X",  "-->> " . $_POST['HakemistoPolkuineen']);    


    $XMLTietojenKasittelytAjax     = new XMLKasittelija( "../" . $_POST['HakemistoPolkuineen'] );

    $XMLTietojenKasittelytAjax->LataaKuvienXMLTiedot();

    $HakemistonKuvausString = $XMLTietojenKasittelytAjax->NoudaHakemistonSelite();    
    
    if ( !(isset ( $_POST['EiMerkistoMuunnos']) ) )
      $HakemistonKuvausString = htmlentities($HakemistonKuvausString, ENT_QUOTES, "UTF-8");  
 
    header("Content-type: text/html; charset=UTF-8");
    echo $HakemistonKuvausString;    
    
    file_put_contents( "Debug00X",  $HakemistonKuvausString . " " . $_POST['HakemistoPolkuineen'] . " <-> " . mb_convert_encoding (  $HakemistonKuvausString ,  "UTF-8" ));    
    

?>