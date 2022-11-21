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
  $sis_rec = [ 
    'api' => [ "sql", "lis", "dat", "eje", "obj", "ele", "arc", "tex", "num", "fec", "hol", "usu" ],
    'app' => [ "dir", "var", "lis", "dat", "val", "est", "tab" ]
  ];
  foreach( $sis_rec as $ide => $cla_lis ){
    
    foreach( $cla_lis as $cla ){
      if( file_exists("./php/$ide/$cla.php") ) require_once("./php/$ide/$cla.php");
    }
    if( file_exists("./php/$ide.php") ) require_once("./php/$ide.php");
  }

  // cargo interface
  $_api = new api();
  
  $_usu = new api_usu( $_SESSION['usu'] );
   
  // peticion AJAX
  if( isset($_REQUEST['_']) ){  

    // log del sistema por ajax
    function _log() : string {

      global $_api, $_usu;
      $_ = "  
      <h2>hola desde php<c>!</c></h2>
      ";

      // recorrer tablas
      /* 
      foreach( api_sql::est(DAT_ESQ,'lis','hol_','tab') as $est ){
        $_ .= "";
        // ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>
      } 
      */

      return $_;
    }
    
    echo api_obj::cod( !api_obj::tip( $eje = api_eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );  

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // cargo peticion
  $_uri = app_dir::uri("hol");

  // cargo aplicacion
  $_app = new app( $_uri );

  // pido contenido por aplicacion
  if( file_exists($cla_rec = "php/{$_uri->esq}.php") ){

    require_once($cla_rec);

    if( class_exists( $cla = $_uri->esq ) ){

      new $cla( $_app );
    }                
  }

  // usuario + loggin
  // $tip = empty($_usu->ide) ? 'ini' : 'fin';
  // $_app->ope["ses_{$tip}"]['htm'] = api::usu($tip);

  // consola del sistema
  if( $_usu->ide == 1 ){
    $_app->rec['jso']['api'] []= "adm";
    $_app->ope['api_adm'] = [ 
      'ico'=>"eje", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Consola del Sistema", 
      'art'=> [ 'style'=>"max-width: 55rem;" ],
      'htm'=> api::adm()
    ]; 
  }

  // agrego ayuda
  if( !empty($_app->htm['dat']) ) $_app->ope['app_dat'] = [ 
    'ico'=>"dat_des", 'tip'=>"win", 'nom'=>"Ayuda", 'htm'=>$_app->htm['dat'] 
  ];

  // cargo documento
  foreach( $_app->ope as $ide => $ope ){

    if( !isset($ope['bot']) ) $ope['bot'] = "ini";

    // enlaces
    if( isset($ope['url']) ){
      // boton
      $_app->htm['ope'][$ope['bot']] .= app::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
    }
    // paneles y modales
    elseif( ( $ope['tip'] == 'pan' || $ope['tip'] == 'win' ) && !empty($ope['htm']) ){
      // botones          
      $_app->htm['ope'][$ope['bot']] .= app::bot([ $ide => $ope ]);
      // contenido
      $_app->htm[$ope['tip']] .= app::{$ope['tip']}($ide,$ope);
    }
  }

  // cargo modal de operadores
  $_app->htm['win'] .= app::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);

  // cargo contenido principal
  $ele = [ 'tit' => $_app->rec['ele']['title'] ];
  $_app->htm['sec'] = app::sec( $_app->htm['sec'], $ele );
  
  // ajusto diseño
  $_ver = [];
  foreach( ['bar','pie'] as $ide ){
    if( !empty($_app->htm[$ide]) ) $_ver []= $ide; 
  }
  if( !empty($_ver) ) $_app->rec['ele']['body']['data-ver'] = implode(',',$_ver);

  // cargo datos por esquemas
  $_dat = [];
  foreach( $_app->rec['dat'] as $esq => $est ){
    // cargo todas las estructuras de la base que empiecen por "api.$esq_"
    if( empty($est) ){
      foreach( $_api as $i => $v ){
        if( preg_match("/^{$esq}_/",$i) ) $_dat[$i] = $v;
      }
    }// cargo estructuras por identificador
    else{
      foreach( $est as $ide ){
        $_dat["{$esq}_{$ide}"] = $_api->{"{$esq}_{$ide}"};
      }          
    }
  }
  $_app->rec['dat'] = $_dat;

  ?>
  <!DOCTYPE html>
  <html lang="es">
      
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width = device-width, initial-scale = 1, maximum-scale = 1">
      <?php // hojas de estilo
      foreach( [ $_app->rec['css'], $_app->rec['css-fin'] ] as $css ){ 
        foreach( $css as $ide ){
          if( preg_match("/^http/", $ide) ){ echo "
            <link rel='stylesheet' href='$ide' >";
          }elseif( file_exists( $rec = "css/{$ide}.css" ) ){ echo "
            <link rel='stylesheet' href='".SYS_NAV.$rec."' >";
          }
        }
      }?>
      <title><?= $_app->rec['ele']['title'] ?></title>
    </head>

    <body <?= api_ele::atr($_app->rec['ele']['body']) ?>>
      
      <!-- Botonera -->
      <header class='app_bot'>
        
        <nav class="ope">
          <?= $_app->htm['ope']['ini']; ?>
        </nav>

        <nav class="ope">
          <?= $_app->htm['ope']['fin']; ?>
        </nav>
        
      </header>

      <?php if( !empty($_app->htm['pan']) ){ ?>
        <!-- Panel -->
        <aside class='app_pan dis-ocu'>
          <?= $_app->htm['pan'] ?>
        </aside>
      <?php } ?>
      <!-- Contenido -->
      <main class="app_sec">
        <?= $_app->htm['sec'] ?>
      </main>
      
      <?php if( !empty($_app->htm['bar']) ){ ?>
        <!-- sidebar -->
        <aside class="app_bar">
          <?= $_app->htm['bar'] ?>
        </aside>
      <?php } ?>

      <?php if( !empty($_app->htm['pie']) ){  ?>
        <!-- pie de página -->
        <footer class="app_pie">
          <?= $_app->htm['pie'] ?>
        </footer>
      <?php } ?>
      <!-- Modales -->
      <section class='app_win dis-ocu'>
        <?= $_app->htm['win'] ?>
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
      foreach( $_app->rec['jso'] as $app => $cla_lis ){
        if( file_exists( $rec = "jso/{$app}.js" ) ){ echo "
        <script src='".SYS_NAV.$rec."'></script>";
        }
        foreach( $cla_lis as $cla ){
          if( file_exists( $rec = "jso/{$app}/{$cla}.js" ) ){ echo "
          <script src='".SYS_NAV.$rec."'></script>";
          }
        }
      }?>
      <script>
        // cargo datos de la interface
        var $_api = new api(<?= api_obj::cod( $_app->rec['dat'] ) ?>);

        // cargo aplicacion
        var $_app = new app();
        
        // ejecuto codigo por aplicacion
        <?= $_app->rec['eje'] ?>

        // inicializo página
        $_app.ini();

        console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

      </script>
    </body>

  </html>      