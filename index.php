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
  foreach( ['api','hol','doc'] as $cla ){

    require_once("php/$cla.php");
  }
  // cargo interfaces
  $_api = new _api();

  $_hol = new _hol();
  
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
    $_doc = $_api->doc();

    // datos del usuario
    $_usu = $_api::_('usu');
    
    // ajusto diseño  
    
    if( !empty($_doc->pan) || !empty($_doc->pie) ){
      $_ver = [];
      if( !empty($_doc->pan) ) $_ver []= 'pan';
      if( !empty($_doc->pie) ) $_ver []= 'pie';
      $_doc->ele['body']['data-ver'] = implode(',',$_ver);
    }
  }
?>
<!DOCTYPE html>
<html lang="es">
    
  <head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width = device-width, initial-scale = 1, maximum-scale = 1">

    <?php // hojas de estilo
    foreach( $_doc->css as $ide ){ 
      if( file_exists( $rec = "css/{$ide}.css" ) ){ echo "
        <link rel='stylesheet' href='".SYS_NAV.$rec."' >";
      }
    } ?>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Material+Icons+Outlined'>
    <link rel='stylesheet' href='<?= SYS_NAV."css/doc.css" ?>' >
    
    <title><?= $_doc->nom ?></title>

  </head>

  <body <?= _htm::atr($_doc->ele['body']) ?>>
        
    <!-- Botonera -->
    <aside class='ope'>

      <!-- Paneles del navegador -->
      <nav class="ope dir-ver">

        <?= $_doc->ope['ini']; ?>
        
        <?= _doc::ico('ses',[ 'eti'=>"a", 'href'=>SYS_NAV."/{$_uri->esq}", 'title'=>"Inicio..." ]); ?>
        
        <?= $_doc->ope['nav']; ?>
        
      </nav>

      <!-- Pantallas emergente -->
      <nav class="ope dir-ver">

        <?= $_doc->ope['win']; ?>

        <?php
        // consola
        if( $_usu->ide == 1 ) 
          echo _doc_art::ope([ 'win'=>[ 'api_adm'=>[ 'ico'=>"eje", 'nom'=>"Administrador del Sistema" ] ]]);
        // usuario
        if( empty($_usu->ide) ){ 
          echo _doc_art::ope([ 'win'=>[ 'ses_ini'=>[ 'ico'=>"ses_ini", 'nom'=>"Loggin"] ]]);
        }
        else{ 
          echo _doc_art::ope([ 'win'=>[ 'ses_usu'=>[ 'ico'=>"ope", 'nom'=>"Cuenta de Usuario"] ]]);
        }?>
      </nav>

    </aside>

    <!-- Navegador -->
    <aside class='nav dis-ocu'>

      <?= $_doc->nav ?>

    </aside>

    <!-- Secciones -->
    <main>

      <?= $_doc->sec ?>

    </main>
    
    <?php if( !empty($_doc->pan) ){ ?>      
      <!-- sidebar -->
      <aside class='pan'>

        <?= $_doc->pan ?>

      </aside>
    <?php } ?>

    <?php if( !empty($_doc->pie) ){  ?>
      <!-- pie de página -->
      <footer>

        <?= $_doc->pie ?>

      </footer>
    <?php } ?>

    <!-- Interface -->
    <section id='win' class='dis-ocu'>
      <?php 
      // imprimo consola del administrador
      if( $_usu->ide == 1 ){ 
        $_doc->jso []= "api/adm";
        include("php/api/adm.php");
      }
      // sesion del usuario
      $tip = empty($_usu->ide) ? 'ini' : 'usu';
      include("php/api/ses.php");

      // imprimo pantallas ?>

      <?= $_doc->win ?>
    </section>

    <!-- Programas -->
    <?php
      foreach( $_doc->jso as $ide ){       
        if( file_exists( $rec = "jso/{$ide}.js" ) ){ 
          echo "<script src='".SYS_NAV.$rec."'></script>";
        }
      }
      // cargo datos operativos
      $_api_dat = [];
      foreach( $_api_atr as $atr ){

        if( isset($_api->{$atr}) ) $_api_dat[$atr] = $_api->{$atr};
      }
    ?>
    <script>

      var $_api = new _api(<?= _obj::cod( $_api_dat ) ?>);

      var $_doc = new _doc();

      <?= $_doc->cod ?>

      console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

    </script>    
  </body>

</html>