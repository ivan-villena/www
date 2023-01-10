<?php
// Página-app
class sis_app {

  // inicializo aplicacion
  static string $IDE = "sis_app-";
  static string $EJE = "sis_app.";

  function __construct(){
  }

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
        'doc', 'arc', 'eje', 'ele', 'est', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol'
      ],
      'sis'=>[
        'log', 'sql', 'dom', 'dat', 'app', 'usu' 
      ]
    ],
    // Estructuras
    'est' => [
      'api'=>[]
    ],
    // Registros
    'dat' => [
      'api'=>[
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
        'app_nav'=>[ 'ico'=>"app_nav", 'tip'=>"pan",    'nom'=>"Índice", 'htm'=>"" ]        
      ],
      'med'=>[
        'app_dat'=>[ 'ico'=>"dat_des", 'tip'=>"win",    'nom'=>"Ayuda" ]
      ],
      'fin'=>[
        'ses_ini'=>[ 'ico'=>"app_ini", 'tip'=>"win",    'nom'=>"Iniciar Sesión..."  ],
        'ses_ope'=>[ 'ico'=>"usu",     'tip'=>"win",    'nom'=>"Cuenta de Usuario..." ],
        'sis_adm'=>[ 'ico'=>"eje",     'tip'=>"win",    'nom'=>"Consola del Sistema..." ]        
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
  }// - Proceso Petición
  public function rec_uri( string $uri ) : object {

    // armo peticion
    $uri = explode('/',$uri);

    $this->rec['uri'] = new stdClass;
    $this->rec['uri']->esq = !empty($uri[0]) ? $uri[0] : "hol";
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

    // ajusto enlace de inicio
    $this->rec['ope']['ini']['app_ini']['url'] .= $_uri->esq;

    // cargo menú principal
    $this->rec['ope']['ini']['app_cab']['htm'] = $this->cab();

    // cargo elemento principal
    $this->rec['ele']['body'] = [
      'data-doc'=>$_uri->esq, 
      'data-cab'=>!!$_uri->cab ? $_uri->cab : NULL, 
      'data-art'=>!!$_uri->art ? $_uri->art : NULL 
    ];
    
    // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
    $this->rec['app']['esq'] = sis_dat::get('app_esq',[ 'ver'=>"`ide`='{$_uri->esq}'", 'opc'=>'uni' ]);
    if( !empty($_uri->cab) ){
      
      // cargo datos del menu
      $this->rec['app']['cab'] = sis_dat::get('app_cab',[ 'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 
        'ele'=>'ope', 'opc'=>'uni'
      ]);

      // cargo datos del artículo
      if( !empty($_uri->art) ){
        $this->rec['app']['art'] = sis_dat::get('app_art',[ 'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$_uri->cab}' AND `ide`='{$_uri->art}'", 
          'ele'=>'ope', 'opc'=>'uni' 
        ]);

        // cargo índice de contenidos
        if( !empty($this->rec['app']['cab']->nav) ){

          $this->rec['app']['nav'] = sis_dat::get('app_nav',[ 'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$this->rec['uri']->cab}' AND `ide`='{$this->rec['uri']->art}'", 
            'ord'=>"pos ASC", 
            'nav'=>'pos'
          ]);

          // pido listado por navegacion
          if( !empty($this->rec['app']['nav'][1]) ) $this->rec['ope']['ini']['app_nav']['htm'] = api_lis::nav($this->rec['app']['nav']);
        }          
      }
    }

    return $_uri;

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
  }// - Inicializo pagina
  public function rec_htm() : array {

    global $sis_usu;

    /* 
    // loggin
    $eje = "ses_".( empty($sis_usu->ide) ? 'ini' : 'ope' );
    $this->rec['ope']['fin'][$eje]['htm'] = $sis_usu->$eje();
    */

    // consola del sistema
    if( $sis_usu->ide == 1 ){
      $this->rec['cla']['sis'] []= "adm";
      ob_start();
      include("./sis/adm.php");
      $this->rec['ope']['fin']['sis_adm']['htm'] = ob_get_clean();
    }

    // cargo operadores del documento ( botones + contenidos )
    foreach( $this->rec['ope'] as $tip => $tip_lis ){

      foreach( $tip_lis as $ide => $ope ){
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $this->rec['htm']['ope'][$tip] .= api_fig::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $this->rec['htm']['ope'][$tip] .= sis_app::bot([ $ide => $ope ]);
          // contenido
          $this->rec['htm'][$ope['tip']] .= sis_app::{$ope['tip']}($ide,$ope);
        }
      }  
    }

    // modal de operadores
    $this->rec['htm']['win'] .= sis_app::win('doc_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);  
    
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

    return $this->rec['htm'];
  }

  /* Botones  : ( pan + win ) */
  static function bot( $dat ) : string {
    $_ = "";      
    $_eje = self::$EJE;
    
    foreach( $dat as $ide => $art ){
      
      $tip = isset($art['tip']) ? $art['tip'] : 'nav';

      $eje_tog = "{$_eje}{$tip}('$ide');";

      $ele = [ 'eti'=>"a", 'onclick'=>$eje_tog, 'data-ide'=>$ide  ];

      if( is_string($art) ){
        $_ .= api_fig::ico($art,$ele);
      }
      elseif( is_array($art) ){

        if( isset($art[0]) ){
          $ele['title'] = isset($art[1]) ? $art[1] : '';
          $_ .= api_fig::ico($art[0],$ele);
        }
        elseif( isset($art['ico']) ){
          $ele['title'] = isset($art['nom']) ? $art['nom'] : '';
          $_ .= api_fig::ico($art['ico'],$ele);
        }
      }
      elseif( is_object($art) && isset($art->ico) ){
        $ele['title'] = isset($art->nom) ? $art->nom : '';
        $_ .= api_fig::ico($art->ico,$ele);
      }
    }
    return $_;
  }

  /* Modal : #sis > article[ide] > header + section */
  static function win( string $ide, array $ope = [] ) : string {
    foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
    $_eje = self::$EJE."win";
    $_ = "";
    // icono de lado izquierdo
    $cab_ico = "";
    if( isset($ope['ico']) ){
      if( is_string($ope['ico']) ){
        $cab_ico = api_fig::ico($ope['ico'],['class'=>"mar_hor-1"]);
      }// con menú
      else{
        $_ .= "
        <div class='ini'>";
          $_.="
        </div>";
      }
    }
    // titulo al centro
    $cab_tit = "";
    if( isset($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? api_tex::let($ope['nom']) : "" )."</h2>
    ";
    // botones de flujo
    $cab_bot = "
    <div class='doc_ope'>
      ".api_fig::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"fin", 'onclick'=>"$_eje(this);" ])."
    </div>";
    // contenido 
    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = api_ele::val_dec( $ope['htm'] );
    }      
    // imprimo con identificador
    api_ele::cla($ope['art'],"ide-$ide",'ini');
    api_ele::cla($ope['art'],DIS_OCU);
    $_ = "
    <article".api_ele::atr($ope['art']).">

      <header".api_ele::atr($ope['cab']).">      
        {$cab_ico} 
        {$cab_tit} 
        {$cab_bot}
      </header>

      <div".api_ele::atr($ope['sec']).">
        {$ope['htm']}
      </div>
    </article>";
    return $_;
  }

  /* Panel : nav|article[ide] > header + section */
  static function pan( string $ide, array $ope = [] ) : string {
    foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
    $_eje = self::$EJE."pan";
    $_ = "";
    $cab_ico = "";
    if( !empty($ope['ico']) ) $cab_ico = api_fig::ico($ope['ico'],['class'=>"mar_hor-1"]);

    $cab_tit = "";
    if( !empty($ope['nom']) ) $cab_tit = "
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
    ";
    // etiquetas
    $eti_nav = 'nav';
    if( isset($ope['nav']['eti']) ){
      $eti_nav = $ope['nav']['eti'];
      unset($ope['nav']['eti']);
    }
    $eti_sec = 'div';
    if( isset($ope['sec']['eti']) ){
      $eti_sec = $ope['sec']['eti'];
      unset($ope['sec']['eti']);
    }

    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = api_ele::val_dec( $ope['htm'] );
    }

    // imprimo con identificador
    api_ele::cla($ope['nav'],"ide-$ide",'ini');
    api_ele::cla($ope['nav'],DIS_OCU);
    $_ = "
    <$eti_nav".api_ele::atr($ope['nav']).">

      <header".api_ele::atr($ope['cab']).">
      
        {$cab_ico} {$cab_tit} ".api_fig::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

      </header>

      <$eti_sec".api_ele::atr($ope['sec']).">

        {$ope['htm']}

      </$eti_sec>

    </$eti_nav>";
    
    return $_;
  }

  /* Seccion : main > ...article */
  static function sec( string | array $dat, array $ele = [] ) : string {
    $_ = "";
    if( isset($ele['tit']) ){ $_ .= "
      <header".api_ele::atr( isset($ele['cab']) ? $ele['cab'] : [] ).">";
        if( is_string($ele['tit']) ){ $_ .= "
          <h1>".api_tex::let($ele['tit'])."</h1>";
        }else{
          $_ .= api_ele::val_dec(...$ele['tit']);
        }$_ .= "
      </header>";
    }
    // contenido directo
    if( is_string($dat) ){ 
      $_ .= $dat;
    }
    // listado de articulos
    else{
      foreach( $dat as $ide => $art ){
        
        if( isset($art['htm'])){
          api_ele::cla($art,"ide-$ide",'ini');
          $_ .= "
          <article".api_ele::atr($art).">
            {$art['htm']}
          </article>";
        }
      }
    }
    return $_;
  }// Articulo por contenido + ...secciones + pie de página
  public function sec_art( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = api_ele::htm($nav->ope);

    $_art = sis_dat::get('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
  }// Section por indices : section > h2 + ...section > h3 + ...section > ...
  public function sec_nav( string $ide ) : string {
    $_ = "";
    $_ide = explode('.',$ide);
    $_nav = sis_dat::get('app_nav',[ 
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

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

  /* Menu principal : titulo + descripcion + listado > item = [icono] + enlace */
  public function cab( array $ele = [] ) : string {
    
    global $sis_usu;
    
    $esq = $this->rec['uri']->esq;
    
    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $_lis = [];
    foreach( sis_dat::get('app_cab',[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

      if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($sis_usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? api_fig::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $_lis_val = [];
      foreach( sis_dat::get('app_art',[ 
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
}