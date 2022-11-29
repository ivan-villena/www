<?php
// Página-app
class app {

  function __construct( $esq ){

    global $sis_cla;
    $_uri = $this->uri_val($esq);

    // Recursos : css + jso + eje + dat + obj
    $this->rec = [
      // clases de sistema
      'cla' => [
        'api'=>$sis_cla
      ],
      // objetos de sistema
      'obj'=>[
        'api'=>[ 'val','opc','num','tex','fig','fec','hol','obj','lis','est','tab','eje','ele','arc','doc' ]
      ],
      // datos de la aplicacion
      'dat'=> [
      ],      
      // elementos del documento
      'ele' => [
      ],
      // ejecuciones por aplicacion
      'eje' => ""
    ];
    // Operadores : boton + panel + pantalla
    $this->ope = [
      // inicio
      'app_ini'=>[
        'ico'=>"app", 'url'=>SYS_NAV, 'nom'=>"Página de Inicio"
      ],// menu
      'app_cab'=>[
        'ico'=>"app_cab", 'tip'=>"pan", 'nom'=>"Menú Principal"
      ],// indice
      'doc_art'=>[
        'ico'=>"app_nav", 'tip'=>"pan", 'nom'=>"Índice", 'htm'=>""
      ],// login
      'ses_ini'=>[ 
        'ico'=>"app_ini", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Iniciar Sesión..." 
      ],// logout
      'ses_fin'=>[ 
        'ico'=>"app_fin", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Cerrar Sesión..."
      ],// consola
      'sis_adm'=>[ 
        'ico'=>"eje", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Consola del Sistema", 'art'=>[ 'style'=>"max-width: 55rem;" ]
      ]
    ];
    // Contenido html
    $this->htm = [
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
    ];
  }

  // accesos
  public array  $ope;

  // contenido
  public array  $htm;

