<?php 

class sis_ses {

  private $usu;

  public $uri;

  public function __construct(){
  }

  // ejecucion desde administrador
  static function log() : string {
    
    $_ = "<h2>hola desde php<c>!</c></h2>";

    // recorrer tablas de un esquema    
    /* 
    foreach( api_sql::est(DAT_ESQ,'lis','hol_','tab') as $est ){
      $_ .= "ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>";
    } 
    */

    
    
    return $_;
  }  

  // form: inicio de sesion
  public function ini(){
    $_ = "
    <form class='dat' action=''>

      <fieldset>

        <label for=''>Email</label>
        <input type='mail'>

      </fieldset>

      <fieldset>

        <label for=''>Password</label>
        <input type='password'>

      </fieldset>

    </form>";
  }

  // form: cerrar sesion
  public function fin(){
    $_ = "    
    <form class='dat' action=''>

    </form>";
  }

}