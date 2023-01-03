// WINDOW
'use strict';

// Usuario
class api_usu {

  // getter
  static _( $ide, $val ){
    let $_, $_dat;
    $_ = $_dat = api_app.est('usu',$ide,'dat');

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
    
    return $_;
  }

}