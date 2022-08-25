<?php

  session_start();

  if( empty($_SESSION['ubi']) ){
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";
    $_SESSION['ser'] = $_SERVER['SERVER_NAME'];
    $_SESSION['usu'] = "admin";
    $_SESSION['pas'] = "admin";
    $_SESSION['esq'] = "_api";
  }

  date_default_timezone_set( $_SESSION['ubi'] );

  $sis_ini = time();  

  // require de clases
  require_once("_/autoload.php");

  require_once("php/doc.php");

  $_api = new _api();

  $_hol = new _hol();

  $_usu = new _usu( $_SESSION['usu'] );

  // peticion AJAX
  if( isset($_REQUEST['_']) ){  

    // log del sistema por ajax
    function _log( ...$opc ) : string {

      global $_api, $_hol, $_usu;

      $_ = "  
      <h2>hola desde php<c>!</c></h2>
      ";
      
      foreach ( $_hol->_('kin_cro_ele') as $_ele ) {
        $_.=" 
          UPDATE _hol.kin_cro_ele SET `ele` = "._num::ran($_ele->ide+1,4)." WHERE `ide` = $_ele->ide; <br>
        ";
      }      

      return $_;
    }    
    
    echo _dat::cod( !_obj::tip( $eje = _eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );  

    die();
  }
  // cargo página
  else{

    $_api_atr = [ '_uri', '_ses', '_ico', '_tex_let', '_var_tip', '_dat_val', '_fec_mes', '_fec_sem', '_fec_dia' ];

    // peticion url ( por defecto: holon )
    $_uri = $_api->uri('hol');

    // cargo directorios
    $_dir = $_api->dir( $_uri );

    // cargo sesion
    $_ses = $_api->ses( $_uri->esq );

    // cargo pagina
    $_doc = $_api->doc();
    
    // contenido
    if( file_exists( $rec = "app/{$_uri->esq}.php" ) ){
      
      $_doc->ele = ['doc'=>$_uri->esq, 'cab'=> !!$_uri->cab ? $_uri->cab : NULL, 'art'=> !!$_uri->art ? $_uri->art : NULL];

      ob_start();

      include( $rec );

      $_doc->htm = ob_get_clean();

    }
    else{
      $_doc->htm = "No existe la página principal";
    }
  }
?>
<!DOCTYPE html>
<html lang="es">
    
  <head>

    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php // hojas de estilo
    foreach( $_doc->css as $ide ){ 
      if( file_exists( $rec = "css/{$ide}.css" ) ){ ?>
        <link rel='stylesheet' href='<?= SYS_NAV.$rec ?>' >
      <?php }
    } ?>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Material+Icons+Outlined'>
    <link rel='stylesheet' href='<?= SYS_NAV."css/doc.css" ?>' >

    <title><?= $_doc->nom ?></title>

  </head>

  <body <?= _htm::atr($_doc->ele) ?>>
        
    <!-- Cabecera -->
    <header>

      <?= $_doc->cab_ini ?>

      <nav class='ope'>
        <?php          
        // inicio
        echo _doc::ico('ses',[ 'eti'=>"a", 'href'=>SYS_NAV."/{$_uri->esq}", 'title'=>"Inicio..." ]);

        // consola
        if( $_usu->ide == 1 ) echo _doc_nav::ver([ 'win'=>[ 'api_adm'=>[ 'ico'=>"eje", 'nom'=>"Administrador del Sistema" ] ]]);
        
        // usuario
        if( $_usu->ide > 0 ){

          echo _doc_nav::ver([ 'win'=>[ 'usu_dat'=>[ 'ico'=>"ses_adm", 'nom'=>"Cuenta de Usuario"] ]]);
        }
        else{
          echo _doc_nav::ver([ 'win'=>[ 'usu_log'=>[ 'ico'=>"ses_ini", 'nom'=>"Loggin"] ]]);
        }

        // botones de acceso
        if( !empty($_doc->cab_nav) ){ 
          
          echo $_doc->cab_nav;
        }
        ?>
      </nav>        
      
      <?= $_doc->cab_fin ?>
      
    </header>    

    <!-- Contenido -->
    <main>
      <?php

        if( !empty($_doc->art_nav) || !empty($_doc->art_ini) ){ ?>
          <aside class='ini'>

            <?php if( !empty($_doc->art_nav) ) { ?>

              <nav class='ope dir-ver pad-1'>

                <?= $_doc->art_nav ?>

              </nav>

            <?php } ?>

            <?php if( !empty($_doc->art_ini) ) { ?>

              <?= $_doc->art_ini ?>

            <?php } ?>
          </aside>
        <?php 
        } 

        echo $_doc->htm;

        if( !empty($_doc->art_fin) ){ ?>
          <aside class='fin'>
            <?= $_doc->art_fin ?>
          </aside>
        <?php }
      ?>
    </main>

    <!-- Interface -->
    <div id='win' class='dis-ocu'>
      <?php 
        // imprimo consola del administrador
        if( $_usu->ide == 1 ){ 

          $_doc->jso []= "api/adm";

          include("php/api/adm.php");
        }

        // cuenta de usuario
        ( $_usu->ide > 0 ) ? _usu_dat::val() : include("app/api/log.php");
        
        // pantallas
        if( isset($_doc->win) ){ 
          
          echo $_doc->win;
        }
      ?>
    </div>

    <!-- Programas -->
    <?php
      foreach( $_doc->jso as $ide ){       

        if( file_exists( $rec = "jso/{$ide}.js" ) ){ ?>

          <script src='<?= SYS_NAV.$rec ?>'></script> <?php
        } 
      }// cargo datos operativos
      $_api_dat = [];
      foreach( $_api_atr as $atr ){

        if( isset($_api->{$atr}) ) $_api_dat[$atr] = $_api->{$atr};
      }
    ?>
    <script>

      var $_api = new _api(<?= _dat::cod( $_api_dat ) ?>);

      var $_doc = new _doc();

      <?= $_doc->cod ?>

      console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

    </script>
  </body>

</html>