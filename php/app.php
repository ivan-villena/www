<?php

  // estilos y clases
  define('DIS_OCU', "dis-ocu" );
  define('FON_SEL', "fon-sel" );
  define('BOR_SEL', "bor-sel" );  

  // Página-app
  class _app {

    // estilos css
    public array $css = ['doc','app'];
    // modulos js
    public array $jso = ['api','doc','app','hol','usu'];
    // elementos
    public array $ele = [];
    // script
    public string $eje = "";
    // titulo
    public string $nom = "{-_-}";
    // datos de interface
    public array $dat = [];
    
    // operador: botones
    public array $ope = [ 'ini'=>"", 'nav'=>"", 'nav_fin'=>"", 'win'=>"", 'win_fin'=>"" ];
    // menú de navegación
    public string $nav = "";
    // seccion principal
    public string $sec = "";
    // pantalla emergente
    public string $win = "";
    // paneles laterales
    public string $pan = "";
    // barra inferior
    public string $pie = "";

    // cargo aplicacion
    function __construct( string $esq ){
      
      global $_api, $_usu;
      
      // peticion url ( por defecto: holon )
      $_uri = new _app_uri( $esq );

      // cargo directorios
      $_dir = $_uri->dir();

      // cargo elementos
      $this->ele = [
        'body'=>[ 'doc'=>$_uri->esq, 'cab'=> !!$_uri->cab ? $_uri->cab : NULL, 'art'=> !!$_uri->art ? $_uri->art : NULL ]
      ];

      // cargo datos : esquema - cabecera - articulo - valor
      $this->_esq = _dat::var("_api.doc_esq",[ 'ver'=>"`ide`='{$_uri->esq}'", 'opc'=>'uni' ]);
      if( !empty($_uri->cab) ){
        // cargo datos del menu
        $this->_cab = _dat::var("_api.doc_cab",[ 
          'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 'ele'=>'ope', 'opc'=>'uni' 
        ]);
        if( !empty($_uri->art) ){
          // cargo datos del artículo
          $this->_art = _dat::var("_api.doc_art",[ 
            'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$_uri->cab}' AND `ide`='{$_uri->art}'", 'ele'=>'ope', 'opc'=>'uni' 
          ]);
          if( !empty($val) ){
            // proceso seccion/valor
            $this->_val = [];
            foreach( explode(';',$val) as $_val ){
              $_val = explode('=',$_val);
              $this->_val[$_val[0]] = isset($_val[1]) ? $_val[1] : NULL;
            }
          }
        }
      }      
      // completo titulo
      if( !empty($this->_art->nom) ){
        $this->nom = $this->_art->nom;
      }
      elseif( !empty($this->_cab->nom) ){
        $this->nom = $this->_cab->nom;
      }
      elseif( !empty($this->_esq->nom) ){
        $this->nom = $this->_esq->nom; 
      }

      // botones : navegacion + pantalla
      $this->ope['ini'] = _doc::ico('ses',[ 'eti'=>"a", 'href'=>SYS_NAV."/{$_uri->esq}", 'title'=>"Inicio..." ]);

      // pido contenido por aplicacion
      if( file_exists($rec = "php/$_uri->esq/app.php") ){

        // inicializo elemento del articulo
        $_ = [ 'sec'=>"", 'nav'=>[], 'nav_fin'=>[], 'win'=>[], 'win_fin'=>[] ];

        $ele = [ 'art'=>[] ];

        // pido calendario
        $_api_dat = [ 'fec'=>[ 'mes','sem','dia' ] ];

        require_once($rec);

        // menu principal
        $_['nav']['cab'] = [ 'ico'=>"nav", 'nom'=>"Menú Principal",
          'htm' => 
            ( isset($nav_htm_ini) ? $nav_htm_ini : '' )
            ._app_ope::nav($_uri->esq, [ 'lis' => [ 'style'=>"height: 70.8vh;" ] ])
            .( isset($nav_htm_fin) ? $nav_htm_fin : '' )
        ];

        // indice por articulo
        if( !empty($this->nav_art) ){
          $_['nav']['art'] = [ 'ico' => "nav_val", 'nom' => "Índice de Contenidos",
            'htm' => _doc_lis::nav( $this->nav_art, [ 'lis' => [ 'style'=>"height: 86.3vh;" ] ])
          ];
        }

        // pido botones del navegador
        $art = [];
        if( !empty($_['nav']) ){
          $this->ope['nav'] = _app_ope::bot($_,'nav');
        }        
        if( !empty($_['nav_fin']) ){
          $art['nav'] = $_['nav_fin'];
          $this->ope['nav_fin'] = _app_ope::bot($art,'nav');          
          $_['nav'] = array_merge($_['nav'],$_['nav_fin']);
        }// imprimo paneles
        foreach( $_['nav'] as $ide => $nav ){ 
          $this->nav .= _app_nav::art($ide,$nav); 
        }

        // pido botones de pantallas
        $art = [];
        if( !empty($_['win']) ){
          $this->ope['win'] = _app_ope::bot($_,'win');
        }
        if( !empty($_['win_fin']) ){
          $art['win'] = $_['win_fin'];
          $this->ope['win_fin'] = _app_ope::bot($art,'win');
          $_['win'] = array_merge($_['win'],$_['win_fin']);
        }// pido e imprimo pantallas
        foreach( $_['win'] as $ide => $win ){ 
          $this->win .= _app_win::art($ide,$win); 
        }

        // genero articulo/s
        $ele['art']['ide'] = !empty($_uri->cab) ? $_uri->cab : 'ini';
        $this->sec = _app_sec::art( $_['sec'], $ele );

        // ajusto diseño
        if( !empty($this->pan) || !empty($this->pie) ){
          $_ver = [];
          if( !empty($this->pan) ) $_ver []= 'pan';
          if( !empty($this->pie) ) $_ver []= 'pie';
          $this->ele['body']['data-ver'] = implode(',',$_ver);
        }

        // pido datos
        $this->dat = [        
          // iconos y caracteres
          '_ico',
          '_let',
          // tipos de variable
          '_var_tip',
          // valores: nombre + descripcion + imagen
          '_dat_val'
        ];

        // cargo por esquemas
        foreach( $_api_dat as $esq => $est ){
          // cargo todas las estructuras de la base que empiecen por "_api.$esq"
          if( empty($est) ) $est = [];

          foreach( _lis::ite($est) as $ide ){
            $this->dat []= "_{$esq}_{$ide}";
          }     
        }
      }
    }
    
    // estructuras
    static function dat_est( string $esq, string $ide = NULL, string $ope = NULL ) : array | object {      
      global $_api;
      $_ = [];
      // cargo estructuras de un esquema por operadores
      if( empty($ide) ){

        if( !isset( $_api->dat_est[$esq] ) ){
          
          foreach( _dat::var("_api.dat_est",[ 'ver'=>"`esq`='{$esq}'", 'niv'=>['ide'], 'obj'=>"ope", 'red'=>"ope" ]) as $est => $_ope ){

            $_api->dat_est[$esq][$est] = _sql::est("_{$esq}",'ver',$est,'uni');

            $_api->dat_est[$esq][$est]->ope = $_ope;
          }
        }
        $_ = $_api->dat_est[$esq];
      }
      else{

        if( !isset($_api->dat_est[$esq][$ide]) ){ 

          if( is_object( $_api->dat_est[$esq][$ide] = _sql::est("_{$esq}",'ver',$ide,'uni') ) ){
            // busco operadores
            $_api->dat_est[$esq][$ide]->ope = _dat::var("_api.dat_est",[
              'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni"
            ]);
          }    
        }
        
        $_ = $_api->dat_est[$esq][$ide];        
      }
      // devuelvo operador
      if( !empty($ope) ){

        $_ = isset( $_->ope->$ope ) ? $_->ope->$ope : FALSE;
      }

      return $_;
    }// atributos 
    static function dat_atr( string $esq, string $est, string | array $ide = NULL ) : bool | array | object {      
      global $_api;

      if( !isset($_api->dat_atr[$esq]) ) $_api->dat_atr[$esq] = [];

      if( !isset($_api->dat_atr[$esq][$est]) ){

        $_api->dat_atr[$esq][$est] = !empty( _sql::est("_{$esq}",'lis',"_{$est}",'uni') ) ? _sql::atr("_{$esq}","_{$est}") : _sql::atr("_{$esq}",$est);
        
        // cargo operadores del atributo
        $_atr = &$_api->dat_atr[$esq][$est];
        foreach( _dat::var("_api.dat_atr",['ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'ele'=>'var' ]) as $_api_atr ){

          if( !empty($_api_atr->var) && isset($_atr[$i = $_api_atr->ide]) ){

            $_atr[$i]->var = _ele::jun($_atr[$i]->var, $_api_atr->var);
          }
        }
      }
      // todos
      if( empty($ide) ){
        $_ = $_api->dat_atr[$esq][$est];
      }// uno
      elseif( is_string($ide) ){
        $_ = isset($_api->dat_atr[$esq][$est][$ide]) ? $_api->dat_atr[$esq][$est][$ide] : FALSE;
      }// muchos
      else{
        $_ = [];
        foreach( $ide as $atr ){
          $_ []= isset($_api->dat_atr[$esq][$est][$atr]) ? $_api->dat_atr[$esq][$est][$atr] : FALSE;
        }
      }
      return $_;
    }// valores
    static function dat_val( string $esq, string $est = NULL, string $ide = NULL ) : bool | array | object {      
      global $_api;
      
      if( !isset($_api->dat_val[$esq]) ) $_api->dat_val[$esq] = [];

      if( empty($est) ){
        
        $_ = $_api->dat_val[$esq] = _dat::var("_api.dat_val",[ 
          'ver'=>"`esq`='{$esq}'", 'niv'=>["est"], 'obj'=>"ope", 'red'=>"ope" 
        ]);
      }
      else{

        if( !isset($_api->dat_val[$esq][$est]) ){

          $_api->dat_val[$esq][$est] = _dat::var("_api.dat_val",[ 
            'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni" 
          ]);
        }

        if( empty( $ide ) ){
        
          $_ = $_api->dat_val[$esq][$est];
        }
        elseif( isset($_api->dat_val[$esq][$est]->$ide) ){
  
          $_ = $_api->dat_val[$esq][$est]->$ide;
        }
        else{
  
          $_ = FALSE;
        }
      }
      return $_;
    }
        
    // controladores de valores variables
    static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
      global $_api;
      $_var = &$_api->var;
      $_ = [];
      
      if( empty($dat) ){
        if( !isset($_var[$esq]) ){
          $_var[$esq] = _dat::var("_api.var",[
            'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }elseif( empty($val) ){
        if( !isset($_var[$esq][$dat]) ){
          $_var[$esq][$dat] = _dat::var("_api.var",[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }else{
        if( !isset($_var[$esq][$dat][$val]) ){
          $_var[$esq][$dat][$val] = _dat::var("_api.var",[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 'niv'=>['ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }
      if( !empty($ide) ){
        $_ = isset($_var[$esq][$dat][$val][$ide]) ? $_var[$esq][$dat][$val][$ide] : [];
      }elseif( !empty($val) ){
        $_ = isset($_var[$esq][$dat][$val]) ? $_var[$esq][$dat][$val] : [];
      }elseif( !empty($dat) ){      
        $_ = isset($_var[$esq][$dat]) ? $_var[$esq][$dat] : [];
      }else{
        $_ = isset($_var[$esq]) ? $_var[$esq] : [];
      }

      return $_;
    }// incremendo del identificador para operadores-variable
    static function var_ide( string $ope ) : string {
      global $_api;

      if( !isset($_api->var_ide[$ope]) ) $_api->var_ide[$ope] = 0;

      $_api->var_ide[$ope]++;

      return $_api->var_ide[$ope];

    }// operaciones : ver, ...
    static function var_ope( string $tip, mixed $dat, mixed $ope = [], ...$opc ) : mixed {
      global $_api;
      $_ = [];
      switch( $tip ){
      case 'opc':
              
        if( !isset($_api->var_ope_opc[$tip][$dat[0]][$dat[1]]) ){

          $_dat = _dat::var( _api::_('var_ope'), [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );
    
          $_api->var_ope_opc[$tip][$dat[0]][$dat[1]] = _doc_opc::val( $_dat, $ope, ...$opc);
        }
    
        $_ = $_api->var_ope_opc[$tip][$dat[0]][$dat[1]];
        
        break;
      }
      return $_;
    }

    // cargo valores de un proceso : absoluto o con dependencias ( _api.dat->est ) 
    static function val( string | array $ope, mixed $dat = NULL ) : array {
      global $_api;
      $_ = [];
      if( is_array($ope) ){
        // cargo temporal
        foreach( $ope as $esq => $est_lis ){
          // recorro estructuras del esquema
          foreach( $est_lis as $est => $dat ){
            // recorro dependencias
            $dat_est = _app::dat_est($esq,$est,'est');
            
            foreach( ( !empty($dat_est) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
              // acumulo valores
              if( isset($dat->$ide) ){
                
                $_["{$esq}-{$ref}"] = $dat->$ide;
              }
            }                            
          }
        }
        $_api->dat []= $_;
      }
      return $_;
    }

    // tablero de la aplicacion
    static function tab( string $esq, string $est, array $ele = NULL ) : array | object {
      global $_api;

      if( !isset($_api->tab[$esq][$est]) ){
        $_api->tab[$esq][$est] = _dat::var("_api.tab",[ 'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'opc'=>'uni', 'ele'=>['ele','ope','opc'] ]);
      }
      // devuelvo tablero : ele + ope + opc
      $_ = $_api->tab[$esq][$est];

      // combino elementos
      if( isset($ele) ){
        $_ = $ele;
        if( !empty($_api->tab[$esq][$est]->ele) ){

          foreach( $_api->tab[$esq][$est]->ele as $eti => $atr ){
            
            $_[$eti] = isset($_[$eti]) ? _ele::jun( $atr, $_[$eti] ) : $atr;
          }
        }
      }
      return $_;
    }

    // tabla de la base 
    static function est( string $esq, string $est, array $ope = NULL ) : object {
      global $_api;

      if( !isset($_api->est[$esq][$est]) || isset($ope) ){

        // combinado        
        $_est = _dat::var("_api.est",[ 'ver'=>"`esq`='{$esq}' AND `ide`='{$est}'", 'obj'=>'ope', 'red'=>'ope',  'opc'=>'uni' ]);

        // cargo atributos por estructura de la base      
        $_atr = _dat::atr($esq,$est);

        // reemplazo atributos por defecto
        if( isset($ope['atr']) ){
          $_est->atr = _lis::ite($ope['atr']);
          // descarto columnas ocultas
          if( isset($_est->atr_ocu) ) unset($_est->atr_ocu);
        }
        if( empty($_est->atr) ){
          $_est->atr = !empty($_atr) ? array_keys($_atr) : [];
        }
        if( isset($ope['atr_ocu']) ){
          $_est->atr_ocu = _lis::ite($ope['atr_ocu']);
        }

        // calculo totales
        $_est->atr_cue = count($_est->atr);
            
        // descripciones
        $_val['tit'] = isset($ope['tit']);      
        $_val['det'] = isset($ope['det']);      

        // reemplazo e inicializo
        foreach( ['tit'=>['cic','gru'], 'det'=>['des','cic','gru']] as $i => $v ){

          foreach( $v as $e ){
            if( isset($ope["{$i}_{$e}"]) ){
            
              $_est->{"{$i}_{$e}"} = _lis::ite($ope["{$i}_{$e}"]);
            }
            elseif( ( !$_val[$i] || !in_array($e,$ope[$i]) ) && isset($_est->{"{$i}_{$e}"}) ){
              unset($_est->{"{$i}_{$e}"});
            }
          }
        }

        // ciclos y agrupaciones: busco descripciones + inicio de operadores      
        foreach( ['cic','gru'] as $ide ){

          if( isset($_est->{"tit_{$ide}"}) ){

            foreach( $_est->{"tit_{$ide}"} as $atr ){
              
              // inicio ciclo
              if( $ide == 'cic' ) $_est->cic_val[$atr] = -1;

              // busco descripciones
              if( isset( $_atr["{$atr}_des"] ) ){
                if( !isset($_est->{"det_{$ide}"}) ) $_est->{"det_{$ide}"}=[]; 
                $_est->{"det_{$ide}"} []= "{$atr}_des";
              }
            }
          }
        }

        $_api->est[$esq][$est] = $_est;
      }

      return $_api->est[$esq][$est];
    }
  }

  // peticion : hhtp://esq/cab/art/...val
  class _app_uri {

    public string $esq = '';

    public string $cab = '';

    public string $art = '';

    public string $val = '';    
    
    function __construct( string $esq = 'hol' ){

      // peticion
      $uri = !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '';
      
      // por separaciones
      $dat = explode('/',$uri);
      $this->esq = !empty($dat[0]) ? $dat[0] : $esq;
      $this->cab = !empty($dat[1]) ? $dat[1] : FALSE;
      $this->art = !empty($dat[2]) ? $dat[2] : FALSE;
      $this->val = !empty($dat[3]) ? $dat[3] : FALSE;      
    }

    // directorio : accesos por aplicacion
    function dir() : object {

      $_ = new stdClass();

      $_->rec = SYS_NAV."_/";
      
      $_->esq = SYS_NAV."{$this->esq}";
        
      $_->cab = "{$this->esq}/{$this->cab}";

      $_->ima = SYS_NAV."_/{$_->cab}/";

      if( !empty($this->art) ){

        $_->art = $_->cab."/{$this->art}";
      
        $_->ima .= "{$this->art}/";
      }

      return $_;
    }

    // sesion : datos por esquemas
    function ses() : array {
      
      $_ = [];

      foreach( $_REQUEST as $i => $v ){

        if( preg_match("/^{$this->esq}-/",$i) ) $_[$i] = $v;
      }

      return $_;      
    }
  }

  // operadpr : botonera + menu + articulo + glosario
  class _app_ope {

    static string $IDE = "_app_ope-";
    static string $EJE = "_app_ope.";

    // botones de acceso : nav + win
    static function bot( $dat, ...$opc ) : string {
      $_ide = self::$IDE."bot";
      $_eje = self::$EJE."bot";
      $_ = "";

      foreach( ( !empty($opc) ? $opc : [ 'nav', 'win' ] ) as $tip ){

        if( isset($dat[$tip]) ){

          foreach( $dat[$tip] as $ide => $art ){

            $eje_val = "$_eje('$tip','$ide');";

            if( is_string($art) ){

              $_ .= _doc::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_val ]);
            }
            elseif( is_array($art) ){

              if( isset($art[0]) ){

                $_ .= _doc::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_val ]);
              }
              elseif( isset($art['ico']) ){

                $_ .= _doc::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_val ]);
              }
            }
            elseif( is_object($art) && isset($art->ico) ){

              $_ .= _doc::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_val ]);
            }
          }
        }
      }
      return $_;
    }

    // menu principal : titulo + descripcion + listado > item = [icono] + enlace
    static function nav( string $esq, array $ele = [] ) : string {
      foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }          
      // armo listado de enlaces
      $_lis = [];
      foreach( _dat::var("_api.doc_cab",[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

        $ite_ico = !empty($_cab->ico) ? _doc::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

        $_lis_val = [];
        foreach( _dat::var("_api.doc_art",[ 'ver'=>"`esq`='$esq' AND `cab`='$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art ){

          $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

          if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

          $ele_val['href'] = SYS_NAV."/$_art->esq/$_art->cab/$_art->ide";

          $_lis_val []= "
          <a"._htm::atr($ele_val).">"
            .( !empty($_art->ico) ? _doc::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
            ."<p>"._doc::let($_art->nom)."</p>
          </a>";
        }
        $_lis []= [ 
          'ite'=>[ 'eti'=>"p", 'class'=>"mar_ver-1 let-tit let-4", 'htm'=>$ite_ico._doc::let($_cab->nom) ], 
          'lis'=>$_lis_val 
        ];
      }
      // reinicio opciones
      _ele::cla($ele['lis'],"nav");
      _ele::cla($ele['dep'],DIS_OCU);
      $ele['opc'] = [ 'tog', 'ver', 'cue' ];
      return _doc_lis::val($_lis,$ele);
    }

    // articulo por operador
    static function art( array $nav, string $esq, string $cab ) : string {
      $_ = "";      

      $agr = _htm::dat($nav->ope);

      $_art = _dat::var("_api.doc_art",[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

      $_ = "
      <article class='inf'>";
        // introduccion
        if( !empty($agr['htm_ini']) ){
          $_ .= $agr['htm_ini'];
        }
        else{ $_ .= "
          <h1>{$nav->nom}</h1>";
        }
        // listado de contenidos
        if( !empty($_art) ){ $_ .= "
  
          <nav class='lis'>";
            foreach( $_art as $art ){
              $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>"._doc::let($art->nom)."</a>";
              if( !empty($art->ope['tex']) ){
                $_ .= "            
                <div class='val nav'>
                  "._doc::tog_ico()."
                  {$art_url}
                </div>
                <div class='dat'>
                  "._htm::val($art->ope['tex'])."
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

    // glosarios por esquema
    static function ide( string $ide, array $ele = [] ) : string {
      
      $_ = [];
      $_ide = explode('.',$ide);      
      
      if( is_array( $tex = _dat::var('_api.doc_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

        foreach( $tex as $pal ){
          $_[ $pal->nom ] = $pal->des;
        }
      }

      // operadores : toggle + filtro
      if( !isset($ele['opc']) ) $ele['opc'] = [];

      return _doc_lis::ite($_,$ele);
    }

  }

  // pantalla emergente
  class _app_win {

    static string $IDE = "_app_win-";
    static string $EJE = "_app_win.";
    
    // pantalla : #sis > article[ide] > header + section
    static function art( string $ide, array $ope = [] ) : string {
      foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
      $_ = "";
      // identificador
      $ope['art']['ide'] = $ide;
      _ele::cla($ope['art'],'dis-ocu');

      $cab_ico = "";
      if( !empty($ope['ico']) ) $cab_ico = _doc::ico($ope['ico'],['class'=>"mar_hor-1"]);

      $cab_tit = "";
      if( !empty($ope['nom']) ) $cab_tit = "<h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>";

      if( !isset($ope['htm']) ){
        $ope['htm'] = '';
      }
      elseif( is_array($ope['htm']) ){ 
        $ope['htm'] = _ele::val( $ope['htm'] );
      }      

      $_ = "
      <article"._htm::atr($ope['art']).">

        <header"._htm::atr($ope['cab']).">
        
          {$cab_ico} {$cab_tit} "._doc::ico('eje_fin',[ 'title'=>'Cerrar', 'onclick'=>"_app_ope.bot('win');" ])."

        </header>

        <section"._htm::atr($ope['sec']).">

          {$ope['htm']}

        </section>

      </article>";
      
      return $_;
    }
  }

  // panel de navegacion
  class _app_nav {

    static string $IDE = "_app_nav-";
    static string $EJE = "_app_nav.";

    // pantalla : nav|article[ide] > header + section
    static function art( string $ide, array $ope = [] ) : string {
      foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
      $_ = "";
      // identificador
      $ope['nav']['ide'] = $ide;
      _ele::cla($ope['nav'],DIS_OCU);

      $cab_ico = "";
      if( !empty($ope['ico']) ) $cab_ico = _doc::ico($ope['ico'],['class'=>"mar_hor-1"]);

      $cab_tit = "";
      if( !empty($ope['nom']) ) $cab_tit = "<h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>";

      $eti_nav = isset($ope['nav']['eti']) ? $ope['nav']['eti'] : 'nav';

      $eti_sec = isset($ope['sec']['eti']) ? $ope['sec']['eti'] : 'section';

      if( !isset($ope['htm']) ){
        $ope['htm'] = '';
      }
      elseif( is_array($ope['htm']) ){ 
        $ope['htm'] = _ele::val( $ope['htm'] );
      }

      $_ = "
      <$eti_nav"._htm::atr($ope['nav']).">

        <header"._htm::atr($ope['cab']).">
        
          {$cab_ico} {$cab_tit} "._doc::ico('eje_fin',[ 'title'=>'Cerrar', 'onclick'=>"_app_ope.bot('nav');" ])."

        </header>

        <$eti_sec"._htm::atr($ope['sec']).">

          {$ope['htm']}

        </$eti_sec>

      </$eti_nav>";
      
      return $_;
    }
  }

  // seccion principal
  class _app_sec {

    static string $IDE = "_app_sec-";
    static string $EJE = "_app_sec.";

    // genero articulo por seccion principal : main > aside + article
    static function art( string | array $dat, array $ele = [] ) : string {
      $_ = "";
      // contenido directo
      if( is_string($dat) ){ $_ .= "
        <article"._htm::atr( isset($ele['art']) ? $ele['art'] : [] ).">
          $dat
        </article>";
      }
      // listado de articulos
      else{
        foreach( $dat as $ide => $art ){
          
          if( isset($art['htm'])){ $art['ide'] = $ide; $_ .= "
            <article"._htm::atr($art).">
              {$art['htm']}
            </article>";
          }
        }
      }
      return $_;
    }// genero secciones del artículo por indices
    static function art_nav( string $ide ) : string {

      $_ = "";

      $_ide = explode('.',$ide);

      $_nav = _dat::var("_api.doc_nav",[ 'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 'nav'=>'pos' ]);

      if( isset($_nav[1]) ){

        foreach( $_nav[1] as $nv1 => $_nv1 ){ $_ .= "
          
          <h2 id='_{$nv1}-'>"._doc::let($_nv1->nom)."</h2>
          <section>";
            if( isset($_nav[2][$nv1]) ){
              foreach( $_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "
  
            <h3 id='_{$nv1}-{$nv2}-'>"._doc::let($_nv2->nom)."</h3>
            <section>";
              if( isset($_nav[3][$nv1][$nv2]) ){
                foreach( $_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "
  
              <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>"._doc::let($_nv3->nom)."</h4>
              <section>";
                if( isset($_nav[4][$nv1][$nv2][$nv3]) ){
                  foreach( $_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "
  
                <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>"._doc::let($_nv4->nom)."</h5>
                <section>";
                  if( isset($_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                    foreach( $_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "
  
                  <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>"._doc::let($_nv5->nom)."</h6>
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