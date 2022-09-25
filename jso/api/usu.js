// WINDOW
'use strict';

// Usuario
class _usu {

  constructor( $dat ){
    
    // datos propios
    if( !!$dat && typeof($dat)=='object' ){

      for( const $atr in $dat ){

        this[$atr] = $dat[$atr];
      }
    }
  }

}