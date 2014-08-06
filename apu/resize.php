<?php

//  $pic = "http://192.168.101.57/kuva.jpg";
//  http://www.heijastus.fi/resize.php?aaropassi20131103D.jpg&width_in=250

   $pic = $_GET['pic_in'];
   $width= $_GET['width_in'];

   $im    = imagecreatefromjpeg( $pic );
   $orange = imagecolorallocate($im, 220, 210, 60);
   $px    = (imagesx($im) - 7.5 * strlen($string)) / 2;

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
   
   
  // Set the content type header - in this case image/jpeg
  header('Content-Type: image/jpeg');

  // Output the image
  imagejpeg($thumb);

  // Free up memory
  imagedestroy($thumb);

?>