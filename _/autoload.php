<?php

function autoNameSpaces( $clase ){

  // : model-view-controller/className

  require_once( str_replace("\\","/", $clase ).".php" );
  
}

function autoload( $clase ){

  require_once("php/".substr($clase,1).".php");

}

spl_autoload_register("autoload");

