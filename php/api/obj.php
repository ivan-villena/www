<?php
// Objeto : [ ...val ], [ ...nom => val ], { ...atr : val }
class _obj {

  // valor : ()($)atr_ide()
  static function val( object | array $dat, string $val='' ) : string {
    $_ = [];
    $val_arr = _obj::tip($dat) == 'nom';
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
        $dat = _obj::val($ope,$dat);
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
  
        $_ = _dat::get($dat,$ope);
        
      }
    }// convierto : {} => []
    elseif( in_array('nom',$opc) && is_object($dat) && get_class($dat)=='stdClass' ){    
      $_ = _obj::nom($dat);
    }
    return $_;
  }
  
  // combino
  static function jun( array | object $dat, array | object $ope ) : array | object {
          
    $val_obj = is_object($_ = $dat);      

    foreach( $ope as $i => $v ){

      if( $val_obj ? isset($_->$i) : isset($_[$i]) ){

        $val_ite = $val_obj ? $_->$i : $_[$i];

        $val = _obj::tip($v) ? _obj::jun($v,$val_ite) : $val_ite;

        if( $val_obj ){ $_->$i = $val; }else{ $_[$i] = $val; }

      }
      else{

        if( $val_obj ){ $_->$i = $v; }else{ $_[$i] = $v; }          
      }
    }
    return $_;
  }
  // tipos : pos | nom | atr
  static function tip( mixed $dat ) : bool | string {
    
    $_ = FALSE;

    if( _obj::pos($dat) ){

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
        if( empty($ope = _lis::ite($ope)) ){

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
      if( _obj::pos($dat) ){
        
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
        if( empty($ope = _lis::ite($ope)) ){

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