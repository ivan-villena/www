<?php

class fig {

  static string $IDE = "fig-";
  static string $EJE = "fig.";

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {
    $_ = [];    
    global $api_fig;
    $est = "_$ide";
    if( !isset($api_fig->$est) ) $api_fig->$est = dat::est_ini(DAT_ESQ,"fig{$est}");
    $_dat = $api_fig->$est;
    
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

  // controlador
  static function var( string $tip, mixed $dat = NULL, array $ope = [], ...$opc ) : string {
    $_ = "";
    $_ide = self::$IDE."var";
    $_eje = self::$EJE."var";
    
    switch( $tip ){
    // dibujos
    case 'pun':
      break;
    case 'lin':
      break;
    case 'pol':
      break;
    // color
    case 'col':
      $ope['type'] = 'color';
      $ope['value'] = empty($dat) ? $dat : '#000000';
      break;
    }
    if( empty($_) && !empty($ope['type']) ){
      $_ = "<input".ele::atr($ope).">";            
    }
    return $_;
  }    

}