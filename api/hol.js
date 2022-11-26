// WINDOW
'use strict';

// Holon : ns.ani.lun.dia:kin
class hol {

  constructor( $dat ){
    
    if( $dat && typeof($dat)=='object' ){     

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }
  // getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $est = `_${$ide}`;
    
    if( !$api_hol || $api_hol[$est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_hol[$est];

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
        case 'fec':
          // ...calculo fecha
          break;
        default:
          if( Number($val) ) $val = parseInt($val)-1;
          if( $_dat && !!$_dat[$val] ) $_ = $_dat[$val];
          break;
        }
      }
    }
    else{
      $_ = $_dat ? $_dat : [];
    }
    return $_;
  }

  // imagen : img/hol
  static ima( $est, $dat, $ele ){

    return arc.ima('hol',`${$est}`,$dat,$ele);
  }
  
}