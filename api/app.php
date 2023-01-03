<?php
// Página-app
class api_app {

  // inicializo aplicacion
  function __construct(){

    $this->_tip = api_app::dat('app_tip',['niv'=>["ide"],'ele'=>["ope"]]);

  }

  // Dato: objeto | consulta
  static function dat( mixed $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

    // objeto->propiedad 
    if( is_string($dat) && is_string($ope) ){
      $esq = $dat;
      $est = $ope;        
      // busco datos por $clase::_($identificador)
      $_ = isset($val) ? $val : new stdClass;

      if( ( !isset($val) || !api_obj::val_tip($val) ) && class_exists( $_cla = "api_$esq" ) && method_exists($_cla,'_') ){

        $_ = !isset($val) ? $_cla::_($est) : $_cla::_($est,$val);
      }
    }
    // estructuras
    else{
      $_ = $dat;
      // datos de la base 
      if( is_string($ide = $dat) ){

        // ejecuto consulta
        $_ = sis_sql::reg('ver', $ide, isset($ope) ? $ope : [] );

        if( isset($ope) ){
          // elimino marcas
          foreach( ['ver','jun','gru','ord','lim'] as $i ){ if( isset($ope[$i]) ) unset($ope[$i]); }
          // busco clave primaria
          if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){

            $ope['niv'] = sis_sql::ind($ide,'ver','pri');
          }
        }
      }// resultados y operaciones
      if( isset($ope) && ( is_array($dat) || !isset($_['err']) ) ) $_ = api_lis::val_est($_,$ope);
    }
    return $_;
  }

  // Valores: comparación
  static function val( mixed $dat, string $ide, mixed $val ) : bool {
    $_ = FALSE;
    switch( $ide ){
    case '===': $_ = ( $dat === $val );  break;
    case '!==': $_ = ( $dat !== $val );  break;
    case '=':   $_ = ( $dat ==  $val );  break;
    case '<>':  $_ = ( $dat !=  $val );  break;
    case '==':  $_ = ( $dat ==  $val );  break;
    case '!=':  $_ = ( $dat !=  $val );  break;          
    case '>':   $_ = ( $dat  >  $val );  break;
    case '>>':  $_ = ( $dat  >  $val );  break;
    case '<<':  $_ = ( $dat  <  $val );  break;
    case '<':   $_ = ( $dat  <  $val );  break;
    case '>=':  $_ = ( $dat >=  $val );  break;
    case '<=':  $_ = ( $dat <=  $val );  break;
    case '^^':  $_ =  preg_match("/^".$val."/",$dat); break;
    case '!^':  $_ = !preg_match("/^".$val."/",$dat); break;    
    case '$$':  $_ =  preg_match("/".$val."$/",$dat); break;
    case '!$':  $_ = !preg_match("/".$val."$/",$dat); break;
    case '**':  $_ =  preg_match("/".$val."/",$dat);  break;
    case '!*':  $_ = !preg_match("/".$val."/",$dat);  break;
    }
    return $_;
  }

  // Tipo : dato + valor
  public array $_tip;
  static function tip( mixed $val ) : bool | object {
        
    $ide = strtolower(gettype($val));    
    // vacios
    if( is_null($val) ){
      $ide = "null";
    }
    // logicos
    elseif( is_bool($val) ){
      $val = "bool";
    }
    // funciones
    elseif( is_callable($val) ){ 
      $ide = "function"; 
    }
    // listados
    elseif( is_array($val) && array_keys($val) !== range( 0, count( array_values($val) ) - 1 ) ){
      $ide = "asoc"; 
    }
    // numericos
    elseif( is_numeric($val) ){ 
      $ide="int";      
      if( is_nan($val) ){ 
        $ide = "nan";
      }// evaluar largos
      else{
        if( is_integer($val) || is_long($val) ){          
          $ide = "integer";
          if( $val >= -128 && $val <= 127 ){ 
            $ide = "tinyint";
          }elseif( $val >= -32768 && $val <= 32767 ){ 
            $ide = "smallint";
          }elseif( $val >= -8388608 && $val <= 8388607 ){ 
            $ide = "mediumint";
          }elseif( $val >= -2147483648 && $val <= 2147483647 ){ 
            $ide = "int";
          }elseif( $val >= -92233720368547 && $val <= 92233720368547 ){ 
            $ide = "bigint";
          }else{
            $ide = "long";
          }
        }else{
          $ide="decimal";
          if( is_double($val) ){ 
            $ide = "double";
          }
          elseif( is_float($val) ){ 
            $ide = "float";
          }
        }
      }
    }
    // textos
    elseif( is_string($val) ){
      $tam = strlen($val);
      $ide = "varchar";
      if( $tam <= 50 ){
        if( preg_match("/^(\d{4})(\/|-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])(\s)([0-1][0-9]|2[0-3])(:)([0-5][0-9])(:)([0-5][0-9])$/",$val) ){ 
          $ide = "datetime";
        }elseif( preg_match("/^\d{4}([\-\/\.])(0?[1-9]|1[1-2])\1(3[01]|[12][0-9]|0?[1-9])$/",$val) ){ 
          $ide = "date";              
        }elseif( preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/",$val) ){ 
          $ide = "time";                  
        }
      }
      elseif( $tam <= 255 && $tam >= 100 ){
        $ide = "tinytext";
      }
      elseif( $tam <= 65535 ){
        $ide = "text";
      }
      elseif( $tam <= 16777215 ){
        $ide = "mediumtext";
      }
      elseif( $tam <= 4294967295 ){
        $ide = "longtext";
      }
      else{ 
        $ide = "string";
      }
    }
    global $api_app;    
    return isset($api_app->_tip[$ide]) ? $api_app->_tip[$ide] : FALSE;
  }

  // Estructura : datos + operadores
  public array $_est;
  static function est( string $esq, string $ide, mixed $ope = NULL, mixed $dat = NULL ) : mixed {
    $_ = [];
    global $api_app;
    // cargo una estructura
    if( !isset($ope) ){

      if( !isset($api_app->_est[$esq][$ide]) ){        
        
        // Cargo Estructura
        $api_app->_est[$esq][$ide] = sis_sql::est('ver',$sql_est = "{$esq}_{$ide}",'uni');
        if( empty($api_app->_est[$esq][$ide]) ){
          $api_app->_est[$esq][$ide] = new stdClass;
        }// de la Base
        else{
          $sql_vis = "_{$sql_est}";
        }
        // ...Propiedades extendidas
        $_est = api_app::dat('app_est',[ 'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'ele'=>["ope"], 'opc'=>"uni" ]);
        // si existe la estructura
        if( isset($_est->ope) ){
          // Propiedades
          foreach( $_est->ope as $ope_ide => $ope ){
            $api_app->_est[$esq][$ide]->$ope_ide = $ope;
          }
        }
        // Estructura de la base
        if( isset($sql_vis) ){
          // Atributos/columnas: de una vista ( si existe ) o de la tabla
          $api_app->_est[$esq][$ide]->atr = sis_sql::atr( !empty( sis_sql::est('lis',"_{$sql_est}",'uni') )  ? "_{$sql_est}" : $sql_est );
          if( isset($_est->ope['atr']) ){
            // cargo variables del operador
            foreach( $_est->ope['atr'] as $atr_ide => $atr_var ){
              $api_app->_est[$esq][$ide]->atr[$atr_ide]->var = api_ele::val_jun(
                $api_app->_est[$esq][$ide]->atr[$atr_ide]->var, $atr_var
              );
            }
          }
          // Datos/registros: de una vista ( si existe ) o de la tabla
          $est_ope = isset($api_app->_est[$esq][$ide]->dat) ? $api_app->_est[$esq][$ide]->dat : [];
          $api_app->_est[$esq][$ide]->dat = api_app::dat( sis_sql::est('val',$sql_vis) == 'vis' ? $sql_vis : $sql_est, $est_ope );          
        }
      }
      // devuelvo estructura completa: esq + ide + nom + atr + dat + ...ope
      $_ = $api_app->_est[$esq][$ide];
    }
    // cargo operadores
    elseif( is_string($ope) ){
      // cargo propiedad
      $ope_atr = explode('.',$ope);
      $_ = api_obj::val_dat(api_app::est($esq,$ide),$ope_atr);
      // proceso datos
      if( !!$_ && isset($dat) ){
        switch( $ope_atr[0] ){
        // devuelvo atributo/s
        case 'atr':
          $atr_lis = $_;
          // devuelvo 1
          if( is_string($dat) ){
            $_ = new stdClass;
            if( isset($atr_lis[$dat]) ) $_ = $atr_lis[$dat];
          }// o muchos
          else{
            $_ = [];
            foreach( $dat as $atr ){
              if( isset($atr_lis[$atr]) ) $_[$atr] = $atr_lis[$atr];
            }
          }
          break;
        // devuelvo valores
        case 'val':
          $_ = api_obj::val( api_app::dat($esq,$ide,$dat), $_ );
          break;
        }        
      }      
    }
    return $_;
  }// identificadores
  static function est_ide( $dat, array $ope=[] ) : array {

    if( is_string($dat) ) $dat = explode('.',$dat);

    if( !isset($dat[1]) ){
      $dat[1] = $dat[0];
      $dat[0] = DAT_ESQ;
    }

    $ope['esq'] = !empty($dat[0]) ? $dat[0] : DAT_ESQ;

    $ope['est'] = $dat[1];
    
    $ope['atr'] = isset($dat[2]) ? $dat[2] : FALSE;

    return $ope;
  }// genero atributos desde listado o desde la base
  static function est_atr( string | array $dat, string $ope = "" ) : array {
    $_ = [];
    if( empty($ope) ){
      // de la base
      if( is_string($dat) ){
        $ide = api_app::est_ide($dat);
        $_ = api_app::est($ide['esq'],$ide['est'],'atr');
      }
      // listado variable por objeto
      else{
        foreach( $dat as $ite ){
          // del 1° objeto: cargo atributos
          foreach( $ite as $ide => $val ){ 
            $atr = new stdClass;
            $atr->ide = $ide;
            $atr->nom = $ide;
            $atr->var = api_app::tip($val);
            $_ [$ide] = $atr;
          }
          break;
        }        
      }
    }
    return $_;
  }// relaciones : esq.est_atr | api.dat_atr[ide].dat
  static function est_rel( string $esq, string $est, string $atr ) : string {
    $_ = '';
    // armo identificador por nombre de estructura + atributo
    if( $atr == 'ide' ){
      $_ = $est;
    }
    // parametrizado en : $sis_app.dat_atr
    elseif( ( $_atr = api_app::est($esq,$est,'atr',$atr) ) && !empty($_atr->var['dat']) ){        
      $_ide = explode('_',$_atr->var['dat']);
      array_shift($_ide);
      $_ = implode('_',$_ide);
    }
    // valido existencia de tabla relacional : "_api.esq_est_atr"
    elseif( !!sis_sql::est('val',"{$esq}_{$est}_{$atr}") ){ 
      $_ = "{$est}_{$atr}";
    }
    else{
      $_ = $atr;
    }
    return $_;
  }

  // Variable
  public array $_var;      
  static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
    
    $_ = [];
    global $api_app;
    
    // cargo todas las estructuras del esquema
    if( empty($dat) ){
      if( !isset($api_app->_var[$esq]) ){
        $api_app->_var[$esq] = api_app::dat('app_var',[
          'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo por agrupacion
    elseif( empty($val) ){
      if( !isset($api_app->_var[$esq][$dat]) ){
        $api_app->_var[$esq][$dat] = api_app::dat('app_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo uno
    else{
      if( !isset($api_app->_var[$esq][$dat][$val]) ){
        $api_app->_var[$esq][$dat][$val] = api_app::dat('app_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 'niv'=>['ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }

    if( !empty($ide) ){
      $_ = isset($api_app->_var[$esq][$dat][$val][$ide]) ? $api_app->_var[$esq][$dat][$val][$ide] : [];
    }
    elseif( !empty($val) ){
      $_ = isset($api_app->_var[$esq][$dat][$val]) ? $api_app->_var[$esq][$dat][$val] : [];
    }
    elseif( !empty($dat) ){
      $_ = isset($api_app->_var[$esq][$dat]) ? $api_app->_var[$esq][$dat] : [];
    }
    else{
      $_ = isset($api_app->_var[$esq]) ? $api_app->_var[$esq] : [];
    }

    return $_;
  }// selector de operaciones : select > ...option  
  public array $_var_ope = [];
  static function var_ope( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {
    global $api_app;

    if( !isset($api_app->_var_ope[$dat[0]][$dat[1]]) ){

      $_dat = api_app::dat( 'app_tip_ope', [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      $api_app->_var_ope[$dat[0]][$dat[1]] = api_opc::lis( $_dat, $ope, ...$opc);
    }

    return $api_app->_var_ope[$dat[0]][$dat[1]];

  }// id por posicion  
  public array $_var_ide = [];
  static function var_ide( string $ope ) : string {
    global $api_app;

    if( !isset($api_app->_var_ide[$ope]) ) $api_app->_var_ide[$ope] = 0;

    $api_app->_var_ide[$ope]++;

    return $api_app->_var_ide[$ope];
  }

  // Recursos
  public array $rec = [
    // archivos clase js/php
    'cla' => [
      'api'=>[
        'app', 'doc', 'dat', 'arc', 'eje', 'ele', 'est', 'obj', 'lis', 'opc', 'num', 'tex', 'fig', 'fec', 'hol', 'usu' 
      ]
    ],
    // Estructuras
    'est'=>[
      'api'=>[]
    ],
    // Registros
    'dat'=>[
      'api'=>[
        'fig'=>[ 'ico' ],
        'tex'=>[ 'let' ]
      ]
    ],    
    // ejecuciones por aplicacion
    'eje' => ""
    ,
    // elementos del documento
    'ele' => [
    ],    
    // datos de la aplicacion
    'app'=> [
    ],      
    // operadores: botones de la cabecera
    'ope' => [
      'ini'=>[
        'app_ini'=>[ 'ico'=>"app", 'url'=>SYS_NAV, 'nom'=>"Página de Inicio" ],        
        'app_cab'=>[ 'ico'=>"app_cab", 'tip'=>"pan", 'nom'=>"Menú Principal" ],
        'app_nav'=>[ 'ico'=>"app_nav", 'tip'=>"pan", 'nom'=>"Índice", 'htm'=>"" ]
      ],
      'fin'=>[
        'ses_ini'=>[ 'ico'=>"app_ini", 'tip'=>"win", 'nom'=>"Iniciar Sesión..."  ],
        'ses_ope'=>[ 'ico'=>"usu",     'tip'=>"win", 'nom'=>"Cuenta de Usuario..." ],
        'sis_adm'=>[ 'ico'=>"eje",     'tip'=>"win", 'nom'=>"Consola del Sistema" ],
        'app_dat'=>[ 'ico'=>"dat_des", 'tip'=>"win", 'nom'=>"Ayuda" ]
      ]
    ]
  ];// recursos: cargo contenido
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
  }

  // peticion
  public object $uri
  ;// Inicio peticion
  public function uri_ini( string $uri ){

    // armo peticion
    $uri = explode('/',$uri);
    $this->uri = new stdClass;
    $this->uri->esq = !empty($uri[0]) ? $uri[0] : "hol";
    $this->uri->cab = !empty($uri[1]) ? $uri[1] : FALSE;
    $this->uri->val = FALSE;
    if( $this->uri->art = !empty($uri[2]) ? $uri[2] : FALSE ){
      $_val = explode('#',$this->uri->art);
      if( isset($_val[1]) ){
        $this->uri->art = $_val[0];
        $this->uri->val = $_val[1];
      }
      elseif( !empty($uri[3]) ){
        $this->uri->val = $uri[3];
      }
    }

    // ajusto enlace de inicio
    $this->rec['ope']['ini']['app_ini']['url'] .= $this->uri->esq;

    // cargo menú principal
    $this->rec['ope']['ini']['app_cab']['htm'] = $this->cab();

    // cargo datos de la pagina por peticion : esquema - cabecera - articulo - valor
    $this->rec['ele']['body'] = [
      'data-doc'=>$this->uri->esq, 
      'data-cab'=>!!$this->uri->cab ? $this->uri->cab : NULL, 
      'data-art'=>!!$this->uri->art ? $this->uri->art : NULL 
    ];
    $this->rec['app']['esq'] = api_app::dat('app_esq',[ 'ver'=>"`ide`='{$this->uri->esq}'", 'opc'=>'uni' ]);

    if( !empty($this->uri->cab) ){
      
      // cargo datos del menu
      $this->rec['app']['cab'] = api_app::dat('app_cab',[ 'ver'=>"`esq`='{$this->uri->esq}' AND `ide`='{$this->uri->cab}'", 
        'ele'=>'ope', 'opc'=>'uni'
      ]);

      // cargo datos del artículo
      if( !empty($this->uri->art) ){
        $this->rec['app']['art'] = api_app::dat('app_art',[ 'ver'=>"`esq`='{$this->uri->esq}' AND `cab`='{$this->uri->cab}' AND `ide`='{$this->uri->art}'", 
          'ele'=>'ope', 'opc'=>'uni' 
        ]);

        // cargo índice de contenidos
        if( !empty($this->rec['app']['cab']->nav) ){

          $this->rec['app']['nav'] = api_app::dat('app_nav',[ 'ver'=>"`esq`='{$this->uri->esq}' AND `cab`='{$this->uri->cab}' AND `ide`='{$this->uri->art}'", 
            'ord'=>"pos ASC", 
            'nav'=>'pos'
          ]);

          // pido listado por navegacion
          if( !empty($this->rec['app']['nav'][1]) ) $this->rec['ope']['ini']['app_nav']['htm'] = api_lis::nav($this->rec['app']['nav']);
        }          
      }
    }
  }// armo directorios
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

  // contenido
  public array $htm = [
    // titulo
    'tit'=>"{-_-}",
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
  ];// inicializo pagina
  public function htm_ini() : void {

    global $sis_usu;

    /* 
    // loggin
    $tip = "ses_".( empty($sis_usu->ide) ? 'ini' : 'ope' );
    $this->rec['ope']['fin'][$tip]['htm'] = api_usu::$tip();

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
          $this->htm['ope'][$tip] .= api_fig::ico($ope['ico'],[ 'eti'=>"a", 'title'=>$ope['nom'], 'href'=>$ope['url'] ]);
        }
        // paneles y modales
        elseif( isset($ope['tip']) && !empty($ope['htm']) ){
          // boton
          $this->htm['ope'][$tip] .= api_doc::bot([ $ide => $ope ]);
          // contenido
          $this->htm[$ope['tip']] .= api_doc::{$ope['tip']}($ide,$ope);          
        }
      }  
    }

    // modal de operadores
    $this->htm['win'] .= api_doc::win('doc_ope',[ 'ico'=>"app_ope", 'nom'=>"Operador" ]);  
    
    // ajusto diseño
    $_ver = [];
    foreach( ['bar','pie'] as $ide ){ 
      if( !empty($this->htm[$ide]) ) $_ver []= $ide; 
    }
    if( !empty($_ver) ) $this->rec['ele']['body']['data-ver'] = implode(',',$_ver);

    // titulo
    if( !empty($this->rec['app']['art']->nom) ){
      $this->htm['tit'] = $this->rec['app']['art']->nom;
    }
    elseif( !empty($this->rec['app']['cab']->nom) ){
      $this->htm['tit'] = $this->rec['app']['cab']->nom;
    }
    elseif( !empty($this->rec['app']['esq']->nom) ){
      $this->htm['tit'] = $this->rec['app']['esq']->nom; 
    }
  }

  // Menu principal : titulo + descripcion + listado > item = [icono] + enlace
  public function cab( array $ele = [] ) : string {
    
    global $sis_usu;
    
    $esq = $this->uri->esq;
    
    foreach( ['ope','lis','dep'] as $i ){ if( !isset($ele[$i]) ) $ele[$i] = []; }

    // armo listado de enlaces
    $_lis = [];
    foreach( api_app::dat('app_cab',[ 'ver'=>"`esq`='$esq'", 'ord'=>"`pos` ASC" ]) as $_cab ){

      if( !empty($_cab->ocu) || ( !empty($_cab->usu) && empty($sis_usu->ide) ) ){
        continue;
      }

      $ite_ico = !empty($_cab->ico) ? api_fig::ico( $_cab->ico, [ 'class'=>"mar_der-1" ] ) : "";

      $_lis_val = [];
      foreach( api_app::dat('app_art',[ 
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
  // Articulo por contenido + ...secciones + pie de página
  public function art( object $nav, string $esq, string $cab ) : string {
    $_ = "";      

    $agr = api_ele::htm($nav->ope);

    $_art = api_app::dat('app_art',[ 'ver'=>"`esq`='{$esq}' AND `cab`='{$cab}'", 'ord'=>"`pos` ASC", 'ele'=>"ope" ]);

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
  }
  // Section por indices : section > h2 + ...section > h3 + ...section > ...
  public function nav( string $ide ) : string {
    $_ = "";
    $_ide = explode('.',$ide);
    $_nav = api_app::dat('app_nav',[ 
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
}