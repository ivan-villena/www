<?php
  
  // Página-app
  class _app {

    public array  $rec;
    public array  $ele;
    public object $esq;
    public object $cab;
    public object $art;
    public array  $nav;
    public array  $ope;
    public array  $htm;

    // cargo aplicacion ( por defecto: sincronario )
    function __construct( string $esq = 'hol' ){

      $_uri = $this->uri($esq);

      // Recursos : css + jso + eje + dat + obj
      $this->rec = [
        'css' => [ 'app' ],
        'jso' => [ 'api','app' ],
        'htm' => [],
        'eje' => "",
        'dat' => [
          'app'=>[ 'ico', 'uri', 'dat' ],
          'dat'=>[ 'tip' ],
          'tex'=>[ 'let' ],
          'fec'=>[ 'mes','sem','dia' ]
        ]
      ];
      // Elementos
      $this->ele = [
        'title'=> "{-_-}",
        'body' => [
          'data-doc'=> $_uri->esq,
          'data-cab'=> !!$_uri->cab ? $_uri->cab : NULL, 
          'data-art'=> !!$_uri->art ? $_uri->art : NULL 
        ]
      ];
      // Operadores : boton + panel + pantalla
      $this->ope = [
        // inicio
        'app_ini'=>[
          'ico'=>"app", 'url'=>SYS_NAV."/".$_uri->esq, 'nom'=>"Página de Inicio"
        ],// menu
        'app_cab'=>[
          'ico'=>"app_cab", 'tip'=>"pan", 'nom'=>"Menú Principal", 'htm'=>_app_nav::cab($_uri->esq)
        ],// indice
        'app_nav'=>[
          'ico'=>"app_nav", 'tip'=>"pan", 'nom' => "Índice", 'htm'=>""
        ],// login
        'ses_ini'=>[ 
          'ico'=>"app_ini", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Iniciar Sesión..." 
        ],// logout
        'ses_fin' => [ 
          'ico'=>"app_fin", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Cerrar Sesión..."
        ],// consola
        'api_adm'=>[ 
          'ico'=>"eje",     'bot'=>"fin", 'tip'=>"win", 'nom'=>"Consola del Sistema", 'art'=>[ 'style'=>"max-width: 55rem;" ]
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
      $this->esq = _dat::get('app_esq',[ 
        'ver'=>"`ide`='{$_uri->esq}'", 
        'opc'=>'uni' 
      ]);
      if( !empty($_uri->cab) ){
        // cargo datos del menu
        $this->cab = _dat::get('app_cab',[ 
          'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 
          'ele'=>'ope', 'opc'=>'uni' 
        ]);
        if( !empty($_uri->art) ){
          // cargo datos del artículo
          $this->art = _dat::get('app_art',[ 
            'ver'=>"`esq`='{$_uri->esq}' AND `cab`='{$_uri->cab}' AND `ide`='{$_uri->art}'", 
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
      // cargo índice de contenidos
      if( !empty($this->cab->nav) ){

        $this->nav = _dat::get('app_nav',[
          'ver'=>"`esq` = '{$_uri->esq}' AND `cab` = '{$_uri->cab}' AND `ide` = '{$_uri->art}'", 
          'ord'=>"pos ASC", 
          'nav'=>'pos'
        ]);
        // pido listado por navegacion
        if( !empty($this->nav[1]) ){
          $this->ope['app_nav']['htm'] = _app_nav::art($this->nav);
        }
      }
    }
    // cargo página
    public function doc() : void {

      global $sis_ini, $_usu;

      $_uri = $this->uri();
      // pido contenido por aplicacion
      if( file_exists($cla_rec = "./htm/{$_uri->esq}.php") ){

        require_once($cla_rec);

        if( class_exists( $cla = "_{$_uri->esq}_app" ) ){

          new $cla($this);
        }                
      }
      // usuario + loggin
      // $tip = empty($_usu->ide) ? 'ini' : 'fin';
      // $this->ope["ses_{$tip}"]['htm'] = _app::ses($tip);

      // consola del sistema
      if( $_usu->ide == 1 ){
        $this->rec['htm'] []= "app/adm";
        $this->ope['api_adm'] = [ 'ico'=>"eje", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Consola del Sistema", 
          'art'=> [ 'style'=>"max-width: 55rem;" ],
          'htm'=>_app::adm()
        ]; 
      }
      // agrego ayuda
      if( !empty($this->htm['dat']) ) $this->ope['app_dat'] = [ 
        'ico'=>"dat_des", 'tip'=>"win", 'nom'=>"Ayuda", 'htm'=>$this->htm['dat'] 
      ];
      // cargo documento
      foreach( $this->ope as $ide => $ope ){
        if( !isset($ope['bot']) ) $ope['bot'] = "ini";
        // enlaces
        if( isset($ope['url']) ){
          // boton
          $this->htm['ope'][$ope['bot']] .= _app::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( ( $ope['tip'] == 'pan' || $ope['tip'] == 'win' ) && !empty($ope['htm']) ){
          // botones          
          $this->htm['ope'][$ope['bot']] .= _app_ope::bot([ $ide => $ope ]);
          // contenido
          $this->htm[$ope['tip']] .= _app_ope::{$ope['tip']}($ide,$ope);
        }
      }
      // cargo modal de operadores
      $this->htm['win'] .= _app_ope::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);

      // cargo contenido principal
      $ele = [ 'tit' => $this->ele['title'] ];
      $this->htm['sec'] = _app_ope::sec( $this->htm['sec'], $ele );
      
      // ajusto diseño
      $_ver = [];
      foreach( ['bar','pie'] as $ide ){
        if( !empty($this->htm[$ide]) ) $_ver []= $ide; 
      }
      if( !empty($_ver) ) $this->ele['body']['data-ver'] = implode(',',$_ver);

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

        <body <?= _ele::atr($this->ele['body']) ?>>
          
          <!-- Botonera -->
          <header class='app_bot'>
            
            <nav class="ope">
              <?= $this->htm['ope']['ini']; ?>
            </nav>

            <nav class="ope">
              <?= $this->htm['ope']['fin']; ?>
            </nav>
            
          </header>

          <?php if( !empty($this->htm['pan']) ){ ?>
            <!-- Panel -->
            <aside class='app_pan dis-ocu'>
              <?= $this->htm['pan'] ?>
            </aside>
          <?php } ?>

          <!-- Contenido -->
          <main class="app_sec">
            <?= $this->htm['sec'] ?>
          </main>
          
          <?php if( !empty($this->htm['bar']) ){ ?>
            <!-- sidebar -->
            <aside class="app_bar">
              <?= $this->htm['bar'] ?>
            </aside>
          <?php } ?>

          <?php if( !empty($this->htm['pie']) ){  ?>
            <!-- pie de página -->
            <footer class="app_pie">
              <?= $this->htm['pie'] ?>
            </footer>
          <?php } ?>

          <!-- Modales -->
          <section class='app_win dis-ocu'>
            <?= $this->htm['win'] ?>
          </section>

          <!-- Programas -->
          <script>
            // sistema
            const SYS_NAV = "<?=SYS_NAV?>";
            // operativas
            const DIS_OCU = "<?=DIS_OCU?>";
            const FON_SEL = "<?=FON_SEL?>";
            const BOR_SEL = "<?=BOR_SEL?>";
          </script>
          <?php 
          foreach( ['jso','htm'] as $tip ){
            foreach( $this->rec[$tip] as $ide ){
              if( file_exists( $rec = "$tip/$ide.js" ) ){ echo "
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
    }// consola del sistema
    static function adm() : string {
      $_eje = "_adm";  
      $_ide = "api-adm";
      return _app_nav::sec('bar', [

        'aja' => [ 'nom'=>"AJAX",
          'nav'=>[ 'onclick'=>"$_eje('aja',this);" ],
          'htm'=>"
    
          <nav class='lis'>
          </nav>
          "
        ],
        'ico' => [ 'nom'=>"Íconos", 
          'nav'=>[ 'onclick'=>"$_eje('ico',this);" ],
          'htm'=>"
          
          "._app_ope::var('val','ver',['nom'=>"Filtrar",'ope'=>[ 
            '_tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"$_eje('ico',this,'ver')" 
          ]])."
    
          <ul class='lis ite mar-2' style='height: 53vh;'>
          </ul>
          "
        ],
        'jso' => [ 'nom'=>"J.S.", 
          'htm'=>"
    
          <fieldset class='inf pad-3'>
            <legend>Ejecutar JavaScript</legend>      
    
            "._app_ope::var('val','cod',[ 
              'ite'=>[ 'class'=>"tam-cre" ], 
              'ope'=>[ '_tip'=>"tex_par", 'rows'=>"10", 'class'=>"anc-100", 'oninput'=>"$_eje('jso',this)" ] 
            ])."
    
          </fieldset>
    
          <div class='ope_res mar-1'>
          </div>"
        ],  
        'php' => [ 'nom'=>"P.H.P.",
          'htm'=>"
    
          <fieldset class='inf ite pad-3'>
            <legend>Ejecutar en PHP</legend>
    
            "._app_ope::var('val','ide',[ 'ope'=>[ '_tip'=>"tex_ora" ] ])."
            
            "._app_ope::var('val','par',[ 
              'ite'=>['class'=>"tam-cre"], 
              'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
              'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
            ])."
    
            "._app_ope::var('val','htm',[
              'nom'=>"¿HTML?",
              'ope'=>[ '_tip'=>"opc_bin", 'val'=>1, 'id'=>"_adm-php-htm" ]
            ])."
            
            "._app::ico('dat_ope',[
              'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('php',this)"
            ])."
    
          </fieldset>
    
          <div class='ope_res mar-1' style='height: 40vh; overflow: auto;'>
          </div>
    
          <pre class='ope_res' style='height: 40vh; overflow: auto;'>
          </pre>
          "
        ],
        'sql' => [ 'nom'=>"S.Q.L",
          'htm'=>"
          <fieldset class='inf ite pad-3'>
            <legend>Ejecutar S.Q.L.</legend>
    
            "._app_ope::var('val','cod',[ 
              'ite'=>[ 'class'=>"tam-cre" ], 
              'ope'=>[ '_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1" ],
              'htm_fin'=>_app::ico('dat_ope',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('sql',this,'cod')" ])
            ])."
    
          </fieldset>
    
          <div class='ope_res mar-1' var='est' style='height: 47vh;'>
          </div>"
        ],
        'htm' => [ 'nom'=>"D.O.M.",
          'htm'=>"
          <fieldset class='inf ite pad-3'>
            <legend>Ejecutar Selector</legend>
    
            "._app_ope::var('val','cod',[ 
              'ite'=>['class'=>"tam-cre"], 
              'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1"],
              'htm_fin'=>_app::ico('dat_ope',['eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('htm',this,'cod')"])
            ])."
    
          </fieldset>
    
          <div class='ele'>
          </div>
    
          <div class='nod mar-1'>
          </div>"
        ] 
      ], [
        'sel' => "php",
        'ite' => [ 'eti'=>"form" ]
      ]);
    }// sesion del usuario
    static function ses( string $tip ) : string {
      switch( $tip ){
        // datos del usuario
        case 'usu':
          $esq = 'api'; 
          $est = 'usu';
          global $_usu;
          $_kin = _hol::_('kin',$_usu->kin);
          $_psi = _hol::_('psi',$_usu->psi);
          $_ = "
          <form class='api_dat' data-esq='{$esq}' data-est='{$est}'>
  
            <fieldset class='ren'>
  
              "._app_ope::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$_usu->$atr  ], 'eti')."
  
              "._app_ope::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$_usu->$atr  ], 'eti')."                        
            
            </fieldset>
  
            <fieldset class='ren'>
  
              "._app_ope::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$_usu->$atr  ],'eti')."
  
              "._app_ope::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$_usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."
  
            </fieldset>
  
          </form>";
          break;
        // inicio de sesion por usuario
        case 'ini':
          $_ = "
          <form class='api_dat' action=''>
  
            <fieldset>
  
              <label for=''>Email</label>
              <input type='mail'>
  
            </fieldset>
  
            <fieldset>
  
              <label for=''>Password</label>
              <input type='password'>
  
            </fieldset>
  
          </form>";
          break;
        // finalizo sesion del usuario
        case 'fin': 
          $_ = "    
          <form class='api_dat' action=''>

          </form>";
          break;
      }  
      return $_;
    }

    // letra : ( n, c )
    static function let( string $dat, array $ele=[] ) : string {
      global $_api;
      $_let = $_api->tex_let;
      $_pal = [];
      foreach( explode(' ',$dat) as $pal ){
        // numero completo
        if( is_numeric($pal) ){
          //if( preg_match("/,/",$pal) ) $pal = str_replace(",","<c>,</c>",$pal);
          //if( preg_match("/\./",$pal) ) $pal = str_replace(".","<c>.</c>",$pal);
          $_pal []= "<n>{$pal}</n>";
        }// caracteres
        else{
          $let = [];
          foreach( _tex::let($pal) as $car ){
            if( is_numeric($car) ){
              $let []= "<n>{$car}</n>";
            }elseif( isset($_let[$car]) ){
              //_ele::cla($ele_let,"{$_let[$car]->var}",'ini');
              $let []= "<c>{$car}</c>";        
            }else{
              $let []= $car;
            }
          }
          $_pal []= implode('',$let);
        }
      }
      return implode(' ',$_pal);
    }
    // icono : .ico.$ide
    static function ico( string $ide, array $ele=[] ) : string {
      $_ = "<span class='ico'></span>";
      global $_api;
      $_ico = $_api->app_ico;
      if( isset($_ico[$ide]) ){
        $eti = 'span';
        if( isset($ele['eti']) ){
          $eti = $ele['eti'];
          unset($ele['eti']);
        }
        if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button"; $_ = "
        <{$eti}"._ele::atr(_ele::cla($ele,"ico $ide material-icons-outlined",'ini')).">{$_ico[$ide]->val}</{$eti}>";
      }
      return $_;
    }
    // imagen : .ima.$ide
    static function ima( ...$dat ) : string {
      $_ = "";
      // por aplicacion
      if( isset($dat[2]) ){
        $ele = isset($dat[3]) ? $dat[3] : [];
        $_ = _app_dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], $ele );
      }
      // por directorio
      else{
        $ele = isset($dat[1]) ? $dat[1] : [];
        $dat = $dat[0];
        // por estilos : bkg
        if( is_array($dat) ){
          $ele = _ele::jun( $dat, $ele );          
        }
        // por directorio : localhost/img/esquema/image
        elseif( is_string($dat)){
          $ima = explode('.',$dat);
          $dat = $ima[0];
          $tip = isset($ima[1]) ? $ima[1] : 'png';
          $dir = SYS_NAV."img/{$dat}";
          _ele::css( $ele, _ele::fon($dir,['tip'=>$tip]) );
        }
        // etiqueta
        $eti = 'span';
        if( isset($ele['eti']) ){
          $eti = $ele['eti'];
          unset($ele['eti']);
        }// codifico boton
        if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button";
        // ide de imagen
        _ele::cla($ele,"ima",'ini');
        // contenido
        $htm = "";
        if( !empty($ele['htm']) ){
          _ele::cla($ele,'dis-fle dir-ver jus-cen ali-cen');
          $htm = $ele['htm'];
          unset($ele['htm']);
        }
        $_ = "<{$eti}"._ele::atr($ele).">{$htm}</{$eti}>";
      }
      return $_;
    }

    // peticion
    public function uri( string $esq = "" ) : object {
      global $_api;      

      if( empty($_api->app_uri->esq) ){

        $uri = explode('/', !empty($_REQUEST['uri']) ? $_REQUEST['uri'] : '');

        $_uri = new stdClass;
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
  
        $_api->app_uri = $_uri;
      }
      return $_api->app_uri;
    }
    // directorios
    public function dir() : object {

      $_ = new stdClass();
      
      $_uri = $this->uri();
      
      $_->esq = SYS_NAV."{$_uri->esq}";
        
      $_->cab = "{$_uri->esq}/{$_uri->cab}";

      $_->ima = SYS_NAV."img/{$_->cab}/";

      if( !empty($_uri->art) ){

        $_->art = $_->cab."/{$_uri->art}";
      
        $_->ima .= "{$_uri->art}/";
      }

      return $_;
    }
    // cargo datos
    static function dat( string $esq, string $est, string $ope, mixed $dat = NULL ) : mixed {
      
      global $_api;
      if( !isset($_api->app_dat[$esq][$est]) ){
        
        $_api->app_dat[$esq][$est] = _dat::get('app_dat',[
          'ver'=>"`esq`='{$esq}' AND `ide`='{$est}'", 
          'ele'=>"ope",
          'red'=>"ope",
          'opc'=>"uni"
        ]);
      }
      $_ = $_api->app_dat[$esq][$est];

      // cargo atributo
      foreach( ( $ope_atr = explode('.',$ope) ) as $ide ){

        $_ = is_array($_) && isset($_[$ide]) ? $_[$ide] : FALSE;
      }
      // proceso valores con datos
      if( $ope_atr[0] == 'val' && isset($dat) ) $_ = _obj::val( _dat::get($esq,$est,$dat), $_ );

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
            foreach( ( !empty($dat_est = _app::dat($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
              // acumulo valores
              if( isset($dat->$ide) ) $_["{$ref}"] = $dat->$ide;
            }
          }
        }
        global $_api;
        $_api->app_val []= $_;
      }
      return $_;
    }
    // armo controlador : nombre => valor
    static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
      global $_api;
      $_ = [];
      
      if( empty($dat) ){
        if( !isset($_api->app_var[$esq]) ){
          $_api->app_var[$esq] = _dat::get('app_var',[
            'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }elseif( empty($val) ){
        if( !isset($_api->app_var[$esq][$dat]) ){
          $_api->app_var[$esq][$dat] = _dat::get('app_var',[
            'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>['atr'], 'red'=>'atr'
          ]);
        }
      }else{
        if( !isset($_api->app_var[$esq][$dat][$val]) ){
          $_api->app_var[$esq][$dat][$val] = _dat::get('app_var',[
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
  }

  // Contenido : botonera + navegador + pantalla + seccion + paneles + opciones + carteles + ...
  class _app_ope {
    
    static string $IDE = "_app_ope-";
    static string $EJE = "_app_ope.";
    static array $VAR = [
      'ico'=>"", 'nom'=>"", 'des'=>"", 
      'ite'=>[], 'eti'=>[], 'ope'=>[], 
      'htm'=>"", 'htm_pre'=>"", 'htm_med'=>"", 'htm_pos'=>"" 
    ];
    // botones ( pan + win )
    static function bot( $dat ) : string {
      $_ = "";      
      $_eje = self::$EJE;
      
      foreach( $dat as $ide => $art ){
        
        $tip = isset($art['tip']) ? $art['tip'] : 'nav';

        $eje_tog = "{$_eje}{$tip}('$ide');";

        if( is_string($art) ){

          $_ .= _app::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_tog ]);
        }
        elseif( is_array($art) ){

          if( isset($art[0]) ){

            $_ .= _app::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_tog ]);
          }
          elseif( isset($art['ico']) ){

            $_ .= _app::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_tog ]);
          }
        }
        elseif( is_object($art) && isset($art->ico) ){

          $_ .= _app::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_tog ]);
        }
      }
      return $_;
    }
    // Modal : #sis > article[ide] > header + section
    static function win( string $ide, array $ope = [] ) : string {
      foreach( ['art','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
      $_eje = self::$EJE."win";
      $_ = "";      
      // icono de lado izquierdo
      $cab_ico = "";
      if( isset($ope['ico']) ) $cab_ico = _app::ico($ope['ico'],['class'=>"mar_hor-1"]);
      // titulo al centro
      $cab_tit = "";
      if( isset($ope['nom']) ) $cab_tit = "
        <h2 style='text-decoration: none; margin:0;'>"._app::let($ope['nom'])."</h2>
      ";
      // contenido 
      if( !isset($ope['htm']) ){
        $ope['htm'] = '';
      }
      elseif( is_array($ope['htm']) ){ 
        $ope['htm'] = _ele::dec( $ope['htm'] );
      }      
      // imprimo con identificador
      _ele::cla($ope['art'],"ide-$ide",'ini');
      _ele::cla($ope['art'],DIS_OCU);
      $_ = "
      <article"._ele::atr($ope['art']).">

        <header"._ele::atr($ope['cab']).">
        
          {$cab_ico} {$cab_tit} "._app::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"win-fin", 'onclick'=>"$_eje(this);" ])."

        </header>

        <div"._ele::atr($ope['sec']).">

          {$ope['htm']}

        </div>

      </article>";
      
      return $_;
    }
    // Panel : nav|article[ide] > header + section
    static function pan( string $ide, array $ope = [] ) : string {
      foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
      $_eje = self::$EJE."pan";
      $_ = "";
      $cab_ico = "";
      if( !empty($ope['ico']) ) $cab_ico = _app::ico($ope['ico'],['class'=>"mar_hor-1"]);

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
        $ope['htm'] = _ele::dec( $ope['htm'] );
      }

      // imprimo con identificador
      _ele::cla($ope['nav'],"ide-$ide",'ini');
      _ele::cla($ope['nav'],DIS_OCU);
      $_ = "
      <$eti_nav"._ele::atr($ope['nav']).">

        <header"._ele::atr($ope['cab']).">
        
          {$cab_ico} {$cab_tit} "._app::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

        </header>

        <$eti_sec"._ele::atr($ope['sec']).">

          {$ope['htm']}

        </$eti_sec>

      </$eti_nav>";
      
      return $_;
    }
    // Articulos : main > ...article
    static function sec( string | array $dat, array $ele = [] ) : string {
      $_ = "";
      if( isset($ele['tit']) ){ $_ .= "
        <header"._ele::atr( isset($ele['cab']) ? $ele['cab'] : [] ).">";
          if( is_string($ele['tit']) ){ $_ .= "
            <h1 class='mar-0'>"._app::let($ele['tit'])."</h1>";
          }else{
            $_ .= _ele::dec(...$ele['tit']);
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
            _ele::cla($art,"ide-$ide",'ini');
            $_ .= "
            <article"._ele::atr($art).">
              {$art['htm']}
            </article>";
          }
        }
      }
      return $_;
    }
    // Carteles : advertencia + confirmacion
    static function tex( string $tip, string | array $val, array $ope = [] ) : string {
      foreach( ['sec','ico','tex'] as $i ){ if( !isset($ope[$i]) ) $ope[$i] = []; }
      _ele::cla($ope['sec'],"val_tex".( !empty($tip) ? " -$tip" : "" ),'ini');

      $_ = "
      <div"._ele::atr($ope['sec']).">";

        if( !empty($ope['cab']) ){
          $_ .= "
          <div class='ite esp-ara'>
            <span></span>
            "._app::let($ope['cab'])."
            <span></span>
          </div>";
        }

        if( !empty($tip) ){
          switch( $tip ){
          case 'err': $ope['ico']['title'] = "Error..."; break;
          case 'adv': $ope['ico']['title'] = "Advertencia..."; break;
          case 'opc': $ope['ico']['title'] = "Consultas..."; break;
          case 'val': $ope['ico']['title'] = "Notificación..."; break;
          }
          $_ .= _app::ico("val_tex-{$tip}", $ope['ico']);
        }

        $_ .= ( is_string($val) ? "<p"._ele::atr($ope['tex']).">"._app::let($val)."</p>" : _ele::dec($val) )."

      </div>";
      return $_;
    }
    // Menú de opciones
    static function opc(){
    }
    // Filtros : operador + valor textual + ( totales )
    static function ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
      $_ = "
      <fieldset class='ite'>";      
      // opciones de filtro por texto
      $_ .= _app_ope::var_ope(['ver','tex'],[
        'ite'=>[ 
          'dat'=>"()($)dat()" 
        ],
        'eti'=>[ 
          'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
          'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
        ]
      ]);
      // ingreso de valor a filtrar
      $_ .= _app_tex::ope('ora', isset($dat['val']) ? $dat['val'] : '', [ 
        'id'=>isset($dat['ide']) ? $dat['ide'] : NULL, 
        'name'=>"val",
        'title'=>"Introducir un valor de búsqueda...",
        'oninput'=>!empty($dat['eje']) ? $dat['eje'] : NULL,
        'class'=>isset($ele['class']) ? $ele['class'] : NULL,
        'style'=>isset($ele['style']) ? $ele['class'] : NULL
      ]);
      // agrego totales
      if( isset($dat['cue']) ){ $_ .= "
        <p class='mar_izq-1' title='Items totales'>
          <c>(</c><n name='tot'>".( is_array($dat['cue']) ? count($dat['cue']) : $dat['cue'] )."</n><c>)</c>
        </p>";
      }
      $_ .= "
      </fieldset>";
      return $_;
    }
    // Variable : div.atr > label + (input,textarea,select,button)[name]
    static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
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

        if( !empty($_atr = _dat::atr($esq,$est,$atr)) ) $_var = [ 
          'nom'=>$_atr->nom, 
          'ope'=>$_atr->var 
        ];
      }
      // carga operadores: esquema - dato - valor
      elseif( $tip != 'val' ){ 

        $_var = _app::var($tip,$esq,$est,$atr);
      }

      // combino operadores
      if( !empty($_var) ){

        if( !empty($_var['ope']) ){
          $ele['ope'] = _ele::jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
          unset($_var['ope']);
        }
        $ele = _obj::jun($ele,$_var);
      }
      // identificadores
      if( empty($ele['ope']['id'])  && !empty($ele['ide']) ){
        $ele['ope']['id'] = $ele['ide'];
      }
      // aseguro valor
      if( isset($ele['val']) && !isset($ele['ope']['val']) ){
        $ele['ope']['val'] = $ele['val'];
      }
      // nombre en formulario
      if( empty($ele['ope']['name']) ){
        $ele['ope']['name'] = $atr;
      }      
      // agregados
      $agr = _ele::htm($ele);

      // etiqueta
      if( !isset($ele['eti']) ) $ele['eti'] = [];
      $eti_htm='';
      if( !in_array('eti',$opc) ){
        if( !empty($ele['ico']) ){
          $eti_htm = _app::ico($ele['ico']);
        }
        elseif( !empty($ele['nom']) ){    
          $eti_htm = _app::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
        }
        if( !empty($eti_htm) ){    
          if( isset($ele['ope']['id']) ) $ele['eti']['for'] = $ele['ope']['id'];     
          $eti_htm = "<label"._ele::atr($ele['eti']).">{$eti_htm}</label>";
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
      // valor            
      if( isset($agr['htm']) ){
        $val_htm = $agr['htm'];
      }
      else{
        if( isset($ele['val']) ){
          $ele['ope']['val'] = $ele['val'];
        }
        if( empty($ele['ope']['name']) && isset($ele['ide']) ){
          $ele['ope']['name'] = $ele['ide'];
        }
        $val_htm = _ele::val($ele['ope']);
      }
      // contenedor
      if( !isset($ele['ite']) ) $ele['ite']=[];      
      if( !isset($ele['ite']['title']) ){
        $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';
      }    
      return "
      <div"._ele::atr(_ele::cla($ele['ite'],"atr",'ini')).">
        ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
        {$eti_ini}
        {$val_htm}
        {$eti_fin}
        ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
      </div>
      ";   
    }// selector de operaciones : select > ...option
    static function var_ope( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {
      global $_api;
      $_ = [];

      if( !isset($_api->app_var_ope[$dat[0]][$dat[1]]) ){

        $_dat = _dat::get( $_api->dat_ope, [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );
  
        $_api->app_var_ope[$dat[0]][$dat[1]] = _app_opc::val( $_dat, $ope, ...$opc);
      }
  
      $_ = $_api->app_var_ope[$dat[0]][$dat[1]];

      return $_;
    }// id por posicion
    static function var_ide( string $ope ) : string {

      global $_api;

      if( !isset($_api->app_var_ide[$ope]) ) $_api->app_var_ide[$ope] = 0;

      $_api->app_var_ide[$ope]++;

      return $_api->app_var_ide[$ope];

    }
    // Contenido : visible/oculto
    static function val( string | array $dat = NULL, array $ele = [] ) : string {
      $_eje = self::$EJE."val";
      foreach( ['val','ico'] as $eti ){ if( !isset($ele[$eti]) ) $ele[$eti]=[]; }
      
      // contenido textual
      if( is_string($dat) ) $dat = [
        'eti'=>"p", 'class'=>"let-enf let-cur", 'htm'=>_app::let($dat) 
      ];

      // contenedor : icono + ...elementos          
      _ele::eje( $dat,'cli',"$_eje(this);",'ini');

      return "
      <div"._ele::atr( _ele::cla( $ele['val'],"val tog",'ini') ).">
      
        "._app_ope::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

        ".( isset($ele['htm_ini']) ? _ele::val($ele['htm_ini']) : '' )."
        
        "._ele::val( $dat )."

        ".( isset($ele['htm_fin']) ? _ele::val($ele['htm_fin']) : '' )."

      </div>";
    }// - icono de toggle
    static function val_ico( array $ele = [] ) : string {
      $_eje = self::$EJE."val";
      return _app::ico('val_tog', _ele::eje($ele,'cli',"$_eje(this);",'ini'));
    }// - expandir / contraer
    static function val_ope( array $ele = [], ...$opc ) : string {
      $_ide = self::$IDE."val";
      $_eje = self::$EJE."val";      

      if( !isset($ele['ope']) ) $ele['ope'] = [];
      _ele::cla($ele['ope'],"ope",'ini');

      $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
      return "
      <fieldset"._ele::atr($ele['ope']).">
        "._app::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
        "._app::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
      </fieldset>";
    }
  }
  // Navegadores : menu + secciones por indice
  class _app_nav {
    static string $IDE = "_app_nav-";
    static string $EJE = "_app_nav.";

    // genero secciones del artículo por indices
    static function htm( string $ide ) : string {

      $_ = "";

      $_ide = explode('.',$ide);

      $_nav = _dat::get('app_nav',[ 
        'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 
        'nav'=>'pos' 
      ]);

      if( isset($_nav[1]) ){

        foreach( $_nav[1] as $nv1 => $_nv1 ){ $_ .= "
          
          <h2 id='_{$nv1}-'>"._app::let($_nv1->nom)."</h2>
          <section>";
            if( isset($_nav[2][$nv1]) ){
              foreach( $_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "
  
            <h3 id='_{$nv1}-{$nv2}-'>"._app::let($_nv2->nom)."</h3>
            <section>";
              if( isset($_nav[3][$nv1][$nv2]) ){
                foreach( $_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "
  
              <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>"._app::let($_nv3->nom)."</h4>
              <section>";
                if( isset($_nav[4][$nv1][$nv2][$nv3]) ){
                  foreach( $_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "
  
                <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>"._app::let($_nv4->nom)."</h5>
                <section>";
                  if( isset($_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                    foreach( $_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "
  
                  <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>"._app::let($_nv5->nom)."</h6>
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
    // menu principal : titulo + descripcion + listado > item = [icono] + enlace
    static function cab( string $esq, array $ele = [] ) : string {
      global $_usu;      
      foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

      // armo listado de enlaces
      $_lis = [];
      foreach( _dat::get('app_cab',[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

        if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($_usu->ide) ) ){
          continue;
        }

        $ite_ico = !empty($_cab->ico) ? _app::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

        $_lis_val = [];
        foreach( _dat::get('app_art',[ 
          'ver'=>"`esq`='$esq' AND `cab`='$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art 
        ){

          $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

          if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

          $ele_val['href'] = SYS_NAV."/$_art->esq/$_art->cab/$_art->ide";

          $_lis_val []= "
          <a"._ele::atr($ele_val).">"
            .( !empty($_art->ico) ? _app::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
            ."<p>"._app::let($_art->nom)."</p>
          </a>";
        }
        $_lis []= [ 
          'ite'=>[ 'eti'=>"p", 'class'=>"ide-$_cab->ide mar_ver-1 let-tit let-4", 'htm'=>$ite_ico._app::let($_cab->nom) ],
          'lis'=>$_lis_val 
        ];
      }
      // reinicio opciones
      _ele::cla($ele['lis'],"nav");
      _ele::cla($ele['dep'],DIS_OCU);
      $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'
      return _app_lis::val($_lis,$ele);

    }
    // indices : a[href] > ...a[href]
    static function art( array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_eje = self::$EJE."art_";// val | ver
      $_ = "";

      // operador
      _ele::cla( $ele['ope'], "ren", 'ini' );
      $_ .= "
      <form"._ele::atr($ele['ope']).">

        "._app_ope::val_ope()."

        "._app_ope::ver([ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}ver(this);" ])."      

      </form>";
      // dependencias
      $tog_dep = FALSE;
      if( in_array('tog_dep',$opc) ){
        _ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
        <form"._ele::atr($ele['ope_dep']).">
  
          "._app_ope::val_ope()."
  
        </form>";
      }
      // armo listado de enlaces
      $_lis = [];
      $opc_ide = in_array('ide',$opc);
      _ele::cla( $ele['lis'], "nav", 'ini' );
      foreach( $dat[1] as $nv1 => $_nv1 ){
        $ide = $opc_ide ? $_nv1->ide : $nv1;
        $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_app::let("{$_nv1->nom}") ];
        if( !isset($dat[2][$nv1]) ){
          $_lis []= _ele::val($eti_1);
        }
        else{
          $_lis_2 = [];
          foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
            $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
            $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_app::let("{$_nv2->nom}") ];
            if( !isset($dat[3][$nv1][$nv2])  ){
              $_lis_2 []= _ele::val($eti_2);
            }
            else{
              $_lis_3 = [];              
              foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
                $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
                $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_app::let("{$_nv3->nom}") ];
                if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                  $_lis_3 []= _ele::val($eti_3);
                }
                else{
                  $_lis_4 = [];                  
                  foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                    $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                    $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_app::let("{$_nv4->nom}") ];
                    if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                      $_lis_4 []= _ele::val($eti_4);
                    }
                    else{
                      $_lis_5 = [];                      
                      foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                        $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                        $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_app::let("{$_nv5->nom}") ];
                        if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                          $_lis_5 []= _ele::val($eti_5);
                        }
                        else{
                          $_lis_6 = [];
                          foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                            $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                            $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=>_app::let("{$_nv6->nom}") ];
                            if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                              $_lis_6 []= _ele::val($eti_6);
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
      _ele::cla($ele['dep'],DIS_OCU);
      $ele['opc'] = [];
      $_ .= _app_lis::val($_lis,$ele);
      return $_;
    }
    // secciones : nav + * > ...[nav="ide"]
    static function sec( string $tip, array $dat, array $ele = [], ...$opc ) : string {
      foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
      $_ = "";
      $_eje = self::$EJE."sec";
      $opc_ico = in_array('ico',$opc);
      $val_sel = isset($ele['sel']) ? $ele['sel'] : FALSE;
      // navegador 
      _ele::cla($ele['lis'], $tip, 'ini');
      $_ .= "
      <nav"._ele::atr($ele['lis']).">";    
      foreach( $dat as $ide => $val ){

        if( is_object($val) ) $val = _obj::nom($val);

        if( isset($val['ide']) ) $ide = $val['ide'];

        $ele_nav = isset($val['nav']) ? $val['nav'] : [];

        $ele_nav['eti'] = 'a';
        _ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

        if( $val_sel && $val_sel == $ide ) _ele::cla($ele_nav,FON_SEL);

        if( $opc_ico && isset($val['ico']) ){
          $ele_nav['title'] = $val['nom'];
          _ele::cla($ele_nav,"mar-0 pad-1 cir-1 tam-4",'ini');
          $_ .= _app::ico($val['ico'],$ele_nav);
        }
        else{
          $ele_nav['htm'] = $val['nom'];
          $_ .= _ele::val($ele_nav);
        }        
      }$_.="
      </nav>";
      // contenido
      $eti_sec = isset($ele['sec']['eti']) ? $ele['sec']['eti'] : 'div';
      $eti_ite = isset($ele['ite']['eti']) ? $ele['ite']['eti'] : 'section';
      if( $tip != 'pes' && !$val_sel ) _ele::cla($ele['sec'],DIS_OCU);
      $_ .= "
      <$eti_sec"._ele::atr($ele['sec']).">";
        foreach( $dat as $ide => $val ){
          $ele_ite = $ele['ite'];
          _ele::cla($ele_ite,"ide-$ide",'ini');
          if( !$val_sel || $val_sel != $ide ) _ele::cla($ele_ite,DIS_OCU);
          $_ .= "
          <$eti_ite"._ele::atr($ele_ite).">
            ".( isset($val['htm']) ? ( is_array($val['htm']) ? _ele::dec($val['htm']) : $val['htm'] ) : '' )."
          </$eti_ite>";
        }$_.="
      </$eti_sec>";

      return $_;
    }    
  }
  // Articulo : dato + tabla + tablero + glosario
  class _app_art {

    // articulo por operador
    static function sec( object $nav, string $esq, string $cab ) : string {
      $_ = "";      

      $agr = _ele::htm($nav->ope);

      $_art = _dat::get('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
              $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>"._app::let($art->nom)."</a>";
              if( !empty($art->ope['tex']) ){
                $_ .= "            
                <div class='val nav'>
                  "._app_ope::val_ico()."
                  {$art_url}
                </div>
                <div class='dat'>
                  "._ele::val($art->ope['tex'])."
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
      
      if( is_array( $tex = _dat::get('app_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

        foreach( $tex as $pal ){
          $_[ $pal->nom ] = $pal->des;
        }
      }

      // operadores : toggle + filtro
      if( !isset($ele['opc']) ) $ele['opc'] = [];

      return _app_lis::ite($_,$ele);
    }
  }
  // Dato : valores, opciones, imagenes, informe, ficha, listados
  class _app_dat {

    static string $IDE = "_app_dat-";
    static string $EJE = "_app_dat.";

    // armo valores ( esq.est ): nombre, descripcion, imagen
    static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );
      // cargo datos
      $_dat = _dat::get($esq,$est,$dat);
      // cargo valores
      $_val = _app::dat($esq,$est,'val');

      // armo titulo : nombre <br> detalle
      if( $tip == 'tit' ){
        
        $_ = ( isset($_val['nom']) ? _obj::val($_dat,$_val['nom']) : "" ).( isset($_val['des']) ? "\n"._obj::val($_dat,$_val['des']) : "");
      }
      // por atributos con texto : nom + des + ima 
      elseif( isset($_val[$tip]) ){

        if( is_string($_val[$tip]) ) $_ = _obj::val($_dat,$_val[$tip]);
      }

      // ficha
      if( $tip == 'ima' ){

        _ele::cla($ele,"{$esq}_{$est} ide-{$_dat->ide}",'ini');
        // cargo titulos
        if( !isset($ele['title']) ){
          $ele['title'] = _app_dat::val('tit',"$esq.$est",$_dat);
        }
        elseif( $ele['title'] === FALSE  ){          
          unset($ele['title']);
        }
        // acceso a informe
        if( !isset($ele['onclick']) ){
          if( _app::dat($esq,$est,'inf') ) _ele::eje($ele,'cli',"_app_dat.inf('$esq','$est',".intval($_dat->ide).")");
        }
        elseif( $ele['onclick'] === FALSE ){
          unset($ele['onclick']);
        }
        $_ = _app::ima( [ 'style' => $_ ], $ele );
      }
      // variable
      elseif( $tip == 'var' ){
        
        $_ = "";

      }
      // textos
      elseif( !!$ele ){  

        if( empty($ele['eti']) ) $ele['eti'] = 'p';
        $ele['htm'] = _app::let($_);
        $_ = _ele::val($ele);
      }    

      return $_;
    }
    // por seleccion ( esq.est.atr ) : en tablero y estructura : variable, html, ficha, color, texto, numero, fecha
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
          $_ = _ele::val($_val);
        }// proceso texto con letras
        elseif( $tip == 'htm' ){

          $_ = _app::let($dat->$atr);
        }// color en atributo
        elseif( $tip == 'col' ){
          
          if( $col = _dat::opc('col',$esq,$est,$atr) ){
            $_ = "<div"._ele::atr(_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
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
          if( !empty($_ima) || !empty( $_ima = _dat::opc('ima',$esq,$est,$atr) ) ){
            
            $_ = _app::ima($_ima['esq'],$_ima['est'],$dat->$atr,$ele);
          }
          else{
            $_ = "<div class='err fon-roj' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
          }
        }// por tipos de dato
        elseif( $tip == 'tip' || in_array($tip,['num','tex','fec']) ){

          if( $tip=='tip' ){
            $tip = $_atr->var_dat;
          }
          if( $tip == 'num' ){
            $_ = "<n"._ele::atr($ele).">{$dat->$atr}</n>";
          }
          elseif( $tip == 'tex' ){
            $_ = "<p"._ele::atr($ele).">"._app::let($dat->$atr)."</p>";
          }
          elseif( $tip == 'fec' ){
            $ele['value'] = $dat->$atr;
            $_ = "<time"._ele::atr($ele).">"._app::let($dat->$atr)."</time>";
          }
          else{
            $_ = _app::let($dat->$atr);
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
    // selector : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
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
      $ele_val = [ 'eti'=>[ 'name'=>"val", 'class'=>"mar_ver-1", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" ] ];
      if( $opc_esq || $opc_est ){
        // operador por esquemas
        if( $opc_esq ){
          $dat_esq = [];
          $ele_esq = [ 'eti'=>[ 'name'=>"esq", 'class'=>"mar_ver-1", 'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" ] ];
        }
        // operador por estructuras
        $ele_est = [ 'eti'=>[ 'name'=>"est", 'class'=>"mar_ver-1", 'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" ] ];
        
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
        if( ( $dat_opc_ide = _app::dat($esq,$est,"opc.$ide") ) && is_array($dat_opc_ide) ){
          // recorro atributos + si tiene el operador, agrego la opcion      
          foreach( $dat_opc_ide as $atr ){
            // cargo atributo
            $_atr = _dat::atr($esq,$est,$atr);
            // armo identificador
            $dat = "{$esq}."._dat::rel($esq,$est,$atr);
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
          
          if( $dat_opc_est = _app::dat($esq_ide,$est_ide,'rel') ){

            // recorro dependencias de la estructura
            foreach( $dat_opc_est as $dep_ide ){
              // redundancia de esquemas
              $dep_ide = str_replace("{$esq_ide}_",'',$dep_ide);
              // datos de la estructura relacional
              $_est = _dat::est($esq_ide,$dep_ide);
              $ite_val = "{$esq_ide}.{$dep_ide}";
              // pido opciones por estructura y oculto en caso de haber valor seleccionado por estructura
              if( !empty( $_opc_val = $_opc_ite($esq_ide, $dep_ide, $ide, $val_cla && ( !$val_est || $val_est != $ite_val ) ? $cla : "") ) ){
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
        $_ .= _app_opc::val($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
      }
      // selector de estructura [opcional]
      if( $opc_esq || $opc_est ){
        $_ .= _app_opc::val($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
      }
      // selector de atributo con nombre de variable por operador
      $_ .= _app_opc::val($dat_ope,$ele_ope,'nad');
      
      // selector de valor por relacion
      if( $opc_val ){
        // copio eventos
        if( $ele_eje ) $ele_val['eti'] = _ele::eje($ele_val['eti'],'cam',$ele_eje);
        $_ .= "
        <c class='sep'>:</c>
        <div class='val'>
          "._app_opc::val( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
          <span class='ico'></span>
        </div>";
      }

      return $_;
    }
    // Descripciones : imagen + nombre > ...atributos
    static function des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
      
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
          _app::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
          <dl>
            <dt>
              ".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " "._obj::val($_dat,$des) : "" )."
            </dt>";
            foreach( $atr as $ide ){ 
              if( isset($_dat->$ide) ){ $htm .= "
                <dd>".( preg_match("/_des/",$ide) ? "<q>"._app::let($_dat->$ide)."</q>" : _app::let($_dat->$ide) )."</dd>";
              }
            }$htm .= "
          </dl>";
          $_ []= $htm;
        }
      }

      return _app_lis::$tip( $_, $ele, ...$opc );
    }
    // Imagenes : listado por => { ... ; ... }
    static function ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {
      // busco valores
      if( isset($val) ) $val = _dat::get($esq,$est,$val);
      // busco atributos en : _dat.est.ope
      if( empty($atr) ) $atr = _app::dat($esq,$est,'fic.ima');
      // Elementos
      if( !isset($ope['ima']) ) $ope['ima'] = [];
      if( empty($ope['ima']['class']) ) _ele::cla($ope['ima'],"tam-4");
      $_ = "
      <ul class='val'>
        <li><c>{</c></li>";        
        foreach( $atr as $atr ){
          $_ima = _dat::opc('ima',$esq,$est,$atr); $_ .= "
          <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
            ".( isset($val->$atr) ? _app::ima($esq,"{$est}_{$atr}",$val->$atr,$ope['ima']) : "" )."
          </li>";
        } $_ .= "
        <li><c>}</c></li>
      </ul>";
      return $_;
    }
    // Atributos : listado por => atributo: "valor"
    static function atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."inf_atr _$esq.$est";      
      $_eje = self::$EJE."inf_atr";
      
      // cargo dato
      if( !is_object($dat) ) $dat = _dat::get($esq,$est,$dat);

      // cargo atributos
      $dat_atr = _dat::atr($esq,$est);      
      $ite = [];
      foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){
        if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ $ite []= "
          <b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> "._app::let($dat->$atr);
        }
      }

      $_ = _app_lis::ite($ite,$ope);          

      return $_;
    }
    // ficha : valor.ima => { ...imagen por atributos } 
    static function fic( string $ide, mixed $val = NULL, array $ope = [] ) : string {
      $_ = "";
      // proceso estructura
      extract( _dat::ide($ide) );
      if( ( $_fic = _app::dat($esq,$est,'fic') ) && isset($_fic[0]) ){ $_ .= 

        "<div class='val' data-esq='$esq' data-est='$est' data-atr='{$_fic[0]}' data-ima='$esq.$est'>".
        
          ( !empty($val) ? _app::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."
        </div>";
        // imágenes de atributos
        if( !empty($_fic[1]) ){ $_ .= "
          <c class='sep'>=></c> 
          "._app_dat::ima($esq,$est,$_fic[1], $val);
        }
      }
      return $_;
    }
    // informe : nombre + descripcion > imagen + atributos | lectura > detalle > tablero > ...
    static function inf( string $esq, string $est, mixed $dat = NULL, array $ope = NULL ) : string {
      $_ = "";      
      if( $_inf = isset($ope) ? $ope : _app::dat($esq,$est,'inf') ){
        // cargo datos
        $_dat = _dat::get($esq,$est,$dat);
        // cargo valores
        $_val = _app::dat($esq,$est,'val');
        // anulo informes de imagenes
        $ele_ima = [];
        if( !isset($ope) ) $ele_ima = [ 'onclick'=>false ];
        
        // opciones
        $opc = [];
        if( isset($_inf['opc']) ){ 
          $opc = _lis::ite($_inf['opc']);
          unset($_inf['opc']);
        }
        // nombre: 
        if( in_array('nom',$opc) && isset($_dat->nom) ){
          $_ .= "<p class='tit'>"._app::let($_dat->nom)."</p>";
        }// por valor
        elseif( isset($_val['nom'])  ){
          $_ .= "<p class='tit'>"._app::let(_obj::val($_dat,$_val['nom']))."</p>";
        }
        // descripciones
        $_ .= "
        <section>";          
          if( in_array('des',$opc) && isset($_dat->des) ){
            $_ .= "<p class='des'>"._app::let($_dat->des)."</p>";
          }// por valor
          elseif( in_array('des-val',$opc) && isset($_val['des'])  ){
            $_ .= "<p class='des'>"._app::let(_obj::val($_dat,$_val['des']))."</p>";
          }
          // imagen + atributos | lectura
          $_ .= "
          <div class='val jus-cen mar-1'>

            "._app::ima($esq,$est,$_dat,_ele::jun([ 'class'=>"mar_der-1" ],$ele_ima));          

            if( !empty($_inf['atr']) ){
              $_ .= _app_dat::atr($esq,$est,$_inf['atr'],$_dat);
              unset($_inf['atr']);
            }
            elseif( !empty($_inf['cit']) ){
              if( isset($_dat->{$_inf['cit']}) ) $_ .= "<q>"._app::let($_dat->{$_inf['cit']})."</q>";
              unset($_inf['cit']);
            }
            $_ .= "
          </div>";
          $_ .= "
        </section>";
        // imprimo por orden
        foreach( $_inf as $inf_ide => $inf_val ){ $_ .= "
          <section>";
          switch( $inf_ide ){
          case 'ima':
            $_ .= _app_dat::ima($esq,$est,$inf_val,$_dat,[ 'ima'=>$ele_ima ]);
            break;
          case 'tab':
            //if( !isset($inf_val[1]) ) $inf_val[2] = [];
            $inf_val[1]['ide'] = $_dat->ide;
            // anulo acceso a informes
            $inf_val[2]['ima']['onclick'] = FALSE;
            $_ .= _app_dat::tab($inf_val[0],$inf_val[1],$inf_val[2]);
            break;
          case 'lec': 
            foreach( _lis::ite($inf_val) as $lec ){
              if( isset($_dat->$lec) ) $_ .= "<q>"._app::let($_dat->$lec)."</q>";
            }
            break;
          case 'des': 
            foreach( _lis::ite($_inf['det']) as $des ){
              $_ .= "<p>"._app::let(_obj::val($_dat,$des))."</p>";
            }
            break;
          }$_ .= "
          </section>";
        }
      }
      return $_;
    }
    // tablero 
    static function tab( $ide, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      extract( _dat::ide($ide) );
      if( $esq && $est && $atr && class_exists($_cla = "_app_{$esq}") && method_exists($_cla,'tab') ){
        $_ .= $_cla::tab( $est, $atr, $ope, $ele );
      }
      return $_;
    }
    // Listado
    static function lis( string | array $dat, string $ide, string $tip, array $ele = [] ) : string {
      $_ = $dat;
      if( is_array($dat) ){
        if( !isset($ele['lis']) ) $ele['lis'] = [];
        _ele::cla($ele['lis'], "ide-{$ide}",'ini');
        $_ = _app_lis::$tip($dat, $ele);
      }
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

    // cargo Valores : absoluto o con dependencias ( api.dat->est ) 
    static function dat( array $ope ) : array {      
      $_ = [];
      // cargo temporal
      foreach( $ope as $esq => $est_lis ){
        // recorro estructuras del esquema
        foreach( $est_lis as $est => $dat ){
          // recorro dependencias            
          foreach( ( !empty($dat_est = _app::dat($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
            // acumulo valores
            if( isset($dat->$ide) ) $_["{$ref}"] = $dat->$ide;
          }
        }
      }
      global $_api;
      $_api->app_val []= $_;

      return $_;
    }
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
          "._app::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']['nom'], 'onclick'=>"{$_eje}('ver');"])."
  
          "._app::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']['nom'], 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."
  
          "._app::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');"])."
        </fieldset>";
        break;
      case 'abm':
        $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
        $_ = "
        <fieldset class='ope mar-2 esp-ara'>

          "._app::ico('dat_ini', [ 'eti'=>"button", 'title'=>$_ope[$tip]['nom'], 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

          if( in_array('eli',$ope['opc']) ){

            $_ .= _app::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');" ]);
          }$_ .= "

          "._app::ico('dat_fin', [ 'eti'=>"button", 'title'=>$_ope['fin']['nom'], 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

        </fieldset>";
        break;              
      case 'est':
        $_ .= "
        <fieldset class='ope'>    
          "._app::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
          
          "._app::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
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
          $_ .= _app_ope::var('app',"val.acu.$ide", [
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
    
        $_ .= _app_ope::var($esq,"val.sum.$ide",[
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
        $_ .= _app_ope::var('app',"val.ver.dat",[ 
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

          if( isset($dat[$ide]) ) $_ .= _app_ope::var('app',"val.ver.$ide", $_ite($ide,$dat,$ele));
        }

        // limites : incremento + cuantos ? del inicio | del final
        if( isset($dat['inc']) || isset($dat['lim']) ){
          $_ .= "
          <div class='ren'>";
            // cada
            if( isset($dat['inc']) ){
              $_ .= _app_ope::var('app',"val.ver.inc", $_ite('inc',$dat,$ele));
            }
            // cuántos
            if( isset($dat['lim']) ){
              $_eje = "_app_ope.var('mar',this,'bor-sel');".( isset($ele['ope']['onchange']) ? " {$ele['ope']['onchange']}" : "" );
              $ele['htm_fin'] = "
              <fieldset class='ope'>
                "._app::ico('lis_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
                "._app::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
              </fieldset>"; 
              $_ .=
              _app_ope::var('app',"val.ver.lim", $_ite('lim',$dat,$ele) );
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
            foreach( ( !empty($dat_opc_est = _app::dat($esq,$est_ide,'rel')) ? $dat_opc_est : [ $est_ide ] ) as $est ){
              $est = str_replace("{$esq}_",'',$est);
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

          "._app_ope::var('val','ver',[ 
            'nom'=>"Filtrar", 
            'id'=>"{$_ide}-ver {$esq}-{$ide}",
            'htm'=>_app_ope::ver([ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
          ])."
        </form>
  
        <!-- valores -->
        <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
          <tbody>";
          foreach( _dat::get($esq,$ide) as $ide => $_var ){
          
            $ide = isset($_var->ide) ? $_var->ide : $ide;
  
            if( !empty($atr) ){
              $ima = !empty( $_ima = _dat::opc('ima',$esq,$est,$atr) ) ? _app::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
            }
            else{
              $ima = _app::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
            }$_ .= "
            <tr class='pos' data-ide='{$ide}'>
              <td data-atr='ima'>{$ima}</td>
              <td data-atr='ide'>"._app::let($ide)."</td>
              <td data-atr='nom'>"._app::let(isset($_var->nom) ? $_var->nom : '')."</td>
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
  // Tablero : opciones + posiciones + secciones
  class _app_tab {

    static string $IDE = "_app_tab-";
    static string $EJE = "_app_tab.";
    static array $OPE = [
      'ver' => [ 'ide'=>"ver", 'ico'=>"dat_ver", 'nom'=>"Selección",'des'=>"" ],
      'opc' => [ 'ide'=>"opc", 'ico'=>"opc_bin", 'nom'=>"Opciones", 'des'=>"" ],
      'val' => [ 'ide'=>"val", 'ico'=>"est",     'nom'=>"Datos",    'des'=>"" ],
      'lis' => [ 'ide'=>"lis", 'ico'=>"lis_ite", 'nom'=>"Listado",  'des'=>"" ]
    ];
    static array $ATR = [
      'est'=>[],// joins      
      'dat'=>[],// datos
      // valores
      'val'=>[ 'acu'=>[], 'pos'=>[], 'mar'=>[], 'ver'=>[], 'opc'=>[] ],
      'val_acu'=>[],
      'val_pos'=>[],
      'val_mar'=>[],
      'val_ver'=>[],
      'val_opc'=>[],
      // opciones
      'opc'=>[]
    ];
    
    // armo Tablero
    static function dat( string $esq, string $est, array $ele = NULL ) : array | object {
      global $_api;

      if( !isset($_api->app_tab[$esq][$est]) ){
        $_api->app_tab[$esq][$est] = _dat::get('app_tab',[ 
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
    // operadores : valores + seleccion + posicion + opciones( posicion | secciones )
    static function ope( string $tip, string $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      $_ide = self::$IDE.$tip;
      $_eje = self::$EJE.$tip;
      $_ope = self::$OPE;
      // elementos
      if( !isset($ele['ope']) ) $ele['ope'] = [];
      // opciones
      $opc = isset($ope['opc']) ? $ope['opc'] : [];
      // proceso datos del tablero
      extract( _dat::ide($dat) );      
      // por aplicacion : posicion + seleccion
      if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];
      
      switch( $tip ){
      // Valores : acumulados + sumatorias + cuentas
      case 'val':
        // por acumulados
        $_ .= "
        <form class='ide-acu'>
          <fieldset class='inf ren'>
            <legend>Acumulados</legend>";

            $_ .= _app_ope::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ]);
            
            $_ .= _app_val::acu($ope['val']['acu'],[ 
              'ide'=>"{$_ide}_acu", 
              'eje'=>"{$_eje}_acu(this);",
              'ope'=>[ 'htm_fin'=>"<span><c class='sep'>(</c><n>0</n><c class='sep'>)</c></span>" ]
            ]);
            $_ .="
          </fieldset>
        </form>";
        // sumatorias por aplicacion
        if( isset($ope['val']['pos']['kin']) ){ $_ .= "
          <form class='ide-sum'>
    
            <fieldset class='inf ren' data-esq='hol' data-est='kin'>
              <legend>Sumatorias del Kin</legend>

              "._app_val::sum('hol.kin',$ope['val']['pos']['kin'])."

            </fieldset>          
          </form>";
        }
        // cuentas por estructura
        $_ .= "
        <section class='ide-cue inf'>
          <h3>Totales por Estructura</h3>

          "._app_lis::val( _app_val::cue('dat',$ope['est'],['ide'=>$_ide]), [ 'dep'=>['class'=>DIS_OCU], 'opc'=>['tog','ver','cue'] ])."
          
        </section>";
        break;
      // Opciones : sección + posición
      case 'opc':
        // controladores
        $_opc_var = function( $_ide, $tip, $esq, $ope, ...$opc ) : string {
          $_ = "";
          $_eje = "_{$esq}_ope.tab_{$tip}";
          
          // solo muestro las declaradas en el operador
          $ope_val = isset($ope[$tip]) ? $ope[$tip] : $opc;

          $ope_atr = array_keys($ope_val);

          $ope_var = _app::var($esq,'tab',$tip);
    
          foreach( $ope_atr as $ide ){
    
            if( isset($ope_var[$ide]) ){
    
              $_ .= _app_ope::var($esq,"tab.$tip.$ide", [
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
          $ele_ope = $ele['ope'];
          _ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
          <form"._ele::atr($ele_ope).">
            <fieldset class='inf ren'>
              <legend>Secciones</legend>";
              // operadores globales
              if( !empty($tab_sec = _app::var('app','tab',$tip_opc)) ){ $_ .= "
                <div class='val'>";
                foreach( _app::var('app','tab',$tip_opc) as $ide => $ite ){
                  if( isset($ope[$tip_opc][$ide]) ){ 
                    $_ .= _app_ope::var('app',"tab.$tip_opc.$ide", [ 
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
          $ele_ope = $ele['ope'];
          _ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
          <form"._ele::atr($ele_ope).">
            <fieldset class='inf ren'>
              <legend>Posiciones</legend>";
              // bordes            
              $ide = 'bor';
              $_ .= _app_ope::var('app',"tab.$tip_opc.$ide",[
                'val'=>isset($ope[$tip_opc][$ide]) ? $ope[$tip_opc][$ide] : 0,
                'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
              ]);                
              // sin acumulados : color de fondo - numero - texto - fecha
              foreach( ['col','num','tex','fec'] as $ide ){
                if( isset($ope[$tip_opc][$ide]) ){
                  $_ .= _app_ope::var('app',"tab.{$tip_opc}.{$ide}", [
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
                    $_ .= _app_ope::var('app',"tab.{$tip_opc}.{$ide}",[
                      'id'=>"{$ele_ide}-{$ide}",
                      'htm'=>_app_dat::opc($ide, $ope['est'], [ 
                        'val'=>$ope[$tip_opc][$ide], 
                        'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                      ])
                    ]);
                    if( isset($ope['val']['acu']) ){ 
                      foreach( array_keys($ope['val']['acu']) as $ite ){
                        $_ .= _app_ope::var('app',"tab.$tip_opc.{$ide}_{$ite}", [
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
          
          <section class='ide-$tip_opc'>";
          foreach( $opc as $atr ){  
            $htm = "";
            $_eje = "_{$esq}_ope.tab_{$atr}";

            foreach( _app::var($esq,$tip_opc,$atr) as $ide => $val ){
              $htm .= _app_ope::var($esq,"$tip_opc.$atr.$ide", [
                'ope'=>[ 
                  'id'=>"{$_ide}-{$atr}-{$ide}", 'dat'=>$atr, 
                  'val'=>isset($ope[$atr][$ide]) ? $ope[$atr][$ide] : NULL,
                  'onchange'=>"$_eje(this)" 
                ]
              ]);
            }          
            // busco datos del operador 
            if( !empty($htm) && !empty($_ope = _app::var($esq,'tab',$tip_opc,$atr)) ){
              $ele_ope = $ele['ope'];
              _ele::cla($ele_ope,"ide-$tip_opc-$atr",'ini'); $_ .= "
              <form"._ele::atr($ele_ope).">
                <fieldset class='inf ren'>
                  <legend>{$_ope['nom']}</legend>
                    {$htm}
                </fieldset>
              </form>";          
            }
          }$_ .= "
          </section>";
        }
        break;
      // Seleccion : estructuras/db + posiciones + fechas
      case 'ver':         
        $_ .= "
        <form class='ide-val'>
          <fieldset class='inf ren'>
            <legend>Seleccionar por Datos</legend>

            "._app_val::ver('dat', $ope['est'], [ 
              'ope'=>[ 'onchange'=>"{$_eje}('val',this);" ] 
            ], 'ope_tam')."

          </fieldset>
        </form>

        <form class='ide-pos'>
          <fieldset class='inf ren'>
            <legend>Seleccionar por Posiciones</legend>

            "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [
              'ope'=>[ '_tip'=>"num_int", 
                'min'=>"1", 'max'=>"999", 'id'=>"{$_ide}-pos", 
                'onchange'=>"{$_eje}('pos',this);" 
              ] 
            ])."
          </fieldset>
        </form>

        <form class='ide-fec'>
          <fieldset class='inf ren'>
            <legend>Seleccionar por Fechas</legend>

            "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
              'ope'=>[ '_tip'=>"fec_dia", 
                'id'=>"{$_ide}-fec", 'onchange'=>"{$_eje}('fec',this);" 
              ] 
            ])."            
          </fieldset>          
        </form> 
        ";
        break;
      // listado : Valores + Columnas + Descripciones
      case 'lis':
        // cargo operador
        $lis_ope = [ 'tit'=>[ 'cic', 'gru' ], 'det'=>[ 'des' ], 'opc'=>[ "ite_ocu" ] ];

        // copio del tablero
        $lis_ope['est'] = isset($ope['est']) ? $ope['est'] : NULL;
        $lis_ope['dat'] = isset($ope['dat']) ? $ope['dat'] : NULL;
        $lis_ope['val'] = isset($ope['val']) ? $ope['val'] : NULL;        
        
        // cargo operadores del listado
        $_ope = _obj::nom(_app_est::$OPE,'ver',['val','ver','atr','des']);
        foreach( $_ope as $ope_ide => &$ope_lis ){ $ope_lis['htm'] = _app_est::ope($ope_ide,$dat,$lis_ope,$ele); }
        $_ =

        _app_nav::sec('ope',$_ope,[ 'lis'=>['class'=>"mar-1 mar_arr-0"] ],'ico','tog')."

        "._app_est::lis($dat,$lis_ope,$ele);

        break;
      }
      return $_;
    }
    // posicion
    static function pos(){
    }
    // seccion
    static function sec(){
    }
  }
  // Tabla
  class _app_est {
    
    static string $IDE = "_app_est-";
    static string $EJE = "_app_est.";    
    static array $LIS = [
      // identificador : esq.est
      'ide'=>"",
      // opciones
      'opc'=>[
        'cab_ocu',  // ocultar cabecera de columnas
        'ite_ocu',  // oculto items: en titulo + detalle
        'det_cit',  // en detalle: agrego comillas
        'ima',      // buscar imagen para el dato
        'var',      // mostrar variable en el dato
        'htm'       // convertir texto html en el dato
      ],
      // columnas del listado
      'atr'=>[],
      'atr_tot'=>0,// columnas totales
      'atr_ocu'=>[],// columnas ocultas
      'atr_dat'=>[],// datos de las columnas
      // estructuras por esquema => [ ...$esq =>[ ...$est ] ]
      'est'=>[],
      'est_dat'=>[],// datos y operadores por estructura
      // filas: valores por estructura [...{...$}]
      'dat'=>[],
      'dat_ite'=>[],// titulos + detalles
      'dal_val'=>[],// datos por fila
      // Valores : acumulado + posicion principal
      'val'=>[ 'acu'=>[], 'pos'=>[] ],
      // titulos: por base {'cic','gru','des'} o por operador [$pos]
      'tit'=>[],
      'tit_cic'=>[],// titulos por ciclos
      'tit_gru'=>[],// titulos por agrupaciones
      // detalles: por base {'cic','gru','des'} o por operador [$pos]
      'det'=>[],
      'det_cic'=>[],// detalle por ciclos
      'det_gru'=>[],// detalle por agrupaciones
      'det_des'=>[] // detalle por descripciones
    ];
    static array $OPE = [
      'val' => [ 'ide'=>'val', 'ico'=>"est",     'nom'=>"Valores"       , 'des'=>"" ],
      'ver' => [ 'ide'=>'ver', 'ico'=>"dat_ver", 'nom'=>"Filtros"       , 'des'=>"" ],
      'atr' => [ 'ide'=>'atr', 'ico'=>"lis_ver", 'nom'=>"Columnas"      , 'des'=>"" ],
      'des' => [ 'ide'=>'des', 'ico'=>"lis_gru", 'nom'=>"Descripciones" , 'des'=>"" ],
      'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav", 'nom'=>"Cuentas"       , 'des'=>"" ]
    ];

    // armo Listado-Tabla 
    static function dat( string $esq, string $est, array $ope = NULL ) : array {
      global $_api;
      
      if( !isset($_api->app_est[$esq][$est]) || isset($ope) ){

        // combinado        
        $_est = _app::dat($esq,$est,'est');        

        if( !$_est ) $_est = [];

        // cargo atributos por estructura de la base      
        $_atr = _dat::atr($esq,$est);
                
        // reemplazo atributos por defecto
        if( isset($ope['atr']) ){
          $_est['atr'] = _lis::ite($ope['atr']);
          // descarto columnas ocultas
          if( isset($_est['atr_ocu']) ) unset($_est['atr_ocu']);
        }
        // columnas totales
        if( empty($_est['atr']) ){ 
          $_est['atr'] = !empty($_atr) ? array_keys($_atr) : [];
        }
        // columnas ocultas
        if( isset($ope['atr_ocu']) ) $_est['atr_ocu'] = _lis::ite($ope['atr_ocu']);

        // calculo totales
        $_est['atr_tot'] = count($_est['atr']);
        
        // ciclos y agrupaciones: busco descripciones + inicio de operadores      
        foreach( ['cic','gru'] as $ide ){

          if( isset($_est["tit_{$ide}"]) ){

            foreach( $_est["tit_{$ide}"] as $atr ){
              
              // inicio ciclo
              if( $ide == 'cic' ) $_est['cic_val'][$atr] = -1;

              // busco descripciones
              if( isset( $_atr["{$atr}_des"] ) ){
                
                if( !isset($_est["det_{$ide}"]) ) $_est["det_{$ide}"]=[]; 
                $_est["det_{$ide}"] []= "{$atr}_des";
              }
            }
          }
        }
        $_api->app_est[$esq][$est] = $_est;
      }

      return $_api->app_est[$esq][$est];
    }
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
      // Dato : abm por columnas
      case 'dat':
        foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
        $_est = _app_est::dat($esq,$est);
        // tipos de dato
        $_cue = [
          'opc'=>[ "Opción", 0 ], 
          'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
          'tex'=>[ "Texto",  0 ], 
          'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
          'obj'=>[ "Objeto",  0 ] 
        ];
        // cuento atributos por tipo
        foreach( $_est['atr'] as $atr ){
          $tip_dat = explode('_', _dat::atr($esq,$est,$atr)->ope['_tip'])[0];
          if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
        }
        // operador : toggles + filtros
        $_ .= "
        <form class='val ide-dat jus-ini'>
  
          <fieldset class='ope'>
            "._app::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
            "._app::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
          </fieldset>
  
          "._app_ope::var('val','ver',[ 
            'nom'=>"Filtrar", 'htm'=>_app_ope::ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
          ])."
  
          <fieldset class='ite'>";
          foreach( $_cue as $atr => $val ){ $_ .= "
            <div class='val'>
              "._app::ico($atr,[
                'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');"
              ])."
              <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
            </div>";
          }$_ .= "
          </fieldset>
  
        </form>";
        // listado
        $pos = 0; $_ .= "
        <table"._ele::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
        foreach( $_est['atr'] as $atr ){
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
          <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
            <td data-atr='val'>
              "._app::ico( isset($app_lis->ocu) && in_array($atr,$app_lis->ocu) ? "ope_ver" : "ope_ocu",[
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
        <h3 class='mar_arr-0'>Valores</h3>";
        // acumulados
        if( isset($ope['val']['acu']) ){
          $_ .= "
          <form class='ide-acu'>
            <fieldset class='inf ren'>
              <legend>Acumulados</legend>

              "._app_ope::var('app',"val.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
              
              "._app_ope::var('app',"val.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$_eje}_tod(this);" ] ])."
              
              "._app_val::acu($ope['val']['acu'],[
                'ide'=>$_ide, // agrego evento para ejecutar todos los filtros
                'eje'=>"{$_eje}_acu(this); ".self::$EJE."ver();",
                'ope'=>[ 'htm_fin'=>"<span class='mar_izq-1'><c>(</c> <n>0</n> <c>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
        }
        break;
      // Filtros :
      case 'ver': 
        $_ = "
        <h3 class='mar_arr-0'>Filtros</h3>";
        // filtros : datos + posicion + atributos
        if( isset($ope['val']) ){
          $dat_tot = count($ope['dat']);
          $_ .= "
          <form class='ide-val'>
            <fieldset class='inf ren'>
              <legend>por Datos</legend>

              "._app_val::ver('dat', $ope['est'], [
                'ope'=>[ 'id'=>"{$_ide}-val", 'max'=>$dat_tot, 'onchange'=>"$_eje();" ] 
              ])."
            </fieldset>
          </form>  

          <form class='ide-fec'>
            <fieldset class='inf ren'>
              <legend>por Fechas</legend>

              "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [ 
                'ope'=>[ 'id'=>"{$_ide}-fec", '_tip'=>"fec_dia", 'onchange'=>"$_eje();" ] 
              ])."            
            </fieldset>          
          </form>
          <!--
          <form class='ide-pos'>
            <fieldset class='inf ren'>
              <legend>por Posiciones</legend>

              "._app_val::ver('lis', [ 'ini'=>[], 'fin'=>[], 'inc'=>[], 'lim'=>[] ], [                  
                'ope'=>[ 'id'=>"{$_ide}-pos", 
                  '_tip'=>"num_int", 'min'=>"1", 'max'=>$dat_tot, 'onchange'=>"$_eje();" 
                ]
              ])."
            </fieldset>
          </form>
          -->";
        }// filtros por : cic + gru
        else{
        }
        break;
      // Columnas : ver/ocultar
      case 'atr':
        $lis_val = [];
        foreach( $ope['est'] as $esq => $est_lis ){
          foreach( $est_lis as $est ){
            // estrutura por aplicacion
            $_est = _app_est::dat($esq,$est);
            // datos de la estructura
            $est_nom = _dat::est($esq,$est)->nom;
            // contenido : listado de checkbox en formulario
            $htm = "
            <form class='ide-$tip ren jus-ini mar_izq-2'>
              <fieldset class='ope'>
                "._app::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
                'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
                "._app::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
                'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."                
              </fieldset>";
              foreach( $_est['atr'] as $atr ){
                $htm .= _app_ope::var('val',$atr,[
                  'nom'=>"¿"._dat::atr($esq,$est,$atr)->nom."?",
                  'val'=>!isset($_est['atr_ocu']) || !in_array($atr,$_est['atr_ocu']),
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
        <h3 class='mar_arr-0'>Columnas</h3>

        "._app_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

        break;
      // Descripciones : titulo + detalle
      case 'des':
        $lis_val = [];        
        foreach( $ope['est'] as $esq => $est_lis ){
          foreach( $est_lis as $est ){
            $_est =  _app_est::dat($esq,$est);
            // ciclos, agrupaciones y lecturas
            if( !empty($_est['tit_cic']) || !empty($_est['tit_gru']) || !empty($_est['det_des']) ){              
              $lis_dep = [];
              foreach( ['cic','gru','des'] as $ide ){
                $pre = $ide == 'des' ? 'det' : 'tit';
                if( !empty($_est["{$pre}_{$ide}"]) ){ $htm = "
                  <form class='ide-{$ide} lis ali-ini mar_izq-2' data-esq='{$esq}' data-est='{$est}'>";
                  foreach( $_est["{$pre}_{$ide}"] as $atr ){
                    $htm .= _app_ope::var('val',$atr,[ 
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
        <h3 class='mar_arr-0'>Descripciones</h3>

        "._app_lis::val($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

        break;
      // Cuentas : total + porcentaje
      case 'cue':
        $_ = "
        <h3 class='mar_arr-0'>Cuentas</h3>
        "._app_lis::val( _app_val::cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[], 'opc'=>['tog','ver','cue'] ]);

        break;
      }
      return $_;
    }
    // listado : thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td )
    static function lis( string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      $_ide = self::$IDE."lis";
      if( !isset($ope['opc']) ) $ope['opc']=[];
      foreach( ['lis','tit_ite','tit_val','ite','dat_val','det_ite','det_val','val'] as $i ){ 
        if( !isset($ele[$i]) ) $ele[$i]=[]; 
      }      
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_ide = "_$esq-$est $_ide";
        // ! inicializo estructura ! // aunq no se use la variable, se usan los datos que esta fun carga
        $_est = _app_est::dat($esq,$est,$ope);
      }// por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
          $_ide = "_$esq-$est $_ide";
        }
      }      
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = is_array($dat) ? $dat : _dat::get($esq,$est);
      // aseguro estructura
      if( isset($esq) && !isset($ope['est']) ) $ope['est'] = [ $esq => [ $est ] ];

      // identificadores de la base        
      if( isset($esq) ){
        $ele['lis']['data-esq'] = $esq;
        $ele['lis']['data-est'] = $est;
      }
      _ele::cla($ele['lis'],"app_est",'ini'); 
      $_ = "
      <div"._ele::atr($ele['lis']).">
        <table>";
          // centrado de texto
          if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
          // columnas:
          if( $dat_val_lis = is_array($dat) ){
            // datos de atributos
            if( !isset($ope['atr_dat']) ) $ope['atr_dat'] = _dat::atr_ver($dat);
            // listado de columnas
            if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['atr_dat']);
          }
          // caclulo total de columnas
          $ope['atr_tot'] = _dat::atr_cue($dat,$ope);

          // cabecera
          if( !in_array('cab_ocu',$ope['opc']) ){ 
            foreach( ['cab_ite','cab_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } 
            $_ .= "
            <thead>
              "._app_est::atr($dat,$ope,$ele)."
            </thead>";
          }
          // cuerpo
          $_.="
          <tbody>";
            // recorro: por listado $dat = []
            $pos_val = 0;            
            if( $dat_val_lis ){
              foreach( $ope['dat'] as $pos => $val ){
                // titulos
                if( !empty($ope['tit'][$pos]) ) $_ .= _app_est::pos('tit',$pos,$ope,$ele);
                // fila-columnas
                $pos_val++; 
                $ele_pos = $ele['ite'];
                _ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_.="
                <tr"._ele::atr($ele_pos).">"._app_est::ite($dat,$val,$ope,$ele)."</tr>";
                // detalles
                if( !empty($ope['det'][$pos]) ) $_ .= _app_est::pos('det',$pos,$ope,$ele);                    
              }
            }
            // estructuras de la base esquema
            else{
              // valido item por objeto-array
              foreach( $ope['dat'] as $val ){ $_val_dat_obj = is_object($val); break; }
              // valido contenido : titulos y detalles por estructura de la base
              $ele_ite = [];
              foreach( [ 'tit'=>['cic','gru'], 'det'=>['des','cic','gru'] ] as $i => $v ){ 
                $_val[$i] = isset($ope[$i]);
                foreach( $v as $e ){
                  $_val["{$i}_{$e}"] = isset($ope["{$i}_{$e}"]);
                  $ele_ite["{$i}_{$e}"] = [ 'ite'=>[ 'data-opc'=>$i, 'data-ope'=>$e ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
                }            
              }
              // contenido previo : titulos por agrupaciones
              if( $_val['tit_gru'] ){
                foreach( $ope['est'] as $esq => $est_lis ){
                  foreach( $est_lis as $est ){
                    $_ .= _app_est::tit('gru', "{$esq}.{$est}", $ope, $ele_ite['tit_gru']);
                  }
                }
              }
              // recorro datos
              $ope['opc'] []= "det_cit";
              foreach( $ope['dat'] as $pos => $val ){
                // titulos y referencias
                foreach( $ope['est'] as $esq => $est_lis ){
                  // recorro referencias
                  foreach( $est_lis as $est){
                    // cargo relaciones                  
                    if( $dat_opc_est = _app::dat($esq,$est,'rel') ){

                      foreach( $dat_opc_est as $atr => $ref ){

                        $ele['ite']["data-{$ref}"] = $_val_dat_obj ? $val->$atr : $val["{$ref}"];
                      }
                    }
                    // cargo titulos de ciclos
                    if( $_val['tit'] || $_val['tit_cic'] ){
                      $_ .= _app_est::tit('cic',"{$esq}.{$est}", $_val_dat_obj ? $val : $val["{$esq}_{$est}"], $ope, $ele_ite['tit_cic']);
                    }
                  }
                }
                // item por esquema.estructuras
                $pos_val ++;
                $ele_pos = $ele['ite'];
                _ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
                <tr"._ele::atr($ele_pos).">";
                foreach( $ope['est'] as $esq => $est_lis ){
                  foreach( $est_lis as $est ){
                    $_ .= _app_est::ite("{$esq}.{$est}", $_val_dat_obj ? $val : $val["{$esq}_{$est}"], $ope, $ele);
                  } 
                }$_ .= "
                </tr>";
                // detalles
                foreach( $ope['est'] as $esq => $est_lis ){
                  foreach( $est_lis as $est ){
                    foreach( ['des','cic','gru'] as $ide ){
                      if( $_val["det_{$ide}"] ){
                        $_ .= _app_est::det($ide,"{$esq}.{$est}", $_val_dat_obj ? $val : $val["{$esq}_{$est}"], $ope, $ele_ite["det_{$ide}"]);
                      }
                    }                  
                  } 
                }                    
              }
            }$_ .= "              
          </tbody>";
          // pie
          if( !empty($ope['pie']) ){
            foreach( ['pie_ite','pie_val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; } $_.="
            <tfoot>";
              // fila de operaciones
              $_.="
              <tr data-tip='ope'>";
                foreach( $_atr as $atr ){ $_.="
                  <td data-atr='{$atr->ide}' data-ope='pie'></td>";
                }$_.="
              </tr>";
              $_.="
            </tfoot>";
          }
          $_.="
        </table>
      </div>";
      return $_;
    }
    // columnas : por atributos
    static function atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";

      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app_est::dat($esq,$est);
      }
      // por listado
      else{
        if( isset($ope['ide']) ){
          extract( _dat::ide($ope['ide']) );
        }
      }
      
      // por muchos      
      if( isset($ope['est']) ){

        $ope_est = $ope['est'];
        unset($ope['est']);

        foreach( $ope_est as $esq => $est_lis ){

          foreach( $est_lis as $est ){

            $_ .= _app_est::atr("{$esq}.{$est}",$ope,$ele);
          }
        }
      }
      // por 1: esquema.estructura
      else{
        $_val['dat'] = isset($esq);

        $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
        // cargo datos
        $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val['dat'] ? _dat::atr($esq,$est) : _dat::atr_ver($dat) );
        // ocultos por estructura
        $atr_ocu = isset($_est['atr_ocu']) ? $_est['atr_ocu'] : [];
        // genero columnas :
        foreach( ( !empty($ope['atr']) ? $ope['atr'] : ( !empty($_est['atr']) ? $_est['atr'] : array_keys($dat_atr) ) ) as $atr ){
          $e = [];
          if( $_val['dat'] ){
            $e['data-esq'] = $esq;
            $e['data-est'] = $est;
          } 
          $e['data-atr'] = $atr;
          if( in_array($atr,$atr_ocu) ) _ele::cla($e,"dis-ocu");
          // poner enlaces
          $htm = _app::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
          if( !$ope_nav ){
            $htm = "<p>{$htm}</p>";
          }else{
            $htm = "<a href='' target='_blank'>{$htm}</a>";
          }$_ .= "
          <th"._ele::atr($e).">
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
          <tr"._ele::atr($ele["{$tip}_ite"]).">
            <td"._ele::atr(_ele::jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
              ".( is_array($val) ? _ele::val($val) : "<p class='{$tip} tex_ali-izq'>"._app::let($val)."</p>" )."
            </td>
          </tr>";
        }        
      }
      return $_;
    }
    // titulo : posicion + ciclos + agrupaciones
    static function tit( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );        
      }
      // 1 titulo : nombre + detalle
      if( $tip == 'pos' ){
        $atr = $val[0];
        $ide = $val[1];
        $val = $val[2];
        $ele['ite']['data-atr'] = $atr;
        $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
        $htm = "";
        if( !empty($htm_val = _app_dat::val('nom',"{$esq}.{$ide}",$val)) ){ $htm .= "
          <p class='tit'>"._app::let($htm_val)."</p>";
        }
        if( !empty($htm_val = _app_dat::val('des',"{$esq}.{$ide}",$val)) ){ $htm .= "
          <q class='mar_arr-1'>"._app::let($htm_val)."</q>";
        }
        if( in_array('ite_ocu',$ope['opc']) ) _ele::cla($ele['ite'],'dis-ocu');
        $_ .="
        <tr"._ele::atr($ele['ite']).">
          <td"._ele::atr($ele['atr']).">{$htm}</td>
        </tr>";
      }
      // ciclos + agrupaciones
      else{
        if( empty($ele['ite']['data-esq']) ){
          $ele['ite']['data-esq'] = $esq;
          $ele['ite']['data-est'] = $est;
        }
        if( !isset($ele['atr']['colspan']) ){
          $ele['atr']['colspan'] = 1;
          _ele::cla($ele['atr'],"anc-100");
        }
        // por ciclos : secuencias
        if( $tip == 'cic' ){        
          // acumulo posicion actual, si cambia -> imprimo ciclo
          $_est = _app_est::dat($esq,$est);
          if( isset($_est['cic_val']) ){
            $val = _dat::get($esq,$est,$val);            
            foreach( $_est['cic_val'] as $atr => &$pos ){
              
              if( !empty($ide = _dat::rel($esq,$est,$atr) ) && $pos != $val->$atr ){

                if( !empty($val->$atr) ){
                  
                  $_ .= _app_est::tit('pos',$dat,[$atr,$ide,$val->$atr],$ope,$ele);
                }
                $pos = $val->$atr;
              }
            }
          }        
        }
        // por agrupaciones : relaciones
        elseif( $tip == 'gru' ){
          if( isset($_est[$tip]) ){
            foreach( $_est[$tip] as $atr ){

              if( !empty($ide = _dat::rel($esq,$est,$atr)) ){

                foreach( _dat::get($esq,$ide) as $val ){
                  $_ .= _app_est::tit('pos',$dat,[$atr,$ide,$val],$ope,$ele);
                }
              }
            }
          }
        }        
      }
      return $_;
    }
    // fila : datos de la estructura
    static function ite( string | array $dat, mixed $val, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app_est::dat($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }
      
      $opc_ima = !in_array('ima',$ope['opc']);
      $opc_var = in_array('var',$ope['opc']);
      $opc_htm = in_array('htm',$ope['opc']);

      // identificadores
      if( $_val['dat'] = isset($esq) ){
        $ele['dat_val']['data-esq'] = $esq;
        $ele['dat_val']['data-est'] = $est;
      }
      // datos de la base
      if( isset($_est) ){

        $_atr    = _dat::atr($esq,$est);
        $est_ima = _app::dat($esq,$est,'opc.ima');
        $atr_ocu = isset($_est['atr_ocu']) ? $_est['atr_ocu'] : FALSE;

        // recorro atributos y cargo campos
        foreach( ( isset($ope['atr']) ? $ope['atr'] : $_est['atr'] ) as $atr ){
          $ele_dat = $ele['dat_val'];
          $ele_dat['data-atr'] = $atr;         
          //ocultos
          if( $atr_ocu && in_array($atr,$atr_ocu) ) _ele::cla($ele_dat,'dis-ocu');
          // contenido
          $ele_val = $ele['val'];
          
          if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
            _ele::cla($ele_val,"tam-5");
            $ide = 'ima';
          }
          // variables
          else{
            $ide = $opc_var ? 'var' : 'tip';
            // adapto estilos por tipo de valor
            if( !empty($_atr[$atr]) ){
              $var_dat = $_atr[$atr]->var_dat;
              $var_val = $_atr[$atr]->var_val;
            }
            elseif( !empty( $_var = _dat::tip( $val ) ) ){
              $var_dat = $_var->dat;
              $var_val = $_var->val;
            }
            else{
              $var_dat = "val";
              $var_val = "nul";
            }
            // - limito texto vertical
            if( $var_dat == 'tex' ){
              if( $var_dat == 'par' ) _ele::css($ele_val,"max-height:4rem;overflow-y:scroll");
            }
          }$_ .= "
          <td"._ele::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? _ele::cla($ele_dat,'dis-ocu') : $ele_dat ).">      
            "._app_dat::ver($ide,"{$esq}.{$est}.{$atr}",$val,$ele_val)."
          </td>";
        }
      }
      // por listado del entorno
      else{
        $_atr = $ope['atr_dat'];
        $_val_dat_obj = is_object($val);
        foreach( $ope['atr'] as $ide ){
          // valor
          $dat_val = $_val_dat_obj ? $val->{$ide} : $val[$ide];
          // html
          if( $opc_htm ){
            $htm = $dat_val;
          }// variable por tipo
          elseif( $opc_var ){
            $htm = "";
          }// elementos
          elseif( is_array( $dat_val ) ){
            $htm = isset($dat_val['htm']) ? $dat_val['htm'] : _ele::val($dat_val);
          }// textos
          else{
            $htm = _app::let($dat_val);
          }
          $ele['dat_val']['data-atr'] = $ide;
          $_.="
          <td"._ele::atr($ele['dat_val']).">
            {$htm}
          </td>";
        }
      }      

      return $_;
    }
    // detalles : posicion + descripciones + lecturas
    static function det( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      // proceso estructura de la base
      if( is_string($dat) ){
        extract( _dat::ide($dat) );
        $_est = _app_est::dat($esq,$est);
      }// por listado
      elseif( isset($ope['ide']) ){
        extract( _dat::ide($ope['ide']) );
      }
      // 1 detalle
      if( $tip == 'pos' ){
        $atr = $val[0];
        $val = $val[1];
        $ele['ite']['data-atr'] = $atr;
        $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
        if( in_array('ite_ocu',$ope['opc']) ) _ele::cla($ele['ite'],'dis-ocu');
        $_ = "
        <tr"._ele::atr($ele['ite']).">
          <td"._ele::atr($ele['atr']).">
            ".( in_array('det_cit',$ope['opc']) ? "<q>"._app::let($val->$atr)."</q>" : _app::let($val->$atr) )."
          </td>
        </tr>";
      }
      // por tipos : descripciones + ciclos + agrupaciones
      elseif( isset($_est["det_{$tip}"]) ){
        if( empty($ele['ite']['data-esq']) ){
          $ele['ite']['data-esq'] = $esq;
          $ele['ite']['data-est'] = $est;
        }
        if( !isset($ele['atr']['colspan']) ){
          $ele['atr']['colspan'] = 1;
          _ele::cla($ele['atr'],"anc-100");
        }        
        $val = _dat::get($esq,$est,$val);
        foreach( $_est["det_{$tip}"] as $atr ){
          
          $_ .= _app_est::det('pos',$dat,[$atr,$val],$ope,$ele);
        }
      }

      return $_;
    }
  }

  // Listado
  class _app_lis {

    static string $IDE = "_app_lis-";
    static string $EJE = "_app_lis.";
        
    // operadores : tog + filtro
    static function ope( string $tip, array $opc = [], array $ele = [] ) : string {
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      
      $_ = "";

      $tod = empty($opc);
      
      if( $tod || in_array('tog',$opc) ){        
        
        $_ .= _app_ope::val_ope( $tip == 'ite' ? ['eje'=>"{$_eje}_tog(this,"] : [] );
      }
      if( $tod || in_array('ver',$opc) ){ 
        $_ .= _app_ope::var('val','ver',[ 
          'des'=>"Filtrar...",
          'htm'=>_app_ope::ver([ 'cue'=>in_array('cue',$opc) ? 0 : NULL, 'eje'=>"{$_eje}_ver(this);" ])
        ]);
      }

      if( !empty($_) ){ $_ = "
        <form"._ele::atr( isset($ele['ope']) ? $ele['ope'] : [] ).">
          {$_}
        </form>";        
      }      
      return $_;
    }

    // horizontal con barra de desplazamiento por item
    static function bar( array $dat, array $ope = [] ) : string {
      $_ide = self::$IDE."bar";
      $_eje = self::$EJE."bar";      
      $_ = "";

      $pos = 0;
      $pos_ver = ( !empty($ope['pos_ver']) ? $ope['pos_ver'] : 1 );
      if( !isset($ope['lis']) ) $ope['lis']=[];
      $_.="
      <ul"._ele::atr(_ele::cla($ope['lis'],"bar",'ini')).">";
        if( !isset($ope['ite']) ) $ope['ite'] = [];
        foreach( $dat as $ite ){ 
          $pos++;
          $ele_ite = $ope['ite'];
          _ele::cla($ele_ite,"pos ide-$pos",'ini');
          if( $pos != $pos_ver ) _ele::cla($ele_ite,DIS_OCU);
          $_.="
          <li"._ele::atr($ope['ite']).">";
            // contenido html
            if( is_string($ite) ){
              $_ .= $ite;
            }// elementos html
            elseif( is_array($ite) ){
              $_ .= _ele::dec($ite);
            }// modelo : titulo + detalle + imagen
            elseif( is_object($ite) ){

            } $_.= "
          </li>";
        }$_.="
      </ul>";
      // operadores
      $min = $pos == 0 ? 0 : 1;
      $max = $pos;
      $_eje .= "_ite";
      $_ .= "
      <form class='ope anc-100 jus-cen mar_ver-2'>

        "._app_num::ope('val',$min,['name'=>"ini", 'title'=>"Ir al primero...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."
                
        "._app::ico('lis_pre',['eti'=>"button", 'name'=>"pre", 'title'=>"Ver el anterior...",  'onclick'=>"$_eje('val',this);"])."

        "._app_num::ope('int',$pos_ver,[ 'name'=>"val", 'min'=>$min, 'max'=>$max, 'title'=>"Buscar posición...", 'oninput'=>"$_eje('val',this);" ])."

        "._app::ico('lis_pos',['eti'=>"button", 'name'=>"pos", 'title'=>"Ver el siguiente...", 'onclick'=>"$_eje('val',this);"])."            

        "._app_num::ope('val',$max,['name'=>"fin", 'title'=>"Ir al último...", 'class'=>"mar_hor-1", 'onclick'=>"$_eje('val',this);" ])."          

      </form>";
      return $_;
    }
    // items : dl, ul, ol
    static function ite( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val'] as $i ){ if( !isset($ope[$i]) ) $ope[$i]=[]; }
      $_ = "";
      $_eje = self::$EJE."ite";
      // operador
      if( isset($ope['opc']) ) $_ .= _app_lis::ope('ite', $ope['opc'] = _lis::ite($ope['opc']), $ope);
      // por punteo o numerado
      if( _obj::pos($dat) ){
        $eti = isset($ope['lis']['eti']) ? $ope['lis']['eti'] : 'ul'; 
        $_ .= "
        <{$eti}"._ele::atr($ope['lis']).">";
          foreach( $dat as $pos => $val ){
            $_ .= _app_lis::ite_pos( 1, $pos, $val, $ope, $eti );
          }$_.="
        </{$eti}>";
      }
      // por términos
      else{
        // agrego toggle del item
        _ele::eje($ope['ite'],'cli',"{$_eje}_val(this);",'ini');
        $_ .= "
        <dl"._ele::atr($ope['lis']).">";
          foreach( $dat as $nom => $val ){ 

            $ope_ite = $ope['ite'];

            if( empty($ope_ite['id']) ) $ope_ite['id'] = "_doc-tex ".str_replace(' ','_',mb_strtolower($nom));
            $_ .= "
            <dt"._ele::atr($ope_ite).">
              "._app::let($nom)."
            </dt>";
            foreach( _lis::ite($val) as $ite ){ $_ .= "
              <dd"._ele::atr($ope['val']).">
                "._app::let($ite)."
              </dd>";
            }
          }$_.="
        </dl>";
      }
      return $_;
    }
    static function ite_pos( int $niv, int | string $ide, mixed $val, array $ope, string $eti = "ul" ) : string {
      $_ = "
      <li"._ele::atr($ope['ite']).">";
        if( is_string($val) ){ 
          $_ .= $val;
        }// sublistas
        else{
          $niv++;
          $_.="
          <$eti data-niv='$niv'>";
          if( isset($ope['opc']) ){
            $opc = [];
            if( in_array('tog_dep',$ope['opc']) ) $opc []= "tog";
            $_ .= "<li>"._app_lis::ope('ite',$opc,$ope)."</li>";
          }
          foreach( $val as $ide => $val ){
            $_ .= _app_lis::ite_pos( $niv, $ide, $val, $ope, $eti );
          }$_.="
          </$eti>";
        }
        $_ .= "
      </li>";
      return $_;
    }
    // contenedores : ul > ...li > .val(.ico + tex-tit) + lis/htm
    static function val( array $dat, array $ope = [] ) : string {
      foreach( ['lis','ite','val','ico','dep','ope'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }
      $_ = "";
      // elementos        
      _ele::cla($ope['lis'],"lis",'ini');
      _ele::cla($ope['dep'],"lis",'ini');
      _ele::cla($ope['ope'],"ite",'ini');      
      // operadores
      if( isset($ope['opc']) ) $_ .= _app_lis::ope('val', _lis::ite($ope['opc']), $ope);
      // listado
      $_ .= "
      <ul"._ele::atr($ope['lis']).">";
      $ide = 0;
      foreach( $dat as $val ){
        $ide++;
        $_ .= _app_lis::val_pos( 1, $ide, $val, $ope );
      }$_ .= "
      </ul>";
      return $_;
    }    
    static function val_pos( int $niv, int | string $ide, string | array $val, array $ope ) : string {
    
      $ope_ite = $ope['ite'];      
      _ele::cla($ope_ite,"pos ide-$ide",'ini');
      // con dependencia : evalúo rotacion de icono
      if( $val_lis = is_array($val) ){
        $ope_ico = $ope['ico'];
        $ele_dep = isset($ope["lis-$niv"]) ? _ele::jun($ope['dep'],$ope["lis-$niv"]) : $ope['dep'];
        if( isset($ele_dep['class']) && preg_match("/".DIS_OCU."/",$ele_dep['class']) ) _ele::cla($ope_ico,"ocu");
        if( !isset($val['ite_ope']) ) $val['ite_ope'] = [];
        $val['ite_ope']['ico'] = $ope_ico;
      }// sin dependencias : separo item por icono vacío
      else{
        _ele::cla($ope_ite,"sep");
      }
      $_ = "
      <li"._ele::atr( isset($ope["ite-$ide"]) ? _ele::jun($ope["ite-$ide"],$ope_ite) : $ope_ite  ).">

        ".( $val_lis ? _app_ope::val( isset($val['ite']) ? $val['ite'] : $ide, $val['ite_ope'] ) : $val );
        
        if( $val_lis ){
          // sublista
          if( isset($val['lis']) ){
            $ope['dep']['data-niv'] = $niv;
            $_ .= "
            <ul"._ele::atr($ele_dep).">";

            if( is_array($val['lis'])  ){
              // operador por dependencias : 1° item de la lista
              if( isset($ope['opc'])){
                $opc = [];
                foreach( $val['lis'] as $i => $v ){ $lis_dep = is_array($v); break; }                
                if( in_array('tog_dep',$ope['opc']) && $lis_dep ) $opc []= "tog";
                if( !empty($opc) ) $_ .= "
                <li>"._app_lis::ope('val',$opc,$ope)."</li>";
              }
              foreach( $val['lis'] as $i => $v ){

                $_ .= _app_lis::val_pos( $niv+1, $i, $v, $ope );
              }
            }
            // listado textual
            elseif( is_string($val['lis']) ){

              $_ .= $val['lis'];
            }$_ .= "
            </ul>";
          }// contenido html directo ( asegurar elemento único )
          elseif( isset($val['htm']) ){

            $_ .= is_string($val['htm']) ? $val['htm'] : _ele::dec($val['htm']);
          }
        }$_ .= "          
      </li>";        
      return $_;
    }
    // tabla
    static function tab( array $dat, array $ope = [], array $ele = [] ) : string {
      $_ = "";
      foreach( ['lis'] as $i ){ 
        if( !isset($ele[$i]) ) $ele[$i]=[]; 
      }  
      return $_;
    }
  }
  // Opcion
  class _app_opc {

    static string $IDE = "_app_opc-";
    static string $EJE = "_app_opc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;
      
      switch( $tip ){
      // vacío : null
      case 'vac':
        $ope['type'] = 'radio'; 
        $ope['disabled'] = '1';
        if( is_nan($dat) ){ 
          $ope['val']="non";
        }elseif( is_null($dat) ){ 
          $ope['val']="nov";
        }                    
        break;
      // binario : input[checkbox]
      case 'bin':
        $ope['type']='checkbox';
        if( !empty($dat) ){ $ope['checked']='checked'; }
        break;
      // único : div > input[radio]
      case 'uni':
        if( isset($ope['dat']) ){
          $_dat = $ope['dat'];
          unset($ope['dat']); 
          $_ .= "
          <div var='opc_uni'>";
          $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
          foreach( $_dat as $ide => $val ){ $_ .= "
            <div class='val'>
              <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
              <input id='{$ope_ide}-{$ide}' type='radio' name='{$ide}' value='{$ide}'>
            </div>";
          }$_ .= "
          </div>";
        }
        break;
      // múltiple : div > ...input[checkbox]
      case 'mul':
        if( isset($ope['dat']) ){
          $_dat = $ope['dat'];
          unset($ope['dat']); 
          $_ .= "
          <div var='opc_mul'>";
          $ope_ide = isset($ope['ide']) ? $ope['ide'] : '_doc-opc-'.count($_dat);
          foreach( $_dat as $ide => $val ){ $_ .= "
            <div class='val'>
              <label for='{$ope_ide}-{$ide}'>{$val}<c>:</c></label>
              <input id='{$ope_ide}-{$ide}' type='checkbox' name='{$ide}' value='{$ide}'>
            </div>";
          }$_ .= "
          </div>";
        }
        break;          
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._ele::atr($ope).">";            
      }
      return $_;
    }
    // opciones : select > ...option[value]
    static function val( mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";

      $ope_eti = !empty($ope['eti']) ? _obj::dec($ope['eti'],[],'nom') : [];

      if( isset($ope_eti['data-opc']) ){
        $opc = array_merge($opc,is_array($ope_eti['data-opc']) ? $ope_eti['data-opc'] : explode(',',$ope_eti['data-opc']) );
      }

      // etiqueta del contenedor
      $eti = isset($ope_eti['eti']) ? $ope_eti['eti'] : 'select';

      // aseguro valor
      $val = NULL;
      if( isset($ope['val']) ){
        $val = $ope['val'];
      }
      elseif( isset($ope_eti['val']) ){
        $val = $ope_eti['val'];
        unset($ope_eti['val']);
      }
      
      $_ = "
      <{$eti}"._ele::atr($ope_eti).">";

        if( in_array('nad',$opc) ){ $_ .= "
          <option default value=''>{-_-}</option>"; 
        }
        // items
        $ope_ite = isset($ope['ite']) ? $ope['ite'] : [];
        if( !empty($ope['gru']) ){

          foreach( $ope['gru'] as $ide => $nom ){ 

            if( isset($dat[$ide]) ){ $_.="
              <optgroup data-ide='{$ide}' label='{$nom}'>
                "._app_opc::lis( $dat[$ide], $val, $ope_ite, ...$opc )."                
              </optgroup>";
            }
          }
        }
        else{                        
          $_ .= _app_opc::lis( $dat, $val, $ope_ite, ...$opc );
        }
        $_ .= "
      </{$eti}>";

      return $_;
    }
    // opciones : ...option[value]
    static function lis( mixed $dat = [], mixed $val = NULL, array $ope = [], ...$opc) : string {
      $_ = "";
      
      $val_ite = !empty($val);
      $val_arr = $val_ite && is_array($val);
      $opc_ide = in_array('ide',$opc);

      $obj_tip = FALSE;
      foreach( $dat as $i => $v){ 
        $obj_tip = _obj::tip($v);
        break;
      }

      foreach( $dat as $i => $v){ 
        $atr=''; 
        $htm=''; 
        $e = $ope;

        // literal
        if( !$obj_tip ){  
          $e['value'] = $i;
          $htm = !!$opc_ide ? "{$i}: ".strval($v) : strval($v) ;
          $atr = _ele::atr($e);
        }
        // elemento
        elseif( $obj_tip == 'nom' ){
          $e = _ele::jun($e,$v);
          if( !isset($e['value']) ) $e['value'] = $i;
          $htm = isset($e['htm']) ? $e['htm'] : $i;
          $atr = _ele::atr($e);
        }
        // objeto ( ide + nom + des + tit )
        elseif( $obj_tip == 'atr' ){
          $_ide = isset($v->ide) ? $v->ide : FALSE ;
          $_htm = isset($v->nom) ? $v->nom : FALSE ;
          // valor
          if( isset($e['value']) ){ 
            $e['value'] = _obj::val($v,$e['value']); 
          }else{ 
            $e['value'] = $i;
            if( $_ide ){ $e['value'] = $_ide; }elseif( $_htm ){ $e['value'] = $_htm; }
          }
          // titulo con descripcion
          if( !isset($e['title']) ){ 
            if( isset($v->des) ){ 
              $e['title'] = $v->des; 
            }elseif( isset($v->tit) ){ 
              $e['title'] = $v->tit; 
            }
          }
          // contenido
          if( isset($e['htm']) ){
            $htm = _obj::val($v,$e['htm']);
          }else{
            if( !!$opc_ide && $_ide && $_htm ){
              $htm = "{$_ide}: {$_htm}";
            }elseif( $_htm ){
              $htm = $_htm;
            }else{
              $htm = $_ide; 
            }
          }
          $atr = _ele::atr($e,$v);            
        }// por posiciones
        else{
          $htm = "( \"".implode( '", "', $v )."\" )" ;
          $atr = _ele::atr($e);
        }
        // agrego atributo si está en la lista
        if( $val_ite ){ 
          if( $val_arr ){
            if( in_array($e['value'],$val) ) $atr .= " selected";
          }
          elseif( $val == $e['value'] ){

            $atr .= " selected";
          }
        }
        $_ .= "<option{$atr}>{$htm}</option>";
      }   
      return $_;
    }
  }
  // Numero
  class _app_num {

    static string $IDE = "_app_num-";
    static string $EJE = "_app_num.";
    
    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      if( $tip == 'val' ){

        if( !isset($ope['htm']) ) $ope['htm'] = $dat;

        $_ = "<n"._ele::atr($ope).">{$ope['htm']}</n>";
      }
      else{

        if( $tip != 'bit' ){
  
          if( !isset($ope['value']) && isset($dat) ) $ope['value'] = $dat;
  
          if( !empty($ope['num_ran']) ){ $tip = 'ran'; }else{ $ope['type']='number'; }
  
          if( isset($ope['max']) && !isset($ope['maxlength']) ) $ope['maxlength'] = strlen( strval( $ope['max'] ) );          
        }
  
        // controlo valores al actualizar
        _ele::eje($ope,'inp',"$_eje"."act(this);",'ini');
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');        
        
        switch( $tip ){
        case 'bit':
  
          $ope['type']='text';  
          break;          
        case 'int':
  
          if( !isset($ope['step']) ) $ope['step']='1'; 
  
          if( !empty($ope['value']) ){
  
            if( !empty($ope['num_pad']) ){
              
              if( !empty($ope['maxlength']) ){ 
                $tam = $ope['maxlength']; 
              }
              elseif( !empty($ope['max']) ){ 
                $tam = count(explode('',$ope['max'])); 
              }
            }
            if( !empty($tam) ){ 
              $ope['value'] = _num::val($ope['value'],$tam); 
            }
            if( !empty($ope['num_sep']) ){ 
              $ope['value'] = _num::int($ope['value']); 
            }
          }
          break;
        case 'dec':
          if( !empty($ope['value']) ){
  
            $ope['value'] = floatval($ope['value']);
  
            if( !empty($ope['maxlength']) ){
              $tam = explode(',',$ope['maxlength']); 
              $int = $tam[0]; 
              $dec = isset($tam[1]) ? $tam[1] : 0;
            }
            else{
  
              if( !empty($ope['num_dec']) ){ 
  
                $dec = $ope['num_dec']; 
              }
              elseif( isset($ope['step']) ){ 
                $dec = explode('.',$ope['step']); 
                $dec = isset($dec[1]) ? strlen($dec[1]) : 0;
              }
              if( isset($ope['num']) ){ 
  
                $int = strlen($ope['num']); 
              }
              elseif( isset($ope['max']) ){ 
  
                $int = strlen($ope['max']); 
              }
            }
            $tam = intval($int) + 1;
  
            if( empty($dec) ) $dec = 2;
  
            $ope['num_dec'] = $dec;
  
            if( !isset($ope['step']) ) $ope['step'] = '0.'._num::val('1',$dec);
  
            if( !empty($ope['num_pad']) && !empty($tam) ) $ope['value'] = _num::val($ope['value'],$tam);
  
            if( !empty($ope['num_sep']) ) $ope['value'] = _num::dec($ope['value']);
          }
          break;
        case 'ran':
          $ope['type']='range'; 
          if( !isset($ope['step']) ) $ope['step']=1; 
          if( !isset($ope['min']) )  $ope['min']=0; 
          if( !isset($ope['max']) )  $ope['max']=$ope['step'];
          // armo bloques : min < --- val --- > max / output
          if( !in_array('ver',$opc) ){
            $cla = "";
            if( isset($ope['class']) ){ 
              $cla = "{$ope['class']}"; 
              unset($ope['class']); 
            }
            if( !isset($ope['id']) ){ 
              $ope['id'] = "_num_ran-"._app_ope::var_ide('_num-ran');
            }
            $htm_out = "";
            if( !in_array('val-ocu',$opc) ){ $htm_out = "
              <output for='{$ope['id']}'>
                <n class='_val'>{$ope['value']}</n>
              </output>";
            }
            $_ = "
            <div class='app_num ran {$cla}'>
            
              <div class='val'>
                <n class='_min'>{$ope['min']}</n>
                <c class='sep'><</c>
                <input"._ele::atr($ope).">
                <c class='sep'>></c>
                <n class='_max'>{$ope['max']}</n>
              </div>

              {$htm_out}

            </div>";
          }
          break;
        }

        if( empty($_) && !empty($ope['type']) ){
          $_ = "<input"._ele::atr($ope).">";
        }
      }        

      return $_;
    }
  }
  // Texto
  class _app_tex {

    static string $IDE = "_app_tex-";

    static string $EJE = "_app_tex.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {      
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      // valor
      if( $tip == 'val' ){

        $ope['eti'] = !empty($ope['eti']) ? $ope['eti'] : 'font';

        $ope['htm'] = _app::let( is_null($dat) ? '' : strval($dat) );

        $_ = _ele::val($ope);

      }// por tipos
      else{

        if( !is_string($dat) ) $dat = strval( is_iterable($dat) ? _obj::cod($dat) : $dat );

        $ope['value'] = str_replace('"','\"',$dat);

        if( $tip == 'par' ){

          if( empty($ope['rows']) ) $ope['rows']="2";      
        }
        else{
          $ope['type']='text';
        }
        if( isset($ope['type']) ){
          $lis_htm = "";
          if( isset($ope['lis']) || isset($ope['dat']) ){
            if( isset($ope['lis']) ){
              $dat_lis = _obj::dec($ope['lis']);
              unset($ope['lis']);          
            }else{
              $dat_lis = [];
            }        
            if( empty($ope['id']) ){ 
              $ope['id']="_tex-{$tip}-"._app_ope::var_ide("_tex-{$tip}-");
            }
            $ope['list'] = "{$ope['id']}-lis";
            $lis_htm = "
            <datalist id='{$ope['list']}'>";
              foreach( $dat_lis as $pos => $ite ){ $lis_htm .= "
                <option data-ide='{$pos}' value='{$ite}'></option>";
              }$lis_htm .="
            </datalist>";
          }
          // seleccion automática
          _ele::eje($ope,'foc',"this.select();",'ini');  
          $_ = "<input"._ele::atr($ope).">".$lis_htm;
        }
        else{
          $_ = "<textarea"._ele::atr($ope).">{$dat}</textarea>";
        }
      }      

      return $_;
    }
  }
  // Figura
  class _app_fig {

    static string $IDE = "_app_fig-";
    static string $EJE = "_app_fig.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;
      
      switch( $tip ){
      case 'ima': 
        $_ = "<img src=''>";
        break;
      // color
      case 'col':
        $ope['type']='color';
        $ope['value'] = empty($dat) ? $dat : '#000000';
        break;
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._ele::atr($ope).">";            
      }
      return $_;
    }
  }
  // Fecha
  class _app_fec {

    static string $IDE = "_app_fec-";
    static string $EJE = "_app_fec.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";      

      switch( $tip ){
      case 'val':
        $ope['value'] = $dat; $_ = "
        <time"._ele::atr($ope).">
          "._app::let(_fec::var($dat))."
        </time>";
      case 'tie': 
        $ope['value'] = intval($dat);
        $ope['type']='numeric';
        break;
      case 'dyh': 
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='datetime-local';
        break;
      case 'hor':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='time';
        break;
      case 'dia':
        $ope['value'] = _fec::var($dat,$tip);
        $ope['type']='date';
        break;
      case 'sem':
        $ope['value'] = intval($dat);
        $ope['type']='week';
        break;
      case 'mes':
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      case 'año': 
        $ope['value'] = intval($dat);
        $ope['type']='number';
        break;
      }

      if( empty($_) && !empty($ope['type']) ){
        // seleccion automática
        _ele::eje($ope,'foc',"this.select();",'ini');
        $_ = "<input"._ele::atr($ope).">";
      }      

      return $_;
    }        
  }
  // Holon 
  class _app_hol {

    static string $IDE = "_app_hol-";
    static string $EJE = "_app_hol.";
    static array $DIA = [
      'kin' => [ 'ide'=>"kin", 'ico'=>"", 'nom'=>"Sincrónico",   'des'=>"" ],
      'psi' => [ 'ide'=>"psi", 'ico'=>"", 'nom'=>"Cíclico",      'des'=>"" ],
      'umb' => [ 'ide'=>"umb", 'ico'=>"", 'nom'=>"Sincronotron", 'des'=>"" ]
    ];
    // operadores
    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      switch( $tip ){
      }
      return $_;
    }
    // Valor : Fecha + ns:kin
    static function val( array $dat, array $ope ) : string {

      $_eje =  !empty($ope['eje']) ? $ope['eje'] : self::$EJE."val";

      $_kin = isset($dat['kin']) ? ( is_object($dat['kin']) ? $dat['kin'] : _hol::_('kin',$dat['kin']) ) : [];
      $_psi = isset($dat['psi']) ? ( is_object($dat['psi']) ? $dat['psi'] : _hol::_('psi',$dat['psi']) ) : [];
      $_sin = isset($dat['sin']) ? explode('.',$dat['sin']) : [];
      $_fec = isset($dat['fec']) ? $dat['fec'] : [];      

      $_ = "
      <!-- Fecha del Calendario -->
      <form class='val fec mar-1'>

        "._app::ico('fec_dia',[ 'eti'=>"label", 'for'=>"hol_val-fec", 'class'=>"mar_hor-1", 
          'title'=>"Desde aquí puedes cambiar la fecha..." 
        ])."
        "._app_fec::ope('dia', $_fec, [ 'id'=>"hol_val-fec", 'name'=>"fec", 
          'title'=>"Selecciona o escribe una fecha del Calendario Gregoriano para buscarla..."
        ])."
        "._app::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);", 
          'title'=>'Haz click para buscar esta fecha del Calendario Gregoriano...'
        ])."

      </form>

      <!-- Fecha del Sincronario -->
      <form class='val sin mar-1'>
        
        <label>N<c>.</c>S<c>.</c></label>

        "._app_num::ope('int', $_sin[0], [ 
          'maxlength'=>2, 'name'=>"gal", 'title'=>"Portales Galácticos, Ciclos NS de 52 años..."
        ])."
        <c>.</c>
        "._app_opc::val( _hol::_('ani'), [
          'eti'=>[ 'name'=>"ani", 'title'=>"Anillo Solar (año): los 52 ciclos anuales de 364+1 días...", 'val'=>$_sin[1] ], 
          'ite'=>[ 'title'=>'($)nom','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        "._app_opc::val( _hol::_('psi_lun'), [
          'eti'=>[ 'name'=>"lun", 'title'=>"Giro Lunar (mes): los 13 ciclos mensuales de 28 días...", 'val'=>$_sin[2] ],
          'ite'=>[ 'title'=>'()($)nom(): ()($)des()','htm'=>'($)ide' ]
        ])."
        <c>.</c>
        "._app_opc::val( _hol::_('lun'), [ 
          'eti'=>[ 'name'=>"dia", 'title'=>"Día Lunar : los 28 días del Giro Lunar...", 'val'=>$_sin[3] ], 
          'ite'=>[ 'title'=>'($)des','htm'=>'($)ide' ]
        ])."          
        <c class='sep'>:</c>
    
        <n name='kin'>$_kin->ide</n>

        "._app::ico('dat_ini',[ 'eti'=>"button", 'type'=>"submit", 'class'=>"mar_hor-1", 'onclick'=>"$_eje(this);",
          'title'=>"Haz Click para buscar esta fecha en el Sincronario de 13 Lunas..."
        ])."

      </form>";

      return $_;
    }
    // Diario
    static function dia( string $est, mixed $dat, array $ope = [] ) : string {
      $_ = [];
      switch( $est ){
      case 'kin':
        if( !isset($ope['lis']) ) $ope['lis'] = [];

        $_kin = _hol::_('kin',$dat);
        $_sel = _hol::_('sel',$_kin->arm_tra_dia);
        $_ton = _hol::_('ton',$_kin->nav_ond_dia);
        $_kin_atr = _dat::atr('hol',"kin");
        
        $_est = [
          'arm_tra_dia'=>[ 'cue'=>20, 'est'=>"sel" ],
          'arm_cel_dia'=>[ 'cue'=>4,  'est'=>"sel_arm_raz" ],
          'cro_est_dia'=>[ 'cue'=>65, 'est'=>"chi" ],
          'cro_ele_dia'=>[ 'cue'=>5,  'est'=>"sel_cro_fam" ],
          'nav_cas_dia'=>[ 'cue'=>52, 'est'=>"cas" ],
          'nav_ond_dia'=>[ 'cue'=>13, 'est'=>"ton" ]
        ];
        
        $_[0] = [ 'ite'=>"Nave del Tiempo", 'lis'=>[] ];
        foreach( [ 'nav_cas'=>52, 'nav_ond'=>13 ] as $atr => $cue ){ 
          $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
          $_[0]['lis'] []= 
          
          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
  
          <div class='tam-cre'>
            <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
            <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
            <p>"._app_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>            
          </div>";          
        }
  
        $_[1] = [ 'ite'=>"Giro Galáctico", 'lis'=>[] ];
        foreach( [ 'arm_tra'=>13, 'arm_tra_dia'=>20, 'arm_cel'=>65, 'arm_cel_dia'=>4 ] as $atr => $cue ){ 
          $est = isset($_est[$atr]['est']) ? $_est[$atr]['est'] : "kin_$atr"; 
          $_dat = _hol::_($est,$_kin->$atr); 
          $_[1]['lis'] []= 
          
          _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
  
          <div class='tam-cre'>
            <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
            <p>"._app::let( _app_dat::val('des',"hol.{$est}",$_dat) )."</p>
            <p>"._app_num::ope('ran',$_kin->$atr,[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>          
          </div>";
        }
  
        $_[2] = [ 'ite'=>"Giro Espectral", 'lis'=>[] ];
        foreach( [ 'cro_est'=>65, 'cro_ele'=>5 ] as $atr => $cue ){ 
          $_dat = _hol::_($est="kin_$atr",$_kin->$atr); 
          
          $_[2]['lis'] []= 
          
            _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
  
            <div class='tam-cre'>
              <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
              <p>Día <n>{$_kin->{"{$atr}_dia"}}</n> de <n>$cue</n></p>
              <p>"._app_num::ope('ran',$_kin->{"{$atr}_dia"},[ 'min'=>1, 'max'=>$cue, 'disabled'=>"", 'class'=>"anc-100"],'ver')."</p>
            </div>          
          ";          
        } 
  
        $_[3] = [ 'ite'=>"Holon Solar", 'lis'=>[] ];
        foreach( ['sol_pla','sol_cel','sol_cir'] as $atr ){ 
          $_dat = _hol::_($est = "uni_{$atr}",$_sel->$atr); 
          $_[3]['lis'] []= 
  
            _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
            
            <div class='tam-cre'>
              <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
            </div>              
          ";          
        }
  
        $_[4] = [ 'ite'=>"Holon Planetario", 'lis'=>[] ];
        foreach( ['pla_cen','pla_hem','pla_mer'] as $atr ){ 
          $_dat = _hol::_($est = "uni_{$atr}",$_sel->$atr); 
          $_[4]['lis'] []= 
  
            _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
            
            <div class='tam-cre'>
              <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
            </div>              
          ";
        }
  
        $_[5] = [ 'ite'=>"Holon Humano", 'lis'=>[] ];
        foreach( ['hum_cen','hum_ext','hum_ded','hum_mer'] as $atr ){ 
          $_dat = _hol::_($est = "uni_{$atr}",$_sel->$atr); 
          $_[5]['lis'] []= 
  
            _hol::ima("{$est}",$_dat,['class'=>"tam-3 mar_der-1"])."
            
            <div class='tam-cre'>
              <p>"._app::let( _app_dat::val('nom',"hol.{$est}",$_dat) )."</p>
            </div>              
          ";
        }              
  
        $ope['lis-1'] = [ 'class'=>"ite" ];
        break;
      case 'psi': 
        if( !isset($ope['lis']) ) $ope['lis'] = [];

        $_psi = _hol::_('psi',$dat);
        $_lun = _hol::_('lun',$_psi->lun);
        $_rad = _hol::_('rad',$_psi->hep_dia);
  
        $_[0] = [ 'ite'=>"Estación Solar", 'lis'=>[] ];
        $_est = _hol::_('psi_est',$_psi->est); 
        $_[0]['lis'] []= 
          
          _hol::ima("psi_est",$_est,['class'=>"tam-3 mar_der-1"])."
  
          <div>
  
          </div>"; 
        $_hep = _hol::_('psi_hep',$_psi->hep); 
        $_[0]['lis'] []= 
          
          _hol::ima("psi_hep",$_hep,['class'=>"tam-3 mar_der-1"])."
  
          <div>
  
          </div>
        ";
  
        $_[1] = [ 'ite'=>"Giro Lunar", 'lis'=>[] ];
        $_lun = _hol::_('psi_lun',$_psi->lun); 
        $_[1]['lis'] []= 
          
          _hol::ima("psi_lun",$_lun,['class'=>"tam-3 mar_der-1"])."
  
          <div>
  
          </div>";
        $_arm = _hol::_('lun_arm',_num::ran($_psi->hep,'4')); 
        $_[1]['lis'] []= 
          
          _hol::ima("lun_arm",$_arm,['class'=>"tam-3 mar_der-1"])."
  
          <div>
  
          </div>
        ";
  
        $_[2] = [ 'ite'=>"Héptada", 'lis'=>[] ];
        $_rad = _hol::_('rad',$_psi->hep_dia);
        $_[2]['lis'] []= 
          
          _hol::ima("rad",$_rad,['class'=>"tam-3 mar_der-1"])."
  
          <div>
  
          </div>
        ";          
  
        $ope['lis-1'] = [ 'class'=>"ite" ];
        break;
      }
      return is_array($_) ? _app_lis::val($_,$ope) : $_;
    }
    // Ficha
    static function fic( string $est, string $atr, mixed $val, array $ope = [] ) : string {
      $_ = "";            
      switch( $est ){
      case 'kin':
        $dat = _hol::_($est,$val);
        switch( $atr ){
        // parejas del oráculo
        case 'par':
          $_ ="
          <div class='lis'>";
          foreach( _hol::_('sel_par') as $_par ){
            
            $ide = $_par->ide;
  
            $par_ide = "par_{$ide}";
            $atr_ide = ( $ide=='des' ) ? 'ide' : $par_ide;
      
            // busco datos de parejas
            $_par = _dat::get(_hol::_('sel_par'),[ 'ver'=>[ ['ide','==',$ide] ], 'opc'=>'uni' ]);
            $kin = _hol::_('kin',$dat->$atr_ide);
            $_ .= "
      
            <p class='mar_arr-2 tex_ali-izq'>
              <b class='ide let-sub'>{$_par->nom}</b><c>:</c>
              <br><q>"._app::let($_par->des)."</q>
              ".( !empty($_par->lec) ? "<br><q>"._app::let($_par->lec)."</q>" : "" )."
            </p>
            
            "._app_dat::inf('hol','kin',$kin,['cit'=>"des"])
            ;
    
          } $_ .= "
          </div>";
          break;
        // lecturas por parejas
        case 'par-lec':

          $_lis = [];
          $_des_sel = _hol::_('sel',$dat->arm_tra_dia);

          foreach( _hol::_('sel_par') as $_par ){

            if( $_par->ide == 'des' ) continue;

            $_kin = _hol::_('kin',$dat->{"par_{$_par->ide}"});
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);

            $_lis []=
              _hol::ima("kin",$_kin)."

              <div>
                <p><b class='tit'>{$_kin->nom}</b> <c>(</c> "._app::let($_par->dia)." <c>)</c></p>
                <p>"._app::let("{$_sel->acc} {$_par->pod} {$_sel->car}, que {$_par->mis} {$_des_sel->car}, {$_par->acc} {$_sel->pod}.")."</p>
              </div>";
          }
          
          if( !isset($ope['lis']) ) $ope['lis']=[];

          _ele::cla($ope['lis'],'ite');
          
          $_ = _app_lis::val($_lis,$ope);          
          break;
        // Propiedades : palabras clave del kin + sello + tono
        case 'par-pro':

          $_par_atr = !empty($ope['par']) ? $ope['par'] : ['fun','acc','mis'];
    
          $_ton_atr = !empty($ope['ton']) ? $ope['ton'] : ['acc'];
    
          $_sel_atr = !empty($ope['sel']) ? $ope['sel'] : ['car','des'];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});
    
            $ite = [ _hol::ima("kin",$_kin) ];
    
            foreach( $_par_atr as $atr ){ if( isset($_par->$atr) ) $ite []= _app::let($_par->$atr); }
    
            $_ton = _hol::_('ton',$_kin->nav_ond_dia);
            foreach( $_ton_atr as $atr ){ if( isset($_ton->$atr) ) $ite []= _app::let($_ton->$atr); }
    
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);            
            foreach( $_sel_atr as $atr ){  if( isset($_sel->$atr) ) $ite []= _app::let($_sel->$atr); }
    
            $_ []= $ite;
          }
          break;
        // Ciclos : posiciones en ciclos del kin
        case 'par-cic':
          $_atr = [ 'ene_cam', 'cro_est', 'cro_ele', 'arm_tra', 'arm_cel', 'nav_cas', 'nav_ond' ];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});
    
            $ite = [ _hol::ima("kin",$_kin) ];
    
            foreach( $_atr as $atr ){
              $ite []= _hol::ima("kin_{$atr}",$_kin->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
          break;
        // Grupos : sincronometría del holon por sellos
        case 'par-gru':
          $_atr = [ 'sol_pla', 'sol_cel', 'sol_cir', 'pla_hem', 'pla_mer', 'hum_cen', 'hum_ext', 'hum_mer' ];
    
          foreach( _hol::_('sel_par') as $_par ){
            
            $_kin = $_par->ide == 'des' ? $dat : _hol::_('kin',$dat->{"par_{$_par->ide}"});                            
    
            $_sel = _hol::_('sel',$_kin->arm_tra_dia);
    
            $ite = [ _hol::ima("kin",$_kin), $_par->nom, $_sel->pod ];
    
            foreach( $_atr as $atr ){
              $ite []= _hol::ima("sel_{$atr}",$_sel->$atr,[ 'class'=>"tam-5" ]);
            }
            
            $_ []= $ite;
          }
          break;
          // castillo de la nave  
        }
        if( is_array($_) ) $_ = _app_est::lis( $_, [ 'opc'=>['htm','cab_ocu'] ], $ope);
        break;
      }
      return $_;
    }
    // tablero
    static function tab( string $est, string $atr, array $ope = [], array $ele = [] ) : string {
      extract( _app_hol::tab_dat($est,$atr,$ope,$ele) );
      $_ = "";
      switch( $tab ){
      case 'rad': 
        switch( $atr ){
        }
        break;
      case 'ton': 
        switch( $atr ){
        // onda encantada
        case 'ond': 
          $_tab = _app_tab::dat('hol','ton')->ele;
          $_ .= "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
            <li fon='ima'></li>
            "._app_hol::tab_sec('ton',$ope)
            ;
            $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
            
            foreach( _hol::_('ton') as $_ton ){
              $ide = "pos-{$_ton->ide}";
              $ele['pos'] = _ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $_ .= _app_hol::tab_pos('ton',$_ton,$ope,$ele);
            } $_ .= "
          </ul>";
          break;
        }
        break;
      case 'sel': 
        switch( $atr ){
        // codigo
        case 'cod':
          $_ = "
          <ul"._ele::atr($ele['sec']).">";
            foreach( _hol::_('sel') as $_sel ){ 
              $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '';
              $_ .= "
              <li class='sec{$agr}'>
                <ul class='val jus-cen'>
                  "._hol::ima("sel",$_sel,['eti'=>"li"])."
                  "._hol::ima("sel_cod",$_sel->cod,['eti'=>"li",'class'=>'tam-2'])."
                </ul>
                <p class='mar-0 ali_pro-cen'>
                  {$_sel->arm}
                  <br>{$_sel->acc}
                  <br>{$_sel->pod}
                </p>
              </li>";
            } $_ .= "
          </ul>";        
          break;
        // colocacion cromática
        case 'cro':
          $_ = "
          <ul"._ele::atr($ele['sec']).">
            <li class='pos ide-0'></li>";
            foreach( _hol::_('sel_cro_fam') as $_dep ){ 
              $_ .= _hol::ima("sel_cro_fam",$_dep,['fam'=>$_dep->ide]);
            } 
            foreach( _hol::_('sel_cro_ele') as $_dep ){ 
              $_ .= _hol::ima("sel_cro_ele",$_dep,['ele'=>$_dep->ide]);
            }
            for( $i=0; $i<=19; $i++ ){ 
              $_sel = _hol::_('sel', ( $i == 0 ) ? 20 : $i);
              $agr = ( !!$ide && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
              $e = $ele['pos'];
              _ele::cla($e,"pos-".intval($_sel->ide)."{$agr}");                         
              $e['hol-sel'] = $_sel->ide; $_ .= "
              <li"._ele::atr($e).">
                "._hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['_eje']) ? $ele['pos']['_eje'] : "" ])."
              </li>";
            } $_ .= "
          </ul>";        
          break;
        // colocacion armónica
        case 'arm':
          $_ .= "
          <ul"._ele::atr($ele['sec']).">
            <li class='pos ide-0'></li>
            ";
            foreach( _hol::_('sel_arm_cel') as $_dep ){ 
              $_ .= _hol::ima("sel_arm_cel",$_dep,['cel'=>$_dep->ide]);
            } 
            foreach( _hol::_('sel_arm_raz') as $_dep ){ 
              $_ .= _hol::ima("sel_arm_raz",$_dep,['raz'=>$_dep->ide]);
            }
            foreach( _hol::_('sel') as $_sel ){
              $agr = ( !empty($ide) && $_sel->ide == $ide ) ? ' _val-pos' : '' ;
              $e = $ele['pos'];
              _ele::cla($e,"pos-".intval($_sel->ide)."{$agr}",'ini');             
              $e['sel'] = $_sel->ide; $_ .= "
              <li"._ele::atr($e).">
                "._hol::ima("sel",$_sel,[ 'onclick'=>isset($ele['pos']['onclick']) ? $ele['pos']['onclick'] : NULL ])."
              </li>";
            }
            $_ .= "
          </ul>";        
          break;
        // tablero del oráculo
        case 'arm_tra':
          $_ .= "
          <ul"._ele::atr($ele['sec']).">";
            for( $i=1; $i<=5; $i++ ){
              $ope['ide'] = $i;
              $_ .= _app_hol::tab('kin','arm_cel',$ope,$ele);
            } $_ .= "
          </ul>";        
          break;      
        // célula del tiempo para el oráculo
        case 'arm_cel':
          $_arm = _hol::_('sel_arm_cel',$ide);
          
          $ele['cel']['title'] = _app_dat::val('tit',"hol.{$est}",$_arm); 
          _ele::cla($ele['cel'],"app_tab sel {$atr}",'ini');
          $_ = "
          <ul"._ele::atr($ele['cel']).">
            <li class='pos ide-0'>"._hol::ima("sel_arm_cel", $_arm, ['htm'=>$_arm->ide,'class'=>'ima'] )."</li>";
            foreach( _hol::_('sel_arm_raz') as $_raz ){
              $_.= _app_hol::tab_pos('sel',$sel,$ope,$ele);
              $sel++;
            } $_ .= "
          </ul>";        
          break;
                  
        }
        break;
      case 'lun': 
        switch( $atr ){
        }
        break;
      case 'cas': 
        switch( $atr ){
        // x 4 ondas encantadas
        case 'ond': 
          $_tab = _app_tab::dat('hol','cas')->ele;
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
            <li fon='ima'></li>
            <li"._ele::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></li>
            "._app_hol::tab_sec('cas',$ope)
            ;
            $ele_pos = $ele['pos'];
            foreach( _hol::_('cas') as $_cas ){
              $ide = "pos-{$_cas->ide}";
              $ele['pos'] = _ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$dei] : [] ]);
              $_ .= _app_hol::tab_pos('cas',$_cas,$ope,$ele);
            } $_ .= "
          </ul>";
          break;
        }
        break;
      case 'kin': 
        switch( $atr ){
        // tzolkin
        case 'tzo':
          $ton_htm = isset($ope['sec']['kin-ton']);
          $ton_val = !empty($ope['sec']['kin-ton']);
          $ele_ton=[ 'class'=> "sec -ton".( $ton_val ? "" : " dis-ocu" ) ];
          $sel_htm = isset($ope['sec']['kin-sel']);
          $sel_val = !empty($ope['sec']['kin-sel']);
          $ele_sel = [ 'class' => "sec -sel".( $sel_val ? "" : " dis-ocu" ) ];
          // ajusto grilla
          if( $ton_val ) _ele::css($ele['sec'],"grid: repeat(21,1fr) / repeat(13,1fr);");

          $_ = "
          <ul"._ele::atr($ele['sec']).">";
            // 1° columna
            if( $ton_htm && $sel_htm ){ $_ .= "
              <li"._ele::atr([ 'class' => "sec -ini".( $ton_val && $sel_val ? "" : " dis-ocu" )])."></li>";
            }
            // filas por sellos
            if( $sel_htm ){            
              foreach( _hol::_('sel') as $_sel ){
                $ele_sel['data-hol_sel'] = $_sel->ide; $_ .= "
                <li"._ele::atr($ele_sel).">"._hol::ima("sel",$_sel)."</li>"; 
              }
            }
            // 260 kines por 13 columnas 
            $kin_arm = 0; 
            $ele_pos = $ele['pos']; 
            foreach( _hol::_('kin') as $_kin ){
              // columnas por tono          
              $kin_arm_tra = intval($_kin->arm_tra);
              if( $ton_htm && $kin_arm != $kin_arm_tra ){
                $ele_ton['data-ide'] = $_kin->arm_tra; $_ .= "
                <li"._ele::atr($ele_ton).">"._hol::ima("ton",$_kin->arm_tra)."</li>";
                $kin_arm = $kin_arm_tra;
              }
              // posicion
              if( isset($ele["pos-{$_kin->ide}"]) ) $ele['pos'] = _ele::jun( $ele["pos-{$_kin->ide}"], $ele['pos'] );
              $_ .= _app_hol::tab_pos('kin',$_kin,$ope,$ele);
              $ele['pos'] = $ele_pos;
            } $_ .= "
          </ul>";        
          break;
        // oráculo del destino por tipo de pareja
        case 'par': 
          if( empty($ide) ) $ide = 1;
          $_tab = _app_tab::dat('hol','cro')->ele;        
          $_kin = is_object($ide) ? $ide : _hol::_('kin',$ide);   
          $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];

          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">";
            foreach( _hol::_('sel_par') as $_par  ){
              $par_ide = $_par->ide;
              $par_kin = ( $par_ide == 'des' ) ? $_kin : _hol::_('kin',$_kin->{"par_{$par_ide}"});
              // combino elementos :
              $ele['pos'] = _ele::jun($_tab["pos-{$_par->pos}"], [ $ele_pos, isset($ele["pos-{$par_ide}"]) ? $ele["pos-{$par_ide}"] : [] ]);
              _ele::cla($ele['pos'],"pos-{$par_ide}",'ini');
              $_ .= _app_hol::tab_pos('kin',$par_kin,$ope,$ele);
            }$_ .="
          </ul>";        
          break;
        // castillo del destino por familia terrestre
        case 'cas':
          $_tab = _app_tab::dat('hol','cas')->ele;      
          $_fam = _hol::_('sel_cro_fam',$ide);  
          $_fam_kin = [ 1 => 1, 2 => 222, 3 => 0, 4 => 0, 5 => 105 ];

          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
            <li"._ele::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] )."></li>"
            ._app_hol::tab_sec('cas',$ope)
            ;
            $ele_pos = $ele['pos'];
            $kin = intval($_fam['kin']);          
            foreach( _hol::_('cas') as $_cas ){
              $_kin = _hol::_('kin',$kin);
              $ide = "pos-{$_cas->ide}";
              $ele['pos'] = _ele::jun($_tab[$ide], [ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $_ .= _app_hol::tab_pos('kin',$kin,$ope,$ele);
              $kin = _num::ran($kin + 105, 260);
            } $_ .= "
          </ul>";          
          break;
        // nave del tiempo : 5 castillos + 20 ondas
        case 'nav':
          foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
          $_tab = _app_tab::dat('hol','cro')->ele;        
          $ele_cas = $ele['cas'];

          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">";
            foreach( _hol::_('kin_nav_cas') as $cas => $_cas ){
              $ide = "pos-{$_cas->ide}";
              $ele['cas'] = _ele::jun($_tab[$ide],[ $ele_cas, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $ope['ide'] = $_cas->ide;
              $_ .= _app_hol::tab('kin','nav_cas',$ope,$ele);
            } $_ .= "
          </ul>";
          break;
        case 'nav_cas':
          foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
          $_cas = _hol::_($est,$ide);        
          $_tab = _app_tab::dat('hol','cas')->ele;
          
          $ond_ini = ( ( $ide - 1 ) * 4 ) + 1;
          $ond_fin = $ond_ini + 4;
          $ele['cas']['title'] = _app_dat::val('tit',"hol.{$est}",$_cas);
          for( $ond = $ond_ini; $ond < $ond_fin; $ond++ ){ 
            $_ond = _hol::_('kin_nav_ond',$ond);
            $ele['cas']['title'] .= "\n".$_ond->enc_des;
          }
          _ele::cla($ele['cas'],"app_tab kin {$atr} fon_col-5-{$ide}".( empty($ope['sec']['cas-col']) ? ' fon-0' : '' ),'ini');
          $_ = "
          <ul"._ele::atr( _ele::jun($_tab['sec'],$ele['cas']) ).">
            <li"._ele::atr(_ele::jun($_tab['pos-00'],[ [ 'class'=>"bor_col-5-{$ide} fon_col-5-{$ide}" ], isset($ele['pos-00']) ? $ele['pos-00'] : [] ])).">
              {$ide}
            </li>
            "._app_hol::tab_sec('cas',$ope);
            
            $kin = ( ( $ide - 1 ) * 52 ) + 1;
            $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);
            foreach( _hol::_('cas') as $_cas ){ 
              $ide = "pos-{$_cas->ide}";
              $ele['pos'] = _ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $_ .= _app_hol::tab_pos('kin',$kin,$ope,$ele);
              $kin++;
            } $_ .= "
          </ul>";        
          break;
        case 'nav_ond':
          foreach(['cas','ond'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
          $_ond = _hol::_($est,$ide); 
          $_cas = _hol::_('kin_nav_cas',$_ond->nav_cas);
          $_tab = _app_tab::dat('hol','ton')->ele;

          $ele['ond']['title'] = _app_dat::val('tit',"hol.kin_nav_cas",$_cas)." .\n{$_ond->enc_des}"; 
          _ele::cla($ele['ond'],"app_tab kin {$atr}",'ini');
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['ond'])).">
            "._app_hol::tab_sec('ton',$ope)
            ;
            $kin = ( ( $ide - 1 ) * 13 ) + 1;
            $ele_pos = _ele::jun($_tab['pos'],$ele['pos']);
            foreach( _hol::_('ton') as $_ton ){
              $ide = "pos-{$_ton->ide}";
              $ele['pos'] = _ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $_ .= _app_hol::tab_pos('kin',$kin,$ope,$ele);
              $kin++;
            } $_ .= "
          </ul>";        
          break;      
        // armónicas : 13 trayectorias + 65 células
        case 'arm':
          foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } } 
          $_tab = _app_tab::dat('hol','ton')->ele;

          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
            "._app_hol::tab_sec('ton',$ope)
            ;
            $ele_tra = isset($_tab['pos']) ? _ele::jun($ele['tra'],$_tab['pos']) : $ele['tra'];
            foreach( _hol::_('kin_arm_tra') as $_tra ){ 
              $ide = "pos-{$_tra->ide}";
              $ele['tra'] = _ele::jun($_tab[$ide],[ $ele_tra, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $ope['ide'] = $_tra->ide;
              $_ .= _app_hol::tab('kin','arm_tra',$ope,$ele);
            } $_ .= "
          </ul>";        
          break;
        case 'arm_tra':
          foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
          $_tra = _hol::_('kin',$ide);
          $_tab = _app_tab::dat('hol','cro')->ele;

          _ele::cla($ele['tra'],"app_tab kin {$atr}",'ini');
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['tra'])).">";
            $cel_pos = 0;
            $cel_ini = ( ( intval($_tra->ide) - 1 ) * 5 ) + 1;
            $cel_fin = $cel_ini + 5; 
            $ele_cel = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['cel']) : $ele['cel'];
            for( $cel = $cel_ini; $cel < $cel_fin; $cel++ ){
              $cel_pos++;
              $ide = "pos-{$cel_pos}";
              $ele['cel'] = _ele::jun($_tab[$ide],[ $ele_cel, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $ope['ide'] = $cel;
              $_ .= _app_hol::tab('kin','arm_cel',$ope,$ele);
            } $_ .= "
          </ul>";        
          break;
        case 'arm_cel': 
          foreach(['tra','cel'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }         
          $_arm = _hol::_($est,$ide);
          $_tab = _app_tab::dat('hol','arm')->ele;

          $ele_cel = $ele['cel'];
          $ele_cel['title'] = _app_dat::val('tit',"hol.{$est}",$_arm);
          _ele::cla($ele_cel,"app_tab kin {$atr} fon_col-5-$_arm->cel fon-0");
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele_cel)).">";
            $_ .= "
            <li"._ele::atr(_ele::jun($_tab['pos-0'], isset($ele['pos-0']) ? $ele['pos-0'] : [] )).">
              "._hol::ima("sel_arm_cel", $_arm->cel, [ 'htm'=>$_arm->ide, 'class'=>'ima' ] )."
            </li>";
            $kin = ( ( $ide - 1 ) * 4 ) + 1;
            $ele_pos = $ele['pos'];
            for( $arm = 1; $arm <= 4; $arm++ ){
              $ide = "pos-{$arm}";
              $ele['pos'] = _ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $_ .= _app_hol::tab_pos('kin',$kin,$ope,$ele);
              $kin++;
            } $_ .= "
          </ul>";        
          break;
        // cromáticas : 4 estaciones + 52 elementos
        case 'cro': 
          foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
          if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_cas';
          $_tab = _app_tab::dat('hol','cas')->ele;

          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
            <li"._ele::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
              "._app::ima('hol/tab/gal')."
            </li>"
            ._app_hol::tab_sec('cas',$ope)
            ;
            $ele_ele = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['ele']) : $ele['ele'];
            foreach( _hol::_('kin_cro_ele') as $_cro ){                
              $ide = "pos-{$_cro->ide}";
              $ele['ele'] = _ele::jun($_tab[$ide],[ $ele_ele, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $ope['ide'] = $_cro->ide;
              $_ .= _app_hol::tab('kin','cro_ele',$ope,$ele);
            } $_ .= "
          </ul>";        
          break;
        case 'cro_est':
          foreach(['est','ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
          if( !in_array('fic_cas',$ope['opc']) ) $ope['opc'] []= 'fic_ond';
          $_tab = _app_tab::dat('hol','ton')->ele;

          _ele::cla($ele['est'],"app_tab kin {$atr}",'ini');
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['est'])).">
            "._app_hol::tab_sec('ton',$ope)
            ;
            $_est = _hol::_('kin_cro_est',$ide); 
            $cas = $_est->cas;
            $ele_ele = isset($_tab['ele']) ? _ele::jun($_tab['ele'],$ele['ele']) : $ele['ele'];
            foreach( _hol::_('ton') as $_ton ){                
              $ide = "pos-{$_ton->ide}";
              $ele['ele'] = _ele::jun($_tab[$ide], [ $ele_ele, isset($ele[$ide]) ? $ele[$ide] : [] ]);                
              $ope['ide'] = $cas;
              $_ .= _app_hol::tab('kin','cro_ele',$ope,$ele);
              $cas = _num::ran($cas + 1, 52);
            } $_ .= "
          </ul>";
          break;
        case 'cro_ele':
          foreach(['ele'] as $i ){ if( !isset($ele[$i]) ){ $ele[$i]=[]; } }
          
          $_tab = _app_tab::dat('hol','cro_cir')->ele;
          $_ele = _hol::_('kin_cro_ele',$ide);
          // cuenta de inicio
          $kin_ini = 185;
          $ele['ele']['title']="{$_ele->ide}: {$_ele->nom}";
          // del castillo | onda : rotaciones
          if( in_array('fic_cas',$opc) || in_array('fic_ond',$opc) ){ _ele::css($ele['ele'],
            "transform: rotate(".(in_array('fic_cas',$opc) ? $ele['rot-cas'][$ide-1] : $ele['rot-ton'][$ide-1])."deg)");
          }
          _ele::cla($ele['ele'],"app_tab kin {$atr}",'ini');
          $_ .= "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['ele'])).">
            <li"._ele::atr(_ele::jun($_tab['pos-0'],isset($ele['pos-0']) ? $ele['pos-0'] : [])).">
              "._app::ima("hol/fic/uni/col/"._num::ran($_ele->ide,4), [ 'htm'=>$_ele->ide, 'class'=>"alt-100 anc-100" ])."
            </li>";

            $kin = _num::ran($kin_ini + ( ( $ide - 1 ) * 5 ) + 1, 260);
            $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
            foreach( _hol::_('sel_cro_fam') as $cro_fam ){
              $ide = "pos-{$cro_fam->ide}";
              $ele['pos'] = _ele::jun($_tab[$ide],[ $ele_pos, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $_ .= _app_hol::tab_pos('kin',$kin,$ope,$ele);
              $kin++;// por verdad eléctrica
              if( $kin > 260 ) $kin = 1;
            }$_ .= "
          </ul>";        
          break;
        }
        break;
      case 'psi': 
        switch( $atr ){
        case 'ban': 
          foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
          $_tab = _app_tab::dat('hol','ton')->ele;
          _ele::css($ele['sec'],"grid-gap:.1rem");
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">          
            "._app::ima('hol/tab/sol',['eti'=>"li", 'fic'=>"uni-sol", 
              'style'=>"width:100%; max-width:80px; height:100%; max-height:80px; grid-row:1; grid-column:2; justify-self:end; align-self:end;" 
            ])."
            "._app::ima('hol/tab/pla',['eti'=>"li", 'fic'=>"uni-lun", 
              'style'=>"width:100%; max-width:60px; height:100%; max-height:60px; grid-row:3; grid-column:3; align-self:center; justify-self:center;" 
            ])."
            "._app_hol::tab_sec('ton',$ope)
            ;
            if( !in_array('cab_nom',$ope['opc']) ) $ope['opc'] []= 'cab_nom';
            $ele_lun = $_tab['pos'];
            foreach( _hol::_('psi_lun') as $_lun ){
              $ide = "pos-{$_lun->ide}";
              $ele_pos = _ele::jun($_tab[$ide],[$ele_lun, isset($ele["lun-{$ide}"]) ? $ele["lun-{$ide}"] : [] ]);
              $ope['ide'] = $_lun->ide;
              $_ .= "
              <li"._ele::atr($ele_pos).">"._app_hol::tab('psi','lun',$ope,$ele)."</li>";
            } $_ .= "
          </ul>";        
          break;
        // anillos solares por ciclo de sirio
        case 'ani': 
          $_tab = _app_tab::dat('hol','cas_cir')->ele;
          $ope['sec']['orb_cir'] = '1';
          $kin = 34;
  
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['sec'])).">
    
            "._app_hol::tab_sec('cas_cir',$ope)
            ;
            foreach( _hol::_('cas') as $_cas ){
              $_kin = _hol::_('kin',$kin);
              $agr = '';
              $e = $ele['pos'];
              _ele::cla($e,"pos-".intval($_cas->ide)."{$agr}",'ini');
              $e['data-hol_kin'] = $_kin->ide;
              $e['data-hol_ton'] = $_cas['ton'];
              $_ .= "
              <li"._ele::atr($e).">"._hol::ima("kin",$_kin,[ 'onclick'=>isset($e['_eje'])?$e['_eje']:NULL ])."</li>";
              $kin += 105; if( $kin >260 ) $kin -= 260;
            } $_ .= "
          </ul>";        
          break;
        // estaciones de 91 días
        case 'est':
          foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
          $_tab = _app_tab::dat('hol','cas')->ele;
  
          $_ = "
          <ul"._ele::atr( _ele::jun($_tab['sec'],$ele['sec']) ).">
            <li"._ele::atr( isset($ele['pos-00']) ? _ele::jun($_tab['pos-00'],$ele['pos-00']) : $_tab['pos-00'] ).">
              "._app::ima('hol/tab/pla',[])."
            </li>
            "._app_hol::tab_sec('cas',$ope)
            ; 
            $ele_hep = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['hep']) : $ele['hep'];
            foreach( _hol::_('cas') as $_cas ){
              $ide = "hep-{$_cas->ide}";
              $ele['hep'] = _ele::jun($_tab["pos-{$_cas->ide}"],[ $ele_hep, isset($ele[$ide]) ? $ele[$ide] : [] ]);
              $ope['ide'] = $_cas->ide;
              $_ .= _app_hol::tab('psi','hep',$ope,$ele);
            } $_ .= "
          </ul>";        
          break;
        // luna de 28 días
        case 'lun':
          foreach( ['lun','cab'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
          if( empty($ide) && is_array($val) && isset($val['psi']) ) $ide = _hol::_('psi',$val['psi'])->lun;
          $_lun = _hol::_($est,$ide);
          $_ton = _hol::_('ton',$ide);
          $_tab = _app_tab::dat('hol','lun')->ele;
          $cab_ocu = in_array('cab_ocu',$opc);
          $cab_nom = in_array('cab_nom',$opc);        
  
          _ele::cla($ele['lun'],"app_tab psi {$atr}",'ini');
          $_ = "
          <table"._ele::atr($ele['lun']).">";
            if( !$cab_ocu ){ $_ .= "
              <thead>
                <tr data-cab='ton'>
                  <th colspan='8'>
                    <div class='val tex_ali-izq' title='{$_lun->nom}: {$_lun->tot}'>
  
                      "._hol::ima("{$est}",$_lun,['class'=>( $cab_nom ? "tam-1 mar_der-1" : "tam-16 mar-1" )])."
  
                      ".( $cab_nom ? "
                        <p><n>{$ide}</n><c>°</c> ".explode(' ',$_lun->nom)[1]."</p>                      
                      " : "
                        <div>
                          <p class='tit let-4'>
                            <n>{$ide}</n><c>°</c> Luna<c>:</c> Tono ".explode(' ',$_lun->nom)[1]."
                          </p>
                          <p class='let-3 mar-1'>
                            "._app::let($_lun->ond_nom." ( $_lun->ond_pos ) : ".$_lun->ond_pod)."
                            <br>"._app::let($_lun->ond_man)."
                          </p>                   
                          <p class='let-3 mar-1'>
                            Totem<c>:</c> $_lun->tot
                            <br>Propiedades<c>:</c> "._app::let($_lun->tot_pro)."
                          </p> 
                        </div>                      
                      " )."
                    </div>
                  </th>
                </tr>";
                // agrego plasmas
                if( !$cab_nom ){ $_ .= "
                  <tr data-cab='rad'>
                    <th>
                      <span class='tex_ali-der'>Plasma</span>
                      <span class='tex_ali-cen'><c>/</c></span>                    
                      <span class='tex_ali-izq'>Héptada</span>
                    </th>";
                    foreach( _hol::_('rad') as $_rad ){ $_ .= "
                      <th>"._hol::ima("rad",$_rad,[])."</th>";
                    }$_ .= "                  
                  </tr>";
                }$_ .="
              </thead>";
            }
            $dia = 1;    
            $hep = ( ( intval($_lun->ide) - 1 ) * 4 ) + 1;
            $psi = ( ( intval($_lun->ide) - 1 ) * 28 ) + 1;
            $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'];
            $ope['eti']='td'; $_ .= "
            <tbody>";
            for( $arm = 1; $arm <= 4; $arm++ ){
              $_ .= "
              <tr class='ite-$arm'>
                <td"._ele::atr(_ele::jun([ 'data-arm'=>$arm, 'data-hep'=>$hep, 'class'=>"sec -hep fon_col-4-{$arm}" ], 
                    isset($ele[$ide = "hep-{$arm}"]) ? $ele[$ide] : []
                  )).">";
                  if( $cab_ocu || $cab_nom ){
                    $_ .= "<n>$hep</n>";
                  }else{
                    $_ .= _hol::ima("psi_hep",$hep,[]);
                  }$_ .= "
                </td>";
                for( $rad=1; $rad<=7; $rad++ ){
                  $_dia = _hol::_('lun',$dia);
                  $ide = "pos-{$_dia->ide}";
                  $ele['pos'] = _ele::jun($ele_pos, isset($ele[$ide]) ? $ele[$ide] : []);
                  $_ .= _app_hol::tab_pos('psi',$psi,$ope,$ele);
                  $dia++;
                  $psi++;
                }
                $hep++; 
                $_ .= "
              </tr>";
            }$_ .= "
            </tbody>  
          </table>";        
          break;
        // heptada de 7 días
        case 'hep': 
          foreach( ['hep'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
          if( empty($ide) && is_array($val) && isset($val['psi'])) $ide = _hol::_('psi',$val['psi'])->hep;     
          $_tab = _app_tab::dat('hol','rad')->ele;
          $_hep = _hol::_('psi_hep',$ide);
  
          _ele::cla($ele['hep'],"app_tab psi {$atr}",'ini');
          $_ = "
          <ul"._ele::atr(_ele::jun($_tab['sec'],$ele['hep'])).">";
  
            $psi = ( ( intval($_hep->ide) - 1 ) * 7 ) + 1;
            $ele_pos = isset($_tab['pos']) ? _ele::jun($_tab['pos'],$ele['pos']) : $ele['pos'] ;
            foreach( _hol::_('rad') as $_rad ){
              $ide = "pos-{$_rad->ide}";
              $ele['pos'] = _ele::jun($_tab[$ide],[ $ele_pos, isset($ele["rad-{$ide}"]) ? $ele["rad-{$ide}"] : [] ]);
              $_ .= _app_hol::tab_pos('psi',$psi,$ope,$ele);
              $psi++;
            } $_ .= "
          </ul>";        
          break;
        // banco-psi de 8 tzolkin con psi-cronos
        case 'tzo': 
          $_ = "
          <ul"._ele::atr($ele['sec']).">";
  
            $ele_tzo = $ele['sec'];
            for( $i=1 ; $i<=8 ; $i++ ){
              $ele['sec'] = $ele_tzo;
              $ele['sec']['pos'] = $i;
              if( isset($ele["tzo-$i"]) ) $ele['sec'] = _ele::jun($ele['sec'],$ele["tzo-$i"]);
              $_ .= _app_hol::tab('kin','tzo',$ope,$ele);
            } $_ .= "
          </ul>";        
          break;
        }
        break;
      case 'ani': 
        switch( $atr ){
        // telektonon
        case 'tel': 
          $ocu = []; 
          foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
    
          $_ = "
          <ul"._ele::atr($ele['sec']).">"
            ._app::ima("hol/gal",['eti'=>"li",'fic'=>'gal','title'=>'Fin de la Exhalación Solar. Comienzo de la Inhalación Galáctica.'])
            ._app::ima("hol/sol",['eti'=>"li",'fic'=>'sol','title'=>'Fin de la Inhalación Galáctica. Comienzo de la Exhalación Solar.']);
            foreach( _hol::_('sel_pol_flu') as $v ){ 
              $_ .= _hol::ima("sel_pol_flu",$v,[ 'eti'=>"li",
                'flu'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"hol.sel_pol_flu",$v)
              ]); 
            }
            foreach( _hol::_('uni_sol_res') as $v ){ 
              $_ .= _hol::ima("uni_sol_res",$v,[ 'eti'=>"li",
                'res'=>$v['ide'],'class'=>$ocu['res'],'title'=> _app_dat::val('tit',"hol.res_flu",$v)
              ]);
            }          
            foreach( _hol::_('uni_sol_pla') as $v ){
              $_ .= _hol::ima("sol_pla",$v,[ 'eti'=>"li",
                'pla'=>$v['ide'],'class'=>$ocu['pla'],'title'=> _app_dat::val('tit',"hol.sol_pla",$v)
              ]);
            }
            foreach( _hol::_('sel_cro_ele') as $v ){ $_ .= "
              <li ele='{$v['ide']}' class='{$ocu['cla']}' title='"._app_dat::val('tit',"hol.sel_cro_ele",$v)."'></li>";
            }
            foreach( _hol::_('uni_sol_cel') as $v ){ $_ .= "
              <li cel='{$v['ide']}' class='{$ocu['cel']}' title='"._app_dat::val('tit',"hol.sol_cel",$v)."'></li>";
            }
            foreach( _hol::_('uni_sol_cir') as $v ){ $_ .= "
              <li cir='{$v['ide']}' class='{$ocu['cir']}' title='"._app_dat::val('tit',"hol.sol_cir",$v)."'></li>";
            }
            foreach( _hol::_('sel') as $_sel ){ 
              $e = $ele['pos'];
              _ele::cla($e,"pos-".intval($_sel->ide)."{$ocu['sel']}",'ini'); 
              $_ .= "
              <li"._ele::atr($e).">"._hol::ima("sel_cod",$_sel)."</li>";
            }
            $_ .= " 
          </ul>";        
          break;
        // holon solar por flujo vertical
        case 'sol': 
          $ocu = []; 
          foreach( ['cel','pla','sel'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
          $_ = "
          <ul"._ele::atr($ele['sec']).">
            <li fon='map'></li>
            <li fon='ato'></li>";
            // circuitos
            foreach( ['res','cla','cel','cir','pla','sel'] as $i ){ $_ .= "
              <li fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></li>";  
            }
            foreach( _hol::_('uni_sol_pla') as $v ){ $_ .= "
              <li pla='{$v->ide}' class='{$ocu['pla']}' title='"._app_dat::val('tit',"{$esq}.sol_pla",$v)."'></li>";
            }
            foreach( _hol::_('sel') as $_sel ){ 
              $e = $ele['pos'];
              _ele::cla($e,"pos-".intval($_sel->ide),'ini'); $_ .= "
              <li"._ele::atr($e).">"._hol::ima("sel_cod",$_sel->cod)."</li>";
            }
            $_ .= " 
          </ul>";        
          break;
        // holon planetario
        case 'pla':
          $ocu = [];
          foreach( ['fam'] as $i ) $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; 
          $_ = "
          <ul"._ele::atr($ele['sec']).">
            <li fon='map'></li>";
            foreach( ['res','flu','cen','sel'] as $i ){ $_ .= "
              <li fon='$i' class='".isset($ope['sec'][$i]) ? '' : ' dis-ocu'."'></li>";
            }
            foreach( _hol::_('sel_cro_fam') as $_dat ){
              $_.=_hol::ima("sel_cro_fam",$_dat,[ 'fam'=>$_dat->ide, 'class'=>$ocu['fam'] ]);
            }
            foreach( _hol::_('sel') as $_sel ){
              $e = $ele['pos'];
              _ele::cla($e,"pos-".intval($_sel->ide).'ini'); $_ .= "
              <li"._ele::atr($e).">"._hol::ima("sel",$_sel)."</li>";
            }
            $_ .= "
          </ul>";        
          break;
        // holon humano
        case 'hum':
          $ocu = []; 
          foreach( ['ext','cen'] as $i ){ $ocu[$i] = isset($ope['sec'][$i]) ? '' : ' dis-ocu'; }
          $_ = "
          <ul"._ele::atr($ele['sec']).">
            <li fon='map'></li>";
            foreach( ['res','ext','cir','cen'] as $ide ){ $_ .= "
              <li fon='$ide' class='".isset($ope['sec'][$ide]) ? '' : ' dis-ocu'."'></li>";
            }
            foreach( _hol::_('sel_cro_ele') as $_dat ){ 
              $_ .= _hol::ima("sel_cro_ele",$_dat,['ele'=>$_dat->ide,'class'=>$ocu['ext']]);
            }
            foreach( _hol::_('sel_cro_fam') as $_dat ){ 
              $_ .= _hol::ima("sel_cro_fam",$_dat,['fam'=>$_dat->ide,'class'=>$ocu['cen']]);
            }
            foreach( _hol::_('sel') as $_dat ){
              $e = $ele['pos'];
              _ele::cla($e,"pos-".intval($_sel->ide),'ini'); $_ .= "
              <li"._ele::atr($e).">"._hol::ima("sel",$_dat)."</li>";
            }
            $_ .= "
          </ul>";        
          break;                
        }
        break;
      }
      return $_;
    }// armo tablero
    static function tab_dat( string $est, string $atr, array $ope = [], array $ele = [] ) : array {

      $_ = [ 'esq'=>"hol", 'tab'=>$est, 'ide'=>$est, 'est'=> $est = $est.( !empty($atr) ? "_$atr" : $atr ) ];
      
      // cargo elementos del tablero
      $ele = _app_tab::dat('hol',$est,$ele);
      foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ){ $ele[$v]=[]; } }
      if( empty($ele['sec']['class']) || !preg_match("/app_tab/",$ele['sec']['class']) ) _ele::cla($ele['sec'],
        "app_tab {$_['ide']}_{$atr}",'ini'
      );
      // opciones
      if( !isset($ope['opc']) ) $ope['opc'] = [];
      $opc = $ope['opc'];      
      // contador de posiciones
      $ope['_tab_pos'] = 0;
      // items
      if( !isset($ope['eti']) ) $ope['eti'] = "li";
      // operador de opciones
      if( !empty($ope['pos']['bor']) ) _ele::cla($ele['pos'],"bor-1");      
      // identificadores de datos
      if( is_object( $ide = !empty($ope['ide']) ? $ope['ide'] : 0 ) ) $ide = $ide->ide;
      // valor por posicion 
      $val = NULL;
      if( !empty($ope['val']['pos']) ){
        $val = $ope['val']['pos'];
        if( is_object($val) ){
          if( isset($val->ide) ) $val = intval($val->ide);       
        }
        else{
          $val = is_numeric($val) ? intval($val) : $val;
        }
      }
      
      $_['ide'] = $ide;
      $_['val'] = $val;
      $_['ope'] = $ope;
      $_['ele'] = $ele;
      $_['opc'] = $opc;

      return $_;     

    }// Seccion: onda encantada + castillo => fondos + pulsares + orbitales
    static function tab_sec( string $tip, array $ope=[], array $ele=[] ) : string {
      $_ = "";
      $_tip = explode('_',$tip);
      $_tab = _app_tab::dat('hol',$_tip[0])->ele;
      $_pul = ['dim'=>'','mat'=>'','sim'=>''];
      // opciones por seccion
      $orb_ocu = !empty($ope['sec']['cas-orb']) ? '' : 'dis-ocu';
      $col_ocu = !empty($ope['sec']['ond-col']) ? '' : ' fon-0';
      // pulsares por posicion
      if( in_array($_tip[0],['ton','cas']) && isset($ope['val']['pos']) ){
        
        $_val = $ope['val']['pos'];

        if( ( is_array($_val) && isset($_val['kin']->nav_ond_dia) ) || ( is_object($_val) && isset($_val->ide) ) ){

          $_ton = _hol::_('ton', is_object($_val) ? intval($_val->ide) : intval($_val['kin']->nav_ond_dia) );
          foreach( $_pul as $i => $v ){
            if( !empty($ope['opc']["ton-pul_{$i}"]) ){
              $_pul[$i] = _hol::ima("ton_pul_[$i]", $_ton["pul_{$i}"], ['class'=>'fon'] );
            }
          }
        }
      }
      switch( $_tip[0] ){
      // oraculo
      case 'par':
        break;
      // onda
      case 'ton':
        // fondo: imagen
        $_ .= "
        <{$ope['eti']}"._ele::atr(_ele::jun($_tab['ima'],[ 'class'=>DIS_OCU ]))."></{$ope['eti']}>";
        // fondos: color
        $_ .= "
        <{$ope['eti']}"._ele::atr(_ele::jun($_tab['fon'],[ 'class'=>"{$col_ocu}" ]))."></{$ope['eti']}>";
        // pulsares
        foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
          <{$ope['eti']}"._ele::atr(_ele::jun($_tab['ond'],[ 'data-pul'=>$ide ])).">{$_pul[$ide]}</{$ope['eti']}>";
        }
        break;
      // castillo
      case 'cas':
        // fondos: imagen
        for( $i = 1; $i <= 4; $i++ ){ $_ .= "
          <{$ope['eti']}"._ele::atr(_ele::jun($_tab["ond-{$i}"],[ $_tab['ima'], [ 'class'=>DIS_OCU ] ]))."></{$ope['eti']}>";
        }
        // fondos: color
        for( $i = 1; $i <= 4; $i++ ){ $_ .= "
          <{$ope['eti']}"._ele::atr(_ele::jun($_tab["ond-{$i}"],[ $_tab['fon'], [ 'class'=>"fon_col-4-{$i}{$col_ocu}" ] ]))."></{$ope['eti']}>";
        }        
        // bordes: orbitales
        for( $i = 1; $i <= ($tip == 'cas_cir' ? 8 : 5); $i++ ){ $_ .= "
          <{$ope['eti']}"._ele::atr(_ele::jun(['class'=>$orb_ocu ],[ $_tab['orb'], $_tab["orb-{$i}"] ]))."></{$ope['eti']}>";
        }        
        // fondos: pulsares
        for( $i = 1; $i <= 4; $i++ ){
          foreach( ['dim','mat','sim'] as $ide ){ $_ .= "
            <{$ope['eti']}"._ele::atr(_ele::jun($_tab['ond'],[ [ 'data-pul'=>$ide ] , $_tab["ond-{$i}"] ])).">{$_pul[$ide]}</{$ope['eti']}>";
          }
        }
        break;      
      }
      return $_;
    }// Posicion: datos + titulos + contenido[ ima, num, tex]
    static function tab_pos( string $est, mixed $val, array &$ope, array $ele ) : string {
      $esq = 'hol';
      // recibo objeto 
      if( is_object( $val_ide = $val ) ){
        $_dat = $val;
        $val_ide = intval($_dat->ide);
      }// o identificador
      else{
        $_dat = _hol::_($est,$val);
      }
      // seccion
      $_val['sec_par'] = !empty($ope['sec']['par']) ? 'sec_par' : FALSE;
      // posicion
      $_val['pos_dep'] = !empty($ope['sec']['pos_dep']);// patrones
      $_val['pos_col'] = !empty($ope['pos']['col']) ? $ope['pos']['col'] : FALSE;// color
      $_val['pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;// imagen      
      // portales 
      $_val['kin_pag'] = !empty($ope['pag']['kin']);
      $_val['psi_pag'] = !empty($ope['pag']['psi']);
      //////////////////////////////////////////////////////////////////////////
      // cargo datos ///////////////////////////////////////////////////////////

        $e = isset($ele['pos']) ? $ele['pos'] : [];      
        // por acumulados
        if( isset($ope['dat']) ){

          foreach( $ope['dat'] as $pos => $_ref ){

            if( isset($_ref["{$esq}_{$est}"]) && intval($_ref["{$esq}_{$est}"]) == $val_ide ){

              foreach( $_ref as $ref => $ref_dat ){

                $e["data-{$ref}"] = $ref_dat;
              }            
              break;
            }
          }
        }
        // por dependencias estructura
        else{
          if( !empty( $dat_est = _app::dat($esq,$est,'rel') ) ){

            foreach( $dat_est as $atr => $ref ){

              if( empty($e["data-{$ref}"]) ){

                $e["data-{$ref}"] = $_dat->$atr;
              }        
            }
          }// pos posicion
          elseif( empty($e["data-{$esq}_{$est}"]) ){    
            $e["data-{$esq}_{$est}"] = $_dat->ide;
          }
        }    
      //////////////////////////////////////////////////////////////////////////
      // .posiciones del tablero principal /////////////////////////////////////
        $agr = "";    
        // omito dependencias
        if( !$_val['pos_dep'] ){

          $agr = "app_ope";

          if( $_val['sec_par'] ){ 
            $agr .= ( !empty($agr) ? ' ': '' ).$_val['sec_par']; 
            $par_ima = !empty($_val['pos_ima']) ? $_val['pos_ima'] : "{$esq}.{$est}.ide";
          }

          if( isset($ope['val']['pos']) ){

            $dat_ide = $ope['val']['pos'];

            if( is_array($dat_ide) && isset($dat_ide[$est]) ){
              $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
            }
            // agrego seleccion
            if( $_dat->ide == $dat_ide ) $agr .= ( !empty($agr) ? ' ': '' ).'_val-pos _val-pos_bor';
          }
        }
      //////////////////////////////////////////////////////////////////////////    
      // armo titulos //////////////////////////////////////////////////////////
        $pos_tit = [];
        if( isset($e["data-fec_dat"]) ){
          $pos_tit []= "Calendario: {$e["data-fec_dat"]}";
        }
        if( isset($e["data-hol_kin"]) ){
          $_kin = _hol::_('kin',$e["data-hol_kin"]);
          $pos_tit []= _app_dat::val('tit',"hol.kin",$_kin);
          if( $_val['kin_pag'] && !empty($_kin->pag) ) $agr .= " _hol-pag_kin";
        }
        if( isset($e["data-hol_sel"]) ){
          $pos_tit []= _app_dat::val('tit',"hol.sel",$e["data-hol_sel"]);
        }
        if( isset($e["data-hol_ton"]) ){
          $pos_tit []= _app_dat::val('tit',"hol.ton",$e["data-hol_ton"]);
        }
        if( isset($e["data-hol_psi"]) ){
          $_psi = _hol::_('psi',$e["data-hol_psi"]);
          $pos_tit []= _app_dat::val('tit',"hol.psi",$_psi);          
          if( $_val['psi_pag'] ){
            $_psi->tzo = _hol::_('kin',$_psi->tzo);
            if( !empty($_psi->tzo->pag) ) $agr .= " _hol-pag_psi";
          }
        }
        if( isset($e["data-hol_rad"]) ){
          $pos_tit []= _app_dat::val('tit',"hol.rad",$e["data-hol_rad"]);
        }
        $e['title'] = implode("\n\n",$pos_tit);

      //////////////////////////////////////////////////////////////////////////
      // Contenido html ////////////////////////////////////////////////////////
        // clases adicionales
        if( !empty($agr) ){ _ele::cla($e,$agr,'ini'); }

        $htm = ""; 
        // por patrones: posicion por dependencia
        if( !empty($_dat) && !!$_val['sec_par'] ){

          $ele_sec = $e;

          if( isset($ele_sec['class']) ){
            unset($ele_sec['class']);
          }
          if( isset($ele_sec['style']) ){ 
            unset($ele_sec['style']);
          }
          
          $htm = _app_hol::tab($est,'par',[
            'ide'=>$_dat,
            'sec'=>[ 'par'=>$ope['sec']['par'] - 1, 'pos_dep'=>1 ],// fuera de posicion principal ( [pos].pos )
            'pos'=>[ 'ima'=>isset($par_ima) ? $par_ima : "hol.{$est}.ide" ]
          ],[
            'sec'=>$ele_sec
          ]);
        }
        // genero posicion
        elseif( !empty($_dat) ){
          // color de fondo
          if( $_val['pos_col'] ){
            $_ide = _dat::ide($_val['pos_col']);
            if( 
              isset($e["data-{$_ide['esq']}_{$_ide['est']}"]) 
              && 
              !empty( $_dat = _dat::get($_ide['esq'],$_ide['est'],$e["data-{$_ide['esq']}_{$_ide['est']}"]) ) 
            ){
              $col = _dat::opc('col', ...explode('.',$_val['pos_col']));
              if( isset($col['val']) ){
                $col = $col['val'];
                $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
                _ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : _num::ran($val,$col) ) );
              }              
            }
          }
          // contenido
          foreach( ['ima','num','tex','fec'] as $tip ){

            if( !empty($ope['pos'][$tip]) ){
              $ide = _dat::ide($ope['pos'][$tip]);

              $htm .= _app_dat::ver($tip, $ope['pos'][$tip], $e["data-{$ide['esq']}_{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
            }
          }
        }
        // agrego posicion automatica-incremental
        $ope['_tab_pos']++;
        _ele::cla($e,"pos ide-{$ope['_tab_pos']}",'ini');
      //////////////////////////////////////////////////////////////////////////
      // devuelvo posicion /////////////////////////////////////////////////////
      return "
      <{$ope['eti']}"._ele::atr($e).">{$htm}</{$ope['eti']}>";
    }
  }
  // Archivo
  class _app_arc {

    static string $IDE = "_app_arc-";

    static string $EJE = "_app_arc.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      if( isset($ope['tip']) ) $ope['accept'] = _arc::tip($ope['tip']);

      switch( $tip ){
      case 'val':
        $ope['type']='file';
        if( isset($ope['multiple']) ) unset($ope['multiple']);
        break;
      case 'lis':
        $ope['type']='file';
        $ope['multiple'] = '1';
        break;
      case 'url':
        $ope['type']='url';
        break;
      // ima - vid - mus
      default:
        $ope['type']='file';
        $ope['accept'] = _arc::tip($tip);
        break;      
      }
      if( empty($_) && !empty($ope['type']) ){
        $_ = "<input"._ele::atr($ope).">";
      }
      return $_;
    }    
    
  }
  // Obejeto
  class _app_obj {

    static string $IDE = "_app_obj-";
    static string $EJE = "_app_obj.";

    // operadores 
    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE;
      $_eje = self::$EJE;

      // texto : json
      if( !isset($dat) || is_string($dat) ){
        $ope['value'] = strval($dat); $_ = "
        <textarea"._ele::atr($ope).">{$dat}</textarea>";
      }
      // por tipos: pos - nom - atr
      elseif( $tip = _obj::tip($dat) ){
        $cue = 0; 
        $htm = '';
        $cla_agr = ''; 
        $cla_tog = ' dis-ocu';
        if( in_array('ocu',$opc) ){ 
          $cla_agr = ' dis-ocu'; 
          $cla_tog = ''; 
        }
        $atr_agr = in_array('dat',$opc) ? '' : " disabled";
        // separadores
        if( $tip=='pos' ){ $ini='('; $fin=')'; }elseif( $tip=='nom' ){ $ini='['; $fin=']'; }else{ $ini='{'; $fin='}'; }
        // conteido
        if( is_object($dat) ){
          // ... incluir metodos
        }
        foreach( $dat as $i=>$v ){ 
          $cue++; 
          $htm .= _app_obj::ite( $i, $v, $tip, ...$opc);
        }
        _ele::cla($ope,"app_obj {$tip}",'ini');
        $_ = "
        <div"._ele::atr($ope).">
          <div class='jus-ini mar_ver-1'>
            <p>
              <c>(</c> <n class='sep'>{$cue}</n> <c>)</c> <c class='sep'>=></c> <c class='_lis-ini'>{$ini}</c>
            </p>
            "._app::ico('dat_ver',['onclick'=>"$_eje.val(this,'tog');"])."
            <ul class='ope _tog{$cla_agr}'>"; 
              if( empty($atr_agr) ){ $_.="
              "._app::ico('dat_tod',['eti'=>"li",'onclick'=>"$_eje.val(this,'tod');"])."
              "._app::ico('dat_nad',['eti'=>"li",'onclick'=>"$_eje.val(this,'nad');"])."
              "._app::ico('dat_agr',['eti'=>"li",'onclick'=>"$_eje.val(this,'agr');"])."
              "._app::ico('dat_eli',['eti'=>"li",'onclick'=>"$_eje.val(this,'eli');"])."
              ";
              }$_.="
            </ul>
            <p class=' _tog{$cla_tog}'>
              <c class='sep _lis-fin'>{$fin}</c>
            </p>
          </div>
          <ul class='lis _atr ali-ini _tog{$cla_agr}'> 
            {$htm}
          </ul>
          <p class='_tog{$cla_agr}'>
            <c class='_lis-fin'>{$fin}</c>
          </p>
        </div>";
      }

      return $_;
    }
    // item por tipo
    static function ite( mixed $ide, mixed $dat = NULL, string $tip = 'pos', array $ope = [], ...$opc ) : string {
      $_ = "";

      $ope['ent']=isset($ope['ent'])?$ope['ent']:'alm';
      
      $ope['eti']=isset($ope['eti'])?$ope['eti']:[]; 
      $ope['ite']=isset($ope['ite'])?$ope['ite']:[];      

      if( is_null($dat) ){ 
        $dat=''; 
        $tip_dat='val'; 
        $tip_val='vac'; 
        $_="<input type='radio' disabled>";
      }
      else{ 
        $tip = _dat::tip($dat); 
        $tip_dat = $tip['dat']; 
        $tip_val = $tip['val']; 
      }

      $ite = "";
      if( in_array('dat',$opc) && $tip != 'val' ){ 
        $ite = "<input type='checkbox'>"; 
      }
      // items de lista -> // reducir dependencias
      $cla_ide = "_app_{$tip_dat}";
      if( in_array($tip_dat,[ 'lis' ]) ){

        if( $ite != "" ){          
          $_ = $cla_ide::ope( $tip_val, $dat, [ 'ide'=>"{$ope['ent']}.{$ide}", 'eti'=>$ope['ite'] ] );
        }
        else{
          $_ = _app_opc::val( $dat, [ 'eti'=>$ope['eti'], 'ite'=>$ope['ite'] ] );
        }
      }// controladores
      else{

        $dat = is_string($dat) ? $dat : strval($dat); 
        $_ = !empty($ope) ? $cla_ide::ope( $tip_val, $dat, $ope['ite'] ) : "<p"._ele::atr($ope['ite']).">{$dat}</p>";
      }
      $ide='';
      if( !empty($ite) ){ 
        $agr = "";
        if( $tip == 'pos' ){
          $agr = " tam='2'";
          $tip = "number";
        }else{
          $tip = "text";
        }
        $ide="<input class='ide' type='{$tip}'{$agr} value='{$ide}' title='{$ide}'>";
      }
      else{ 
        $ide="<c class='sep'>[</c><n>{$ide}</n><c class='sep'>]</c>";
      }
      if( $tip == 'pos' ){
        $sep='='; 
      }else{ 
        $sep=( $tip == 'nom' ) ? '=>' : ':' ; 
      }  
      $sep = "<c class='sep'>{$sep}</c>"; 

      return "
      <li class='atr' data-ide='{$ide}'>
        {$ite}{$ide}{$sep}{$_}
      </li>";  
    }
  }
  // Ejecucion
  class _app_eje {

    static string $IDE = "_app_eje-";

    static string $EJE = "_app_eje.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }
  // Elemento
  class _app_ele {

    static string $IDE = "_app_ele-";

    static string $EJE = "_app_ele.";

    static function ope( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
      $_ = "";
      $_ide = self::$IDE."$tip";
      $_eje = self::$EJE."$tip";

      return $_;
    }    
    
  }