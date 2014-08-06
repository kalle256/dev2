<?php

    include '../luokat/XMLKasittelija.php';
    
    $TiedostonTiedot = pathinfo( $_POST['KuvaPolkuineen'] );
    $KuvaTeksti = $_POST['KuvaTeksti'] ;    
     
    $KuvaTeksti = urldecode ( $KuvaTeksti );
    
    $HakemistoPolku = str_replace( "/thumbs", "", $TiedostonTiedot['dirname'] );

    $HakemistoPolku = "../" . $HakemistoPolku;
    
    $TiedostonNimi = $TiedostonTiedot['basename'];
    
    echo "Hei ->" . $TiedostonNimi . "<-POST22 data loppuu";    
    
    $XMLTietojenKasittelytAjax     = new XMLKasittelija( $HakemistoPolku );

    $XMLTietojenKasittelytAjax->LataaKuvienXMLTiedot();

    $XMLTietojenKasittelytAjax->AsetaKuvalleSelite( $TiedostonNimi, $KuvaTeksti );

?>