// WINDOW
'use strict';

// Interface
class _api {

  log = { 'php':[], 'jso':[] };

  constructor( $dat ){
    
    if( $dat && typeof($dat)=='object' ){     

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }
  
  // getter
  static _( $ide, $val ){
    let $_=[], $={};

    if( $val !== undefined ){
      switch( $ide ){
      case 'fec': 
        $_ = _fec.dat($val);
        break;
      default:
        if( typeof($val)=='object' ){
          $_ = $val;
        }
        // por posicion: 1-n
        else if( _num.tip($val) ){

          $_ = $_api[$ide] && $_api[$ide][$.cod = _num.val($val)-1] !== undefined ? $_api[$ide][$.cod] : {};
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
}