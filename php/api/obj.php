<?php
// Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
class api_obj {

  // valor : ()($)atr_ide()
  static function val( object | array $dat, string $val='' ) : string {
    $_ = [];
    $val_arr = api_obj::tip($dat) == 'nom';
    foreach( explode(' ',$val) as $pal ){ 
      $let=[];
      foreach( explode('()',$pal) as $cad ){ 
        $sep = $cad;
        if( substr($cad,0,3)=='($)' ){ $sep='';
          $ide=substr($cad,3);
          if( $val_arr ){
            if( isset($dat[$ide]) ){ $sep = $dat[$ide]; }
          }else{
            if( isset($dat->$ide) ){ $sep = $dat->$ide; }
          }
        }
        $let[]=$sep;
      }
      $_[] = implode('',$let);
    }
    $_ = implode(' ',$_);
    return $_;
  }
  // convierto a string: {} => ""
  static function cod( object | array | string $dat ) : string {
    $_ = [];
    
    if( is_array($dat) || is_object($dat) ){
      // https://www.php.net/manual/es/function.json-encode.php
      // https://www.php.net/manual/es/json.constants.php
      $_ = json_encode( $dat, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PRETTY_PRINT );
    }
    return $_;
  }
  // convierto a objeto : "" => {}/[]
  static function dec( object | array | string $dat, array | object $ope = NULL, ...$opc ){
    $_ = $dat;
    // convierto : "" => {}
    if( is_string($dat) ){  
      // busco : ()($)atributo-valor()
      if( !empty($ope) && preg_match("/\(\)\(\$\).+\(\)/",$dat) ){
        $dat = api_obj::val($ope,$dat);
      }
      // json : { "atr": val, ... } || [ val, val, ... ]
      if( preg_match("/^({|\[).*(}|\])$/",$dat) ){ 
        // https://www.php.net/manual/es/function.json-decode
        // https://www.php.net/manual/es/json.constants.php
        $_ = json_decode($dat, in_array('nom',$opc) ? TRUE : FALSE, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK );
  
      }
      // valores textuales : ('v_1','v_2','v_3')
      elseif( preg_match("/^\('*.*'*\)$/",$dat) ){
        
        $_ = preg_match("/','/",$dat) ? explode("','",substr($dat,1,-1 )) : [ trim(substr($dat,1,-1 )) ] ;
  
      }
      // elemento del documento : "a_1(=)v_1(,,)a_2(=)v_2"
      elseif( preg_match("/\(,,\)/",$dat) && preg_match("/\(=\)/",$dat) ){
  
        foreach( explode('(,,)',$dat) as $v ){ 
  
          $eti = explode('(=)',$v);
  
          $_[$eti[0]] = $eti[1];
        }
      }
      // esquema.estructura : tabla de la base
      elseif( preg_match("/[A-Za-z0-9_]+\.[A-Za-z0-9_]+$/",$dat) ){
  
        $_ = api::dat($dat,$ope);
        
      }
    }// convierto : {} => []
    elseif( in_array('nom',$opc) && is_object($dat) && get_class($dat)=='stdClass' ){    
      $_ = api_obj::nom($dat);
    }
    return $_;
  }
  // recorro atributos y convierto valor en caso de tener alguno
  static function var( array | object $obj, array | object $dat ) : array | object {
    // iteraciones
    foreach( $obj as &$val ){
      $val = api_obj::var_ite($val,$dat);
    }
    return $obj;
  }
  static function var_ite( mixed $val, array | object $dat ) : mixed {

    if( is_array($val) || is_object($val) ){
      foreach( $val as $var_ide => $val_atr ){
        $val[$var_ide] = api_obj::var_ite($val_atr,$dat);
      }
    }
    elseif( is_string($val) ){ // && preg_match("/\(\)\(\$\).+\(\)/",$val)      
      $val = api_obj::val($dat,$val);
    }

    return $val;
  }
  // combino por contenido
  static function jun( array | object $dat, array | object $ope, ...$opc ) : array | object {
    // devuelvo original  
    $val_obj = is_object($_ = $dat);
    $opc_act = in_array('mod',$opc);
    // recorro y agrego atributos del secundario
    foreach( $ope as $atr => $val ){
      // si tienen el mismo atributo
      if( $opc_act && ( $val_obj ? isset($_->$atr) : isset($_[$atr]) ) ){
        // valor del original
        $val_ite = $val_obj ? $_->$atr : $_[$atr];
        // combino objetos o reemplazo
        $val = ( api_obj::tip($val) && api_obj::tip($val_ite) ) ? api_obj::jun($val_ite,$val,...$opc) : $val;
      }
      // agrego / actualizo atributo
      if( $val_obj ){ $_->$atr = $val; }else{ $_[$atr] = $val; }
    }
    return $_;
  }
  // tipos : pos | nom | atr
  static function tip( mixed $dat ) : bool | string {
    
    $_ = FALSE;

    if( api_obj::pos($dat) ){

      $_ = 'pos';
    }
    elseif( is_array($dat) ){

      $_ = 'nom';
    }
    elseif( is_object($dat) ){

      $_ = get_class($dat) == 'stdClass' ? 'atr' : 'atr';
    }

    return $_;
  }  
  // posicion : [ # => $$ ]
  static function pos( mixed $dat, string $tip = NULL, mixed $ope = NULL ) : bool | array {
    $_ = [];
    if( !isset($tip) ){
      // valido tipo : []
      $_ = is_array($dat) && array_keys($dat) === range( 0, count( array_values($dat) ) - 1 );
    }
    else{
      switch( $tip ){
      }
    }
    return $_;
  }    
  // nombre : [ ..."" => $$ ]
  static function nom( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
    $_ = $dat;
    if( empty($tip) ){
      if( is_object($dat) && get_class($dat)=='stdClass' ){
        $_ = [];
        foreach( $dat as $atr => $val ){
          $_[$atr] = $val;
        }
      }
    }else{
      switch( $tip ){
      case 'ver':
        $_ = [];
        if( empty($ope = api_lis::ite($ope)) ){

          foreach( $dat as $atr => $val ){ $_[$atr] = $val; }
        }
        elseif( is_object($dat) ){

          foreach( $ope as $atr ){ if( isset($dat->$atr) ) $_[$atr] = $dat->$atr; }
        }
        else{
          foreach( $ope as $atr ){ if( isset($dat[$atr]) ) $_[$atr] = $dat[$atr]; }
        }
        break;
      }
    }
    return $_;
  }
  // objeto : { ..."" : $$ }
  static function atr( array | object $dat, string $tip = NULL, array $ope=[] ) : array | object {
    $_ = $dat;

    if( !isset($tip) ){
      // listado de objetos
      if( api_obj::pos($dat) ){
        
        $_ = array_map( function($i){ return clone $i; }, $dat );
      }
      // creo un objeto desde un array
      elseif( is_array($dat) ){
        $_ = new stdClass();
        foreach( $dat as $atr => $val ){
          $_->$atr = $val;
        }
      }
      // copio objeto
      elseif( is_object($dat) ){
        $_ = clone $dat;
      }
    }
    else{
      switch( $tip ){
      case 'ver':
        $_ = new stdClass();
        if( empty($ope = api_lis::ite($ope)) ){

          foreach( $dat as $atr => $val ){ $_->$atr = $val; }
        }
        elseif( is_object($dat) ){

          foreach( $ope as $atr ){ if( isset($dat->$atr) ) $_->$atr = $dat->$atr; }
        }
        else{
          foreach( $ope as $atr ){ if( isset($dat[$atr]) ) $_->$atr = $dat[$atr]; }
        }
        break;
      }
    }
    return $_;
  }
}