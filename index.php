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
    require_once("./sis/sql.php");
    require_once("./api/app.php");
    require_once("./api/dat.php");
    require_once("./api/doc.php");    
    // Componentes
    require_once("./api/arc.php");
    require_once("./api/eje.php");
    require_once("./api/ele.php");
    require_once("./api/est.php");
    require_once("./api/obj.php");
    require_once("./api/lis.php");
    require_once("./api/opc.php");
    require_once("./api/num.php");
    require_once("./api/tex.php");
    require_once("./api/fig.php");
    require_once("./api/fec.php");
    require_once("./api/hol.php");    
    require_once("./api/usu.php");
    
    // cargo sistema
    $api_app = new api_app();
    $sis_usu = api_usu::dat( $_SESSION['usu'] );

  //

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
        include("./api/hol/cas.php");
        $_ = hol_cas();
      */
      
      return $_;
    }

    // ver cabeceras para api's: tema no-cors
    echo api_obj::val_cod( !api_obj::val_tip( $eje = api_eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // inicializo página
  $api_app->uri_ini( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "hol" );
  
  // cargo contenido por aplicacion
  $_uri = $api_app->uri;
  if( file_exists($rec = "./app/{$_uri->esq}/index.php") ){ 
    require_once( $rec );
  }
  
  // inicializo contenido de página + aplicación
  $api_app->htm_ini();
  ?>
  <!DOCTYPE html>
  <html lang="es">
       
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
      <!-- hojas de estilo -->
      <?=$api_app->rec_cla('css')?>
      <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
      <link rel='stylesheet' href='<?=SYS_NAV?>sis/css.css'>

      <title><?=$api_app->htm['tit']?></title>
    </head>

    <body <?=api_ele::atr($api_app->rec['ele']['body'])?>>
      
      <!-- Botonera -->
      <header class='doc_bot'>
        
        <nav class='doc_ope'>
          <?= $api_app->htm['ope']['ini']; ?>
        </nav>

        <nav class='doc_ope'>
          <?= $api_app->htm['ope']['fin']; ?>
        </nav>
        
      </header>

      <?php if( !empty($api_app->htm['pan']) ){ ?>
        <!-- Panel -->
        <aside class='doc_pan dis-ocu'>
          <?= $api_app->htm['pan'] ?>
        </aside>
      <?php } ?>
      
      <!-- Contenido -->
      <main class='doc_sec'>
        <?= api_doc::sec( $api_app->htm['sec'], [ 'tit'=>$api_app->htm['tit'] ] ) ?>
      </main>
      
      
      <?php if( !empty($api_app->htm['bar']) ){ ?>
        <!-- sidebar -->
        <aside class='doc_bar'>
          <?= $api_app->htm['bar'] ?>
        </aside>
      <?php } ?>

      <?php if( !empty($api_app->htm['pie']) ){  ?>
        <!-- pie de página -->
        <footer class='doc_pie'>
          <?= $api_app->htm['pie'] ?>
        </footer>
      <?php } ?>
      
      <!-- Modales -->
      <div class='doc_win dis-ocu'>
        <?= $api_app->htm['win'] ?>
      </div>
      
      <!-- Parámetros -->
      <script>
        // Rutas
        const SYS_NAV = "<?=SYS_NAV?>";        
        
        // Clases
        const DIS_OCU = "<?=DIS_OCU?>";
        const FON_SEL = "<?=FON_SEL?>";
        const BOR_SEL = "<?=BOR_SEL?>";
        
        // Peticiones
        const $sis_log = { 
          php: [], 
          jso: [], 
          uri: <?=api_obj::val_cod( $api_app->uri )?>
        };
        
      </script>
      <!-- Documento -->
      <script src="<?=SYS_NAV?>sis/dom.js"></script>
      <!-- Módulos -->
      <?=$api_app->rec_cla('jso')?>
      <!-- Aplicación -->
      <script>
        <?php
        $dat_est = [];
        foreach( $api_app->_est as $esq => $esq_lis ){
          $dat_est_val = [];
          foreach( $esq_lis as $est => $est_ope ){
            if( isset($api_app->rec['est']['api'][$esq]) ){
              if( in_array($est,$api_app->rec['est']['api'][$esq]) ) $dat_est_val[$est] = $est_ope;
            }elseif( isset($api_app->rec['dat']['api'][$esq]) ){
              if( in_array($est,$api_app->rec['dat']['api'][$esq]) ) $dat_est_val[$est] = [ 'dat' => $est_ope->dat ];
            }
          }
          if( !empty($dat_est_val) ) $dat_est[$esq] = $dat_est_val;
        }?>

        var $api_app = new api_app(<?= api_obj::val_cod(['_tip'=>$api_app->_tip,'_est'=>$dat_est]) ?>);
        
        // codigo por aplicacion
        <?= $api_app->rec['eje'] ?>
        
      </script>
      <!-- Inicializo página -->
      <script src="<?=SYS_NAV?>index.js"></script>
      <script>
        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
      </script>
    </body>

  </html>