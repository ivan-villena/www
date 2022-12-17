<?php  
  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  // Sesion
  session_start();
    // Sesion
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";  
    if( !isset($_SESSION['usu']) ) $_SESSION['usu'] = 1;
    date_default_timezone_set( $_SESSION['ubi'] );
    // Inicio
    $sis_ini = time();

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

  // Modulos
  $sis_cla = [ 
      'api'=>[ 
        'doc', 'dat', 'arc', 'eje', 'ele', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol'
      ],
      'sis'=>[
        'ses','sql', 'usu', 'app'
      ]
    ];

    $sis_obj = [
      'api'=>[ 'doc', 'arc', 'eje', 'ele', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol' ]
    ];
    
    foreach( $sis_cla as $cla_rec => $cla_lis ){

      foreach( $cla_lis as $cla_ide ){

        if( file_exists($rec = "./{$cla_rec}/{$cla_ide}.php") ) require_once($rec);
      }      
    }
    // Cargo Datos en Objetos
    $api_dat = new api_dat();
    $api_doc = new api_doc();
    $api_arc = new api_arc();
    $api_eje = new api_eje();
    $api_ele = new api_ele();
    $api_obj = new api_obj();
    $api_lis = new api_lis();
    $api_opc = new api_opc();
    $api_num = new api_num();
    $api_tex = new api_tex();
    $api_fig = new api_fig();
    $api_fec = new api_fec();
    $api_hol = new api_hol();
        
    // cargo sistema    
    $sis_usu = new sis_usu( $_SESSION['usu'] );
    $sis_ses = new sis_ses( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "hol" );
  //
  // peticion AJAX
  if( isset($_REQUEST['_']) ){

    // ver cabeceras para api's: tema no-cors
    echo api_obj::val_cod( !api_obj::val_tip( $eje = api_eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // cargo documento y aplicacion
  $sis_app = new sis_app( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : "hol" );

  // pido contenido por aplicacion
  $_uri = $sis_app->uri;
  if( file_exists($rec = "./app/{$_uri->esq}/index.php") ) require_once( $rec );
  
  // inicializo contenido
  $sis_app->htm_ini();
  ?>
  <!DOCTYPE html>
  <html lang="es">
       
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      
      <!-- hojas de estilo -->
      <?=$sis_app->rec_cla('css')?>
      <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
      <link rel='stylesheet' href='<?=SYS_NAV?>sis/css.css'>

      <title><?=$sis_app->htm['tit']?></title>
    </head>

    <body <?=api_ele::atr($sis_app->rec['ele']['body'])?>>
      
      <!-- Botonera -->
      <header class='doc_bot'>
        
        <nav class='doc_ope'>
          <?= $sis_app->htm['ope']['ini']; ?>
        </nav>

        <nav class='doc_ope'>
          <?= $sis_app->htm['ope']['fin']; ?>
        </nav>
        
      </header>

      <?php if( !empty($sis_app->htm['pan']) ){ ?>
        <!-- Panel -->
        <aside class='doc_pan dis-ocu'>
          <?= $sis_app->htm['pan'] ?>
        </aside>
      <?php } ?>
      
      <!-- Contenido -->
      <main class='doc_sec'>
        <?= api_doc::sec( $sis_app->htm['sec'], [ 'tit'=>$sis_app->htm['tit'] ] ) ?>
      </main>
      
      
      <?php if( !empty($sis_app->htm['bar']) ){ ?>
        <!-- sidebar -->
        <aside class='doc_bar'>
          <?= $sis_app->htm['bar'] ?>
        </aside>
      <?php } ?>

      <?php if( !empty($sis_app->htm['pie']) ){  ?>
        <!-- pie de página -->
        <footer class='doc_pie'>
          <?= $sis_app->htm['pie'] ?>
        </footer>
      <?php } ?>
      
      <!-- Modales -->
      <div class='doc_win dis-ocu'>
        <?= $sis_app->htm['win'] ?>
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
        const $sis_log = { php:[], jso:[], uri :<?= api_obj::val_cod( $sis_app->uri ) ?>  };

      </script>
      <!-- Módulos -->
      <?=$sis_app->rec_cla('jso')?>
      <!-- Cargo Datos -->
      <script>        
        <?php // cargo objetos
        $var = get_defined_vars();
        foreach( $sis_app->rec['obj']['api'] as $cla ){
          if( isset($var[$obj = "api_{$cla}"]) && is_object($var[$obj]) ){ echo "
            var \${$obj} = new {$obj}(".( !empty($atr = get_object_vars($var[$obj])) ? api_obj::val_cod($atr) : "" ).");\n";
          }
        }
        
        $dat_api = [];
        foreach( ['_ope','_tip','_est_ope'] as $atr ){ $dat_api[$atr] = $api_dat->$atr; }
        ?>        
        // cargo datos globales
        var $api_dat = new api_dat(<?= api_obj::val_cod($dat_api) ?>);        
        
        // codigo por aplicacion
        <?= $sis_app->rec['eje'] ?>
        
      </script>
      <!-- Inicializo página -->
      <script src="<?=SYS_NAV?>index.js"></script>
      <script>
        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
      </script>
    </body>

  </html>