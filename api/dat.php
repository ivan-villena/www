<?php
// Dato : (api).esq.est[ide].atr
class api_dat {

  static string $IDE = "api_dat-";
  static string $EJE = "api_dat.";

  static array $OPE = [
    'acu'=>['nom'=>"Acumulados" ], 
    'ver'=>['nom'=>"Selección"  ], 
    'sum'=>['nom'=>"Sumatorias" ], 
    'cue'=>['nom'=>"Conteos"    ]
  ];
  static array $VAR = [
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
  static array $ABM = [
    'ver'=>['nom'=>"Ver"        ], 
    'agr'=>['nom'=>"Agregar"    ], 
    'mod'=>['nom'=>"Modificar"  ], 
    'eli'=>['nom'=>"Eliminar"   ]
  ];

  function __construct(){
    $this->_ope = api_dat::get('dat_ope', [ 'niv'=>['ide'] ]);
    $this->_tip = api_dat::get('dat_tip', [ 'niv'=>['ide'], 'ele'=>['ope'] ]);
    $this->_est = [];
    $this->_atr = [];
    $this->_var = [];
    $this->_var_ide = [];
    $this->_var_ope = [];
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_dat;
    $est = "_$ide";
    if( !isset($api_dat->$est) ) $api_dat->$est = api_dat::est_ini(DAT_ESQ,"dat{$est}");
    $_dat = $api_dat->$est;
    
    if( !empty($val) ){
      $_ = $val;
      if( !is_object($val) ){
        switch( $ide ){
        default:
          if( is_numeric($val) ) $val = intval($val) - 1;
          if( isset($_dat[$val]) ) $_ = $_dat[$val];
          break;
        }
      }
    }// toda la lista
    elseif( isset($_dat) ){
      $_ = $_dat;
    }
    return $_;
  }

  // objeto | consulta
  static function get( mixed $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

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
  }// tipo : dato + valor
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
    $tip_lis = api_dat::_('tip');
    return isset($tip_lis[$ide]) ? $tip_lis[$ide] : FALSE;
  }// comparaciones de valores
  static function ver( mixed $dat, string $ide, mixed $val ) : bool {
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
  }// identificadores
  static function ide( $dat, array $ope=[] ) : array {

    if( is_string($dat) ) $dat = explode('.',$dat);

    if( !isset($dat[1]) ){
      $dat[1] = $dat[0];
      $dat[0] = DAT_ESQ;
    }

    $ope['esq'] = !empty($dat[0]) ? $dat[0] : DAT_ESQ;

    $ope['est'] = $dat[1];
    
    $ope['atr'] = isset($dat[2]) ? $dat[2] : FALSE;

    return $ope;
  }

  // Variable : div.dat_var > label + (select,input,textarea,button)[name]  
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

