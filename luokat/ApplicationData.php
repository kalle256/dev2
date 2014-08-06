<?php

  class ApplicationData {

    private $ApplicationLogFile;
    
    public function __construct( ) {

      $this->CreateDirStructure( );
      
      $this->ApplicationLogFile   = "ApplicationData/ApplicationLog/" . date('Ymd') . ".log";
      
      $this->WriteStringToLog( "+++++++++++++++++++++++++", 
                               "Aloitetaan heijastus.fi kuvagalleria sovelluksen suorittaminen" );      
    }
    
    private function CreateDirStructure( ) {

      if ( !is_dir("ApplicationData") )
        mkdir( "ApplicationData", 0777 );        

      if ( !is_dir("ApplicationData/ApplicationLog") )
        mkdir( "ApplicationData/ApplicationLog", 0777 );                

      if ( !is_dir("ApplicationData/debug") )
        mkdir( "ApplicationData/debug", 0777 );                

    }      

/**
 *  Kirjoitetaan log tiedostoon muotoiltu xml string
 */

    public function LDWordWrapAFile( $KatkottavanTiedostonNimi, $RivinMaxPituus ) {
      
      $KatkottavaAineisto = file_get_contents( $KatkottavanTiedostonNimi );
      
      $KatkottavaAineisto = wordwrap( $KatkottavaAineisto, $RivinMaxPituus, "\n", true );
      
      file_put_contents( $KatkottavanTiedostonNimi, $KatkottavaAineisto );

    }      

    public function AsetaXMLSisennykset( $XMLSisennettavaTiedosto ) {

      $docTemp = new DOMDocument();
      
      $docTemp->preserveWhiteSpace = FALSE;
      $docTemp->formatOutput = true;      
  
      $docTemp->load( $XMLSisennettavaTiedosto );
  
      $docTemp->save( $XMLSisennettavaTiedosto );
    
    }


    public function WriteXMLDOMToLog( $XMLStringToLogFile, $KommentinOtsikko ) {

      $xml = new DOMDocument( "1.0", "UTF-8" );
      $xml->preserveWhiteSpace = FALSE;
      $xml->formatOutput = true;

      $xml->loadXML( $XMLStringToLogFile );

      $TempXMLStringToFile = $xml->saveXML();

      file_put_contents( $this->ApplicationLogFile, $this->CreateTimeStamp() . " " . $KommentinOtsikko . "\n", FILE_APPEND);      
      file_put_contents( $this->ApplicationLogFile, "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n", FILE_APPEND);
      file_put_contents( $this->ApplicationLogFile, $TempXMLStringToFile, FILE_APPEND);
      file_put_contents( $this->ApplicationLogFile, "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", FILE_APPEND);      
      file_put_contents( $this->ApplicationLogFile, "\n\n", FILE_APPEND);      
      
    }      

    public function CreateTimeStamp() {
      
      $temp = microtime(true); 
      
      $micro = sprintf("%03d",($temp - floor($temp)) * 1000);      
      
      $TimeStampTemp = date( DATE_ATOM, time() );

      $TimeAtOPFormat = str_replace("+", "." . $micro . "+", $TimeStampTemp);  //       2014-04-07T12:08:38+03:00   ->  2014-04-07T12:08:38.123+03:00

      return $TimeAtOPFormat;
    }      


    public function WriteStringToLog( $StringToLogFile, $KommentinOtsikko ) {

      file_put_contents( $this->ApplicationLogFile, $this->CreateTimeStamp() . " " . $KommentinOtsikko . "\n", FILE_APPEND);      
      file_put_contents( $this->ApplicationLogFile, "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n", FILE_APPEND);
      file_put_contents( $this->ApplicationLogFile, $StringToLogFile, FILE_APPEND);
      file_put_contents( $this->ApplicationLogFile, "\nXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", FILE_APPEND);      
      file_put_contents( $this->ApplicationLogFile, "\n\n", FILE_APPEND);


      $this->LDWordWrapAFile( $this->ApplicationLogFile, "100" );
      
    }      

  }    
?>    