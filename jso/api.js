// WINDOW
'use strict';

// Interface
class api {

  log = { 'php':[], 'jso':[] };

  constructor( $dat ){
    
    if( $dat && typeof($dat)=='object' ){     

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }
  
  // getter por estructura
  static _( $ide, $val ){
    let $_=[], $={};

    if( $val !== undefined ){
      switch( $ide ){
      case 'fec': 
        $_ = api_fec.dat($val);
        break;
      default:
        if( typeof($val)=='object' ){
          $_ = $val;
        }
        // por posicion: 1-n
        else if( api_num.tip($val) ){

          $_ = $_api[$ide] && $_api[$ide][$.cod = api_num.val($val)-1] !== undefined ? $_api[$ide][$.cod] : {};
        }
        // por identificador
        else{
          $_ = $_api[$ide] && $_api[$ide][$val] !== undefined ? $_api[$ide][$val] : {};
        }
        break;
      }
    }
    else{
      $_ = $_api[$ide] !== undefined ? $_api[$ide] : [];
    }
    return $_;
  }  
  // getter por : objetos | consultas
  static dat( $dat, $ope, $val='' ){

    let $_=[], $={};

    // por objeto[->propiedad]
    if( $ope && typeof($ope) == 'string' ){

      $.esq = $dat;
      $.est = $ope;
      $_ = $val;

      if( !$val || !api_obj.tip($val) ){
        
        // por clase : metodo estático
        if( $.esq && ( $.cla = eval(`api_${$.esq}`) ) ){

          if( !!$.cla._ ) $_ = $.cla._($.est,$val);
        }
      }
    }  
    // de la documento 
    else if( typeof($dat) == 'string' ){
      
      $_ = ( $.ver = api_ele.ope($dat) ) ? $.ver : [];
    }
    // por estructura : [ {}, [] ]
    else{

      $_ = api_lis.ope('ver',$dat,$ope);
    }
    return $_;
  }
}