<?php

function autoNameSpaces( $clase ){

  // : model-view-controller/className
  require_once( str_replace("\\","/", $clase ).".php" );
  
}

function autoload( $clase ){

  if( file_exists( $directorio = "php".str_replace('_','/',$clase).".php" ) ){
    
    require_once($directorio);
  }
  else{
    // error: no existe $clase 
  }
}

spl_autoload_register("autoload");

