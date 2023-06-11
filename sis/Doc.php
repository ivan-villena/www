<?php

// Pagina-Documento
class Doc {

  /* Peticion : esq/cab/art/val=$ */
  public object $Uri;
  
  // Clases : js / php / css
  public array $Cla = [];  
  
  // Solo Registros
  public array $Dat = [];  
  
  // Estructuras de Datos
  public array $Est = []; 
  
  // Ejecuciones por Aplicacion
  public string $Eje = "";
  
  // Elementos del Documento
  public array $Ele = [];
  
  // Accesos de cabecera
  public array $Cab = [];
  
  // Sesion del Usuario
  public string $Usu = "";
  
  // HTML por secciones
  public array $Htm = []; 
  
  // Errores de carga y ejecucion
  public array $Err = [];

  // id-secuencial por clave unica
  static array $Ide = [];

  public function __construct( string $uri = "" ){

    $this->Cla = [
      'Var'=>[
        'Num', 'Tex', 'Fec', 'Obj', 'Eje', 'Ele', 'Arc'
      ],      
      'Sis'=>[
        'Dat', 'Doc', 'Usu', 'App'
      ],
      'Doc'=>[
        'Dat', 'Ope', 'Val', 'Var'
      ]
    ];

    $this->Dat = [

      'var'=>[ 
        'tip', 'tex_let', 'tex_ico'
      ]
    ];

    $this->Cab = [
      'ini'=>[
        'app_ini'=>[ 'ico'=>"app",          'url'=>SYS_NAV,  'nom'=>"Página de Inicio"     ],
        'usu_ses'=>[ 'ico'=>"ope_nav-ini",  'tip'=>"win",    'nom'=>"Iniciar Sesión..."    ],
        'app_usu'=>[ 'ico'=>"usu",          'tip'=>"win",    'nom'=>"Cuenta de Usuario..." ],        
        'app_cab'=>[ 'ico'=>"app_cab",      'tip'=>"pan",    'nom'=>"Menú Principal"       ],
        'app_nav'=>[ 'ico'=>"app_nav",      'tip'=>"pan",    'nom'=>"Índice"               ]
      ],
      'med'=>[
        'app_dat'=>[ 'ico'=>"dat_des", 'tip'=>"win",    'nom'=>"Ayuda" ]
      ],
      'fin'=>[
        'app_adm'=>[ 'ico'=>"eje", 'tip'=>"win",    'nom'=>"Consola del Sistema..." ]
      ]
    ];

    $this->Htm = [
      // titulo
      'tit'=>"{-_-}",
      // botones
      'cab'=>[ 'ini'=>"", 'med'=>"", 'fin'=>"", 'tod'=>""  ],
      // paneles
      'pan'=>"",
      // main
      'sec'=>"",
      // modales
      'win'=>"",
      // barra lateral
      'bar'=>"",
      // barra inferior
      'pie'=>""    
    ];
    
    $this->Uri = $this->Uri( $uri );
    
  } 

  // cargo peticion
  public function uri( string $ide ) : object {

    // armo peticion
    $dir = explode('/',$ide);

    $Uri = new stdClass;

    $Uri->esq = $dir[0];
    $Uri->cab = !empty($dir[1]) ? $dir[1] : FALSE;
    $Uri->nav = FALSE;
    $Uri->val = FALSE;

    // proceso articulo
    if( $Uri->art = !empty($dir[2]) ? $dir[2] : FALSE ){

      // proceso indice
      $nav = explode('#',$Uri->art);

      if( isset($nav[1]) ){
        $Uri->art = $nav[0];
        $Uri->nav = $nav[1];
      }
      
      // proceso parametros
      if( !empty($dir[3]) ){

        $Uri->val = $dir[3];
      }
      else{

        $val = explode('?',$Uri->art);

        if( isset($val[1]) ){
          $Uri->art = $val[0];
          $Uri->val = explode('&',$val[1]);
        }
      }
    }
    // proceso parametros
    else{

      $val = explode('?',$Uri->cab);

      if( isset($val[1]) ){
        $Uri->cab = $val[0];
        $Uri->val = explode('&',$val[1]);
      }
    }

    return $Uri;

  }

