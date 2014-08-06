<?php

  require_once( 'luokat/XMLKasittelija.php' ); 

  class HakemistoLinkkienRakentaja {
    
    private $KuvaHakemistotPolku;
    
    private $XMLTietojenKasittely;    
    
    public $HakemistoLinkitHTML;

    public function __construct( $KuvaHakemistotPolku ) {

      $this->KuvaHakemistotPolku = $KuvaHakemistotPolku;
      $this->HakemistoLinkitHTML = "";

    }
    
    private function AsetaKuvaHakemistonTietoturva( $TarkasteltavaHakemisto ) {
      
      file_put_contents( $TarkasteltavaHakemisto . "/.htaccess", "Options -Indexes" ); 
      
    }    
    
    public function MuodostaHakemistoLinkitHTML() {
      
      global $ApplicationData;       
      
      if ( is_dir( $this->KuvaHakemistotPolku ) ) {
        
        system( "ls -A -1 $this->KuvaHakemistotPolku > $this->KuvaHakemistotPolku/HakemistoListaus.txt" );

        $HakemistonHakemistot = file( "$this->KuvaHakemistotPolku/HakemistoListaus.txt" );
      
        foreach ( $HakemistonHakemistot as $line_num => $HakemistonHakemisto ) {
          
          $HakemistonHakemisto = trim( $HakemistonHakemisto );

          if ( is_dir( $this->KuvaHakemistotPolku . "/" . $HakemistonHakemisto ) ) {
            
            $this->XMLTietojenKasittely= new XMLKasittelija( $this->KuvaHakemistotPolku . "/" . $HakemistonHakemisto );
            
            $this->XMLTietojenKasittely->LataaKuvienXMLTiedot();

            $HakemistonKuvausString = $this->XMLTietojenKasittely->NoudaHakemistonSelite();
            
            if ( mb_convert_encoding ( $HakemistonKuvausString, "ISO-8859-1", "UTF-8" ) == "Tyhjä hakemistokuvaus vielä" )
              $HakemistonKuvausStringHTML =  $this->KuvaHakemistotPolku . " - " . $HakemistonHakemisto;
            else
              $HakemistonKuvausStringHTML =  $HakemistonKuvausString;
                          
            $URLEnkoodattuPolku = urlencode( $this->KuvaHakemistotPolku . "/" . $HakemistonHakemisto );
                
            $this->HakemistoLinkitHTML .= "<tr><td>" . 
                                          "<a href=\"?NaytaKuvaHakemisto=$URLEnkoodattuPolku  \"> $HakemistonKuvausStringHTML</a>" .
                                          "</td></tr>\n";

            $this->AsetaKuvaHakemistonTietoturva( $this->KuvaHakemistotPolku . "/" . $HakemistonHakemisto );
      
          }  
        }
        return "<table>" . $this->HakemistoLinkitHTML . "</table>";
      } 
    }
  }    
  
?>