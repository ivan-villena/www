<?php

  // Página-app
  class _app {

    // propiedades:

      // estilos css
      public array $css = ['doc','app'];
      // modulos js
      public array $jso = ['api','usu','app','doc','hol'];
      // elementos
      public array $ele = [ 'body'=>[] ];
      // script
      public string $eje = "";

      // peticion
      public object $uri;
      // titulo
      public string $nom = "{-_-}";      
      // datos de interface
      public array $dat = [];
      // objetos de interface
      public array $obj = [];

      // pagina 
      public object $esq;
      public object $cab;
      public object $art;
      
      // operador: botones
      public array $ope = [];
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
    function __construct( string $esq = 'hol' ){

      // peticion url ( por defecto: holon )
      $_uri = $this->uri = new _app_uri( $esq );
      
      // inicializo datos
      $this->dat = [
        'app'=>[ 'uri' ],        
        'doc'=>[ 'ico', 'let' ],        
        'dat'=>[ 'tip', 'val' ],
        'fec'=>[ 'mes','sem','dia' ]
      ];
      // inicializo operadores
      $this->ope = [ 
        'ini'=>"",      // inicio
        'nav_art'=>[],  // indice
        'nav'=>"", 'nav_fin'=>"", // paneles
        'win'=>"", 'win_fin'=>""  // pantallas
      ];
      // cargo elementos
      $this->ele['body'] = [
        'doc'=>$_uri->esq, 'cab'=> !!$_uri->cab ? $_uri->cab : NULL, 'art'=> !!$_uri->art ? $_uri->art : NULL 
      ];

      // cargo datos : esquema - cabecera - articulo - valor
      $this->esq = _dat::get("_api.app_esq",[ 'ver'=>"`ide`='{$_uri->esq}'", 'opc'=>'uni' ]);
      if( !empty($_uri->cab) ){
        // cargo datos del menu
        $this->cab = _dat::get("_api.app_cab",[ 
          'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 'ele'=>'ope', 'opc'=>'uni' 
        ]);
        if( !empty($_uri->art) ){
          // cargo datos del artículo
          $this->art = _dat::get("_api.app_art",[ 
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
      if( !empty($this->art->nom) ){
        $this->nom = $this->art->nom;
      }
      elseif( !empty($this->cab->nom) ){
        $this->nom = $this->cab->nom;
      }
      elseif( !empty($this->esq->nom) ){
        $this->nom = $this->esq->nom; 
      }

      // botones : navegacion + pantalla
      $this->ope['ini'] = _doc::ico('ses',[ 'eti'=>"a", 'href'=>SYS_NAV."/{$_uri->esq}", 'title'=>"Inicio..." ]);

      // cargo contenido
      $_ = [ 'sec'=>[], 'nav'=>[], 'nav_fin'=>[], 'win'=>[], 'win_fin'=>[] ];

      // cargo menu principal
      $_['nav']['cab'] = [ 'ico'=>"nav", 'nom'=>"Menú Principal" ];

      // cargo índice de contenidos
      if( !empty($this->cab->nav) ){
        $this->ope['nav_art'] = _dat::get("_api.app_art_nav",[
          'ver'=>"esq = '{$_uri->esq}' AND cab = '{$_uri->cab}' AND ide = '{$_uri->art}'", 
          'ord'=>"pos ASC", 
          'nav'=>'pos'
        ]);          
        if( !empty($this->ope['nav_art'][1]) ){

          $_['nav']['art'] = [ 'ico' => "nav_val", 'nom' => "Índice de Contenidos", 

            'htm'=>_doc_lis::nav( $this->ope['nav_art'], [ 'lis' => [] ])
          ];
        }
      }              

      // pido contenido por aplicacion
      if( file_exists($cla_rec = "php/{$_uri->esq}/app.php") ){

        require_once($cla_rec);

        $cla_app = "_{$_uri->esq}_app";

        new $cla_app($_,$this);

        // completo el menù
        $_['nav']['cab']['htm'] = 
          ( isset($_['nav_htm_ini']) ? $_['nav_htm_ini'] : '' )
          ._app_nav::cab($_uri->esq, [ 'lis' => [] ])
          .( isset($_['nav_htm_fin']) ? $_['nav_htm_fin'] : '' )
        ;

        // pido botones del navegador
        $art = [];
        $this->ope['nav'] = _app_ope::tog($_,'nav');
        if( !empty($_['nav_fin']) ){
          $art['nav'] = $_['nav_fin'];
          $this->ope['nav_fin'] = _app_ope::tog($art,'nav');          
          $_['nav'] = array_merge($_['nav'],$_['nav_fin']);
        }// imprimo paneles
        foreach( $_['nav'] as $ide => $nav ){ 
          $this->nav .= _app_ope::nav($ide,$nav); 
        }

        // pido botones de pantallas
        $art = [];
        if( !empty($_['win']) ) $this->ope['win'] = _app_ope::tog($_,'win');
        if( !empty($_['win_fin']) ){
          $art['win'] = $_['win_fin'];
          $this->ope['win_fin'] = _app_ope::tog($art,'win');
          $_['win'] = array_merge($_['win'],$_['win_fin']);
        }// imprimo pantallas
        foreach( $_['win'] as $ide => $win ){ 
          $this->win .= _app_ope::win($ide,$win); 
        }
        
        // inicializo elemento del articulo
        $ele = [ 
          'tit' => $this->nom
        ];

        $this->sec = _app_ope::sec( $_['sec'], $ele );

        // ajusto diseño
        if( !empty($this->pan) || !empty($this->pie) ){
          $_ver = [];
          if( !empty($this->pan) ) $_ver []= 'pan';
          if( !empty($this->pie) ) $_ver []= 'pie';
          $this->ele['body']['data-ver'] = implode(',',$_ver);
        }        
        
      }

      // cargo datos por esquemas
      global $_api;
      $this->obj['uri'] = $_uri;
      foreach( $this->dat as $esq => $est ){
        // cargo todas las estructuras de la base que empiecen por "_api.$esq_"
        if( empty($est) ){
          foreach( $_api as $i => $v ){
            if( preg_match("/^{$esq}_/",$i) ) $this->obj[$i] = $v;
          }
        }// cargo estructuras por identificador
        else{
          foreach( _lis::ite($est) as $est ){
            $this->obj[$i = "{$esq}_{$est}"] = $_api->$i;
          }          
        }
      }
    }
    // controlador : nombre => valor
    static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
      global $_api;
      $_ = [];
      
      if( empty($dat) ){
        if( !isset($_api->app_var[$esq]) ){
          $_api->app_var[$esq] = _dat::get("_api.app_var",[
            'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }elseif( empty($val) ){
        if( !isset($_api->app_var[$esq][$dat]) ){
          $_api->app_var[$esq][$dat] = _dat::get("_api.app_var",[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }else{
        if( !isset($_api->app_var[$esq][$dat][$val]) ){
          $_api->app_var[$esq][$dat][$val] = _dat::get("_api.app_var",[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 'niv'=>['ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }
      if( !empty($ide) ){
        $_ = isset($_api->app_var[$esq][$dat][$val][$ide]) ? $_api->app_var[$esq][$dat][$val][$ide] : [];
      }elseif( !empty($val) ){
        $_ = isset($_api->app_var[$esq][$dat][$val]) ? $_api->app_var[$esq][$dat][$val] : [];
      }elseif( !empty($dat) ){      
        $_ = isset($_api->app_var[$esq][$dat]) ? $_api->app_var[$esq][$dat] : [];
      }else{
        $_ = isset($_api->app_var[$esq]) ? $_api->app_var[$esq] : [];
      }

      return $_;
    }
    // datos de un proceso : absoluto o con dependencias ( _api.dat->est ) 
    static function dat( string | array $ope, mixed $dat = NULL ) : array {      
      $_ = [];

      if( is_array($ope) ){
        // cargo temporal
        foreach( $ope as $esq => $est_lis ){
          // recorro estructuras del esquema
          foreach( $est_lis as $est => $dat ){
            // recorro dependencias            
            foreach( 
              ( !empty($dat_est = _dat::est($esq,$est,'ope','est')) ? $dat_est : [ $esq => $est ] ) 
            as $ide => $ref ){
              // acumulo valores
              if( isset($dat->$ide) ){
                
                $_["{$esq}-{$ref}"] = $dat->$ide;
              }
            }
          }
        }
        global $_api;
        $_api->app_dat []= $_;
      }
      return $_;
    }
    // tablero 
    static function tab( string $esq, string $est, array $ele = NULL ) : array | object {
      global $_api;

      if( !isset($_api->app_art_tab[$esq][$est]) ){
        $_api->app_art_tab[$esq][$est] = _dat::get("_api.app_tab",[ 
          'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 
          'opc'=>'uni', 
          'ele'=>['ele','ope','opc'] 
        ]);
      }
      // devuelvo tablero : ele + ope + opc
      $_ = $_api->app_art_tab[$esq][$est];

      // combino elementos
      if( isset($ele) ){
        $_ = $ele;
        if( !empty($_api->app_art_tab[$esq][$est]->ele) ){

          foreach( $_api->app_art_tab[$esq][$est]->ele as $eti => $atr ){
            
            $_[$eti] = isset($_[$eti]) ? _ele::jun( $atr, $_[$eti] ) : $atr;
          }
        }
      }
      return $_;
    }
    // tabla 
    static function est( string $esq, string $est, array $ope = NULL ) : object {
      global $_api;

      if( !isset($_api->app_art_est[$esq][$est]) || isset($ope) ){

        // combinado        
        $_est = _dat::get("_api.app_est",[ 
          'ver'=>"`esq`='{$esq}' AND `ide`='{$est}'", 
          'obj'=>'ope', 
          'red'=>'ope',
          'opc'=>'uni' 
        ]);

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
        $_est->atr_tot = count($_est->atr);
            
        // descripciones
        $_val['tit'] = isset($ope['tit']);      
        $_val['det'] = isset($ope['det']);      

        // reemplazo e inicializo
        foreach( [ 'tit'=>['cic','gru'], 'det'=>['des','cic','gru'] ] as $i => $v ){

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

        $_api->app_art_est[$esq][$est] = $_est;
      }

      return $_api->app_art_est[$esq][$est];
    }
  }

  // peticion y recursos
  class _app_uri {

    public string $esq;

    public string $cab;

    public string $art;

    public string $val;

    public function __construct( string $esq ){
      // peticion
      $uri = !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '';

      // por separaciones
      $dat = explode('/',$uri);      
      
      $this->esq = !empty($dat[0]) ? $dat[0] : $esq;
      $this->cab = !empty($dat[1]) ? $dat[1] : FALSE;
      $this->art = !empty($dat[2]) ? $dat[2] : FALSE;
      if( $this->art ){
        $_val = explode('#',$this->art); 
      }        
      if( isset($_val[1]) ){
        $this->art = $_val[0];
        $this->val = $_val[1];  
      }
      else{          
        $this->val = !empty($dat[3]) ? $dat[3] : FALSE;
      }
      global $_api;
      $_api->app_uri = $this;
    }
    // contenido html : valido archivo
    public function rec( string $ide, array $arc = [ 'html', 'php' ] ) : string {

      $_ = '';

      foreach( $arc as $tip ){

        if( file_exists( $rec = "{$ide}.{$tip}" ) ){

          $_ = $rec;

          break;
        }        
      }
      return $_;
    }
    // directorio
    public function dir() : object {

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
    // sesion
    public function ses() : array {
      
      $_ = [];

      foreach( $_REQUEST as $i => $v ){

        if( preg_match("/^{$this->esq}-/",$i) ) $_[$i] = $v;
      }

      return $_;      
    }
  }

  // contenido : botonera + navegador + pantalla + seccion + paneles
  class _app_ope {

    static string $IDE = "_app_ope-";
    static string $EJE = "_app_ope.";

    // operador : botones del panel ( nav + win )    
    static function tog( $dat, ...$opc ) : string {
      $_eje = self::$EJE."tog";

      $_ = "";      

      foreach( ( !empty($opc) ? $opc : [ 'nav', 'win' ] ) as $tip ){

        if( isset($dat[$tip]) ){

          foreach( $dat[$tip] as $ide => $art ){

            $eje_tog = "{$_eje}('$tip','$ide');";

            if( is_string($art) ){

              $_ .= _doc::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_tog ]);
            }
            elseif( is_array($art) ){

              if( isset($art[0]) ){

                $_ .= _doc::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_tog ]);
              }
              elseif( isset($art['ico']) ){

                $_ .= _doc::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_tog ]);
              }
            }
            elseif( is_object($art) && isset($art->ico) ){

              $_ .= _doc::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_tog ]);
            }
          }
        }
      }
      return $_;
    }
    // pantalla emergente : #sis > article[ide] > header + section
    static function win( string $ide, array $ope = [] ) : string {      
      foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
      $_eje = self::$EJE."tog";
      $_ = "";
      // identificador
      $ope['art']['ide'] = $ide;
      _ele::cla($ope['art'],'dis-ocu');

      $cab_ico = "";
      if( !empty($ope['ico']) ) $cab_ico = _doc::ico($ope['ico'],['class'=>"mar_hor-1"]);

      $cab_tit = "";
      if( !empty($ope['nom']) ) $cab_tit = "
        <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
      ";

      if( !isset($ope['htm']) ){
        $ope['htm'] = '';
      }
      elseif( is_array($ope['htm']) ){ 
        $ope['htm'] = _ele::val( $ope['htm'] );
      }      

      $_ = "
      <article"._htm::atr($ope['art']).">

        <header"._htm::atr($ope['cab']).">
        
          {$cab_ico} {$cab_tit} "._doc::ico('eje_fin',[ 'title'=>'Cerrar', 'onclick'=>"$_eje('win');" ])."

        </header>

        <section"._htm::atr($ope['sec']).">

          {$ope['htm']}

        </section>

      </article>";
      
      return $_;
    }
    // docks de navegacion : nav|article[ide] > header + section
    static function nav( string $ide, array $ope = [] ) : string {
      foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
      $_eje = self::$EJE."tog";
      $_ = "";
      // identificador
      $ope['nav']['ide'] = $ide;
      _ele::cla($ope['nav'],DIS_OCU);

      $cab_ico = "";
      if( !empty($ope['ico']) ) $cab_ico = _doc::ico($ope['ico'],['class'=>"mar_hor-1"]);

      $cab_tit = "";
      if( !empty($ope['nom']) ) $cab_tit = "
        <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
      ";

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
        
          {$cab_ico} {$cab_tit} "._doc::ico('eje_fin',[ 'title'=>'Cerrar', 'onclick'=>"$_eje('nav');" ])."

        </header>

        <$eti_sec"._htm::atr($ope['sec']).">

          {$ope['htm']}

        </$eti_sec>

      </$eti_nav>";
      
      return $_;
    }
    // articulos por seccion principal : main > ...article
    static function sec( string | array $dat, array $ele = [] ) : string {
      $_ = "";
      if( isset($ele['tit']) ){ $_ .= "
        <header"._htm::atr( isset($ele['cab']) ? $ele['cab'] : [] ).">";
          if( is_string($ele['tit']) ){ $_ .= "
            <h1>"._doc::let($ele['tit'])."</h1>";
          }else{
            $_ .= _ele::val(...$ele['tit']);
          }$_ .= "
        </header>";
      }
      // contenido directo
      if( is_string($dat) ){ $_ .= "
        <article"._htm::atr( isset($ele['art']) ? $ele['art'] : [] ).">
          $dat
        </article>";
      }
      // listado de articulos
      else{
        foreach( $dat as $ide => $art ){
          
          if( isset($art['htm'])){
            $art['data-ide'] = $ide; $_ .= "
            <article"._htm::atr($art).">
              {$art['htm']}
            </article>";
          }
        }
      }
      return $_;
    }

  }

  // navegadores : menu + secciones por indice
  class _app_nav {
   
    // menu principal : titulo + descripcion + listado > item = [icono] + enlace
    static function cab( string $esq, array $ele = [] ) : string {
      global $_usu;      
      foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

      // armo listado de enlaces
      $_lis = [];
      foreach( _dat::get("_api.app_cab",[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

        if( !empty($_cab->usu) && empty($_usu->ide) ) continue;

        $ite_ico = !empty($_cab->ico) ? _doc::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";        

        $_lis_val = [];
        foreach( _dat::get("_api.app_art",[ 
          'ver'=>"`esq`='$esq' AND `cab`='$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art 
        ){

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
    // genero secciones del artículo por indices
    static function sec( string $ide ) : string {

      $_ = "";

      $_ide = explode('.',$ide);

      $_nav = _dat::get("_api.app_art_nav",[ 
        'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 
        'nav'=>'pos' 
      ]);

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
  
  // conenido del articulo : dato + tabla + tablero + glosario
  class _app_art {

    // articulo por operador
    static function sec( object $nav, string $esq, string $cab ) : string {
      $_ = "";      

      $agr = _htm::dat($nav->ope);

      $_art = _dat::get("_api.app_art",[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
      
      if( is_array( $tex = _dat::get('_api.app_art_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

        foreach( $tex as $pal ){
          $_[ $pal->nom ] = $pal->des;
        }
      }

      // operadores : toggle + filtro
      if( !isset($ele['opc']) ) $ele['opc'] = [];

      return _doc_lis::ite($_,$ele);
    }
  }