  // cargo directorios por aplicacion
  public function dir( App $App = NULL ) : object {

    $Uri = $this->Uri;

    $Dir = new stdClass();
    
    $Dir->esq = SYS_NAV."{$Uri->esq}";            

    $Dir->esq_ima = SYS_NAV."_img/{$Uri->esq}/";

    if( !empty($Uri->cab) ){

      $Dir->cab = SYS_NAV."{$Uri->esq}/{$Uri->cab}";

      $Dir->cab_ima = SYS_NAV."_img/{$Uri->esq}/{$Uri->cab}/";

      if( !empty($Uri->art) ){
  
        $Dir->art = SYS_NAV."{$Uri->esq}/{$Uri->cab}/{$Uri->art}";
      
        $Dir->art_ima = "{$Dir->cab_ima}{$Uri->art}/";
      }          
    }

    // listado de enlaces por menu
    if( isset($App) ){

      foreach( $App->cab_ver() as $_cab ){

        // para enlaces
        $Dir->{$_cab->ide} = SYS_NAV."{$Uri->esq}/{$_cab->ide}/";

        // para imagenes
        $Dir->{"{$_cab->ide}_ima"} = "{$Dir->esq_ima}{$_cab->ide}/";
      }      
    }

    return $Dir;
  }
  
  // Pido Contenido html con ejecucion por Aplicacion
  static function eje( string $ide ){

    $esq = Tex::let_pal( explode('/',$ide)[0] );

    return Eje::htm("./App/{$ide}", "./App/{$esq}");
  }

  // pido clave secuencial por clave unica
  static function ide( string $key ) : string {

    if( !isset(self::$Ide[$key]) ) self::$Ide[$key] = 0;

    self::$Ide[$key]++;

    return self::$Ide[$key];
  }
  
