<?php


  class PainikkeidenMuodostaja {
  
    private $PainikkeetHTML;
    
    public function __construct( ) {

    }
    
    public function MuodostaPainikkeet( ) {


      if ( isset($_SESSION['SisaanKirjautunutKayttaja']) ) {

        $this->PainikkeetHTML = "\n<form  name=\"AsetaTila\" action=\"index.php\">\n ";
      
        $this->PainikkeetHTML .= "\n<form name=\"KirjaaUlos\" action=\"index.php\">\n ";
        $this->PainikkeetHTML .= "<input type=\"hidden\" name=\"KirjauduUlosSivustoilta\" value=\"KirjaaUlosKayttaja\" />";
        $this->PainikkeetHTML .= "<button type=\"submit\" class=\"css3button\" formmethod=\"get\">Kirjaudu ulos</button>";        
        $this->PainikkeetHTML .= "</form>\n<br/>\n";

        $this->PainikkeetHTML .= "\n<form name=\"NaytaKuvagalleriat\" action=\"index.php\">\n ";
        $this->PainikkeetHTML .= "<input type=\"hidden\" name=\"NaytaKuvagalleriat\" value=\"NaytaKuvagalleriat\" />";
        $this->PainikkeetHTML .= "<button type=\"submit\" class=\"css3button\" formmethod=\"get\">N‰yt‰ kuvagalleriat</button>";        
        $this->PainikkeetHTML .= "</form>\n<br/>\n";
      }        
      else {
        $this->PainikkeetHTML  = "\n<button class=\"css3button\" id=\"KirjautumisDialogPainike\">Kirjaudu sis‰‰n</button>\n ";
      
      }        

      return mb_convert_encoding (  $this->PainikkeetHTML , "UTF-8");
    }
  }
?>