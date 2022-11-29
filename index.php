<?php  
  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  // cargo sesion
  session_start();

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

    
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";  
    if( !isset($_SESSION['usu']) ) $_SESSION['usu'] = 1;
    date_default_timezone_set( $_SESSION['ubi'] );
    $sis_ini = time();  
  
  //
  // cargo modulos
  $sis_cla = [
    'sis/sql',
    'val', 'opc', 'num', 'tex', 'fig', 'fec', 'hol', 'obj', 'lis', 'est', 'tab', 'eje', 'ele', 'arc',
    'doc', 'dat', 'app', 'usu'
    ];    

    foreach( $sis_cla as $mod_ide => $mod_lis ){

      if( file_exists($rec = "./api/{$cla_ide}.php") ) require_once($rec);
    }
    // cargo variables
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
    $api_dat = new dat();
    $api_usu = new usu( $_SESSION['usu'] );
  //
  // peticion AJAX
  if( isset($_REQUEST['_']) ){  

    // log del sistema por ajax
    function sis_log() : string {
      
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

  // cargo documento y aplicacion  
  $api_doc = new doc();
  $api_app = new app("hol");

  // pido listado por navegacion
  if( !empty($api_app->rec['dat']['nav'][1]) ) 
    $api_app->ope['doc_art']['htm'] = doc::art($api_app->rec['dat']['nav']);
  
  // pido contenido por aplicacion
  $_uri = $api_app->uri;
  if( file_exists($rec = "./src/{$_uri->esq}/index.php") ) require_once( $rec );
  
  // usuario + loggin
  $tip = empty($api_usu->ide) ? 'ini' : 'fin';
  // $api_app->ope["ses_{$tip}"]['htm'] = api::usu($tip);

  // consola del sistema
  if( $api_usu->ide == 1 ){

    $api_app->rec['cla']['api'] []= "sis/adm";

    ob_start();
    include("./src/api/sis/adm.php");
    $api_app->ope['sis_adm']['htm'] = ob_get_clean();
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
  
  // ajusto diseño
  $_ver = [];
  foreach( ['bar','pie'] as $ide ){ if( !empty($api_app->htm[$ide]) ) $_ver []= $ide; }
  if( !empty($_ver) ) $api_app->rec['ele']['body']['data-ver'] = implode(',',$_ver);

  // titulo
  $doc_tit = "{-_-}";
  if( !empty($api_app->rec['dat']['art']->nom) ){
    $doc_tit = $api_app->rec['dat']['art']->nom;
  }
  elseif( !empty($api_app->rec['dat']['cab']->nom) ){
    $doc_tit = $api_app->rec['dat']['cab']->nom;
  }
  elseif( !empty($api_app->rec['dat']['esq']->nom) ){
    $doc_tit = $api_app->rec['dat']['esq']->nom; 
  }  
  ?>
  <!DOCTYPE html>
  <html lang="es">
      
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width = device-width, initial-scale = 1, maximum-scale = 1">
      
      <!-- hojas de estilo -->
      <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
      <?=$api_app->rec_cla('css')?>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Material+Icons+Outlined'>
      <link rel='stylesheet' href='<?=SYS_NAV?>api/sis/ope.css'>

      <title><?=$doc_tit?></title>
    </head>

    <body <?=ele::atr($api_app->rec['ele']['body'])?>>
      
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
        <?= doc::sec( $api_app->htm['sec'], [ 'tit'=>$doc_tit ] ) ?>
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
        // inicio log
        const $sis_log = { 'php':[], 'jso':[] };
      </script>
      <?=$api_app->rec_cla('jso')?>
      <script>
        // cargo objetos
        <?=$api_app->rec_cla('jso-obj',$api_app->rec['obj']['api'],'api')?>
        var $api_dat = new dat({ _est_ope: <?= obj::val_cod( get_object_vars($api_dat)['_est_ope'] ) ?> });
        var $api_app = new app({ uri : <?= obj::val_cod( $api_app->uri ) ?> });      
        // ejecuto codigo por aplicacion
        <?= $api_app->rec['eje'] ?>
        // inicializo página
        $api_app.ini();
        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
      </script>
    </body>

  </html>      