<?php

// Dato
class Dat {
  
  static string $IDE = "Dat-";
  static string $EJE = "Dat.";

  // getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = Dat::get_est('dat',$ide,'dat');
    
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
    }

    return $_;
  }

  // Tipo : dato + valor
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
    $dat_tip = Dat::_('tip');
    return isset($dat_tip[$ide]) ? $dat_tip[$ide] : FALSE;
  }

  /* Operaciones */
  static array $Ope = [
    'opc'=>[]
  ];// Comparación
  static function ope_ver( mixed $dat, string $ide, mixed $val ) : bool {
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
  }// Selector de operadores : select > ...option      
  static function ope_opc( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {

    if( !isset(self::$Ope['opc'][$dat[0]][$dat[1]]) ){

      $_dat = Dat::get( 'dat_ope', [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      self::$Ope['opc'][$dat[0]][$dat[1]] = Lis::opc( $_dat, $ope, ...$opc);
    }

    return self::$Ope['opc'][$dat[0]][$dat[1]];

  }

  // Identificadores
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

  /* Consulta: objeto | consulta */
  static function get( mixed $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

    // objeto->propiedad 
    if( is_string($dat) && is_string($ope) ){
      $esq = $dat;
      $est = $ope;        
      // busco datos por $clase::_($identificador)
      $_ = isset($val) ? $val : new stdClass;

      if( ( !isset($val) || !Obj::val_tip($val) ) && class_exists( $_cla = Tex::let_pal($esq) ) && method_exists($_cla,'_') ){

        $_ = !isset($val) ? $_cla::_($est) : $_cla::_($est,$val);
      }
    }
    // estructuras
    else{
      $_ = $dat;
      // datos de la base 
      if( is_string($ide = $dat) ){

        // ejecuto consulta
        $_ = sql::reg('ver', $ide, isset($ope) ? $ope : [] );

        if( isset($ope) ){
          // elimino marcas
          foreach( ['ver','jun','gru','ord','lim'] as $i ){ if( isset($ope[$i]) ) unset($ope[$i]); }
          // busco clave primaria
          if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){

            $ope['niv'] = sql::ind($ide,'ver','pri');
          }
        }
      }// resultados y operaciones
      if( isset($ope) && ( is_array($dat) || !isset($_['err']) ) ) $_ = Lis::val_est($_,$ope);
    }
    return $_;
  }// Cargo Estructura en app.dat
  static function get_est( string $esq, string $ide, mixed $ope = NULL, mixed $dat = NULL ) : mixed {
    $_ = [];
    global $App;

    // cargo una estructura
    if( !isset($ope) ){

      if( !isset($App->dat[$esq][$ide]) ){
        
        // Cargo Estructura
        $App->dat[$esq][$ide] = sql::est('ver',$sql_est = "{$esq}_{$ide}",'uni');
        if( empty($App->dat[$esq][$ide]) ){
          $App->dat[$esq][$ide] = new stdClass;
        }// de la Base
        else{
          $sql_vis = "_{$sql_est}";
        }
        // ...Propiedades extendidas
        $_est = Dat::get('dat_est',[ 'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'ele'=>["ope"], 'opc'=>"uni" ]);
        // si existe la estructura
        if( isset($_est->ope) ){
          // Propiedades
          foreach( $_est->ope as $ope_ide => $ope ){
            $App->dat[$esq][$ide]->$ope_ide = $ope;
          }
        }
        // Estructura de la base
        if( isset($sql_vis) ){
          // Atributos/columnas: de una vista ( si existe ) o de la tabla
          $App->dat[$esq][$ide]->atr = sql::atr( !empty( sql::est('lis',"_{$sql_est}",'uni') )  ? "_{$sql_est}" : $sql_est );
          if( isset($_est->ope['atr']) ){
            // cargo variables del operador
            foreach( $_est->ope['atr'] as $atr_ide => $atr_var ){
              $App->dat[$esq][$ide]->atr[$atr_ide]->var = Ele::val_jun(
                $App->dat[$esq][$ide]->atr[$atr_ide]->var, $atr_var
              );
            }
          }
          // Datos/registros: de una vista ( si existe ) o de la tabla
          $est_ope = isset($App->dat[$esq][$ide]->dat) ? $App->dat[$esq][$ide]->dat : [];
          $App->dat[$esq][$ide]->dat = Dat::get( sql::est('val',$sql_vis) == 'vis' ? $sql_vis : $sql_est, $est_ope );          
        }
      }
      // devuelvo estructura completa: esq + ide + nom + atr + dat + ...ope
      $_ = $App->dat[$esq][$ide];
    }
    // cargo operadores
    elseif( is_string($ope) ){
      // propiedad
      $ope_atr = explode('.',$ope);
      $_ = Obj::val_dat(Dat::get_est($esq,$ide),$ope_atr);
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
          $_ = Obj::val( Dat::get($esq,$ide,$dat), $_ );
          break;
        }        
      }      
    }

    return $_;
  }// Cargo variables : api.dat_var en app.var
  static function get_var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
    $_ = [];    
    global $App;
    
    // cargo todas las estructuras del esquema
    if( empty($dat) ){
      if( !isset($App->var[$esq]) ){
        $App->var[$esq] = Dat::get('dat_var',[
          'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo por agrupacion
    elseif( empty($val) ){
      if( !isset($App->var[$esq][$dat]) ){
        $App->var[$esq][$dat] = Dat::get('dat_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo uno
    else{
      if( !isset($App->var[$esq][$dat][$val]) ){
        $App->var[$esq][$dat][$val] = Dat::get('dat_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 'niv'=>['ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }

    if( !empty($ide) ){
      $_ = isset($App->var[$esq][$dat][$val][$ide]) ? $App->var[$esq][$dat][$val][$ide] : [];
    }
    elseif( !empty($val) ){
      $_ = isset($App->var[$esq][$dat][$val]) ? $App->var[$esq][$dat][$val] : [];
    }
    elseif( !empty($dat) ){
      $_ = isset($App->var[$esq][$dat]) ? $App->var[$esq][$dat] : [];
    }
    else{
      $_ = isset($App->var[$esq]) ? $App->var[$esq] : [];
    }

    return $_;
  }// Atributos desde listado o desde la base
  static function get_atr( string | array $dat, string $ope = "" ) : array {
    $_ = [];
    if( empty($ope) ){
      // de la base
      if( is_string($dat) ){
        $ide = Dat::ide($dat);
        $_ = Dat::get_est($ide['esq'],$ide['est'],'atr');
      }
      // listado variable por objeto
      else{
        foreach( $dat as $ite ){
          // del 1° objeto: cargo atributos
          foreach( $ite as $ide => $val ){ 
            $atr = new stdClass;
            $atr->ide = $ide;
            $atr->nom = $ide;
            $atr->var = Dat::tip($val);
            $_ [$ide] = $atr;
          }
          break;
        }        
      }
    }
    return $_;
  }// Relaciones : esq.est_atr | api.dat_atr[ide].dat
  static function get_rel( string $esq, string $est, string $atr ) : string {
    $_ = '';
    // armo identificador por nombre de estructura + atributo
    if( $atr == 'ide' ){
      $_ = $est;
    }
    // parametrizado en : $App.est_atr
    elseif( ( $_atr = Dat::get_est($esq,$est,'atr',$atr) ) && !empty($_atr->var['dat']) ){        
      $_ide = explode('_',$_atr->var['dat']);
      array_shift($_ide);
      $_ = implode('_',$_ide);
    }
    // valido existencia de tabla relacional : "_api.esq_est_atr"
    elseif( !!sql::est('val',"{$esq}_{$est}_{$atr}") ){ 
      $_ = "{$est}_{$atr}";
    }
    else{
      $_ = $atr;
    }
    return $_;
  }// armo selector : ide = atributo ? filtro + color + imagen + texto + numeros + fechas
  static function get_opc( string $ide, mixed $dat, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."get_opc(";
    $_eje = self::$EJE."get_opc(";

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
      $ope['ope'] = Ele::eje($ope['ope'],'cam',$_eje."'atr',this);",'ini');
      if( !empty($opc_ope_tam) ) $ope['ope'] = Ele::css($ope['ope'],$opc_ope_tam);
      // oculto items
      $cla = DIS_OCU;
      // copio eventos
      if( $ele_eje ) $ele_est['eti'] = Ele::eje($ele_est['eti'],'cam',$ele_eje);
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
    if( is_string($dat) || Lis::val($dat) ){
      $_ide = is_string($dat) ? explode('.',$dat) : $dat;
      $dat = [ $_ide[0] => [ $_ide[1] ] ];
    }
    // opciones por operador de estructura
    $_opc_ite = function( string $esq, string $est, string $ide, string $cla = NULL ) : array {
      $_ = [];
      // atributos parametrizados
      if( ( $dat_opc_ide = Dat::get_est($esq,$est,"opc.$ide") ) && is_array($dat_opc_ide) ){
        // recorro atributos + si tiene el operador, agrego la opcion      
        foreach( $dat_opc_ide as $atr ){
          // cargo atributo
          $_atr = Dat::get_est($esq,$est,'atr',$atr);
          $atr_nom = $_atr->nom;
          if( $_atr->ide == 'ide' && empty($_atr->nom) && !empty($_atr_nom = Dat::get_est($esq,$est,'atr','nom')) ){
            $atr_nom = $_atr_nom->nom;
          }
          // armo identificador
          $dat = "{$esq}.".Dat::get_rel($esq,$est,$atr);
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
        
        if( $dat_opc_est = Dat::get_est($esq_ide,$est_ide,'rel') ){

          // recorro dependencias de la estructura
          foreach( $dat_opc_est as $dep_ide ){
            // redundancia de esquemas
            $dep_ide = str_replace("{$esq_ide}_",'',$dep_ide);
            // datos de la estructura relacional
            $_est = Dat::get_est($esq_ide,$dep_ide);
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
      $_ .= Lis::opc($dat_esq,$ele_esq,'nad')."<c class='sep'>.</c>";
    }
    // selector de estructura [opcional]
    if( $opc_esq || $opc_est ){
      $_ .= Lis::opc($dat_est,$ele_est,'nad')."<c class='sep'>.</c>";
    }
    // selector de atributo con nombre de variable por operador
    $_ .= Lis::opc($dat_ope,$ele_ope,'nad');
    
    // selector de valor por relacion
    if( $opc_val ){
      // copio eventos
      if( $ele_eje ) $ele_val['eti'] = Ele::eje($ele_val['eti'],'cam',$ele_eje);
      $_ .= "
      <c class='sep'>:</c>
      <div class='doc_val'>
        ".Lis::opc( isset($dat_val) ? $dat_val : [], $ele_val, 'nad')."
        <span class='ico'></span>
      </div>";
    }
    return $_;
  }// busco Valores: nombre, descripcion, tablero, imagen, color, cantidad, texto, numero
  static function get_val( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( Dat::ide($ide) );
    // cargo valores
    $_val = Dat::get_est($esq,$est,'val');
    // cargo datos/registros
    if(  $tip != 'ima' ) $_dat = Dat::get($esq,$est,$dat);

    // armo titulo : nombre <br> detalle
    if( $tip == 'tit' ){
      
      $_ = ( isset($_val['nom']) ? Obj::val($_dat,$_val['nom']) : "" ).( isset($_val['des']) ? "\n".Obj::val($_dat,$_val['des']) : "");
    }
    // por atributos con texto : nom + des + ima 
    elseif( isset($_val[$tip]) ){

      if( $tip == 'ima' ){
        if( is_array($_val[$tip]) ) $tip = 'tab';
      }
      elseif( is_string($_val[$tip]) ){ 
        $_ = Obj::val($_dat,$_val[$tip]);
      }
    }

    // ficha por imagen
    if( $tip == 'ima' ){

      // identificador      
      $ele['data-esq'] = $esq;
      $ele['data-est'] = $est;

      // 1 o muchos: valores ", " o rango " - "
      $_ = "";
      $ele_ima = $ele;
      $ima_lis = is_string($dat) ? explode(preg_match("/, /",$dat) ? ", ": " - ",$dat) : [ $dat ];
      foreach( $ima_lis as $dat_val ){

        $_dat = Dat::get($esq,$est,$dat_val);

        $ele_ima['data-ide'] = $_dat->ide;
      
        // cargo titulos
        if( !isset($ele_ima['title']) ){
          $ele_ima['title'] = Dat::get_val('tit',"$esq.$est",$_dat);
        }
        elseif( $ele_ima['title'] === FALSE  ){
          unset($ele_ima['title']);
        }
        
        // acceso a informe
        if( !isset($ele_ima['onclick']) ){
          if( Dat::get_est($esq,$est,'inf') ) Ele::eje($ele_ima,'cli',"Dat.inf('$esq','$est',".intval($_dat->ide).")");
        }
        elseif( $ele_ima['onclick'] === FALSE ){
          unset($ele_ima['onclick']);
        }
        
        $_ .= Fig::ima( [ 'style' => Obj::val($_dat,$_val[$tip]) ], $ele_ima );
      }
    }
    // tablero por imagen
    elseif( $tip == 'tab' ){
      $_dat = Dat::get($esq,$est,$dat);
      $par = $_val['ima'];
      $ele_ima = $ele;
      $ele = isset($par[2]) ? $par[2] : [];
      $ele['sec'] = Ele::val_jun($ele_ima,isset($ele['sec']) ? $ele['sec'] : []);
      Ele::cla($ele['sec'],"ima");
      $_ = Dat::tab($par[0], $_dat, isset($par[1]) ? $par[1] : [], $ele);
    }
    // variable por dato
    elseif( $tip == 'var' ){
      
      $_ = "";

    }
    // textos por valor
    elseif( !!$ele ){  

      if( empty($ele['eti']) ) $ele['eti'] = 'p';
      $ele['htm'] = Tex::let($_);
      $_ = Ele::val($ele);
    }    

    return $_;
  }// busco valor por seleccion ( esq.est.atr ) : variable, html, ficha, color, texto, numero
  static function get_ver( string $tip, string $ide, mixed $dat, array $ele = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( Dat::ide($ide) );
    // parametros: "esq.est.atr" 
    $ide = 'NaN';
    if( !is_object($dat) ){

      $ide = $dat;
      $dat = Dat::get($esq,$est,$dat);
    }
    elseif( isset($dat->ide) ){

      $ide = $dat->ide;
    }

    if( is_object($dat) && isset($dat->$atr) ){
      
      $_atr = Dat::get_est($esq,$est,'atr',$atr);
      // variable por tipo
      if( $tip == 'var' ){
        $_var = $_atr->var;
        $_var['val'] = $dat->$atr;
        $_ = Ele::val($_val);
      }// proceso texto con letras
      elseif( $tip == 'htm' ){

        $_ = Tex::let($dat->$atr);
      }// color en atributo
      elseif( $tip == 'col' ){
        
        if( $col = Dat::get_ide('col',$esq,$est,$atr) ){ $_ = "
          <div".Ele::atr(Ele::cla($ele,"fon-{$col}-{$dat->$atr} alt-100 anc-100",'ini'))."></div>";
        }else{
          $_ = "<div class='err' title='No existe el color para el atributo : _{$esq}-{$est}-{$atr}'>{$dat->$atr}</div>";
        }
      }// imagen en atributo
      elseif( $tip == 'ima' ){

        if( !empty($_atr->var['dat']) ){
          $_ima_ide = explode('_',$_atr->var['dat']);
          $_ima['esq'] = array_shift($_ima_ide);
          $_ima['est'] = implode('_',$_ima_ide);
        }
        if( !empty($_ima) || !empty( $_ima = Dat::get_ide('ima',$esq,$est,$atr) ) ){
          
          $_ = Fig::ima($_ima['esq'],$_ima['est'],$dat->$atr,$ele);
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
          $_ = Num::var('val',$dat->$atr,$ele);
        }
        elseif( $tip == 'tex' ){
          $_ = Tex::var('val',$dat->$atr);
          
        }
        elseif( $tip == 'fec' ){
          $ele['value'] = $dat->$atr;
          $_ = "<time".Ele::atr($ele).">".Tex::let($dat->$atr)."</time>";
        }
        else{
          $_ = Tex::let($dat->$atr);
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
  static function get_ide( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
    // dato
    $_ = [ 'esq' => $esq, 'est' => $est ];
    if( !empty($atr) ){
      // armo identificador
      $_['est'] = $atr == 'ide' ? $est : "{$est}_{$atr}";  
      // busco dato en atributos
      $_atr = Dat::get_est($esq,$est,'atr',$atr);
      if( isset($_atr->var['dat']) && !empty($var_dat = $_atr->var['dat']) ){
        $dat = explode('_',$var_dat);
        $_['esq'] = array_shift($dat);
        $_['est'] = implode('_',$dat);
      }
    }
    // valido dato
    if( !empty( $dat_Val = Dat::get_est($_['esq'],$_['est'],"val.$tip",$dat) ) ){
      $_['ide'] = "{$_['esq']}.{$_['est']}";
      $_['val'] = $dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;
  }
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /* Ficha: imagen + { nombre + descripcion } */
  static function fic( string $ide, mixed $val = NULL, array $ope = [] ) : string {
    $_ = "";
    // proceso estructura
    extract( Dat::ide($ide) );
    $_val = Dat::get_est($esq,$est,'val');
    // proceso valores
    $val_lis = [];
    if( is_numeric($val) ){
      $val_lis = [ $val ];
    }elseif( is_string($val) ){
      $sep = ( preg_match("/, /",$val) ) ? ", " : (  preg_match("/\s-\s/",$val) ? " - " : FALSE );
      if( $sep == ', ' ){
        $val_lis = explode($sep,$val);
      }else{
        $ran_sep = explode($sep,$val);
        if( isset($ran_sep[0]) && isset($ran_sep[1]) ){
          $val_lis = range(Num::val($ran_sep[0]), Num::val($ran_sep[1]));
        }
      }
    }elseif( is_array($val) ){
      $val_lis = $val;
    }
    // armo fichas
    foreach( $val_lis as $val ){
      // cargo datos
      $_dat = ( class_exists( $cla = Tex::let_pal($esq)) && method_exists($cla,'_') ) ? $cla::_($est,$val) : [];
      $_ .= "
      <div class='doc_val'>";
  
        if( isset($_val['ima']) ) $_ .= Fig::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
  
        if( isset($_val['nom']) || isset($_val['des']) ){ $_.="
          <div class='tex_ali-izq'>";
            if( isset($_val['nom']) ){
              $_ .= Tex::var('val',Obj::val($_dat,$_val['nom']),['class'=>"tit"]);
            }
            if( isset($_val['des']) ){
              $_ .= Tex::var('val',Obj::val($_dat,$_val['des']),['class'=>"des"]);
            }
            $_ .= "
          </div>";
        }$_ .= "
      </div>";
    }
    return $_;
  }// Valores: .ima => { ...imagen por atributos } 
  static function fic_atr( string $ide, mixed $val = NULL, array $ope = [] ) : string {
    $_ = "";
    extract( Dat::ide($ide) );
    if( ( $_fic = Dat::get_est($esq,$est,'fic') ) && isset($_fic[0]) ){ $_ .= 

      "<div class='doc_val' data-esq='$esq' data-est='$est' data-atr='{$_fic[0]}' data-ima='$esq.$est'>".
      
        ( !empty($val) ? Fig::ima($esq,$est,$val,['class'=>"tam-4"]) : "" )."

      </div>";
      // imágenes de atributos
      if( !empty($_fic[1]) ){ $_ .= "
        <c class='sep'>=></c> 
        ".Dat::fic_ima($esq,$est,$_fic[1], $val);
      }
    }
    return $_;
  }// Imagenes : { ... ; ... }
  static function fic_ima( string $esq, string $est, array $atr, mixed $val = NULL, array $ope = [] ) : string {
    // Valores
    if( isset($val) ) $val = Dat::get($esq,$est,$val);
    // Atributos 
    if( empty($atr) ) $atr = Dat::get_est($esq,$est,'fic.ima');
    // Elementos
    if( !isset($ope['ima']) ) $ope['ima'] = [];
    $_ = "
    <ul class='lis val tam-mov'>
      <li><c>{</c></li>";        
      foreach( $atr as $atr ){
        $_ima = Dat::get_ide('ima',$esq,$est,$atr); $_ .= "
        <li class='mar_hor-1' data-esq='$esq' data-est='$est' data-atr='$atr' data-ima='{$_ima['ide']}'>
          ".( isset($val->$atr) ? Fig::ima($esq,"{$est}_{$atr}",$val->$atr,$ope['ima']) : "" )."
        </li>";
      } $_ .= "
      <li><c>}</c></li>
    </ul>";
    return $_;
  }

  // Informe : nombre + descripcion > imagen + atributos | lectura > detalle > tablero > ...
  static function inf( string $esq, string $est, mixed $dat = NULL, array $ope = NULL ) : string {
    $_ = "";      
    if( $_inf = isset($ope) ? $ope : Dat::get_est($esq,$est,'inf') ){      
      // cargo atributos
      $_atr = Dat::get_est($esq,$est,'atr');
      // cargo datos
      $_dat = Dat::get($esq,$est,$dat);
      // cargo valores
      $_val = Dat::get_est($esq,$est,'val');
      // cargo opciones
      $opc = [];
      if( isset($_inf['opc']) ){ 
        $opc = Lis::val_ite($_inf['opc']); 
        unset($_inf['opc']); 
      }

      // Nombre
      if( in_array('nom',$opc) && isset($_dat->nom) ){
        $_ .= Tex::var('val',$_dat->nom,['class'=>"tit"]);
      }// por valor
      elseif( isset($_val['nom'])  ){
        $_ .= Tex::var('val',Obj::val($_dat,$_val['nom']),['class'=>"tit"]);
      }

      // Descripcion
      if( in_array('des',$opc) ){
        if( isset($_val['des']) ){
          $_ .= Tex::var('val',Obj::val($_dat,$_val['des']),['class'=>"des"]);
        }elseif( isset($_dat->des) ){
          $_ .= Tex::var('val',$_dat->des,['class'=>"des"]);
        }        
      }

      // Detalle: imagen + atributos
      if( !empty($_val['ima']) || !empty($_inf['det']) ){ 
        $_ .= "
        <div class='doc_val jus-cen mar_arr-1'>";
          if( !empty($_val['ima']) ){
            $_ .= Fig::ima($esq,$est,$_dat,[ 'class'=>"mar_der-2" ]);
          }
          if( !empty($_inf['det']) ){
            $atr = $_inf['det'];
            unset($_inf['det']);
            $_ .= is_array($atr) ? Dat::inf_atr($esq,$est,$atr,$_dat) : Tex::var('val',$_dat->$atr,['class'=>"det"]);
          }$_ .= "
        </div>";
      }
      
      // Componentes: atributo + texto + listado + tablero + fichas + html + ejecuciones
      foreach( $_inf as $inf_ide => $inf_val ){
        $inf_ide_pri = explode('-',$inf_ide)[0];
        if( $inf_sep = !in_array($inf_ide_pri,['dat','htm']) ){ $_ .= "
          <section class='".( $inf_ide_pri != 'tab' ? 'ali_pro-cre' : '' )."'>";
        }
        switch( $inf_ide_pri ){
        // Datos: atributo nombre = valor
        case 'dat':
          $_ .= Dat::inf_atr($esq,$est,$inf_val,$_dat);
          break;
        // Atributos : valor c/s titulo
        case 'atr':
          $agr_tit = preg_match("/-tit/",$inf_ide);
          foreach( Lis::val_ite($inf_val) as $atr ){
            if( isset($_dat->$atr) ){
              // titulo
              if( $agr_tit ) $_ .= Tex::var('val',$_atr[$atr]->nom,['class'=>"tit"]);
              // contenido
              foreach( explode("\n",$_dat->$atr) as $tex_par ){
                $_ .= Tex::var('val',$tex_par);
              }
            }
          }
          break;
        // Texto por valor: parrafos por \n
        case 'tex':
          foreach( Lis::val_ite($inf_val) as $tex ){
            // por contenido
            if( is_string($tex) ){
              foreach( explode("\n",$tex) as $tex_val ){
                $_ .= Tex::var('val',Obj::val($_dat,$tex_val));
              }
            }// por elemento {<>}
            else{
              foreach( $tex as &$ele_val ){
                if( is_string($ele_val) ) $ele_val = Obj::val($_dat,$ele_val);
              }
              $_ .= Ele::val($tex);
            }
          }
          break;          
        // listados : "\n",
        case 'lis':
          foreach( Lis::val_ite($inf_val) as $lis ){
            if( isset($_atr[$lis]) && isset($_dat->$lis) ){
              // con atributo-titulo
              $_ .= Tex::var('val',$_atr[$lis]->nom,['class'=>"tit"]).Lis::pos($_dat->$lis);
            }
          }
          break;
        // Tablero por identificador
        case 'tab':
          $_ .= Dat::tab($inf_val[0], $_dat, isset($inf_val[1]) ? $inf_val[1] : [], isset($inf_val[2]) ? $inf_val[2] : []);
          break;
        // Fichas : por relaciones con valores(", ") o rangos(" - ")
        case 'fic':
          $agr_tit = preg_match("/-tit/",$inf_ide);
          foreach( Lis::val_ite($inf_val) as $ide ){
            if( isset($_atr[$ide]) && isset($_atr[$ide]->var['dat']) && isset($_dat->$ide) ){
              $dat_ide = explode('_',$_atr[$ide]->var['dat']);
              $dat_esq = array_shift($dat_ide);
              $dat_est = implode('_',$dat_ide);
              // titulo
              if( $agr_tit ) $_ .= Tex::var('val',$_atr[$ide]->nom,['class'=>"tit"]);
              // pido ficha/s
              $_ .= Dat::fic("{$dat_esq}.{$dat_est}", $_dat->$ide);
            }
          }
          break;
        // Contenido HTML : textual o con objetos
        case 'htm':
          if( is_string($inf_val) ){
            $_ .= Obj::val($_dat,$inf_val);
          }else{
            foreach( Lis::val_ite($inf_val) as $ele ){
              // convierto texto ($), y genero elemento/s
              $_ .= Ele::val( Obj::val_lis($ele,$_dat) );
            }
          }
          break;
        // Ejecuciones : por clase::metodo([...parametros])
        case 'eje':
          // convierto valores ($), y ejecuto por identificadorv
          $_ .= Eje::val( $inf_val['ide'], isset($inf_val['par']) ? Obj::val_lis($inf_val['par'],$_dat) : [] );
          break;
        }
        if( $inf_sep ){ $_ .= "
          </section>";
        }
      }
    }
    return $_;
  }// Listado
  static function inf_lis( string | array $dat, string $ide, array $ele = [] ) : string {
    $_ = $dat;
    if( is_array($dat) ){
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      $ele['lis']['data-dat'] = $ide;
      // tipos: pos + ite + tab
      $tip = "dep";
      if( isset($ele['lis_tip']) ){
        $tip = $ele['lis_tip'];
        unset($ele['lis_tip']);
      }
      $_ = Lis::$tip($dat, $ele);
    }
    return $_;
  }// Glosario : palabras por esquema
  static function inf_ide( string | array $ide, array $ele = [] ) : string {

    $_ = [];
    $_ide = explode('.',$ide);
    
    if( is_array( $tex = Dat::get('app_ide',['ver'=>"`esq`='{$_ide[0]}' AND `ide`='{$_ide[1]}'"]) ) ){

      foreach( $tex as $pal ){
        $_[ $pal->nom ] = $pal->des;
      }
    }
    // operadores : toggle + filtro
    if( !isset($ele['opc']) ) $ele['opc'] = [];

    return Lis::pos($_,$ele);

  }// Atributos : item => Atributo: "valor"
  static function inf_atr( string $esq, string $est, array $atr, mixed $dat, array $ope = [] ) : string {
    $_ = "";
    $_opc = isset($ope['opc']) ? $ope['opc'] : [];
    $_opc_des = in_array('des',$_opc);
    // cargo dato
    if( !is_object($dat) ) $dat = Dat::get($esq,$est,$dat);
    // cargo atributos
    $dat_atr = Dat::get_est($esq,$est,'atr');      
    $ite = [];
    foreach( ( !empty($atr) ? $atr : array_keys($dat_atr) ) as $atr ){       
      if( isset($dat_atr[$atr]) && isset($dat->$atr) ){ 
        $_atr = $dat_atr[$atr];
        $val = $dat->$atr;
        if( is_numeric($val) && isset($_atr->var['dat']) ){
          // busco nombres /o/ iconos
          $atr_ide = explode('_',$_atr->var['dat']);
          $atr_esq = array_shift($atr_ide);
          $atr_est = implode('_',$atr_ide);
          $atr_dat = Dat::get_est($atr_esq,$atr_est,'val');
          $atr_obj = [];
          if( class_exists($atr_cla = $atr_esq) && method_exists($atr_cla,'_') ){
            $atr_obj = $atr_cla::_("{$atr_est}", $val);
          }
          if( isset($atr_dat['nom']) ){
            $val = Tex::let( Obj::val($atr_obj,$atr_dat['nom']) );
            if( isset($atr_dat['des']) && !$_opc_des ){
              $val .= "<br>".Tex::let(Obj::val($atr_obj,$atr_dat['des']));
            }
          }
          $val = str_replace($dat_atr[$atr]->nom,"<b class='let-ide'>{$dat_atr[$atr]->nom}</b>",$val);
        }
        else{
          $val = "<b class='let-ide'>{$dat_atr[$atr]->nom}</b><c>:</c> ".Tex::let($val);
        }
        $ite []= $val;
      }
    }

    $_ = Lis::pos($ite,$ope);          

    return $_;
  }// Descripciones : imagen + nombre > ...atributos
  static function inf_des( string $esq, string $est, string | array $atr, array $ele = [], ...$opc ) : string {
    $_ = [];

    // tipos de lista
    $tip = !empty($ele['tip']) ? $ele['tip'] : 'dep';

    // atributos de la estructura
    $atr = Lis::val_ite($atr);

    // descripciones : cadena con " ()($)atr() "
    $des = !empty($ele['des']) ? $ele['des'] : FALSE;

    // elemento de lista
    if( !isset($ele['lis']) ) $ele['lis'] = [];
    Ele::cla($ele['lis'],"ite",'ini');
    $ele['lis']['data-ide'] = "$esq.$est";

    if( class_exists( $cla = Tex::let_pal($esq) ) ){

      foreach( $cla::_($est) as $pos => $_dat ){ 
        $htm = 
        Fig::ima($esq,$est,$_dat,[ 'class' => "mar_der-2" ])."
        <dl>
          <dt>".( isset($_dat->nom) ? $_dat->nom : ( isset($_dat->ide) ? $_dat->ide : $pos ) )."<c>:</c>".( $des ? " ".Obj::val($_dat,$des) : "" )."</dt>";
          foreach( $atr as $ide ){ 
            if( isset($_dat->$ide) ){ $htm .= "
              <dd>".Tex::let($_dat->$ide)."</dd>";
            }
          }$htm .= "
        </dl>";
        $_ []= $htm;
      }
    }
    return Lis::$tip( $_, $ele, ...$opc );

  }// Posiciones : ficha + nombre ~ descripcion ~ posicion
  static function inf_pos( string $esq, string $est, array $dat, array $ele = [] ) : string {
    $_ = [];

    foreach( Dat::get_est($esq,$est,'pos') as $ite ){
      $var = [ 'ite'=>$ite['nom'], 'lis'=>[] ];
      extract( Dat::ide($ite['ide']) );
      $ope_atr = Dat::get_est($esq,$est,'atr');

      foreach( $ite['atr'] as $atr ){
        $val = isset($dat[$est]->$atr) ? $dat[$est]->$atr : NULL;        
        $_atr = isset($ope_atr[$atr]->var) ? $ope_atr[$atr]->var : [];
        
        // identificadores
        $_ide = explode('_', isset($_atr['dat']) ? $_atr['dat'] : "{$esq}_{$est}_{$atr}" );
        $esq_ide = array_shift($_ide);
        $est_ide = implode('_',$_ide);
        $dat_ide = "{$esq_ide}.{$est_ide}";
        
        // datos
        $_dat = Dat::get($esq_ide,$est_ide,$val);
        $_val = Dat::get_est($esq_ide,$est_ide,'val');
        
        $htm = isset($_val['ima']) ? Fig::ima($esq_ide ,$est_ide, $_dat, [ 'class'=>"tam-3 mar_der-1" ]) : "";
        $htm .= "
        <div class='tam-cre'>";
          if( isset($_val['nom']) ) $htm .= "
            <p class='tit'>".Tex::let( Dat::get_val('nom',$dat_ide,$_dat) )."</p>";
          if( isset($_val['des']) ) $htm .= "
            <p class='des'>".Tex::let( Dat::get_val('des',$dat_ide,$_dat) )."</p>";
          if( isset($_val['num']) ) $htm .= 
            Num::var('ran',$val,[ 'min'=>1, 'max'=>$_val['num'], 'disabled'=>"", 'class'=>"anc-100"],'ver');
          $htm .= "
        </div>";
        $var['lis'] []= $htm;
      }
      $_ []= $var;
    }
    $ele['lis-1'] = [ 'class'=>"ite" ];
    $ele['opc'] = [ "tog","ver","not-sep" ];
    $ele['ope'] = [ 'class'=>"mar_arr-1" ];

    return Lis::dep($_,$ele);
  }
  
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
  /* Procesos de Lista */  
  static array $Val = [
    'val'=>[],
    'abm'=>[
      'agr'=>['nom'=>"Agregar"    ], 
      'mod'=>['nom'=>"Modificar"  ], 
      'eli'=>['nom'=>"Eliminar"   ],
    ],
    'ope'=>[
      'acu'=>['nom'=>"Acumulados" ], 
      'ver'=>['nom'=>"Selección"  ], 
      'sum'=>['nom'=>"Sumatorias" ], 
      'cue'=>['nom'=>"Conteos"    ]
    ]
  ];// cargo datos de un proceso ( absoluto o con dependencias )
  static function val( array $dat ) : array {
    $_ = [];
    // cargo temporal
    foreach( $dat as $esq => $est_lis ){
      // recorro estructuras del esquema
      foreach( $est_lis as $est => $dat ){
        // recorro dependencias
        foreach( ( !empty($dat_est = Dat::get_est($esq,$est,'rel')) ? $dat_est : [ $esq => $est ] ) as $ide => $ref ){
          // acumulo valores
          if( isset($dat->$ide) ) $_[$ref] = $dat->$ide;
        }
      }
    }
    // agrego
    $_['pos'] = count(self::$Val['val']) + 1;
    self::$Val['val'] []= $_;

    return $_;
  }// acumulado : posicion + marcas + seleccion
  static function val_acu( array $dat, array $ope = [], array $opc = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."val_acu";

    if( empty($opc) ) $opc = array_keys($dat);

    $_eje_val = isset($ope['eje']) ? $ope['eje'] : NULL;

    if( !empty($ope['ide']) ) $_ide = $ope['ide'];

    $_ .= "
    <div class='doc_ren'>";
      foreach( $opc as $ide ){        
        $_ .= Doc::var('dat',"ope.acu.$ide", [
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
  }// sumatorias por valores
  static function val_sum(  string $dat, mixed $val = [], array $ope = [] ) : string {
    $_ = "";
    extract( Dat::ide($dat) );
    $_ide = self::$IDE."sum"." _$esq-$est";
    // estructuras por esquema
    foreach( Dat::get_var($esq,'ope','sum') as $ide => $ite ){
  
      $_ .= Doc::var($esq,"ope.sum.$ide",[
        'ope'=>[ 'id'=>"{$_ide} sum-{$ide}" ],
        // busco fichas del operador
        'htm_fin'=> !empty($ite['var_fic']) ? Dat::fic_atr($ite['var_fic'], $val, $ope) : ''
      ]);
    }
    return $_;
  }// filtros : datos + valores
  static function val_ver( array $ope, string $ide, string $eje, ...$opc ) : string {
    $_ = "";        
    $tip = isset($ope['tip']) && !empty($ope['tip']) ? $ope['tip'] : [ 'pos','fec' ];
    // Controladores: ( desde - hasta ) + ( cada - cuántos: del inicio, del final )
    $ope_ver_var = function ( array $var = [], array $ele = [], ...$opc ) : string {
      $_ = "";
      
      // proceso controlador 
      $_ite = function ( $ide, &$var ) {
  
        if( !isset($var[$ide]) ) $var[$ide] = [];
        
        if( !empty($var[$ide]['id']) ) $var[$ide]['id'] .= "-{$ide}";
  
        // aseguro tipos numericos para incremento y limite
        if( ( $ide == 'inc' || $ide == 'lim' ) ) $var[$ide]['tip'] = "num_int";
  
        return $var[$ide];
      };
      
      // operadores por defecto
      if( empty($var) ) $var = [ 'ini'=>[], 'fin'=>[] ];
  
      // imprimo: desde - hasta
      foreach( ['ini','fin'] as $ide ){
        
        if( isset($var[$ide]) ) $_ .= Doc::var('dat',"ope.ver.$ide", [ 'ope'=>$_ite($ide,$var) ]);
      }

      // imprimo cada
      if( isset($var['inc']) ){
        $_ .= Doc::var('dat',"ope.ver.inc", [ 'ope'=>$_ite('inc',$var) ]);
      }

      // imprimo cuántos
      if( isset($var['lim']) ){
        $_eje = "Doc.var('mar',this,'bor-sel');".( isset($var['lim']['onchange']) ? " {$var['lim']['onchange']}" : "" );
        $_ .= "
        <div class='doc_ren tam-ini'>

          ".Doc::var('dat',"ope.ver.lim", [ 'ope'=>$_ite('lim',$var) ] )."

          <fieldset class='doc_ope'>
            ".Fig::ico('lis_ini',[ 'eti'=>"button", 'title'=>"Los primeros...", 'class'=>"bor-sel", 'onclick'=>$_eje ])."
            ".Fig::ico('lis_fin',[ 'eti'=>"button", 'title'=>"Los primeros...", 'onclick'=>$_eje ])."
          </fieldset>

        </div>";
      }

      return $_;
    };

    // por listado de registros
    if( isset($ope['dat']) ){

      // form: dato por estructuras
      if( isset($ope['est']) ){         
        // aseguro estructuras
        $ope_dat = [];
        foreach( $ope['est'] as $esq_ide => $est_lis ){
          $ope_dat[$esq_ide] = Lis::val($est_lis) ? $est_lis : array_keys($est_lis);
        }
        // // opciones: pido selectores ( + ajustar tamaño... )
        array_push($opc,'est','val');
        $_ .= "
        <form class='ide-dat'>
          <fieldset class='doc_inf doc_ren'>
            <legend>por Datos</legend>

            ".Doc::var('dat',"ope.ver.dat",[ 
              'ite'=>[ 'class'=>"tam-mov" ], 
              'htm'=>Dat::get_opc('ver',$ope_dat,[ 'ope'=>[ 'id'=>"{$ide}-val", 'onchange'=>"$eje('dat');" ] ], ...$opc)
            ])."

          </fieldset>
        </form>";
      }

      // form: posicion
      $dat_cue = count($ope['dat']);
      if( in_array('pos',$tip) ){
        $pos_var = [ 'id'=>"{$ide}-pos", 'min'=>"1", 'max'=>$dat_cue, 'onchange'=>"$eje('pos');" ];
        $pos_var_val = array_merge($pos_var,[ 'tip'=>"num_int" ]); 
        $_ .= "
        <form class='ide-pos'>
          <fieldset class='doc_inf doc_ren'>
            <legend>por Posiciones</legend>

            ".$ope_ver_var([ 'ini'=>$pos_var_val, 'fin'=>$pos_var_val, 'inc'=>$pos_var, 'lim'=>$pos_var ])."

          </fieldset>
        </form>";
      }

      // form: fecha principal
      $fin = $dat_cue-1;
      if( in_array('fec',$tip) && isset($ope['dat'][0]['fec_dat']) && isset($ope['dat'][$fin]['fec_dat']) ){
        $fec_var = [ 'id'=>"{$ide}-fec", 'min'=>1, 'max'=>$dat_cue, 'onchange'=>"$eje('fec');" ];
        $fec_var_val = array_merge($fec_var,[ 'tip'=>"fec_dia",
          'min'=>Fec::val_var($ope['dat'][0]['fec_dat']),
          'max'=>Fec::val_var($ope['dat'][$fin]['fec_dat'])
        ]);
        $_ .= "
        <form class='ide-fec'>
          <fieldset class='doc_inf doc_ren'>
            <legend>por Fechas</legend>

            ".$ope_ver_var([ 'ini'=>$fec_var_val, 'fin'=>$fec_var_val, 'inc'=>$fec_var, 'lim'=>$fec_var ])."
            
          </fieldset>          
        </form>";
      }          
    }
    // valores por atributo
    elseif( isset($ope['var']) ){
      $var = $ope['var'];
      $var_ope = array_merge($ope['var'],[ 'tip'=>"num_int", 'min'=>1 ]);
      $_ .= "
      <form>
        <fieldset>

          ".$ope_ver_var([ 'ini'=>$var, 'fin'=>$var, 
            'inc'=>in_array('inc',$opc) ? $var_ope : NULL, 
            'lim'=>in_array('lim',$opc) ? $var_ope : NULL 
          ])."
          
        </fieldset>          
      </form>";
    }

    return $_;
  }// conteos : por valores de estructura relacionada por atributo
  static function val_cue( string $tip, string | array $dat, array $ope = [] ) : string | array {
    $_ = "";
    $_ide = self::$IDE."val_cue";
    $_eje = self::$EJE."val_cue";

    if( is_string($dat) ){
      extract( Dat::ide($dat) );
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
          foreach( ( !empty($dat_opc_est = Dat::get_est($esq,$est_ide,'rel')) ? $dat_opc_est : [ $est_ide ] ) as $est ){
            $est = str_replace("{$esq}_",'',$est);
            // armo listado para aquellos que permiten filtros
            if( $dat_opc_ver = Dat::get_est($esq,$est,'opc.ver') ){
              // nombre de la estructura
              $est_nom = Dat::get_est($esq,$est)->nom;                
              $htm_lis = [];
              foreach( $dat_opc_ver as $atr ){
                // armo relacion por atributo
                $rel = Dat::get_rel($esq,$est,$atr);
                // busco nombre de estructura relacional
                $rel_nom = Dat::get_est($esq,$rel)->nom;
                // armo listado : form + table por estructura
                $htm_lis []= [ 
                  'ite'=>$rel_nom, 'htm'=>"
                  <div class='val mar_izq-2 dis-ocu'>
                    ".Dat::val_cue('est',"{$esq}.{$est}.{$atr}",$ope)."
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
      $ide = !empty($atr) ? Dat::get_rel($esq,$est,$atr) : $est;
      $_ = "
      <!-- filtros -->
      <form class='doc_val'>

        ".Doc::var('val','ver',[ 
          'nom'=>"Filtrar", 
          'id'=> "{$_ide}-ver {$esq}-{$ide}",
          'htm'=> Lis::ope_ver([ 'ide'=>"{$_ide}-ver {$esq}-{$ide}", 'eje'=>"$_eje('ver',this);" ])
        ])."
      </form>

      <!-- valores -->
      <table data-esq='{$esq}' data-est='{$est}'".( !empty($atr) ? " data-atr='{$atr}'" : '' ).">
        <tbody>";
        foreach( Dat::get($esq,$ide) as $ide => $_var ){
        
          $ide = isset($_var->ide) ? $_var->ide : $ide;

          if( !empty($atr) ){
            $ima = !empty( $_ima = Dat::get_ide('ima',$esq,$est,$atr) ) ? Fig::ima($_ima['esq'], $_ima['est'], $ide, ['class'=>"tam-1 mar_der-1"]) : '';
          }
          else{
            $ima = Fig::ima($esq, $est, $ide, ['class'=>"tam-1 mar_der-1"]);
          }$_ .= "
          <tr class='pos' data-ide='{$ide}'>
            <td data-atr='ima'>{$ima}</td>
            <td data-atr='ide'>".Tex::let($ide)."</td>
            <td data-atr='nom'>".Tex::let(isset($_var->nom) ? $_var->nom : '')."</td>
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
  }// administrador: alta, baja y modificacion
  static function val_abm( string $tip, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_eje = self::$EJE."val_abm-{$tip}";
    $_abm = self::$Val['abm'];
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    switch( $tip ){
    // Navegador
    case 'nav':
      $url = isset($ope['url']) ? SYS_NAV."{$ope['url']}" : '';
      if( !empty($url) ){
        $url_agr = "{$url}/0";
        $url_ver = in_array('lis',$opc) ? "{$url}/lis" : "{$url}/tab";
      }
      $_ .= "
      <fieldset class='doc_ope abm-$tip'>

        ".Fig::ico('dat_ver', ['eti'=>"a", 'title'=>$_abm['ver']['nom'], 'onclick'=>"{$_eje}('ver');"])."

        ".Fig::ico('dat_agr', ['eti'=>"a", 'title'=>$_abm['agr']['nom'], 'href'=>!empty($url) ? $url_agr : NULL, 'onclick'=>empty($url) ? "{$_eje}('agr');" : NULL])."

        ".Fig::ico('dat_eli', ['eti'=>"a", 'title'=>$_abm['eli']['nom'], 'onclick'=>"{$_eje}('eli');"])."
      </fieldset>";
      break;
    // Tabla
    case 'est':
      $_ .= "
      <fieldset class='doc_ope'>    
        ".Fig::ico('dat_agr',['eti'=>"button", 'type'=>"button", 'title'=>"Agregar", 'onclick'=>""])."
        
        ".Fig::ico('dat_eli',['eti'=>"button", 'type'=>"button", 'title'=>"Eliminar", 'onclick'=>""])."    
      </fieldset>";                  
      break;                
    // Registro
    case 'inf':
      $tip = isset($ope['tip']) ? $ope['tip'] : 'ini';
      $_ = "
      <fieldset class='doc_ope mar-2 esp-ara'>

        ".Fig::ico('dat_ini', [ 'eti'=>"button", 'title'=>$_abm[$tip]['nom'], 'type'=>"submit", 'onclick'=>"{$_eje}('{$tip}');" ]);

        if( in_array('eli',$ope['opc']) ){

          $_ .= Fig::ico('dat_eli', [ 'eti'=>"button", 'type'=>"button", 'title'=>$_abm['eli']['nom'], 'onclick'=>"{$_eje}('eli');" ]);
        }$_ .= "

        ".Fig::ico('dat_fin', [ 'eti'=>"button", 'title'=>$_abm['fin']['nom'], 'type'=>"reset", 'onclick'=>"{$_eje}('fin');" ])."    

      </fieldset>";
      break;              
    }

    return $_;
  }

  /* Tabla */
  static array $Lis = [
    'var' => [
      // estructuras por esquema => [ ...$esq =>[ ...$est ] ]
      'est'=>[],
      // columnas
      'atr_dat'=>[],
      'atr_tot'=>0,
      // titulos : item superior
      'tit'=>[],
      // datos de filas
      'dat'=>[],
      // detalles : item inferior
      'det'=>[],
      // Valores : acumulado + posicion principal
      'val'=>[ 'acu'=>[], 'pos'=>[] ],
      // opciones
      'opc'=>[
        'cab_ocu',  // ocultar cabecera de columnas
        'ite_ocu',  // oculto items: en titulo + detalle
        'ima',      // buscar imagen para el dato
        'var',      // mostrar variable en el dato
        'htm'       // convertir texto html en el dato
      ]
    ],
    'dat' => [
      'esq-1'=>[ 
        "est-1" => [
          // columnas
          'atr'=>[],
          'atr_tot'=>0,// totales
          'atr_ocu'=>[],// ocultas
          // titulos
          'tit_cic'=>[],
          'tit_gru'=>[],
          // detalle
          'det_cic'=>[],
          'det_gru'=>[],
          'det_des'=>[]
        ],
        "est-2" => []
      ]
    ],
    'ope' => [
      'val' => [ 'ide'=>'val', 'ico'=>"est",     'nom'=>"Valores"       , 'des'=>"" ],
      'ver' => [ 'ide'=>'ver', 'ico'=>"dat_ver", 'nom'=>"Filtros"       , 'des'=>"" ],
      'atr' => [ 'ide'=>'atr', 'ico'=>"lis_ver", 'nom'=>"Columnas"      , 'des'=>"" ],
      'des' => [ 'ide'=>'des', 'ico'=>"lis_gru", 'nom'=>"Descripciones" , 'des'=>"" ],
      'cue' => [ 'ide'=>'cue', 'ico'=>"app_nav", 'nom'=>"Cuentas"       , 'des'=>"" ]
    ]
  ];// thead( tr > th ) + tbody( tr > td ) + tfoot( tr > td ) 
  static function lis( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis";
    self::$Lis['dat'] = [];
    if( !isset($ope['opc']) ) $ope['opc']=[];
    foreach( ['lis','tit_ite','tit_val','ite','dat_val','det_ite','det_val','val'] as $i ){ if( !isset($ele[$i]) ) $ele[$i]=[]; }
    /////////////////////////////////////////
    // 1- proceso estructura de la base /////
    if( $_val_dat = is_string($dat) ){
      extract( Dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
      // identificador unico
      $ele['lis']['data-esq'] = $esq;
      $ele['lis']['data-est'] = $est;
      // cargo operadores
      self::$Lis['dat'] = Dat::lis_dat($esq,$est,$ope);      
      $_val = [ 'tit_cic'=>[], 'tit_gru'=>[], 'det_des'=>[], 'det_cic'=>[], 'det_gru'=>[] ];
      $_val_tit = [ 'cic', 'gru' ];
      $_val_det = [ 'des', 'cic', 'gru'];
      $ele_ite = [];
      $ope['atr_tot'] = 0;
      foreach( self::$Lis['dat'] as $esq_ide => $est_lis ){
        foreach( $est_lis as $est_ide => $est_ope ){
          // total de columnas    
          $ope['atr_tot'] =+ $est_ope['atr_tot'];
          // valido contenido : titulos y detalles por estructura de la base
          foreach( $_val as $i => &$v ){
            if( isset($est_ope[$i]) ){
              $v []= "{$esq_ide}.{$est_ide}";
              if( !isset($ele_ite[$i]) ){
                $_i = explode('_',$i);
                $ele_ite[$i] = [ 'ite'=>[ 'data-opc'=>$_i[0], 'data-ope'=>$_i[1] ], 'atr'=>[ 'colspan'=>$ope['atr_tot'] ] ]; 
              }
            }
          }
        }
      }
      // aseguro valores
      if( !isset($ope['dat']) ) $ope['dat'] = Dat::get($esq,$est);
      // valido item por { objeto( tabla) / array( joins) }
      foreach( $ope['dat'] as $val ){ $_val_obj = is_object($val); break; }
    }
    else{
      // datos de atributos
      if( !isset($ope['atr_dat']) ) $ope['atr_dat'] = Dat::get_atr($dat);
      // listado de columnas
      if( !isset($ope['atr']) ) $ope['atr'] = array_keys($ope['atr_dat']);
      // total de columnas
      $ope['atr_tot'] = count($ope['atr']);
    }
    /////////////////////////////////////////
    // 2- imprimo operador //////////////////
    if( isset($ope['ope']) ){

      if( empty($ope['ope']) ) $ope['ope'] = [ "dat" ];

      if( !empty( $_ope = Obj::val_nom(self::$Lis['ope'],'ver',Lis::val_ite($ope['ope'])) ) ){

        foreach( $_ope as $ope_ide => &$ope_lis ){
          $ope_lis['htm'] = Dat::lis_ope($ope_ide,$dat,$ope,$ele);
        }
        $_ .= Doc::nav('ope', $_ope,[ 'lis'=>['class'=>"mar_hor-1"] ],'ico','tog');
      }    
    }
    /////////////////////////////////////////
    // 3- imprimo listado ///////////////////
    Ele::cla($ele['lis'],"dat lis",'ini'); 
    $_ .= "
    <div".Ele::atr($ele['lis']).">
      <table>";
        // centrado de texto
        if( !isset($ele['dat_val']['align']) ) $ele['dat_val']['align'] = 'center';
        // cabecera
        if( !in_array('cab_ocu',$ope['opc']) ){ $_ .= "
          <thead>
            ".Dat::lis_atr($dat,$ope,$ele)."
          </thead>";
        }
        // cuerpo        
        $_.="
        <tbody>";
          $pos_val = 0;   
          // recorro: por listado $dat = []                     
          if( !$_val_dat ){
            $_ite = function( string $tip, int $pos, array $ope, array $ele ) : string {
              $_ = "";
              foreach( Lis::val_ite($ope[$tip][$pos]) as $val ){ 
                $_ .= "
                <tr".Ele::atr($ele["{$tip}_ite"]).">
                  <td".Ele::atr(Ele::val_jun(['data-ope'=>$tip,'colspan'=>$ope['atr_tot']],$ele["{$tip}_val"])).">
                    ".( is_array($val) ? Ele::val($val) : "<p class='{$tip} tex_ali-izq'>".Tex::let($val)."</p>" )."
                  </td>
                </tr>";
                return $_;
              }
              return $_;
            };            
            foreach( $dat as $ite => $val ){ 
              // Titulo
              if( !empty($ope['tit'][$ite]) ) $_ .= self::lis_des('tit',$ite,$ope,$ele);
              // Item
              $ele_pos = $ele['ite'];              
              Ele::cla($ele_pos,"pos ide-$ite",'ini'); $_ .= "
              <tr".Ele::atr($ele_pos).">
                ".Dat::lis_ite( $dat, $val, $ope, $ele )."
              </tr>";
              // Detalle
              if( !empty($ope['det'][$ite]) ) $_ .= self::lis_des('det',$ite,$ope,$ele);
            }
          }
          // estructuras de la base esquema
          else{
            // contenido previo : titulos por agrupaciones
            if( !empty($_val['tit_gru']) ){
              foreach( self::$Lis['dat'] as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_gru']) ){
                    $_ .= Dat::lis_tit('gru', $dat_ide, $est_ope, $ele_ite['tit_gru'], $ope['opc']);
                  }                  
                }
              }
            }            
            // recorro datos
            foreach( $ope['dat'] as $pos => $val ){
              // 1- arriba: referencias + titulos por ciclos
              foreach( self::$Lis['dat'] as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  // recorro por relaciones
                  if( $dat_rel = Dat::get_est($esq,$est,'rel') ){
                    foreach( $dat_rel as $atr => $ref ){
                      $ele['ite']["data-{$ref}"] = $_val_obj ? $val->$atr : $val["{$ref}"];
                    }
                  }
                  // cargo titulos de ciclos
                  if( in_array($dat_ide = "{$esq}.{$est}", $_val['tit_cic']) ){
                    $_ .= Dat::lis_tit('cic', $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite['tit_cic'], $ope['opc']);
                  }
                }
              }
              // 2- item por [ ...esquema.estructura ]
              $pos_val ++;
              $ele_pos = $ele['ite'];
              Ele::cla($ele_pos,"pos ide-$pos_val",'ini'); $_ .= "
              <tr".Ele::atr($ele_pos).">";
              foreach( self::$Lis['dat'] as $esq => $est_lis ){
                // recorro la copia y leo el contenido desde la propiedad principal
                foreach( $est_lis as $est => $est_ope){
                  $_ .= Dat::lis_ite("{$esq}.{$est}", $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele, $ope['opc']);
                }
              }$_ .= "
              </tr>";
              // 3- abajo: detalles
              foreach( self::$Lis['dat'] as $esq => $est_lis ){
                foreach( $est_lis as $est => $est_ope ){
                  foreach( $_val_det as $ide ){
                    if( in_array($dat_ide = "{$esq}.{$est}", $_val["det_{$ide}"]) ){
                      $_ .= Dat::lis_det($ide, $dat_ide, $_val_obj ? $val : $val["{$esq}_{$est}"], $est_ope, $ele_ite["det_{$ide}"], $ope['opc']);
                    }
                  }                  
                } 
              }                    
            }
          }
          $_ .= "              
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
  }// Listado-Tabla 
  static function lis_dat( string $esq, string $est, array $ope = [] ) : array {     
    $_ = [];

    $_ite = function( string $esq, string $est, array $ope = [] ) : array {
      
      // inicializo atributos de lista
      $_ = $ope;

      /* columnas 
      */
      if( empty($_['atr']) ) $_['atr'] = !empty( $_atr = Dat::get_est($esq,$est,'atr') ) ? array_keys($_atr) : [];
      // totales
      $_['atr_tot'] = count($_['atr']);
      
      /* ciclos y agrupaciones 
      */
      // busco descripciones + inicio de operadores      
      foreach( ['cic','gru'] as $ide ){

        if( isset($_["tit_{$ide}"]) ){

          foreach( $_["tit_{$ide}"] as $atr ){
            
            // inicio ciclo
            if( $ide == 'cic' ) $_['cic_val'][$atr] = -1;

            // busco descripciones
            if( isset( $_atr["{$atr}_des"] ) ){
              if( !isset($_["det_{$ide}"]) ) $_["det_{$ide}"] = []; 
              $_["det_{$ide}"] []= "{$atr}_des";
            }
          }
        }
      }
      return $_;
    };

    // carga inicial
    foreach( ( isset($ope['est']) ? $ope['est'] : [ $esq => [ $est => $ope ] ] ) as $esq_ide => $est_lis ){

      foreach( $est_lis as $est_ide => $est_ope ){

        $_[$esq_ide][$est_ide] = $_ite($esq_ide,$est_ide,$est_ope);
      }
    }

    return $_;
  }// Operadores : listado + valores + filtros + columnas + descripciones + cuentas/conteos
  static function lis_ope( string $tip, string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."lis_$tip";
    $_eje = self::$EJE."lis_$tip";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( Dat::ide($dat) );
      $_ide = "_$esq-$est $_ide";
    }
    switch( $tip ){
    // Dato : abm por columnas
    case 'abm':
      foreach( ['lis'] as $e ){ if( !isset($ele[$e]) ) $ele[$e]=[]; }
      $est_ope = self::$Lis['dat'];
      // tipos de dato
      $_cue = [
        'opc'=>[ "Opción", 0 ], 
        'num'=>[ "Número", 0, ['ini'=>'','fin'=>'']], 
        'tex'=>[ "Texto",  0 ], 
        'fec'=>[ "Fecha",  0, ['ini'=>'','fin'=>'']], 
        'obj'=>[ "Objeto",  0 ] 
      ];
      // cuento atributos por tipo
      foreach( $est_ope['atr'] as $atr ){
        $tip_dat = explode('_', Dat::get_est($esq,$est,'atr',$atr)->ope['tip'])[0];
        if( isset($_cue[$tip_dat]) ) $_cue[$tip_dat][1]++;
      }
      // operador : toggles + filtros
      $_ .= "
      <form class='doc_val ide-dat jus-ini'>

        <fieldset class='doc_ope'>
          ".Fig::ico('val_ver-nad',['eti'=>"button",'title'=>"Ocultar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ocu');"])."
          ".Fig::ico('val_ver-tod',['eti'=>"button",'title'=>"Mostrar todas las Columnas", 'onclick'=>"{$_eje}_val(this,'ver');"])."
        </fieldset>

        ".Doc::var('val','ver',[ 
          'nom'=>"Filtrar", 'htm'=> Lis::ope_ver([ 'eje'=>"{$_eje}_ver(this);" ]) 
        ])."

        <fieldset class='doc_ite'>";
        foreach( $_cue as $atr => $val ){ $_ .= "
          <div class='doc_val'>
            ".Fig::ico($atr,[ 'eti'=>"button", 'title'=>"Mostrar las Columnas de {$val[0]}...", 'onclick'=>"{$_eje}_ver(this,'$atr');" ])."
            <span><c class='lis sep'>(</c><n>{$val[1]}</n><c class='lis sep'>)</c></span>
          </div>";
        }$_ .= "
        </fieldset>

      </form>";
      // listado
      $pos = 0; $_ .= "
      <table".Ele::atr( !empty($ele['lis']) ? $ele['lis'] : [] ).">";
      foreach( $est_ope['atr'] as $atr ){
        $pos++;
        $_atr = Dat::get_est($esq,$est,'atr',$atr);
        $dat_tip = explode('_',$_atr->ope['tip'])[0];

        $_var = [];        
        if( isset($_atr->ope['min']) ){ $_var['min'] = $_atr->ope['min']; }
        if( isset($_atr->ope['max']) ){ $_var['max'] = $_atr->ope['max']; }
        if( isset($_atr->ope['step']) ){ $_var['step'] = $_atr->ope['step']; }
        if( isset($_atr->ope['tam']) ){ $_var['tam'] = $_atr->ope['tam']; }
        $htm = "
        <form class='doc_ren esp-bet'>
        
          ".Dat::val_ver([ 'var'=>$_var ],"{$_ide}-{$atr}","{$_eje}_val")."

        </form>";
        $_ .= "
        <tr class='pos ide-{$pos}' data-esq='{$esq}' data-est='{$est}' data-atr='{$atr}'>
          <td data-atr='val'>
            ".Fig::ico( isset($lis->ocu) && in_array($atr,$lis->ocu) ? "ope_ver" : "ope_ocu",[
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
    // Filtros :
    case 'ver': 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Filtros</h3>";
      // filtros : datos + posicion + atributos
      if( isset($ope['val']) ){
        // acumulados
        if( isset($ope['val']['acu']) ){
          // cambio método
          $eje_val = self::$EJE."lis_val";
          $_ .= "
          <form class='ide-acu'>
            <fieldset class='doc_inf doc_ren'>
              <legend>Acumulados</legend>

              ".Doc::var('dat',"ope.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ])."
              
              ".Doc::var('dat',"ope.ver.tod", [ 'ope'=>[ 'id'=>"{$_ide}-tod", 'onchange'=>"{$eje_val}('tod',this);" ] ])."
              
              ".Dat::val_acu($ope['val']['acu'],[
                'ide'=>$_ide, 
                'eje'=>"{$eje_val}('acu'); Dat.lis_ver();",// agrego evento para ejecutar todos los filtros
                'ope'=>[ 'htm_fin'=>"<span class='mar_izq-1'><c>(</c> <n>0</n> <c>)</c></span>" ]
              ]); 
              $_ .= "
            </fieldset>
          </form>";
        }
        // pido operadores de filtro: dato + posicion + fecha
        $_ .= Dat::val_ver([ 'dat'=>$ope['dat'], 'est'=>$ope['est'] ], $_ide, $_eje );

      }// filtros por : cic + gru
      else{
      }
      break;
    // Columnas : ver/ocultar
    case 'atr':
      $lis_val = [];
      foreach( self::$Lis['dat'] as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          // datos de la estructura
          $est_dat = Dat::get_est($esq,$est);
          // contenido : listado de checkbox en formulario
          $htm = "
          <form class='ide-$tip doc_ren jus-ini mar_izq-2'>
            <fieldset class='doc_ope'>
              ".Fig::ico('val_ver-tod',['eti'=>"button", 'title'=>"Mostrar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ver", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."
              ".Fig::ico('val_ver-nad',['eti'=>"button", 'title'=>"Ocultar todas las Columnas", 'class'=>"tam-2",
              'data-val'=>"ocu", 'data-esq'=>$esq, 'data-est'=>$est, 'onclick'=>"{$_eje}_tog(this);"])."                
            </fieldset>";
            foreach( $est_ope['atr'] as $atr ){
              $_atr = Dat::get_est($esq,$est,'atr',$atr);
              $atr_nom = empty($_atr->nom) && $atr=='ide' ? Dat::get_est($esq,$est,'atr','nom')->nom : $_atr->nom ;
              $htm .= Doc::var('val',$atr,[
                'nom'=>"¿{$atr_nom}?", 
                'val'=>!isset($est_ope['atr_ocu']) || !in_array($atr,$est_ope['atr_ocu']),
                'ope'=>[ 'tip'=>'opc_bin', 'id'=>"{$_ide} _{$esq}-{$est}-{$atr}", 
                  'data-esq'=>$esq, 'data-est'=>$est, 'data-val'=>"atr", 'onchange'=>"{$_eje}_tog(this);"
                ] 
              ]);
            } $htm.="
          </form>";
          $lis_val []= [ 'ite'=>$est_dat->nom, 'htm'=>$htm ];
        }              
      }        
      $_ = "        
      <h3 class='mar_arr-0 tex_ali-izq'>Columnas</h3>

      ".Lis::dep($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Descripciones : titulo + detalle
    case 'des':
      $lis_val = [];        
      foreach( self::$Lis['dat'] as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope){
          // ciclos, agrupaciones y lecturas
          if( !empty($est_ope['tit_cic']) || !empty($est_ope['tit_gru']) || !empty($est_ope['det_des']) ){
            $lis_dep = [];
            foreach( ['cic','gru','des'] as $ide ){
              $pre = $ide == 'des' ? 'det' : 'tit';
              if( !empty($est_ope["{$pre}_{$ide}"]) ){ $htm = "
                <form class='ide-{$ide} doc_ren ali-ini mar_izq-2' data-esq='{$esq}' data-est='{$est}'>";
                foreach( $est_ope["{$pre}_{$ide}"] as $atr ){
                  $htm .= Doc::var('val',$atr,[ 
                    'nom'=>"¿".Dat::get_est($esq,$est,'atr',$atr)->nom."?",
                    'ope'=>[ 'tip'=>'opc_bin', 'id'=>"{$_ide}-{$atr}-{$ide}", 'onchange'=>"{$_eje}_tog(this);" ] 
                  ]);
                }$htm .= "
                </form>";
                $lis_dep[] = [ 
                  'ite'=> Dat::get_var('est','lis','ver',$ide)['nom'], 
                  'htm'=> $htm
                ];
              }
            }
            $lis_val[] = [
              'ite'=> Dat::get_est($esq,$est)->nom,
              'lis'=> $lis_dep
            ];
          }
        }
      } 
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Descripciones</h3>

      ".Lis::dep($lis_val,[ 'dep'=>[], 'opc'=>['tog'] ]);

      break;
    // Cuentas : total + porcentaje
    case 'cue':
      $_ = "
      <h3 class='mar_arr-0 tex_ali-izq'>Cuentas</h3>
      ".Lis::dep( Dat::val_cue('dat', $ope['est'], [ 'ide'=>$_ide ]), [ 'dep'=>[], 'opc'=>['tog','ver','cue'] ]);

      break;
    }
    return $_;
  }// Columnas : por atributos
  static function lis_atr( string | array $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    // por muchos      
    if( isset($ope['est']) ){
      
      foreach( self::$Lis['dat'] as $esq => $est_lis ){
        foreach( $est_lis as $est => $est_ope ){
          if( isset($est_ope['est']) ) unset($est_ope['est']);
          $_ .= Dat::lis_atr("{$esq}.{$est}",$est_ope,$ele);
        }
      }
    }
    // por 1: esquema.estructura
    else{
      // proceso estructura de la base
      if( $_val_dat = is_string($dat) ){
        extract( Dat::ide($dat) );      
      }
      $ope_nav = isset($ope['nav']) ? $ope['nav'] : FALSE;
      // cargo datos
      $dat_atr = isset($ope['atr_dat']) ? $ope['atr_dat'] : ( $_val_dat ? Dat::get_est($esq,$est,'atr') : Dat::get_atr($dat) );
      // ocultos por estructura
      $atr_ocu = isset($ope['atr_ocu']) ? $ope['atr_ocu'] : [];
      // genero columnas :
      $ele['cab_ite']['scope'] = "col";
      foreach( ( isset($ope['atr']) ? $ope['atr'] : array_keys($dat_atr) ) as $atr ){
        $ele_ite = $ele['cab_ite'];
        if( $_val_dat ){
          $ele_ite['data-esq'] = $esq;
          $ele_ite['data-est'] = $est;
        } 
        $ele_ite['data-atr'] = $atr;
        if( in_array($atr,$atr_ocu) ) Ele::cla($ele_ite,DIS_OCU);
        // poner enlaces
        $htm = Tex::let( isset($dat_atr[$atr]->nom) ? $dat_atr[$atr]->nom : $atr );
        if( $ope_nav ) $htm = "<a href='".SYS_NAV."{$ope['nav']}' target='_blank'>{$htm}</a>";
        // ...agregar operadores ( iconos )
        $htm_ope = "";
        $_ .= "
        <th".Ele::atr($ele_ite).">
          <p class='let-ide'>{$htm}</p>
          {$htm_ope}
        </th>";
      }         
    }
    return $_;
  }// Descripción : por posición, titulo o detalle
  static function lis_des( string $tip, int $pos, array $ope, array $ele ) : string {
    $_ = "";
    foreach( Lis::val_ite($ope[$tip][$pos]) as $val ){ 
      $_ .= "
      <tr".Ele::atr($ele["{$tip}_ite"]).">
        <td".Ele::atr(Ele::val_jun([ 'data-ope'=>$tip, 'colspan'=>$ope['atr_tot'] ], $ele["{$tip}_val"])).">
          ".( is_array($val) ? Ele::val($val) : "<p class='{$tip} tex_ali-izq'>".Tex::let($val)."</p>" )."
        </td>
      </tr>";
      return $_;
    }
    return $_;
  }// Titulo por : posicion + ciclos + agrupaciones
  static function lis_tit( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( Dat::ide($dat) );        
    }
    // 1 titulo : nombre + detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $ide = $val[1];
      $val = $val[2];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      $htm = "";
      
      if( !empty($htm_val = Dat::get_val('nom',"{$esq}.{$ide}",$val)) ) $htm .= "
      <p class='tit'>".Tex::let($htm_val)."</p>";
      
      if( !empty($htm_val = Dat::get_val('des',"{$esq}.{$ide}",$val)) ) $htm .= "
      <p class='des'>".Tex::let($htm_val)."</p>";
      
      if( in_array('ite_ocu',$opc) ) Ele::cla($ele['ite'],'dis-ocu'); $_ .= "
      <tr".Ele::atr($ele['ite']).">
        <td".Ele::atr($ele['atr']).">{$htm}</td>
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
        Ele::cla($ele['atr'],"anc-100");
      }
      // por ciclos : secuencias
      if( $tip == 'cic' ){
        // acumulo posicion actual, si cambia -> imprimo ciclo
        if( isset($ope['cic_val']) ){

          $val = Dat::get($esq,$est,$val);
          // actualizo ultimo titulo para no repetir por cada item
          foreach( $ope['cic_val'] as $atr => &$pos ){
            
            if( !empty($ide = Dat::get_rel($esq,$est,$atr) ) && $pos != $val->$atr ){

              if( !empty($val->$atr) ){
                
                $_ .= Dat::lis_tit('pos',$dat,[$atr,$ide,$val->$atr],$ope,$ele,$opc);
              }
              self::$Lis['dat'][$esq][$est]['cic_val'][$atr] = $pos = $val->$atr;
            }
          }
        }
      }
      // por agrupaciones : relaciones
      elseif( $tip == 'gru' ){
        if( isset($ope["tit_$tip"]) ){

          foreach( $ope["tit_$tip"] as $atr ){

            if( !empty($ide = Dat::get_rel($esq,$est,$atr)) ){

              foreach( Dat::get($esq,$ide) as $val ){

                $_ .= Dat::lis_tit('pos',$dat,[$atr,$ide,$val],$ope,$ele);
              }
            }
          }
        }
      }        
    }
    return $_;
  }// Fila : valores de la estructura|objetos
  static function lis_ite( string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    $opc_ima = !in_array('ima',$opc);
    $opc_var = in_array('var',$opc);
    $opc_htm = in_array('htm',$opc);
    $atr_ocu = isset($ope['atr_ocu']) ? $ope['atr_ocu'] : FALSE;
    // proceso estructura de la base
    if( is_string($dat) ){
      extract( Dat::ide($dat) );
      $_atr = Dat::get_est($esq,$est,'atr');
      $ele['dat_val']['data-esq'] = $esq;
      $ele['dat_val']['data-est'] = $est;
      $est_ima = Dat::get_est($esq,$est,'opc.ima');
      // recorro atributos y cargo campos
      foreach( $ope['atr'] as $atr ){
        $ele_dat = $ele['dat_val'];
        $ele_dat['data-atr'] = $atr;         
        //ocultos
        if( $atr_ocu && in_array($atr,$atr_ocu) ) Ele::cla($ele_dat,'dis-ocu');
        // contenido
        $ele_val = $ele['val'];

        $ide = "";
        if( $opc_ima && ( !empty($est_ima) && in_array($atr,$est_ima) ) ){
          Ele::cla($ele_val,"tam-5 mar_hor-1");
          $ide = 'ima';
        }
        // variables
        else{
          // adapto estilos por tipo de valor
          if( !empty($_atr[$atr]) ){
            $var_dat = $_atr[$atr]->var_dat;
            $var_val = $_atr[$atr]->var_val;
          }
          elseif( !empty( $_var = Dat::tip($val) ) ){
            $var_dat = $_var->dat;
            $var_val = $_var->val;
          }
          else{
            $var_dat = "val";
            $var_val = "nul";
          }
          // - limito texto vertical
          if( $var_dat == 'tex' ){
            if( $var_dat == 'par' ) Ele::css($ele_val,"max-height:4rem;overflow-y:scroll");
          }
          $ele_dat['data-val_dat'] = $var_dat;
          $ele_dat['data-val_tip'] = $var_val;
          $ide = $opc_var ? 'var' : 'tip';
        }
        $htm = Dat::get_ver($ide,"{$esq}.{$est}.{$atr}",$val,$ele_val);
        if( $ide == "ima" ) $htm = "<div class='doc_val'>$htm</div>";
        $ele_dat['data-val'] = $ide;
        $_ .= "
        <td".Ele::atr( ( $atr_ocu && in_array($atr,$atr_ocu) ) ? Ele::cla($ele_dat,DIS_OCU) : $ele_dat ).">      
          {$htm}
        </td>";
      }
    }
    // por listado del entorno
    else{
      $_val_obj = is_object($val);
      foreach( $ope['atr'] as $ide ){
        // valor
        $dat_val = $_val_obj ? $val->{$ide} : $val[$ide];
        // html
        if( $opc_htm ){
          $htm = $dat_val;
        }
        // elementos
        elseif( is_array( $dat_val ) ){
          $htm = isset($dat_val['htm']) ? $dat_val['htm'] : Ele::val($dat_val);
        }
        // textos
        else{
          $htm = Tex::let($dat_val);
        }
        $ele['dat_val']['data-atr'] = $ide;
        $_.="
        <td".Ele::atr($ele['dat_val']).">
          {$htm}
        </td>";
      }
    }      
    return $_;
  }// Detalle por : posicion + descripciones + lecturas
  static function lis_det( string $tip, string | array $dat, mixed $val, array $ope = [], array $ele = [], array $opc = [] ) : string {
    $_ = "";
    // 1 detalle
    if( $tip == 'pos' ){
      $atr = $val[0];
      $val = $val[1];
      $ele['ite']['data-atr'] = $atr;
      $ele['ite']['data-ide'] = is_object($val) ? ( isset($val->ide) ? $val->ide : ( isset($val->pos) ? $val->pos : '' ) ) : $val;
      if( in_array('ite_ocu',$opc) ) Ele::cla($ele['ite'],'dis-ocu');
      $_ = "
      <tr".Ele::atr($ele['ite']).">
        <td".Ele::atr($ele['atr']).">
          <p class='tex des'>".Tex::let($val->$atr)."</p>
        </td>
      </tr>";
    }
    // por tipos : descripciones + ciclos + agrupaciones
    elseif( isset($ope["det_{$tip}"]) ){
      if( is_string($dat) ){
        extract( Dat::ide($dat) );
        $ele['ite']['data-esq'] = $esq;
        $ele['ite']['data-est'] = $est;
        $val = Dat::get($esq,$est,$val);        
      }

      if( !isset($ele['atr']['colspan']) ){
        $ele['atr']['colspan'] = 1;
        Ele::cla($ele['atr'],"anc-100");
      }

      foreach( $ope["det_{$tip}"] as $atr ){
        $_ .= Dat::lis_det('pos',$dat,[$atr,$val],$ope,$ele,$opc);
      }
    }

    return $_;
  }

  /* Tablero */
  static array $Tab = [
    'dat' => [
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
    ],
    'ope' => [
      'ver' => [ 'ide'=>"ver", 'ico'=>"dat_ver", 'nom'=>"Selección",'des'=>"" ],
      'opc' => [ 'ide'=>"opc", 'ico'=>"opc_bin", 'nom'=>"Opciones", 'des'=>"" ],
      'val' => [ 'ide'=>"val", 'ico'=>"est",     'nom'=>"Datos",    'des'=>"" ],
      'lis' => [ 'ide'=>"lis", 'ico'=>"lis_ite", 'nom'=>"Listado",  'des'=>"" ]
    ]
  ];// sec > ...pos > ...val 
  static function tab( string | array $ide, object $dat = NULL, array $ope = NULL, array $ele = NULL ) : string {
    $_ = "";
    if( is_string($ide) ){
      // tablero por aplicacion: esq.est.atr
      extract( Dat::ide($ide) );
      // convierto parametros por valores ($)
      if( isset($dat) && !empty($ope) ) $ope = Obj::val_lis($ope,$dat);
      // aseguro identificador del tablero
      if( !isset($ope['ide']) && isset($dat->ide) ) $ope['ide'] = $dat->ide;
      if( $atr && class_exists( $cla = Tex::let_pal($esq) ) && method_exists($cla,"tab") ){
        $_ = $cla::tab( $est, $atr, $ope, $ele );
      }
    }
    return $_;
  }// datos: ide + est + dat + val[ pos, ver, opc ] + sec[ ima col ...opc ] + pos[ bor + ima + num + fec + tex ] + ...opc
  static function tab_dat( string $esq, string $est, string $atr, array $ope = [], array $ele = [] ) : array {
    foreach( ['sec','pos'] as $v ){ if( !isset($ele[$v]) ) $ele[$v] = []; }

    $_ = [ 
      'esq' => $esq,
      'tab' => $est,
      'est' => $est = $est.( !empty($atr) ? "_$atr" : $atr ) 
    ];

    if( empty($ele['sec']['class']) || !preg_match("/^tab/",$ele['sec']['class']) ) Ele::cla($ele['sec'],
      "dat tab {$_['esq']} {$_['tab']} {$atr}",'ini'
    );    
    // opciones
    if( !isset($ope['opc']) ) $ope['opc'] = [];
    $opc = $ope['opc'];

    // operador de opciones    
    if( !isset($ele['pos']['eti']) ) $ele['pos']['eti'] = "li";
    $ope['pos_cue'] = 0;// inicializo contador de posiciones
    
    // posicion: bordes
    if( !empty($ope['pos']['bor']) && ( !isset($ele['pos']['class']) || !preg_match("/bor-1/",$ele['pos']['class']) ) ){
      Ele::cla($ele['pos'],"bor-1");
    }
    
    // opciones
    $ope['val_pos_col'] = !empty($ope['pos']['col']) ? $ope['pos']['col'] : FALSE;// color
    $ope['val_pos_ima'] = !empty($ope['pos']['ima']) ? $ope['pos']['ima'] : FALSE;// imagen
    
    // dependencia por patrones del destino
    $ope['val_pos_dep'] = !empty($ope['sec']['pos_dep']);
    
    // ejecucion por contenido
    $ope['pos_eje'] = class_exists( $cla = Tex::let_pal($_['esq']) ) && method_exists($cla,"tab_pos");    
    
    // completo datos por aplicacion
    if( class_exists( $cla) && method_exists($cla,"tab_dat") ) $cla::tab_dat($est,$atr,$ope,$ele);
    
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

  }// operadores : valores + seleccion + posicion + opciones( posicion | secciones )
  static function tab_ope( string $tip, string $dat, array $ope = [], array $ele = [] ) : string {
    $_ = "";
    $_ide = self::$IDE."tab_{$tip}";
    $_eje = self::$EJE."tab_{$tip}";
    $_ope = self::$Tab['ope'];
    // elementos
    if( !isset($ele['ope']) ) $ele['ope'] = [];
    // opciones
    $opc = isset($ope['opc']) ? $ope['opc'] : [];
    // proceso datos del tablero
    extract( Dat::ide($dat) );      
    // por aplicacion : posicion + seleccion
    if( !isset($ope['est']) ) $ope['est'] = [ $esq =>[ $est ] ];
    
    switch( $tip ){
    // Valores : acumulados + sumatorias + cuentas
    case 'val':
      // por acumulados
      $_ .= "
      <form class='ide-acu'>
        <fieldset class='doc_inf doc_ren'>
          <legend>Acumulados</legend>";

          $_ .= Doc::var('dat',"ope.ver.tot", [ 'ope'=>[ 'id'=>"{$_ide}-tot" ] ]);
          
          $_ .= Dat::val_acu($ope['val']['acu'],[ 
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
  
          <fieldset class='doc_inf doc_ren' data-esq='hol' data-est='kin'>
            <legend>Sumatorias del Kin</legend>

            ".Dat::val_sum('hol.kin',$ope['val']['pos']['kin'])."

          </fieldset>          
        </form>";
      }
      // cuentas por estructura
      $_ .= "
      <section class='ide-cue inf pad_hor-2'>
        <h3>Totales por Estructura</h3>

        ".Lis::dep( Dat::val_cue('dat',$ope['est'],['ide'=>$_ide]), [ 
          'dep'=>['class'=>DIS_OCU], 
          'opc'=>['tog','ver','cue'] 
        ])."
        
      </section>";
      break;
    // Operador : Sección + Posición + Opción
    case 'opc':
      // -- controladores por aplicacion
      $_opc_var = function( $_ide, $tip, $esq, $ope, ...$opc ) : string {
        $_ = "";
        $_eje = Tex::let_pal($esq).".tab_{$tip}";
        
        // solo muestro las declaradas en el operador
        $ope_val = isset($ope[$tip]) ? $ope[$tip] : $opc;

        $ope_atr = array_keys($ope_val);

        $ope_var = Dat::get_var($esq,'tab',$tip);
  
        foreach( $ope_atr as $ide ){
  
          if( isset($ope_var[$ide]) ){
  
            $_ .= Doc::var($esq,"tab.$tip.$ide", [
              'val'=>!empty($ope_val[$ide]) ? !empty($ope_val[$ide]) : NULL, 
              'ope'=>[ 'id'=>"{$_ide}-{$ide}", 'onchange'=>"$_eje(this);" ]
            ]);
          }
        } 
        return $_;
      };
      // Secciones        
      if( !empty($ope[$tip_opc = 'sec']) ){
        $ele_ide = self::$IDE."tab-{$tip_opc}";
        $ele_eve = self::$EJE."tab_{$tip_opc}(this);";
        $ele_ope = $ele['ope'];
        Ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".Ele::atr($ele_ope).">
          <fieldset class='doc_inf doc_ren'>
            <legend>Secciones</legend>";
            // operadores globales
            if( !empty($tab_sec = Dat::get_var('est','tab',$tip_opc)) ){ $_ .= "
              <div class='doc_val'>";
              foreach( Dat::get_var('est','tab',$tip_opc) as $ide => $ite ){
                if( isset($ope[$tip_opc][$ide]) ){ 
                  $_ .= Doc::var('dat',"tab.$tip_opc.$ide", [ 
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
      // Posiciones
      if( !empty($ope[$tip_opc = 'pos']) ){ 
        $ele_ide = self::$IDE."tab-{$tip_opc}";
        $ele_eve = self::$EJE."tab_{$tip_opc}(this);";
        $ele_ope = $ele['ope'];
        Ele::cla($ele_ope,"ide-$tip_opc",'ini'); $_ .= "
        <form".Ele::atr($ele_ope).">
          <fieldset class='doc_inf doc_ren'>
            <legend>Posiciones</legend>";
            // bordes            
            $ide = 'bor';
            $_ .= Doc::var('dat',"tab.$tip_opc.$ide",[
              'val'=>isset($ope[$tip_opc][$ide]) ? $ope[$tip_opc][$ide] : 0,
              'ope'=>[ 'id'=>"{$ele_ide}-bor", 'onchange'=>$ele_eve ] 
            ]);                
            // sin acumulados : color de fondo - numero - texto - fecha
            foreach( ['col','num','tex','fec'] as $ide ){
              if( isset($ope[$tip_opc][$ide]) ){
                $_ .= Doc::var('dat',"tab.{$tip_opc}.{$ide}", [
                  'id'=>"{$ele_ide}-{$ide}",
                  'htm'=>Dat::get_opc($ide, $ope['est'], [
                    'val'=>$ope[$tip_opc][$ide], 
                    'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                  ])
                ]);                      
              }
            }
            // con acumulados : imagen de fondo - ( ficha )
            foreach( ['ima'] as $ide ){
              if( isset($ope[$tip_opc][$ide]) ){ $_ .= "
                <div class='doc_ren'>";
                  // vistas por acumulados
                  $_ .= Doc::var('dat',"tab.{$tip_opc}.{$ide}",[
                    'id'=>"{$ele_ide}-{$ide}",
                    'htm'=>Dat::get_opc($ide, $ope['est'], [ 
                      'val'=>$ope[$tip_opc][$ide], 
                      'ope'=>[ 'id'=>"{$ele_ide}-{$ide}", 'onchange'=>$ele_eve ]
                    ])
                  ]);
                  if( isset($ope['val']['acu']) ){ 
                    foreach( array_keys($ope['val']['acu']) as $ite ){
                      $_ .= Doc::var('dat',"tab.$tip_opc.{$ide}_{$ite}", [
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
      // Opciones
      if( !empty($opc) ){
        $tip = "opc";
        $_eje = Tex::let_pal($esq).".tab_{$tip}";
        $_ .= "

        <section class='ide-{$tip}'>";        
        foreach( $opc as $atr ){  
          $htm = "";
          foreach( Dat::get_var($esq,'tab',"{$tip}-{$atr}") as $ide => $val ){
            $val_ope = [
              'val'=>isset($ope[$atr][$ide]) ? $ope[$atr][$ide] : NULL,
              'ope'=>[ 'id'=>"{$_ide}-{$esq}-{$tip}_{$atr}-{$ide}" ]
            ];
            if( isset($val['ope']['tip']) && $val['ope']['tip'] != 'num' ){
              $val_ope['ope']['onchange'] = "$_eje('$atr',this)";
            }
            $htm .= Doc::var($esq,"tab.{$tip}-{$atr}.$ide", $val_ope);
          }          
          // busco datos del operador 
          if( !empty($htm) && !empty($_ope = Dat::get_var($esq,'tab',$tip,$atr)) ){
            $ele_ope = $ele['ope'];
            Ele::cla($ele_ope,"ide-{$esq}_tab_{$tip}-$atr",'ini'); $_ .= "
            <form".Ele::atr($ele_ope).">
              <fieldset class='doc_inf doc_ren'>
                <legend>{$_ope['nom']}</legend>
                  {$htm}
              </fieldset>
            </form>";          
          }
        }$_ .= "
        </section>";
      }
      break;
    // Seleccion : Datos + Posiciones + Fechas
    case 'ver':
      $_ .= Dat::val_ver([ 'dat'=>$ope['dat'], 'est'=>$ope['est'] ], $_ide, $_eje, 'ope_tam' );
      break;
    // Listado : Valores + Columnas + Descripciones
    case 'lis':
      // cargo operador con datos del tablero
      if( !isset($ope['ope']) ) $ope['ope'] = [ "ver", "atr", "des" ];
      if( !isset($ope['opc']) ) $ope['opc'] = [];
      array_push($ope['opc'],"ite_ocu");
      if( !isset($ele['lis']) ) $ele['lis'] = [];
      Ele::cla($ele['lis'],"mar_aba-0");
      $_ = Dat::lis($dat,$ope,$ele);
      break;
    }
    return $_;
  }// posicion
  static function tab_pos( string $esq, string $est, mixed $val, array &$ope, array $ele ) : string {

    // recibo objeto 
    $_dat = FALSE;
    if( is_object( $val_ide = $val ) ){
      $_dat = $val;
      $val_ide = intval($_dat->ide);
    }// o identificador
    elseif( !empty($val) ){
      if( class_exists( $cla_dat = Tex::let_pal($esq)) && method_exists($cla_dat,'_') ){
        $_dat = $cla_dat::_("{$est}",$val);
      }      
    }
    //////////////////////////////////////////////
    // cargo datos ///////////////////////////////
    $e = $ele['pos'];
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
    }// por dependencias estructura
    elseif( $_dat ){
      if( !empty( $dat_est = Dat::get_est($esq,$est,'rel') ) ){

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
    
    //////////////////////////////////////////////
    // .posiciones del tablero principal /////////
    $cla_agr = [];
    // habilito operador
    if( $_dat && !$ope['val_pos_dep'] ){
      $cla_agr []= "ope";
      if( isset($ope['val']['pos']) ){

        $dat_ide = $ope['val']['pos'];

        if( is_array($dat_ide) && isset($dat_ide[$est]) ){
          $dat_ide = is_object($dat_ide[$est]) ? $dat_ide[$est]->ide : $dat_ide[$est];
        }
        // agrego seleccion
        if( $_dat->ide == $dat_ide ) $cla_agr []= '_val-pos _val-pos-bor';
      }
    }// clases adicionales
    if( !empty($cla_agr) ) Ele::cla($e,implode(' ',$cla_agr),'ini');
    
    //////////////////////////////////////////////
    // Contenido html ///////////////////////////
    $htm = "";
    // metodo por aplicacion
    if( $ope['pos_eje'] ){
      $cla = Tex::let_pal($esq);
      $htm = $cla::tab_pos($est,$val,$ope,$e);
    }
    // contenido automático
    if( $_dat && empty($htm) && !isset($e['htm']) ){
      // color de fondo
      if( $ope['val_pos_col'] ){
        $_ide = Dat::ide($ope['val_pos_col']);
        if( isset($e[$dat_ide = "data-{$_ide['esq']}_{$_ide['est']}"]) && !empty( $_dat = Dat::get($_ide['esq'],$_ide['est'],$e[$dat_ide]) ) ){
          $col = Dat::get_ide('col', ...explode('.',$ope['val_pos_col']));          
          if( isset($col['val']) ){
            $col = $col['val'];
            $val = ( $col == 1 && $_dat->{$_ide['atr']} > $col ) ?  0 : $_dat->{$_ide['atr']};
            Ele::cla($e, "fon_col-$col-".( $val === 0 ? $val : Num::val_ran($val,$col) ) );
          }
        }
      }
      // imagen + numero + texto + fecha
      if( !isset($ele['ima']) ) $ele['ima'] = [];
      if( !empty($e['title']) ) $ele['ima']['title'] = FALSE;
      foreach( ['ima','num','tex','fec'] as $tip ){
        if( !empty($ope['pos'][$tip]) ){
          $ide = Dat::ide($ope['pos'][$tip]);
          $htm .= Dat::get_ver($tip, $ope['pos'][$tip], $e["data-{$ide['esq']}_{$ide['est']}"], isset($ele[$tip]) ? $ele[$tip] : [] );
        }
      }
    }
    // cargo contenido por aplicacion o automático
    if( !isset($e['htm']) && !empty($htm) ){
      $e['htm'] = $htm;
    }
    //////////////////////////////////////////////
    // devuelvo posicion /////////////////////////
    $ope['pos_cue']++;// valor de posicion automatica-incremental
    Ele::cla($e,"pos ide-{$ope['pos_cue']}",'ini');    
    return Ele::eti($e);
  }// Secciones
  static function tab_sec( array $ope, array $ide ) : array {
    $_ = [];
    if( isset($ope['sec']) ){
      foreach( $ide as $i ){
        $_[$i] = isset($ope['sec'][$i]) ? ( 
          is_string($ope['sec'][$i]) ? explode(', ',$ope['sec'][$i]) : $ope['sec'][$i] 
        ) : FALSE;
      }
    }
    return $_;
  }  

}