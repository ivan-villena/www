<?php  
  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  // cargo sesion
  session_start();

    // directorios :: localhost / icpv.com.ar  
    define('SYS_NAV', "http://{$_SERVER['HTTP_HOST']}/" );

    // Base de datos: local / produccion
    define('DAT_SER', 'localhost');
    define('DAT_USU', "c1461857_api");
    define('DAT_PAS', "lu51zakoWA");
    define('DAT_ESQ', "c1461857_api");

    // OPERACIONES : clases
    define('DIS_OCU', "dis-ocu");
    define('BOR_SEL', "bor-sel");
    define('FON_SEL', "fon-sel");

    
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";  
    if( !isset($_SESSION['usu']) ) $_SESSION['usu'] = 1;
    date_default_timezone_set( $_SESSION['ubi'] );
    $sis_ini = time();    
  //
  // cargo modulos
  $sis_cla = [
    'sis/sql',
    'app', 'doc', 'dat', 'usu', 
    'arc', 'eje', 'obj', 'ele',
    'opc', 'num', 'tex', 'fig', 'fec', 'hol', 
    'lis', 'est', 'tab'
    ];
    
    foreach( $sis_cla as $cla_ide ){

      if( file_exists($rec = "./api/{$cla_ide}.php") ) require_once($rec);
    }
    // cargo variables
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
    $api_doc = new doc();
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
      
      foreach( [ 'ton', 'sel', 'kin'] as $ide ){
        foreach( hol::_($ide) as $dat ){
          $img = str_replace("localhost","www.icpv.com.ar",dat::est_ope('hol',$ide,'val.ima',$dat));
          $img = str_replace("background: ","",$img);
          $_ .= "UPDATE `cac_react`.`$ide` SET `img`='$img' WHERE ide='$dat->ide';<br>";
        }
      }

      return $_;
    }
    
    echo obj::val_cod( !obj::val_tip( $eje = eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }
  
  ////////////////////////////////
  // cargo documento y aplicacion
  $api_app = new app( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "hol" );

  // pido contenido por aplicacion
  $_uri = $api_app->uri;
  if( file_exists($rec = "./src/{$_uri->esq}/index.php") ) require_once( $rec );
    
  // inicializo contenido
  $api_app->htm_ini();
  ?>
  <!DOCTYPE html>
  <html lang="es">
      
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width = device-width, initial-scale = 1, maximum-scale = 1">
      
      <!-- hojas de estilo -->
      <?=$api_app->rec_cla('css')?>
      <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Material+Icons+Outlined'>
      <link rel='stylesheet' href='<?=SYS_NAV?>api/sis/css.css'>

      <title><?=$api_app->htm['tit']?></title>
    </head>

    <body <?=ele::atr($api_app->rec['ele']['body'])?>>
      
      <!-- Botonera -->
      <header class='doc_bot'>
        
        <nav class="doc_ope">
          <?= $api_app->htm['ope']['ini']; ?>
        </nav>

        <nav class="doc_ope">
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
        <?= doc::sec( $api_app->htm['sec'], [ 'tit'=>$api_app->htm['tit'] ] ) ?>
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
        <?php 
        $var = get_defined_vars();
        foreach( $api_app->rec['obj']['api'] as $cla ){
          if( isset($var[$obj = "api_{$cla}"]) && is_object($var[$obj]) ){ echo "
            var \${$obj} = new $cla(".( !empty($atr = get_object_vars($var[$obj])) ? obj::val_cod($atr) : "" ).");\n";
          }          
        }
        $dat_api = [];
        foreach( ['_ico','_ope','_tip','_est_ope'] as $atr ){
          $dat_api[$atr] = $api_dat->$atr;
        }
        ?>
        var $api_dat = new dat(<?= obj::val_cod($dat_api) ?>);
        var $api_app = new app({ uri : <?= obj::val_cod( $api_app->uri ) ?> });      
        // ejecuto codigo por aplicacion
        <?= $api_app->rec['eje'] ?>
        // inicializo página
        $api_app.ini();
        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
      </script>
    </body>

  </html>      