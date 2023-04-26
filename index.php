<?php  
  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  // Sesion
    session_start();

    if( !isset($_SESSION['usu']) ){ 
      $_SESSION['usu'] = 1;
      $_SESSION['ini'] = time();
      $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";
    }

    date_default_timezone_set( $_SESSION['ubi'] );
    $sis_ini = time();

  //
  // Constantes
  
    // Rutas :: localhost / icpv.com.ar  
    define('SYS_NAV', "http://{$_SERVER['HTTP_HOST']}/" );
    
    // Base de datos: local / produccion
    define('DAT_SER', 'localhost');
    define('DAT_USU', "c1461857_api");
    define('DAT_PAS', "lu51zakoWA");
    define('DAT_ESQ', "c1461857_api");
    
    // Elementos : Clases
    define('DIS_OCU', "dis-ocu");
    define('BOR_SEL', "bor-sel");
    define('FON_SEL', "fon-sel");
  //
  // Modulos
    require_once("./_/sql.php");
    require_once("./api/Dat.php");
    require_once("./api/Arc.php");
    require_once("./api/Eje.php");
    require_once("./api/Ele.php");
    require_once("./api/Obj.php");
    require_once("./api/Lis.php");
    require_once("./api/Num.php");
    require_once("./api/Tex.php");
    require_once("./api/Fig.php");
    require_once("./api/Fec.php");
    require_once("./api/Hol.php");
    require_once("./api/Doc.php");
    require_once("./api/Usu.php");
    require_once("./api/App.php");

    $Usu = new Usu( $_SESSION['usu'] );
  //

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // peticion AJAX
  if( isset($_REQUEST['_']) ){
    
    // ejecucion
    function sis_log() : string {
      
      $_ = "<h2>hola desde php<c>!</c></h2>";

    
      /* Recorrer tablas de un esquema:

      foreach( api_sql::est(DAT_ESQ,'lis','hol_','tab') as $est ){
        $_ .= "ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>";
      } 
      */
      
      /*  Invocando funciones
        include("./_/hol/sel.php");
        $_ = hol_sel_par_gui();
      */
      
      return $_;
    }

    // ver cabeceras para api's: tema no-cors
    echo Obj::val_cod( !Obj::val_tip( $eje = Eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // inicializo página
  $App = new App();

  $Uri = $App->rec_uri( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "sincronario" );
  
  if( file_exists($rec = "./app/{$Uri->esq}/index.php") ){ 
    
    // cargo contenido por aplicacion
    require_once( $rec );
    
    // inicializo contenido de página + aplicación
    $App->doc( $Usu, $Uri );
    
  }
  else{
    ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Error</title>
      </head>

      <body>

        <p>Página no encontrada...</p>
        
      </body>
    </html>
    <?php 
  }

  ?>