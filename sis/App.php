<?php

// Página-app
class App {

  static string $IDE = "App-";
  static string $EJE = "\$App.";

  /* Estructuras */
  public array $Dat = []; 

  /* Sesión */
  public array $Ses = [
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
  public function ses_cla( string $tip = "", array $dat = [] ) : string {
    $_ = "";
    if( empty($dat) ) $dat = $this->Ses['cla'];
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

  /* peticion */
  public object $Uri;
  // - Armo objeto
  public function uri( string $ide ) : object {

    // armo peticion
    $dir = explode('/',$ide);

    $Uri = new stdClass;

    $Uri->esq = $dir[0];
    $Uri->cab = !empty($dir[1]) ? $dir[1] : FALSE;
    $Uri->val = FALSE;

    if( $Uri->art = !empty($dir[2]) ? $dir[2] : FALSE ){

      $val = explode('#',$Uri->art);

      if( isset($val[1]) ){
        $Uri->art = $val[0];
        $Uri->val = $val[1];
      }
      elseif( !empty($dir[3]) ){
        $Uri->val = $dir[3];
      }
    }

    return $this->Uri = $Uri;

  }

  /* directorios */
  public object $Dir;  
  // - Armo objeto
  public function dir() : object {

    $Uri = $this->Uri;

    $Dir = new stdClass();
    
    $Dir->esq = SYS_NAV."{$Uri->esq}";
      
    $Dir->cab = "{$Uri->esq}/{$Uri->cab}";

    $Dir->ima = SYS_NAV."_img/{$Dir->cab}/";

    if( !empty($Uri->art) ){

      $Dir->art = $Dir->cab."/{$Uri->art}";
    
      $Dir->ima .= "{$Uri->art}/";
    }

    return $this->Dir = $Dir;
  }

  // Esquema : nombre de la aplicacion
  public object $Esq;

  // Menu 
  public object $Cab;
  // Imprimo listado
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
  
  // Articulo
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
  }// genero secciones por Indice de la base : article > h2 + ...section > h3 + ...section > ...
  public function art_sec( string $ide ) : string {
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
  
  /* Indice por aplicacion */
  public array $Nav;

  /* Glosario : palabras por esquema */
  static function ide( int $esq, string $art, array $ele = [] ) : string {

    $_ = [];
    
    if( is_array( $tex = Dat::get('app_ide',['ver'=>"`esq`=$esq AND `art`='$art'"]) ) ){

      foreach( $tex as $pal ){
        $_[ $pal->nom ] = $pal->des;
      }
    }

    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return Lis::pos($_,$ele);
  }

  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
        'ses_usu'=>[ 
          'ico'=>"usu",     'tip'=>"pan",    'nom'=>"Cuenta de Usuario..." 
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
  ];// - Imprimo
  public function doc( Usu $Usu ){

    // cargo rutas
    $Uri = $this->Uri;

    // Cargo clases : por aplicacion
    $this->Ses['cla']["./_app/{$Uri->esq}"] = [];
    if( !empty($Uri->cab) ){ 
      // de contenido
      $this->Ses['cla']["./_app/{$Uri->esq}"] []= $Uri->cab;
      // de articulo
      if( !empty($Uri->art) ){
        $this->Ses['cla']["./_app/{$Uri->esq}/{$Uri->cab}"] = [ $Uri->art ];
      }
    }

    // cargo elemento principal
    $this->Doc['ele']['body'] = [
      'data-doc'=>$Uri->esq, 
      'data-cab'=>!!$Uri->cab ? $Uri->cab : NULL, 
      'data-art'=>!!$Uri->art ? $Uri->art : NULL 
    ];    

    // imprimo sesion del usuario
    if( empty($Usu->ide) ){

      $this->Doc['htm']['win'] .= Doc::win('app-ses_ini', [
        'ico'=>"app_ini",
        'nom'=>"Iniciar Sesión...",
        'htm'=>$this->usu_ses()
      ]);
    }// imprimo menu de usuario por aplicacion
    else{

      $this->Doc['cab']['fin']['ses_usu']['htm'] = $this->usu();

      // imprimo consola del sistema
      if( $Usu->ide == 1 ){

        $this->Doc['cab']['fin']['app_adm']['htm'] = $this->adm();
      }      
    }

    // ajusto inicio
    $this->Doc['cab']['ini']['app_ini']['url'] .= "/{$Uri->esq}";    

    // imprimo menú principal
    $this->Doc['cab']['ini']['app_cab']['htm'] = $this->cab();

    // imprimo indice
    if( !empty( $this->Nav ) ){
      // proceso nivelacion de indices
      $this->Doc['cab']['ini']['app_nav']['htm'] = Lis::nav( $this->Nav );
    }    

    // cargo operadores del documento: botones y html de enlaces paneles y modales
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
          // html: pan / win
          $this->Doc['htm'][$ope['tip']] .= Doc::{"{$ope['tip']}"}($ide,$ope);
        }
      }  
    }

