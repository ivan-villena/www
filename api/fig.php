<?php

class api_fig {

  static string $IDE = "api_fig-";
  static string $EJE = "api_fig.";

  function __construct(){
  }// getter
  static function _( string $ide, $val = NULL ) : string | array | object {

    $_ = $_dat = sis_app::dat_est('fig',$ide,'dat');
    
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
      $_ = "<input".api_ele::atr($ope).">";            
    }
    return $_;
  }

  // icono : .fig_ico.$ide
  static function ico( string $ide, array $ele=[] ) : string {
    $_ = "<span class='fig_ico'></span>";    
    $fig_ico = api_fig::_('ico');
    if( isset($fig_ico[$ide]) ){
      $eti = 'span';      
      if( isset($ele['eti']) ){
        $eti = $ele['eti'];
        unset($ele['eti']);
      }
      if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button"; $_ = "
      <{$eti}".api_ele::atr(api_ele::cla($ele,"fig_ico ide-$ide",'ini')).">
        {$fig_ico[$ide]->val}
      </{$eti}>";
    }
    return $_;
  }
  
  // imagen : .fig_ima.$ide
  static function ima( ...$dat ) : string {
    $_ = "";
    // por aplicacion
    if( isset($dat[2]) ){
      $ele = isset($dat[3]) ? $dat[3] : [];
      $_ = api_dat::val('ima', "{$dat[0]}.{$dat[1]}", $dat[2], $ele );
    }
    // por directorio
    else{
      $ele = isset($dat[1]) ? $dat[1] : [];
      $dat = $dat[0];
      // por estilos : bkg
      if( is_array($dat) ){
        $ele = api_ele::val_jun( $dat, $ele );          
      }
      // por directorio : localhost/img/esquema/image
      elseif( is_string($dat)){
        $ima = explode('.',$dat);
        $dat = $ima[0];
        $tip = isset($ima[1]) ? $ima[1] : 'png';
        $dir = SYS_NAV."img/{$dat}";
        api_ele::css( $ele, api_ele::css_fon($dir,['tip'=>$tip]) );
      }
      // etiqueta
      $eti = 'span';
      if( isset($ele['eti']) ){
        $eti = $ele['eti'];
        unset($ele['eti']);
      }// codifico boton
      if( $eti == 'button' && empty($ele['type']) ) $ele['type'] = "button";
      // ide de imagen
      api_ele::cla($ele,"fig_ima",'ini');
      // contenido
      $htm = "";
      if( !empty($ele['htm']) ){
        api_ele::cla($ele,'dis-fle dir-ver jus-cen ali-cen');
        $htm = $ele['htm'];
        unset($ele['htm']);
      }
      $_ = "<{$eti}".api_ele::atr($ele).">{$htm}</{$eti}>";
    }
    return $_;
  }
}