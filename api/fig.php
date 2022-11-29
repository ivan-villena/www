<?php

class fig {

  static string $IDE = "fig-";
  static string $EJE = "fig.";

  function __construct(){

    $this->_ico = dat::get('fig_ico', [ 'niv'=>['ide'] ]);
    
  }
  // getter
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

  // icono : .fig_ico.$ide
  static function ico( string $ide, array $ele=[] ) : string {
    $_ = "<span class='fig_ico'></span>";
    $fig_ico = fig::_('ico');
    if( isset($fig_ico[$ide]) ){
      $eti = 'span';      
      if( isset($ele['eti']) ){
        $eti = $ele['eti'];
        unset($ele['eti']);
      }
      if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button"; $_ = "
      <{$eti}".ele::atr(ele::cla($ele,"fig_ico $ide material-icons-outlined",'ini')).">
        {$fig_ico[$ide]->val}
      </{$eti}>";
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