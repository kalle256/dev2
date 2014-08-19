<?php

    include '../luokat/XMLKasittelija.php';
    
    $XMLTietojenKasittelytAjax = new XMLKasittelija( "../" . $_POST['HakemistonPolkuPOSTssa'] );

    $XMLTietojenKasittelytAjax->LataaKuvienXMLTiedot();

    $XMLTietojenKasittelytAjax->AsetaHakemistonSelite( $_POST['HakemistoTekstiPOSTssa'] );

?>