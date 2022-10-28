<?php  
  session_start();

  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  // directorios :: localhost / icpv.com.ar
  define('SYS_NAV', "http://{$_SERVER['HTTP_HOST']}/" );

  // Base de datos: local / produccion
  define('DAT_SER',"localhost");
  define('DAT_USU',"c1461857_api");
  define('DAT_PAS',"lu51zakoWA");
  define('DAT_ESQ',"c1461857_api");

  // OPERACIONES : clases
  define('DIS_OCU', "dis-ocu" );
  define('BOR_SEL', "bor-sel" );
  define('FON_SEL', "fon-sel" );

  // cargo sesion
  if( !isset($_SESSION['usu']) ){    
    $_SESSION['usu'] = 1;
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";
  }
  
  date_default_timezone_set( $_SESSION['ubi'] );

  $sis_ini = time();
  
  // cargo modulos
  require_once("./php/api.php");
  require_once("./php/usu.php");
  require_once("./php/hol.php");

  // cargo interface
  $_api = new _api();
  
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
      foreach( _sql::est(DAT_ESQ,'lis','hol_','tab') as $est ){
        $_ .= "";
      }
      // ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>

      return $_;
    }
    
    echo _obj::cod( !_obj::tip( $eje = _eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );  

    exit;
  }
  // cargo página
  else{
    // cargo aplicacion
    require_once("./php/app.php");
    
    $_app = new _app();        

    // cargo página
    $_app->htm();
  }
?>