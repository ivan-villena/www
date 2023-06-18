<?php
  // mostrar errores:
  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  // Rutas :: localhost / icpv.com.ar  
  define('SYS_NAV', "http://{$_SERVER['HTTP_HOST']}/" );
  
  // Base de datos: local / produccion
  define('DAT_SER', 'localhost');
  define('DAT_USU', "c1461857_api");
  define('DAT_PAS', "lu51zakoWA");
  define('DAT_ESQ', "c1461857_api");
  define('DAT_APP', "sincronario");
  
  // Elementos : Clases
  define('DIS_OCU', "dis-ocu");
  define('BOR_SEL', "bor-sel");
  define('FON_SEL', "fon-sel");

  // Variables
  require_once("./Var/Num.php");
  require_once("./Var/Tex.php");
  require_once("./Var/Fec.php");
  require_once("./Var/Obj.php");
  require_once("./Var/Eje.php");
  require_once("./Var/Ele.php");
  require_once("./Var/Arc.php");

  // Sistema
  require_once("./Sis/Dat.php");
  require_once("./Sis/Doc.php");
  require_once("./Sis/Usu.php");
  require_once("./Sis/App.php");

  // Documento
  require_once("./Doc/Val.php");
  require_once("./Doc/Var.php");
  require_once("./Doc/Ope.php");
  require_once("./Doc/Dat.php");

  // Sesion
  session_start();

  if( !isset($_SESSION['Fec']) ){
    // Fecha 
    $_SESSION['Fec'] = Fec::dat();    
    // ubiccacion por defecto
    $_SESSION['ubi'] = "America/Argentina/Buenos_Aires";
    // objeto peticiones
    $_SESSION['Uri'] = new stdClass;
  }
  
  date_default_timezone_set( $_SESSION['ubi'] );

  // inicializo tiempo de ejecucion
  $_SESSION['ini'] = time();
  
  // inicializo listado de errores
  $_SESSION['Err'] = [];
  
  // genero usuario
  $Usu = new Usu( !empty($_SESSION['usu']) ? $_SESSION['usu'] : 0 );  

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // peticion AJAX
  if( isset($_REQUEST['_']) ){

    include("./Sis/Api/log.php");

    // ver cabeceras para api's: tema no-cors
    echo Obj::val_cod( !Obj::tip( $eje = Eje::val($_REQUEST['_']) ) ? [ '_' => $eje ] : $eje );

    exit;
  }

  ////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////

  // cargo controlador
  $Doc = new Doc( isset($_REQUEST['uri']) ? $_REQUEST['uri'] : DAT_APP );

  // cargo y actualizo rutas
  $Uri = $_SESSION['Uri'] = $Doc->Uri;

  // cargo aplicacion
  $App = new App();

  // cargo directorios
  $Dir = $Doc->Dir( $App );

  // valido rutas y cargo datos de la aplicacion
  if( empty( $Doc->Err = $_SESSION['Err'] ) ){

    // cargo clases principales
    $Doc->Cla["App/{$Uri->esq}"] = [ "Usuario" ];
          
    if( !empty($Uri->cab) ){
      // de contenido
      $Doc->Cla["App/{$Uri->esq}"] []= $Uri->cab;
      
      // de articulo
      if( !empty($Uri->art) ){ 
        
        $Doc->Cla["App/{$Uri->esq}"] []= "{$Uri->cab}/{$Uri->art}";
      }
    }

    // cargo usuario
    if( !empty($Usu->key) && file_exists($rec_usu = "./App/{$Uri->esq}/Usuario.php") ){

      require_once($rec_usu);
      
      $Usuario = new Usuario();      

      // cargo datos de la sesion
      if( method_exists($Usuario,'ver_sesion') ){
        
        $Doc->Usu = $Usuario->ver_sesion();
      }    
    }

    // cargo clase de la aplicacion
    if( file_exists($rec_cla = "./App/".Tex::let_pal($Uri->esq).".php") ){
      
      require_once( $rec_cla );
    }

    // cargo modulo global
    if( file_exists($rec_app = "./App/{$Uri->esq}/index.php") ){

      require_once( $rec_app );
    }

    // imprimo inicio
    if( empty($Uri->cab) || empty($Uri->art) ){

      if( file_exists($rec_ini = "./App/{$Uri->esq}/inicio.php")  ){

        // cargo estilos
        $Doc->Cla["App/{$Uri->esq}"] []= "inicio";

        ob_start();
        include( $rec_ini );
        $Doc->Htm['sec'] = ob_get_clean();
      }
    }
    // imprimo seccion
    elseif( empty($Uri->art) ){

      if( file_exists($rec_cab = "./App/{$Uri->esq}/{$Uri->cab}.php")  ){

        ob_start();
        include( $rec_cab );
        $Doc->Htm['sec'] = ob_get_clean();
      }
    }
    // imprimo articulos
    else{

      if( is_string( $doc_rec = $App->art() ) ){

        $Doc->Err []= $doc_rec;
      }
      else{
        
        // cargo indice
        $Nav = $App->Nav;
        
        // imprimo e importo modulo
        if( isset($doc_rec['cab']) ){
          
          ob_start();
          include( $doc_rec['cab'] );
          $Doc->Htm['sec'] = ob_get_clean();
        }
    
        // imprimo articulo
        if( isset($doc_rec['art']) ){ 

          ob_start();
          include( $doc_rec['art'] ); 
          $Doc->Htm['sec'] = ob_get_clean();
        }
      }
    }

    // ajusto enlace de inicio
    $Doc->Cab['ini']['app_ini']['url'] .= "/{$Uri->esq}";
    
    // imprimo sesion del usuario
    if( empty($Usu->key) ){

      $Doc->Cab['ini']['usu_ses']['htm'] = $Usu->ses('ini');

    }// imprimo menu de usuario por aplicacion
    else{

      $Doc->Cab['ini']['app_usu']['htm'] = $Doc->Usu.Doc_Ope::nav('bot', $App->usu() );
    }      

    // imprimo menú principal
    $Doc->Cab['ini']['app_cab']['htm'] = $App->cab();

    // imprimo indice
    if( !empty( $Nav ) ){

      $Doc->Cab['ini']['app_nav']['htm'] = $App->nav();
    }
    
    // cargo tutorial    
    
    if( !empty( $rec = Arc::val_rec("./App/{$Uri->esq}/ayuda/".( !empty($Uri->cab) ? $Uri->cab : "inicio" )) ) ){
      
      ob_start();
      include($rec);
      $Doc->Cab['med']['app_dat']['htm'] = ob_get_clean();
    }
    
    // imprimo consola del sistema
    if( $Usu->key == 1 ){

      $Doc->Cab['fin']['app_adm']['htm'] = $App->adm();
    }

    // titulo: por articulo
    if( !empty($App->Art->nom) ){

      $Doc->Htm['tit'] = $App->Art->nom;
    }// por menu
    elseif( !empty($App->Cab->nom) ){

      $Doc->Htm['tit'] = $App->Cab->nom;
    }// por aplicacion
    elseif( !empty($App->Esq->nom) ){

      $Doc->Htm['tit'] = $App->Esq->nom; 
    }    
  }

  // Imprimo Pagina...
  $Doc->htm( $App );
  ?>