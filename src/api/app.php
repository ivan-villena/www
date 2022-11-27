<?php
// Página-app
class app {

  public array  $rec;
  public array  $doc;
  public array  $ope;
  public array  $htm;  

  static string $IDE = "app-";
  static string $EJE = "app.";

  // cargo aplicacion ( por defecto: sincronario )
  function __construct($esq){

    $_uri = $this->uri_val($esq);
    
    // Recursos : css + jso + eje + dat + obj
    global $sis_cla;
    $this->rec = [
      // clases de sistema
      'cla' => $sis_cla,
      // elementos      
      'ele' => [
        'title'=> "{-_-}",
        'body' => [
          'data-doc'=> $_uri->esq,
          'data-cab'=> !!$_uri->cab ? $_uri->cab : NULL, 
          'data-art'=> !!$_uri->art ? $_uri->art : NULL 
        ]
      ],// ejecuciones
      'eje' => ""
    ];

    // Operadores : boton + panel + pantalla
    $this->ope = [
      // inicio
      'app_ini'=>[
        'ico'=>"app", 'url'=>SYS_NAV, 'nom'=>"Página de Inicio"
      ],// menu
      'app_cab'=>[
        'ico'=>"app_cab", 'tip'=>"pan", 'nom'=>"Menú Principal", 'htm'=>$this->cab( $_uri->esq )
      ],// indice
      'app_nav'=>[
        'ico'=>"app_nav", 'tip'=>"pan", 'nom' => "Índice", 'htm'=>""
      ],// login
      'ses_ini'=>[ 
        'ico'=>"app_ini", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Iniciar Sesión..." 
      ],// logout
      'ses_fin'=>[ 
        'ico'=>"app_fin", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Cerrar Sesión..."
      ],// consola
      'app_adm'=>[ 
        'ico'=>"eje", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Consola del Sistema", 'art'=>[ 'style'=>"max-width: 55rem;" ]
      ]
    ];
    
    // Contenido html
    $this->htm = [
      // botones
      'ope' => [ 'ini'=>"", 'fin'=>"" ],
      // paneles
      'pan' => "",
      // main
      'sec' => "",
      // modales
      'win' => "",
      // barra lateral
      'bar' => "",
      // barra inferior
      'pie' => ""
    ];

    // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
    $this->doc['esq'] = dat::get('app_esq',[ 
      'ver'=>"`ide`='{$_uri->esq}'", 
      'opc'=>'uni' 
    ]);
    if( !empty($_uri->cab) ){
      // cargo datos del menu
      $this->doc['cab'] = dat::get('app_cab',[ 
        'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 
        'ele'=>'ope', 'opc'=>'uni' 
      ]);
      // cargo datos del artículo
      if( !empty($_uri->art) ){
        $this->doc['art'] = dat::get('app_art',[ 
          'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$_uri->cab}' AND `ide`='{$_uri->art}'", 
          'ele'=>'ope', 'opc'=>'uni' 
        ]);
        if( !empty($val) ){
          // proceso seccion/valor
          $this->doc['val'] = [];
          foreach( explode(';',$val) as $_val ){
            $_val = explode('=',$_val);
            $this->doc['val'][$_val[0]] = isset($_val[1]) ? $_val[1] : NULL;
          }
        }
      }
      // cargo índice de contenidos
      if( !empty($this->doc['cab']->nav) ){

        $this->doc['nav'] = dat::get('app_nav',[
          'ver'=>"`esq` = '{$_uri->esq}' AND `cab` = '{$_uri->cab}' AND `ide` = '{$_uri->art}'", 
          'ord'=>"pos ASC", 
          'nav'=>'pos'
        ]);
      }
    }

    // completo titulo
    if( !empty($this->doc['art']->nom) ){
      $this->rec['ele']['title'] = $this->doc['art']->nom;
    }
    elseif( !empty($this->doc['cab']->nom) ){
      $this->rec['ele']['title'] = $this->doc['cab']->nom;
    }
    elseif( !empty($this->doc['esq']->nom) ){
      $this->rec['ele']['title'] = $this->doc['esq']->nom; 
    }      
  }

