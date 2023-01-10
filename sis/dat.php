<?php

class sis_dat {

  // Tipos de dato y de valor
  public array $_tip;

  // Estructuras de datos
  public array $_est;

  // Variables
  public array $_var;
  public array $_var_ope = [];
  public array $_var_ide = [];

  // Inicio
  function __construct(){

    $this->_tip = sis_dat::get('app_tip',['niv'=>["ide"],'ele'=>["ope"]]);
  }

  /* Consulta: objeto | consulta */
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
    global $sis_dat;    
    return isset($sis_dat->_tip[$ide]) ? $sis_dat->_tip[$ide] : FALSE;
  }

  /* Estructura : datos + operadores   */
  static function est( string $esq, string $ide, mixed $ope = NULL, mixed $dat = NULL ) : mixed {
    $_ = [];
    global $sis_dat;
    // cargo una estructura
    if( !isset($ope) ){

      if( !isset($sis_dat->_est[$esq][$ide]) ){        
        
        // Cargo Estructura
        $sis_dat->_est[$esq][$ide] = sis_sql::est('ver',$sql_est = "{$esq}_{$ide}",'uni');
        if( empty($sis_dat->_est[$esq][$ide]) ){
          $sis_dat->_est[$esq][$ide] = new stdClass;
        }// de la Base
        else{
          $sql_vis = "_{$sql_est}";
        }
        // ...Propiedades extendidas
        $_est = sis_dat::get('app_est',[ 'ver'=>"`esq`='{$esq}' AND `ide`='{$ide}'", 'ele'=>["ope"], 'opc'=>"uni" ]);
        // si existe la estructura
        if( isset($_est->ope) ){
          // Propiedades
          foreach( $_est->ope as $ope_ide => $ope ){
            $sis_dat->_est[$esq][$ide]->$ope_ide = $ope;
          }
        }
        // Estructura de la base
        if( isset($sql_vis) ){
          // Atributos/columnas: de una vista ( si existe ) o de la tabla
          $sis_dat->_est[$esq][$ide]->atr = sis_sql::atr( !empty( sis_sql::est('lis',"_{$sql_est}",'uni') )  ? "_{$sql_est}" : $sql_est );
          if( isset($_est->ope['atr']) ){
            // cargo variables del operador
            foreach( $_est->ope['atr'] as $atr_ide => $atr_var ){
              $sis_dat->_est[$esq][$ide]->atr[$atr_ide]->var = api_ele::val_jun(
                $sis_dat->_est[$esq][$ide]->atr[$atr_ide]->var, $atr_var
              );
            }
          }
          // Datos/registros: de una vista ( si existe ) o de la tabla
          $est_ope = isset($sis_dat->_est[$esq][$ide]->dat) ? $sis_dat->_est[$esq][$ide]->dat : [];
          $sis_dat->_est[$esq][$ide]->dat = sis_dat::get( sis_sql::est('val',$sql_vis) == 'vis' ? $sql_vis : $sql_est, $est_ope );          
        }
      }
      // devuelvo estructura completa: esq + ide + nom + atr + dat + ...ope
      $_ = $sis_dat->_est[$esq][$ide];
    }
    // cargo operadores
    elseif( is_string($ope) ){
      // cargo propiedad
      $ope_atr = explode('.',$ope);
      $_ = api_obj::val_dat(sis_dat::est($esq,$ide),$ope_atr);
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
          $_ = api_obj::val( sis_dat::get($esq,$ide,$dat), $_ );
          break;
        }        
      }      
    }
    return $_;
  }// Identificadores
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
  }// Atributos desde listado o desde la base
  static function est_atr( string | array $dat, string $ope = "" ) : array {
    $_ = [];
    if( empty($ope) ){
      // de la base
      if( is_string($dat) ){
        $ide = sis_dat::est_ide($dat);
        $_ = sis_dat::est($ide['esq'],$ide['est'],'atr');
      }
      // listado variable por objeto
      else{
        foreach( $dat as $ite ){
          // del 1° objeto: cargo atributos
          foreach( $ite as $ide => $val ){ 
            $atr = new stdClass;
            $atr->ide = $ide;
            $atr->nom = $ide;
            $atr->var = sis_dat::tip($val);
            $_ [$ide] = $atr;
          }
          break;
        }        
      }
    }
    return $_;
  }// Relaciones : esq.est_atr | api.dat_atr[ide].dat
  static function est_rel( string $esq, string $est, string $atr ) : string {
    $_ = '';
    // armo identificador por nombre de estructura + atributo
    if( $atr == 'ide' ){
      $_ = $est;
    }
    // parametrizado en : $sis_dat.dat_atr
    elseif( ( $_atr = sis_dat::est($esq,$est,'atr',$atr) ) && !empty($_atr->var['dat']) ){        
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

  /* Variable */
  static function var( string $esq, string $dat='', string $val='', string $ide='' ) : array {
    
    $_ = [];
    global $sis_dat;
    
    // cargo todas las estructuras del esquema
    if( empty($dat) ){
      if( !isset($sis_dat->_var[$esq]) ){
        $sis_dat->_var[$esq] = sis_dat::get('app_var',[
          'ver'=>"`esq`='{$esq}'", 'niv'=>['dat','val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo por agrupacion
    elseif( empty($val) ){
      if( !isset($sis_dat->_var[$esq][$dat]) ){
        $sis_dat->_var[$esq][$dat] = sis_dat::get('app_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}'", 'niv'=>['val','ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }// cargo uno
    else{
      if( !isset($sis_dat->_var[$esq][$dat][$val]) ){
        $sis_dat->_var[$esq][$dat][$val] = sis_dat::get('app_var',[
          'ver'=>"`esq`='{$esq}' AND `dat`='{$dat}' AND `val`='{$val}'", 'niv'=>['ide'], 'ele'=>["atr"], 'red'=>"atr"
        ]);
      }
    }

    if( !empty($ide) ){
      $_ = isset($sis_dat->_var[$esq][$dat][$val][$ide]) ? $sis_dat->_var[$esq][$dat][$val][$ide] : [];
    }
    elseif( !empty($val) ){
      $_ = isset($sis_dat->_var[$esq][$dat][$val]) ? $sis_dat->_var[$esq][$dat][$val] : [];
    }
    elseif( !empty($dat) ){
      $_ = isset($sis_dat->_var[$esq][$dat]) ? $sis_dat->_var[$esq][$dat] : [];
    }
    else{
      $_ = isset($sis_dat->_var[$esq]) ? $sis_dat->_var[$esq] : [];
    }

    return $_;
  }// Selector de operadores : select > ...option    
  static function var_ope( mixed $dat = NULL, mixed $ope = NULL, ...$opc ) : mixed {

    global $sis_dat;

    if( !isset($sis_dat->_var_ope[$dat[0]][$dat[1]]) ){

      $_dat = sis_dat::get( 'app_tip_ope', [ 'ver'=>[ ['tip','==',$dat[0]], ['dat','==',$dat[1]] ]] );

      $sis_dat->_var_ope[$dat[0]][$dat[1]] = api_opc::lis( $_dat, $ope, ...$opc);
    }

    return $sis_dat->_var_ope[$dat[0]][$dat[1]];

  }// id secuencial
  static function var_ide( string $ope ) : string {
    global $sis_dat;

    if( !isset($sis_dat->_var_ide[$ope]) ) $sis_dat->_var_ide[$ope] = 0;

    $sis_dat->_var_ide[$ope]++;

    return $sis_dat->_var_ide[$ope];
  }

}