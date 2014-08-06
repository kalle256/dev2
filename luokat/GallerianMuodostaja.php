<?php


  // https://github.com/blueimp/Gallery#controls
  include 'luokat/XMLKasittelija.php';


  class GallerianMuodostaja {
    
    private $XMLTietojenKasittely;
    
    private $CompressionLimit = 1600000;  // Ie 2 MByte
    
    private $URLEncPath;
    
    private $MuodostettuKuvaGalleria = "";
    
    private $Hakemisto;

    public $KuvaHakemistonKuvaus;

    public function __construct( $Hakemisto ) {

      $this->Hakemisto   = $Hakemisto;

      $this->URLEncPath  = urlencode( $Hakemisto );   
      
      $this->URLEncPath  = str_replace( "%2F", "/", $this->URLEncPath);      

      $this->XMLTietojenKasittely     = new XMLKasittelija( $this->Hakemisto );

      $this->XMLTietojenKasittely->LataaKuvienXMLTiedot();
      
      $this->KuvaHakemistonKuvaus = $this->XMLTietojenKasittely->NoudaHakemistonSelite();
      
      if ( !is_dir($this->Hakemisto . "/thumbs") )
        mkdir( $this->Hakemisto . "/thumbs", 0777 );
        
      if ( !is_dir($this->Hakemisto . "/CompressedFiles") )
        mkdir( $this->Hakemisto . "/CompressedFiles", 0777 );
        
    }
    
    public function MuodostaKuvaGalleria() {
      
      system( "ls -A -1 $this->Hakemisto > $this->Hakemisto/KuvaTiedostoListaus.txt" );

      $HakemistonTiedostot = file( "$this->Hakemisto/KuvaTiedostoListaus.txt" );
      
      foreach ($HakemistonTiedostot as $line_num => $HakemistonTiedosto) {
        
        $HakemistonTiedosto = trim ($HakemistonTiedosto);
  
        $path_parts = pathinfo( $HakemistonTiedosto );  
        
             
        if ( is_file( $this->Hakemisto . "/" . $HakemistonTiedosto ) && isset( $path_parts['extension'] ) ) {

          if ( strtolower( $path_parts['extension'] ) == "jpg" ) {

            $URLEnkHakemistonTiedosto = urlencode( $HakemistonTiedosto);

            if ( !is_file($this->Hakemisto . "/thumbs/" . $HakemistonTiedosto))
              $this->MuodostaThumb($this->Hakemisto , $HakemistonTiedosto);
              
            if ( $this->CheckIfCompressionIsNeeded( $this->Hakemisto, $HakemistonTiedosto) ) {
              $this->CompressFile( $this->Hakemisto, $HakemistonTiedosto );
              $URLEncPathToFile = $this->URLEncPath . "/CompressedFiles/" . $URLEnkHakemistonTiedosto;
            }
            else               
              $URLEncPathToFile = $this->URLEncPath . "/" . $URLEnkHakemistonTiedosto;
              
            $KuvaTeksti = $this->XMLTietojenKasittely->NoudaKuvanSelite( $HakemistonTiedosto );

            $this->MuodostettuKuvaGalleria .= "<a href=\"$URLEncPathToFile\"title=\"$KuvaTeksti\" data-gallery >\n" . 
                                              "<img id=\"$this->Hakemisto/$HakemistonTiedosto\" src=$this->URLEncPath/thumbs/$URLEnkHakemistonTiedosto>" . 
                                              "</a>\n\n" ;
          }  
        }
      }
    
      return "<div id=\"links\">\n"   . 
              $this->MuodostettuKuvaGalleria . 
              "</div> \n";

    } 
    

    private function MuodostaThumb( $Hakemisto, $Tiedosto ) {

      $width= 100; 

      $im    = imagecreatefromjpeg( $Hakemisto . "/" . $Tiedosto );
      $orange = imagecolorallocate($im, 220, 210, 60);
      

      $old_x=imageSX($im);
      $old_y=imageSY($im);

      $new_w=(int)($width);
   
      if (($new_w<=0) or ($new_w>$old_x)) {
        $new_w=$old_x;
      }

      $new_h=($old_x*($new_w/$old_x));

      if ($old_x > $old_y) {
        $thumb_w=$new_w;
        $thumb_h=$old_y*($new_h/$old_x);

      }
      if ($old_x < $old_y) {
        $thumb_w=$old_x*($new_w/$old_y);
        $thumb_h=$new_h;
      }
      if ($old_x == $old_y) {
        $thumb_w=$new_w;
        $thumb_h=$new_h;
      }


      $thumb=ImageCreateTrueColor($thumb_w,$thumb_h);
      imagecopyresized($thumb,$im,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
  
    
    

/******************************************************************************************
 *  Kirjoitetaan kuvaan ©KK
 *  
 ******************************************************************************************/  
        
//    $string = 'cK';
//    $black = imagecolorallocate($thumb, 100, 100, 100);

    // prints a black "P" in the top left corner
//    imagechar($thumb, 4, 6, 1, $string[0], $black);
//    imagechar($thumb, 4, 18, 3, $string[1], $black);
//    imagechar($thumb, 4, 27, 3, $string[1], $black);

//    $Halkaisija = 12;
//    imagearc($thumb, 10,  10,  $Halkaisija,  $Halkaisija,  0, 360, $black);

/******************************************************************************************/  
   
   
      imagejpeg($thumb,$Hakemisto . "/thumbs/" . $Tiedosto ,90);
      imagedestroy($thumb);
    }
    
    
    
    private function CheckIfCompressionIsNeeded( $Hakemisto, $Tiedosto ) {    
    
      if ( filesize($this->Hakemisto . "/" . $Tiedosto) > $this->CompressionLimit  )
        return true;
      else 
        return false;        
    }      
    
    
  
    private function CompressFile( $Hakemisto, $Tiedosto ) {

      if ( is_file( $Hakemisto . "/CompressedFiles/" . $Tiedosto ) )
        return;  // Jos tiedosto jo pakattu niin ei pakata uudelleen

      $width= 2000; 

      $im    = imagecreatefromjpeg( $Hakemisto . "/" . $Tiedosto );
      $orange = imagecolorallocate($im, 220, 210, 60);
      
      $old_x=imageSX($im);
      $old_y=imageSY($im);

      $new_w=(int)($width);
   
      if (($new_w<=0) or ($new_w>$old_x)) {
        $new_w=$old_x;
      }

      $new_h=($old_x*($new_w/$old_x));

      if ($old_x > $old_y) {
        $thumb_w=$new_w;
        $thumb_h=$old_y*($new_h/$old_x);

      }
      if ($old_x < $old_y) {
        $thumb_w=$old_x*($new_w/$old_y);
        $thumb_h=$new_h;
      }
      if ($old_x == $old_y) {
        $thumb_w=$new_w;
        $thumb_h=$new_h;
      }


      $thumb=ImageCreateTrueColor($thumb_w,$thumb_h);
      imagecopyresized($thumb,$im,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
       
      imagejpeg($thumb,$Hakemisto . "/CompressedFiles/" . $Tiedosto ,90);
      imagedestroy($thumb);
    }



  }
  
?>