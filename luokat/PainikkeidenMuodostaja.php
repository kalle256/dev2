<?php


  class PainikkeidenMuodostaja {
  
    private $PainikkeetHTML = "";
    
    public function __construct( ) {

    }
    
    public function MuodostaPainikkeet( ) {

      if ( $_SESSION['SisaanKirjautunutKayttaja'] != "EiKirjautunutKayttaja" ) {
        $this->PainikkeetHTML  = "\n<button class=\"css3button\" id=\"UlosKirjautumisPainike\">Kirjaudu ulos</button>\n<br/>\n ";
      }        
      else {
        $this->PainikkeetHTML  = "\n<button class=\"css3button\" id=\"KirjautumisDialogPainike\">Kirjaudu sis‰‰n</button>\n<br/>\n ";
      }        
      
      $this->PainikkeetHTML  .= "\n <button class=\"css3button\" id=\"ListaaKuvaGalleriatKayttajalle\">Kuvagalleriat</button>\n ";

      $this->PainikkeetHTML  .= "\n <button class=\"css3button\" id=\"NaytaYhteystiedotPainike\">Yhteystiedot</button>\n ";

      if ( strpos($_SERVER['HTTP_USER_AGENT'], 'rv:11.0') !== false &&           // Mikali selaimena IE ei n‰yte css3button muotoiltuja painikkeita
           strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0;')!== false) {
        $this->PainikkeetHTML = str_replace("css3button", "", $this->PainikkeetHTML);
      }
      
      return mb_convert_encoding (  $this->PainikkeetHTML , "UTF-8");
    }
  }
?>