      if( !empty($_atr = api_dat::atr($esq,$est,$atr)) ) $_var = [ 
        'nom'=>$_atr->nom, 
        'ope'=>$_atr->var 
      ];
    }
    // carga operadores: esquema - dato - valor
    elseif( $tip != 'val' ){ 

      $_var = api_dat::var_dat($tip,$esq,$est,$atr);
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
  }// armo controlador : nombre => valor
  static function var_dat( string $esq, string $dat='', string $val='', string $ide='' ) : array {    
    $_ = [];

    global $api_dat;
    // cargo todas las estructuras del esquema
    if( empty($dat) ){
      if( !isset($api_dat->_var[$esq]) ){
        $api_dat->_var[$esq] = api_dat::get('dat_var',[
          'ver'=>"`esq`='{$esq}'", 
          'niv'=>['dat','val','ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }elseif( empty($val) ){
      if( !isset($api_dat->_var[$esq][$dat]) ){
        $api_dat->_var[$esq][$dat] = api_dat::get('dat_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 
          'niv'=>['val','ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }else{
      if( !isset($api_dat->_var[$esq][$dat][$val]) ){
        $api_dat->_var[$esq][$dat][$val] = api_dat::get('dat_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 
          'niv'=>['ide'], 
          'ele'=>["atr"], 
          'red'=>"atr"
        ]);
      }
    }
    if( !empty($ide) ){
      $_ = isset($api_dat->_var[$esq][$dat][$val][$ide]) ? $api_dat->_var[$esq][$dat][$val][$ide] : [];
    }elseif( !empty($val) ){
      $_ = isset($api_dat->_var[$esq][$dat][$val]) ? $api_dat->_var[$esq][$dat][$val] : [];
    }elseif( !empty($dat) ){
      $_ = isset($api_dat->_var[$esq][$dat]) ? $api_dat->_var[$esq][$dat] : [];
    }else{
      $_ = isset($api_dat->_var[$esq]) ? $api_dat->_var[$esq] : [];
    }

    return $_;
  }// selector de operaciones : select > ...option
  static function var_ope( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {
    global $api_dat;

    if( !isset($api_dat->_var_ope[$dat[0]][$dat[1]]) ){

      $_dat = api_dat::get( api_dat::_('ope'), [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      $api_dat->_var_ope[$dat[0]][$dat[1]] = api_opc::lis( $_dat, $ope, ...$opc);
    }

    return $api_dat->_var_ope[$dat[0]][$dat[1]];

  }// id por posicion
  static function var_ide( string $ope ) : string {
    global $api_dat;

    if( !isset($api_dat->_var_ide[$ope]) ) $api_dat->_var_ide[$ope] = 0;

    $api_dat->_var_ide[$ope]++;

    return $api_dat->_var_ide[$ope];
  }    

  // estructura : datos + operadores
  static function est( string $esq, string $ide, mixed $tip = NULL, mixed $dat = NULL ) : mixed {
    $_ = [];
    global $api_dat;
    // cargo una estructura
    if( !isset($tip) ){

      if( !isset($api_dat->_est[$esq][$ide]) ){

        $api_dat->_est[$esq][$ide] = sis_sql::est('ver',"{$esq}_{$ide}",'uni');
      }
      $_ = $api_dat->_est[$esq][$ide];
    }
    else{
      switch( $tip ){
      }
    }
    return $_;
  }// inicio estructura: busco datos por vista o tabla
  static function est_ini( string $esq, string $est, array $ope = [] ) : string | array {
    $_ = [];

    $val_est = sis_sql::est('val',$est);

    $vis = "_{$est}";

    $val_vis = sis_sql::est('val',$vis);

    if( $val_est || $val_vis ){

      $ide = ( $val_vis == 'vis' ) ? $vis : $est;

      $_ = api_dat::get("{$esq}.{$ide}",$ope);
    }

    return $_;
  }// cargo operador: valores + relaciones + atributos + informe + listado + opciones
  static function est_ope( string $esq, string $est, string $ope, mixed $dat = NULL ) : mixed {
    global $api_dat;
    if( !isset($api_dat->_est_ope[$esq][$est]) ){
      
      $api_dat->_est_ope[$esq][$est] = api_dat::get('dat_est',[
        'ver'=>"`esq`='{$esq}' AND `ide`='{$est}'", 
        'ele'=>["ope"],
        'red'=>"ope",
        'opc'=>"uni"
      ]);
    }
    $_ = $api_dat->_est_ope[$esq][$est];

    // cargo atributo
    foreach( ( $ope_atr = explode('.',$ope) ) as $ide ){

      $_ = ( is_array($_) && isset($_[$ide]) ) ? $_[$ide] : FALSE;
    }

    // proceso valores con datos
    if( $ope_atr[0] == 'val' && isset($dat) ) $_ = api_obj::val( api_dat::get($esq,$est,$dat), $_ );

    return $_;
  }// relaciones : esq.est_atr | api.dat_atr[ide].dat
  static function est_rel( string $esq, string $est, string $atr ) : string {
    $_ = '';
    // armo identificador por nombre de estructura + atributo
    if( $atr == 'ide' ){
      $_ = $est;
    }
    // parametrizado en : $sis_app.dat_atr
    elseif( ( $_atr = api_dat::atr($esq,$est,$atr) ) && !empty($_atr->var['dat']) ){        
      $_ = explode('.',$_atr->var['dat'])[1];
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

  // atributo : datos + tipo + variable
  static function atr( string $esq, string $est, mixed $ide = NULL ) : mixed {
    $_ = [];
    global $api_dat;
    // cargo atributos de la estructura
    if( !isset($api_dat->_atr[$esq][$est]) ){
      
      // busco atributos de una vista ( si existe ) o de una tabla
      $sql_ide = "{$esq}_{$est}";
      $api_dat->_atr[$esq][$est] = sis_sql::atr( !empty( sis_sql::est('lis',"_{$sql_ide}",'uni') )  ? "_{$sql_ide}" : $sql_ide );

      // cargo operadores del atributo
      $dat = &$api_dat->_atr[$esq][$est];

      if( $dat_atr = api_dat::est_ope($esq,$est,'atr') ){

        foreach( $dat_atr as $i => $v ){
        
          if( isset($dat[$i]) ) $dat[$i]->var = api_ele::val_jun($dat[$i]->var, api_obj::val_nom($v));
        }
      }
    }// devuelvo todos los atributos
    $_ = $api_dat->_atr[$esq][$est];
    // buscar por identificador/es
    if( isset($ide) ){
      $atr_lis = $_;
      // devuelvo 1
      if( is_string($ide) ){
        $_ = new stdClass;
        if( isset($atr_lis[$ide]) ) $_ = $atr_lis[$ide];
      }// muchos
      else{
        $_ = [];
        foreach( $ide as $atr ){

          if( isset($atr_lis[$atr]) ) $_[$atr] = $atr_lis[$atr];
        }
      }
    }
    return $_;
  }// genero atributos desde listado o desde la base
  static function atr_ver( string | array $dat, string $ope = "" ) : array {
    $_ = [];
    if( empty($ope) ){
      // de la base
      if( is_string($dat) ){
        $ide = api_dat::ide($dat);
        $_ = api_dat::atr($ide['esq'],$ide['est']);
      }
      // listado variable por objeto
      else{
        foreach( $dat as $ite ){
          // del 1° objeto: cargo atributos
          foreach( $ite as $ide => $val ){ 
            $atr = new stdClass;
            $atr->ide = $ide;
            $atr->nom = $ide;
            $atr->var = api_dat::tip($val);
            $_ [$ide] = $atr;
          }
          break;
        }        
      }
    }
    return $_;
  }

  // armo valores ( esq.est ): nombre, descripcion, imagen
  static function val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( api_dat::ide($ide) );
    // cargo datos/registros
    $_dat = api_dat::get($esq,$est,$dat);
    // cargo valores
    $_val = api_dat::est_ope($esq,$est,'val');

    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      
      $_ = ( isset($_val['nom']) ? api_obj::val($_dat,$_val['nom']) : "" ).( isset($_val['des']) ? "\n".api_obj::val($_dat,$_val['des']) : "");
    }
    // por atributos con texto : nom + des + ima 
    elseif( isset($_val[$tip]) ){

      if( is_string($_val[$tip]) ) $_ = api_obj::val($_dat,$_val[$tip]);
    }

    // ficha
    if( $tip == 'ima' ){
      // identificador      
      $ele['data-esq'] = $esq;
      $ele['data-est'] = $est;
      $ele['data-ide'] = $_dat->ide;
      
      // cargo titulos
      if( !isset($ele['title']) ){
        $ele['title'] = api_dat::val('tit',"$esq.$est",$_dat);
      }
      elseif( $ele['title'] === FALSE  ){
        unset($ele['title']);
      }
      
      // acceso a informe
      if( !isset($ele['onclick']) ){
        if( api_dat::est_ope($esq,$est,'inf') ) api_ele::eje($ele,'cli',"api_dat.inf('$esq','$est',".intval($_dat->ide).")");
      }
      elseif( $ele['onclick'] === FALSE ){
        unset($ele['onclick']);
      }
      
      $_ = api_fig::ima( [ 'style' => $_ ], $ele );
    }
    // variable
    elseif( $tip == 'var' ){
      
      $_ = "";

    }
    // textos
    elseif( !!$ele ){  

      if( empty($ele['eti']) ) $ele['eti'] = 'p';
      $ele['htm'] = api_tex::let($_);
      $_ = api_ele::val($ele);
    }    

    return $_;
  }// busco valor por seleccion ( esq.est.atr ) : variable, html, ficha, color, texto, numero, fecha
  static function val_ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( api_dat::ide($ide) );
    // parametros: "esq.est.atr" 
    $ide = 'NaN';
    if( !is_object($dat) ){

      $ide = $dat;
      $dat = api_dat::get($esq,$est,$dat);
    }
    elseif( isset($dat->ide) ){

      $ide = $dat->ide;
    }

    if( is_object($dat) && isset($dat->$atr) ){
      
      $_atr = api_dat::atr($esq,$est,$atr);
      // variable por tipo
      if( $tip == 'var' ){
        $_var = $_atr->var;
        $_var['val'] = $dat->$atr;
        $_ = api_ele::val($_val);
      }// proceso texto con letras
      elseif( $tip == 'htm' ){

        $_ = api_tex::let($dat->$atr);
      }// color en atributo
      elseif( $tip == 'col' ){
        
        if( $col = api_dat::val_ide('col',$esq,$est,$atr) ){ $_ = "
          <div".api_ele::atr(api_ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini')).">
          </div>";
        }else{
          $_ = "<div class='err' title='No existe el color para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
        }
      }// imagen en atributo
      elseif( $tip == 'ima' ){

        if( !empty($_atr->var['dat']) ){
          $_ima_ide = explode('.',$_atr->var['dat']);
          $_ima['esq'] = $_ima_ide[0];
          $_ima['est'] = $_ima_ide[1];
        }
        if( !empty($_ima) || !empty( $_ima = api_dat::val_ide('ima',$esq,$est,$atr) ) ){
          
          $_ = api_fig::ima($_ima['esq'],$_ima['est'],$dat->$atr,$ele);
        }
        else{
          $_ = "<div class='err' title='No existe la imagen para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
        }
      }// por tipos de dato
      elseif( $tip == 'tip' || in_array($tip,['num','tex','fec']) ){

        if( $tip=='tip' ){
          $tip = $_atr->var_dat;
        }
        if( $tip == 'num' ){
          $_ = "<n".api_ele::atr($ele).">{$dat->$atr}</n>";
        }
        elseif( $tip == 'tex' ){
          $_ = "<p".api_ele::atr($ele).">".api_tex::let($dat->$atr)."</p>";
        }
        elseif( $tip == 'fec' ){
          $ele['value'] = $dat->$atr;
          $_ = "<time".api_ele::atr($ele).">".api_tex::let($dat->$atr)."</time>";
        }
        else{
          $_ = api_tex::let($dat->$atr);
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
        $_ = "<div class='err' title='No existe el atributo {$atr} para el objeto _{$esq}.{$est}[{$ide}]'>{-_-}</div>";
      }      
    }      

    return $_;
  }// busco identificadores por seleccion : imagen, color...
  static function val_ide( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
    // dato
    $_ = [ 'esq' => $esq, 'est' => $est ];
    if( !empty($atr) ){
      // armo identificador
      $_['est'] = $atr == 'ide' ? $est : "{$est}_{$atr}";  
      // busco dato en atributos
      $_atr = api_dat::atr($esq,$est,$atr);
      if( isset($_atr->var['dat']) && !empty($var_dat = $_atr->var['dat']) ){
        $dat = explode('.',$var_dat);
        $_['esq'] = $dat[0];
        $_['est'] = $dat[1];
      }
    }
    // valido dato
    if( !empty( $dat_Val = api_dat::est_ope($_['esq'],$_['est'],"val.$tip",$dat) ) ){
      $_['ide'] = "{$_['esq']}.{$_['est']}";
      $_['val'] = $dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;
  }// armo selector : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
  static function val_opc( string $ide, mixed $dat, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."val_opc(";
    $_eje = self::$EJE."val_opc(";

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
    $ele_val = [ 'eti'=>[ 'name'=>"val", 
      'class'=>"mar_ver-1", 'title'=>"Seleccionar el Regisrto por Estructura",
      'style'=>$opc_ope_tam, 'onchange'=>$_eje."'val',this);" 
    ] ];
    if( $opc_esq || $opc_est ){
      // operador por esquemas
      if( $opc_esq ){
        $dat_esq = [];
        $ele_esq = [ 'eti'=>[ 'name'=>"esq", 
          'class'=>"mar_ver-1", 'title'=>"Seleccionar el Esquema de Datos...",
          'style'=>$opc_ope_tam, 'onchange'=>$_eje.",'esq');" 
        ] ];
      }
      // operador por estructuras
      $ele_est = [ 'eti'=>[ 'name'=>"est", 
        'class'=>"mar_ver-1", 'title'=>"Seleccionar la Estructura de Datos...",
        'style'=>$opc_ope_tam, 'onchange'=>$_eje."'est',this);" 
      ] ];
      
      // operador por relaciones de atributo
      $ope['ope'] = api_ele::eje($ope['ope'],'cam',$_eje."'atr',this);",'ini');
      if( !empty($opc_ope_tam) ) $ope['ope'] = api_ele::css($ope['ope'],$opc_ope_tam);
      // oculto items
      $cla = DIS_OCU;
      // copio eventos
      if( $ele_eje ) $ele_est['eti'] = api_ele::eje($ele_est['eti'],'cam',$ele_eje);
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
    if( is_string($dat) || api_lis::val($dat) ){
      $_ide = is_string($dat) ? explode('.',$dat) : $dat;
      $dat = [ $_ide[0] => [ $_ide[1] ] ];
    }
    // opciones por operador de estructura
    $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
      $_ = [];
      // atributos parametrizados
      if( ( $dat_opc_ide = api_dat::est_ope($esq,$est,"opc.$ide") ) && is_array($dat_opc_ide) ){
        // recorro atributos + si tiene el operador, agrego la opcion      
        foreach( $dat_opc_ide as $atr ){
          // cargo atributo
          $_atr = api_dat::atr($esq,$est,$atr);
          if( empty($_atr) ){
            var_dump(api_dat::atr($esq,$est));
            var_dump($esq,$est,$atr);
          }
          $atr_nom = $_atr->nom;
          if( $_atr->ide == 'ide' && empty($_atr->nom) && !empty($_atr_nom = api_dat::atr($esq,$est,'nom')) ){
            $atr_nom = $_atr_nom->nom;
          }
          // armo identificador
          $dat = "{$esq}.".api_dat::est_rel($esq,$est,$atr);
          $_ []= [
            'data-esq'=>$esq, 'data-est'=>$est, 'data-ide'=>$dat,
            'value'=>"{$esq}.{$est}.{$atr}", 'class'=>$cla, 
            'htm'=>$atr_nom
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
        
        if( $dat_opc_est = api_dat::est_ope($esq_ide,$est_ide,'rel') ){

          // recorro dependencias de la estructura
          foreach( $dat_opc_est as $dep_ide ){
            // redundancia de esquemas
            $dep_ide = str_replace("{$esq_ide}_",'',$dep_ide);
            // datos de la estructura relacional
            $_est = api_dat::est($esq_ide,$dep_ide);
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
      $_ .= api_opc::lis($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
    }
    // selector de estructura [opcional]
    if( $opc_esq || $opc_est ){
      $_ .= api_opc::lis($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
    }
    // selector de atributo con nombre de variable por operador
    $_ .= api_opc::lis($dat_ope,$ele_ope,'nad');
    
    // selector de valor por relacion
    if( $opc_val ){
      // copio eventos
      if( $ele_eje ) $ele_val['eti'] = api_ele::eje($ele_val['eti'],'cam',$ele_eje);
      $_ .= "
      <c class='sep'>:</c>
      <div class='doc_val'>
        ".api_opc::lis( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
        <span class='ico'></span>
      </div>";
    }
    return $_;
  }

  // Listado
  static function lis( string | array $dat, string $ide, array $ele = [] ) : string {
    $_ = $dat;
    if( is_array($dat) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      $ele['lis']['data-dat'] = $ide;
      // tipos: pos + ite + tab
      $tip = "ite";
      if( isset($ele['lis_tip']) ){
        $tip = $ele['lis_tip'];
        unset($ele['lis_tip']);
      }
      $_ = api_lis::$tip($dat, $ele);
    }
    return $_;
  }// glosarios : definiciones por esquema
  static function lis_ide( string | array $ide, array $ele = [] ) : string {

    $_ = [];
    $_ide = explode('.',$ide);
    
    if( is_array( $tex = api_dat::get('app_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

      foreach( $tex as $pal ){
        $_[ $pal->nom ] = $pal->des;
      }
    }
    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return api_lis::pos($_,$ele);
  }// Atributos : listado por => atributo: "valor"
  static function lis_atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_opc_des = in_array('des',$_opc);
    // cargo dato
    if( !is_object($dat) ) $dat = api_dat::get($esq,$est,$dat);
    // cargo atributos
    $dat_atr = api_dat::atr($esq,$est);      
    $ite = [];
    foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){       
      if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ 
        $_atr = $dat_atr[$atr];
        $val = $dat->$atr;
        if( is_numeric($val) && isset($_atr->var['dat']) ){
          // busco nombres /o/ iconos
          $atr_ide = explode('.',$_atr->var['dat']);
          $atr_dat = api_dat::est_ope( $atr_ide[0], $atr_ide[1], 'val');
          $atr_obj = [];
          if( class_exists($atr_cla = $atr_ide[0]) && method_exists($atr_cla,'_') ){
            $atr_obj = $atr_cla::_("{$atr_ide[1]}", $val);
          }
          if( isset($atr_dat['nom']) ){
            $val = api_tex::let( api_obj::val($atr_obj,$atr_dat['nom']) );
            if( isset($atr_dat['des']) && !$_opc_des ){
              $val .= "<br>".api_tex::let(api_obj::val($atr_obj,$atr_dat['des']));
            }
          }
          $val = str_replace($dat_atr[$atr]->nom,"<b class='ide'>{$dat_atr[$atr]->nom}</b>",$val);
        }
        else{
          $val = "<b class='ide'>{$dat_atr[$atr]->nom}</b><c>:</c> ".api_tex::let($val);
        }
        $ite []= $val;
      }
    }

    $_ = api_lis::pos($ite,$ope);          

    return $_;
  }// Descripciones : imagen + nombre > ...atributos
  static function lis_des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
    
    $_ = [];
    // tipos de lista
    $tip = !empty($ele['tip']) ? $ele['tip'] : 'ite';
    // atributos de la estructura
    $atr = api_lis::val_ite($atr);
    // descripciones : cadena con " ()($)atr() "
    $des = !empty($ele['des']) ? $ele['des'] : FALSE;
    // elemento de lista
    if( !isset($ele['lis']) ) $ele['lis'] = [];
    api_ele::cla($ele['lis'],"ite",'ini');
    $ele['lis']['data-ide'] = "$esq.$est";

    if( class_exists($_cla = "$esq") ){

      foreach( $_cla::_($est) as $pos => $_dat ){ 
        $htm = 
        api_fig::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
        <dl>
          <dt>
            ".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " ".api_obj::val($_dat,$des) : "" )."
          </dt>";
          foreach( $atr as $ide ){ 
            if( isset($_dat->$ide) ){ $htm .= "
              <dd>".( preg_match("/_des/",$ide) ? "<q>".api_tex::let($_dat->$ide)."</q>" : api_tex::let($_dat->$ide) )."</dd>";
            }
          }$htm .= "
        </dl>";
        $_ []= $htm;
      }
    }

    return api_lis::$tip( $_, $ele, ...$opc );
  }

  // Posiciones : listado de atributos con ficha + nombre ~ descripcion ~ posicion
  static function pos( string $esq, string $est, array $dat, array $ele = [] ) : string {
    $_ = [];
    foreach( api_dat::est_ope($esq,$est,'pos') as $ite ){
      $var = [ 'ite'=>$ite['nom'], 'lis'=>[] ];
      extract( api_dat::ide($ite['ide']) );
      $ope_atr = api_dat::atr($esq,$est);

      foreach( $ite['atr'] as $atr ){
        $val = isset($dat[$est]->$atr) ? $dat[$est]->$atr : NULL;
        
        $_atr = isset($ope_atr[$atr]->var) ? $ope_atr[$atr]->var : [];
        $_ide = explode('.', $ide = isset($_atr['dat']) ?  $_atr['dat'] : "{$esq}.{$est}_{$atr}" );                
        $_dat = api_hol::_($_ide[1],$val);        
        $_val = api_dat::est_ope($_ide[0],$_ide[1],'val');
        
        $htm = "";
        if( isset($_val['ima']) ) $htm .=
          api_hol::ima($_ide[1], $_dat, [ 'class'=>"tam-3 mar_der-1" ]);
        $htm .= "
        <div class='tam-cre'>";
          if( isset($_val['nom']) ) $htm .= "
            <p class='tit'>".api_tex::let( api_dat::val('nom',$ide,$_dat) )."</p>";
          if( isset($_val['des']) ) $htm .= "
            <p class='des'>".api_tex::let( api_dat::val('des',$ide,$_dat) )."</p>";
          if( isset($_val['num']) ) $htm .= 
            api_num::var('ran',$val,[ 'min'=>1, 'max'=>$_val['num'], 'disabled'=>"", 'class'=>"anc-100"],'ver');
          $htm .= "
        </div>";
        $var['lis'] []= $htm;
      }
      $_ []= $var;
    }
    $ele['lis-1'] = [ 'class'=>"ite" ];
    return api_lis::ite($_,$ele);
  }

  // Ficha : valor.ima => { ...imagen por atributos } 
  static function fic( string $ide, mixed $val = NULL, array $ope = [] ) : string {
    $_ = "";
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    // proceso estructura
    extract( api_dat::ide($ide) );
    if( !in_array('det',$opc) ){
      if( ( $_fic = api_dat::est_ope($esq,$est,'fic') ) && isset($_fic[0]) ){ $_ .= 

        "<div class='doc_val' data-esq='$esq' data-est='$est' data-atr='{$_fic[0]}' data-ima='$esq.$est'>".
        
          ( !empty($val) ? api_fig::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."
  
        </div>";
        // imágenes de atributos
        if( !empty($_fic[1]) ){ $_ .= "
          <c class='sep'>=></c> 
          ".api_dat::fic_ima($esq,$est,$_fic[1], $val);
        }
      }
    }// con titulo y detalle
    else{
      $_val = api_dat::est_ope($esq,$est,'val');
      $_dat = [];
      if( class_exists($cla = "api_$esq") && method_exists($cla,'_')){
        $_dat = $cla::_("{$est}",$val);
      }
      $_ .= "
      <div class='doc_val'>";
      if( isset($_val['ima']) ){
        $_ .= api_fig::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
      }
      if( isset($_val['nom']) || isset($_val['des']) ){ $_.="
        <div class='tex_ali-izq'>";
        if( isset($_val['nom']) ){
          $_ .= "<p class='tit'>".api_tex::let(api_obj::val($_dat,$_val['nom']))."</p>";
        }elseif( isset($_dat->nom) ){
          $_ .= "<p class='tit'>".api_tex::let($_dat->nom)."</p>";
        }
        if( isset($_val['des']) ){
          $_ .= "<p class='des'>".api_tex::let(api_obj::val($_dat,$_val['des']))."</p>";
        }elseif( isset($_dat->des) ){
          $_ .= "<p class='des'>".api_tex::let($_dat->des)."</p>";
        }$_ .= "
        </div>";
      }$_ .= "
      </div>";
    }
    return $_;
  }// Imagenes : listado por => { ... ; ... }
  static function fic_ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {
    // busco valores
    if( isset($val) ) $val = api_dat::get($esq,$est,$val);
    // busco atributos 
    if( empty($atr) ) $atr = api_dat::est_ope($esq,$est,'fic.ima');
    // Elementos
    if( !isset($ope['ima']) ) $ope['ima'] = [];
    if( empty($ope['ima']['class']) ) api_ele::cla($ope['ima'],"tam-4");
    $_ = "
    <ul class='lis val'>
      <li><c>{</c></li>";        
      foreach( $atr as $atr ){
        $_ima = api_dat::val_ide('ima',$esq,$est,$atr); $_ .= "
        <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
          ".( isset($val->$atr) ? api_fig::ima($esq,"{$est}_{$atr}",$val->$atr,$ope['ima']) : "" )."
        </li>";
      } $_ .= "
      <li><c>}</c></li>
    </ul>";
    return $_;
  }

  // Reporte : nombre + descripcion > imagen + atributos | lectura > detalle > tablero > ...
  static function inf( string $esq, string $est, mixed $dat = NULL, array $ope = NULL ) : string {
    $_ = "";      
    if( $_inf = isset($ope) ? $ope : api_dat::est_ope($esq,$est,'inf') ){
      // cargo atributos
      $_atr = api_dat::atr($esq,$est);
      // cargo datos
      $_dat = api_dat::get($esq,$est,$dat);
      // cargo valores
      $_val = api_dat::est_ope($esq,$est,'val');
      // opciones
      $opc = [];
      if( isset($_inf['opc']) ){ $opc = api_lis::val_ite($_inf['opc']); unset($_inf['opc']); }

      // nombre: 
      if( in_array('nom',$opc) && isset($_dat->nom) ){ $_ .= "
        <p class='tit mar-0'>".api_tex::let($_dat->nom)."</p>";
      }// por valor
      elseif( isset($_val['nom'])  ){ $_ .= "
        <p class='tit mar-0'>".api_tex::let(api_obj::val($_dat,$_val['nom']))."</p>";
      }

      // descripciones
      $opc_cit = in_array('des-cit',$opc);
      if( isset($_inf['des']) ){
        if( is_array(($_inf['des'])) ){
          foreach( $_inf['des'] as $des ){
            if( isset($_dat->$des) ) 
              $_ .= $opc_cit ? "<p class='des'><q>".api_tex::let($_dat->$des)."</q></p>" : "<p class='des'>".api_tex::let($_dat->$des)."</p>";
          }
        }else{
          foreach( explode("\n",$_inf['des']) as $des ){ $_ .= "
            <p class='des'>".api_tex::let(api_obj::val($_dat,$des))."</p>";
          }
        }
      }
      if( in_array('des',$opc) && isset($_dat->des) ){
        $_ .= $opc_cit ? "<p class='des'><q>".api_tex::let($_dat->des)."</q></p>" : "<p class='des'>".api_tex::let($_dat->des)."</p>";
      }

      // imagen + atributos | lectura
      if( !empty($_val['ima']) || ( !empty($_inf['atr']) || !empty($_inf['cit']) ) )
      $_ .= "
      <div class='doc_val jus-cen mar_arr-1'>";
        if( !empty($_val['ima']) ){ // 'onclick'=>FALSE
          $_ .= api_fig::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
        }
        if( !empty($_inf['atr']) ){
          $_ .= api_dat::lis_atr($esq,$est,$_inf['atr'],$_dat);
          unset($_inf['atr']);
        }
        elseif( !empty($_inf['cit']) ){
          if( isset($_dat->{$_inf['cit']}) ) $_ .= "
          <q class='mar-1'>".api_tex::let($_dat->{$_inf['cit']})."</q>";
          unset($_inf['cit']);
        }
        $_ .= "
      </div>";
      
      // componentes
      foreach( $_inf as $inf_ide => $inf_val ){ 
        $inf_ide_pri = explode('-',$inf_ide)[0];
        if( $inf_sep = !in_array($inf_ide_pri,['htm']) ){ $_ .= "
          <section class='".( $inf_ide_pri != 'tab' ? 'ali_pro-cre' : '' )."'>";
        }
        switch( $inf_ide_pri ){
        // detalles : por atributos o por valor
        case 'det':
          if( is_array($inf_val) ){
            foreach( $inf_val as $det ){
              if( isset($_dat->$det) ){
                foreach( explode("\n",$_dat->$det) as $tex_par ){
                  $_ .= "<p>".api_tex::let($tex_par)."</p>";
                }
              }
            }
          }else{
            foreach( explode("\n",$inf_val) as $tex_par ){
              $_ .= "<p>".api_tex::let(api_obj::val($_dat,$tex_par))."</p>";
            }
          }
          break;
        // titulos con descripcion / atributos
        case 'tit':
          if( is_array($inf_val) ){
            if( preg_match("/-atr/",$inf_ide) ){
              if( isset($inf_val[1]) && is_array($inf_val[1]) ){ $_ .= "
                <p class='tit'>".api_tex::let($inf_val[0])."</p>
                ".api_dat::lis_atr($esq,$est,$inf_val[1],$_dat);
              }
            }else{
              foreach( $inf_val as $tit ){
                if( isset($_atr[$tit]) && isset($_dat->$tit) ){ $_ .= "
                  <p class='tit'>{$_atr[$tit]->nom}</p>";
                  foreach( explode("\n",$_dat->$tit) as $tex_par ){ $_ .= "
                    <p class='des'>".api_tex::let($tex_par)."</p>";
                  }
                }
              }
            }
          }
          break;                       
        // lecturas con "", con/sin titulo por atributo
        case 'lec':
          if( is_array($inf_val) ){
            $agr_cla = preg_match("/-lis/",$inf_ide) ? " tex_ali-izq" : "";
            $agr_tit = !preg_match("/-tit/",$inf_ide);
            foreach( $inf_val as $lec ){
              if( isset($_atr[$lec]) && isset($_dat->$lec) ) 
              if( $agr_tit ) $_ .= "
                <p class='tit{$agr_cla}'>{$_atr[$lec]->nom}</p>";
              $_ .= "
              <p class='cit mar-0{$agr_cla}'><q>".api_tex::let($_dat->$lec)."</q></p>";
            }
          }else{
            $_ .= "<p class='cit mar-0'><q>".api_tex::let(api_obj::val($_dat,$inf_val))."</q></p>";
          }
          break;
        // listado por atributo(\n) con titulo / punteo
        case 'lis': 
          if( is_array($inf_val) ){
            // atributos
            if( preg_match("/-atr/",$inf_ide) ){ 
              $_ .= api_dat::lis_atr($esq,$est,$inf_val,$_dat);
            }// titulo > ...valores
            else{
              foreach( $inf_val as $lis ){
                if( isset($_atr[$lis]) && isset($_dat->$lis) ){ $_ .= "
                  <p class='tit'>".api_tex::let($_atr[$lis]->nom)."</p>
                  ".api_lis::pos($_dat->$lis);
                }
              }
            }
          }
          break;
        // Tablero por identificador
        case 'tab':
          // convierto parametros por valores ($)          
          if( isset($inf_val[1]) ) $inf_val[1] = api_obj::val_lis($inf_val[1],$_dat);
          // defino identificador del tablero
          if( !isset($inf_val[1]['ide']) ) $inf_val[1]['ide'] = isset($_dat->ide) ? $_dat->ide : FALSE;
          // armo tablero por aplicacion
          extract( api_dat::ide($inf_val[0]) );
          if( $atr && class_exists($cla = "api_{$esq}") && method_exists($cla,"tab") ){
            $_ .= $cla::tab( $est, $atr, $inf_val[1], isset($inf_val[2]) ? $inf_val[2] : [] );
          }
          break;
        // Fichas : por atributos con Relaciones
        case 'fic':
          foreach( api_lis::val_ite($inf_val) as $ide ){
            if( isset($_atr[$ide]) && isset($_atr[$ide]->var['dat']) && isset($_dat->$ide) ){
              if( is_numeric($_dat->$ide) ){
                $_ .= api_dat::fic($_atr[$ide]->var['dat'],$_dat->$ide,[ 'opc'=>[ "det" ] ]);
              }// listado de imagenes por valor
              elseif( is_string($_dat->$ide) ){
                if( !isset($sep) ){
                  $sep = ( preg_match("/, /",$_dat->$ide) ) ? ", " : (  preg_match("/\s*\-\s*/",$_dat->$ide) ? " - " : FALSE );
                }                  
                if( $sep ){
                  // valores
                  $ran_lis = [];
                  if( $sep == ', ' ){
                    $ran_lis = explode($sep,$_dat->$ide);
                  }else{
                    $ran_sep = explode($sep,$_dat->$ide);
                    if( isset($ran_sep[0]) && isset($ran_sep[1]) ){
                      $ran_lis = range(api_num::val($ran_sep[0]), api_num::val($ran_sep[1]));
                    }
                  }  
                  foreach( $ran_lis as $atr_val ){
                    $_ .= api_dat::fic($_atr[$ide]->var['dat'],$atr_val,[ 'opc'=>[ "det" ] ]);
                  }
                }
              }                
            }
          }
          break;
        // Texto por valor: parrafos por \n
        case 'tex':
          foreach( api_lis::val_ite($inf_val) as $tex ){
            foreach( explode("\n",$tex) as $tex_val ){
              $_ .= "<p>".api_tex::let(api_obj::val($_dat,$tex_val))."</p>";
            }
          }
          break;          
        // Contenido HTML : textual o con objetos
        case 'htm':
          if( is_string($inf_val) ){
            $_ .= api_obj::val($_dat,$inf_val);
          }else{
            foreach( api_lis::val_ite($inf_val) as $ele ){
              // convierto texto ($), y genero elemento/s
              $_ .= api_ele::val( api_obj::val_lis($ele,$_dat) );
            }
          }
          break;
        // Ejecuciones : por clase::metodo([...parametros])
        case 'eje':
          // convierto valores ($), y ejecuto por identificadorv
          $_ .= api_eje::val( $inf_val['ide'], isset($inf_val['par']) ? api_obj::val_lis($inf_val['par'],$_dat) : [] );
          break;
        }
        if( $inf_sep ){ $_ .= "
          </section>";
        }
      }
    }
    return $_;
  }

  // abm
  static function abm( string $tip, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_eje = self::$EJE."ope_{$tip}";
    $_ope = self::$ABM;
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    switch( $tip ){
    case 'nav':
      $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
      if( !empty($url) ){
        $url_agr = "{$url}/0";
        $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
      }
      $_ .= "
      <fieldset class='doc_ope' abm='{$tip}'>    
        ".api_fig::ico('dat_ver', ['eti'=>"a", 'title'=>$_ope['ver']['nom'], 'onclick'=>"{$_eje}('ver');"])."

        ".api_fig::ico('dat_agr', ['eti'=>"a", 'title'=>$_ope['agr']['nom'], 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."

        ".api_fig::ico('dat_eli', ['eti'=>"a", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');"])."
      </fieldset>";
      break;
    case 'abm':
      $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
      $_ = "
      <fieldset class='doc_ope mar-2 esp-ara'>

        ".api_fig::ico('dat_ini', [ 'eti'=>"button", 'title'=>$_ope[$tip]['nom'], 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

        if( in_array('eli',$ope['opc']) ){

          $_ .= api_fig::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_ope['eli']['nom'], 'onclick'=>"{$_eje}('eli');" ]);
        }$_ .= "

        ".api_fig::ico('dat_fin', [ 'eti'=>"button", 'title'=>$_ope['fin']['nom'], 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

      </fieldset>";
      break;              
    case 'est':
      $_ .= "
      <fieldset class='doc_ope'>    
        ".api_fig::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
        
        ".api_fig::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
      </fieldset>";                  
      break;                
    }

    return $_;
  }
}