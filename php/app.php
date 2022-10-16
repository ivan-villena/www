<?php

  // Página-app
  class _app {
    // peticion
    public object $uri;
    // recursos
    public array $rec;
    // elementos
    public array $ele;
    // pagina
    public object $esq;
    // menu
    public object $cab;
    // articulo
    public object $art;
    // operador: botones + contenido
    public array $ope = [];
    // documento
    public array $doc = [];

    // cargo aplicacion ( por defecto: sincronario )
    function __construct( string $esq = 'hol' ){

      // peticion url 
      $this->uri = new _app_uri($esq);

      // recursos : css + jso + eje + dat + obj
      $this->rec = [
        'css' => [ 'doc','app' ],
        'jso' => [ 'api','doc','app','usu','hol' ],
        'htm' => [],
        'eje' => "",
        'dat' => [
          'app'=>[ 'uri', 'dat' ],
          'dat'=>[ 'tip' ],
          'doc'=>[ 'ico', 'let' ],          
          'fec'=>[ 'mes','sem','dia' ]
        ]
      ];

      // cargo elementos
      $this->ele = [
        'title'=> "{-_-}",
        'body' => [
          'doc'=> $this->uri->esq,
          'cab'=> !!$this->uri->cab ? $this->uri->cab : NULL, 
          'art'=> !!$this->uri->art ? $this->uri->art : NULL 
        ]
      ];

      // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
      $this->esq = _dat::get("api.app_esq",[ 
        'ver'=>"`ide`='{$this->uri->esq}'", 
        'opc'=>'uni' 
      ]);
      if( !empty($this->uri->cab) ){
        // cargo datos del menu
        $this->cab = _dat::get("api.app_cab",[ 
          'ver'=>"`esq`='{$this->uri->esq}' AND `ide`='{$this->uri->cab}'", 
          'ele'=>'ope', 'opc'=>'uni' 
        ]);
        if( !empty($this->uri->art) ){
          // cargo datos del artículo
          $this->art = _dat::get("api.app_art",[ 
            'ver'=>"`esq`='{$this->uri->esq}' AND `cab`='{$this->uri->cab}' AND `ide`='{$this->uri->art}'", 
            'ele'=>'ope', 'opc'=>'uni' 
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
        $this->ele['title'] = $this->art->nom;
      }
      elseif( !empty($this->cab->nom) ){
        $this->ele['title'] = $this->cab->nom;
      }
      elseif( !empty($this->esq->nom) ){
        $this->ele['title'] = $this->esq->nom; 
      }

      // inicializo operadores
      $this->ope = [ 
        // inicio
        'ini'=>_doc::ico('app',[ 'eti'=>"a", 'href'=>SYS_NAV."/{$this->uri->esq}", 'title'=>"Ir a la página de Inicio..." ]),
        // menu
        'nav_cab'=>[],
        // indice
        'nav_art'=>[],
        // superior
        'sup'=>[],
        // inferior
        'inf'=>[],
        // paneles
        'nav'=>"",
        'nav_fin'=>"",
        // pantallas
        'win'=>"",
        'win_fin'=>""
      ];
      // inicializo contenido
      $this->doc = [
        // panel de navegacion
        'nav' => "",
        // seccion principal
        'sec' => "",
        // pantalla emergente
        'win' => "",
        // paneles laterales
        'pan' => "",
        // barra inferior
        'pie' => ""
      ];
    }
    // cargo página
    public function ini() : void {

      global $sis_ini, $_usu;

      $_uri = $this->uri;

      // cargo contenido
      $_ = [ 'sec'=>[], 'nav'=>[], 'nav_fin'=>[], 'win'=>[], 'win_fin'=>[] ];

      // cargo menu principal
      $_['nav']['cab'] = [ 'ico'=>"app_nav", 'nom'=>"Menú Principal" ];

      // cargo índice de contenidos
      if( !empty($this->cab->nav) ){

        $this->ope['nav_art'] = _dat::get("api.app_art_nav",[
          'ver'=>"esq = '{$_uri->esq}' AND cab = '{$_uri->cab}' AND ide = '{$_uri->art}'", 
          'ord'=>"pos ASC", 
          'nav'=>'pos'
        ]);

        if( !empty($this->ope['nav_art'][1]) ){

          $_['nav']['art'] = [ 
            'ico' => "lis_nav", 'nom' => "Índice",
            'htm'=>_doc_lis::nav( $this->ope['nav_art'], [ 'lis' => [] ])
          ];
        }
      }              

      // pido contenido por aplicacion
      if( file_exists($cla_rec = "htm/{$_uri->esq}.php") ){

        require_once($cla_rec);

        $cla_app = "_{$_uri->esq}_app";

        new $cla_app( $_ );

        // completo el menú
        $_['nav']['cab']['htm'] = 
        ( isset($_['nav_htm_ini']) ? $_['nav_htm_ini'] : '' )
        ._app_nav::cab($_uri->esq, [ 'lis' => [] ])
        .( isset($_['nav_htm_fin']) ? $_['nav_htm_fin'] : '' );

        // tutoriales de la página
        if( !empty( $_['app_tut'] ) ) $_['win_fin']['tut'] = [
          'ico'=>"dat_des", 
          'nom'=>"Tutorial", 
          'htm'=>$_['app_tut'] 
        ];

        // pido botones del navegador
        $art = [];
        $this->ope['nav'] = _app_ope::tog($_,'nav');
        if( !empty($_['nav_fin']) ){ 
          $art['nav'] = $_['nav_fin'];
          // botones finales
          $this->ope['nav_fin'] = _app_ope::tog($art,'nav');          
          $_['nav'] = array_merge($_['nav'],$_['nav_fin']);
        }
        // imprimo paneles
        foreach( $_['nav'] as $ide => $nav ){ 
          $this->doc['nav'] .= _app_ope::nav($ide,$nav); 
        }

        // pido botones de pantallas
        $art = [];
        if( !empty($_['win']) ) $this->ope['win'] = _app_ope::tog($_,'win');
        if( !empty($_['win_fin']) ){
          $art['win'] = $_['win_fin'];
          // botones finales
          $this->ope['win_fin'] = _app_ope::tog($art,'win');
          $_['win'] = array_merge($_['win'],$_['win_fin']);
        }
        // imprimo pantallas
        foreach( $_['win'] as $ide => $win ){ 
          $this->doc['win'] .= _app_ope::win($ide,$win); 
        }
        
        // inicializo elemento del articulo
        $ele = [ 'tit' => $this->ele['title'] ];
        $this->doc['sec'] = _app_ope::sec( $_['sec'], $ele );

        // ajusto diseño
        if( !empty($this->doc['pan']) || !empty($this->doc['pie']) ){
          $_ver = [];
          if( !empty($this->doc['pan']) ) $_ver []= 'pan';
          if( !empty($this->doc['pie']) ) $_ver []= 'pie';
          $this->ele['body']['data-ver'] = implode(',',$_ver);
        }
      }

      // cargo datos por esquemas
      global $_api;
      $_dat = [];
      foreach( $this->rec['dat'] as $esq => $est ){
        // cargo todas las estructuras de la base que empiecen por "api.$esq_"
        if( empty($est) ){
          foreach( $_api as $i => $v ){
            if( preg_match("/^{$esq}_/",$i) ) $_dat[$i] = $v;
          }
        }// cargo estructuras por identificador
        else{
          foreach( $est as $ide ){
            $_dat["{$esq}_{$ide}"] = $_api->{"{$esq}_{$ide}"};
          }          
        }
      }
      $this->rec['dat'] = $_dat;
      ?>
      <!DOCTYPE html>
      <html lang="es">
          
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width = device-width, initial-scale = 1, maximum-scale = 1">
          <?php // hojas de estilo
          foreach( $this->rec['css'] as $ide ){ 
            if( file_exists( $rec = "css/{$ide}.css" ) ){ echo "
              <link rel='stylesheet' href='".SYS_NAV.$rec."' >";
            }
          } ?>
          <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Material+Icons+Outlined'>
          <link rel='stylesheet' href='<?= SYS_NAV."css/api.css" ?>' >
          
          <title><?= $this->ele['title'] ?></title>
        </head>

        <body <?= _htm::atr($this->ele['body']) ?>>
              
          <!-- Botonera -->
          <aside class='bot'>
            
            <nav class="ope dir-ver">

              <?= $this->ope['ini']; ?>
              
              <?= $this->ope['nav']; ?>

              <?= $this->ope['win']; ?>

            </nav>

            <nav class="ope dir-ver">

              <?= $this->ope['nav_fin']; ?>

              <?= $this->ope['win_fin']; ?>

              <?php
              $win = [];
              // consola
              if( $_usu->ide == 1 ) 
                $win['api_adm'] = [ 'ico'=>"eje", 'nom'=>"Administrador del Sistema" ];
              // usuario + loggin
              if( empty($_usu->ide) ){ 
                $win['ses_ini'] = [ 'ico'=>"app_ini", 'nom'=>"Iniciar Sesión..."];
              }
              else{ 
                $win['ses_fin'] = [ 'ico'=>"app_fin", 'nom'=>"Cerrar Sesión..."];
              }
              
              echo _app_ope::tog([ 'win'=>$win ]);
              ?>
            </nav>
            
          </aside>

          <!-- Panel -->
          <aside class='nav dis-ocu'>
            <?= $this->doc['nav'] ?>
          </aside>

          <!-- Secciones -->
          <main>

            <?= $this->doc['sec'] ?>

          </main>
          
          <?php if( !empty($this->doc['pan']) ){ ?>      
            <!-- sidebar -->
            <aside class='pan'>
              <?= $this->doc['pan'] ?>
            </aside>
          <?php } ?>

          <?php if( !empty($this->doc['pie']) ){  ?>
            <!-- pie de página -->
            <footer>
              <?= $this->doc['pie'] ?>
            </footer>
          <?php } ?>

          <!-- Interface -->
          <section id='win' class='dis-ocu'>
            <?php 
            // imprimo consola del administrador
            if( $_usu->ide == 1 ){
              $this->rec['htm'] []= "api/adm";
              include("htm/api/adm.php");
            }
            // sesion del usuario
            $tip = empty($_usu->ide) ? 'ini' : 'fin';
            include("htm/api/ses.php");

            // imprimo pantallas 
            ?>
            <?= $this->doc['win'] ?>

          </section>

          <!-- Programas -->
          <?php
          foreach( ['jso','htm'] as $dir ){
            foreach( $this->rec[$dir] as $ide ){       
              if( file_exists( $rec = "$dir/$ide.js" ) ){ echo "
                <script src='".SYS_NAV.$rec."'></script>";
              }
            }
          }?>
          <script>
            
            // cargo datos de la interface
            var $_api = new _api(<?= _obj::cod( $this->rec['dat'] ) ?>);
            
            // cargo aplicacion
            var $_app = new _app();
            
            // instacio objeto por aplicacion
            <?= $this->rec['eje'] ?>

            // inicializo página
            $_app.ini();

            console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

          </script>

        </body>

      </html>      
      <?php
    }
    // cargo datos
    static function dat( string $esq, string $est, string $ide ) : mixed {

      $_ = _dat::est($esq,$est)->ope;
      
      foreach( explode('.',$ide) as $ide ){

        if( isset($_->$ide) ) $_ =  $_->$ide;
      }

      return $_;
    }// valores : nombre, descripcion, titulo, imagen, color...
    static function dat_val( string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : mixed {
      $_ = FALSE;
      // cargo valores de la estructura
      $_ = _app::dat($esq,$est,'val');
      // busco por atributos y datos
      if( !empty($atr) ){
        $_val = $_;
        // valores variables ()($)...()
        if( isset($_val->$atr) ){
          $_ = $_val->$atr;
          if( isset($dat) ) $_ = _obj::val( _dat::get($esq,$est,$dat), $_ );
        }
      }
      return $_;
    }// por seleccion : imagen, color...
    static function dat_opc( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
      // dato
      $_ = [ 'esq' => $esq, 'est' => $est ];

      // armo identificador
      if( !empty($atr) ){        
        $_['est'] = $atr == 'ide' ? $est : "{$est}_{$atr}";  

        // busco dato en atributos
        $_atr = _dat::atr($esq,$est,$atr);
        
        if( isset($_atr->var['dat']) && !empty($var_dat = $_atr->var['dat']) ){
          $dat = explode('.',$var_dat);
          $_['esq'] = $dat[0];
          $_['est'] = $dat[1];
        }
      }
      // valido dato
      if( !empty( $dat_Val = _app::dat_val($_['esq'],$_['est'],"opc.$tip",$dat) ) ){
        $_['ide'] = "{$_['esq']}.{$_['est']}";
        $_['val'] = $dat_Val;
      }
      else{
        $_ = [];
      }
      return $_;
    }
    // armo controlador : nombre => valor
    static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
      global $_api;
      $_ = [];
      
      if( empty($dat) ){
        if( !isset($_api->app_var[$esq]) ){
          $_api->app_var[$esq] = _dat::get("api.app_var",[
            'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }elseif( empty($val) ){
        if( !isset($_api->app_var[$esq][$dat]) ){
          $_api->app_var[$esq][$dat] = _dat::get("api.app_var",[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }else{
        if( !isset($_api->app_var[$esq][$dat][$val]) ){
          $_api->app_var[$esq][$dat][$val] = _dat::get("api.app_var",[
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
    // cargo Valores : absoluto o con dependencias ( api.dat->est ) 
    static function val( string | array $ope, mixed $dat = NULL ) : array {      
      $_ = [];

      if( is_array($ope) ){
        // cargo temporal
        foreach( $ope as $esq => $est_lis ){
          // recorro estructuras del esquema
          foreach( $est_lis as $est => $dat ){
            // recorro dependencias            
            foreach( 
              ( !empty($dat_est = _app::dat($esq,$est,'est')) ? $dat_est : [ $esq => $est ] ) 
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
    // armo Tablero
    static function tab( string $esq, string $est, array $ele = NULL ) : array | object {
      global $_api;

      if( !isset($_api->app_tab[$esq][$est]) ){
        $_api->app_tab[$esq][$est] = _dat::get("api.app_tab",[ 
          'ver'=>"`esq`='{$esq}' AND `est`='{$est}'", 
          'opc'=>'uni', 
          'ele'=>['ele','ope','opc'] 
        ]);
      }
      // devuelvo tablero : ele + ope + opc
      $_ = $_api->app_tab[$esq][$est];

      // combino elementos
      if( isset($ele) ){
        $_ = $ele;
        if( !empty($_api->app_tab[$esq][$est]->ele) ){

          foreach( $_api->app_tab[$esq][$est]->ele as $eti => $atr ){
            
            $_[$eti] = isset($_[$eti]) ? _ele::jun( $atr, $_[$eti] ) : $atr;
          }
        }
      }
      return $_;
    }
    // armo Listado-Tabla 
    static function est( string $esq, string $est, array $ope = NULL ) : object {
      global $_api;
      if( !isset($_api->app_est[$esq][$est]) || isset($ope) ){
        // combinado        
        $_est = _dat::get("api.app_est",[ 
          'ver'=>"`esq`='{$esq}' AND `ide`='{$est}'", 
          'obj'=>'ope', 'red'=>'ope', 'opc'=>'uni'
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
        $_api->app_est[$esq][$est] = $_est;
      }

      return $_api->app_est[$esq][$est];
    }
  }
  // peticion
  class _app_uri {

    public string $esq;

    public string $cab;

    public string $art;

    public string $val;

    // peticion
    public function __construct( string $esq ) {      

      $uri = explode('/', !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '');
      
      $this->esq = !empty($uri[0]) ? $uri[0] : $esq;
      $this->cab = !empty($uri[1]) ? $uri[1] : FALSE;
      $this->art = !empty($uri[2]) ? $uri[2] : FALSE;

      if( $this->art ) $_val = explode('#',$this->art);

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
  // Contenido : botonera + navegador + pantalla + seccion + paneles
  class _app_ope {

    static string $IDE = "_app_ope-";
    static string $EJE = "_app_ope.";

    // operador : botones del panel ( nav + win )    
    static function tog( $dat, ...$opc ) : string {
      $_eje = self::$EJE;

      $_ = "";      

      foreach( ( !empty($opc) ? $opc : [ 'nav', 'win', 'sec' ] ) as $tip ){

        if( isset($dat[$tip]) ){

          foreach( $dat[$tip] as $ide => $art ){

            $eje_tog = "{$_eje}{$tip}('$ide');";

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
      $_eje = self::$EJE."win";
      $_ = "";
      // identificador
      $ope['art']['ide'] = $ide;
      _ele::cla($ope['art'],'dis-ocu');
      // icono de lado izquierdo
      $cab_ico = "";
      if( !empty($ope['ico']) ) $cab_ico = _doc::ico($ope['ico'],['class'=>"mar_hor-1"]);
      // titulo al centro
      $cab_tit = "";
      if( !empty($ope['nom']) ) $cab_tit = "
        <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? $ope['nom'] : '' )."</h2>
      ";
      // contenido 
      if( !isset($ope['htm']) ){
        $ope['htm'] = '';
      }
      elseif( is_array($ope['htm']) ){ 
        $ope['htm'] = _ele::val( $ope['htm'] );
      }      
      // imprimo
      $_ = "
      <article"._htm::atr($ope['art']).">

        <header"._htm::atr($ope['cab']).">
        
          {$cab_ico} {$cab_tit} "._doc::ico('dat_fin',[ 'title'=>'Cerrar', 'onclick'=>"$_eje();" ])."

        </header>

        <div"._htm::atr($ope['sec']).">

          {$ope['htm']}

        </div>

      </article>";
      
      return $_;
    }
    // docks de navegacion : nav|article[ide] > header + section
    static function nav( string $ide, array $ope = [] ) : string {
      foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
      $_eje = self::$EJE."nav";
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

      $eti_sec = isset($ope['sec']['eti']) ? $ope['sec']['eti'] : 'div';

      if( !isset($ope['htm']) ){
        $ope['htm'] = '';
      }
      elseif( is_array($ope['htm']) ){ 
        $ope['htm'] = _ele::val( $ope['htm'] );
      }

      $_ = "
      <$eti_nav"._htm::atr($ope['nav']).">

        <header"._htm::atr($ope['cab']).">
        
          {$cab_ico} {$cab_tit} "._doc::ico('dat_fin',[ 'title'=>'Cerrar', 'onclick'=>"$_eje();" ])."

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
      if( is_string($dat) ){ 
        $_ .= $dat;
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
  // Navegadores : menu + secciones por indice
  class _app_nav {
   
    // menu principal : titulo + descripcion + listado > item = [icono] + enlace
    static function cab( string $esq, array $ele = [] ) : string {
      global $_usu;      
      foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

      // armo listado de enlaces
      $_lis = [];
      foreach( _dat::get("api.app_cab",[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

        if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($_usu->ide) ) ){
          continue;
        }

        $ite_ico = !empty($_cab->ico) ? _doc::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

        $_lis_val = [];
        foreach( _dat::get("api.app_art",[ 
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
          'ite'=>[ 'eti'=>"p", 
            'ide'=>$_cab->ide, 'class'=>"mar_ver-1 let-tit let-4", 'htm'=>$ite_ico._doc::let($_cab->nom) 
          ],
          'lis'=>$_lis_val 
        ];
      }
      // reinicio opciones
      _ele::cla($ele['lis'],"nav");
      _ele::cla($ele['dep'],DIS_OCU);
      $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'
      return _doc_lis::val($_lis,$ele);

    }
    // genero secciones del artículo por indices
    static function sec( string $ide ) : string {

      $_ = "";

      $_ide = explode('.',$ide);

      $_nav = _dat::get("api.app_art_nav",[ 
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
  // Articulo : dato + tabla + tablero + glosario
  class _app_art {

    // articulo por operador
    static function sec( object $nav, string $esq, string $cab ) : string {
      $_ = "";      

      $agr = _htm::dat($nav->ope);

      $_art = _dat::get("api.app_art",[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
      
      if( is_array( $tex = _dat::get('api.app_art_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

        foreach( $tex as $pal ){
          $_[ $pal->nom ] = $pal->des;
        }
      }

      // operadores : toggle + filtro
      if( !isset($ele['opc']) ) $ele['opc'] = [];

      return _doc_lis::ite($_,$ele);
    }
  }
  // Dato : valores, opciones, ficha, listado, tabla, atributos, descripciones, imagenes
  class _app_dat {

    static string $IDE = "_app_dat-";
    static string $EJE = "_app_dat.";

    // armo valores ( esq.est ): nombre, descripcion, imagen
    static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );
      // cargo datos
      $dat_var = _dat::get($esq,$est,$dat);
      // cargo valores
      $dat_val = _app::dat_val($esq,$est);

      // armo titulo : nombre <br> detalle
      if( $tip == 'tit' ){
        
        $_ = ( isset($dat_val->nom) ? _obj::val($dat_var,$dat_val->nom) : "" ).( isset($dat_val->des) ? "\n"._obj::val($dat_var,$dat_val->des) : "");
      }
      // por atributos con texto : nom + des + ima 
      elseif( isset($dat_val->$tip) ){

        if( is_string($dat_val->$tip) ) $_ = _obj::val($dat_var,$dat_val->$tip);
      }
      // armo imagen
      if( $tip == 'ima' ){
        if( !isset($ele['title']) ){

          $ele['title'] = _app_dat::val('tit',"$esq.$est",$dat);
        }
        elseif( $ele['title'] === FALSE  ){
          
          unset($ele['title']);
        }
        $_ = _doc::ima( [ 'style' => $_ ], $ele );
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
      // valor seleccionado
      if( isset($ope['val']) ) $_val = explode('-',$ope['val']);
      // cargo selector de estructura
      $ele_eje = isset($ope['ope']['onchange']) ? $ope['ope']['onchange'] : FALSE;
      $ele_val = [ 
        'eti'=>[ 'name'=>"val", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" ] 
      ];
      if( $opc_esq || $opc_est ){
        // operador por esquemas
        if( $opc_esq ){
          $dat_esq = [];
          $ele_esq = [ 
            'eti'=>[ 'name'=>"esq", 'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" ] 
          ];
        }// operador por estructuras
        $ele_est = [ 
          'eti'=>[ 'name'=>"est", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" ] 
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
        if( ( $dat_opc_ide = _app::dat($esq,$est,$ide) ) && is_array($dat_opc_ide) ){  
          // recorro atributos + si tiene el operador, agrego la opcion      
          foreach( $dat_opc_ide as $atr ){
            // cargo atributo
            $_atr = _dat::atr($esq,$est,$atr);
            // identificador
            $dat = "{$esq}.";
            if( !empty($_atr->var['dat']) ){ $dat = $_atr->var['dat']; }else{ $dat .= _dat::rel($esq,$est,$atr); }  
            $_ []= [                 
              'data-esq'=>$esq, 'data-est'=>$est, 'data-ide'=>$dat, 
              'value'=>"{$esq}.{$est}.{$atr}", 'class'=>$cla, 
              'htm'=>$_atr->nom 
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
        }
        // recorro estructura/s por esquema
        foreach( $est_lis as $est_ide ){
          // busco estructuras dependientes
          if( $dat_opc_est = _app::dat($esq_ide,$est_ide,'est') ){
            // recorro dependencias de la estructura
            foreach( $dat_opc_est as $dep_ide ){
              // datos de la estructura relacional
              $_est = _dat::est($esq_ide,$dep_ide);
              $ite_val = "{$esq_ide}.{$dep_ide}";
              // pido opciones por estructura y oculto en caso de haber valor seleccionado por estructura
              if( 
                !empty( $_opc_val = $_opc_ite($esq_ide, $dep_ide, $ide, $val_cla && ( !$val_est || $val_est != $ite_val ) ? $cla : "") ) 
              ){
                // con selector de estructura
                if( $opc_est ){
                  // cargo opcion de la estructura
                  $dat_est[] = [ 'value'=>$ite_val, 'htm'=>isset($_est->nom) ? $_est->nom : $dep_ide ];
                  // cargo todos los atributos a un listado general
                  array_push($dat_ope, ...$_opc_val);

                }// por agrupador
                else{
                  // agrupo por estructura
                  $ele_ope['gru'][$_est->ide] = isset($_est->nom) ? $_est->nom : $dep_ide;
                  // cargo atributos por estructura
                  $dat_ope[$_est->ide] = $_opc_val;
                }                    
              }
            }
          }// estructura sin dependencias
          else{
            $dat_ope[] = $_opc_ite($esq_ide, $est_ide, $ide);
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
    // valor por seleccion ( esq.est.atr ) : texto, variable, icono, ficha, colores, html
    static function ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );
      // parametros: "esq.est.atr" 
      $ide = 'NaN';
      if( !is_object($dat) ){

        $ide = $dat;
        $dat = _dat::get($esq,$est,$dat);
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
          
          if( $col = _app::dat_opc('col',$esq,$est,$atr) ){
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
          if( !empty($_ima) || !empty( $_ima = _app::dat_opc('ima',$esq,$est,$atr) ) ){
            
            $_ = _doc::ima($_ima['esq'],$_ima['est'],$dat->$atr,$ele);
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
    // Listado por estructura
    static function lis( string | array $dat, string $ide, string $tip, array $ele = [] ) : string {

      $_ = $dat;

      if( is_array($dat) ){

        $ele['lis']['est'] = $ide;

        _ele::cla($ele['lis'], "anc_max-fit mar-aut mar_ver-3");
        
        $_ = _doc_lis::$tip($dat, $ele);
      }
      return $_;
    }
    // ficha : valor[ima] => { ...imagenes por atributos } 
    static function fic( string $ide, mixed $val = NULL, array $ope = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );

      $_fic = _app::dat($esq,$est,'fic.val');

      if( isset($_fic->ide) ){ $_ .= 

        "<div class='val' data-esq='$esq' data-est='$est' data-atr='ide' data-ima='$esq.$est'>".
        
          ( !empty($val) ? _doc::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."

        </div>

        <c class='sep'>=></c>

        "._app_dat::fic_ima($esq,$est,isset($_fic->atr) ? $_fic->atr : [], $val);
      }     

      return $_;
    }
    // listado : ...( atributo = valor )
    static function fic_atr( mixed $dat, array $ope = [] ) : string {
      $_ide = self::$IDE."atr";
      $_eje = self::$EJE."atr";
      $_ = "";

      $_ide = $_ide;
      if( is_string($dat) ){
        $var_dat = _dat::ide($dat);        
        $esq = $var_dat['esq'];
        $est = $var_dat['est'];
        $_ide = "{$_ide} _$esq.$est";
        // datos
        $dat = _dat::get($dat,$ope);
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
        $dat = _dat::get($ide['esq'],$ide['est'],$dat);
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
    // de propiedades : imagen + nombre > ...atributos
    static function fic_des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
      
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
    // de imagenes : { ... ; ... }
    static function fic_ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {
      // busco valores
      if( isset($val) ) $val = _dat::get($esq,$est,$val);
      // busco atributos en : _dat.est.ope
      if( empty($atr) ) $atr = _app::dat($esq,$est,'fic.ima');
      $_ = "
      <ul class='val'>  
        <li><c>{</c></li>";        
        foreach( $atr as $atr ){
          $_ima = _app::dat_opc('ima',$esq,$est,$atr); $_ .= "
          <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
            ".( isset($val->$atr) ? _doc::ima($esq,"{$est}_{$atr}",$val->$atr,[ 'class'=>"tam-4" ]) : "" )."
          </li>";
        } $_ .= "
        <li><c>}</c></li>
      </ul>";
      return $_;
    }
  }
  // Valor : acumulado + sumatoria + filtro + conteos
  class _app_val {

    static array $OPE = [
      'acu'=>['nom'=>"Acumulados" ], 
      'ver'=>['nom'=>"Selección"  ], 
      'sum'=>['nom'=>"Sumatorias" ], 
      'cue'=>['nom'=>"Conteos"    ]
    ];
    static string $IDE = "_app_val-";
    static string $EJE = "_app_val.";

    // abm de la base
    static function abm( string $tip, array $ope = [], array $ele = [] ) : string {
      $opc = isset($ope['opc']) ? $ope['opc'] : [];
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";
      $_ope = [
        'ver'=>['nom'=>"Ver"        ], 
        'agr'=>['nom'=>"Agregar"    ], 
        'mod'=>['nom'=>"Modificar"  ], 
        'eli'=>['nom'=>"Eliminar"   ]
      ];      
      switch( $tip ){
      case 'nav':        
        $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
        if( !empty($url) ){
          $url_agr = "{$url}/0";
          $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
        }
        $_ .= "
        <fieldset class='ope' abm='{$tip}'>    
          "._doc::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']['nom'], 'onclick'=>"{$_eje}('ver');"])."
  
          "._doc::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']['nom'], 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."
  
          "._doc::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');"])."
        </fieldset>";
        break;
      case 'abm':
        $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
        $_ = "
        <fieldset class='ope mar-2 esp-ara'>

          "._doc::ico('dat_ini', [ 'eti'=>"button", 'title'=>$_ope[$tip]['nom'], 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

          if( in_array('eli',$ope['opc']) ){

            $_ .= _doc::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');" ]);
          }$_ .= "

          "._doc::ico('dat_fin', [ 'eti'=>"button", 'title'=>$_ope['fin']['nom'], 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

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
    // acumulado : posicion + marcas + seleccion
    static function acu( array $dat, array $ope = [], array $opc = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."acu";
      $_eje = self::$EJE."acu";

      if( empty($opc) ) $opc = array_keys($dat);

      $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

      if( !empty($ope['ide']) ) $_ide = $ope['ide'];

      $_ .= "
      <div class='ren'>";
        foreach( $opc as $ide ){        
          $_ .= _doc_val::var('app',"val.acu.$ide", [
            'ope'=> [ 
              'id'=>"{$_ide}-{$ide}", 'val'=>isset($dat[$ide]) ? $dat[$ide] : NULL, 'onchange'=>$_eje_val
            ],
            'htm_fin'=>( !empty($ope['ope']['htm_fin']) ? $ope['ope']['htm_fin'] : '' ).( !empty($ope["var-{$ide}"]['htm_fin']) ? $ope["var-{$ide}"]['htm_fin'] : '' )
          ]);
        }
        if( !empty($ope['htm_fin']) ){
          $_ .= $ope['htm_fin'];
        } $_ .= "
      </div>";
      return $_;
    }
    // sumatorias
    static function sum(  string $dat, mixed $val = [], array $ope = [] ) : string {
      extract( _dat::ide($dat) );

      $_ = "";
      $_ide = self::$IDE."sum"." _$esq-$est";

      // estructuras por esquema
      foreach( _app::var($esq,'val','sum') as $ide => $ite ){
    
        $_ .= _doc_val::var($esq,"val.sum.$ide",[
          'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
          // busco fichas del operador
          'htm_fin'=> !empty($ite['var_fic']) ? _app_dat::fic($ite['var_fic'], $val, $ope) : ''
        ]);
      }    

      return $_;
    }
    // filtros : texto + listado + datos
    static function ver( string $tip, string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      $_ite = function( $ide, $dat=[], $ele=[] ){

        if( !empty($ele['ope']['id']) ) $ele['ope']['id'] .= "-{$ide}"; 

        // impido tipos ( para fechas )
        if( ( $ide == 'inc' || $ide == 'lim' ) && isset($ele['ope']['_tip']) ) unset($ele['ope']['_tip']);
        
        // combino elementos
        if( !empty($dat[$ide]) && is_array($dat[$ide]) ) $ele['ope'] = _ele::jun($ele['ope'],$dat[$ide]);

        return $ele;
      };
      switch( $tip ){
      // dato : estructura => valores 
      case 'dat':
        // selector de estructura.relaciones para filtros
        array_push($opc,'est','val');
        $_ .= _doc_val::var('app',"val.ver.dat",[ 
          'ite'=>[ 'class'=>"tam-mov" ], 
          'htm'=>_app_dat::opc('ver',$dat,$ele,...$opc)
        ]);
        break;
      // listado : desde + hasta + cada + cuantos
      case 'lis': 
        // por defecto
        if( empty($dat) ) $dat = [ 'ini'=>[], 'fin'=>[] ];

        // desde - hasta
        foreach( ['ini','fin'] as $ide ){

          if( isset($dat[$ide]) ) $_ .= _doc_val::var('app',"val.ver.$ide", $_ite($ide,$dat,$ele));
        }

        // limites : incremento + cuantos ? del inicio | del final
        if( isset($dat['inc']) || isset($dat['lim']) ){
          $_ .= "
          <div class = 'ren'>";
            // cada
            if( isset($dat['inc']) ){
              $_ .= _doc_val::var('app',"val.ver.inc", $_ite('inc',$dat,$ele));
            }
            // cuántos
            if( isset($dat['lim']) ){
              $_eje = "_doc_val.var('mar', this, 'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
              $ele['htm_fin'] = "
              <fieldset class = 'ope'>
                "._doc::ico('lis_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
                "._doc::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
              </fieldset>"; 
              $_ .=
              _doc_val::var('app',"val.ver.lim", $_ite('lim',$dat,$ele) );
            }$_ .= "
          </div>";
        }
        break;
      }
      return $_;
    }
    // conteos : por valores de estructura relacionada por atributo
    static function cue( string $tip, string | array $dat, array $ope = [] ) : string | array {
      $_ = "";
      $_ide = self::$IDE."cue";
      $_eje = self::$EJE."cue";

      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide = "_$esq-$est $_ide";
      }

      switch( $tip ){        
      case 'dat': 
        $_ = [];
        
        // -> por esquemas
        foreach( ( is_array($dat) ? $dat : [ $esq=>[ $est ] ] ) as $esq => $est_lis ){

          // -> por estructuras
          foreach( $est_lis as $est_ide ){

            // -> por dependencias ( est_atr )
            foreach( ( !empty($dat_opc_est = _app::dat($esq,$est_ide,'est')) ? $dat_opc_est : [ $est_ide ] ) as $est ){

              // armo listado para aquellos que permiten filtros
              if( $dat_opc_ver = _app::dat($esq,$est,'opc.ver') ){
                // nombre de la estructura
                $est_nom = _dat::est($esq,$est)->nom;                
                $htm_lis = [];
                foreach( $dat_opc_ver as $atr ){
                  // armo relacion por atributo
                  $rel = _dat::rel($esq,$est,$atr);
                  // busco nombre de estructura relacional
                  $rel_nom = _dat::est($esq,$rel)->nom;
                  // armo listado : form + table por estructura
                  $htm_lis []= [ 
                    'ite'=>$rel_nom, 'htm'=>"
                    <div class='var mar_izq-2 dis-ocu'>
                      "._app_val::cue('est',"{$esq}.{$est}.{$atr}",$ope)."
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
        $ide = !empty($atr) ? _dat::rel($esq,$est,$atr) : $est;
        $_ = "
        <!-- filtros -->
        <form class='val'>

          "._doc_val::var('val','ver',[ 
            'nom'=>"Filtrar", 
            'id'=>"{$_ide}-ver {$esq}-{$ide}",
            'htm'=>_doc_val::ver([ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
          ])."
        </form>
  
        <!-- valores -->
        <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
          <tbody>";
          foreach( _dat::get($esq,$ide) as $ide => $_var ){
          
            $ide = isset($_var->ide) ? $_var->ide : $ide;
  
            if( !empty($atr) ){
              $ima = !empty( $_ima = _app::dat_opc('ima',$esq,$est,$atr) ) ? _doc::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
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
  }
  // Tabla : atributos + valores + filtros + columnas + titulo + detalle
  class _app_est {

    static array $OPE = [
      'val' => [ 'ide'=>'val', 'nom'=>"Valores"       , 
        'des'=>"",
      ],
      'atr' => [ 'ide'=>'atr', 'nom'=>"Columnas"      , 
        'des'=>"",
      ],
      'des' => [ 'ide'=>'des', 'nom'=>"Descripciones" , 
        'des'=>"",
      ],
      'cue' => [ 'ide'=>'cue', 'nom'=>"Cuentas"       , 
        'des'=>"",
      ]
    ];
    static string $IDE = "_app_est-";
    static string $EJE = "_app_est.";

    // operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
    static function ope( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      $_ope = self::$OPE;
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide = "_$esq-$est $_ide";
        $_est = _app::est($esq,$est,$ope);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
        $_ide = "_$esq-$est $_ide";
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::get($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];      
      
      switch( $tip ){
      case 'dat':
        foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
        // tipos de dato
        $_cue = [
          'opc'=>[ "Opción", 0 ], 
          'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
          'tex'=>[ "Texto",  0 ], 
          'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
          'obj'=>[ "Objeto",  0 ] 
        ];
        // cuento atributos por tipo
        foreach( $_est->atr as $atr ){
          $tip_dat = explode('_', _dat::atr($esq,$est,$atr)->ope['_tip'])[0];
          if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
        }
        // operador : toggles + filtros
        $_ .= "
        <form class='val jus-ini' dat='atr'>
  
          <fieldset class='ope'>
            "._doc::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
            "._doc::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
          </fieldset>
  
          "._doc_val::var('val','ver',[ 
            'nom'=>"Filtrar", 'htm'=>_doc_val::ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
          ])."
  
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

          $dat_tip = explode('_',$_atr->ope['_tip'])[0];
          if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
          if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
          if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
          if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
          $htm = "
          <form class='ren esp-bet'>
          
            "._app_val::ver('lis', isset($_cue[$dat_tip][2]) ? $_cue[$dat_tip][2] : [], [ 'ope'=>$_var ] )."
  
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
        break;
      // Valores : cantidad + acumulado + filtros
      case 'val': 
        $_ = "
        <h3 class='dis-ocu'>Valores</h3>";
        // acumulados
        if( isset($ope['val']['acu']) ){
          $_ .= "
          <form ide = 'acu'>
            <fieldset class = 'inf ren'>
              <legend>Acumulados</legend>

              "._doc_val::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
              
              "._doc_val::var('app',"val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
              
              "._app_val::acu($ope['val']['acu'],[
                'ide'=>$_ide, // agrego evento para ejecutar todos los filtros
                'eje'=>"{$_eje}_acu(this); ".self::$EJE.$tip."_ver();",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c> <n>0</n> <c class='sep'>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
          // filtros : datos + posicion + atributos
          $_eje .= "_ver";
          $dat_tot = count($ope['dat']);
          $_ .= "
          <div ide = 'ver'>
  
            <form ide = 'val'>
              <fieldset class = 'inf ren'>
                <legend>Filtrar por Datos</legend>
  
                "._app_val::ver('dat', $ope['est'], [
                  'ope'=>[ 'id'=>"{$_ide}-val", 'max'=>$dat_tot, 'onchange'=>"$_eje();" ] 
                ])."
              </fieldset>
            </form>  

            <form ide = 'fec'>
              <fieldset class = 'inf ren'>
                <legend>Filtrar por Fechas</legend>

                "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
                  'ope'=>[ 'id'=>"{$_ide}-fec", '_tip'=>"fec_dia", 'onchange'=>"$_eje();" ] 
                ])."            
              </fieldset>          
            </form>
            <!--
            <form ide = 'pos'>
              <fieldset class = 'inf ren'>
                <legend>Filtrar por Posiciones</legend>
  
                "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [                  
                  'ope'=>[ 'id'=>"{$_ide}-pos", 
                    '_tip'=>"num_int", 'min'=>"1", 'max'=>$dat_tot, 'onchange'=>"$_eje();" 
                  ]
                ])."
              </fieldset>
            </form>
            -->
          </div>";
        }
        // filtros por : cic + gru
        else{
        }
        break;
      // Columnas : ver/ocultar
      case 'atr':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
              
          foreach( $est_lis as $est ){
            // estrutura por aplicacion
            $_est = _app::est($esq,$est);
            // datos de la estructura
            $est_nom = _dat::est($esq,$est)->nom;
            // contenido : listado de checkbox en formulario
            $htm = "
            <form ide = '{$tip}' class='ren jus-ini mar_izq-2'>
              <fieldset class='ope'>
                "._doc::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
                'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
                "._doc::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
                'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."                
              </fieldset>";
              foreach( $_est->atr as $atr ){
                $htm .= _doc_val::var('val',$atr,[
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

        "._doc_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

        break;
      // Descripciones : titulo + detalle
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
                
                if( !empty($_est->{"{$pre}_{$ide}"}) ){ $htm = "
                  <form ide = '{$ide}' data-esq='{$esq}' data-est='{$est}' class='ren jus-ini mar_izq-2'>";
                  foreach( $_est->{"{$pre}_{$ide}"} as $atr ){
                    $htm .= _doc_val::var('val',$atr,[ 
                      'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                      'ope'=>[ '_tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                    ]);
                  }$htm .= "
                  </form>";
                  $lis_dep[] = [ 
                    'ite'=>_app::var('app','est','ver',$ide)['nom'], 
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

        "._doc_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

        break;
      // Cuentas : total + porcentaje
      case 'cue':
        $_ = "
        <h3 class='dis-ocu'>Cuentas</h3>

        "._doc_lis::val( 
          _app_val::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), 
          [ 
            'dep'=>[],
            'opc'=>[ 'tog', 'ver', 'cue' ]
          ]
        );
        break;
      }

      return $_;
    }

  }
  // tablero : opciones + posiciones + secciones
  class _app_tab {

    static array $OPE = [
      'opc' => [ 'ide'=>"opc", 'ico'=>"opc_bin", 'nom'=>"Opciones del Tablero",
        'des'=>"" 
      ],
      'val' => [ 'ide'=>"val", 'ico'=>"lis_est", 'nom'=>"Elementos del Tablero",
        'des'=>"" 
      ],
      'cue' => [ 'ide'=>"cue", 'ico'=>"lis_nav", 'nom'=>"Códigos y Cuentas",
        'des'=>"" 
      ],      
      'lis' => [ 'ide'=>"lis", 'ico'=>"lis_ite", 'nom'=>"Listado de Posiciones", 
        'des'=>""
      ]
    ];
    static array $ATR = [
      // joins por estructuras
      'est'=>[],
      // elementos del tablero
      'dat'=>[],
      // operador de valores
      'val'=>[ 'acu'=>[], 'pos'=>[] ]
    ];
    static string $IDE = "_app_tab-";
    static string $EJE = "_app_tab.";

    // operadores : valores + seleccion + posicion + opciones( posicion | secciones )
    static function ope( string $tip, string $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;
      // opciones
      $opc = isset($ope['opc']) ? $ope['opc'] : [];
      // proceso datos del tablero
      extract( _dat::ide($dat) );      
      // por aplicacion : posicion + seleccion
      if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];

      switch( $tip ){
      // Valores : acumulados + sumatorias + filtros
      case 'val':
        // por acumulados
        if( isset($ope['val']['acu']) ){
          $_ .= "
          <form ide = 'acu'>
            <fieldset class = 'inf ren'>
              <legend>Acumulados</legend>";

              $_ .= _doc_val::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ]);
              
              $_ .= _app_val::acu($ope['val']['acu'],[ 
                'ide'=>"{$_ide}_acu", 
                'eje'=>"{$_eje}_acu(this);",
                'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" ]
              ]);
              $_ .="
            </fieldset>
          </form>";
          // agrego sumatorias por aplicacion
          if( isset($ope['val']['pos']['kin']) ){ $_ .= "
            
            <form ide = 'sum'>
      
              <fieldset class='inf ren' data-esq='api' data-est='hol_kin'>
                <legend>Sumatorias del Kin</legend>
  
                "._app_val::sum('hol.kin',$ope['val']['pos']['kin'])."
  
              </fieldset>          
            </form>";
          }
          // Filtros : estructuras/db + posiciones + fechas
          $_ .= "
          <section ide = 'ver'>

            <form ide = 'val'>
              <fieldset class = 'inf ren'>
                <legend>Seleccionar por Datos</legend>

                "._app_val::ver('dat', $ope['est'], [ 
                  'ope'=>[ 'onchange'=>"{$_eje}_ver('val',this);" ] 
                ], 'ope_tam')."

              </fieldset>
            </form>

            <form ide = 'pos'>
              <fieldset class = 'inf ren'>
                <legend>Seleccionar por Posiciones</legend>

                "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [
                  'ope'=>[ '_tip'=>"num_int", 
                    'min'=>"1", 'max'=>"999", 'id'=>"{$_ide}-pos", 
                    'onchange'=>"{$_eje}_ver('pos',this);" 
                  ] 
                ])."
              </fieldset>
            </form>

            <form ide = 'fec'>
              <fieldset class = 'inf ren'>
                <legend>Seleccionar por Fechas</legend>

                "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
                  'ope'=>[ '_tip'=>"fec_dia", 
                    'id'=>"{$_ide}-fec", 'onchange'=>"{$_eje}_ver('fec',this);" 
                  ] 
                ])."            
              </fieldset>          
            </form> 

          </section>
          ";
        }
        // por estructura unica
        else{

        }
        break;
      // Opciones : sección + posición
      case 'opc':
        // controladores
        $_opc_var = function( $_ide, $tip, $esq, $ope, ...$opc ) : string {
          $_ = "";
          $_eje = "_{$esq}_app.tab_{$tip}";
          
          // solo muestro las declaradas en el operador
          $ope_val = isset($ope[$tip]) ? $ope[$tip] : $opc;

          $ope_atr = array_keys($ope_val);

          $ope_var = _app::var($esq,'tab',$tip);
    
          foreach( $ope_atr as $ide ){
    
            if( isset($ope_var[$ide]) ){
    
              $_ .= _doc_val::var($esq,"tab.$tip.$ide", [
                'ope'=>[
                  'id'=>"{$_ide}-{$ide}", 
                  'val'=>!empty($ope_val[$ide]) ? !empty($ope_val[$ide]) : NULL, 
                  'onchange'=>"$_eje(this);"
                ]
              ]);
            }
          } 
          return $_;
        };
        // secciones        
        if( !empty($ope[$tip_opc = 'sec']) ){
          $ele_ide = "{$_ide}-{$tip_opc}";
          $ele_eve = "{$_eje}_{$tip_opc}(this);";
          $ele['ope']['ide'] = $tip_opc; $_ .= "
          <form"._htm::atr($ele['ope']).">
            <fieldset class = 'inf ren'>
              <legend>Secciones</legend>";
              // operadores globales
              if( !empty($tab_sec = _app::var('app','tab',$tip_opc)) ){ $_ .= "
                <div class='val'>";
                foreach( _app::var('app','tab',$tip_opc) as $ide => $ite ){
                  if( isset($ope[$tip_opc][$ide]) ){ 
                    $_ .= _doc_val::var('app',"tab.$tip_opc.$ide", [ 
                      'val'=>$ope[$tip_opc][$ide], 
                      'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ] 
                    ]); 
                  }
                }$_ .= "
                </div>";
              }
              // operadores por aplicación
              $_ .= $_opc_var($_ide,$tip_opc,$esq,$ope)."

            </fieldset>
          </form>";          
        }
        // posiciones
        if( !empty($ope[$tip_opc = 'pos']) ){ 
          $ele_ide = "{$_ide}-{$tip_opc}";
          $ele_eve = "{$_eje}_{$tip_opc}(this);";
          $ele['ope']['ide'] = $tip_opc; $_ .= "
          <form"._htm::atr($ele['ope']).">    
            <fieldset class = 'inf ren'>
              <legend>Posiciones</legend>";
              // bordes            
              $ide = 'bor';
              $_ .= _doc_val::var('app',"tab.$tip_opc.$ide",[
                'val'=>isset($ope[$tip_opc][$ide]) ? $ope[$tip_opc][$ide] : 0,
                'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
              ]);                
              // sin acumulados : color de fondo - numero - texto - fecha
              foreach( ['col','num','tex','fec'] as $ide ){
                if( isset($ope[$tip_opc][$ide]) ){
                  $_ .= _doc_val::var('app',"tab.{$tip_opc}.{$ide}", [
                    'id'=>"{$ele_ide}-{$ide}",
                    'htm'=>_app_dat::opc($ide, $ope['est'], [
                      'val'=>$ope[$tip_opc][$ide], 
                      'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                    ])
                  ]);                      
                }
              }
              // con acumulados : imagen de fondo - ( ficha )
              foreach( ['ima'] as $ide ){
                if( isset($ope[$tip_opc][$ide]) ){ $_ .= "
                  <div class='ren'>";
                    // vistas por acumulados
                    $_ .= _doc_val::var('app',"tab.{$tip_opc}.{$ide}",[
                      'id'=>"{$ele_ide}-{$ide}",
                      'htm'=>_app_dat::opc($ide, $ope['est'], [ 
                        'val'=>$ope[$tip_opc][$ide], 
                        'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                      ])
                    ]);
                    if( isset($ope['val']['acu']) ){ 
                      foreach( array_keys($ope['val']['acu']) as $ite ){
                        $_ .= _doc_val::var('app',"tab.$tip_opc.{$ide}_{$ite}", [
                          'val'=>isset($ope[$tip_opc]["{$ide}_{$ite}"]) ? $ope[$tip_opc]["{$ide}_{$ite}"] : FALSE,
                          'ope'=>[ 'id'=>"{$ele_ide}-{$ide}_{$ite}", 'onchange'=>$ele_eve ]
                        ]);
                      }
                    }
                    $_ .= "
                  </div>";
                }
              }
              // operadores por aplicaciones                  
              $_ .= $_opc_var($_ide,$tip_opc,$esq,$ope)."
            </fieldset>    
          </form>";          
        }
        // atributos por aplicacion
        if( !empty($opc) ){
          $tip_opc = "atr"; $_ .= "
          
          <section ide='$tip_opc'>";
          foreach( $opc as $atr ){  
            $htm = "";
            $_eje = "_{$esq}_app.tab_{$atr}";

            foreach( _app::var($esq,$tip_opc,$atr) as $ide => $val ){
              $htm .= _doc_val::var($esq,"$tip_opc.$atr.$ide", [
                'ope'=>[ 
                  'id'=>"{$_ide}-{$atr}-{$ide}", 'dat'=>$atr, 
                  'val'=>isset($ope[$atr][$ide]) ? $ope[$atr][$ide] : NULL,
                  'onchange'=>"$_eje(this)" 
                ]
              ]);
            }          
            // busco datos del operador 
            if( 
              !empty($htm) && !empty($_ope = _app::var($esq,'tab',$tip_opc,$atr)) 
            ){
              $ele['ope']['ide'] = $atr; $_ .= "
              <form"._htm::atr($ele['ope']).">
                <fieldset class = 'inf ren'>
                  <legend>{$_ope['nom']}</legend>
                    {$htm}
                </fieldset>
              </form>";          
            }
          }$_ .= "
          </section>";
        }

        break;
      // cuentas : totales + porcentajes
      case 'cue':
        $_ = "

        "._doc_lis::val( 
          _app_val::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), 
          [ 
            'dep'=>[ 'class'=>"dis-ocu" ], 
            'opc'=>[ 'tog', 'ver', 'cue' ]
          ] 
        );
        break;
      // listado : Valores + Columnas + Descripciones
      case 'lis':
        // cargo operador
        $lis_ope = [ 
          'tit'=>[ 'cic', 'gru' ], 
          'det'=>[ 'des' ],
          'opc'=>[ "ite_ocu" ]
        ];
        // copio del tablero
        $lis_ope['est'] = isset($ope['est']) ? $ope['est'] : NULL;
        $lis_ope['dat'] = isset($ope['dat']) ? $ope['dat'] : NULL;
        $lis_ope['val'] = isset($ope['val']) ? $ope['val'] : NULL;        
        
        // cargo operadores del listado
        $_ope = _obj::nom(_app_est::$OPE,'ver',['val','atr','des']);
        foreach( $_ope as $ope_ide => &$ope_lis ){

          $ope_lis['htm'] = _app_est::ope($ope_ide,$dat,$lis_ope,$ele);
        }
        $ele['lis']['class'] = "";
        $_ =
          _doc_val::nav('bar',$_ope,[ 'lis'=>[] ],'tog')."
          
          <div var='est' style='height: 70vh;'>
            "._doc_lis::tab($dat,$lis_ope,$ele)."
          </div>";
        break;
      }
      return $_;
    }    
  }
