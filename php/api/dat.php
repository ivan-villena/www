<?php
// Dato : (api).esq.est[ide].atr
class _dat {

  // getter : estructura - objeto
  static function get( mixed $dat, mixed $ope = NULL, mixed $val = NULL ) : array | object {

    // objeto->propiedad 
    if( is_string($dat) && is_string($ope) ){

      $esq = $dat;
      $est = $ope;        
      // busco datos por $clase::_($identificador)
      $_ = isset($val) ? $val : new stdClass;
      if( ( !isset($val) || !_obj::tip($val) ) && class_exists($_cla = "_$esq") && method_exists($_cla,'_') ){

        $_ = !isset($val) ? $_cla::_($est) : $_cla::_($est,$val);
      }
    }// estructuras de la base
    else{
      $_ = $dat;
      // datos de la base 
      if( is_string($ide = $dat) ){

        // ejecuto consulta
        $_ = _sql::reg('ver',$ide,isset($ope) ? $ope : []);

        if( isset($ope) ){
          // elimino marcas
          foreach( ['ver','jun','gru','ord','lim'] as $i ){

            if( isset($ope[$i]) ) unset($ope[$i]);
          }
          // busco clave primaria
          if( isset($ope['niv']) && ( empty($ope['niv']) || in_array($ope['niv'],['_uni','_mul']) ) ){
            
            $ope['niv'] = _sql::ind($ide,'ver','pri');
          }
        }
      }
      // resultados y operaciones
      if( isset($ope) && ( is_array($dat) || !isset($_['err']) ) ) _lis::ope($_,$ope);

    }
    return $_;
  }  
  // tipo : dato + valor
  static function tip( mixed $val ) : bool | object {
    $_ = FALSE;
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
    
    global $_api;
    if( isset( $_api->dat_tip[$ide] ) ){
      $_ = $_api->dat_tip[$ide];
    }
    return $_;
  }
  // comparaciones de valores
  static function ver( $dat, string $ide, $val ) : bool {
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
  // identificadores
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
  // inicio estructura: busco datos por vista o tabla
  static function ini( string $esq, string $est, array $ope = [] ) : string | array {
    $_ = [];

    $val_est = _sql::est('val',$est);

    $vis = "_{$est}";

    $val_vis = _sql::est('val',$vis);

    if( $val_est || $val_vis ){

      $ide = ( $val_vis == 'vis' ) ? $vis : $est;

      $_ = _dat::get("{$esq}.{$ide}",$ope);
    }

    return $_;
  }
  // estructura : datos + operadores
  static function est( string $esq, string $ide, mixed $tip = NULL, mixed $ope = NULL ) : mixed {
    $_ = [];
    global $_api;
    // cargo una estructura
    if( !isset($tip) ){

      if( !isset($_api->dat_est[$esq][$ide]) ){

        $_api->dat_est[$esq][$ide] = _sql::est('ver',"{$esq}_{$ide}",'uni');
      }
      $_ = $_api->dat_est[$esq][$ide];
    }
    else{
      switch( $tip ){
      }
    }
    return $_;
  }
  // atributo : datos + tipo + variable
  static function atr( string $esq, string $est, mixed $ide = NULL, string $tip = NULL, mixed $ope = NULL ) : mixed {
    $_ = [];
    global $_api;            
    // cargo atributos de la estructura
    if( !isset($_api->dat_atr[$esq][$est]) ){
      
      // busco atributos de una vista ( si existe ) o de una tabla
      $sql_ide = "{$esq}_{$est}";
      $_api->dat_atr[$esq][$est] = _sql::atr( !empty( _sql::est('lis',"_{$sql_ide}",'uni') )  ? "_{$sql_ide}" : $sql_ide );

      // cargo operadores del atributo
      $dat = &$_api->dat_atr[$esq][$est];

      if( $dat_atr = _app::dat($esq,$est,'atr') ){

        foreach( $dat_atr as $i => $v ){
        
          if( isset($dat[$i]) ) $dat[$i]->var = _ele::jun($dat[$i]->var, _obj::nom($v));
        }
      }
    }
    $_ = $_api->dat_atr[$esq][$est];
    // devuelvo todos los atributos
    if( isset($ide) ){
      $_atr = $_;
      $_ = [];
      // devuelvo 1-n atributos
      if( !isset($tip) ){
        // uno
        if( is_string($ide) ){

          if( isset($_atr[$ide]) ) $_ = $_atr[$ide];
        }// muchos
        else{
          foreach( $ide as $atr ){ 

            if( isset($_atr[$atr]) ) $_[$atr] = $_atr[$atr];
          }
        }
      }
      else{
        switch( $tip ){
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
        $ide = _dat::ide($dat);
        $_ = _dat::atr($ide['esq'],$ide['est']);
      }
      // listado variable por objeto
      else{
        foreach( $dat as $ite ){
          // del 1° objeto: cargo atributos
          foreach( $ite as $ide => $val ){ 
            $atr = new stdClass;
            $atr->ide = $ide;
            $atr->nom = $ide;
            $atr->var = _dat::tip($val);
            $_ [$ide] = $atr;
          }
          break;
        }        
      }
    }
    return $_;
  }// cuento columnas totales
  static function atr_cue( string | array $dat, array $ope=[] ) : int {
    $_ = 0;
    
    // atributos
    if( isset($ope['atr']) ){
      
      $_ = count($ope['atr']);
    }
    // joins
    elseif( isset($ope['est']) ){
      foreach( $ope['est'] as $esq => $est_lis ){  
        foreach( $est_lis as $est ){
          $dat_est = _app_est::dat($esq,$est,$ope);
          $_ += count($dat_est['atr']);
        }
      }
    }// 1 estructura de la base
    elseif( !( $obj_tip = _obj::tip($dat) ) ){

      $ide = _dat::ide($dat);

      $dat_est = _app_est::dat($ide['esq'],$ide['est']);

      $_ = isset($dat_est['atr']) ? count($dat_est['atr']) : 0;

    }
    // por listado                    
    elseif( $obj_tip == 'pos' ){

      foreach( $dat as $ite ){

        foreach( $ite as $val ){ 
          $_ ++; 
        }
        break;
      }
    }
    return $_;
  }
  // proceso abm : alta , modificacion y baja de registro-objeto
  static function reg( string $est, string $tip, object $dat ) : string {
    $_="";
    $_sql = [];
    // ejecuto transacciones    
    $var_eve = [];
    foreach( $_sql as $est => $ope ){ 

      $eje []= _sql::reg( $tip, $est, $ope);
    }
    if( !empty($eje) ){
      $_ = _sql::dec( ...$eje );
    }
    return $_;
  }
  // relaciones : esq.est_atr | api.dat_atr[ide].dat
  static function rel( string $esq, string $est, string $atr ) : string {
    $_ = '';
    // armo identificador por nombre de estructura + atributo
    if( $atr == 'ide' ){
      $_ = $est;
    }
    // parametrizado en : $_app.dat_atr
    elseif( ( $_atr = _dat::atr($esq,$est,$atr) ) && !empty($_atr->var['dat']) ){        
      $_ = explode('.',$_atr->var['dat'])[1];
    }
    // valido existencia de tabla relacional : "_api.esq_est_atr"
    elseif( !!_sql::est('val',"{$esq}_{$est}_{$atr}") ){ 
      $_ = "{$est}_{$atr}";
    }
    else{
      $_ = $atr;
    }
    return $_;
  }
  // seleccion : imagen, color...
  static function opc( string $tip, string $esq, string $est, string $atr = NULL, mixed $dat = NULL ) : array {
    // dato
    $_ = [ 'esq' => $esq, 'est' => $est ];
    if( !empty($atr) ){
      // armo identificador
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
    if( !empty( $dat_Val = _app::dat($_['esq'],$_['est'],"val.$tip",$dat) ) ){
      $_['ide'] = "{$_['esq']}.{$_['est']}";
      $_['val'] = $dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;
  }
}