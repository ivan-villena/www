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
  $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";  
  if( !isset($_SESSION['usu']) ) $_SESSION['usu'] = 1;
  date_default_timezone_set( $_SESSION['ubi'] );
  $sis_ini = time();  
  
  // cargo modulos
  $sis_rec = [ 
    'api' => [ "sql", "lis", "dat", "eje", "obj", "ele", "arc", "tex", "num", "fec", "hol", "usu" ],
    'app' => [ "var", "lis", "dat", "val", "est", "tab" ]
  ];
  foreach( $sis_rec as $ide => $cla_lis ){
    
    foreach( $cla_lis as $cla ){
      if( file_exists("./php/$ide/$cla.php") ) require_once("./php/$ide/$cla.php");
    }
    if( file_exists("./php/$ide.php") ) require_once("./php/$ide.php");
  }

  // cargo interface
  $_api = new api();
  
  $_usu = new api_usu( $_SESSION['usu'] );
   
  // peticion AJAX
  if( isset($_REQUEST['_']) ){  

    // log del sistema por ajax
    function _log() : string {

      global $_api, $_usu;
      $_ = "  
      <h2>hola desde php<c>!</c></h2>
      ";

      // recorrer tablas
      /* 
      foreach( api_sql::est(DAT_ESQ,'lis','hol_','tab') as $est ){
        $_ .= "";
        // ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>
      } 
      */

      return $_;
    }
    
    echo api_obj::cod( !api_obj::tip( $eje = api_eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );  

    exit;
  }
  // cargo página
  else{
    // cargo aplicacion    
    
    $_app = new app();        

    // cargo página
    $_app->ini();
  }
?>