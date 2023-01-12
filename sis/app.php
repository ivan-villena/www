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
        'arc', 'eje', 'ele', 'est', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol'
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
    $this->rec['htm']['win'] .= sis_app::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);  
    
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

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////    

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
    <div class='app_ope'>
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
  
  // Navegador : nav + * > ...[nav="ide"]
  static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
    $_ = "";
    $_eje = self::$EJE."nav";
    foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    $opc_ico = in_array('ico',$opc);
    $val_sel = isset($ele['sel']) ? $ele['sel'] : FALSE;
    
    // navegador 
    api_ele::cla($ele['lis'], "app_nav $tip", 'ini');
    $_ .= "
    <nav".api_ele::atr($ele['lis']).">";    
    foreach( $dat as $ide => $val ){

      if( is_object($val) ) $val = api_obj::val_nom($val);

      if( isset($val['ide']) ) $ide = $val['ide'];

      $ele_nav = isset($val['nav']) ? $val['nav'] : [];

      $ele_nav['eti'] = 'a';
      api_ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

      if( $val_sel && $val_sel == $ide ) api_ele::cla($ele_nav,FON_SEL);

      if( $opc_ico && isset($val['ico']) ){
        $ele_nav['title'] = $val['nom'];
        api_ele::cla($ele_nav,"mar-0 pad-1 tam-4 bor_cir-1",'ini');
        $_ .= api_fig::ico($val['ico'],$ele_nav);
      }
      else{
        $ele_nav['htm'] = $val['nom'];
        $_ .= api_ele::val($ele_nav);
      }        
    }$_.="
    </nav>";
    
    // contenido
    $eti_sec = 'div';
    if( isset($ele['sec']['eti'])  ){
      $eti_sec = $ele['sec']['eti'];
      unset($ele['sec']['eti']);
    }
    $eti_ite = 'section';
    if( isset($ele['ite']['eti']) ){
      $eti_ite = $ele['ite']['eti'];
      unset($ele['ite']['eti']);
    }
    if( $tip != 'pes' && !$val_sel ) api_ele::cla($ele['sec'],DIS_OCU);
    $_ .= "
    <$eti_sec".api_ele::atr($ele['sec']).">";
      foreach( $dat as $ide => $val ){
        $ele_ite = $ele['ite'];
        api_ele::cla($ele_ite,"ide-$ide",'ini');
        if( !$val_sel || $val_sel != $ide ) api_ele::cla($ele_ite,DIS_OCU);
        $_ .= "
        <$eti_ite".api_ele::atr($ele_ite).">
          ".( isset($val['htm']) ? ( is_array($val['htm']) ? api_ele::val_dec($val['htm']) : $val['htm'] ) : '' )."
        </$eti_ite>";
      }$_.="
    </$eti_sec>";

    // agrupo contenedor del operador
    if( $tip == 'ope' ){
      $_ = "
      <div class='app_ope-nav'>
        {$_}
      </div>";
    }

    return $_;
  }  

  /* Opciones : click derecho */
  static function opc( array $ope = [], array $ele = [] ) {
  }

  // Carteles : advertencia + confirmacion
  static function tex( array $ope = [], array $ele = [] ) : string {
    foreach( ['sec','ico','tex'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    api_ele::cla($ele['sec'],"app_tex".( !empty($ope['tip']) ? " -{$ope['tip']}" : "" ),'ini');

    $_ = "
    <div".api_ele::atr($ele['sec']).">";

      // cabecera: icono + titulo
      if( isset($ope['tip']) || $ope['tit'] ){
        $_.="
        <header>";
          if( isset($ope['tip']) ){
            switch( $ope['tip'] ){
            case 'err': $ele['ico']['title'] = "Error..."; break;
            case 'adv': $ele['ico']['title'] = "Advertencia..."; break;
            case 'opc': $ele['ico']['title'] = "Consultas..."; break;
            case 'val': $ele['ico']['title'] = "Notificación..."; break;
            }
            api_ele::cla($ele['ico'],"mar-1");
            $_ .= api_fig::ico("val_tex-{$ope['tip']}", $ele['ico']);
          }
          if( isset($ope['tit']) ){
            if( is_string($ope['tit']) ) $_.="<p class='tit'>".api_tex::let($ope['tit'])."</p>";
          }
          $_.="
        </header>";
      }

      // contenido: texto
      if( isset($ope['tex']) ){
        $ope_tex = [];
        if( is_array($ope['tex']) ){
          foreach( $ope['tex'] as $tex ){
            $ope_tex []= api_tex::let($tex);
          }
        }else{
          $ope_tex []= $ope['tex'];
        }
        $_ .= "<p".api_ele::atr($ele['tex']).">".implode('<br>',$ope_tex)."</p>" ;
      }

      // elementos
      if( isset($ope['htm']) ){
        $_ .= api_ele::val(...api_lis::val_ite($ope['htm']));
      }

      // botones: aceptar - cancelar
      if( isset($ope['opc']) ){
        $_ .= "
        <form class='app_ope'>";
        if( isset($ope['opc']['ini']) ){
          
        }
        if( isset($ope['opc']['fin']) ){
        
        }
        $_ .= "
        </form>";
      }

      $_.="
    </div>";
    return $_;
  }

  // Variable : div.dat_var > label + (select,input,textarea,button)[name]
  static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
    $_VAR = [ 
      'ico'=>"", 
      'nom'=>"", 
      'des'=>"", 
      'ite'=>[], 
      'eti'=>[], 
      'ope'=>[], 
      'htm'=>"", 
      'htm_pre'=>"", 
      'htm_med'=>"", 
      'htm_pos'=>"" 
    ];

    // identificadores
    $dat_ide = is_string($ide) ? explode('.',$ide) : $ide;
    if( isset($dat_ide[2]) ){
      $esq = $dat_ide[0]; 
      $est = $dat_ide[1];
      $atr = $dat_ide[2];
    }
    elseif( isset($dat_ide[1]) ){
      $est = $dat_ide[0];
      $atr = $dat_ide[1];
    }
    else{
      $atr = $dat_ide[0];
    }

    // por atributi de la base
    if( $tip == 'atr' ){

      if( !empty($_atr = sis_dat::est($esq,$est,'atr',$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != 'val' ){ 

      $_var = sis_dat::var($tip,$esq,$est,$atr);
    }

    // combino operadores
    if( !empty($_var) ){

      if( !empty($_var['ope']) ){
        $ele['ope'] = api_ele::val_jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
        unset($_var['ope']);
      }
      $ele = api_obj::val_jun($ele,$_var);
    }
    // identificadores
    if( empty($ele['ope']['id'])  && !empty($ele['ide']) ){
      $ele['ope']['id'] = $ele['ide'];
    }
    // nombre en formulario
    if( empty($ele['ope']['name']) ){
      $ele['ope']['name'] = $atr;
    }      
    // proceso html + agregados
    $agr = api_ele::htm($ele);

    // etiqueta
    $eti_htm='';
    if( !isset($ele['eti']) ) $ele['eti'] = [];
    if( !in_array('eti',$opc) ){
      // icono o texto
      if( !empty($ele['ico']) ){
        $eti_htm = api_fig::ico($ele['ico']);
      }elseif( !empty($ele['nom']) ){    
        $eti_htm = api_tex::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
      }
      // agrego for/id e imprimo
      if( !empty($eti_htm) ){    
        if( isset($ele['id']) ){
          $ele['eti']['for'] = $ele['id'];
        }elseif( isset($ele['ope']['id']) ){
          $ele['eti']['for'] = $ele['ope']['id'];
        }
        $eti_htm = "<label".api_ele::atr($ele['eti']).">{$eti_htm}</label>";
      }
    }

    // contenido medio
    if( !in_array('eti_fin',$opc) ){
      $eti_ini = $eti_htm.( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ); 
      $eti_fin = "";
    }else{
      $eti_ini = ""; 
      $eti_fin = ( !empty($agr['htm_med']) ? $agr['htm_med'] : '' ).$eti_htm;
    }

    // valor: hmtl o controlador
    if( isset($agr['htm']) ){
      $val_htm = $agr['htm'];
    }else{
      if( isset($ele['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }
      if( empty($ele['ope']['name']) && isset($ele['ide']) ){
        $ele['ope']['name'] = $ele['ide'];
      }
      $val_htm = api_ele::val($ele['ope']);
    }

    // contenedor
    if( !isset($ele['ite']) ) $ele['ite']=[];      
    if( !isset($ele['ite']['title']) ) $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';

    return "
    <div".api_ele::atr(api_ele::cla($ele['ite'],"dat_var",'ini')).">
      ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
      {$eti_ini}
      {$val_htm}
      {$eti_fin}
      ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
    </div>
    ";   
  }

  // Conenedor : visible/oculto
  static function val( string | array $dat = NULL, array $ele = [] ) : string {

    $_eje = self::$EJE."val";    
    foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
    
    // contenido textual
    if( is_string($dat) ) $dat = [ 'eti'=>"p", 'class'=>"tex-enf tex-cur", 'htm'=> api_tex::let($dat) ];

    // contenedor : icono + ...elementos          
    api_ele::eje( $dat,'cli',"$_eje(this);",'ini');

    return "
    <div".api_ele::atr( api_ele::cla( $ele['val'],"app_val",'ini') ).">
    
      ".sis_app::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

      ".( isset($ele['htm_ini']) ? api_ele::val($ele['htm_ini']) : '' )."
      
      ".api_ele::val( $dat )."

      ".( isset($ele['htm_fin']) ? api_ele::val($ele['htm_fin']) : '' )."

    </div>";
  }// - icono de toggle
  static function val_ico( array $ele = [] ) : string {

    $_eje = self::$EJE."val";

    return api_fig::ico('val_tog', api_ele::eje($ele,'cli',"$_eje(this);",'ini'));

  }

  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

  /* Menu: titulo + descripcion + listado > item = [icono] + enlace */
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

  // - Articulos : article > h2 + ...section > h3 + ...section > ...
  public function art_nav( string $ide ) : string {
    $_ = "";
    
    $_ide = explode('.',$ide);
    
    $app_nav = sis_dat::get('app_nav',[ 'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 'nav'=>'pos' ]);

    if( isset($app_nav[1]) ){

      foreach( $app_nav[1] as $nv1 => $_nv1 ){ $_ .= "        
        <h2 id='_{$nv1}-'>".api_tex::let($_nv1->nom)."</h2>
        <article>";
          if( isset($app_nav[2][$nv1]) ){
            foreach( $app_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "

          <h3 id='_{$nv1}-{$nv2}-'>".api_tex::let($_nv2->nom)."</h3>
          <section>";
            if( isset($app_nav[3][$nv1][$nv2]) ){
              foreach( $app_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "

            <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>".api_tex::let($_nv3->nom)."</h4>
            <section>";
              if( isset($app_nav[4][$nv1][$nv2][$nv3]) ){
                foreach( $app_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "

              <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>".api_tex::let($_nv4->nom)."</h5>
              <section>";
                if( isset($app_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                  foreach( $app_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "

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
        </article>";
      }
    }

    return $_;
  }

  // Articulo : + ...secciones + pie de página
  public function art( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = api_ele::htm($nav->ope);

    $_art = sis_dat::get('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".api_tex::let($art->nom)."</a>";
            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='doc_val nav'>
                ".sis_app::val_ico()."
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
  
}