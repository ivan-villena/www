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
  $sis_cla = [
    'api'=>[ 
      'sis/sql', 'usu', 'doc', 'app', 'dat', 'val',
      'opc', 'num', 'tex', 'fig', 'fec', 'hol', 'obj', 'lis', 'est', 'tab', 'eje', 'ele', 'arc'
    ]
  ];
  foreach( $sis_cla as $mod_ide => $mod_lis ){

    foreach( $mod_lis as $cla_ide ){

      if( file_exists("./$mod_ide/$cla_ide.php") ) require_once("./$mod_ide/$cla_ide.php");
    }
  }

  // cargo interface
  $api_doc = new doc();
  $api_dat = new dat();
  $api_val = new val();
  $api_opc = new opc();
  $api_num = new num();
  $api_tex = new tex();
  $api_fig = new fig();
  $api_fec = new fec();
  $api_hol = new hol();
  $api_obj = new obj();
  $api_lis = new lis();
  $api_est = new est();
  $api_tab = new tab();
  $api_eje = new eje();
  $api_ele = new ele();
  $api_arc = new arc();
  $api_usu = new usu( $_SESSION['usu'] );  

  // peticion AJAX
  if( isset($_REQUEST['_']) ){  

    // log del sistema por ajax
    function _log() : string {
      
      $_ = "  
      <h2>hola desde php<c>!</c></h2>
      ";

      // recorrer tablas de un esquema
      /* 
      foreach( api_sql::est(DAT_ESQ,'lis','hol_','tab') as $est ){
        $_ .= "ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>";
      } 
      */

      $_ = hol::sql('psi_est_dia');

      return $_;
    }
    
    echo obj::val_cod( !obj::val_tip( $eje = eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // cargo documento por aplicacion
  $api_app = new app("hol");  

  // pido listado por navegacion
  if( !empty($api_app->doc['nav'][1]) ) 
    $api_app->ope['app_nav']['htm'] = $api_app->nav();

  // pido contenido por aplicacion
  $_uri = $api_app->uri;
  if( file_exists($rec = "./{$_uri->esq}/index.php") ) require_once( $rec );

  // usuario + loggin
  // $tip = empty($api_usu->ide) ? 'ini' : 'fin';
  // $api_app->ope["ses_{$tip}"]['htm'] = api::usu($tip);

  // consola del sistema
  if( $api_usu->ide == 1 ){
    ob_start();    
    include("./api/sis/adm.php");
    $api_app->rec['cla']['api'] []= "sis/adm";
    $api_app->ope['sis_adm'] = [ 
      'ico'=>"eje", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Consola del Sistema", 
      'art'=> [ 'style'=>"max-width: 55rem;" ],
      'htm'=> ob_get_clean()
    ]; 
  }

  // agrego ayuda
  if( !empty($api_app->htm['dat']) ) $api_app->ope['app_dat'] = [ 
    'ico'=>"dat_des", 'tip'=>"win", 'nom'=>"Ayuda", 'htm'=>$api_app->htm['dat'] 
  ];

  // cargo documento
  foreach( $api_app->ope as $ide => $ope ){

    if( !isset($ope['bot']) ) $ope['bot'] = "ini";

    // enlaces
    if( isset($ope['url']) ){
      // boton
      $api_app->htm['ope'][$ope['bot']] .= fig::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
    }
    // paneles y modales
    elseif( ( $ope['tip'] == 'pan' || $ope['tip'] == 'win' ) && !empty($ope['htm']) ){
      // botones          
      $api_app->htm['ope'][$ope['bot']] .= doc::bot([ $ide => $ope ]);
      // contenido
      $api_app->htm[$ope['tip']] .= doc::{$ope['tip']}($ide,$ope);
    }
  }

  // cargo modal de operadores
  $api_app->htm['win'] .= doc::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);

  // cargo contenido principal
  $ele = [ 'tit' => $api_app->rec['ele']['title'] ];
  $api_app->htm['sec'] = doc::sec( $api_app->htm['sec'], $ele );
  
  // ajusto diseño
  $_ver = [];
  foreach( ['bar','pie'] as $ide ){ if( !empty($api_app->htm[$ide]) ) $_ver []= $ide; }
  if( !empty($_ver) ) $api_app->rec['ele']['body']['data-ver'] = implode(',',$_ver);
  ?>
  <!DOCTYPE html>
  <html lang="es">
      
    <head>

      <meta charset="UTF-8">
      <meta name="viewport" content="width = device-width, initial-scale = 1, maximum-scale = 1">
      
      <?php // hojas de estilo
        foreach( $api_app->rec['cla'] as $mod_ide => $mod_lis ){          
          // por modulos
          foreach( $mod_lis as $cla_ide ){
            if( file_exists( $rec = "{$mod_ide}/{$cla_ide}.css" ) ) echo "
            <link rel='stylesheet' href='".SYS_NAV."$rec' >";
          }
          // por página
          if( file_exists( $rec = "{$mod_ide}/index.css" ) ) echo "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
        }
      ?>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Material+Icons+Outlined'>
      <link rel='stylesheet' href='<?=SYS_NAV?>/api/_.css'>

      <title><?= $api_app->rec['ele']['title'] ?></title>

    </head>

    <body <?= ele::atr($api_app->rec['ele']['body']) ?>>
      
      <!-- Botonera -->
      <header class='doc_bot'>
        
        <nav class="ope">
          <?= $api_app->htm['ope']['ini']; ?>
        </nav>

        <nav class="ope">
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
      <main class="doc_sec">
        <?= $api_app->htm['sec'] ?>
      </main>
      
      <?php if( !empty($api_app->htm['bar']) ){ ?>
        <!-- sidebar -->
        <aside class="doc_bar">
          <?= $api_app->htm['bar'] ?>
        </aside>
      <?php } ?>

      <?php if( !empty($api_app->htm['pie']) ){  ?>
        <!-- pie de página -->
        <footer class="doc_pie">
          <?= $api_app->htm['pie'] ?>
        </footer>
      <?php } ?>
      
      <!-- Modales -->
      <section class='doc_win dis-ocu'>
        <?= $api_app->htm['win'] ?>
      </section>
      
      <!-- Programas -->
      <script>
        
        // sistema
        const SYS_NAV = "<?=SYS_NAV?>";
        
        // operativas
        const DIS_OCU = "<?=DIS_OCU?>";
        const FON_SEL = "<?=FON_SEL?>";
        const BOR_SEL = "<?=BOR_SEL?>";
        
      </script>
      <?php 
        foreach( $api_app->rec['cla'] as $mod_ide => $mod_lis ){        
          // por modulos
          foreach( $mod_lis as $cla_ide ){ 
            if( file_exists( $rec = "{$mod_ide}/{$cla_ide}.js" ) ) echo "
              <script src='".SYS_NAV."$rec'></script>";
          }
          // por página
          if( file_exists( $rec = "{$mod_ide}/index.js" ) ) echo "
          <script src='".SYS_NAV."$rec'></script>";
        }
      ?>
      <script>
        // cargo módulos
        var $api_dat = new dat({ _ope: <?= obj::val_cod( get_object_vars($api_dat)['_ope'] ) ?> });
        var $api_val = new val(<?= obj::val_cod( get_object_vars($api_val) ) ?>);
        var $api_opc = new opc(<?= obj::val_cod( get_object_vars($api_opc) ) ?>);
        var $api_num = new num(<?= obj::val_cod( get_object_vars($api_num) ) ?>);
        var $api_tex = new tex(<?= obj::val_cod( get_object_vars($api_tex) ) ?>);
        var $api_fig = new fig(<?= obj::val_cod( get_object_vars($api_fig) ) ?>);
        var $api_fec = new fec(<?= obj::val_cod( get_object_vars($api_fec) ) ?>);
        var $api_hol = new hol(<?= obj::val_cod( get_object_vars($api_hol) ) ?>);        
        var $api_obj = new obj(<?= obj::val_cod( get_object_vars($api_obj) ) ?>);
        var $api_lis = new lis(<?= obj::val_cod( get_object_vars($api_lis) ) ?>);
        var $api_est = new est(<?= obj::val_cod( get_object_vars($api_est) ) ?>);
        var $api_tab = new tab(<?= obj::val_cod( get_object_vars($api_tab) ) ?>);
        var $api_eje = new eje(<?= obj::val_cod( get_object_vars($api_eje) ) ?>);
        var $api_ele = new ele(<?= obj::val_cod( get_object_vars($api_ele) ) ?>);
        var $api_arc = new arc(<?= obj::val_cod( get_object_vars($api_arc) ) ?>);

        // cargo documento/aplicacion
        var $api_doc = new doc(<?= obj::val_cod( get_object_vars($api_doc) ) ?>);
        var $api_app = new app({ uri : <?= obj::val_cod( get_object_vars($api_app->uri) ) ?> });
        
        // cargo datos de la interface
        var $api_log = { 'php':[], 'jso':[] };
        
        // ejecuto codigo por aplicacion
        <?= $api_app->rec['eje'] ?>

        // inicializo página
        $api_app.ini();

        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

      </script>
    </body>

  </html>      