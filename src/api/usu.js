// WINDOW
'use strict';

// Usuario
class usu {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
  }

}