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
  // Componentes
    require_once("./api/dat.php");
    require_once("./api/arc.php");
    require_once("./api/eje.php");
    require_once("./api/ele.php");
    require_once("./api/obj.php");
    require_once("./api/lis.php");
    require_once("./api/opc.php");
    require_once("./api/num.php");
    require_once("./api/tex.php");
    require_once("./api/fig.php");
    require_once("./api/fec.php");
    require_once("./api/hol.php");
  //  
  // Sistema
    require_once("./sis/sql.php");
    require_once("./sis/doc.php");
    require_once("./sis/app.php");
    require_once("./sis/usu.php");  
    
    $sis_dat = new sis_doc();
    $sis_app = new sis_app();
    $sis_usu = new sis_usu( $_SESSION['usu'] );    
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
        include("./_/hol/sel.php");
        $_ = hol_sel_par_gui();
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
  $_uri = $sis_app->rec_uri( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "hol" );
  
  // cargo contenido por aplicacion
  if( file_exists($rec = "./app/{$_uri->esq}/index.php") ){ 
    require_once( $rec ); 
  }
  
  // inicializo contenido de página + aplicación
  $_htm = $sis_app->rec_htm();

  ?>
  <!DOCTYPE html>
  <html lang="es">
       
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
      <title><?=$_htm['tit']?></title>

      <!-- hojas de estilo -->
      <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
      <?=$sis_app->rec_cla('css')?>
      <link rel='stylesheet' href='<?=SYS_NAV?>css.css'>      
    </head>

    <body <?=api_ele::atr($sis_app->rec['ele']['body'])?>>
      
      <?php // Operador
      if( !empty($_htm['ope']['ini']) || !empty($_htm['ope']['fin']) ){
        ?>
        <!-- Operador -->
        <header class='doc_bot'>
          
          <nav class='ini'>
            <?= $_htm['ope']['ini'] ?>
            <?= $_htm['ope']['med'] ?>
          </nav>

          <nav class='fin'>
            <?= $_htm['ope']['fin'] ?>
          </nav>
          
        </header>        
        <?php
      } ?>

      <?php // Paneles Ocultos
      if( !empty($_htm['pan']) ){ ?>
        <!-- Panel -->
        <aside class='doc_pan dis-ocu'>
          <?= $_htm['pan'] ?>
        </aside>
        <?php 
      } ?>
      
      <?php // Contenido Principal
      if( !empty($_htm['sec']) ){
        ?>
        <!-- Contenido -->
        <main class='doc_sec'>
          <?= sis_doc::sec( $_htm['sec'], [ 'tit'=>$_htm['tit'] ] ) ?>
        </main>          
        <?php
      } ?>
            
      <?php // Lateral: siempre visible
      if( !empty($_htm['bar']) ){ ?>
        <!-- Sidebar -->
        <aside class='doc_bar'>
          <?= $_htm['bar'] ?>
        </aside>
        <?php 
      } ?>

      <?php // pié de página
      if( !empty($_htm['pie']) ){  ?>
        <!-- pie de página -->
        <footer class='doc_pie'>
          <?= $_htm['pie'] ?>
        </footer>
        <?php 
      } ?>
      
      <!-- Modales -->
      <div class='doc_win dis-ocu'>
        <?= $_htm['win'] ?>
      </div>
      
      <!-- Módulos -->
      <?=$sis_app->rec_cla('jso')?>
      
      <!-- Aplicación -->
      <script>
        // Rutas
        const SYS_NAV = "<?=SYS_NAV?>";
        
        // Clases
        const DIS_OCU = "<?=DIS_OCU?>";
        const FON_SEL = "<?=FON_SEL?>";
        const BOR_SEL = "<?=BOR_SEL?>";

        <?php
        $dat_est = [];
        foreach( $sis_app->dat as $esq => $esq_lis ){
          $dat_est_val = [];
          foreach( $esq_lis as $est => $est_ope ){
            if( isset($sis_app->rec['est']['api'][$esq]) ){
              if( in_array($est,$sis_app->rec['est']['api'][$esq]) ) $dat_est_val[$est] = $est_ope;
            }elseif( isset($sis_app->rec['dat']['api'][$esq]) ){
              if( in_array($est,$sis_app->rec['dat']['api'][$esq]) ) $dat_est_val[$est] = [ 'dat' => $est_ope->dat ];
            }
          }
          if( !empty($dat_est_val) ) $dat_est[$esq] = $dat_est_val;
        }
        ?>
        // Cargo Sistema
        const $sis_dom = new sis_dom();

        const $sis_app = new sis_app(<?= api_obj::val_cod([ 'rec'=>[ 'uri'=>$_uri ], 'dat'=>$dat_est ]) ?>);
        
        // Codigo por aplicacion
        <?= $sis_app->rec['eje'] ?>
        
        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

      </script>

    </body>

  </html>