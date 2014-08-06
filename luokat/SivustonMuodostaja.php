<?php


  class SivustonMuodostaja {
  
    private $SivustoTemplateTiedosto              = "htmlTemplatet/WebSivustoTemplate01.html";
    
    private $MuodostettuSivusto = "";  
    
    private $SivustonRunko = "";

    public function __construct( ) {

      $this->MuodostettavaWebSivu = file_get_contents( $this->SivustoTemplateTiedosto );
    }
    
    public function WebSivustonRunkoa( $Sisaltoa ) {

      $this->SivustonRunko .= $Sisaltoa;
    
    }
    
    public function WebSivustonRunkoaUTF8( $Sisaltoa ) {

      $this->SivustonRunko .= mb_convert_encoding (  $Sisaltoa , "UTF-8");
    
    }

    public function WebSivustoonVasenNavigaatio( $HTMLString ) {

      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=VasenNavigaatio>" , $HTMLString, $this->MuodostettavaWebSivu );
      
    }      

    public function KorvaaTag( $HTMLTiedostonNimi ) {

      $HTMLStringSivustoon = file_get_contents( "htmlTemplatet/" . $HTMLTiedostonNimi );
      $HTMLStringSivustoon = mb_convert_encoding (  $HTMLStringSivustoon , "UTF-8");
      $this->MuodostettavaWebSivu = str_replace( "<includeTAG=" . $HTMLTiedostonNimi . ">" , $HTMLStringSivustoon, $this->MuodostettavaWebSivu );
      
    }      

    public function TulostaWebSivusto() {
   
      $this->MuodostettuSivusto = str_replace( "<includeTAG=SivustonRunko>", $this->SivustonRunko, $this->MuodostettavaWebSivu );
      echo $this->MuodostettuSivusto;
      
      file_put_contents("NaytettyWebSivu.html", $this->MuodostettuSivusto);
      
    }
  }
  
?>