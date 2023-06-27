<?php

if( !class_exists("Sincronario") ){

  require_once("./App/Sincronario.php");
}

Class Cilco {

  public object $Fec;

  public string $fec;

  public int    $sir;

  public function __construct( string | object $fecha ){

    $Fec = Fec::dat($fecha);

  }

}