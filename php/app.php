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
    // datos
    

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
      
      // pido datos por aplicacion: calendario
      $this->_dat = [ 'fec'=>['mes','sem','dia'] ];

      // botones : navegacion + pantalla
      $this->ope['ini'] = _doc::ico('ses',[ 'eti'=>"a", 'href'=>SYS_NAV."/{$_uri->esq}", 'title'=>"Inicio..." ]);

      // pido contenido por aplicacion
      if( file_exists($rec = "php/$_uri->esq/app.php") ){

        // inicializo elemento del articulo
        $_ = [ 'sec'=>"", 'nav'=>[], 'nav_fin'=>[], 'win'=>[], 'win_fin'=>[] ];

        $ele = [ 'art'=>[] ];

        require_once($rec);

        // menu principal
        $_['nav']['cab'] = [ 'ico'=>"nav", 'nom'=>"Menú Principal",
          'htm' => 
            ( isset($nav_htm_ini) ? $nav_htm_ini : '' )
            ._app_ope::nav($_uri->esq, [ 'lis' => [ 'style'=>"height: 71.5vh;" ] ])
            .( isset($nav_htm_fin) ? $nav_htm_fin : '' )
        ];

        // indice por articulo
        if( !empty($this->nav_art) ){
          $_['nav']['art'] = [ 'ico' => "nav_val", 'nom' => "Índice de Contenidos",
            'htm' => _doc_lis::nav( $this->nav_art, [ 'lis' => [ 'style'=>"height: 86.5vh;" ] ])
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
      }
    }

    // datos de la base
    public function dat() : array {      
      $_ = [
        // iconos y caracteres
        '_ico',
        '_let',
        // tipos de variable
        '_var_tip',
        // valores: nombre + descripcion + imagen
        '_dat_val',
      ];
      // cargo por esquemas
      if( !empty($this->_dat) ){
        foreach( $this->_dat as $esq => $est ){
          // cargo todas las estructuras de la base que empiecen por "_api.esq"
          if( empty($est) ){
            $est = [];
          }
          foreach( _lis::ite($est) as $ide ){            
            $_ []= "_{$esq}_{$ide}";
          }     
        }
      }
      return $_;
    }// estructuras
    static function dat_est( string $esq, string $ide = NULL, string $ope = NULL ) : array | object {      
      global $_api;
      $_ = [];
      // cargo estructuras de un esquema por operadores
      if( empty($ide) ){

        if( !isset( $_api->_dat_est[$esq] ) ){
          
          foreach( _dat::var("_api.dat_est",[ 'ver'=>"`esq`='{$esq}'", 'niv'=>['ide'], 'obj'=>"ope", 'red'=>"ope" ]) as $est => $_ope ){

            $_api->_dat_est[$esq][$est] = _sql::est("_{$esq}",'ver',$est,'uni');

            $_api->_dat_est[$esq][$est]->ope = $_ope;
          }
        }
        $_ = $_api->_dat_est[$esq];
      }
      else{

        if( !isset($_api->_dat_est[$esq][$ide]) ){ 

          if( is_object( $_api->_dat_est[$esq][$ide] = _sql::est("_{$esq}",'ver',$ide,'uni') ) ){
            // busco operadores
            $_api->_dat_est[$esq][$ide]->ope = _dat::var("_api.dat_est",[
              'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni"
            ]);
          }    
        }
        
        $_ = $_api->_dat_est[$esq][$ide];        
      }
      // devuelvo operador
      if( !empty($ope) ){

        $_ = isset( $_->ope->$ope ) ? $_->ope->$ope : FALSE;
      }

      return $_;
    }// atributos 
    static function dat_atr( string $esq, string $est, string | array $ide = NULL ) : bool | array | object {      
      global $_api;

      if( !isset($_api->_dat_atr[$esq]) ) $_api->_dat_atr[$esq] = [];

      if( !isset($_api->_dat_atr[$esq][$est]) ){

        $_api->_dat_atr[$esq][$est] = !empty( _sql::est("_{$esq}",'lis',"_{$est}",'uni') ) ? _sql::atr("_{$esq}","_{$est}") : _sql::atr("_{$esq}",$est);
        
        // cargo operadores del atributo
        $_atr = &$_api->_dat_atr[$esq][$est];
        foreach( _dat::var("_api.dat_atr",['ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'ele'=>'var' ]) as $_api_atr ){

          if( !empty($_api_atr->var) && isset($_atr[$i = $_api_atr->ide]) ){

            $_atr[$i]->var = _ele::jun($_atr[$i]->var, $_api_atr->var);
          }
        }
      }
      // todos
      if( empty($ide) ){
        $_ = $_api->_dat_atr[$esq][$est];
      }// uno
      elseif( is_string($ide) ){
        $_ = isset($_api->_dat_atr[$esq][$est][$ide]) ? $_api->_dat_atr[$esq][$est][$ide] : FALSE;
      }// muchos
      else{
        $_ = [];
        foreach( $ide as $atr ){
          $_ []= isset($_api->_dat_atr[$esq][$est][$atr]) ? $_api->_dat_atr[$esq][$est][$atr] : FALSE;
        }
      }
      return $_;
    }// valores
    static function dat_val( string $esq, string $est = NULL, string $ide = NULL ) : bool | array | object {      
      global $_api;
      
      if( !isset($_api->_dat_val[$esq]) ) $_api->_dat_val[$esq] = [];

      if( empty($est) ){
        
        $_ = $_api->_dat_val[$esq] = _dat::var("_api.dat_val",[ 
          'ver'=>"`esq`='{$esq}'", 'niv'=>["est"], 'obj'=>"ope", 'red'=>"ope" 
        ]);
      }
      else{

        if( !isset($_api->_dat_val[$esq][$est]) ){

          $_api->_dat_val[$esq][$est] = _dat::var("_api.dat_val",[ 
            'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'obj'=>"ope", 'red'=>"ope", 'opc'=>"uni" 
          ]);
        }

        if( empty( $ide ) ){
        
          $_ = $_api->_dat_val[$esq][$est];
        }
        elseif( isset($_api->_dat_val[$esq][$est]->$ide) ){
  
          $_ = $_api->_dat_val[$esq][$est]->$ide;
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
      $_var = &$_api->_var;
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

      if( !isset($_api->_ide[$ope]) ) $_api->_ide[$ope] = 0;

      $_api->_ide[$ope]++;

      return $_api->_ide[$ope];

    }// operaciones : ver, ...
    static function var_ope( string $tip, mixed $dat, mixed $ope = [], ...$opc ) : mixed {
      global $_api;
      $_ = [];
      switch( $tip ){
      case 'opc':
              
        if( !isset($_api->_var_ope_opc[$tip][$dat[0]][$dat[1]]) ){

          $_dat = _dat::var( _api::_('var_ope'), [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );
    
          $_api->_var_ope_opc[$tip][$dat[0]][$dat[1]] = _doc_lis::opc( $_dat, $ope, ...$opc);
        }
    
        $_ = $_api->_var_ope_opc[$tip][$dat[0]][$dat[1]];
        
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
        $_api->_dat []= $_;
      }
      return $_;
    }

    // tablero de la aplicacion
    static function tab( string $esq, string $est, array $ele = NULL ) : array | object {
      global $_api;

      if( !isset($_api->_tab[$esq][$est]) ){
        $_api->_tab[$esq][$est] = _dat::var("_api.tab",[ 'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 'opc'=>'uni', 'ele'=>['ele','ope','opc'] ]);
      }
      // devuelvo tablero : ele + ope + opc
      $_ = $_api->_tab[$esq][$est];

      // combino elementos
      if( isset($ele) ){
        $_ = $ele;
        if( !empty($_api->_tab[$esq][$est]->ele) ){

          foreach( $_api->_tab[$esq][$est]->ele as $eti => $atr ){
            
            $_[$eti] = isset($_[$eti]) ? _ele::jun( $atr, $_[$eti] ) : $atr;
          }
        }
      }
      return $_;
    }

    // tabla de la base 
    static function est( string $esq, string $est, array $ope = NULL ) : object {
      global $_api;

      if( !isset($_api->_est[$esq][$est]) || isset($ope) ){

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

        $_api->_est[$esq][$est] = $_est;
      }

      return $_api->_est[$esq][$est];
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

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
                  "._doc_val::tog_ico()."
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
    }
    // genero secciones del artículo por indices
    static function nav( string $ide ) : string {

      $_ = "";

      $_ide = explode('.',$ide);

      $_nav = _dat::var("_api.doc_nav",[ 'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 'nav'=>'pos' ]);

      if( isset($_nav[1]) ){

        foreach( $_nav[1] as $nv1 => $_nv1 ){ $_ .= "
          
          <?=_doc_val::tog(['eti'=>'h2','id'=>'_{$nv1}-','htm'=>'"._doc::let($_nv1->nom)."'])?>
          <section>";
            if( isset($_nav[2][$nv1]) ){
              foreach( $_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "
  
            <?=_doc_val::tog(['eti'=>'h3','id'=>'_{$nv1}-{$nv2}-','htm'=>'"._doc::let($_nv2->nom)."'])?>
            <section>";
              if( isset($_nav[3][$nv1][$nv2]) ){
                foreach( $_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "
  
              <?=_doc_val::tog(['eti'=>'h4','id'=>'_{$nv1}-{$nv2}-{$nv3}-','htm'=>'"._doc::let($_nv3->nom)."'])?>
              <section>";
                if( isset($_nav[4][$nv1][$nv2][$nv3]) ){
                  foreach( $_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "
  
                <?=_doc_val::tog(['eti'=>'h5','id'=>'_{$nv1}-{$nv2}-{$nv3}-{$nv4}-','htm'=>'"._doc::let($_nv4->nom)."'])?>
                <section>";
                  if( isset($_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                    foreach( $_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "
  
                  <?=_doc_val::tog(['eti'=>'h6','id'=>'_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-','htm'=>'"._doc::let($_nv5->nom)."'])?>
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

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  // datos : valores( nom + des + ima ) + seleccion( col + ima + tex + num + fec ) + opciones( esq + est + atr + val ) + imagen( ide + fic )
  class _app_dat {

    static string $IDE = "_app_dat-";
    static string $EJE = "_app_dat.";

    // armo valores ( esq.est ): nombre, descripcion, imagen
    static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );

      $dat_var = _dat::var($esq,$est,$dat);
      $dat_val = _dat::val($esq,$est);

      // armo titulo : nombre <br> detalle
      if( $tip == 'ver' ){

        $_ = ( isset($dat_val->nom) ? _obj::val($dat_var,$dat_val->nom) : "" ).( isset($dat_val->des) ? "\n"._obj::val($dat_var,$dat_val->des) : "");
      }
      // por atributos con texto : nom + des + ima 
      elseif( isset($dat_val->$tip) ){

        if( is_string($dat_val->$tip) ) $_ = _obj::val($dat_var,$dat_val->$tip);
      }
      // armo imagen
      if( $tip == 'ima' ){

        // cargo titulo
        $tit = _app_dat::val('ver',"$esq.$est",$dat);
        
        $_ = _doc::ima( [ 'style'=>$_, 'title'=>$tit ], $ele );
      }
      // armo variable
      elseif( $tip == 'var' ){
        
        $_ = "";

      }
      // armo textos
      elseif( !!$ele ){  

        if( empty($ele['eti']) ) $ele['eti'] = 'p';
        $ele['htm'] = _doc::let($_);
        $_ = _htm::val($ele);
      }    

      return $_;
    }

    // valor por seleccion ( esq.est.atr ) : texto, variable, icono, ficha, colores, html
    static function ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );
      // parametros: "esq.est.atr" 
      $ide = 'NaN';
      if( !is_object($dat) ){

        $ide = $dat;
        $dat = _dat::var($esq,$est,$dat);
      }
      elseif( isset($dat->ide) ){

        $ide = $dat->ide;
      }

      if( is_object($dat) && isset($dat->$atr) ){
        
        $_atr = _dat::atr($esq,$est,$atr);
        // variable por tipo
        if( $tip == 'var' ){
          $_var = $_atr->var;
          $_var['val'] = $dat->$atr;
          $_ = _htm::val($_val);
        }// proceso texto con letras
        elseif( $tip == 'htm' ){

          $_ = _doc::let($dat->$atr);
        }// color en atributo
        elseif( $tip == 'col' ){
          
          if( $col = _dat::val_ver('col',$esq,$est,$atr) ){
            $_ = "<div"._htm::atr(_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
          }else{
            $_ = "<div class='err fon-roj' title='No existe el color para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
          }
        }// imagen en atributo
        elseif( $tip == 'ima' ){

          if( !empty($_atr->var['dat']) ){
            $_ima_ide = explode('.',$_atr->var['dat']);
            $_ima['esq'] = $_ima_ide[0];
            $_ima['est'] = $_ima_ide[1];
          }
          if( !empty($_ima) || !empty( $_ima = _dat::val_ver('ima',$esq,$est,$atr) ) ){
            
            $_ = _doc::ima( $_ima['esq'], $_ima['est'], $dat->$atr, $ele );
          }
          else{
            $_ = "<div class='err fon-roj' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
          }
        }
        elseif( $tip == 'tip' || in_array($tip,['num','tex','fec']) ){

          if( $tip=='tip' ){
            $tip = $_atr->var_dat;
          }
          if( $tip == 'num' ){
            $_ = "<n"._htm::atr($ele).">{$dat->$atr}</n>";
          }
          elseif( $tip == 'tex' ){
            $_ = "<p"._htm::atr($ele).">"._doc::let($dat->$atr)."</p>";
          }
          elseif( $tip == 'fec' ){
            $ele['value'] = $dat->$atr;
            $_ = "<time"._htm::atr($ele).">"._doc::let($dat->$atr)."</time>";
          }
          else{
            $_ = _doc::let($dat->$atr);
          }
        }
        else{

          $_ = $dat->$atr;
        }
      }
      else{
        if( is_null($dat->$atr) ){
          $_ = "<p title='Valor nulo para el objeto _{$esq}.{$est}[{$ide}].{$atr}'></p>";
        }else{
          $_ = "<div class='err fon-roj' title='No existe el atributo {$atr} para el objeto _{$esq}.{$est}[{$ide}]'>{-_-}</div>";
        }      
      }      

      return $_;
    }

    // selector por operador : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
    static function opc( string $ide, mixed $dat, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."opc(";
      $_eje = self::$EJE."opc(";
      // opciones
      $opc_esq = in_array('esq',$opc);
      $opc_est = in_array('est',$opc);
      $opc_val = in_array('val',$opc);
      $opc_ope_tam = in_array('ope_tam',$opc) ? "max-width: 6rem;" : NULL;
      // capturo elemento select
      if( !isset($ope['ope']) ) $ope['ope'] = [];
      if( empty($ope['ope']['name']) ) $ope['ope']['name'] = $ide;
      if( empty($ope['ope']['title']) ) $ope['ope']['title'] = "Seleccionar el Atributo de la Estructura...";
      // valor seleccionado
      if( isset($ope['val']) ) $_val = explode('-',$ope['val']);
      // cargo selector de estructura
      $ele_eje = isset($ope['ope']['onchange']) ? $ope['ope']['onchange'] : FALSE;
      $ele_val = [ 
        'eti'=>[ 'name'=>"val", 'title'=>"Seleccionar el valor buscado...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" ] 
      ];
      if( $opc_esq || $opc_est ){
        // operador por esquemas
        if( $opc_esq ){
          $dat_esq = [];
          $ele_esq = [ 
            'eti'=>[ 'name'=>"esq", 'title'=>"Seleccionar el Esquema de Datos...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" ] 
          ];
        }// operador por estructuras
        $ele_est = [ 
          'eti'=>[ 'name'=>"est", 'title'=>"Seleccionar la Estructura de datos...", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" ] 
        ];
        // operador por relaciones de atributo
        $ope['ope'] = _ele::eje($ope['ope'],'cam',$_eje."'atr',this);",'ini');
        if( !empty($opc_ope_tam) ) $ope['ope'] = _ele::css($ope['ope'],$opc_ope_tam);
        // oculto items
        $cla = DIS_OCU;
        // copio eventos
        if( $ele_eje ) $ele_est['eti'] = _ele::eje($ele_est['eti'],'cam',$ele_eje);
        // aseguro valores seleccionado
        if( $opc_esq ){          
          if( isset($_val[0]) ) $ele_esq['eti']['val'] = $_val[0];
          if( isset($_val[1]) ) $ele_est['eti']['val'] = $_val[1];
          if( isset($_val[2]) ) $ope['ope']['val'] = $_val[2];
          if( isset($_val[3]) ){ $ele_val['eti']['val'] = $_val[3]; $dat_val = []; }
        }else{
          if( isset($_val[0]) ) $ele_est['eti']['val'] = $_val[0];
          if( isset($_val[1]) ) $ope['ope']['val'] = $_val[1];
          if( isset($_val[2]) ){ $ele_val['eti']['val'] = $_val[2]; $dat_val = []; }
        }
      }else{
        if( isset($_val[0]) ) $ope['ope']['val'] = $_val[0];
        if( isset($_val[1]) ){ $ele_val['eti']['val'] = $_val[1]; $dat_val = []; }
      }
      // de donde tomo los datos? esquemas => estructuras
      $_ = "";
      // atributos por relacion
      $dat_ope = [];
      // estructuras
      $dat_est = [];
      // agrupador
      $ele_ope['gru'] = [];
      $ele_ope['eti'] = $ope['ope'];
      // proceso identificador de dato
      if( is_string($dat) || _obj::pos($dat) ){
        $_ide = is_string($dat) ? explode('.',$dat) : $dat;
        $dat = [ $_ide[0] => [ $_ide[1] ] ];
      }
      // opciones por operador de estructura
      $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
        $_ = [];
        // atributos parametrizados
        if( ( $dat_opc_ide = _dat::est_ope($esq,$est,$ide) ) && is_array($dat_opc_ide) ){  
          // recorro atributos + si tiene el operador, agrego la opcion      
          foreach( $dat_opc_ide as $atr ){
            // cargo atributo
            $_atr = _dat::atr($esq,$est,$atr);
            // identificador
            $dat = "{$esq}.";
            if( !empty($_atr->var['dat']) ){ $dat = $_atr->var['dat']; }else{ $dat .= _dat::atr_est($esq,$est,$atr); }  
            $_ []= [                 
              'data-esq'=>$esq, 'data-est'=>$est, 'data-ide'=>$dat, 'value'=>"{$esq}.{$est}.{$atr}", 'class'=>$cla, 'htm'=>$_atr->nom 
            ];
          }
        }
        return $_;
      };
      $val_cla = isset($cla);
      $val_est = isset($ele_est['eti']['val']) ? $ele_est['eti']['val'] : FALSE;
      foreach( $dat as $esq_ide => $est_lis ){
        // cargo esquema [opcional]
        if( $opc_esq ){
          $dat_esq []= $esq_ide;
        }// recorro estructura/s por esquema
        foreach( $est_lis as $est_ide ){
          // busco estructuras dependientes
          if( $dat_opc_est = _dat::est_ope($esq_ide,$est_ide,'est') ){
            // recorro dependencias de la estructura
            foreach( $dat_opc_est as $dep_ide ){                
              // datos de la estructura relacional
              $_est = _dat::est($esq_ide,$dep_ide);
              $ite_val = "{$esq_ide}.{$dep_ide}";
              // pido opciones por estructura y oculto en caso de haber valor seleccionado por estructura
              if( !empty( $_opc_val = $_opc_ite($esq_ide, $dep_ide, $ide, $val_cla && ( !$val_est || $val_est != $ite_val ) ? $cla : "") ) ){
                // con selector de estructura
                if( $opc_est ){
                  // cargo opcion de la estructura
                  $dat_est[] = [ 'value'=>$ite_val, 'htm'=>isset($_est->nom) ? $_est->nom : $est ];
                  // cargo todos los atributos a un listado general
                  array_push($dat_ope, ...$_opc_val);

                }// por agrupador
                else{
                  // agrupo por estructura
                  $ele_ope['gru'][$_est->ide] = isset($_est->nom) ? $_est->nom : $est;
                  // cargo atributos por estructura
                  $dat_ope[$_est->ide] = $_opc_val;
                }                    
              }
            }
          }// estructura sin dependencias
          else{
            $dat_ope[] = $_opc_ite($esq_ide, $dep_ide, $ide);
          }
        }
      }
      // selector de esquema [opcional]
      if( $opc_esq ){
        $_ .= _doc_lis::opc($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
      }
      // selector de estructura [opcional]
      if( $opc_esq || $opc_est ){
        $_ .= _doc_lis::opc($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
      }
      // selector de atributo con nombre de variable por operador
      $_ .= _doc_lis::opc($dat_ope,$ele_ope,'nad');
      
      // selector de valor por relacion
      if( $opc_val ){
        // copio eventos
        if( $ele_eje ) $ele_val['eti'] = _ele::eje($ele_val['eti'],'cam',$ele_eje);
        $_ .= "
        <div class='val'>
          <c class='sep'>:</c>
          "._doc_lis::opc( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
          <span class='ico'></span>
        </div>";
      }

      return $_;
    }

    // conteos : por valores de estructura relacionada por atributo
    static function cue( string $tip, string | array $dat, array $ope = [], ...$opc ) : string | array {
      $_ = "";
      $_ide = self::$IDE."cue";
      $_eje = self::$EJE."cue";

      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq-$est";
      }

      switch( $tip ){        
      case 'dat': 
        $_ = [];
        
        // -> por esquemas
        foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){

          // -> por estructuras
          foreach( $est_lis as $est_ide ){

            // -> por dependencias ( est_atr )
            foreach( ( !empty($dat_opc_est = _dat::est_ope($esq,$est_ide,'est')) ? $dat_opc_est : [ $est_ide ] ) as $est ){

              // armo listado para aquellos que permiten filtros
              if( $dat_opc_ver = _dat::est_ope($esq,$est,'ver') ){
                // nombre de la estructura
                $est_nom = _dat::est($esq,$est)->nom;                
                $htm_lis = [];
                foreach( $dat_opc_ver as $atr ){
                  // armo relacion por atributo
                  $rel = _dat::atr_est($esq,$est,$atr);
                  // busco nombre de estructura relacional
                  $rel_nom = _dat::est($esq,$rel)->nom;
                  // armo listado : form + table por estructura
                  $htm_lis []= [ 
                    'ite'=>$rel_nom, 'htm'=>"
                    <div class='var mar_izq-2 dis-ocu'>
                      "._app_dat::cue('est',"{$esq}.{$est}.{$atr}",$ope)."
                    </div>"
                  ];
                }
                $_[] = [ 'ite'=> $est_nom, 'lis'=> $htm_lis ];
              }
            }
          }
        }
        break;
      case 'est':
        if( isset($ope['ide']) ) $_ide = $ope['ide'];
        // armo relacion por atributo
        $ide = !empty($atr) ? _dat::atr_est($esq,$est,$atr) : $est;
        $_ = "
        <!-- filtros -->
        <form class='val'>

          "._doc::var('val','ver',[ 
            'nom'=>"Filtrar", 
            'id'=>"{$_ide}-ver {$esq}-{$ide}",
            'htm'=>_doc_val::ver('tex', [ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
          ])."
        </form>
  
        <!-- valores -->
        <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
          <tbody>";
          foreach( _dat::var($esq,$ide) as $ide => $_var ){
          
            $ide = isset($_var->ide) ? $_var->ide : $ide;
  
            if( !empty($atr) ){
              $ima = !empty( $_ima = _dat::val_ver('ima',$esq,$est,$atr) ) ? _doc::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
            }
            else{
              $ima = _doc::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
            }$_ .= "
            <tr data-ide='{$ide}'>
              <td data-atr='ima'>{$ima}</td>
              <td data-atr='ide'>"._doc::let($ide)."</td>
              <td data-atr='nom'>"._doc::let(isset($_var->nom) ? $_var->nom : '')."</td>
              <td><c class='sep'>:</c></td>
              <td data-atr='tot' title='Cantidad seleccionada...'><n>0</n></td>
              <td><c class='sep'>=></c></td>
              <td data-atr='por' title='Porcentaje sobre el total...'><n>0</n><c>%</c></td>
            </tr>";
          } $_ .= "
          </tbody>
        </table>";
        break;
      }

      return $_;
    }

    // ficha : imagenes por valor + relaciones por estructura
    static function fic( string $ide, mixed $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."fic";
      $_eje = self::$EJE."fic";      
      // proceso estructura
      extract( _dat::ide($ide) );

      $_fic = _dat::est_ope($esq,$est,'fic');

      if( isset($_fic->ide) ){
        
        if( !empty($val) ) $val = _dat::var($esq,$est,$val); 
      
        $ima = _dat::val_ver('ima',$esq,$est,$atr = $_fic->ide);          
        
        $_ .= "
        <div class='val' data-ima='{$ima['ide']}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>";
          if( !empty($val) ){ 
            $_ .= _doc::ima($esq,$est,$val); 
          } $_ .= "
        </div>

        <c class='sep'>=></c>
    
        <c class='lis ini'>{</c>";
        foreach( $_fic->atr as $atr ){
          
          $_ima = _dat::val_ver('ima',$esq,$est,$atr); 
          $_ .= "
          <div class='val mar_hor-1' data-ima='{$_ima['ide']}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>";
            if( !empty($val) ){ 
              $_ .= _doc::ima($esq,$ima,$val->$atr); 
            }$_ .= "
          </div>";
        }$_ .= "
        <c class='lis fin'>}</c>";
      }     

      return $_;
    }

    // listado de ...atributo: valor
    static function atr( mixed $dat, array $ope = [], ...$opc ) : string {
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";
      $_ = "";

      $_ide = $_ide;
      if( is_string($dat) ){
        $var_dat = _dat::ide($dat,$var_dat);        
        $esq = $var_dat['esq'];
        $est = $var_dat['est'];                
        $_ide = "{$_ide} _$esq.$est";
        // datos
        $dat = _dat::var($dat,$ope);
      }

      // estructura
      $ide = _dat::ide($ope['est']);
      unset($ope['est']);
      
      // atributos
      $dat_atr = _dat::atr($ide['esq'],$ide['est']);
      if( isset($ope['atr']) ){
        $lis_atr = $ope['atr'];
        unset($ope['atr']);
      }
      else{
        $lis_atr = array_keys($dat_atr);
      }

      // dato: objeto
      if( !is_object($dat) ){
        $dat = _dat::var($ide['esq'],$ide['est'],$dat);
      }
      $ite = [];
      foreach( $lis_atr as $atr ){
        if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ $ite []= "
          <b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> "._doc::let($dat->$atr);
        }
      }

      $_ = _doc_lis::ite($ite,$ope);          

      return $_;
    }

    // listado : imagen + nombre > ...atributos
    static function lis( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
      
      $_ = [];
      // tipos de lista
      $tip = !empty($ele['tip']) ? $ele['tip'] : 'val';
      // atributos de la estructura
      $atr = _lis::ite($atr);
      // descripciones : cadena con " ()($)atr() "
      $des = !empty($ele['des']) ? $ele['des'] : FALSE;
      // elemento de lista
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      _ele::cla($ele['lis'],"ite",'ini');
      $ele['lis']['data-ide'] = "$esq.$est";

      if( class_exists($_cla = "_$esq") ){

        foreach( $_cla::_($est) as $pos => $_dat ){ 
          $htm = 
          _doc::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
          <dl>
            <dt>
              ".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " "._obj::val($_dat,$des) : "" )."
            </dt>";
            foreach( $atr as $ide ){ 
              if( isset($_dat->$ide) ){ $htm .= "
                <dd>".( preg_match("/_des/",$ide) ? "<q>"._doc::let($_dat->$ide)."</q>" : _doc::let($_dat->$ide) )."</dd>";
              }
            }$htm .= "
          </dl>";
          $_ []= $htm;
        }
      }

      return _doc_lis::$tip( $_, $ele, ...$opc );
    }
  }

  // valor : abm + acumulados, sumatorias, filtros, cuentas
  class _app_val {

    static string $IDE = "_app_val-";
    static string $EJE = "_app_val.";

    static array $OPE = [
      'acu'=>['nom'=>"Acumulados" ], 
      'ver'=>['nom'=>"Selección"  ], 
      'sum'=>['nom'=>"Sumatorias" ], 
      'cue'=>['nom'=>"Conteos"    ]
    ];
    static array $BOT = [
      'ver'=>['nom'=>"Ver"        ], 
      'agr'=>['nom'=>"Agregar"    ], 
      'mod'=>['nom'=>"Modificar"  ], 
      'eli'=>['nom'=>"Eliminar"   ]
    ];

    // abm de la base
    static function abm( string $tip, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";
      $_ope = self::$BOT;
      switch( $tip ){
      case 'nav':
        $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
        if( !empty($url) ){
          $url_agr = "{$url}/0";
          $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
        }
        $_ .= "
        <fieldset class='ope' abm='{$tip}'>    
          "._doc::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']->nom, 'onclick'=>"{$_eje}('ver');"])."
  
          "._doc::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']->nom, 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."
  
          "._doc::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']->nom, 'onclick'=>"{$_eje}('eli');"])."
        </fieldset>";
        break;
      case 'abm':
        $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
        $_ = "
        <fieldset class='ope mar-2 esp-ara'>

          "._doc::ico('dat_val', [ 'eti'=>"button", 'title'=>$_ope[$tip]->nom, 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

          if( in_array('eli',$ope['opc']) ){

            $_ .= _doc::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']->nom, 'onclick'=>"{$_eje}('eli');" ]);
          }$_ .= "

          "._doc::ico('dat_act', [ 'eti'=>"button", 'title'=>$_ope['fin']->nom, 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

        </fieldset>";
        break;              
      case 'est':
        $_ .= "
        <fieldset class='ope'>    
          "._doc::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
          
          "._doc::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
        </fieldset>";                  
        break;                
      }

      return $_;
    }    

    // filtros : dato + atributo + posicion + texto
    static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ver";
      $_eje = self::$EJE."ver";

      $_ite = function( $ide, $dat=[], $ele=[] ){

        if( !empty($ele['ope']['id']) ) $ele['ope']['id'] .= "-{$ide}"; 

        // impido tipos ( para fechas )
        if( ( $ide == 'inc' || $ide == 'cue' ) && isset($ele['ope']['_tip']) ) unset($ele['ope']['_tip']);
        
        // combino elementos
        if( !empty($dat[$ide]) && is_array($dat[$ide]) ) $ele['ope'] = _ele::jun($ele['ope'],$dat[$ide]);

        return $ele;
      };
      switch( $tip ){
      // datos : estructura => valores 
      case 'dat':
        // selector de estructura.relaciones para filtros
        array_push($opc,'est','val');
        $_ .= _doc::var('doc',"val.ver.dat",[ 
          'ite'=>[ 'class'=>"tam-mov" ], 
          'htm'=>_app_dat::opc('ver',$dat,$ele,...$opc)
        ]);
        break;
      // atributo : nombre : valor por tipo ( bool, numero, texto, fecha, lista, archivo, figura )
      case 'atr':
        break;
      // listados : desde + hasta
      case 'lis': 
        // por defecto
        if( empty($dat) ) $dat = [ 'ini'=>[], 'fin'=>[] ];

        // desde - hasta
        foreach( ['ini','fin'] as $ide ){

          if( isset($dat[$ide]) ) $_ .= _doc::var('doc', "val.ver.$ide", $_ite($ide,$dat,$ele));
        }
        $_ .= _app_val::ver('lim',$dat,$ele);
        break;
      // limites : incremento + cuantos ? del inicio | del final
      case 'lim':
        // cada
        if( isset($dat['inc']) ){
          $_ .= _doc::var('doc', "val.ver.inc", $_ite('inc',$dat,$ele));
        }
        // cuántos
        if( isset($dat['cue']) ){
          $_eje = "_doc.var('mar', this, 'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
          $ele['htm_fin'] = "
          <fieldset class='ope'>
            "._doc::ico('nav_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
            "._doc::ico('nav_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
          </fieldset>"; 
          $_ .=
          _doc::var('doc', "val.ver.cue", $_ite('cue',$dat,$ele) );
        }
        break;
      }

      return $_;
    }

    // acumulado : posicion + marcas + seleccion
    static function acu( array $dat, array $ope = [], array $opc = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."acu";
      $_eje = self::$EJE."acu";

      if( empty($opc) ) $opc = array_keys($dat);

      $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

      if( !empty($ope['ide']) ) $_ide = $ope['ide'];

      foreach( $opc as $ide ){        
        $_ .= _doc::var('doc',"val.acu.$ide", [
          'ope'=> [ 
            'id'=>"{$_ide}-{$ide}", 'val'=>isset($dat[$ide]) ? $dat[$ide] : NULL, 'onchange'=>$_eje_val
          ],
          'htm_fin'=>( !empty($ope['ope']['htm_fin']) ? $ope['ope']['htm_fin'] : '' ).( !empty($ope["var-{$ide}"]['htm_fin']) ? $ope["var-{$ide}"]['htm_fin'] : '' )
        ]);
      }
      if( !empty($ope['htm_fin']) ){
        $_ .= $ope['htm_fin'];
      }
      return $_;
    }

    // sumatorias
    static function sum(  string | array $dat, array $ope = [] ) : string {

      $_ = "";
      $_ide = self::$IDE."sum";
      $_eje = self::$EJE."sum";

      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq-$est";
      }

      if( isset($ope['ide']) ) $_ide = $ope['ide'];
      // operadores por esquemas
      foreach( _app::var($esq,'val','sum') as $ide => $ite ){
    
        $_ .= _doc::var($esq, ['val','sum',$ide], [          
          'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
          'htm_fin'=> !empty($ite['var_fic']) ? _app_dat::fic($ite['var_fic']) : ''
        ]);
      }    

      return $_;
    }
  }

  // tablero : opciones + posiciones + secciones
  class _app_tab {

    static array $OPE = [
      'val' => ['nom'=>"Valores"  ],
      'opc' => ['nom'=>"Opciones" ],
      'pos' => ['nom'=>"Posición" ],
      'ver' => ['nom'=>"Selección"]
    ];
    static string $IDE = "_app_tab-";
    static string $EJE = "_app_tab.";

    // operadores : valores + seleccion + posicion + opciones( posicion | secciones )
    static function ope( string $tip, string $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;

      // proceso datos del tablero
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";

      // por aplicacion : posicion + seleccion
      if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];

      switch( $tip ){
      // todos
      case 'tod':
        $ele['sec']['class'] = "mar_izq-2";
        foreach( $_ope as $ide => $_dat ){
          $_lis []= [
            'ite'=>[ 'eti'=>"h3", 'class'=>"mar-0", 'htm'=>$_dat['nom'] ],
            'htm'=>_app_tab::ope($ide,$dat,$ope,$ele,...$opc)
          ];
        }
        $_ = "
        <h2>Operadores</h2>

        "._doc_lis::val($_lis,[ 'opc'=>['tog'], 'ope'=>[ 'class'=>"mar_hor-1" ] ]);

        break;
      // valores : totales + acumulados + sumatorias
      case 'val':
        $ele['sec']['ide'] = $tip; $_ .= "
        <section"._htm::atr($ele['sec']).">

          <form ide='acu'>
            <fieldset class='inf ren'>
              <legend>Acumulados</legend>";

              $_ .= _doc::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ]);
              
              $_ .= _app_val::acu($ope[$tip],[ 
                'ide'=>$_ide, 
                'eje'=>"{$_eje}_acu(this);",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" ]
              ]);
              $_ .="
            </fieldset>
          </form>

          <form ide='sum'>

            <fieldset class='inf ren' data-esq='hol' data-est='kin'>
              <legend>Sumatorias del Kin</legend>

              "._app_val::sum('hol',[ 'ide'=>$_ide ])."

            </fieldset>
          </form>

        </section>";
        break;
      // Filtros : estructuras/db + posiciones + fechas
      case 'ver':
        $ele['sec']['ide'] = $tip; $_ .= "
        <section"._htm::atr($ele['sec']).">

          <form ide='dat'>
            <fieldset class='inf ren'>
              <legend>por Datos</legend>

              "._app_val::ver('dat', $ope['est'], [ 'ope'=>[ 'onchange'=>"$_eje('dat',this);" ] ], 'ope_tam')."

            </fieldset>
          </form>

          <form ide='pos'>
            <fieldset class='inf ren'>
              <legend>por Posiciones</legend>

              "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [
                'ope'=>[ '_tip'=>"num_int", 'min'=>"1", 'max'=>"999", 'id'=>"{$_ide}-pos", 'onchange'=>"$_eje('pos',this);" ] 
              ])."
            </fieldset>
          </form>

          <form ide='fec'>
            <fieldset class='inf ren'>
              <legend>por Fechas</legend>

              "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [ 
                'ope'=>[ '_tip'=>"fec_dia", 'id'=>"{$_ide}-fec", 'onchange'=>"$_eje('fec',this);" ] 
              ])."
            </fieldset>
            
          </form>

        </section>";          
        break;
      // Opciones : sección + posición
      case 'opc':
        if( !empty($ope['sec']) || !empty($ope['pos']) ){
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">";
            // secciones
            $tip = 'sec';
            $ele_ide = "{$_ide}-{$tip}";
            $ele_eve = "{$_eje}_{$tip}(this);";
            if( !empty($ope[$tip]) ){
              $ele['ope']['ide'] = $tip; $_ .= "
              <form"._htm::atr($ele['ope']).">
                <fieldset class='inf ren'>
                  <legend>Secciones</legend>";
                  // operadores globales
                  if( !empty($tab_sec = _app::var('doc','tab',$tip)) ){ $_ .= "
                    <div class='val'>";
                    foreach( _app::var('doc','tab',$tip) as $ide => $ite ){
                      if( isset($ope[$tip][$ide]) ){ 
                        $_ .= _doc::var('doc', "tab.$tip.$ide", [ 
                          'val'=>$ope[$tip][$ide], 
                          'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ] 
                        ]); 
                      }
                    }$_ .= "
                    </div>";
                  }
                  // operadores por aplicación
                  $_ .= _app_tab::opc($tip,$dat,$ope[$tip])."

                </fieldset>
              </form>";          
            }
            // posiciones
            $tip = 'pos';
            $ele_ide = "{$_ide}-{$tip}";
            $ele_eve = "{$_eje}_{$tip}(this);";
            if( !empty($ope[$tip]) ){ 
              $ele['ope']['ide'] = $tip; $_ .= "
              <form"._htm::atr($ele['ope']).">    
                <fieldset class='inf ren'>
                  <legend>Posiciones</legend>";
                  // bordes            
                  $ide = 'bor';
                  $_ .= _doc::var('doc', "tab.$tip.$ide",[
                    'val'=>isset($ope[$tip][$ide]) ? $ope[$tip][$ide] : 0,
                    'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
                  ]);                
                  // color de fondo - numero - texto - fecha
                  foreach( ['col','num','tex','fec'] as $ide ){                  
                    if( isset($ope[$tip][$ide]) ){
                      $_ .= _doc::var('doc', "tab.{$tip}.{$ide}", [
                        'id'=>"{$ele_ide}-{$ide}",
                        'htm'=>_app_dat::opc($ide, $ope['est'], [
                          'val'=>$ope[$tip][$ide], 
                          'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                        ])
                      ]);                      
                    }
                  }
                  // imagen de fondo - ( ficha )
                  if( isset($ope[$tip][$ide = 'ima']) ){ $_ .= "
                    <div class='ren'>";
                      if( isset($ope[$tip][$ide]) ){
                        $_ .= _doc::var('doc',"tab.{$tip}.{$ide}",[
                          'id'=>"{$ele_ide}-{$ide}",
                          'htm'=>_app_dat::opc($ide, $ope['est'], [ 
                            'val'=>$ope[$tip][$ide], 
                            'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                          ])
                        ]);
                      }
                      if( isset($ope['val']) ){ $_ .= "
                        <div class='val' ide='acu'>";
                          foreach( array_keys($ope['val']) as $ite ){        
                            $_ .= _doc::var('doc', "tab.$tip.{$ide}_{$ite}", [
                              'val'=>isset($ope[$tip]["{$ide}_{$ite}"]) ? $ope[$tip]["{$ide}_{$ite}"] : FALSE,
                              'ope'=>[ 'id'=>"{$ele_ide}-{$ide}_{$ite}", 'onchange'=>$ele_eve ]
                            ]);
                          }$_.="
                        </div>";
                        }
                      $_ .= "
                    </div>";
                  }    
                  // operadores por aplicaciones                  
                  $_ .= _app_tab::opc($tip,$dat,$ope[$tip])."
                </fieldset>    
              </form>";          
            }$_ .= "
          </section>";
        }
        break;
      // Posicion principal : atributos por aplicación
      case 'pos':
        if( !empty($ope[$tip]) ){
          $ele['sec']['ide'] = $tip; $_ .= "
          <section"._htm::atr($ele['sec']).">

            "._app_tab::pos($dat,$ope,$ele,...$opc)."

          </section>";
        }
        break;

      }  

      return $_;
    }
    // por opciones : seccion + posicion
    static function opc( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."opc";
      $_eje = self::$EJE."opc";

      // proceso estructura de la base
      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";        
      $_eje = "_doc_{$esq}_tab.opc";

      // solo muestro las declaradas en el operador
      $atr = array_keys($ope);

      foreach( _app::var($esq,'tab',$tip) as $ide => $_dat ){

        if( in_array($ide,$atr) ){

          $_ .= _doc::var($esq, "tab.$tip.$ide", [
            'ope'=>[
              'id'=>"{$_ide}-{$ide}", 
              'val'=>!empty($ope[$ide]) ? !empty($ope[$ide]) : NULL, 
              'onchange'=>"$_eje('$tip',this);"
            ]
          ]);
        }
      }

      return $_;
    }
    // por posicion : valor principal
    static function pos( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."pos";
      $_eje = self::$EJE."pos";

      extract( _dat::ide($dat) );
      $_ide .= " _$esq.$est";
      $_eje = "_doc_{$esq}_tab.pos";

      foreach( $opc as $atr ){
        $htm = "";
        foreach( _app::var($esq,'pos',$atr) as $ide => $val ){
          $var = [
            'ope'=>[ 
              'id'=>"{$_ide}-{$atr}-{$ide}", 
              'dat'=>$atr, 
              'onchange'=>"$_eje('$atr',this)" ]
          ];
          if( isset($ope[$atr][$ide]) ){
            $var['var']['val'] = $ope[$atr][$ide];
          }
          $htm .= _doc::var($esq, "pos.$atr.$ide", $var);
        }        
        // busco datos del operador 
        if( !empty($htm) && !empty($_ope = _app::var($esq,'val','pos',$atr)) ){
          $ele['ope']['pos'] = $atr; $_ .= "
          <form"._htm::atr($ele['ope']).">
            <fieldset class='inf ren'>
              <legend>{$_ope['nom']}</legend>
                {$htm}
            </fieldset>
          </form>";          
        }
      }

      return $_;
    }
    
  }  
  
  // Estructura : atributos + valores + filtros + columnas + titulo + detalle
  class _app_est {

    static array $OPE = [
      'val' => [ 'nom'=>"Valores"       , 'des'=>"", 'htm'=>"" ], 
      'ver' => [ 'nom'=>"Filtros"       , 'des'=>"", 'htm'=>"" ], 
      'col' => [ 'nom'=>"Columnas"      , 'des'=>"", 'htm'=>"" ], 
      'des' => [ 'nom'=>"Descripciones" , 'des'=>"", 'htm'=>"" ],      
      'cue' => [ 'nom'=>"Cuentas"       , 'des'=>"", 'htm'=>"" ]
    ];
    static array $VAL = [
      'atr'=>[],// columnas del listado
      'atr_tot'=>0,// columnas totales
      'atr_ocu'=>[],// columnas ocultas
      'atr_dat'=>[],// datos de las columnas
      'est'=>[],// estructuras por esquema => [ ...$esq =>[ ...$est ] ]
      'est_dat'=>[],// datos y operadores por estructura
      'dat'=>[],// filas: valores por estructura [...{...$}]
      'val'=>[],// dato por columna: valor por objeto {...$}
      'tit'=>[],// titulos: por base {'cic','gru','des'} o por operador [$pos]
      'tit_cic'=>[],// titulos por ciclos
      'tit_gru'=>[],// titulos por agrupaciones
      'det'=>[],// detalles: por base {'cic','gru','des'} o por operador [$pos]
      'det_cic'=>[],// detalle por ciclos
      'det_gru'=>[],// detalle por agrupaciones
      'det_des'=>[]// detalle por descripciones
    ];
    static array $OPC = [ 
      'cab_ocu',  // ocultar titulo de columnas
      'ima',      // buscar imagen para el dato
      'var',      // mostrar variable en el dato
      'htm'       // convertir texto html en el dato
    ];
    static array $ATR = [
      'opc'=>[ "Opción", 0 ], 
      'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
      'tex'=>[ "Texto",  0 ], 
      'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
      'obj'=>[ "Objeto",  0 ] 
    ];
    static string $IDE = "_app_est-";
    static string $EJE = "_app_est.";

    // operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
    static function ope( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est,$ope);
      }// por listado
      else{        
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::var($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];      
      // todos
      switch( $tip ){      
      case 'tod':
        $ope_ver = '';
        $ope['var']['var'] = "est";
        // cargo operadores
        $_ope = _obj::nom($_ope,'ver',$ope_lis = ['ver','col','des','cue']);
        foreach( $ope_lis as $ide ){
          $_ope[$ide]['htm'] = _app_est::ope($ide,$dat,$ope,$ele);
        }
        $_ = "
  
        "._app_est::ope('val',$dat,$ope,$ele)."
  
        "._doc::nav('pes', $_ope, [ 
          'nav'=>['class' => "mar_arr-2" ]
        ],'tog')."        

        <div"._htm::atr($ope['var']).">

          "._app_est::lis($dat,$ope,$ele,...$opc)."

        </div>
        ";          
        break;
      // atributos y tipos de dato con filtros
      case 'atr':
        foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
        // tipos de dato
        $_cue = self::$ATR;
        // cuento atributos por tipo
        foreach( $_est->atr as $atr ){
          $tip_dat = explode('_', _dat::atr($esq,$est,$atr)->ope['_tip'])[0];
          if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
        }
        // operador : toggles + filtros
        $_ .= "
        <form class='val jus-ini' dat='atr'>
  
          <fieldset class='ope'>
            "._doc::ico('ope_ocu',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
            "._doc::ico('ope_ver',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
          </fieldset>
  
          "._doc::var('val','ver',[ 'nom'=>"Filtrar", 'htm'=>_doc_val::ver('tex',[ 'eje'=>"{$_eje}_ver(this);" ]) ])."
  
          <fieldset class='ite'>";
          foreach( $_cue as $atr => $val ){ $_ .= "
            <div class='val'>
              "._doc::ico($atr,[
                'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');"
              ])."
              <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
            </div>";
          }$_ .= "
          </fieldset>
  
        </form>";
        // listado
        $pos = 0; $_ .= "
        <table"._htm::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
        foreach( $_est->atr as $atr ){
          $pos++;
          $_atr = _dat::atr($esq,$est,$atr);
          $_var = [ 'id'=>"{$_ide}-{$atr}", 'onchange'=>"{$_eje}_val(this,'dat');" ];

          $var_tip = explode('_',$_atr->ope['_tip'])[0];
          if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
          if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
          if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
          if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
          $htm = "
          <form class='ren esp-bet'>
          
            "._app_val::ver('lis', isset($_cue[$var_tip][2]) ? $_cue[$var_tip][2] : [], [ 'ope'=>$_var ] )."
  
          </form>";
          $_ .= "
          <tr data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}' pos='{$pos}'>
            <td data-atr='val'>
              "._doc::ico( isset($app_lis->ocu) && in_array($atr,$app_lis->ocu) ? "ope_ver" : "ope_ocu",[
                'eti'=>"button",'title'=>"Mostrar",'class'=>"tam-2{$cla_ver}",'value'=>"tog",'onclick'=>"$_eje('val',this);"
              ])."
            </td>
            <td data-atr='pos'>
              <n>{$pos}</n>
            </td>
            <td data-atr='ide' title='".( !empty($_atr->ope['des']) ? $_atr->ope['des'] : '' )."'>
              <font class='ide'>{$_atr->nom}</font>
            </td>
            <td data-atr='ope'>
              {$htm}
            </td>
          </tr>";
        }$_ .= "
        </table>";                                    
      // valores : cantidad + acumulado + operaciones
      case 'val': 
        $_ = "
        <h3 class='dis-ocu'>Valores</h3>";
        // acumulados
        if( isset($ope['val']) ){
          $_ .= "
          <form ide='acu'>

            <fieldset class='inf ren'>
              <legend>Valores</legend>
              
              "._doc::var('doc', "val.dat.cue", [ 'ope'=>[ 'id'=>"{$_ide}-cue" ] ])."
              
              "._doc::var('doc', "val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
              
              "._app_val::acu($ope['val'],[
                'ide'=>$_ide, 
                'eje'=>"{$_eje}_acu(this); ".self::$EJE."ver('val',this);",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c> <n>0</n> <c class='sep'>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
        }// por datos de la base ?
        else{
        }
        break;
      // filtros : datos + posicion + atributos
      case 'ver':
        $dat_tot = count($ope['dat']);
        $_ = "
        <h3 class='dis-ocu'>Filtros</h3>

        <form ide='dat'>
          <fieldset class='inf ren'>
            <legend>por Datos</legend>
            
            "._app_val::ver('dat', $ope['est'], [ 'ope'=>[ 'max'=>$dat_tot, 'onchange'=>"$_eje('dat',this);" ] ])."

          </fieldset>
        </form>

        <form ide='pos'>
          <fieldset class='inf ren'>
            <legend>por Posiciones</legend>
            "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'cue'=>[] ], [                  
              'ope'=>[ '_tip'=>"num_int", 'min'=>"1", 'max'=>$dat_tot, 'id'=>"{$_ide}-pos", 'onchange'=>"$_eje('pos',this);" ]
            ])."
          </fieldset>
        </form>";
        break;
      // columnas : ver/ocultar
      case 'col':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
              
          foreach( $est_lis as $est ){
            // estrutura por aplicacion
            $_est = _app::est($esq,$est);
            // datos de la estructura
            $est_nom = _dat::est($esq,$est)->nom;
            // contenido : listado de checkbox en formulario
            $htm = "
            <form ide='{$tip}' class='ren jus-ini mar_izq-2'>";
              foreach( $_est->atr as $atr ){
                $htm .= _doc::var('val',$atr,[
                  'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                  'val'=>!isset($_est->atr_ocu) || !in_array($atr,$_est->atr_ocu),
                  'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr", 'onchange'=>"{$_eje}_tog(this);"
                  ] 
                ]);
              } $htm.="
            </form>";
            
            $lis_val []= [
              'ite'=>$est_nom,
              'htm'=>$htm
            ];
          }              
        }        
        $_ = "        
        <h3 class='dis-ocu'>Columnas</h3>

        "._doc_lis::val($lis_val,[
          'htm_fin'=>[ 'eti'=>"ul", 'class'=>"ope mar_izq-1", 'htm'=>"
            "._doc::ico('ope_ver',['eti'=>"li", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2", 
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
            "._doc::ico('ope_ocu',['eti'=>"li", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2", 
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])
          ],
          'opc'=>['tog']
        ]);
        break;
      // descripciones : titulo + detalle
      case 'des':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
            
          foreach( $est_lis as $est ){
            
            $_est =  _app::est($esq,$est,$ope);

            // ciclos, agrupaciones y lecturas
            if( !empty($_est->tit_cic) || !empty($_est->tit_gru) || !empty($_est->det_des) ){              

              $lis_dep = [];
              foreach( ['cic','gru','des'] as $ide ){

                $pre = $ide == 'des' ? 'det' : 'tit';
                
                if( !empty($_est->{"{$pre}_{$ide}"}) ){
                  $htm = "
                  <form ide='{$ide}' data-esq='{$esq}' data-est='{$est}' class='ren jus-ini mar_izq-2'>";
                  foreach( $_est->{"{$pre}_{$ide}"} as $atr ){
                    $htm .= _doc::var('val',$atr,[ 
                      'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                      'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                    ]);
                  }$htm .= "
                  </form>";
                  
                  $lis_dep[] = [ 
                    'ite'=>_app::var('doc','est','ver',$ide)['nom'], 
                    'htm'=>$htm
                  ];
                }
              }
              $lis_val[] = [
                'ite'=>_dat::est($esq,$est)->nom,
                'lis'=>$lis_dep
              ];
            }
          }
        }
        $_ = "
        <h3 class='dis-ocu'>Descripciones</h3>

        "._doc_lis::val($lis_val,[ 'opc'=>['tog'] ]);

        break;
      // cuentas : total + porcentaje
      case 'cue':
        $_ = "
        <h3 class='dis-ocu'>Cuentas</h3>

        "._doc_lis::val( _app_dat::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[ 'class'=>"dis-ocu" ], 'opc'=>['tog', 'ver', 'cue'] ] );

        break;
      }

      return $_;
    }

    // listado : thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
    static function lis( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      foreach( [ 'lis','cue','tit_ite','tit_val','dat_ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
      $_ = "";
      $_ide = self::$IDE."lis";
      $_eje = self::$EJE."lis";
      
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est,$ope);
      }// por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::var($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];            
      
      // identificadores de la base        
      if( isset($esq) ){
        $ele['lis']['data-esq'] = $esq;
        $ele['lis']['data-est'] = $est;
      }
      $_ = "
      <table"._htm::atr($ele['lis']).">";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';

        // columnas:
        if( $dat_val_lis = is_array($dat) ){
          // datos de atributos
          if( !isset($ope['atr_dat']) ){
            $ope['atr_dat'] = _est::atr($dat);
          }
          // listado de columnas
          if( !isset($ope['atr']) ){
            $ope['atr'] = array_keys($ope['atr_dat']);
          }
        }
        // caclulo total de columnas
        $ope['atr_tot'] = _dat::est_atr($dat,$ope);

        // cabecera
        if( !in_array('cab_ocu',$opc) ){ 
          foreach( [ 'cab','cab_ite','cab_val' ] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } 
          $_ .= "
          <thead"._htm::atr($ele['cab']).">
            "._app_est::col($dat,$ope,$ele,...$opc)."
          </thead>";
        }
        // cuerpo
        $_.="
        <tbody"._htm::atr($ele['cue']).">";          
          // recorro: por listado $dat = []
          if( $dat_val_lis ){

            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              if( !empty($ope['tit'][$pos]) ) $_ .= _app_est::pos('tit',$pos,$ope,$ele);

              // fila-columnas
              $ope['val'] = $_dat; $_.="
              <tr"._htm::atr($ele['dat_ite']).">
                "._app_est::ite($dat,$ope,$ele,...$opc)."
              </tr>";

              // detalles
              if( !empty($ope['det'][$pos]) ) $_ .= _app_est::pos('det',$pos,$ope,$ele);                    
            }
          }
          // estructuras de la base esquema
          else{

            // valido item por objeto-array
            foreach( $ope['dat'] as $_dat ){ $_val_dat_obj = is_object($_dat); break; }
            
            // valido contenido : titulos y detalles por estructura de la base
            $ele_ite = [];
            foreach( [ 'tit'=>['cic','gru'], 'det'=>['des','cic','gru'] ] as $i => $v ){ 
              $_val[$i] = isset($ope[$i]);
              foreach( $v as $e ){

                if( !is_numeric($e) && ( $_val["{$i}_{$e}"] = isset($ope["{$i}_{$e}"]) ) || ( $_val[$i] && in_array($e,$ope[$i]) ) ){

                  $ele_ite["{$i}_{$e}"] = [ 'ite'=>[ 'data-opc'=>$i, 'data-ope'=>$e ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
                }
              }            
            }

            // contenido previo : titulos por agrupaciones
            if( isset($ele_ite['tit_gru']) ){

              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  $_ .= _app_est::tit('gru', "{$esq}.{$est}", $ope, $ele_ite['tit_gru'], ...$opc);
                }
              }
            }

            // recorro datos
            foreach( $ope['dat'] as $pos => $_dat ){
              // titulos
              foreach( $ope['est'] as $esq => $est_lis ){
                // recorro referencias
                foreach( $est_lis as $est){
                  // cargo relaciones                  
                  if( $dat_opc_est = _dat::est_ope($esq,$est,'est') ){

                    foreach( $dat_opc_est as $atr => $ref ){

                      $ele['ite']["{$esq}-{$ref}"] = $_val_dat_obj ? $_dat->$atr : $_dat["{$esq}-{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos                
                  if( $_val['tit'] || $_val['tit_cic'] ){

                    $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                    $_ .= _app_est::tit('cic', "{$esq}.{$est}", $ope, $ele_ite['tit_cic'], ...$opc);
                  }
                }
              }
              // cargo item por esquema.estructuras
              $ele['ite']['pos'] = $pos; $_ .= "
              <tr"._htm::atr($ele['ite']).">";
              foreach( $ope['est'] as $esq => $est_lis ){
      
                foreach( $est_lis as $est ){
                  
                  $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                  $_ .= _app_est::ite("{$esq}.{$est}", $ope, $ele, ...$opc);
                } 
              }$_ .= "
              </tr>";
              $opc []= "det_cit";
              // cargo detalles
              foreach( $ope['est'] as $esq => $est_lis ){

                foreach( $est_lis as $est ){

                  foreach( ['des','cic','gru'] as $ide ){

                    if( isset($ele_ite["det_{$ide}"]) ){

                      $ope['val'] = $_val_dat_obj ? $_dat : $_dat["{$esq}-{$est}"];
                      $_ .= _app_est::det($ide, "{$esq}.{$est}", $ope, $ele_ite["det_{$ide}"], ...$opc );
                    }
                  }                  
                } 
              }                    
            }
          }$_ .= "              
        </tbody>";
        // pie
        if( !empty($ope['pie']) ){
          foreach( ['pie','pie_ite','pie_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
          $_.="
          <tfoot"._htm::atr($ele['pie']).">";
            // fila de operaciones
            $_.="
            <tr"._htm::atr($ele['pie_ite']).">";
              foreach( $_atr as $atr ){ $_.="
                <td data-atr='{$atr->ide}' data-ope='pie'></td>";
              }$_.="
            </tr>";
            $_.="
          </tfoot>";
        }
        $_.="
      </table>";
      return $_;
    }
    // columnas : por atributos
    static function col( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";

      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est);
      }
      // por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide .= " _$esq.$est";
        }
      }
      
      // por muchos      
      if( isset($ope['est']) ){

        $ope_est = $ope['est'];
        unset($ope['est']);

        foreach( $ope_est as $esq => $est_lis ){

          foreach( $est_lis as $est ){

            $_ .= _app_est::col("{$esq}.{$est}",$ope,$ele,...$opc);
          }
        }
      }
      // por 1: esquema.estructura
      else{
        $_val['dat'] = isset($esq);

        $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
        // cargo datos
        $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val['dat'] ? _dat::atr($esq,$est) : _est::atr($dat) );
        // ocultos por estructura
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : [];
        // genero columnas
        foreach( ( !empty($ope['atr']) ? $ope['atr'] : ( !empty($_est->atr) ? $_est->atr : array_keys($ope['atr_dat']) ) ) as $atr ){
          $e = [];
          if( $_val['dat'] ){
            $e['data-esq'] = $esq;
            $e['data-est'] = $est;
          } 
          $e['data-atr'] = $atr;
          if( in_array($atr,$atr_ocu) ) _ele::cla($e,"dis-ocu");
          // poner enlaces
          $htm = _doc::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
          if( !$ope_nav ){
            $htm = "<p>{$htm}</p>";
          }else{
            $htm = "<a href='' target='_blank'>{$htm}</a>";
          }$_ .= "
          <th"._htm::atr($e).">
            {$htm}
          </th>";
        }         
      }   

      return $_;
    }
    // posicion : titulo + detalle
    static function pos( string $tip, int $ide, array $ope = [], array $ele = [] ) : string {              
      $_ = "";
      if( isset($ope[$tip][$ide]) ){

        foreach( _lis::ite($ope[$tip][$ide]) as $val ){ 
          $_.="
          <tr"._htm::atr($ele["{$tip}_ite"]).">
            <td"._htm::atr(_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
              ".( is_array($val) ? _htm::val($val) : "<p class='{$tip} tex_ali-izq'>"._doc::let($val)."</p>" )."
            </td>
          </tr>";
        }        
      }
      return $_;
    }
    // titulo : posicion + ciclos + agrupaciones
    static function tit( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."tit";
      $_eje = self::$EJE."tit";
      // proceso estructura de la base
      if( is_string($dat) ){

        extract( _dat::ide($dat) );
        $_ide .= " _$esq.$est";
        $_est = _app::est($esq,$est);
      }
      // por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
        $_ide .= " _$esq.$est";
      }

      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }
      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }

      // 1 titulo : nombre + detalle
      if( $tip == 'pos' ){
        $ele['ite']['data-atr'] = $ope[0];
        $ele['ite']['data-ide'] = is_object($ope[2]) ? $ope[2]->ide : $ope[2];
        $htm = "";
        if( !empty($htm_val = _app_dat::val('nom',"{$esq}.{$ope[1]}",$ope[2])) ){ $htm .= "
          <p class='tit'>"._doc::let($htm_val)."</p>";
        }
        if( !empty($htm_val = _app_dat::val('des',"{$esq}.{$ope[1]}",$ope[2])) ){ $htm .= "
          <q class='mar_arr-1'>"._doc::let($htm_val)."</q>";
        }
        $_ .="
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">{$htm}</td>
        </tr>";
      }
      // por ciclos : secuencias
      elseif( $tip == 'cic' ){
        
        // acumulo posicion actual, si cambia -> imprimo ciclo        
        if( isset($_est->cic_val) ){
          
          $val = _dat::var($esq,$est,$ope['val']);            
  
          foreach( $_est->cic_val as $atr => &$pos ){

            if( !empty($ide = _dat::atr_est($esq,$est,$atr) ) && $pos != $val->$atr ){
              if( !empty($val->$atr) ){
                $ite_ele = $ele;

                if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');

                $_ .= _app_est::tit('pos',$dat,[$atr,$ide,$val->$atr],$ite_ele);
              }
              $pos = $val->$atr;
            }
          }
        }        
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($_est->$tip) ){
          foreach( $_est->$tip as $atr ){

            if( !empty($ide = _dat::atr_est($esq,$est,$atr)) ){

              foreach( _dat::var($esq,$ide) as $val ){                
                $ite_ele = $ele;
                if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');
                $_ .= _app_est::tit('pos',$dat,[$atr,$ide,$val],$ite_ele);
              }
            }
          }
        }
      }
      return $_;
    }
    // item : datos de la estructura
    static function ite( string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."ite";
      $_eje = self::$EJE."ite";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }
      
      $dat = $ope['val'];
      $opc_ima = !in_array('ima',$opc);
      $opc_var = in_array('var',$opc);
      $opc_htm = in_array('htm',$opc);

      // identificadores
      if( $_val['dat'] = isset($esq) ){
        $ele['dat_val']['data-esq'] = $esq;
        $ele['dat_val']['data-est'] = $est;
      }
      // datos de la base
      if( isset($_est) ){

        $_atr    = _dat::atr($esq,$est);
        $est_ima = _dat::est_ope($esq,$est,'ima');  
        $atr_ocu = isset($_est->atr_ocu) ? $_est->atr_ocu : FALSE;
        
        foreach( ( isset($ope['atr']) ? $ope['atr'] : $_est->atr ) as $atr ){
          $ele_dat = $ele['dat_val'];
          $ele_dat['data-atr'] = $atr;         
          //ocultos
          if( $atr_ocu && in_array($atr,$atr_ocu) ) _ele::cla($ele_dat,'dis-ocu');
          // contenido
          $ele_val = $ele['val'];
          
          if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
            _ele::cla($ele_val,"tam-3");
            $ide = 'ima';
          }
          // variables
          else{
            $ide = $opc_var ? 'var' : 'tip';
            // adapto estilos por tipo de valor
            if( !empty($_atr[$atr]) ){
              $var_dat = $_atr[$atr]->var_dat;
              $var_val = $_atr[$atr]->var_val;
            }elseif( !empty( $_var = _val::tip( $dat ) ) ){
              $var_dat = $_var->dat;
              $var_val = $_var->val;
            }else{
              $var_dat = "val";
              $var_val = "nul";
            }
            // - limito texto vertical
            if( $var_dat == 'tex' ){
              if( $var_dat == 'par' ) _ele::css($ele_val,"max-height:4rem; overflow-y:scroll");
            }
          }$_ .= "
          <td"._htm::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? _ele::cla($ele_dat,'dis-ocu') : $ele_dat ).">      
            "._app_dat::ver($ide,"{$esq}.{$est}.{$atr}",$dat,$ele_val)."
          </td>";
        }
      }
      // por listado del entorno
      else{
        $_atr = $ope['atr_dat'];
        $_val_dat_obj = is_object($dat);
        foreach( $ope['atr'] as $ide ){
          // valor
          $dat_val = $_val_dat_obj ? $dat->{$ide} : $dat[$ide];
          // html
          if( $opc_htm ){
            $htm = $dat_val;
          }// variable por tipo
          elseif( $opc_var ){
            $htm = "";
          }// elementos
          elseif( is_array( $dat_val ) ){
            $htm = isset($dat_val['htm']) ? $dat_val['htm'] : _htm::val($dat_val);
          }// textos
          else{
            $htm = _doc::let($dat_val);
          }
          $ele['dat_val']['data-atr'] = $ide;
          $_.="
          <td"._htm::atr($ele['dat_val']).">
            {$htm}
          </td>";
        }
      }      

      return $_;
    }
    // detalles : posicion + descripciones + lecturas
    static function det( string $tip, string | array $dat, array $ope = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."det";
      $_eje = self::$EJE."det";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app::est($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }

      if( empty($ele['ite']['data-esq']) ){
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        _ele::cla($ele['atr'],"anc-100");
      }

      // 1 detalle
      if( $tip == 'pos' ){
        $ele['ite']['data-atr'] = $ope[0];
        $ele['ite']['data-ide'] = isset($ope[1]->ide) ? $ope[1]->ide : ( isset($ope[1]->pos) ? $ope[1]->pos : '' ); $_ .= "
        <tr"._htm::atr($ele['ite']).">
          <td"._htm::atr($ele['atr']).">
            ".( in_array('det_cit',$opc) ? "<q>"._doc::let($ope[1]->{$ope[0]})."</q>" : _doc::let($ope[1]->{$ope[0]}) )."
          </td>
        </tr>";
      }
      // por tipos : descripciones + ciclos + agrupaciones
      elseif( isset($_est->{"det_$tip"}) ){
        $val = _dat::var($esq,$est,$ope['val']);
        foreach( $_est->{"det_$tip"} as $atr ){
          $ite_ele = $ele;
          if( in_array('ite_ocu',$opc) ) _ele::cla($ite_ele['ite'],'dis-ocu');
          $_ .= _app_est::det('pos',$dat,[$atr,$val],$ite_ele,...$opc);
        }
      }

      return $_;
    }    
  }