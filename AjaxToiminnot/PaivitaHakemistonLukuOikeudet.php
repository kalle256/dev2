<?php

    include '../luokat/XMLKasittelija.php';
    
    $XMLTietojenKasittelytAjax = new XMLKasittelija( "../" . $_POST['HakemistonPolkuPOSTssa'] );

    $XMLTietojenKasittelytAjax->LataaKuvienXMLTiedot();

    $XMLTietojenKasittelytAjax->AsetaHakemistonOikeudet( $_POST['AseteltavatOikeudet'] );

    echo "OK";    
?>