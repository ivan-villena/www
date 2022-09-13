<?php
  
  session_start();
  
  if( !isset($_SESSION['usu']) ){
    // interface
    $_SESSION['usu'] = 1;
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";
    // acceso a database
    $_SESSION['sql'] = [ 
      'ser' => $_SERVER['SERVER_NAME'], 'usu' => "admin",  'pas' => "admin", 'esq' => "_api" 
    ];
  }
  
  // require de clases manual // require_once("_/autoload.php");
  foreach( ['api','doc','app','hol','usu'] as $cla ){ 
    
    require_once("php/$cla.php");
  }

  // cargo interfaces
  $_api = new _api();

  // cargo holon
  $_hol = new _hol();

  // cargo usuario
  $_usu = new _usu( $_SESSION['usu'] );  
  
  date_default_timezone_set( !empty($_usu->ubi) ? $_usu->ubi : $_SESSION['ubi'] );  
   
  // peticion AJAX
  if( isset($_REQUEST['_']) ){  

    // log del sistema por ajax
    function _log( ...$opc ) : string {

      global $_api, $_hol;

      $_ = "  
      <h2>hola desde php<c>!</c></h2>
      ";

      return $_;
    }    
    
    echo _obj::cod( !_obj::tip( $eje = _eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );  

    die();
  }
  // cargo página
  else{

    $sis_ini = time();

    $_api_atr = [ '_uri', '_ses', '_ico', '_let', '_var_tip', '_dat_val', '_fec_mes', '_fec_sem', '_fec_dia' ];

    // peticion url ( por defecto: holon )
    $_uri = $_api->uri('hol');

    // directorios
    $_dir = $_api->dir( $_uri );

    // sesion
    $_ses = $_api->ses( $_uri->esq );

    // pagina
    $_app = new _app();
  }
?>
<!DOCTYPE html>
<html lang="es">
    
  <head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width = device-width, initial-scale = 1, maximum-scale = 1">

    <?php // hojas de estilo
    foreach( $_app->css as $ide ){ 
      if( file_exists( $rec = "css/{$ide}.css" ) ){ echo "
        <link rel='stylesheet' href='".SYS_NAV.$rec."' >";
      }
    } ?>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Material+Icons+Outlined'>
    <link rel='stylesheet' href='<?= SYS_NAV."css/api.css" ?>' >
    
    <title><?= $_app->nom ?></title>

  </head>

  <body <?= _htm::atr($_app->ele['body']) ?>>
        
    <!-- Botonera -->
    <aside class='ope'>

      <!-- Paneles del navegador -->
      <nav class="ope dir-ver">

        <?= $_app->ope['ini']; ?>
        
        <?= $_app->ope['nav']; ?>

        <?= $_app->ope['win']; ?>
      </nav>

      <!-- API -->
      <nav class="ope dir-ver">

        <?= $_app->ope['nav_fin']; ?>

        <?= $_app->ope['win_fin']; ?>

        <?php
        $win = [];
        // consola
        if( $_usu->ide == 1 ) 
          $win['api_adm'] = [ 'ico'=>"eje", 'nom'=>"Administrador del Sistema" ];
        // usuario + loggin
        if( empty($_usu->ide) ){ 
          $win['ses_ini'] = [ 'ico'=>"ses_ini", 'nom'=>"Iniciar Sesión..."];
        }
        else{ 
          $win['ses_fin'] = [ 'ico'=>"ses_fin", 'nom'=>"Cerrar Sesión..."];
        }
        
        echo _app_ope::bot([ 'win'=>$win ]);
        ?>
      </nav>

    </aside>

    <!-- Navegador -->
    <aside class='nav dis-ocu'>

      <?= $_app->nav ?>

    </aside>

    <!-- Secciones -->
    <main>

      <?= $_app->sec ?>

    </main>
    
    <?php if( !empty($_app->pan) ){ ?>      
      <!-- sidebar -->
      <aside class='pan'>

        <?= $_app->pan ?>

      </aside>
    <?php } ?>

    <?php if( !empty($_app->pie) ){  ?>
      <!-- pie de página -->
      <footer>

        <?= $_app->pie ?>

      </footer>
    <?php } ?>

    <!-- Interface -->
    <section id='win' class='dis-ocu'>
      <?php 
      // imprimo consola del administrador
      if( $_usu->ide == 1 ){
        $_app->jso []= "api/adm";
        include("php/api/adm.php");
      }
      // sesion del usuario
      $tip = empty($_usu->ide) ? 'ini' : 'fin';
      include("php/api/ses.php");

      // imprimo pantallas ?>
      <?= $_app->win ?>

    </section>

    <!-- Programas -->
    <?php
    foreach( $_app->jso as $ide ){       
      if( file_exists( $rec = "jso/{$ide}.js" ) ){ echo "
        <script src='".SYS_NAV.$rec."'></script>";
      }
    }// cargo datos operativos
    $_api_dat = [];
    foreach( $_api_atr as $atr ){

      if( isset($_api->{$atr}) ) $_api_dat[$atr] = $_api->{$atr};
    }
    ?>
    <script>
      // cargo datos de la interface
      var $_api = new _api(<?= _obj::cod( $_api_dat ) ?>);
      // cargo aplicacion
      var $_app = new _app();
      // inicializo documento
      $_app.ini();
      // ejecucion inicial
      <?= $_app->eje ?>

      console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

    </script>    
  </body>

</html>