<?php

// Página-app
class App {

  static string $IDE = "App-";
  static string $EJE = "App.";  

  /* Estructuras */
  public array $dat = [];

  /* Recursos */
  public array $rec = [
    // peticion
    'uri' => [
    ],
    // Clases : js / php / css
    'cla' => [  
      'api'=>[
        'arc', 'eje', 'ele', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol'
      ],
      'sis'=>[
        'sql', 'dom', 'dat', 'doc', 'app', 'usu'
      ]
    ],
    // Estructuras
    'est' => [
      'api'=>[]
    ],
    // Registros
    'dat' => [
      'api'=>[
        'dat'=>[ 'tip' ],
        'fig'=>[ 'ico' ],
        'tex'=>[ 'let' ]
      ]
    ],
    // Ejecuciones
    'eje' => ""

  ];//- Clases del programa
  public function rec_cla( string $tip = "", array $dat = [] ) : string {
    $_ = "";
    if( empty($dat) ) $dat = $this->rec['cla'];
    // estilos
    if( $tip == 'css' ){

      foreach( $dat as $mod_ide => $mod_lis ){
        // por módulos
        foreach( $mod_lis as $cla_ide ){
          if( file_exists( "./".($rec = "{$mod_ide}/{$cla_ide}.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
        }
        // por página
        if( file_exists( "./".($rec = "{$mod_ide}/index.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
      }
    }// prorama : clases 
    elseif( $tip == 'jso' ){
    
      foreach( $dat as $mod_ide => $mod_lis ){
        // por raiz
        if( is_string($mod_lis) ){
          if( file_exists( "./".($rec = "{$mod_lis}.js") ) ) $_ .= "
          <script src='".SYS_NAV."$rec'></script>";   
        }
        else{
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

  /* Peticion */
  public object $Uri
  ;// carga inicial
  public function uri( string $uri ) : object {

    // armo peticion
    $uri = explode('/',$uri);

    $this->Uri = new stdClass;
    $this->Uri->esq = $uri[0];
    $this->Uri->cab = !empty($uri[1]) ? $uri[1] : FALSE;
    $this->Uri->val = FALSE;

    if( $this->Uri->art = !empty($uri[2]) ? $uri[2] : FALSE ){
      $_val = explode('#',$this->Uri->art);
      if( isset($_val[1]) ){
        $this->Uri->art = $_val[0];
        $this->Uri->val = $_val[1];
      }
      elseif( !empty($uri[3]) ){
        $this->Uri->val = $uri[3];
      }
    }

    $Uri = $this->Uri;  

    return $Uri;

  }// - Armo directorios
  public function uri_dir() : object {

    $Uri = $this->Uri;

    $_ = new stdClass();
    
    $_->esq = SYS_NAV."{$Uri->esq}";
      
    $_->cab = "{$Uri->esq}/{$Uri->cab}";

    $_->ima = SYS_NAV."_img/{$_->cab}/";

    if( !empty($Uri->art) ){

      $_->art = $_->cab."/{$Uri->art}";
    
      $_->ima .= "{$Uri->art}/";
    }

    return $_;
  }

  /* Pagina */
  public array $Doc = [
    // Elementos
    'ele' => [
    ],
    // Cabecera: Botones de Acceso
    'cab' => [
      'ini'=>[
        'app_ini'=>[ 
          'ico'=>"app",     'url'=>SYS_NAV,  'nom'=>"Página de Inicio" 
        ],
        'app_cab'=>[ 
          'ico'=>"app_cab", 'tip'=>"pan",    'nom'=>"Menú Principal" 
        ],
        'app_nav'=>[ 
          'ico'=>"app_nav", 'tip'=>"pan",    'nom'=>"Índice", 
          'htm'=>"" // no se imprime por defecto
        ]
      ],
      'med'=>[
        'app_dat'=>[ 
          'ico'=>"dat_des", 'tip'=>"win",    'nom'=>"Ayuda" 
        ]
      ],
      'fin'=>[
        'ses_ini'=>[ 
          'ico'=>"app_ini", 'tip'=>"win",    'nom'=>"Iniciar Sesión..."  
        ],
        'ses_ope'=>[ 
          'ico'=>"usu",     'tip'=>"win",    'nom'=>"Cuenta de Usuario..." 
        ],
        'app_adm'=>[ 
          'ico'=>"eje",     'tip'=>"win",    'nom'=>"Consola del Sistema..." 
        ]
      ]
    ],
    /* Contenido HTML */
    'htm' => [
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
    ]
  ];// Imprimo
  public function doc( $Usu, $Uri ){

    /* 
    // loggin
    $eje = "ses_".( empty($Usu->ide) ? 'ini' : 'ope' );
    $this->Doc['cab']['fin'][$eje]['htm'] = $Usu->$eje();
    */

    // consola del sistema
    if( $Usu->ide == 1 ){

      $this->Doc['cab']['fin']['app_adm']['htm'] = $this->doc_adm();
    }

    // Cargo clases por aplicacion
    $this->rec['cla']["./_app/{$Uri->esq}"] = [];
    if( !empty($Uri->cab) ){ 
      // de contenido
      $this->rec['cla']["./_app/{$Uri->esq}"] []= $Uri->cab;
      // de articulo
      if( !empty($Uri->art) ){
        $this->rec['cla']["./_app/{$Uri->esq}/{$Uri->cab}"] = [ $Uri->art ];
      }
    }    

    // cargo html para operadores del documento: botones de enlaces paneles y modales
    foreach( $this->Doc['cab'] as $tip => $tip_lis ){

      foreach( $tip_lis as $ide => $ope ){
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $this->Doc['htm']['cab'][$tip] .= Fig::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $this->Doc['htm']['cab'][$tip] .= Doc::cab([ $ide => $ope ]);
          // contenido: pan / win
          $this->Doc['htm'][$ope['tip']] .= Doc::{"{$ope['tip']}"}($ide,$ope);
        }
      }  
    }

    // modal de operadores
    $this->Doc['htm']['win'] .= Doc::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);  
    
    // ajusto diseño
    $_ver = [];
    foreach( ['bar','pie'] as $ide ){ 
      if( !empty($this->Doc['htm'][$ide]) ) $_ver []= $ide; 
    }
    if( !empty($_ver) ) $this->Doc['ele']['body']['data-ver'] = implode(',',$_ver);

    // titulo
    if( !empty($this->Art->nom) ){
      $this->Doc['htm']['tit'] = $this->Art->nom;
    }
    elseif( !empty($this->Cab->nom) ){
      $this->Doc['htm']['tit'] = $this->Cab->nom;
    }
    elseif( !empty($this->Esq->nom) ){
      $this->Doc['htm']['tit'] = $this->Esq->nom; 
    }

    $doc = $this->Doc['htm'];

    ?>
    <!DOCTYPE html>
    <html lang="es">
         
      <head>
        <!-- parametros -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- hojas de estilo -->
        <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
        <?=$this->rec_cla('css')?>
        <link rel='stylesheet' href='<?=SYS_NAV?>sis/css.css'>
        <!-- aplicacion -->
        <title><?=$doc['tit']?></title>
      </head>
  
      <body <?=Ele::atr($this->Doc['ele']['body'])?>>
        
        <?php // Cabecera con Operador : botones de accesos a enlaces, paneles y modales
        if( !empty($doc['cab']['ini']) || !empty($doc['cab']['fin']) || !empty($doc['cab']['tod']) ){
          ?>
          <!-- Operador -->
          <header class='doc_cab'>
            <?php
            if( !empty($doc['cab']['tod']) ){

              echo $doc['cab']['tod'];
            }
            else{
              ?>
              <nav class='ini'>
                <?= !empty($doc['cab']['ini']) ? $doc['cab']['ini'] : "" ?>
                <?= !empty($doc['cab']['med']) ? $doc['cab']['med'] : "" ?>
              </nav>
    
              <nav class='fin'>
                <?= !empty($doc['cab']['fin']) ? $doc['cab']['fin'] : "" ?>
              </nav>
            <?php
            }
            ?>
          </header>        
          <?php
        } ?>
  
        <?php // Paneles Ocultos
        if( !empty($doc['pan']) ){ ?>
          <!-- Panel -->
          <aside class='doc_pan dis-ocu'>
            <?= $doc['pan'] ?>
          </aside>
          <?php 
        } ?>
        
        <?php // Contenido Principal
        if( !empty($doc['sec']) ){
          ?>
          <!-- Contenido -->
          <main class='doc_sec'>
            <?= Doc::sec( $doc['sec'], [ 'tit'=> empty($this->Cab) || !empty($this->Cab->tit) ? $doc['tit'] : NULL ] ) ?>
          </main>          
          <?php
        } ?>
              
        <?php // Lateral: siempre visible
        if( !empty($doc['bar']) ){ ?>
          <!-- Sidebar -->
          <aside class='doc_bar'>
            <?= $doc['bar'] ?>
          </aside>
          <?php 
        } ?>
  
        <?php // pié de página
        if( !empty($doc['pie']) ){  ?>
          <!-- pie de página -->
          <footer class='doc_pie'>
            <?= $doc['pie'] ?>
          </footer>
          <?php 
        } ?>
        
        <!-- Modales -->
        <div class='doc_win dis-ocu'>
          <?= $doc['win'] ?>
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
        <?=$this->rec_cla('jso')?>
        
        <!-- Inicio Aplicación -->
        <script>
          <?php
          global $sis_ini;
          $dat_est = [];
          foreach( $this->dat as $esq => $esq_lis ){
            $dat_est_val = [];
            foreach( $esq_lis as $est => $est_ope ){
              if( isset($this->rec['est']['api'][$esq]) ){
                if( in_array($est,$this->rec['est']['api'][$esq]) ) $dat_est_val[$est] = $est_ope;
              }elseif( isset($this->rec['dat']['api'][$esq]) ){
                if( in_array($est,$this->rec['dat']['api'][$esq]) ) $dat_est_val[$est] = [ 'dat' => $est_ope->dat ];
              }
            }
            if( !empty($dat_est_val) ) $dat_est[$esq] = $dat_est_val;
          }?>
  
          const $App = new App(<?= Obj::val_cod([ 'Uri'=>$Uri, 'dat'=>$dat_est ]) ?>);

          $App.doc();
          
          // ejecuto codigo por aplicacion
          <?= $this->rec['eje'] ?>
          
          console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
  
        </script>
  
      </body>
  
    </html>
    <?php
  }// Cargo contenido
  public function doc_ini(){

    // cargp rutas
    $Uri = $this->Uri;
    
    // ajusto inicio
    $this->Doc['cab']['ini']['app_ini']['url'] .= "/{$Uri->esq}";

    // cargo datos : esquema - cabecera - articulo - valor
    $this->Esq = Dat::get('app_esq',[ 'ver'=>"`ide`='{$Uri->esq}'", 'opc'=>'uni' ]);

    if( !empty($this->Esq->key) ){

      $esq_key = $this->Esq->key;
    
      if( !empty($Uri->cab) ){
        
        // cargo datos del menu
        $this->Cab = Dat::get('app_cab',[ 
          'ver'=>"`esq`='{$esq_key}' AND `ide`='{$Uri->cab}'", 
          'ele'=>'ope', 'opc'=>'uni'
        ]);
  
        $cab_key = $this->Cab->key;
        // cargo datos del artículo
        if( !empty($Uri->art) ){
          $this->Art = Dat::get('app_art',[ 
            'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `ide`='{$Uri->art}'", 
            'ele'=>'ope', 'opc'=>'uni' 
          ]);
  
          $art_key = $this->Art->key;
          // cargo índice de contenidos
          if( !empty($this->Cab->nav) ){
                
            $this->Nav = Dat::get('app_nav',[ 
              'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `art`='{$art_key}'", 
              'ord'=>"`key` ASC", 
              'nav'=>'key'
            ]);
            
            // pido listado por navegacion
            if( !empty( $this->Nav[1]) ){ 

              $this->Doc['cab']['ini']['app_nav']['htm'] = Lis::nav( $this->Nav ) ;
            }
          }          
        }
      }
  
      // cargo menú principal
      $this->Doc['cab']['ini']['app_cab']['htm'] = $this->cab();   
    }

    // cargo elemento principal
    $this->Doc['ele']['body'] = [
      'data-doc'=>$Uri->esq, 
      'data-cab'=>!!$Uri->cab ? $Uri->cab : NULL, 
      'data-art'=>!!$Uri->art ? $Uri->art : NULL 
    ];

  }// Consola
  public function doc_adm(){

    $_eje = "App.doc_adm";    
    $_ope = [
      'aja' => [ 'nom'=>"AJAX",   'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('aja',this);" ] ],
      'ico' => [ 'nom'=>"Íconos", 'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('ico',this);" ] ],
      'php' => [ 'nom'=>"P.H.P.", 'htm'=>"" ]
    ];
  
    // datos por ajax
      ob_start();
      ?>
        <nav class='lis'>
  
        </nav>
      <?php
      $_ope['aja']['htm'] = ob_get_clean();
  
    // iconos del sistema
      ob_start();
      ?>
        <?=Dat::var('val','ver',['nom'=>"Filtrar",'ope'=>[ 
          'tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"$_eje('ico',this,'ver')"  
        ]])?>
  
        <ul class='lis ite mar-2'>
        </ul>
      <?php
      $_ope['ico']['htm'] = ob_get_clean();
  
    // php: ejecucion
      ob_start();
      ?>
        <fieldset class='doc_inf dir-hor pad-3'>
          <legend>Ejecutar en PHP</legend>
  
          <?=Dat::var('val','ide',[ 'ope'=>[ 'tip'=>"tex_ora" ] ])?>
          
          <?=Dat::var('val','par',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
            'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
          ])?>
  
          <?=Dat::var('val','htm',[
            'nom'=>"¿HTML?",
            'ope'=>[ 'tip'=>"num_bin", 'val'=>1, 'id'=>"_adm-php-htm" ]
          ])?>
          
          <?=Fig::ico('dat_ope',[
            'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('php',this)"
          ])?>
  
        </fieldset>
  
        <div class='ope_res mar-1'></div>
  
        <pre class='ope_res'></pre>
  
      <?php
      $_ope['php']['htm'] = ob_get_clean();
    //

    return Doc::nav('bar', $_ope, [ 'sel' => "php", 'ite' => [ 'eti'=>"form" ] ]);

  }// ejecucion
  public function doc_log() : string {
    
    $_ = "<h2>hola desde php<c>!</c></h2>";

  
    /* Recorrer tablas de un esquema:

    foreach( api_sql::est(DAT_ESQ,'lis','hol_','tab') as $est ){
      $_ .= "ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>";
    } 
    */
    
    /*  Invocando funciones
      include("./_/hol/sel.php");
      $_ = hol_sel_par_gui();
    */
    
    return $_;
  }

  // Esquema : nombre de la aplicacion
  public object $Esq;

  // Menu : titulo + descripcion + listado > item = [icono] + enlace
  public object $Cab;
  // genero listado
  public function cab( array $ele = [] ) : string {
    
    global $Usu;
    
    $esq_key = $this->Esq->key;
    $esq_ide = $this->Esq->ide;

    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $_lis = [];
    foreach( Dat::get('app_cab',[ 'ver'=>"`esq`='$esq_key'", 'ord'=>"`key` ASC" ]) as $_cab ){

      if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($Usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? Fig::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $_lis_val = [];
      foreach( Dat::get('app_art',[ 'ver'=>"`esq`='$esq_key' AND `cab`='$_cab->key'", 'ord'=>"`key` ASC" ]) as $_art ){

        $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

        if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

        // por titulo de separacion
        if( empty($_art->ide) ){
          $ite_val = "
          <p class='tex-tit'>".Tex::let($_art->nom)."</p>";
        }
        // por enlace
        else{
          $ele_val['href'] = SYS_NAV."/$esq_ide/$_cab->ide/$_art->ide";
          $ite_val = "
          <p>"
          .( !empty($_art->ico) ? Fig::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
          .Tex::let($_art->nom)."
          </p>";
        }       

        $_lis_val []= "
        <a".Ele::atr($ele_val).">
          {$ite_val}
        </a>";
      }
      $_lis []= [ 
        'ite'=>[ 'eti'=>"p", 'class'=>"ide-$_cab->ide mar_ver-1 tex-tit tex-enf", 'htm'=>$ite_ico.Tex::let($_cab->nom) ],
        'lis'=>$_lis_val 
      ];
    }
    // reinicio opciones
    Ele::cla($ele['lis'],"nav");
    Ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'
    return Lis::dep($_lis,$ele);
  }
  
  // Articulo : + ...secciones + pie de página
  public object $Art;
  // genero desde objeto de la base
  public function art( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = Ele::htm($nav->ope);

    $_art = Dat::get('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

    $_ = "
    <article class='app_art'>";
      // introduccion
      if( !empty($agr['htm_ini']) ){
        $_ .= $agr['htm_ini'];
      }
      else{ $_ .= "
        <h2>{$nav->nom}</h2>";
      }
      // listado de contenidos
      if( !empty($_art) ){ $_ .= "

        <nav class='lis'>";
          foreach( $_art as $art ){
            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".Tex::let($art->nom)."</a>";
            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='doc_val nav'>
                ".Doc::val_ico()."
                {$art_url}
              </div>
              <div class='doc_dat'>
                ".Ele::val($art->ope['tex'])."
              </div>
              ";
            }else{
              $_ .= $art_url;
            }
            
          }$_.="
        </nav>";
      }
      // pie de pagina
      if( !empty($agr['htm_fin']) ){
        $_ .= $agr['htm_fin'];
      }
      $_ .= "
    </article>";          

    return $_;
  }// genero articulos por Indice de la base : article > h2 + ...section > h3 + ...section > ...
  public function art_nav( string $ide ) : string {
    $_ = "";
    
    $_ide = explode('.',$ide);
    
    $app_nav = Dat::get('app_nav',[ 'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 'nav'=>'pos' ]);

    if( isset($app_nav[1]) ){

      foreach( $app_nav[1] as $nv1 => $_nv1 ){ $_ .= "
        <h2 id='_{$nv1}-'>".Tex::let($_nv1->nom)."</h2>
        <article>";
          if( isset($app_nav[2][$nv1]) ){
            foreach( $app_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "

          <h3 id='_{$nv1}-{$nv2}-'>".Tex::let($_nv2->nom)."</h3>
          <section>";
            if( isset($app_nav[3][$nv1][$nv2]) ){
              foreach( $app_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "

            <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>".Tex::let($_nv3->nom)."</h4>
            <section>";
              if( isset($app_nav[4][$nv1][$nv2][$nv3]) ){
                foreach( $app_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "

              <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>".Tex::let($_nv4->nom)."</h5>
              <section>";
                if( isset($app_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                  foreach( $app_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "

                <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>".Tex::let($_nv5->nom)."</h6>
                <section>                      

                </section>";
                  }
                }$_ .= "                  
              </section>";
                }
              }$_ .= "                
            </section>";
              }
            }$_ .= "              
          </section>";
            }
          }$_ .= "              
        </article>";
      }
    }

    return $_;
  }
  
  /* Indice */
  public array $Nav;

  
  // Glosario : palabras por esquema
  static function ide( int $esq, string $ide, array $ele = [] ) : string {

    $_ = [];
    
    if( is_array( $tex = Dat::get('app_ide',['ver'=>"`esq`=$esq AND `art`='$ide'"]) ) ){

      foreach( $tex as $pal ){
        $_[ $pal->nom ] = $pal->des;
      }
    }

    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return Lis::pos($_,$ele);
  }
}