  // recursos
  public array $rec;
  // cargo contenido
  public function rec_cla( string $tip = "", array $dat = [], string $ide = "api" ) : string {
    $_ = "";
    if( empty($dat) ) $dat = $this->rec['cla'];
    // estilos
    if( $tip == 'css' ){

      foreach( $dat as $mod_ide => $mod_lis ){
        // por módulos
        $rec_ide = ( $mod_ide == $ide ) ? $mod_ide : "src/{$mod_ide}";
        foreach( $mod_lis as $cla_ide ){
          if( file_exists( "./".($rec = "{$rec_ide}/{$cla_ide}.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
        }
        // por página
        if( file_exists( "./".($rec = "{$rec_ide}/index.css") ) ) $_ .= "
          <link rel='stylesheet' href='".SYS_NAV."$rec' >";
      }
    }// prorama : clases 
    elseif( $tip == 'jso' ){
      foreach( $dat as $mod_ide => $mod_lis ){
        // por modulos
        $rec_ide = ( $mod_ide == $ide ) ? $mod_ide : "src/{$mod_ide}";
        foreach( $mod_lis as $cla_ide ){ 
          if( file_exists( "./".($rec = "{$rec_ide}/{$cla_ide}.js") ) ) $_ .= "
            <script src='".SYS_NAV."$rec'></script>";
        }
        // por página
        if( file_exists( "./".($rec = "{$rec_ide}/index.js") ) ) $_ .= "
        <script src='".SYS_NAV."$rec'></script>";
      }
    }// programa : objetos
    elseif( $tip == 'jso-obj' ){
      $var = get_defined_vars();
      foreach( $dat as $cla ){
        if( isset($var[$obj = !empty($ide) ? "{$ide}_{$cla}" : $cla]) && is_object($var[$obj]) ){ $_ .= "
          var \${$obj} = new $cla(".( !empty($atr = get_object_vars($var[$obj])) ? obj::val_cod($atr) : "" ).")";
        }
      }
    }
    return $_;
  }

  // peticion http://
  public object $uri;
  // cargo peticion
  public function uri_val( string $uri, string $esq = "hol" ) : object {
    
    if( !isset($this->uri) ){

      $this->uri = new stdClass;

      $uri = explode('/', !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '');      

      $_uri = &$this->uri;
      $_uri->esq = !empty($uri[0]) ? $uri[0] : $esq;
      $_uri->cab = !empty($uri[1]) ? $uri[1] : FALSE;
      if( $_uri->art = !empty($uri[2]) ? $uri[2] : FALSE ){
        $_val = explode('#',$_uri->art);
        if( isset($_val[1]) ){
          $_uri->art = $_val[0];
          $_uri->val = $_val[1];  
        }
        else{
          $_uri->val = !empty($uri[3]) ? $uri[3] : FALSE;
        }
      }

      // ajusto enlace de inicio
      $this->rec['ope']['app_ini']['url'] .= "$_uri->esq";

      // cargo menú principal
      $this->rec['ope']['app_cab']['htm'] = $this->cab($_uri->esq);

      // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
      $this->rec['ele']['body'] = [
        'data-doc'=>$_uri->esq, 
        'data-cab'=>!!$_uri->cab ? $_uri->cab : NULL, 
        'data-art'=>!!$_uri->art ? $_uri->art : NULL 
      ];
      $this->rec['dat']['esq'] = dat::get('app_esq',[ 'ver'=>"`ide`='{$_uri->esq}'", 'opc'=>'uni' ]);
      if( !empty($_uri->cab) ){
        
        // cargo datos del menu
        $this->rec['dat']['cab'] = dat::get('app_cab',[ 'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 
          'ele'=>'ope', 'opc'=>'uni'
        ]);

        // cargo datos del artículo
        if( !empty($_uri->art) ){
          $this->rec['dat']['art'] = dat::get('app_art',[ 'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$_uri->cab}' AND `ide`='{$_uri->art}'", 
            'ele'=>'ope', 'opc'=>'uni' 
          ]);

          // cargo índice de contenidos
          if( !empty($this->rec['dat']['cab']->nav) ){

            $this->rec['dat']['nav'] = dat::get('app_nav',[ 'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$_uri->cab}' AND `ide`='{$_uri->art}'", 
              'ord'=>"pos ASC", 
              'nav'=>'pos'
            ]);

            // pido listado por navegacion
            if( !empty($this->rec['dat']['nav'][1]) ) $this->ope['doc_art']['htm'] = doc::art($this->rec['dat']['nav']);
          }          
        }
      }      
    }
    return $this->uri;
  }
  // armo directorios
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
  public function cab( string $esq = "", array $ele = [] ) : string {
    global $api_usu;      
    if( empty($esq) ) $esq = $this->uri->esq;
    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $_lis = [];
    foreach( dat::get('app_cab',[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

      if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($api_usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? fig::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $_lis_val = [];
      foreach( dat::get('app_art',[ 
        'ver'=>"`esq`='$esq' AND `cab`='$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art 
      ){
        $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

        if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

        $ele_val['href'] = SYS_NAV."/$_art->esq/$_art->cab/$_art->ide";

        $_lis_val []= "
        <a".ele::atr($ele_val).">"
          .( !empty($_art->ico) ? fig::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
          ."<p>".tex::let($_art->nom)."</p>
        </a>";
      }
      $_lis []= [ 
        'ite'=>[ 'eti'=>"p", 'class'=>"ide-$_cab->ide mar_ver-1 tex-tit tex-4", 'htm'=>$ite_ico.tex::let($_cab->nom) ],
        'lis'=>$_lis_val 
      ];
    }
    // reinicio opciones
    ele::cla($ele['lis'],"nav");
    ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'
    return lis::ite($_lis,$ele);

  }

  // Articulo por contenido + ...secciones + pie de página
  public function art( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = ele::htm($nav->ope);

    $_art = dat::get('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

    $_ = "
    <article class='inf'>";
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
            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".tex::let($art->nom)."</a>";
            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='val nav'>
                ".doc::val_ico()."
                {$art_url}
              </div>
              <div class='dat'>
                ".ele::val($art->ope['tex'])."
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
    $_nav = dat::get('app_nav',[ 
      'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 
      'nav'=>'pos' 
    ]);
    if( isset($_nav[1]) ){

      foreach( $_nav[1] as $nv1 => $_nv1 ){ $_ .= "
        
        <h2 id='_{$nv1}-'>".tex::let($_nv1->nom)."</h2>
        <section>";
          if( isset($_nav[2][$nv1]) ){
            foreach( $_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "

          <h3 id='_{$nv1}-{$nv2}-'>".tex::let($_nv2->nom)."</h3>
          <section>";
            if( isset($_nav[3][$nv1][$nv2]) ){
              foreach( $_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "

            <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>".tex::let($_nv3->nom)."</h4>
            <section>";
              if( isset($_nav[4][$nv1][$nv2][$nv3]) ){
                foreach( $_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "

              <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>".tex::let($_nv4->nom)."</h5>
              <section>";
                if( isset($_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                  foreach( $_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "

                <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>".tex::let($_nv5->nom)."</h6>
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