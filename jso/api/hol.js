// WINDOW
'use strict';

// Holon : ns.ani.lun.dia:kin
class _hol {

  // getter
  static _( $ide, $val ){
    let 
      $_ = [], 
      $est = `hol_${$ide}`;
    
    if( $_api[$est] === undefined ){
      // pido datos
      // .. vuelvo a llamar esta funcion
    }
    if( !!($val) ){
      $_ = $val;
      switch( $ide ){
      case 'fec':
        // calculo fecha
        break;
      default:
        if( typeof($val) != 'object' ){

          if( Number($val) ) $val = parseInt($val)-1;

          $_ = $_api[$est] && !!($_api[$est][$val]) ? $_api[$est][$val] : {};
        }
        break;
      }
    }
    else{
      $_ = $_api[$est] ? $_api[$est] : [];
    }
    return $_;
  }
  // imagen : img/hol
  static ima( $est, $dat, $ele ){

    return _app.ima('hol',`${$est}`,$dat,$ele);
  }
}