<?php

// Página-app
class app {

  static string $IDE = "app-";
  static string $EJE = "app.";

  public array  $rec;
  public array  $doc;
  public array  $ope;
  public array  $htm;

  // cargo aplicacion ( por defecto: sincronario )
  function __construct( string $esq = 'hol' ){

    global $sis_rec;

    $_uri = $this->uri($esq);

    // Recursos : css + jso + eje + dat + obj
    $this->rec = [
      // clases de sistema
      'jso' => $sis_rec,
      'css' => array_keys($sis_rec),
      'css-fin' => [
        'https://fonts.googleapis.com/css?family=Material+Icons+Outlined',
        'api/ope'
      ],
      // elementos      
      'ele' => [
        'title'=> "{-_-}",
        'body' => [
          'data-doc'=> $_uri->esq,
          'data-cab'=> !!$_uri->cab ? $_uri->cab : NULL, 
          'data-art'=> !!$_uri->art ? $_uri->art : NULL 
        ]
      ],
      // ejecuciones
      'eje' => "",
      // datos de la base
      'dat' => [
        'app'=>[ 'ico', 'uri', 'dat' ],
        'dat'=>[ 'tip' ],
        'tex'=>[ 'let' ],
        'fec'=>[ 'mes','sem','dia' ]
      ]
    ];
    // Operadores : boton + panel + pantalla
    $this->ope = [
      // inicio
      'app_ini'=>[
        'ico'=>"app", 'url'=>SYS_NAV."/".$_uri->esq, 'nom'=>"Página de Inicio"
      ],// menu
      'app_cab'=>[
        'ico'=>"app_cab", 'tip'=>"pan", 'nom'=>"Menú Principal", 'htm'=> app::cab($_uri->esq)
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
    $this->doc['esq'] = api::dat('app_esq',[ 
      'ver'=>"`ide`='{$_uri->esq}'", 
      'opc'=>'uni' 
    ]);
    if( !empty($_uri->cab) ){
      // cargo datos del menu
      $this->doc['cab'] = api::dat('app_cab',[ 
        'ver'=>"`esq`='{$_uri->esq}' AND `ide`='{$_uri->cab}'", 
        'ele'=>'ope', 'opc'=>'uni' 
      ]);
      // cargo datos del artículo
      if( !empty($_uri->art) ){
        $this->doc['art'] = api::dat('app_art',[ 
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
  
        $this->doc['nav'] = api::dat('app_nav',[
          'ver'=>"`esq` = '{$_uri->esq}' AND `cab` = '{$_uri->cab}' AND `ide` = '{$_uri->art}'", 
          'ord'=>"pos ASC", 
          'nav'=>'pos'
        ]);
        // pido listado por navegacion
        if( !empty($this->doc['nav'][1]) ){
          $this->ope['app_nav']['htm'] = app::art($this->doc['nav']);
        }
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
  // cargo datos de la base
  static function dat( string $esq, string $est, string $ope, mixed $dat = NULL ) : mixed {
    
    global $_api;
    if( !isset($_api->app_dat[$esq][$est]) ){
      
      $_api->app_dat[$esq][$est] = api::dat('app_dat',[
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
    if( $ope_atr[0] == 'val' && isset($dat) ) $_ = api_obj::val( api::dat($esq,$est,$dat), $_ );

    return $_;
  }

  // cargo página
  public function ini() : void {

    global $sis_ini, $_usu;

    $_uri = $this->uri();
    // pido contenido por aplicacion
    if( file_exists($cla_rec = "./php/{$_uri->esq}.php") ){

      require_once($cla_rec);

      if( class_exists( $cla = $_uri->esq ) ){

        new $cla( $this );
      }                
    }

    // usuario + loggin
    // $tip = empty($_usu->ide) ? 'ini' : 'fin';
    // $this->ope["ses_{$tip}"]['htm'] = app::usu($tip);

    // consola del sistema
    if( $_usu->ide == 1 ){
      $this->rec['jso']['app'] []= "adm";
      $this->ope['api_adm'] = [ 'ico'=>"eje", 'bot'=>"fin", 'tip'=>"win", 'nom'=>"Consola del Sistema", 
        'art'=> [ 'style'=>"max-width: 55rem;" ],
        'htm'=> app::adm()
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
        $this->htm['ope'][$ope['bot']] .= app::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
      }
      // paneles y modales
      elseif( ( $ope['tip'] == 'pan' || $ope['tip'] == 'win' ) && !empty($ope['htm']) ){
        // botones          
        $this->htm['ope'][$ope['bot']] .= app::bot([ $ide => $ope ]);
        // contenido
        $this->htm[$ope['tip']] .= app::{$ope['tip']}($ide,$ope);
      }
    }
    // cargo modal de operadores
    $this->htm['win'] .= app::win('app_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);

    // cargo contenido principal
    $ele = [ 'tit' => $this->rec['ele']['title'] ];
    $this->htm['sec'] = app::sec( $this->htm['sec'], $ele );
    
    // ajusto diseño
    $_ver = [];
    foreach( ['bar','pie'] as $ide ){
      if( !empty($this->htm[$ide]) ) $_ver []= $ide; 
    }
    if( !empty($_ver) ) $this->rec['ele']['body']['data-ver'] = implode(',',$_ver);

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
        foreach( [ $this->rec['css'], $this->rec['css-fin'] ] as $css ){ 
          foreach( $css as $ide ){
            if( preg_match("/^http/", $ide) ){ echo "
              <link rel='stylesheet' href='$ide' >";
            }
            elseif( file_exists( $rec = "css/{$ide}.css" ) ){ echo "
              <link rel='stylesheet' href='".SYS_NAV.$rec."' >";
            }
          }
        }?>
        <title><?= $this->rec['ele']['title'] ?></title>
      </head>

      <body <?= api_ele::atr($this->rec['ele']['body']) ?>>
        
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
        foreach( $this->rec['jso'] as $app => $cla_lis ){
          if( file_exists( $rec = "jso/{$app}.js" ) ){ echo "
          <script src='".SYS_NAV.$rec."'></script>";
          }
          foreach( $cla_lis as $cla ){
            if( file_exists( $rec = "jso/{$app}/{$cla}.js" ) ){ echo "
            <script src='".SYS_NAV.$rec."'></script>";
            }
          }
        }?>
        <script>
          // cargo datos de la interface
          var $_api = new api(<?= api_obj::cod( $this->rec['dat'] ) ?>);
          
          // cargo aplicacion
          var $_app = new app();
          
          // ejecuto codigo por aplicacion
          <?= $this->rec['eje'] ?>

          // inicializo página
          $_app.ini();

          console.log(`{-_-}.ini: en ${( ( Date.now() - (  <?= $sis_ini ?> * 1000 ) ) / 1000 ).toFixed(2)} segundos...`);

        </script>
      </body>

    </html>      
    <?php
  }
  // consola del sistema
  static function adm() : string {
    $_eje = "app_adm";  
    $_ide = "app-adm";
    return app::nav('bar', [

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
        
        ".app::var('val','ver',['nom'=>"Filtrar",'ope'=>[ 
          '_tip'=>"tex_ora", 'id'=>"_adm-ico-ver", 'oninput'=>"$_eje('ico',this,'ver')" 
        ]])."
  
        <ul class='lis ite mar-2' style='height: 48vh;'>
        </ul>
        "
      ],
      'jso' => [ 'nom'=>"J.S.", 
        'htm'=>"
  
        <fieldset class='inf pad-3'>
          <legend>Ejecutar JavaScript</legend>      
  
          ".app::var('val','cod',[ 
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
  
          ".app::var('val','ide',[ 'ope'=>[ '_tip'=>"tex_ora" ] ])."
          
          ".app::var('val','par',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_hor-1"], 
            'htm_ini'=>"<c>(</c>", 'htm_fin'=>"<c>)</c>"
          ])."
  
          ".app::var('val','htm',[
            'nom'=>"¿HTML?",
            'ope'=>[ '_tip'=>"opc_bin", 'val'=>1, 'id'=>"_adm-php-htm" ]
          ])."
          
          ".app::ico('dat_ope',[
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
  
          ".app::var('val','cod',[ 
            'ite'=>[ 'class'=>"tam-cre" ], 
            'ope'=>[ '_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1" ],
            'htm_fin'=> app::ico('dat_ope',[ 'eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('sql',this,'cod')" ])
          ])."
  
        </fieldset>
  
        <div class='ope_res mar-1' var='est' style='height: 47vh;'>
        </div>"
      ],
      'htm' => [ 'nom'=>"D.O.M.",
        'htm'=>"
        <fieldset class='inf ite pad-3'>
          <legend>Ejecutar Selector</legend>
  
          ".app::var('val','cod',[ 
            'ite'=>['class'=>"tam-cre"], 
            'ope'=>['_tip'=>"tex_ora", 'class'=>"anc-100 mar_der-1"],
            'htm_fin'=> app::ico('dat_ope',['eti'=>"button", 'type'=>"submit", 'onclick'=>"$_eje('htm',this,'cod')"])
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
  }
  // sesion del usuario
  static function usu( string $tip ) : string {
    switch( $tip ){
      // datos del usuario
      case 'ses':
        $esq = 'api'; 
        $est = 'usu';
        global $_usu;
        $_kin = api_hol::_('kin',$_usu->kin);
        $_psi = api_hol::_('psi',$_usu->psi);
        $_ = "
        <form class='api_dat' data-esq='{$esq}' data-est='{$est}'>

          <fieldset class='ren'>

            ".app::var('atr', [$esq,$est,$atr='nom'], [ 'val'=>$_usu->$atr  ], 'eti')."

            ".app::var('atr', [$esq,$est,$atr='ape'], [ 'val'=>$_usu->$atr  ], 'eti')."                        
          
          </fieldset>

          <fieldset class='ren'>

            ".app::var('atr', [$esq,$est,$atr='mai'], [ 'val'=>$_usu->$atr  ],'eti')."

            ".app::var('atr', [$esq,$est,$atr='fec'], [ 'val'=>$_usu->$atr, 'ite'=>[ 'class'=>"tam-ini" ]  ], 'eti')."

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
        foreach( api_tex::let($pal) as $car ){
          if( is_numeric($car) ){
            $let []= "<n>{$car}</n>";
          }elseif( isset($_let[$car]) ){
            //api_ele::cla($ele_let,"{$_let[$car]->var}",'ini');
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
      <{$eti}".api_ele::atr(api_ele::cla($ele,"ico $ide material-icons-outlined",'ini')).">{$_ico[$ide]->val}</{$eti}>";
    }
    return $_;
  }
  // imagen : .ima.$ide
  static function ima( ...$dat ) : string {
    $_ = "";
    // por aplicacion
    if( isset($dat[2]) ){
      $ele = isset($dat[3]) ? $dat[3] : [];
      $_ = app_dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], $ele );
    }
    // por directorio
    else{
      $ele = isset($dat[1]) ? $dat[1] : [];
      $dat = $dat[0];
      // por estilos : bkg
      if( is_array($dat) ){
        $ele = api_ele::jun( $dat, $ele );          
      }
      // por directorio : localhost/img/esquema/image
      elseif( is_string($dat)){
        $ima = explode('.',$dat);
        $dat = $ima[0];
        $tip = isset($ima[1]) ? $ima[1] : 'png';
        $dir = SYS_NAV."img/{$dat}";
        api_ele::css( $ele, api_ele::fon($dir,['tip'=>$tip]) );
      }
      // etiqueta
      $eti = 'span';
      if( isset($ele['eti']) ){
        $eti = $ele['eti'];
        unset($ele['eti']);
      }// codifico boton
      if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button";
      // ide de imagen
      api_ele::cla($ele,"ima",'ini');
      // contenido
      $htm = "";
      if( !empty($ele['htm']) ){
        api_ele::cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $htm = $ele['htm'];
        unset($ele['htm']);
      }
      $_ = "<{$eti}".api_ele::atr($ele).">{$htm}</{$eti}>";
    }
    return $_;
  }

  // botones : ( pan + win )
  static function bot( $dat ) : string {
    $_ = "";      
    $_eje = self::$EJE;
    
    foreach( $dat as $ide => $art ){
      
      $tip = isset($art['tip']) ? $art['tip'] : 'nav';

      $eje_tog = "{$_eje}{$tip}('$ide');";

      if( is_string($art) ){

        $_ .= app::ico( $art, [ 'eti'=>"a", 'onclick'=>$eje_tog ]);
      }
      elseif( is_array($art) ){

        if( isset($art[0]) ){

          $_ .= app::ico( $art[0], [ 'eti'=>"a", 'title'=>isset($art[1])?$art[1]:'', 'onclick'=>$eje_tog ]);
        }
        elseif( isset($art['ico']) ){

          $_ .= app::ico( $art['ico'], [ 'eti'=>"a", 'title'=>isset($art['nom'])?$art['nom']:'', 'onclick'=>$eje_tog ]);
        }
      }
      elseif( is_object($art) && isset($art->ico) ){

        $_ .= app::ico( $art->ico, [ 'eti'=>"a", 'title'=>isset($art->nom)?$art->nom:'', 'onclick'=>$eje_tog ]);
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
    if( isset($ope['ico']) ){
      if( is_string($ope['ico']) ){
        $cab_ico = app::ico($ope['ico'],['class'=>"mar_hor-1"]);
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
      <h2 style='text-decoration: none; margin:0;'>".( !empty($ope['nom']) ? app::let($ope['nom']) : "" )."</h2>
    ";
    // botones de flujo
    $cab_bot = "
    <div class='ope'>
      ".app::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'data-ope'=>"fin", 'onclick'=>"$_eje(this);" ])."
    </div>";
    // contenido 
    if( !isset($ope['htm']) ){
      $ope['htm'] = '';
    }
    elseif( is_array($ope['htm']) ){ 
      $ope['htm'] = api_ele::dec( $ope['htm'] );
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
  // Panel : nav|article[ide] > header + section
  static function pan( string $ide, array $ope = [] ) : string {
    foreach( ['nav','cab','sec'] as $e ){ if( !isset($ope[$e]) ){ $ope[$e]=[]; } }            
    $_eje = self::$EJE."pan";
    $_ = "";
    $cab_ico = "";
    if( !empty($ope['ico']) ) $cab_ico = app::ico($ope['ico'],['class'=>"mar_hor-1"]);

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
      $ope['htm'] = api_ele::dec( $ope['htm'] );
    }

    // imprimo con identificador
    api_ele::cla($ope['nav'],"ide-$ide",'ini');
    api_ele::cla($ope['nav'],DIS_OCU);
    $_ = "
    <$eti_nav".api_ele::atr($ope['nav']).">

      <header".api_ele::atr($ope['cab']).">
      
        {$cab_ico} {$cab_tit} ".app::ico('dat_fin',[ 'title'=>'Cerrar ( tecla "Esc" )', 'onclick'=>"$_eje();" ])."

      </header>

      <$eti_sec".api_ele::atr($ope['sec']).">

        {$ope['htm']}

      </$eti_sec>

    </$eti_nav>";
    
    return $_;
  }
  // Seccion : main > ...article
  static function sec( string | array $dat, array $ele = [] ) : string {
    $_ = "";
    if( isset($ele['tit']) ){ $_ .= "
      <header".api_ele::atr( isset($ele['cab']) ? $ele['cab'] : [] ).">";
        if( is_string($ele['tit']) ){ $_ .= "
          <h1 class='mar-0'>".app::let($ele['tit'])."</h1>";
        }else{
          $_ .= api_ele::dec(...$ele['tit']);
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

  // Menu : titulo + descripcion + listado > item = [icono] + enlace
  static function cab( string $esq, array $ele = [] ) : string {
    global $_usu;      
    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $_lis = [];
    foreach( api::dat('app_cab',[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

      if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($_usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? app::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $_lis_val = [];
      foreach( api::dat('app_art',[ 
        'ver'=>"`esq`='$esq' AND `cab`='$_cab->ide'", 'ord'=>"`pos` ASC" ]) as $_art 
      ){

        $ele_val = !empty($_art->ele) ? $_art->ele : [ 'class'=>"dis-fle ali-cen" ];

        if( !empty($_art->des) ) $ele_val['title'] = $_art->des;

        $ele_val['href'] = SYS_NAV."/$_art->esq/$_art->cab/$_art->ide";

        $_lis_val []= "
        <a".api_ele::atr($ele_val).">"
          .( !empty($_art->ico) ? app::ico( $_art->ico, [ 'class'=>"mar_der-1" ] ) : $ite_ico )
          ."<p>".app::let($_art->nom)."</p>
        </a>";
      }
      $_lis []= [ 
        'ite'=>[ 'eti'=>"p", 'class'=>"ide-$_cab->ide mar_ver-1 let-tit let-4", 'htm'=>$ite_ico.app::let($_cab->nom) ],
        'lis'=>$_lis_val 
      ];
    }
    // reinicio opciones
    api_ele::cla($ele['lis'],"nav");
    api_ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [ 'tog' ]; // dlt- 'ver', 'cue'
    return app_lis::val($_lis,$ele);

  }
  // Indice : a[href] > ...a[href]
  static function art( array $dat, array $ele = [], ...$opc ) : string {
    foreach( ['ope','ope_dep','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    $_eje = self::$EJE."art_";// val | ver
    $_ = "";

    // operador
    api_ele::cla( $ele['ope'], "ren", 'ini' );
    $_ .= "
    <form".api_ele::atr($ele['ope']).">

      ".app::val_ope()."

      ".app::val_ver([ 'cue'=>0, 'ele_val'=>['class'=>"anc-100"], 'eje'=>"{$_eje}ver(this);" ])."      

    </form>";
    // dependencias
    $tog_dep = FALSE;
    if( in_array('tog_dep',$opc) ){
      api_ele::cla( $ele['ope_dep'], "ite", 'ini' ); $tog_dep = "
      <form".api_ele::atr($ele['ope_dep']).">

        ".app::val_ope()."

      </form>";
    }
    // armo listado de enlaces
    $_lis = [];
    $opc_ide = in_array('ide',$opc);
    api_ele::cla( $ele['lis'], "nav", 'ini' );
    foreach( $dat[1] as $nv1 => $_nv1 ){
      $ide = $opc_ide ? $_nv1->ide : $nv1;
      $eti_1 = ['eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=> app::let("{$_nv1->nom}") ];
      if( !isset($dat[2][$nv1]) ){
        $_lis []= api_ele::val($eti_1);
      }
      else{
        $_lis_2 = [];
        foreach( $dat[2][$nv1] as $nv2 => $_nv2 ){
          $ide = $opc_ide ? $_nv2->ide : "{$nv1}-{$nv2}"; 
          $eti_2 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=> app::let("{$_nv2->nom}") ];
          if( !isset($dat[3][$nv1][$nv2])  ){
            $_lis_2 []= api_ele::val($eti_2);
          }
          else{
            $_lis_3 = [];              
            foreach( $dat[3][$nv1][$nv2] as $nv3 => $_nv3 ){
              $ide = $opc_ide ? $_nv3->ide : "{$nv1}-{$nv2}-{$nv3}";
              $eti_3 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=> app::let("{$_nv3->nom}") ];
              if( !isset($dat[4][$nv1][$nv2][$nv3]) ){
                $_lis_3 []= api_ele::val($eti_3);
              }
              else{
                $_lis_4 = [];                  
                foreach( $dat[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){
                  $ide = $opc_ide ? $_nv4->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}"; 
                  $eti_4 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=> app::let("{$_nv4->nom}") ];
                  if( !isset($dat[5][$nv1][$nv2][$nv3][$nv4]) ){
                    $_lis_4 []= api_ele::val($eti_4);
                  }
                  else{
                    $_lis_5 = [];                      
                    foreach( $dat[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){
                      $ide = $opc_ide ? $_nv5->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}"; 
                      $eti_5 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=> app::let("{$_nv5->nom}") ];
                      if( !isset($dat[6][$nv1][$nv2][$nv3][$nv4][$nv5]) ){
                        $_lis_5 []= api_ele::val($eti_5);
                      }
                      else{
                        $_lis_6 = [];
                        foreach( $dat[6][$nv1][$nv2][$nv3][$nv4][$nv5] as $nv6 => $_nv6 ){
                          $ide = $opc_ide ? $_nv6->ide : "{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-{$nv6}"; 
                          $eti_6 = [ 'eti'=>"a", 'href'=>"#_{$ide}-", 'onclick'=>"{$_eje}val(this);", 'htm'=> app::let("{$_nv6->nom}") ];
                          if( !isset($dat[7][$nv1][$nv2][$nv3][$nv4][$nv5][$nv6]) ){
                            $_lis_6 []= api_ele::val($eti_6);
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
    api_ele::cla($ele['dep'],DIS_OCU);
    $ele['opc'] = [];
    $_ .= app_lis::val($_lis,$ele);
    return $_;
  }// Articulo : cabecera + ...secciones + pie de página
  static function art_sec( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = api_ele::htm($nav->ope);

    $_art = api::dat('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
            $art_url = "<a href='".SYS_NAV."/{$art->esq}/{$art->cab}/{$art->ide}'>".app::let($art->nom)."</a>";
            if( !empty($art->ope['tex']) ){
              $_ .= "            
              <div class='val nav'>
                ".app::val_ico()."
                {$art_url}
              </div>
              <div class='dat'>
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
  }// secciones por indices : section > h2 + ...section > h3 + ...section > ...
  static function art_nav( string $ide ) : string {
    $_ = "";
    $_ide = explode('.',$ide);
    $_nav = api::dat('app_nav',[ 
      'ver'=>"`esq`='{$_ide[0]}' AND `cab`='{$_ide[1]}' AND `ide`='{$_ide[2]}'", 
      'nav'=>'pos' 
    ]);
    if( isset($_nav[1]) ){

      foreach( $_nav[1] as $nv1 => $_nv1 ){ $_ .= "
        
        <h2 id='_{$nv1}-'>".app::let($_nv1->nom)."</h2>
        <section>";
          if( isset($_nav[2][$nv1]) ){
            foreach( $_nav[2][$nv1] as $nv2 => $_nv2 ){$_ .= "

          <h3 id='_{$nv1}-{$nv2}-'>".app::let($_nv2->nom)."</h3>
          <section>";
            if( isset($_nav[3][$nv1][$nv2]) ){
              foreach( $_nav[3][$nv1][$nv2] as $nv3 => $_nv3 ){$_ .= "

            <h4 id='_{$nv1}-{$nv2}-{$nv3}-'>".app::let($_nv3->nom)."</h4>
            <section>";
              if( isset($_nav[4][$nv1][$nv2][$nv3]) ){
                foreach( $_nav[4][$nv1][$nv2][$nv3] as $nv4 => $_nv4 ){ $_ .= "

              <h5 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-'>".app::let($_nv4->nom)."</h5>
              <section>";
                if( isset($_nav[5][$nv1][$nv2][$nv3][$nv4]) ){
                  foreach( $_nav[5][$nv1][$nv2][$nv3][$nv4] as $nv5 => $_nv5 ){ $_ .= "

                <h6 id='_{$nv1}-{$nv2}-{$nv3}-{$nv4}-{$nv5}-'>".app::let($_nv5->nom)."</h6>
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
  // Navegador : nav + * > ...[nav="ide"]
  static function nav( string $tip, array $dat, array $ele = [], ...$opc ) : string {
    foreach( ['lis','val','sec','ite'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }
    $_ = "";
    $_eje = self::$EJE."nav";
    $opc_ico = in_array('ico',$opc);
    $val_sel = isset($ele['sel']) ? $ele['sel'] : FALSE;
    // navegador 
    api_ele::cla($ele['lis'], $tip, 'ini');
    $_ .= "
    <nav".api_ele::atr($ele['lis']).">";    
    foreach( $dat as $ide => $val ){

      if( is_object($val) ) $val = api_obj::nom($val);

      if( isset($val['ide']) ) $ide = $val['ide'];

      $ele_nav = isset($val['nav']) ? $val['nav'] : [];

      $ele_nav['eti'] = 'a';
      api_ele::eje($ele_nav,'cli',"{$_eje}(this,'$ide'".( !empty($opc) ? ", '".implode("', '",$opc)."'" : '' ).");",'ini');

      if( $val_sel && $val_sel == $ide ) api_ele::cla($ele_nav,FON_SEL);

      if( $opc_ico && isset($val['ico']) ){
        $ele_nav['title'] = $val['nom'];
        api_ele::cla($ele_nav,"mar-0 pad-1 cir-1 tam-4",'ini');
        $_ .= app::ico($val['ico'],$ele_nav);
      }
      else{
        $ele_nav['htm'] = $val['nom'];
        $_ .= api_ele::val($ele_nav);
      }        
    }$_.="
    </nav>";
    // contenido
    $eti_sec = isset($ele['sec']['eti']) ? $ele['sec']['eti'] : 'div';
    $eti_ite = isset($ele['ite']['eti']) ? $ele['ite']['eti'] : 'section';
    if( $tip != 'pes' && !$val_sel ) api_ele::cla($ele['sec'],DIS_OCU);
    $_ .= "
    <$eti_sec".api_ele::atr($ele['sec']).">";
      foreach( $dat as $ide => $val ){
        $ele_ite = $ele['ite'];
        api_ele::cla($ele_ite,"ide-$ide",'ini');
        if( !$val_sel || $val_sel != $ide ) api_ele::cla($ele_ite,DIS_OCU);
        $_ .= "
        <$eti_ite".api_ele::atr($ele_ite).">
          ".( isset($val['htm']) ? ( is_array($val['htm']) ? api_ele::dec($val['htm']) : $val['htm'] ) : '' )."
        </$eti_ite>";
      }$_.="
    </$eti_sec>";

    return $_;
  }
  // Carteles : advertencia + confirmacion
  static function tex( string $tip, string | array $val, array $ope = [] ) : string {
    foreach( ['sec','ico','tex'] as $i ){ if( !isset($ope[$i]) ) $ope[$i] = []; }
    api_ele::cla($ope['sec'],"val_tex".( !empty($tip) ? " -$tip" : "" ),'ini');

    $_ = "
    <div".api_ele::atr($ope['sec']).">";

      if( !empty($ope['cab']) ){
        $_ .= "
        <div class='ite esp-ara'>
          <span></span>
          ".app::let($ope['cab'])."
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
        $_ .= app::ico("val_tex-{$tip}", $ope['ico']);
      }

      $_ .= ( is_string($val) ? "<p".api_ele::atr($ope['tex']).">".app::let($val)."</p>" : api_ele::dec($val) )."

    </div>";
    return $_;
  }
  // Menú de opciones
  static function opc(){
  }

  // Variable : div.atr > label + (input,textarea,select,button)[name]
  static function var( string $tip, string | array $ide, array $ele=[], ...$opc ) : string {
    $_ope = [
      'ico'=>"", 'nom'=>"", 'des'=>"", 
      'ite'=>[], 'eti'=>[], 'ope'=>[], 
      'htm'=>"", 'htm_pre'=>"", 'htm_med'=>"", 'htm_pos'=>"" 
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

      if( !empty($_atr = api_dat::atr($esq,$est,$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != 'val' ){ 

      $_var = app::var_dat($tip,$esq,$est,$atr);
    }

    // combino operadores
    if( !empty($_var) ){

      if( !empty($_var['ope']) ){
        $ele['ope'] = api_ele::jun($_var['ope'],isset($ele['ope']) ? $ele['ope'] : []);
        unset($_var['ope']);
      }
      $ele = api_obj::jun($ele,$_var);
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
    $agr = api_ele::htm($ele);

    // etiqueta
    if( !isset($ele['eti']) ) $ele['eti'] = [];
    $eti_htm='';
    if( !in_array('eti',$opc) ){
      if( !empty($ele['ico']) ){
        $eti_htm = app::ico($ele['ico']);
      }
      elseif( !empty($ele['nom']) ){    
        $eti_htm = app::let( ( !in_array('not_sep',$opc) && preg_match("/[a-zA-Z\d]$/",$ele['nom']) ) ? "{$ele['nom']}:" : $ele['nom']);
      }
      if( !empty($eti_htm) ){    
        if( isset($ele['ope']['id']) ) $ele['eti']['for'] = $ele['ope']['id'];     
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
      $val_htm = api_ele::val($ele['ope']);
    }
    // contenedor
    if( !isset($ele['ite']) ) $ele['ite']=[];      
    if( !isset($ele['ite']['title']) ){
      $ele['ite']['title'] = isset($ele['tit']) ? $ele['tit'] : '';
    }    
    return "
    <div".api_ele::atr(api_ele::cla($ele['ite'],"atr",'ini')).">
      ".( !empty($agr['htm_ini']) ? $agr['htm_ini'] : '' )."
      {$eti_ini}
      {$val_htm}
      {$eti_fin}
      ".( !empty($agr['htm_fin']) ? $agr['htm_fin'] : '' )."      
    </div>
    ";   
  }// armo controlador : nombre => valor
  static function var_dat( string $esq, string $dat='', string $val='', string $ide='' ) : array {
    global $_api;
    $_ = [];
    
    if( empty($dat) ){
      if( !isset($_api->app_var[$esq]) ){
        $_api->app_var[$esq] = api::dat('app_var',[
          'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>['atr'], 'red'=>'atr'
        ]);
      }
    }elseif( empty($val) ){
      if( !isset($_api->app_var[$esq][$dat]) ){
        $_api->app_var[$esq][$dat] = api::dat('app_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>['atr'], 'red'=>'atr'
        ]);
      }
    }else{
      if( !isset($_api->app_var[$esq][$dat][$val]) ){
        $_api->app_var[$esq][$dat][$val] = api::dat('app_var',[
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
  }// selector de operaciones : select > ...option
  static function var_ope( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {
    global $_api;
    $_ = [];

    if( !isset($_api->app_var_ope[$dat[0]][$dat[1]]) ){

      $_dat = api::dat( $_api->dat_ope, [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      $_api->app_var_ope[$dat[0]][$dat[1]] = app_var::opc_val( $_dat, $ope, ...$opc);
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
      'eti'=>"p", 'class'=>"let-enf let-cur", 'htm'=> app::let($dat) 
    ];

    // contenedor : icono + ...elementos          
    api_ele::eje( $dat,'cli',"$_eje(this);",'ini');

    return "
    <div".api_ele::atr( api_ele::cla( $ele['val'],"val tog",'ini') ).">
    
      ".app::val_ico( isset($ele['ico']) ? $ele['ico'] : [] )."

      ".( isset($ele['htm_ini']) ? api_ele::val($ele['htm_ini']) : '' )."
      
      ".api_ele::val( $dat )."

      ".( isset($ele['htm_fin']) ? api_ele::val($ele['htm_fin']) : '' )."

    </div>";
  }// - icono de toggle
  static function val_ico( array $ele = [] ) : string {
    $_eje = self::$EJE."val";
    return app::ico('val_tog', api_ele::eje($ele,'cli',"$_eje(this);",'ini'));
  }// - expandir / contraer
  static function val_ope( array $ele = [], ...$opc ) : string {
    $_ide = self::$IDE."val";
    $_eje = self::$EJE."val";      

    if( !isset($ele['ope']) ) $ele['ope'] = [];
    api_ele::cla($ele['ope'],"ope",'ini');

    $_eje_val = isset($ele['eje']) ? $ele['eje'] : "$_eje(this,";
    return "
    <fieldset".api_ele::atr($ele['ope']).">
      ".app::ico('val_tog-tod', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Expandir todos...", 'onclick'=>$_eje_val."'tod');" ] )."
      ".app::ico('val_tog-nad', [ 'eti'=>"button", 'class'=>"tam-2", 'title'=>"Contraer todos...", 'onclick'=>$_eje_val."'nad');", 'style'=>"transform: rotate(180deg);" ] )."
    </fieldset>";
  }// - Filtros : operador + valor textual + ( totales )
  static function val_ver( string | array $dat = [], array $ele = [], ...$opc ) : string {
    $_ = "
    <fieldset class='ite'>";      
    // opciones de filtro por texto
    $_ .= app::var_ope(['ver','tex'],[
      'ite'=>[ 
        'dat'=>"()($)dat()" 
      ],
      'eti'=>[ 
        'name'=>"ope", 'title'=>"Seleccionar un operador de comparación...", 'val'=>'**', 
        'class'=>isset($dat['ele_ope']['class']) ? $dat['ele_ope']['class'] : "mar_hor-1", 'onchange'=>$dat['eje']
      ]
    ]);
    // ingreso de valor a filtrar
    $_ .= app_var::tex('ora', isset($dat['val']) ? $dat['val'] : '', [ 
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
}