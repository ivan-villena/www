<?php

// Pagina-Documento
class Doc {

  // Clases : js / php / css
  public array $Cla = [];  
  // Solo Registros
  public array $Dat = [];  
  // Estructuras
  public array $Est = []; 
  // Ejecuciones
  public string $Eje = "";
  // Elementos
  public array $Ele = [];
  // Accesos de cabecera
  public array $Cab = [];
  // Contenido del Usuario
  public string $Usu = "";
  // codigo HTML por secciones
  public array $Htm = [];  

  public function __construct(){

    $this->Cla = [
      'Sis'=>[
        'Num', 'Tex', 'Fec', 'Obj', 'Eje', 'Ele', 'Arc', 'Dat', 'Doc', 'Usu', 'App'
      ],
      'Api'=>[
        'sql', 'dom'
      ],
      'Doc'=>[
        'Dat', 'Ope', 'Val', 'Var', 'Hol'
      ]
    ];

    $this->Dat = [

      'sis'=>[ 
        'dat_tip', 'tex_let', 'tex_ico'
      ]
      
    ];

    $this->Cab = [
      'ini'=>[
        'app_ini'=>[ 'ico'=>"app",     'url'=>SYS_NAV,  'nom'=>"Página de Inicio" ],
        'app_cab'=>[ 'ico'=>"app_cab", 'tip'=>"pan",    'nom'=>"Menú Principal"   ],
        'app_nav'=>[ 'ico'=>"app_nav", 'tip'=>"pan",    'nom'=>"Índice"           ]
      ],
      'med'=>[
        'app_dat'=>[ 'ico'=>"dat_des", 'tip'=>"win",    'nom'=>"Ayuda" ]
      ],
      'fin'=>[
        'ses_ini'=>[ 'ico'=>"app_ini", 'tip'=>"win",    'nom'=>"Iniciar Sesión..."    ],
        'ses_usu'=>[ 'ico'=>"usu",     'tip'=>"win",    'nom'=>"Cuenta de Usuario..."   ],
        'app_adm'=>[ 'ico'=>"eje",     'tip'=>"win",    'nom'=>"Consola del Sistema..." ]
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
  }
  
  public function cla( string $tip = "", array $dat = [] ) : string {
    $_ = "";
    
    if( empty($dat) ) $dat = $this->Cla;
    
    // estilos
    if( $tip == 'css' ){

      foreach( $dat as $mod_ide => $mod_lis ){
        // por aplicacion
        if( file_exists( "./".($rec = "{$mod_ide}.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";        

        // por página
        if( file_exists( "./".($rec = "{$mod_ide}/index.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";

        // por módulos
        foreach( $mod_lis as $cla_ide ){

          if( file_exists( "./".($rec = "{$mod_ide}/{$cla_ide}.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
        }          
      }
    }
    // prorama : clases 
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
          // por página
          if( file_exists( "./".($rec = "{$mod_ide}/index.js") ) ) $_ .= "
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
    
  public function htm( App $App, Usu $Usu ){

    // cargo rutas
    $Uri = $App->Uri;

    // Cargo clases : por aplicacion
    $this->Cla["App/{$Uri->esq}"] = [];

    if( !empty($Uri->cab) ){ 
      
      // de contenido
      $this->Cla["App/{$Uri->esq}"] []= $Uri->cab;
      
      // de articulo
      if( !empty($Uri->art) ){
        $this->Cla["App/{$Uri->esq}"][] = "{$Uri->cab}/{$Uri->art}";
      }
    }

    // cargo elemento principal
    $this->Ele['body'] = [ 
      'data-doc'=>$Uri->esq, 
      'data-cab'=>!!$Uri->cab ? $Uri->cab : NULL, 
      'data-art'=>!!$Uri->art ? $Uri->art : NULL 
    ];    

    // imprimo sesion del usuario
    if( empty($Usu->ide) ){

      $this->Htm['win'] .= Doc_Ope::win('app-ses_ini', [
        'ico'=>"app_ini",
        'nom'=>"Iniciar Sesión...",
        'htm'=>$App->usu_ses()
      ]);

    }// imprimo menu de usuario por aplicacion
    else{

      $this->Cab['fin']['ses_usu']['htm'] = $this->Usu.Doc_Ope::nav('bot', $App->usu() );

      // imprimo consola del sistema
      if( $Usu->ide == 1 ){

        $this->Cab['fin']['app_adm']['htm'] = $App->adm();
      }      
    }

    // ajusto enlace de inicio
    $this->Cab['ini']['app_ini']['url'] .= "/{$Uri->esq}";    

    // imprimo menú principal
    $this->Cab['ini']['app_cab']['htm'] = $App->cab();

    // imprimo indice
    if( !empty( $App->Nav ) ){

      $this->Cab['ini']['app_nav']['htm'] = $App->Nav();
    }

    // cargo operadores del documento: botones y html de enlaces paneles y modales
    foreach( $this->Cab as $tip => $tip_lis ){

      foreach( $tip_lis as $ide => $ope ){
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $this->Htm['cab'][$tip] .= Doc_Val::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $this->Htm['cab'][$tip] .= Doc_Ope::cab([ $ide => $ope ]);
          // html: pan / win
          $this->Htm[$ope['tip']] .= Doc_Ope::{"{$ope['tip']}"}($ide,$ope);
        }
      }  
    }

    // modal de operadores
    $this->Htm['win'] .= Doc_Ope::win('app_ope',[ 
      'ico'=>"app_ope", 
      'nom'=>"Operador" 
    ]);

    // cargo articulos por aplicacione
    if( !empty($Uri->art) && empty($this->Htm['sec']) ){
      
      // imprimo articulo : html-php
      if( !empty( $rec = Arc::val_rec($val = "./App/$Uri->esq/$Uri->cab/$Uri->art") ) ){

        include( $rec );
      }
      else{
        
        echo Doc_Ope::tex([ 'tip'=>"err", 'tex'=>"No existe el archivo '$val'" ]);
      }
    }
    
    // ajusto diseño
    $_ver = [];
    foreach( ['bar','pie'] as $ide ){ 
      if( !empty($this->Htm[$ide]) ) $_ver []= $ide; 
    }
    if( !empty($_ver) ) $this->Ele['body']['data-ver'] = implode(',',$_ver);

    // titulo
    if( !empty($App->Art->nom) ){
      $this->Htm['tit'] = $App->Art->nom;
    }
    elseif( !empty($App->Cab->nom) ){
      $this->Htm['tit'] = $App->Cab->nom;
    }
    elseif( !empty($App->Esq->nom) ){
      $this->Htm['tit'] = $App->Esq->nom; 
    }
    
    global $sis_ini;
    $Doc = $this->Htm;

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
        <link rel='stylesheet' href='<?=SYS_NAV?>Api/css.css'>
        <!-- aplicacion -->
        <title><?=$Doc['tit']?></title>
      </head>
  
      <body <?=Ele::atr($this->Ele['body'])?>>
        
        <?php // Cabecera con Operador : botones de accesos a enlaces, paneles y modales
        if( !empty($Doc['cab']['ini']) || !empty($Doc['cab']['fin']) || !empty($Doc['cab']['tod']) ){
          ?>
          <!-- Operador -->
          <header class='ope_cab'>
            <?php
            if( !empty($Doc['cab']['tod']) ){

              echo $Doc['cab']['tod'];
            }
            else{
              ?>
              <nav class='ini'>
                <?= !empty($Doc['cab']['ini']) ? $Doc['cab']['ini'] : "" ?>
                <?= !empty($Doc['cab']['med']) ? $Doc['cab']['med'] : "" ?>
              </nav>
    
              <nav class='fin'>
                <?= !empty($Doc['cab']['fin']) ? $Doc['cab']['fin'] : "" ?>
              </nav>
            <?php
            }
            ?>
          </header>        
          <?php
        } ?>
  
        <?php // Paneles Ocultos
        if( !empty($Doc['pan']) ){ ?>
          <!-- Panel -->
          <aside class='ope_pan dis-ocu'>
            <?= $Doc['pan'] ?>
          </aside>
          <?php 
        } ?>
        
        <?php // Contenido Principal
        if( !empty($Doc['sec']) ){
          ?>
          <!-- Contenido -->
          <main class='ope_sec'>
            <?= Doc_Ope::sec( $Doc['sec'] ) ?>
          </main>          
          <?php
        } ?>
              
        <?php // Lateral: siempre visible
        if( !empty($Doc['bar']) ){ ?>
          <!-- Sidebar -->
          <aside class='ope_bar'>
            <?= $Doc['bar'] ?>
          </aside>
          <?php 
        } ?>
  
        <?php // pié de página
        if( !empty($Doc['pie']) ){  ?>
          <!-- pie de página -->
          <footer class='ope_pie'>
            <?= $Doc['pie'] ?>
          </footer>
          <?php 
        } ?>
        
        <!-- Modales -->
        <div class='ope_win dis-ocu'>
          <?= $Doc['win'] ?>
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
          const $Doc = new Doc();          
  
          const $App = new App(<?= Obj::val_cod([ 'Uri'=>$Uri ]) ?>);

          const $Dat = new Dat(<?= Obj::val_cod([ 'Est'=>$this->est() ]) ?>);

          // Inicializo Aplicacion
          $App.ini();

          // ejecuto codigo por aplicacion
          <?= $this->Eje ?>
          
          // calculo tiempo de carga
          console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
  
        </script>
  
      </body>
  
    </html>
    <?php
  }
}