    // modal de operadores
    $this->Doc['htm']['win'] .= Doc::win('app_ope',[ 
      'ico'=>"app_ope", 
      'nom'=>"Operador" 
    ]);
    
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

    $Doc = $this->Doc['htm'];

    ?>
    <!DOCTYPE html>
    <html lang="es">
          
      <head>
        <!-- parametros -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- hojas de estilo -->
        <link rel='stylesheet' href='<?=SYS_NAV?>index.css'>
        <?=$this->ses_cla('css')?>
        <link rel='stylesheet' href='<?=SYS_NAV?>sis/css.css'>
        <!-- aplicacion -->
        <title><?=$Doc['tit']?></title>
      </head>
  
      <body <?=Ele::atr($this->Doc['ele']['body'])?>>
        
        <?php // Cabecera con Operador : botones de accesos a enlaces, paneles y modales
        if( !empty($Doc['cab']['ini']) || !empty($Doc['cab']['fin']) || !empty($Doc['cab']['tod']) ){
          ?>
          <!-- Operador -->
          <header class='doc_cab'>
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
          <aside class='doc_pan dis-ocu'>
            <?= $Doc['pan'] ?>
          </aside>
          <?php 
        } ?>
        
        <?php // Contenido Principal
        if( !empty($Doc['sec']) ){
          ?>
          <!-- Contenido -->
          <main class='doc_sec'>
            <?= Doc::sec( $Doc['sec'] ) ?>
          </main>          
          <?php
        } ?>
              
        <?php // Lateral: siempre visible
        if( !empty($Doc['bar']) ){ ?>
          <!-- Sidebar -->
          <aside class='doc_bar'>
            <?= $Doc['bar'] ?>
          </aside>
          <?php 
        } ?>
  
        <?php // pié de página
        if( !empty($Doc['pie']) ){  ?>
          <!-- pie de página -->
          <footer class='doc_pie'>
            <?= $Doc['pie'] ?>
          </footer>
          <?php 
        } ?>
        
        <!-- Modales -->
        <div class='doc_win dis-ocu'>
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
        <?=$this->ses_cla('jso')?>
        
        <!-- Inicio Aplicación -->
        <script>
          <?php          
          $dat_est = [];
          foreach( $this->Dat as $esq => $esq_lis ){
            $dat_est_val = [];
            foreach( $esq_lis as $est => $est_ope ){
              if( isset($this->Ses['est']['api'][$esq]) ){
                if( in_array($est,$this->Ses['est']['api'][$esq]) ) $dat_est_val[$est] = $est_ope;
              }elseif( isset($this->Ses['dat']['api'][$esq]) ){
                if( in_array($est,$this->Ses['dat']['api'][$esq]) ) $dat_est_val[$est] = [ 'dat' => $est_ope->dat ];
              }
            }
            if( !empty($dat_est_val) ) $dat_est[$esq] = $dat_est_val;
          }
          global $sis_ini;
          ?>
  
          const $App = new App(<?= Obj::val_cod([ 'Uri'=>$Uri, 'Dat'=>$dat_est ]) ?>);

          $App.doc();
          
          // ejecuto codigo por aplicacion
          <?= $this->Ses['eje'] ?>
          
