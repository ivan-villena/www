<?php  
  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  $sis_ini = time();

  // Sesion
    session_start();

    if( !isset($_SESSION['usu']) ){ 
      $_SESSION['usu'] = 1;
      $_SESSION['ini'] = time();
      $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";
    }

    date_default_timezone_set( $_SESSION['ubi'] );

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

    // Interfaces
    require_once("./Api/sql.php");  

    // Sistema
    require_once("./Sis/Num.php");
    require_once("./Sis/Tex.php");
    require_once("./Sis/Fec.php");
    require_once("./Sis/Obj.php");
    require_once("./Sis/Eje.php");
    require_once("./Sis/Ele.php");
    require_once("./Sis/Arc.php");

    require_once("./Sis/Dat.php");
    require_once("./Sis/Doc.php");
    require_once("./Sis/Usu.php");
    require_once("./Sis/App.php");

    // Documento
    require_once("./Doc/Val.php");
    require_once("./Doc/Var.php");
    require_once("./Doc/Ope.php");
    require_once("./Doc/Dat.php");    
  //

  $Usu = new Usu( $_SESSION['usu'] );  

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // peticion AJAX
  if( isset($_REQUEST['_']) ){

    // Ejecucion desde consola
    function adm_log() : string {
      
      $_ = "<h2>hola desde php<c>!</c></h2>";

      foreach( sql::est('nom','tip_','tab') as $est ){

        $_ .= "RENAME TABLE `$est` TO `".str_replace('tip_','sis-tip_',$est)."`;<br>";

      } 

    
      /* Recorrer tablas de un esquema:

      foreach( sql::est('nom','hol-uni','tab') as $est ){

        $_ .= "ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>";

      } 
      */
      
      /*  Invocando funciones 
        include("./_sql/hol/sel.php");
        $_ = hol-sel_par_gui();
      */
      
      return $_;
    }

    // ver cabeceras para api's: tema no-cors
    echo Obj::val_cod( !Obj::tip( $eje = Eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////  

  $App = new App();

  $Doc = new Doc();

  // cargo rutas
  $_SESSION['Uri'] = $Uri = $App->uri( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "sincronario" );  
  
  if( file_exists($rec = "./App/{$Uri->esq}/index.php") ){

    // cargo modulo principal de la aplicacion
    if( file_exists($rec_cla = "./App/".Tex::let_pal($Uri->esq).".php") ){
      
      require_once( $rec_cla );
    }

    // cargo datos de la aplicacion
    $App->uri_dat();
    
    // cargo secciones por aplicacion
    require_once( $rec );
    
    // cargo contenido de página + aplicación
    $Doc->htm( $App, $Usu );

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
        <?=$Doc->cla('css')?>
        <link rel='stylesheet' href='<?=SYS_NAV?>sis/css.css'>
        <!--  -->
        <title>Error</title>
      </head>

      <body>

        <?=Doc_Ope::tex([ 
          'tip'=>"err", 
          'tex'=>"No existe la Página solicitada: {$_REQUEST['uri']}..."
        ])?>
        
      </body>
    </html>
    <?php 
  }

  ?>