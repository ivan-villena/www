<?php  
  // mostrar errores:
  // error_reporting(E_ALL);
  // ini_set('display_errors', '1');
  
  // directorios
  define('SYS_NAV', "http://{$_SERVER['HTTP_HOST']}/" );

  // OPERACIONES : clases
  define('DIS_OCU', "dis-ocu" );
  define('BOR_SEL', "bor-sel" );
  define('FON_SEL', "fon-sel" );

  // cargo sesion
  session_start(); 
  
  if( !isset($_SESSION['usu']) ){
    // interface
    $_SESSION['usu'] = 1;
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";
    // acceso a database
    $_SESSION['sql'] = [ 
      'ser' => $_SERVER['HTTP_HOST'],
      'usu' => "root", 
      'pas' => "", 
      'esq' => "api" 
    ];
  }
  
  date_default_timezone_set( $_SESSION['ubi'] );

  $sis_ini = time();
  
  // cargo interface
  require_once("php/api.php");
  $_api = new _api();
  
  // cargo usuario  
  require_once("php/usu.php");
  $_usu = new _usu( $_SESSION['usu'] );
   
  // peticion AJAX
  if( isset($_REQUEST['_']) ){  

    // log del sistema por ajax
    function _log() : string {

      global $_api, $_usu;
      $_ = "  
      <h2>hola desde php<c>!</c></h2>
      ";

      // recorrer tablas y 
      foreach( _sql::est("api",'lis','hol_','tab') as $est ){
        $_ .= "";
      }
      // ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>

      return $_;
    }
    
    echo _obj::cod( !_obj::tip( $eje = _eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );  

    die();
  }
  // cargo página
  else{

    // cargo modulos
    foreach( ['doc','app','hol'] as $cla ){ 

      require_once("php/$cla.php");
    }

    // cargo aplicacion    
    $_app = new _app();

    // cargo página
    $_app->ini();
  }
?>