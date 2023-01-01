<?php
// Página-app
class api_app {

  // inicializo aplicacion
  function __construct( string $uri, string $ini = "hol" ){

    // Recursos : modulos del sistema
    global $sis_cla;
    $this->rec['cla'] = $sis_cla;

    // armo peticion
    $uri = explode('/',$uri);
    $this->uri = new stdClass;
    $this->uri->esq = !empty($uri[0]) ? $uri[0] : $ini;
    $this->uri->cab = !empty($uri[1]) ? $uri[1] : FALSE;
    $this->uri->val = FALSE;
    if( $this->uri->art = !empty($uri[2]) ? $uri[2] : FALSE ){
      $_val = explode('#',$this->uri->art);
      if( isset($_val[1]) ){
        $this->uri->art = $_val[0];
        $this->uri->val = $_val[1];  
      }
      elseif( !empty($uri[3]) ){
        $this->uri->val = $uri[3];
      }
    }

    // ajusto enlace de inicio
    $this->rec['ope']['ini']['app_ini']['url'] .= $this->uri->esq;

    // cargo menú principal
    $this->rec['ope']['ini']['app_cab']['htm'] = $this->cab();

    // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
    $this->rec['ele']['body'] = [
      'data-doc'=>$this->uri->esq, 
      'data-cab'=>!!$this->uri->cab ? $this->uri->cab : NULL, 
      'data-art'=>!!$this->uri->art ? $this->uri->art : NULL 
    ];
    $this->rec['dat']['esq'] = api_dat::get('app_esq',[ 'ver'=>"`ide`='{$this->uri->esq}'", 'opc'=>'uni' ]);

    if( !empty($this->uri->cab) ){
      
      // cargo datos del menu
      $this->rec['dat']['cab'] = api_dat::get('app_cab',[ 'ver'=>"`esq`='{$this->uri->esq}' AND `ide`='{$this->uri->cab}'", 
        'ele'=>'ope', 'opc'=>'uni'
      ]);

      // cargo datos del artículo
      if( !empty($this->uri->art) ){
        $this->rec['dat']['art'] = api_dat::get('app_art',[ 'ver'=>"`esq`='{$this->uri->esq}' AND `cab`='{$this->uri->cab}' AND `ide`='{$this->uri->art}'", 
          'ele'=>'ope', 'opc'=>'uni' 
        ]);

        // cargo índice de contenidos
        if( !empty($this->rec['dat']['cab']->nav) ){

          $this->rec['dat']['nav'] = api_dat::get('app_nav',[ 'ver'=>"`esq`='{$this->uri->esq}' AND `cab`='{$this->uri->cab}' AND `ide`='{$this->uri->art}'", 
            'ord'=>"pos ASC", 
            'nav'=>'pos'
          ]);

          // pido listado por navegacion
          if( !empty($this->rec['dat']['nav'][1]) ) $this->rec['ope']['ini']['app_nav']['htm'] = api_lis::nav($this->rec['dat']['nav']);
        }          
      }
    }
  }

  // contenido
  public array $htm = [
    // titulo
    'tit'=>"{-_-}",
    // botones
    'ope'=>[ 'ini'=>"", 'fin'=>"" ],
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
  ];// inicializo pagina
  public function htm_ini() : void {

    global $sis_usu;

    /* 
    // loggin
    $tip = "ses_".( empty($sis_usu->ide) ? 'ini' : 'ope' );
    $this->rec['ope']['fin'][$tip]['htm'] = api_usu::$tip();

    // consola del sistema
    $this->rec['cla']['sis'] []= "adm";
    ob_start();
    include("./sis/adm.php");
    $this->rec['ope']['fin']['sis_adm']['htm'] = ob_get_clean();
    */

    // cargo operadores del documento ( botones + contenidos )
    foreach( $this->rec['ope'] as $tip => $tip_lis ){

      foreach( $tip_lis as $ide => $ope ){
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $this->htm['ope'][$tip] .= api_fig::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $this->htm['ope'][$tip] .= api_doc::bot([ $ide => $ope ]);
          // contenido
          $this->htm[$ope['tip']] .= api_doc::{$ope['tip']}($ide,$ope);          
        }
      }  
    }

    // modal de operadores
    $this->htm['win'] .= api_doc::win('doc_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);  
    
    // ajusto diseño
    $_ver = [];
    foreach( ['bar','pie'] as $ide ){ 
      if( !empty($this->htm[$ide]) ) $_ver []= $ide; 
    }
    if( !empty($_ver) ) $this->rec['ele']['body']['data-ver'] = implode(',',$_ver);

    // titulo
    if( !empty($this->rec['dat']['art']->nom) ){
      $this->htm['tit'] = $this->rec['dat']['art']->nom;
    }
    elseif( !empty($this->rec['dat']['cab']->nom) ){
      $this->htm['tit'] = $this->rec['dat']['cab']->nom;
    }
    elseif( !empty($this->rec['dat']['esq']->nom) ){
      $this->htm['tit'] = $this->rec['dat']['esq']->nom; 
    }
  }