  // cargo modulos del documento: javascript + css
  public function cla( string $tip = "", array $dat = [] ) : string {
    $_ = "";
    
    if( empty($dat) ) $dat = $this->Cla;
    
    // estilos
    if( $tip == 'css' ){

      foreach( $dat as $mod_ide => $mod_lis ){
        
        // por aplicacion
        if( file_exists( "./".($rec = "{$mod_ide}.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";

        // por módulos
        foreach( $mod_lis as $cla_ide ){

          if( file_exists( "./".($rec = "{$mod_ide}/{$cla_ide}.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
        }
      }
    }
    // programa
    elseif( $tip == 'jso' ){
    
      foreach( $dat as $mod_ide => $mod_lis ){
        // por raiz
        if( is_string($mod_lis) ){

          if( file_exists( "./".($rec = "{$mod_lis}.js") ) ) $_ .= "
          <script src='".SYS_NAV."$rec'></script>";   
        }
        else{
          
          // por aplicacion
          if( file_exists( "./".($rec = "{$mod_ide}.js") ) ) $_ .= "
            <script src='".SYS_NAV."$rec'></script>";

          // por modulos        
          foreach( $mod_lis as $cla_ide ){ 
            
            if( file_exists( "./".($rec = "{$mod_ide}/{$cla_ide}.js") ) ) $_ .= "
              <script src='".SYS_NAV."$rec'></script>";
          }
        }
      }
    }

    return $_;
  }

  // cargo estructuras de datos
  public function est() : array {

    $_ = [];
    
    foreach( Dat::$Est as $esq => $esq_lis ){

      $Est = [];

      foreach( $esq_lis as $est => $est_ope ){

        // paso toda la estructura
        if( isset($this->Est[$esq]) && in_array($est,$this->Est[$esq]) ){

          $Est[$est] = $est_ope;

        }// solo paso datos
        elseif( isset($this->Dat[$esq]) && in_array($est,$this->Dat[$esq]) && isset($est_ope->dat) ){

          $Est[$est] = [ 'dat' => $est_ope->dat ];
        }
      }

      if( !empty($Est) ) $_[$esq] = $Est;
    }

    return $_;
  }

  // imprimo pagina por Aplicacion
  public function htm( App $App ){

    // Proceso Documento
    $htm = $this->Htm;    

    // cargo botones y html para enlaces a paneles y modales
    foreach( $this->Cab as $tip => $tip_lis ){

      foreach( $tip_lis as $ide => $ope ){
        
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $htm['cab'][$tip] .= Doc_Val::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $htm['cab'][$tip] .= Doc_Ope::cab([ $ide => $ope ]);
          // html: pan / win
          $htm[$ope['tip']] .= Doc_Ope::{"{$ope['tip']}"}($ide,$ope);
        }
      }  
    }

    // proceso Errores
    if( !empty($this->Err) ){
      
      $htm['tit'] = "Error";

      $htm['sec'] = Doc_Ope::tex([ 'tip'=>"err", 'tex'=>$this->Err ]);

    }// agrego Modal de operaciones
    else{
      
      $htm['win'] .= Doc_Ope::win('ope',[ 'ico'=>"", 'nom'=>"Operador" ]);
    }
    
    ?>
    <!DOCTYPE html>
    <html lang="es">
          
      <head>
        <!-- parametros -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- hojas de estilo -->
        <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
        <?=$this->cla('css')?>
        <link rel='stylesheet' href='<?=SYS_NAV?>Sis/Api/css.css'>
        <!-- aplicacion -->
        <title><?=$htm['tit']?></title>
      </head>
  
      <body <?=Ele::atr([
        'data-esq'=>isset($App->Esq->ide) ? $App->Esq->ide : NULL, 
        'data-cab'=>isset($App->Cab->ide) ? $App->Cab->ide : NULL, 
        'data-art'=>isset($App->Art->ide) ? $App->Art->ide : NULL
      ])?>>
        
        <?php // Cabecera con Operador : botones de accesos a enlaces, paneles y modales
        if( !empty($htm['cab']['ini']) || !empty($htm['cab']['fin']) || !empty($htm['cab']['tod']) ){
          ?>
          <!-- Operador -->
          <header class='ope_cab'>
            <?php
            if( !empty($htm['cab']['tod']) ){

              echo $htm['cab']['tod'];
            }
            else{
              ?>
              <nav class='ini'>
                <?= !empty($htm['cab']['ini']) ? $htm['cab']['ini'] : "" ?>
                <?= !empty($htm['cab']['med']) ? $htm['cab']['med'] : "" ?>
              </nav>
    
              <nav class='fin'>
                <?= !empty($htm['cab']['fin']) ? $htm['cab']['fin'] : "" ?>
              </nav>
            <?php
            }
            ?>
          </header>        
          <?php
        } ?>
  
        <?php // Paneles Ocultos
        if( !empty($htm['pan']) ){ ?>
          <!-- Panel -->
          <aside class='ope_pan dis-ocu'>
            <?= $htm['pan'] ?>
          </aside>
          <?php 
        } ?>
        
        <?php // Contenido Principal
        if( !empty($htm['sec']) ){
          ?>
          <!-- Contenido -->
          <main class='ope_sec'>
            <?= Doc_Ope::sec( $htm['sec'] ) ?>
          </main>          
          <?php
        } ?>
              
        <?php // Lateral: siempre visible
        if( !empty($htm['bar']) ){ ?>
          <!-- Sidebar -->
          <aside class='ope_bar'>
            <?= $htm['bar'] ?>
          </aside>
          <?php 
        } ?>
  
        <?php // pié de página
        if( !empty($htm['pie']) ){  ?>
          <!-- pie de página -->
          <footer class='ope_pie'>
            <?= $htm['pie'] ?>
          </footer>
          <?php 
        } ?>
        
        <!-- Modales -->
        <div class='ope_win dis-ocu'>
          <?= $htm['win'] ?>
        </div>
        
        <!-- Cargo Sistema -->
        <script>
          // Rutas
          const SYS_NAV = "<?=SYS_NAV?>";        
          // Clases
          const DIS_OCU = "<?=DIS_OCU?>";
          const FON_SEL = "<?=FON_SEL?>";
          const BOR_SEL = "<?=BOR_SEL?>";
        </script>
  
        <!-- Módulos -->
        <?=$this->cla('jso')?>
        
        <!-- Inicio Aplicación -->
        <script>
          // Cargo Documento
          var $Doc = new Doc();
  
          var $App = new App(<?= Obj::val_cod([
            'Esq'=>isset($App->Esq->ide) ? $App->Esq->ide : NULL,
            'Cab'=>isset($App->Cab->ide) ? $App->Cab->ide : NULL,
            'Art'=>isset($App->Art->ide) ? $App->Art->ide : NULL
          ])?>);

          var $Dat = new Dat(<?= Obj::val_cod([ 'Est'=>$this->est() ]) ?>);

          // Cargo Aplicacion
          if( <?=$app=Tex::let_pal($App->Esq->ide)?> ){

            var <?="\${$app} = new $app();"?>
          }          

          // Inicializo Aplicacion: Menú e Índices
          $App.ini();

          // ejecuto codigo
          <?= $this->Eje ?>

          // inicializo pagina
          if( !!<?="\${$app}"?>.inicio ){

            <?="\${$app}"?>.inicio();
          }
          
          // calculo tiempo de carga
          console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $_SESSION['ini'] ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
  
        </script>
  
      </body>
  
    </html>
    <?php
  }
}