  // peticion http://
  public object $uri;
  // armo peticion
  public function uri_val( string $esq = "" ) : object {
    
    if( !isset($this->uri) ){

      $uri = explode('/', !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '');

      $this->uri = new stdClass;

      $_uri = &$this->uri;
      
      $_uri->esq = !empty($uri[0]) ? $uri[0] : $esq;
      $_uri->cab = !empty($uri[1]) ? $uri[1] : FALSE;
      $_uri->art = !empty($uri[2]) ? $uri[2] : FALSE;

      if( $_uri->art ) $_val = explode('#',$_uri->art);

      if( isset($_val[1]) ){
        $_uri->art = $_val[0];
        $_uri->val = $_val[1];  
      }
      else{          
        $_uri->val = !empty($uri[3]) ? $uri[3] : FALSE;
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
  // Indice del Articulo: a[href] > ...a[href]
  public function nav( array $dat = [], array $ele = [], ...$opc ) : string {    
    foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    // aseguro datos indexados
    if( empty($dat) ) $dat = $this->doc['nav'];
    $_eje = self::$EJE."art";// val | ver
    $_ = "";

    // operador
    ele::cla( $ele['ope'], "ren", 'ini' );
    $_ .= "
    <form".ele::atr($ele['ope']).">

      ".doc::val_ope()."

      ".doc::val_ver([ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}_ver(this);" ])."      

    </form>";
    
    // dependencias
    $tog_dep = FALSE;
    if( in_array('tog_dep',$opc) ){
      ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
      <form".ele::atr($ele['ope_dep']).">

        ".doc::val_ope()."

      </form>";
    }
    
    // armo listado de enlaces
    $_lis = [];
    $opc_ide = in_array('ide',$opc);
    ele::cla( $ele['lis'], "nav", 'ini' );
    foreach( $dat[1] as $nv1 => $_nv1 ){
      $ide = $opc_ide ? $_nv1->ide : $nv1;
      $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv1->nom}") ];
      if( !isset($dat[2][$nv1]) ){
        $_lis []= ele::val($eti_1);
      }
      else{
        $_lis_2 = [];
        foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
          $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
          $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv2->nom}") ];
          if( !isset($dat[3][$nv1][$nv2])  ){
            $_lis_2 []= ele::val($eti_2);
          }
          else{
            $_lis_3 = [];              
            foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
              $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
              $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv3->nom}") ];
              if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                $_lis_3 []= ele::val($eti_3);
              }
              else{
                $_lis_4 = [];                  
                foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                  $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                  $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv4->nom}") ];
                  if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                    $_lis_4 []= ele::val($eti_4);
                  }
                  else{
                    $_lis_5 = [];                      
                    foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                      $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                      $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv5->nom}") ];
                      if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                        $_lis_5 []= ele::val($eti_5);
                      }
                      else{
                        $_lis_6 = [];
                        foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                          $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                          $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}(this);", 'htm'=> tex::let("{$_nv6->nom}") ];
                          if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                            $_lis_6 []= ele::val($eti_6);
                          }
                          else{
                            $_lis_7 = [];
                            // ... continuar ciclo
                            $_lis_6 []= [ 'ite'=>$eti_6, 'lis'=>$_lis_7 ];                              
                          }
                        }
                        $_lis_5 []= [ 'ite'=>$eti_5, 'lis'=>$_lis_6 ];
                      }
                    }
                    $_lis_4 []= [ 'ite'=>$eti_4, 'lis'=>$_lis_5 ];
                  }
                }
                $_lis_3 []= [ 'ite'=>$eti_3, 'lis'=>$_lis_4 ];
              }
            }
            $_lis_2 []= [ 'ite'=>$eti_2, 'lis'=>$_lis_3 ];  
          }
        }
        $_lis []= [ 'ite'=>$eti_1, 'lis'=>$_lis_2 ];
      }
    }
    // pido listado
    ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [];
    $_ .= lis::ite($_lis,$ele);
    return $_;
  }
  // Section por indices : section > h2 + ...section > h3 + ...section > ...
  public function sec( string $ide ) : string {
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