  // recursos
  public array $rec = [
    // clases de sistema
    'cla' => [
    ],
    // objetos de sistema
    'obj'=>[
      'api'=>[ 'doc','arc','eje','ele','obj','lis','opc','num','tex','fig','fec','hol' ]
    ],
    // instancias 
    'api_dat'=>[ '_est'
    ],
    // datos de la aplicacion
    'dat'=> [
    ],      
    // elementos del documento
    'ele' => [
    ],
    // ejecuciones por aplicacion
    'eje' => "",
    // operadores: botones de la cabecera
    'ope' => [
      'ini'=>[
        'app_ini'=>[ 'ico'=>"app", 'url'=>SYS_NAV, 'nom'=>"Página de Inicio" ],        
        'app_cab'=>[ 'ico'=>"app_cab", 'tip'=>"pan", 'nom'=>"Menú Principal" ],
        'app_nav'=>[ 'ico'=>"app_nav", 'tip'=>"pan", 'nom'=>"Índice", 'htm'=>"" ]
      ],
      'fin'=>[
        'ses_ini'=>[ 'ico'=>"app_ini", 'tip'=>"win", 'nom'=>"Iniciar Sesión..."  ],
        'ses_ope'=>[ 'ico'=>"usu",     'tip'=>"win", 'nom'=>"Cuenta de Usuario..." ],
        'sis_adm'=>[ 'ico'=>"eje",     'tip'=>"win", 'nom'=>"Consola del Sistema" ],
        'app_dat'=>[ 'ico'=>"dat_des", 'tip'=>"win", 'nom'=>"Ayuda" ]
      ]
    ]
  ];// recursos: cargo contenido
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
    return $_;
  }

  // peticion
  public object $uri
  ;// armo directorios
  public function uri_dir( object $uri = NULL ) : object {

    if( !isset($uri) ) $uri = $this->uri;

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

  // Menu principal : titulo + descripcion + listado > item = [icono] + enlace
  public function cab( array $ele = [] ) : string {
    
    global $sis_usu;
    
    $esq = $this->uri->esq;
    
    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $_lis = [];
    foreach( api_dat::get('app_cab',[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

      if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($sis_usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? api_fig::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $_lis_val = [];
      foreach( api_dat::get('app_art',[ 
        'ver'=>"`esq`='$esq' AND `cab`='$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art 
      ){
        $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

        if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

        $ele_val['href'] = SYS_NAV."/$_art->esq/$_art->cab/$_art->ide";

        $_lis_val []= "
        <a".api_ele::atr($ele_val).">
          <p>"
          .( !empty($_art->ico) ? api_fig::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
          .api_tex::let($_art->nom)."
          </p>
        </a>";
      }
      $_lis []= [ 
        'ite'=>[ 'eti'=>"p", 'class'=>"ide-$_cab->ide mar_ver-1 tex-tit tex-enf", 'htm'=>$ite_ico.api_tex::let($_cab->nom) ],
        'lis'=>$_lis_val 
      ];
    }
    // reinicio opciones
    api_ele::cla($ele['lis'],"nav");
    api_ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'
    return api_lis::dep($_lis,$ele);
  }
  // Articulo por contenido + ...secciones + pie de página
  public function art( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = api_ele::htm($nav->ope);

    $_art = api_dat::get('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

    $_ = "
    <article class='doc_inf'>";
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
            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".api_tex::let($art->nom)."</a>";
            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='doc_val nav'>
                ".api_doc::val_ico()."
                {$art_url}
              </div>
              <div class='doc_dat'>
                ".api_ele::val($art->ope['tex'])."
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
  // Section por indices : section > h2 + ...section > h3 + ...section > ...
  public function nav( string $ide ) : string {
    $_ = "";
    $_ide = explode('.',$ide);
    $_nav = api_dat::get('app_nav',[ 
      'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 
      'nav'=>'pos' 
    ]);
    if( isset($_nav[1]) ){

      foreach( $_nav[1] as $nv1 => $_nv1 ){ $_ .= "
        
        <h2 id='_{$nv1}-'>".api_tex::let($_nv1->nom)."</h2>
        <section>";
          if( isset($_nav[2][$nv1]) ){
            foreach( $_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "

          <h3 id='_{$nv1}-{$nv2}-'>".api_tex::let($_nv2->nom)."</h3>
          <section>";
            if( isset($_nav[3][$nv1][$nv2]) ){
              foreach( $_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "

            <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>".api_tex::let($_nv3->nom)."</h4>
            <section>";
              if( isset($_nav[4][$nv1][$nv2][$nv3]) ){
                foreach( $_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "

              <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>".api_tex::let($_nv4->nom)."</h5>
              <section>";
                if( isset($_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                  foreach( $_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "

                <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>".api_tex::let($_nv5->nom)."</h6>
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
        </section>";
      }
    }
    return $_;
  }
}