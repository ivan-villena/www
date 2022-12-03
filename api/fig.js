// WINDOW
'use strict';

class fig {

  constructor( $dat = {} ){
    
    for( const $atr in $dat ){ this[$atr] = $dat[$atr]; }
    
  }// getter
  static _( $ide, $val ){
    let $_ = [], $_dat, $_est = `_${$ide}`;
    
    // aseguro datos
    if( !$api_fig || $api_fig[$_est] === undefined ){
      // ...pido datos
    }
    $_dat = $api_fig[$_est];

    if( !!($val) ){
      $_ = $val;
      if( typeof($val) != 'object' ){
        switch( $ide ){
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

  // controladres
  static var( $tip, $dat, $ope, ...$opc ){

    let $_ = ""; $={};

    switch( $tip ){
    }

    return $_;      
  }

}