          console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);
  
        </script>
  
      </body>
  
    </html>
    <?php
  }// - Cargo contenido
  public function doc_ini(){

    // cargp rutas
    $Uri = $this->Uri;

    // cargo datos : esquema - cabecera - articulo - valor
    $this->Esq = Dat::get('app_esq',[ 'ver'=>"`ide`='{$Uri->esq}'", 'opc'=>'uni' ]);

    if( !empty($this->Esq->key) ){

      $esq_key = $this->Esq->key;
    
      if( !empty($Uri->cab) ){
        
        // cargo datos del menu
        $this->Cab = Dat::get('app_cab',[ 
          'ver'=>"`esq`='{$esq_key}' AND `ide`='{$Uri->cab}'", 
          'ele'=>'ope', 
          'opc'=>'uni'
        ]);
  
        $cab_key = $this->Cab->key;
        // cargo datos del artículo
        if( !empty($Uri->art) ){
          $this->Art = Dat::get('app_art',[ 
            'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `ide`='{$Uri->art}'", 
            'ele'=>'ope', 
            'opc'=>'uni' 
          ]);
  
          $art_key = $this->Art->key;
          // cargo índice de contenidos
          if( !empty($this->Cab->nav) ){
                
            $this->Nav = Dat::get('app_nav',[ 
              'ver'=>"`esq`='{$esq_key}' AND `cab`='{$cab_key}' AND `art`='{$art_key}'", 
              'ord'=>"`key` ASC",
              'nav'=>'key'
            ]);
          }          
        }
      }
    }
  }

  /* Consola */
  public function adm(){

    $_eje = self::$EJE."adm";

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

  }// Ejecuto desde la consola
  public function adm_log() : string {
    
    $_ = "<h2>hola desde php<c>!</c></h2>";

    foreach( sql::est('nom','hol_uni_','tab') as $est ){

      $_ .= "RENAME TABLE `$est` TO `".str_replace('uni_','',$est)."`;<br>";

    } 

  
    /* Recorrer tablas de un esquema:

    foreach( sql::est('nom','hol_uni','tab') as $est ){

      $_ .= "ALTER TABLE `api`.`$est` DROP PRIMARY KEY;<br>";

    } 
    */
    
    /*  Invocando funciones 
      include("./_sql/hol/sel.php");
      $_ = hol_sel_par_gui();
    */
    
    return $_;
  }

  /* Menu del usuario */
  public array $Usu;
  // - imprimo menu con accesos
  public function usu() : string {
    $_ = "";

    // busco opciones del menu para el usuario por aplicacion    
    $lis = "";
    if( !empty( $app_usu = Dat::get('app_usu',[ 'ver'=>"`esq`='{$this->Esq->key}'" ]) ) ){      

      foreach( $app_usu as $usu ){

        // procesar imagen en vez de icono
        $ite_ico = !empty($usu->ico) ? Fig::ico( $usu->ico, [ 'class'=>"mar_der-1" ] ) : "";

        // enlace: esquema/usuario/identificador
        $_lis []= [
          'ite'=>[ 'eti'=>"a",  'class'=>"mar_ver-1 tex-tit tex-enf", 'htm'=>$ite_ico.Tex::let($usu->nom) ],
        ];
      }
      // pido listado html
      $ele = [ 
        'lis'=>[]
       ];
      Ele::cla($ele['lis'],"nav");
      $lis = Lis::dep($_lis,$ele);
    }
    $_ .= "
    <nav class='doc_lis'>

      $lis
      
      ".Doc::bot('tex',[
        ["App.ses_usu()","Administrar Perfil"],
        ["App.ses_fin()","Cerrar Sesión"]
      ])."

    </nav>";

    return $_;

  }// - Imprimo Inicio de Sesión
  public function usu_ses() : string {

    $_eje = self::$EJE."usu";

    $_ = "
    <form class='app_dat' onsubmit='{$_eje}_ini'>

      <fieldset class='dat_var'>
        <input id='app-ses_ini-mai' name='mai' type='email' placeholder='Ingresa tu Email...'>
      </fieldset>

      <fieldset class='dat_var'>
        <input id='app-ses_ini-pas' name='pas' type='password' placeholder='Ingresa tu Password...'>
      </fieldset>

      <fieldset class='dat_var'>
        <label>Mantener Sesión Activa en este Equipo:</label>
        <input id='app-ses_ini-val' name='val' type='checkbox'>
      </fieldset>

      <a href=''>¿Olvidaste la contraseña?</a>

      <fieldset class='doc_bot tex'>
        <button type='submit'>Ingresar</button>
      </fieldset>

    </form>";

    return $_;
  }// - proceso inicio de sesion 
  public function usu_ini( string $mai, string $pas ) : string {

    $_ = "";

    if( isset($_REQUEST['ema']) && isset($_REQUEST['pas']) ){
        
      $Usu = new Usu( $_REQUEST['ema'] );

      if( isset($Usu->pas) ){
        if( $Usu->pas == $_REQUEST['pas'] ){
          $_SESSION['usu'] = $_REQUEST['ide'];
        }
        else{
          $_ = "Password Incorrecto";
        }
      }
      else{
        $_ = "Usuario Inexistente";
      }
    }

    return $_;
  }// - finaliza la sesion
  public function usu_fin() : void {

    // elimino datos de la sesion
    session_destroy();

    // reinicio 
    session_start();

  }// - reiniciar contraseña
  public function usu_pas() : void {
  }// - datos del usuario
  public function usu_dat() : void {
  }
}