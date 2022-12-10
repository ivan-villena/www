<?php  
  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  
  // Inicio Sesion
  session_start();

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

    // Sesion
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";  
    if( !isset($_SESSION['usu']) ) $_SESSION['usu'] = 0;
    date_default_timezone_set( $_SESSION['ubi'] );

    // Inicio
    $sis_ini = time();

  //
  // cargo modulos
  $sis_cla = [ 
    'sis/sql', 
    'dat', 'hol', 'usu', 'app', 'doc',
    'ele', 'arc', 'eje', 'obj', 'lis', 
    'opc', 'num', 'tex', 'fig', 'fec'
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
    $api_ele = new ele();
    $api_arc = new arc();
    $api_eje = new eje();
    $api_obj = new obj();
    $api_lis = new lis();
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
          $img = dat::est_ope('hol',$ide,'val.ima',$dat);
          $_ .= "UPDATE `cac_react`.`$ide` SET 
            `img`='$img' 
          WHERE `ide` = $dat->ide;<br>";
        }
      }

      return $_;
    }
    
    echo obj::val_cod( !obj::val_tip( $eje = eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

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
      <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
      <?=$api_app->rec_cla('css')?>
      <link rel='stylesheet' href='<?=SYS_NAV?>api/sis/css.css'>

      <title><?=$api_app->htm['tit']?></title>
    </head>

    <body <?=ele::atr($api_app->rec['ele']['body'])?>>
      
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
        <?= doc::sec( $api_app->htm['sec'], [ 'tit'=>$api_app->htm['tit'] ] ) ?>
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
      
      <!-- Programas -->
      <script>
        // Rutas
        const SYS_NAV = "<?=SYS_NAV?>";        
        // Clases
        const DIS_OCU = "<?=DIS_OCU?>";
        const FON_SEL = "<?=FON_SEL?>";
        const BOR_SEL = "<?=BOR_SEL?>";
        // Peticiones
        const $sis_log = { php:[], jso:[], uri :<?= obj::val_cod( $api_app->uri ) ?>  };

      </script>
      <!-- Módulos -->
      <?=$api_app->rec_cla('jso')?>
      <!-- Cargo Datos -->
      <script>        
        <?php // cargo objetos
        $var = get_defined_vars();
        foreach( $api_app->rec['obj']['api'] as $cla ){
          if( isset($var[$obj = "api_{$cla}"]) && is_object($var[$obj]) ){ echo "
            var \${$obj} = new $cla(".( !empty($atr = get_object_vars($var[$obj])) ? obj::val_cod($atr) : "" ).");\n";
          }
        }
        $dat_api = [];
        foreach( ['_ope','_tip','_est_ope'] as $atr ){ $dat_api[$atr] = $api_dat->$atr; }
        ?>
        var $api_dat = new dat(<?= obj::val_cod($dat_api) ?>);        
        // codigo por aplicacion
        <?= $api_app->rec['eje'] ?>
        
      </script>
      <!-- Inicializo página -->
      <script src="./index.js"></script>
      <script>
        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
      </script>
    </body>

  </html>      