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

    require_once("./sis/sql.php");
    require_once("./sis/Dat.php");
    require_once("./sis/Doc.php");
    require_once("./sis/App.php");
    require_once("./sis/Usu.php");    

    $App = new App();
    $Usu = new Usu( $_SESSION['usu'] );
  //

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // peticion AJAX
  if( isset($_REQUEST['_']) ){

    // ver cabeceras para api's: tema no-cors
    echo Obj::val_cod( !Obj::val_tip( $eje = Eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // cargo rutas
  $Uri = $App->uri( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "sincronario" );
  
  if( file_exists($rec = "./_app/{$Uri->esq}/index.php") ){ 

    // inicializo página
    $App->doc_ini();
    
    // cargo secciones por aplicacion
    require_once( $rec );
    
    // cargo contenido de página + aplicación
    $App->doc( $Usu );
  }
  else{
    ?>
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <!-- parametros -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- hojas de estilo -->
        <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
        <?=$App->ses_cla('css')?>
        <link rel='stylesheet' href='<?=SYS_NAV?>sis/css.css'>
        <!--  -->
        <title>Error</title>
      </head>

      <body>

        <?=Doc::tex([ 'tip'=>"err", 'tex'=>"No existe la Página solicitada..." ],[ 'sec'=>[ 'class'=>"mar-aut" ] ])?>
        
      </body>
    </html>
    <?php 
  }

  ?>