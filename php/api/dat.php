<?php
// Dato : (api).esq.est[ide].atr
class api_dat {
  
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

    $val_est = api_sql::est('val',$est);

    $vis = "_{$est}";

    $val_vis = api_sql::est('val',$vis);

    if( $val_est || $val_vis ){

      $ide = ( $val_vis == 'vis' ) ? $vis : $est;

      $_ = api::dat("{$esq}.{$ide}",$ope);
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

        $_api->dat_est[$esq][$ide] = api_sql::est('ver',"{$esq}_{$ide}",'uni');
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
      $_api->dat_atr[$esq][$est] = api_sql::atr( !empty( api_sql::est('lis',"_{$sql_ide}",'uni') )  ? "_{$sql_ide}" : $sql_ide );

      // cargo operadores del atributo
      $dat = &$_api->dat_atr[$esq][$est];

      if( $dat_atr = app::dat($esq,$est,'atr') ){

        foreach( $dat_atr as $i => $v ){
        
          if( isset($dat[$i]) ) $dat[$i]->var = api_ele::jun($dat[$i]->var, api_obj::nom($v));
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
  // proceso abm : alta , modificacion y baja de registro-objeto
  static function reg( string $est, string $tip, object $dat ) : string {
    $_="";
    $_sql = [];
    // ejecuto transacciones    
    $var_eve = [];
    foreach( $_sql as $est => $ope ){ 

      $eje []= api_sql::reg( $tip, $est, $ope);
    }
    if( !empty($eje) ){
      $_ = api_sql::dec( ...$eje );
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
    elseif( ( $_atr = api_dat::atr($esq,$est,$atr) ) && !empty($_atr->var['dat']) ){        
      $_ = explode('.',$_atr->var['dat'])[1];
    }
    // valido existencia de tabla relacional : "_api.esq_est_atr"
    elseif( !!api_sql::est('val',"{$esq}_{$est}_{$atr}") ){ 
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
      $_atr = api_dat::atr($esq,$est,$atr);
      if( isset($_atr->var['dat']) && !empty($var_dat = $_atr->var['dat']) ){
        $dat = explode('.',$var_dat);
        $_['esq'] = $dat[0];
        $_['est'] = $dat[1];
      }
    }
    // valido dato
    if( !empty( $dat_Val = app::dat($_['esq'],$_['est'],"val.$tip",$dat) ) ){
      $_['ide'] = "{$_['esq']}.{$_['est']}";
      $_['val'] = $dat_Val;
    }
    else{
      $_ = [];
    }
    return $_;
  }
}