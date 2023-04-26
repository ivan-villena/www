<?php

// Página-app
class App {

  /* Estructuras */
  public array $dat = [];

  public array $var = [];

  /* Recursos */
  public array $rec = [
    // peticion
    'uri' => [
    ],
    // Datos de la aplicacion
    'app' => [
    ],    
    // archivos clase js/php
    'cla' => [
      'api'=>[
        'dat', 'arc', 'eje', 'ele', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol', 'usu', 'doc', 'app'
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
    // Ejecucion por aplicacion
    'eje' => ""
    ,
    // Elementos del documento
    'ele' => [
    ],    
    // operadores: botones de la cabecera
    'ope' => [
      'ini'=>[
        'app_ini'=>[ 'ico'=>"app",     'url'=>SYS_NAV,  'nom'=>"Página de Inicio" ],
        'app_cab'=>[ 'ico'=>"app_cab", 'tip'=>"pan",    'nom'=>"Menú Principal" ],
        'app_nav'=>[ 'ico'=>"app_nav", 'tip'=>"pan",    'nom'=>"Índice", 
          'htm'=>"" // no se imprime
        ]
      ],
      'med'=>[
        'app_dat'=>[ 'ico'=>"dat_des", 'tip'=>"win",    'nom'=>"Ayuda" ]
      ],
      'fin'=>[
        'ses_ini'=>[ 'ico'=>"app_ini", 'tip'=>"win",    'nom'=>"Iniciar Sesión..."  ],
        'ses_ope'=>[ 'ico'=>"usu",     'tip'=>"win",    'nom'=>"Cuenta de Usuario..." ],
        'app_adm'=>[ 'ico'=>"eje",     'tip'=>"win",    'nom'=>"Consola del Sistema..." ]        
      ]
    ],
    // Contenido de la Página
    'htm' => [
      // titulo
      'tit'=>"{-_-}",
      // botones
      'ope'=>[ 'ini'=>"", 'med'=>"", 'fin'=>"" ],
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
  ];// - Proceso Petición
  public function rec_uri( string $uri ) : object {

    // armo peticion
    $uri = explode('/',$uri);

    $this->rec['uri'] = new stdClass;
    $this->rec['uri']->esq = $uri[0];
    $this->rec['uri']->cab = !empty($uri[1]) ? $uri[1] : FALSE;
    $this->rec['uri']->val = FALSE;

    if( $this->rec['uri']->art = !empty($uri[2]) ? $uri[2] : FALSE ){
      $_val = explode('#',$this->rec['uri']->art);
      if( isset($_val[1]) ){
        $this->rec['uri']->art = $_val[0];
        $this->rec['uri']->val = $_val[1];
      }
      elseif( !empty($uri[3]) ){
        $this->rec['uri']->val = $uri[3];
      }
    }

    $_uri = $this->rec['uri'];
    
    // ajusto inicio
    $this->rec['ope']['ini']['app_ini']['url'] .= "/{$_uri->esq}";

    // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
    $this->rec['app']['esq'] = Dat::get('app_esq',[ 'ver'=>"`ide`='{$_uri->esq}'", 'opc'=>'uni' ]);

    if( !empty($this->rec['app']['esq']->key) ){

      $esq_key = $this->rec['app']['esq']->key;
    
      if( !empty($_uri->cab) ){
        
        // cargo datos del menu
        $this->rec['app']['cab'] = Dat::get('app_cab',[ 'ver'=>"`esq`='{$esq_key}' AND `ide`='{$_uri->cab}'", 
          'ele'=>'ope', 'opc'=>'uni'
        ]);
  
        $cab_key = $this->rec['app']['cab']->key;
        // cargo datos del artículo
        if( !empty($_uri->art) ){
          $this->rec['app']['art'] = Dat::get('app_art',[ 'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `ide`='{$_uri->art}'", 
            'ele'=>'ope', 'opc'=>'uni' 
          ]);
  
          $art_key = $this->rec['app']['art']->key;
          // cargo índice de contenidos
          if( !empty($this->rec['app']['cab']->nav) ){
  
            $this->rec['app']['nav'] = Dat::get('app_nav',[ 'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `art`='{$art_key}'", 
              'ord'=>"key ASC", 
              'nav'=>'key'
            ]);
  
            // pido listado por navegacion
            if( !empty($this->rec['app']['nav'][1]) ) $this->rec['ope']['ini']['app_nav']['htm'] = Lis::nav($this->rec['app']['nav']);
          }          
        }
      }
  
      // ajusto enlace de inicio
      $this->rec['ope']['ini']['app_ini']['url'] .= $_uri->esq;
  
      // cargo menú principal
      $this->rec['ope']['ini']['app_cab']['htm'] = $this->cab();   
    }

    // cargo elemento principal
    $this->rec['ele']['body'] = [
      'data-doc'=>$_uri->esq, 
      'data-cab'=>!!$_uri->cab ? $_uri->cab : NULL, 
      'data-art'=>!!$_uri->art ? $_uri->art : NULL 
    ];   

    return $_uri;

  }//- Clases del programa
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
  }// - Armo directorios
  public function rec_dir( object $uri = NULL ) : object {

    if( !isset($uri) ) $uri = $this->rec['uri'];

    $_ = new stdClass();
    
    $_->esq = SYS_NAV."{$uri->esq}";
      
    $_->cab = "{$uri->esq}/{$uri->cab}";

    $_->ima = SYS_NAV."img/{$_->cab}/";

    if( !empty($uri->art) ){

      $_->art = $_->cab."/{$uri->art}";
    
      $_->ima .= "{$uri->art}/";
    }

    return $_;
  }

  /* Pagina */
  public function doc( $Usu, $Uri ){

    /* 
    // loggin
    $eje = "ses_".( empty($Usu->ide) ? 'ini' : 'ope' );
    $this->rec['ope']['fin'][$eje]['htm'] = $Usu->$eje();
    */

    // consola del sistema
    if( $Usu->ide == 1 ){

      $this->rec['ope']['fin']['app_adm']['htm'] = $this->doc_adm();
    }

    // cargo operadores del documento ( botones + contenidos )
    foreach( $this->rec['ope'] as $tip => $tip_lis ){

      foreach( $tip_lis as $ide => $ope ){
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $this->rec['htm']['ope'][$tip] .= Fig::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $this->rec['htm']['ope'][$tip] .= Doc::bot([ $ide => $ope ]);
          // contenido
          $this->rec['htm'][$ope['tip']] .= Doc::{$ope['tip']}($ide,$ope);
        }
      }  
    }

    // modal de operadores
    $this->rec['htm']['win'] .= Doc::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);  
    
    // ajusto diseño
    $_ver = [];
    foreach( ['bar','pie'] as $ide ){ 
      if( !empty($this->rec['htm'][$ide]) ) $_ver []= $ide; 
    }
    if( !empty($_ver) ) $this->rec['ele']['body']['data-ver'] = implode(',',$_ver);

    // titulo
    if( !empty($this->rec['app']['art']->nom) ){
      $this->rec['htm']['tit'] = $this->rec['app']['art']->nom;
    }
    elseif( !empty($this->rec['app']['cab']->nom) ){
      $this->rec['htm']['tit'] = $this->rec['app']['cab']->nom;
    }
    elseif( !empty($this->rec['app']['esq']->nom) ){
      $this->rec['htm']['tit'] = $this->rec['app']['esq']->nom; 
    }

    $htm = $this->rec['htm'];

    ?>
    <!DOCTYPE html>
    <html lang="es">
         
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title><?=$htm['tit']?></title>
  
        <!-- hojas de estilo -->
        <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
        <?=$this->rec_cla('css')?>
        <link rel='stylesheet' href='<?=SYS_NAV?>_/css.css'>      
      </head>
  
      <body <?=Ele::atr($this->rec['ele']['body'])?>>
        
        <?php // Operador
        if( !empty($htm['ope']['ini']) || !empty($htm['ope']['fin']) ){
          ?>
          <!-- Operador -->
          <header class='doc_bot'>
            
            <nav class='ini'>
              <?= $htm['ope']['ini'] ?>
              <?= $htm['ope']['med'] ?>
            </nav>
  
            <nav class='fin'>
              <?= $htm['ope']['fin'] ?>
            </nav>
            
          </header>        
          <?php
        } ?>
  
        <?php // Paneles Ocultos
        if( !empty($htm['pan']) ){ ?>
          <!-- Panel -->
          <aside class='doc_pan dis-ocu'>
            <?= $htm['pan'] ?>
          </aside>
          <?php 
        } ?>
        
        <?php // Contenido Principal
        if( !empty($htm['sec']) ){
          ?>
          <!-- Contenido -->
          <main class='doc_sec'>
            <?= Doc::sec( $htm['sec'], [ 'tit'=>$htm['tit'] ] ) ?>
          </main>          
          <?php
        } ?>
              
        <?php // Lateral: siempre visible
        if( !empty($htm['bar']) ){ ?>
          <!-- Sidebar -->
          <aside class='doc_bar'>
            <?= $htm['bar'] ?>
          </aside>
          <?php 
        } ?>
  
        <?php // pié de página
        if( !empty($htm['pie']) ){  ?>
          <!-- pie de página -->
          <footer class='doc_pie'>
            <?= $htm['pie'] ?>
          </footer>
          <?php 
        } ?>
        
        <!-- Modales -->
        <div class='doc_win dis-ocu'>
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
  
          const $App = new App(<?= Obj::val_cod([ 'rec'=>[ 'uri'=>$Uri ], 'dat'=>$dat_est ]) ?>);
          
          // ejecuto codigo por aplicacion
          <?= $this->rec['eje'] ?>
          
          console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
  
        </script>
  
      </body>
  
    </html>
    <?php
  }// Consola
  public function doc_adm(){

    $_eje = "App.doc_adm";    
    $_ope = [
      'aja' => [ 'nom'=>"AJAX",   'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('aja',this);" ] ],
      'ico' => [ 'nom'=>"Íconos", 'htm'=>"", 'nav'=>[ 'onclick'=>"$_eje('ico',this);" ] ],
      'jso' => [ 'nom'=>"J.S.",   'htm'=>"" ],  
      'php' => [ 'nom'=>"P.H.P.", 'htm'=>"" ],
      'sql' => [ 'nom'=>"S.Q.L",  'htm'=>"" ],
      'htm' => [ 'nom'=>"D.O.M.", 'htm'=>"" ] 
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
        <?=Doc::var('val','ver',['nom'=>"Filtrar",'ope'=>[ 
          'tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"$_eje('ico',this,'ver')"  
        ]])?>
  
        <ul class='lis ite mar-2'>
        </ul>
      <?php
      $_ope['ico']['htm'] = ob_get_clean();
  
    // javascript: consola
      ob_start();
      ?>
        <fieldset class='doc_inf pad-3'>
          <legend>Ejecutar JavaScript</legend>      
  
          <?=Doc::var('val','cod',[ 
            'ite'=>[ 'class'=>"tam-cre" ], 
            'ope'=>[ 'tip'=>"tex_par", 'rows'=>"10", 'class'=>"anc-100", 'oninput'=>"$_eje('jso',this)" ] 
          ])?>
  
        </fieldset>
  
        <div class='ope_res mar-1'>
        </div>  
      <?php
      $_ope['jso']['htm'] = ob_get_clean();
  
    // php: ejecucion
      ob_start();
      ?>
        <fieldset class='doc_inf dir-hor pad-3'>
          <legend>Ejecutar en PHP</legend>
  
          <?=Doc::var('val','ide',[ 'ope'=>[ 'tip'=>"tex_ora" ] ])?>
          
          <?=Doc::var('val','par',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
            'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
          ])?>
  
          <?=Doc::var('val','htm',[
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
  
    // sql: consultas
      ob_start();
      ?>
        <fieldset class='doc_inf dir-hor pad-3'>
          <legend>Ejecutar S.Q.L.</legend>
  
          <?=Doc::var('val','cod',[ 
            'ite'=>[ 'class'=>"tam-cre" ], 
            'ope'=>[ 'tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1" ],
            'htm_fin'=> Fig::ico('dat_ope',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('sql',this,'cod')" ])
          ])?>
  
        </fieldset>
  
        <div class='lis est ope_res mar-1'></div>
      <?php
      $_ope['sql']['htm'] = ob_get_clean();
  
    // html: consultas dom
      ob_start();
      ?>
        <fieldset class='doc_inf dir-hor pad-3'>
          <legend>Ejecutar Selector</legend>
  
          <?=Doc::var('val','cod',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1"],
            'htm_fin'=> Fig::ico('dat_ope',['eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('htm',this,'cod')"])
          ])?>
  
        </fieldset>
  
        <div class='ele'></div>
  
        <div class='ele_nod mar-1'></div>
  
      <?php
      $_ope['htm']['htm'] = ob_get_clean();
  
    //
    return Doc::nav('bar', $_ope, [ 'sel' => "php", 'ite' => [ 'eti'=>"form" ] ]);
  }

  // Menu : titulo + descripcion + listado > item = [icono] + enlace
  public function cab( array $ele = [] ) : string {
    
    global $Usu;
    
    $esq_key = $this->rec['app']['esq']->key;
    $esq_ide = $this->rec['app']['esq']->ide;

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

        $ele_val['href'] = SYS_NAV."/$esq_ide/$_cab->ide/$_art->ide";

        $_lis_val []= "
        <a".Ele::atr($ele_val).">
          <p>"
          .( !empty($_art->ico) ? Fig::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
          .Tex::let($_art->nom)."
          </p>
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
  }
  // Indices por artiuclo : article > h2 + ...section > h3 + ...section > ...
  public function nav( string $ide ) : string {
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
}