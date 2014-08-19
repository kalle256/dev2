<?php

  class ApplicationData {

    private $ApplicationLogFile;
    
    private $UserRequestLogFile;
    
    public function __construct( ) {

      $this->CreateDirStructure( );
      
      $this->ApplicationLogFile   = "ApplicationData/ApplicationLog/" . date('Ymd') . ".log";
      
      $this->UserRequestLogFile   = "ApplicationData/RequestLogs/" . $this->getClientIP() . "-" .  date('Ymd') . ".log";      
      
      $this->WriteStringToLog( "+++++++++++++++++++++++++", 
                               "Aloitetaan heijastus.fi kuvagalleria sovelluksen suorittaminen" );      
                               
      $this->WriteUserRequestLogsToFile( $this->getClientIP(), "Request lokitusta" );

    }
    
    private function WriteUserRequestLogsToFile( $StringToLogFile, $KommentinOtsikko ) {
      
      $indicesServer = array('PHP_SELF',
                             'argv',
                             'argc',
                             'GATEWAY_INTERFACE',
                             'SERVER_ADDR',
                             'SERVER_NAME',
                             'SERVER_SOFTWARE',
                             'SERVER_PROTOCOL',
                             'REQUEST_METHOD',
                             'REQUEST_TIME',
                             'REQUEST_TIME_FLOAT',
                             'QUERY_STRING',
                             'DOCUMENT_ROOT',
                             'HTTP_ACCEPT',
                             'HTTP_ACCEPT_CHARSET',
                             'HTTP_ACCEPT_ENCODING',
                             'HTTP_ACCEPT_LANGUAGE', 
                             'HTTP_CONNECTION',
                             'HTTP_HOST',
                             'HTTP_REFERER',
                             'HTTP_USER_AGENT',
                             'HTTPS',
                             'REMOTE_ADDR',
                             'REMOTE_HOST',
                             'REMOTE_PORT',
                             'REMOTE_USER',
                             'REDIRECT_REMOTE_USER',
                             'SCRIPT_FILENAME',
                             'SERVER_ADMIN',
                             'SERVER_PORT',
                             'SERVER_SIGNATURE',
                             'PATH_TRANSLATED',
                             'SCRIPT_NAME',
                             'REQUEST_URI',
                             'PHP_AUTH_DIGEST',
                             'PHP_AUTH_USER',
                             'PHP_AUTH_PW',
                             'AUTH_TYPE',
                             'PATH_INFO', 
                             'ORIG_PATH_INFO'   ) ;


      file_put_contents( $this->UserRequestLogFile, $this->CreateTimeStamp() . " " . $KommentinOtsikko . "\n", FILE_APPEND);      
      file_put_contents( $this->UserRequestLogFile, "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n", FILE_APPEND);

      
      foreach ($indicesServer as $arg) {

          if ( isset($_SERVER[$arg]) ) {
            if ( is_array( $_SERVER[$arg] ) )
              file_put_contents( $this->UserRequestLogFile, $arg . ' -- ' . print_r( $_SERVER[$arg], true) . "\n", FILE_APPEND);
            else 
              file_put_contents( $this->UserRequestLogFile, $arg . ' -- ' . $_SERVER[$arg] . "\n", FILE_APPEND);                          
          }
          else
            file_put_contents( $this->UserRequestLogFile, $arg . ' --\n', FILE_APPEND);
      }
      
      file_put_contents( $this->UserRequestLogFile, "\nXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX", FILE_APPEND);      
      file_put_contents( $this->UserRequestLogFile, "\n\n", FILE_APPEND);

      $this->LDWordWrapAFile( $this->ApplicationLogFile, "100" );
     
    }      
    


    private function getClientIP( ) {    

      if (isset($_SERVER)) {

          if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
              return $_SERVER["HTTP_X_FORWARDED_FOR"];

          if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];

          return $_SERVER["REMOTE_ADDR"];
      }

      if (getenv('HTTP_X_FORWARDED_FOR'))
        return getenv('HTTP_X_FORWARDED_FOR');

      if (getenv('HTTP_CLIENT_IP'))
        return getenv('HTTP_CLIENT_IP');

      return getenv('REMOTE_ADDR');
    }    
    
    private function CreateDirStructure( ) {

      if ( !is_dir("ApplicationData") )
        mkdir( "ApplicationData", 0777 );        

      if ( !is_dir("ApplicationData/ApplicationLog") )
        mkdir( "ApplicationData/ApplicationLog", 0777 );                

      if ( !is_dir("ApplicationData/RequestLogs") )
        mkdir( "ApplicationData/RequestLogs", 0777 );                

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